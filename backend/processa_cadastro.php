<?php
session_start();
require_once 'conexao.php'; // Arquivo com conexão ao banco (mysqli ou PDO)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['phone']); // novo campo
    $email = trim($_POST['email']);
    $senha = $_POST['password'];
    $foto = $_FILES['foto'];

    // Validações básicas
    if (empty($nome) || empty($telefone) || empty($email) || empty($senha) || empty($foto)) {
        die("Preencha todos os campos corretamente.");
    }

    // Verifica se email já está cadastrado
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        die("E-mail já cadastrado.");
    }

    // Salvar foto no servidor
    $foto_nome = uniqid() . "_" . basename($foto['name']);
    $destino = "uploads/" . $foto_nome;

    if (!move_uploaded_file($foto['tmp_name'], $destino)) {
        die("Erro ao salvar a foto.");
    }

    // Criptografar senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir no banco
    $stmt = $conn->prepare("INSERT INTO clientes (nome, telefone, email, senha, foto) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nome, $telefone, $email, $senha_hash, $foto_nome);

    if ($stmt->execute()) {
        header("Location: ../index.php?cadastro_sucesso=1");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
} else {
    header("Location: cadastro.php");
    exit();
}
?>
