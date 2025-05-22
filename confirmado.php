<?php
include 'backend/permissao_cliente.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agendamento Confirmado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }

        html {
            height: 100%;
            background: linear-gradient(to right, #1f1c2c, #928dab);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            min-height: 100%;
        }

        .menu {
            width: 100%;
            background: #121212;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .menu .logo {
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        .menu .menu-links {
            display: flex;
            gap: 12px;
        }

        .menu .menu-links a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            padding: 10px 16px;
            background: #007bff;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .menu .menu-links a:hover {
            background: #0056b3;
        }

        .container {
            background: white;
            padding: 30px;
            margin: 60px auto;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            text-align: center;
        }

        h2 {
            color: #28a745;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #444;
        }

        a.button {
            display: inline-block;
            margin-top: 30px;
            background: #007bff;
            color: white;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s;
        }

        a.button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="menu">
        <div class="logo">Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?></div>
        <div class="menu-links">
            <a href="agendamentos.php">Meus Agendamentos</a>
            <a href="usuario.php">Usuário</a>
            <a href="backend/sair.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Agendamento realizado com sucesso!</h2>
        <p>Seu horário foi reservado. Agradecemos por escolher nosso serviço.</p>
        <a class="button" href="agendamentos.php">Ver meus agendamentos</a>
    </div>
</body>
</html>
