<?php
include 'conexao.php';

function remover_acentos($string) {
    return preg_replace(
        array('/[áàâãä]/u', '/[éèêë]/u', '/[íìîï]/u', '/[óòôõö]/u', '/[úùûü]/u', '/[ç]/u'),
        array('a', 'e', 'i', 'o', 'u', 'c'),
        strtolower($string)
    );
}

// Pegando os dados do POST
$id = $_POST['funcionario_id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['password'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fim = $_POST['hora_fim'];
$servicos = $_POST['servicos'] ?? [];
$dias_folga = $_POST['dias_folga'] ?? [];
$foto = $_FILES['foto'];

// Tratando os dias de folga
$dias_folga_formatado = implode(',', array_map('remover_acentos', $dias_folga));

// Atualizando os dados do funcionário
if (!empty($senha)) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE funcionarios SET nome = ?, email = ?, senha = ? WHERE funcionario_id = ?");
    $stmt->bind_param("sssi", $nome, $email, $senha_hash, $id);
} else {
    $stmt = $conn->prepare("UPDATE funcionarios SET nome = ?, email = ? WHERE funcionario_id = ?");
    $stmt->bind_param("ssi", $nome, $email, $id);
}
$stmt->execute();

// Atualizando o expediente
$stmt = $conn->prepare("UPDATE expediente_funcionario SET hora_inicio = ?, hora_fim = ?, dias_folga = ? WHERE funcionario_id = ?");
$stmt->bind_param("sssi", $hora_inicio, $hora_fim, $dias_folga_formatado, $id);
$stmt->execute();

// Atualizando os serviços (remover tudo e inserir novamente)
$conn->query("DELETE FROM funcionario_servico WHERE funcionario_id = $id");
$stmt = $conn->prepare("INSERT INTO funcionario_servico (funcionario_id, servico_id) VALUES (?, ?)");
foreach ($servicos as $servico_id) {
    $stmt->bind_param("ii", $id, $servico_id);
    $stmt->execute();
}

// Atualizando a foto (se enviada)
if ($foto['size'] > 0 && $foto['error'] == 0) {
    $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
    $nome_foto = uniqid() . "." . $ext;
    move_uploaded_file($foto['tmp_name'], "uploads/" . $nome_foto);
    
    $stmt = $conn->prepare("UPDATE funcionarios SET foto = ? WHERE funcionario_id = ?");
    $stmt->bind_param("si", $nome_foto, $id);
    $stmt->execute();
}

// Redirecionar de volta
header("Location: ../admin_usuarios.php");
exit;
