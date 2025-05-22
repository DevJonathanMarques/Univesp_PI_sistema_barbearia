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
    <title>Cadastro - Barbearia</title>
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

        .register-container {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .register-container h2 {
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

        .back-button {
            background-color: #6c757d;
            color: #fff;
        }

        .back-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Cadastro</h2>
        <form action="backend/processa_cadastro.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="foto" accept="image/*" required>
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="tel" name="phone" placeholder="Telefone" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Cadastrar</button>
        </form>
        <button class="back-button" onclick="window.location.href='index.php'">Voltar ao Login</button>
    </div>
</body>
</html>
