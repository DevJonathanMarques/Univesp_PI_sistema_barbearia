<?php
include 'conexao.php';

$funcionarios = [];

$query = "SELECT funcionario_id, nome, foto FROM funcionarios";
$stmt = $conn->prepare($query);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $funcionarios[] = $row;
    }
}

$stmt->close();
$conn->close();

// Retorna o array de funcionarios para ser usado em outro arquivo
return $funcionarios;