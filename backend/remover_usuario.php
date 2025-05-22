<?php
include 'conexao.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "ID inválido.";
    exit;
}

// 1. Remover agendamentos
$stmt = $conn->prepare("DELETE FROM agendamentos WHERE funcionario_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// 2. Remover serviços do funcionário
$stmt = $conn->prepare("DELETE FROM funcionario_servico WHERE funcionario_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// 3. Remover expediente do funcionário
$stmt = $conn->prepare("DELETE FROM expediente_funcionario WHERE funcionario_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// 4. Excluir horários indisponíveis do funcionário
$stmt = $conn->prepare("DELETE FROM horarios_indisponiveis WHERE funcionario_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// 4. Remover o próprio funcionário
$stmt = $conn->prepare("DELETE FROM funcionarios WHERE funcionario_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../admin_usuarios.php");
exit;
