<?php
session_start();

if (isset($_SESSION['permissao'])) {
    if ($_SESSION['permissao'] === 'admin') {
        header('Location: admin_usuarios.php');
        exit();
    } elseif ($_SESSION['permissao'] === 'funcionario') {
        header('Location: funcionario_agendamentos.php');
        exit();
    } elseif ($_SESSION['permissao'] === 'cliente') {
        header('Location: barbeiros.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barbearia</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #1f1c2c, #928dab);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .login-container {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 360px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 24px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            padding: 12px;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button[type="submit"] {
            background-color: #1f1c2c;
            color: #fff;
        }

        button[type="submit"]:hover {
            background-color: #37324d;
        }

        .register-button {
            background-color: #4caf50;
            color: #fff;
        }

        .register-button:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Entrar no Sistema</h2>
        <form action="backend/login.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="usuario" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <button class="register-button" onclick="window.location.href='cadastro.php'">Criar Cadastro</button>
    </div>
</body>
</html>
