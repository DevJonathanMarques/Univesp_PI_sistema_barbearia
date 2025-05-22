<?php
session_start();
include 'conexao.php'; // Arquivo para conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    if (empty($usuario) || empty($senha)) {
        // erro 1 = preencha todos os campos
        header("Location: ../index.php?erro=1");
        exit();
    }

    // Proteção contra SQL Injection
    $usuario = mysqli_real_escape_string($conn, $usuario);

    // ===== 1. Verifica se é um administrador =====
    $query_adm = "SELECT * FROM adms WHERE user = ?";
    $stmt_adm = $conn->prepare($query_adm);
    $stmt_adm->bind_param("s", $usuario);
    $stmt_adm->execute();
    $result_adm = $stmt_adm->get_result();

    if ($result_adm->num_rows > 0) {
        $adm = $result_adm->fetch_assoc();
        
        if (password_verify($senha, $adm['senha'])) {
            echo "Teste";
            $_SESSION['id'] = $adm['id'];
            $_SESSION['usuario'] = $adm['user'];
            $_SESSION['permissao'] = 'admin';
            header("Location: ../admin_usuarios.php");
            exit();
        }
    }

    // ===== 2. Verifica se é um funcionário =====
    $query_func = "SELECT * FROM funcionarios WHERE email = ?";
    $stmt_func = $conn->prepare($query_func);
    $stmt_func->bind_param("s", $usuario);
    $stmt_func->execute();
    $result_func = $stmt_func->get_result();

    if ($result_func->num_rows > 0) {
        $func = $result_func->fetch_assoc();

        if (password_verify($senha, $func['senha'])) {
            $_SESSION['id'] = $func['funcionario_id'];
            $_SESSION['usuario'] = $func['email'];
            $_SESSION['nome'] = $func['nome'];
            $_SESSION['permissao'] = 'funcionario';
            header("Location: ../funcionario_agendamentos.php");
            exit();
        }
    }

    // ===== 3. Verifica se é um cliente =====
    $query_cliente = "SELECT * FROM clientes WHERE email = ?";
    $stmt_cliente = $conn->prepare($query_cliente);
    $stmt_cliente->bind_param("s", $usuario);
    $stmt_cliente->execute();
    $result_cliente = $stmt_cliente->get_result();

    if ($result_cliente->num_rows > 0) {
        $cliente = $result_cliente->fetch_assoc();

        if (password_verify($senha, $cliente['senha'])) {
            $_SESSION['id'] = $cliente['cliente_id'];
            $_SESSION['usuario'] = $cliente['email'];
            $_SESSION['nome'] = $cliente['nome'];
            $_SESSION['permissao'] = 'cliente';
            header("Location: ../barbeiros.php");
            exit();
        }
    }

    // erro 2 = usuário ou senha inválidos
    header("Location: ../index.php?erro=2");
    exit();
}
?>
