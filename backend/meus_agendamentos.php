<?php
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION["id"])) {
    die("Acesso não autorizado.");
}

$cliente_id = $_SESSION["id"];

// Filtra apenas agendamentos futuros
$query = "
    SELECT 
        a.agendamento_id,
        s.descricao AS servico,
        f.nome AS funcionario,
        a.data_agendamento
    FROM agendamentos a
    JOIN servicos s ON a.servico_id = s.servico_id
    JOIN funcionarios f ON a.funcionario_id = f.funcionario_id
    WHERE a.cliente_id = ?
      AND a.data_agendamento >= NOW()
    ORDER BY a.data_agendamento ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $cliente_id);
$stmt->execute();
$result = $stmt->get_result();

$meus_agendamentos = [];
while ($row = $result->fetch_assoc()) {
    $meus_agendamentos[] = $row;
}

$stmt->close();
$conn->close();
?>
