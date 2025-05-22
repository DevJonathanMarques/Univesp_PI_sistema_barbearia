<?php
include 'conexao.php'; // Conexão segura com o banco de dados

// Verifica se o ID do funcionário está na sessão
if (!isset($_SESSION["id"])) {
    die("Usuário não autenticado.");
}

$funcionario_id = $_SESSION["id"];

// Preparar a query para pegar todos os agendamentos do funcionário logado
$query = "
    SELECT 
        a.agendamento_id,
        c.nome AS cliente,
        c.telefone AS telefone_cliente,
        c.email AS email_cliente,
        s.descricao AS servico,
        f.nome AS funcionario,
        a.data_agendamento
    FROM agendamentos a
    JOIN clientes c ON a.cliente_id = c.cliente_id
    JOIN servicos s ON a.servico_id = s.servico_id
    JOIN funcionarios f ON a.funcionario_id = f.funcionario_id
    WHERE a.funcionario_id = ?
    ORDER BY a.data_agendamento ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $funcionario_id);
$stmt->execute();
$result = $stmt->get_result();

// Guardar os agendamentos em um array
$agendamentos = [];
while ($row = $result->fetch_assoc()) {
    $agendamentos[] = $row;
}

// Fechar a conexão
$stmt->close();
$conn->close();
?>