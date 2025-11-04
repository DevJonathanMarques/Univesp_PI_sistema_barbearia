<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$data = $_POST['date'] ?? '';
$hora = $_POST['time'] ?? '';
$barbeiro_id = $_POST['barbeiro_id'] ?? '';
$servico_id = $_POST['servico_id'] ?? '';
$cliente_id = $_SESSION['id'] ?? null;

if (!$data || !$hora || !$barbeiro_id || !$servico_id || !$cliente_id) {
    echo "Dados incompletos para salvar o agendamento.";
    exit;
}

// Recuperar a duração do serviço
$stmt_duracao = $conn->prepare("SELECT duracao FROM servicos WHERE servico_id = ?");
$stmt_duracao->bind_param("i", $servico_id);
$stmt_duracao->execute();
$resultado = $stmt_duracao->get_result();

if ($resultado->num_rows === 0) {
    echo "Serviço não encontrado.";
    exit;
}

$duracao = (int) $resultado->fetch_assoc()['duracao'];

$data_hora_agendamento_inicio = $data . ' ' . $hora;
$inicio_agendamento = new DateTime($data_hora_agendamento_inicio);
$fim_agendamento = clone $inicio_agendamento;
$fim_agendamento->add(new DateInterval("PT{$duracao}M"));

$inicio_agendamento_formatado = $inicio_agendamento->format('Y-m-d H:i:s');
$fim_agendamento_formatado = $fim_agendamento->format('Y-m-d H:i:s');

// ===== Verificação de folga =====
$dia_da_semana = date('l', strtotime($data));
$dias_semana_map = [
    'Monday' => 'segunda',
    'Tuesday' => 'terca',
    'Wednesday' => 'quarta',
    'Thursday' => 'quinta',
    'Friday' => 'sexta',
    'Saturday' => 'sabado',
    'Sunday' => 'domingo'
];
$dia_folga = $dias_semana_map[$dia_da_semana] ?? '';

$stmt_folga = $conn->prepare("SELECT dias_folga FROM expediente_funcionario WHERE funcionario_id = ?");
$stmt_folga->bind_param("i", $barbeiro_id);
$stmt_folga->execute();
$result_folga = $stmt_folga->get_result();

if ($result_folga->num_rows > 0) {
    $expediente = $result_folga->fetch_assoc();
    $dias_folga = explode(",", $expediente['dias_folga']);
    if (in_array($dia_folga, $dias_folga)) {
        echo "O barbeiro está de folga neste dia.";
        $stmt_folga->close();
        exit;
    }
}
$stmt_folga->close();

// ===== Verificação de expediente =====
$stmt_expediente = $conn->prepare("SELECT hora_inicio, hora_fim FROM expediente_funcionario WHERE funcionario_id = ?");
$stmt_expediente->bind_param("i", $barbeiro_id);
$stmt_expediente->execute();
$result_expediente = $stmt_expediente->get_result();

if ($result_expediente->num_rows > 0) {
    $expediente = $result_expediente->fetch_assoc();
    $hora_inicio_expediente = new DateTime($data . ' ' . $expediente['hora_inicio']);
    $hora_fim_expediente = new DateTime($data . ' ' . $expediente['hora_fim']);

    if ($inicio_agendamento < $hora_inicio_expediente || $fim_agendamento > $hora_fim_expediente) {
        echo "O horário selecionado está fora do expediente do barbeiro.";
        $stmt_expediente->close();
        $conn->close();
        exit;
    }
}
$stmt_expediente->close();

// ===== Verificação de conflito com agendamentos =====
$stmt_check = $conn->prepare(
    "SELECT 1 FROM agendamentos 
     WHERE funcionario_id = ? 
     AND (
         (data_agendamento >= ? AND data_agendamento < ?)
         OR 
         (DATE_ADD(data_agendamento, INTERVAL (SELECT duracao FROM servicos WHERE servico_id = ?) MINUTE) > ? 
          AND data_agendamento < ?)
     )"
);
$stmt_check->bind_param("issssi", $barbeiro_id, $inicio_agendamento_formatado, $fim_agendamento_formatado, $servico_id, $fim_agendamento_formatado, $inicio_agendamento_formatado);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    echo "Este horário já foi agendado. Por favor, escolha outro.";
    $stmt_check->close();
    $conn->close();
    exit;
}
$stmt_check->close();

// ===== Verificação de conflito com horários indisponíveis =====
$stmt_indisp = $conn->prepare("SELECT hora FROM horarios_indisponiveis WHERE funcionario_id = ? AND data = ?");
$stmt_indisp->bind_param("is", $barbeiro_id, $data);
$stmt_indisp->execute();
$result_indisp = $stmt_indisp->get_result();

while ($row = $result_indisp->fetch_assoc()) {
    $inicio_ind = new DateTime("$data {$row['hora']}");
    $fim_ind = clone $inicio_ind;
    $fim_ind->add(new DateInterval('PT1H'));

    if ($inicio_agendamento < $fim_ind && $fim_agendamento > $inicio_ind) {
        echo "Este horário está marcado como indisponível pelo barbeiro.";
        $stmt_indisp->close();
        $conn->close();
        exit;
    }
}
$stmt_indisp->close();

// ===== Inserção no banco =====
$stmt = $conn->prepare("INSERT INTO agendamentos (cliente_id, funcionario_id, servico_id, data_agendamento) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $cliente_id, $barbeiro_id, $servico_id, $inicio_agendamento_formatado);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    include 'pagamento.php';
    header("Location: ../confirmado.php");
    exit;
} else {
    echo "Erro ao salvar agendamento: " . $stmt->error;
    $stmt->close();
    $conn->close();
}
?>
