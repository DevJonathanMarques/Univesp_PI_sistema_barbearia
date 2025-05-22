<?php
include 'conexao.php';

function buscarHorariosDisponiveis($conn, $barbeiro_id, $data, $servico_id) {
    // Obter dia da semana
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

    // Obter expediente do funcionário
    $stmt = $conn->prepare("SELECT hora_inicio, hora_fim, dias_folga FROM expediente_funcionario WHERE funcionario_id = ?");
    $stmt->bind_param("i", $barbeiro_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) return [];

    $expediente = $result->fetch_assoc();
    $stmt->close();

    $dias_folga = explode(",", $expediente['dias_folga']);
    if (in_array($dia_folga, $dias_folga)) return []; // Folga

    $hora_inicio = $expediente['hora_inicio'];
    $hora_fim = $expediente['hora_fim'];

    // Obter duração do serviço
    $stmt = $conn->prepare("SELECT duracao FROM servicos WHERE servico_id = ?");
    $stmt->bind_param("i", $servico_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) return [];

    $duracao_servico = (int) $result->fetch_assoc()['duracao'];
    $stmt->close();

    // Gerar horários com base no expediente
    $horarios = [];
    $inicio = new DateTime($hora_inicio);
    $fim = new DateTime($hora_fim);
    while ($inicio < $fim) {
        $horarios[] = $inicio->format("H:i");
        $inicio->add(new DateInterval('PT30M'));
    }

    // Buscar agendamentos do funcionário para o dia
    $stmt = $conn->prepare("SELECT data_agendamento, s.duracao 
                            FROM agendamentos a 
                            JOIN servicos s ON a.servico_id = s.servico_id
                            WHERE funcionario_id = ? AND DATE(data_agendamento) = ?");
    $stmt->bind_param("is", $barbeiro_id, $data);
    $stmt->execute();
    $result = $stmt->get_result();

    $intervalos_ocupados = [];
    while ($row = $result->fetch_assoc()) {
        $inicio_agendado = new DateTime($row['data_agendamento']);
        $fim_agendado = clone $inicio_agendado;
        $fim_agendado->add(new DateInterval("PT{$row['duracao']}M"));
        $intervalos_ocupados[] = [$inicio_agendado, $fim_agendado];
    }
    $stmt->close();

    // Buscar horários indisponíveis e transformar em intervalos de 1 hora
    $stmt = $conn->prepare("SELECT hora FROM horarios_indisponiveis WHERE funcionario_id = ? AND data = ?");
    $stmt->bind_param("is", $barbeiro_id, $data);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $inicio_ind = new DateTime("{$data} {$row['hora']}");
        $fim_ind = clone $inicio_ind;
        $fim_ind->add(new DateInterval('PT1H'));
        $intervalos_ocupados[] = [$inicio_ind, $fim_ind];
    }
    $stmt->close();

    // Verificar horários disponíveis
    $horarios_disponiveis = [];
    foreach ($horarios as $hora) {
        $inicio_tentativa = new DateTime("$data $hora");
        $fim_tentativa = clone $inicio_tentativa;
        $fim_tentativa->add(new DateInterval("PT{$duracao_servico}M"));

        // Ignora horários no passado
        if ($inicio_tentativa < new DateTime()) continue;

        $conflito = false;
        foreach ($intervalos_ocupados as [$inicio_ocupado, $fim_ocupado]) {
            if ($inicio_tentativa < $fim_ocupado && $fim_tentativa > $inicio_ocupado) {
                $conflito = true;
                break;
            }
        }

        if (!$conflito && $fim_tentativa <= new DateTime("$data $hora_fim")) {
            $horarios_disponiveis[] = $hora;
        }
    }

    return $horarios_disponiveis;
}
?>
