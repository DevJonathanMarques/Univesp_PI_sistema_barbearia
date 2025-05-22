<?php
include 'backend/conexao.php';

// Consulta principal (sem entrada do usuário, está segura)
$sql = "SELECT f.funcionario_id, f.nome, f.email, f.foto, e.hora_inicio, e.hora_fim, e.dias_folga
        FROM funcionarios f
        LEFT JOIN expediente_funcionario e ON f.funcionario_id = e.funcionario_id";
$result = $conn->query($sql);

$funcionarios = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['funcionario_id'];

        // Prepared statement para evitar SQL Injection
        $stmt = $conn->prepare("SELECT s.descricao 
                                FROM funcionario_servico fs 
                                JOIN servicos s ON fs.servico_id = s.servico_id 
                                WHERE fs.funcionario_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $servicos_result = $stmt->get_result();

        $servicos = [];
        while ($s = $servicos_result->fetch_assoc()) {
            $servicos[] = $s['descricao'];
        }

        $row['servicos'] = $servicos;
        $funcionarios[] = $row;

        $stmt->close();
    }
}