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

        main {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 360px;
            text-align: center;
        }

        h1 {
            margin-bottom: 24px;
            color: #333;
            font-size: 1.5rem;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        label {
            text-align: left;
            margin-top: 10px;
            font-weight: 500;
        }

        input {
            padding: 12px 15px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button, a.button-link {
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        button[type="submit"] {
            background-color: #1f1c2c;
            color: #fff;
        }

        button[type="submit"]:hover {
            background-color: #37324d;
        }

        a.button-link {
            background-color: #4caf50;
            color: #fff;
        }

        a.button-link:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <main>
        <h1>Entrar no Sistema</h1>
        <form action="backend/login.php" method="POST" enctype="multipart/form-data">
            <label for="usuario">E-mail</label>
            <input type="text" id="usuario" name="usuario" placeholder="Digite seu e-mail" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

            <button type="submit">Entrar</button>
        </form>

        <a href="cadastro.php" class="button-link">Criar Cadastro</a>
    </main>
</body>
</html>