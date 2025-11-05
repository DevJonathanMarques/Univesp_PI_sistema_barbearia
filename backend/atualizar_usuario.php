<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION["id"]) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acesso não autorizado.");
}

$cliente_id = $_SESSION["id"];
$nome = $_POST["nome"] ?? '';
$telefone = $_POST["phone"] ?? '';
$email = $_POST["email"] ?? '';

$foto = null;
if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
    $extensao = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $novo_nome = "uploads/foto_cliente_" . $cliente_id . "." . $extensao;
    move_uploaded_file($_FILES["foto"]["tmp_name"], $novo_nome);
    $foto = $novo_nome;
}

if ($foto) {
    $stmt = $conn->prepare("UPDATE clientes SET nome = ?, telefone = ?, email = ?, foto = ? WHERE cliente_id = ?");
    $stmt->bind_param("ssssi", $nome, $telefone, $email, $foto, $cliente_id);
} else {
    $stmt = $conn->prepare("UPDATE clientes SET nome = ?, telefone = ?, email = ? WHERE cliente_id = ?");
    $stmt->bind_param("sssi", $nome, $telefone, $email, $cliente_id);
}

if ($stmt->execute()) {
    header("Location: ../usuario.php?atualizado=1");
    $_SESSION['nome'] = $_POST["nome"];
    $_SESSION['usuario'] = $_POST["email"];
} else {
    echo "Erro ao atualizar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
