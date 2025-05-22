<?php
include 'permissao_admin.php';
include 'conexao.php';

$funcionario_id = $_POST['funcionario_id'] ?? null;
$data = $_POST['data'] ?? null;
$horarios = $_POST['horarios'] ?? [];

if (!$funcionario_id || !$data) {
    die("Dados inválidos.");
}

// Remove os horários antigos para aquele dia
$stmtDelete = $conn->prepare("DELETE FROM horarios_indisponiveis WHERE funcionario_id = ? AND data = ?");
$stmtDelete->bind_param("is", $funcionario_id, $data);
$stmtDelete->execute();
$stmtDelete->close();

// Insere os novos horários
$stmt = $conn->prepare("INSERT INTO horarios_indisponiveis (funcionario_id, data, hora) VALUES (?, ?, ?)");

foreach ($horarios as $hora) {
    $hora_formatada = sprintf('%02d:00:00', $hora);
    $stmt->bind_param("iss", $funcionario_id, $data, $hora_formatada);
    $stmt->execute();
}

$stmt->close();
$conn->close();

header("Location: ../admin_usuarios.php");
exit();
