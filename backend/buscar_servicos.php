<?php
function buscarServicos($barbeiro_id) {
    include 'conexao.php';

    if (!isset($barbeiro_id) || !is_numeric($barbeiro_id)) {
        return [];
    }

    $sql = "
        SELECT s.servico_id, s.descricao, s.duracao, s.preco
        FROM servicos s
        JOIN funcionario_servico fs ON s.servico_id = fs.servico_id
        WHERE fs.funcionario_id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $barbeiro_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $servicos = [];
    while ($row = $result->fetch_assoc()) {
        $servicos[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $servicos;
}
?>