<?php
include 'backend/permissao_cliente.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agendamento Confirmado | Barber Shop</title>
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
            display: flex;
            flex-direction: column;
        }

        nav.menu {
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
            transition: background 0.3s, outline 0.2s;
        }

        .menu .menu-links a:hover,
        .menu .menu-links a:focus {
            background: #0056b3;
            outline: 2px solid #ffffff;
            outline-offset: 2px;
        }

        main.container {
            background: white;
            padding: 40px;
            margin: 60px auto;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);
            max-width: 600px;
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .checkmark {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid #28a745;
            margin: 0 auto 20px auto;
            position: relative;
            animation: pop 0.5s ease-out;
        }

        @keyframes pop {
            0% { transform: scale(0); opacity: 0; }
            80% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); }
        }

        .checkmark::after {
            content: '';
            width: 25px;
            height: 45px;
            border-right: 4px solid #28a745;
            border-bottom: 4px solid #28a745;
            transform: rotate(45deg) scale(0);
            transform-origin: center;
            animation: draw 0.4s ease-out forwards 0.3s;
        }

        @keyframes draw {
            to { transform: rotate(45deg) scale(1); }
        }

        h1 {
            color: #28a745;
            margin-bottom: 15px;
            font-size: 1.9em;
        }

        p {
            font-size: 16px;
            color: #444;
            margin: 10px 0;
            line-height: 1.5;
        }

        a.button {
            display: inline-block;
            margin-top: 30px;
            background: #007bff;
            color: white;
            padding: 14px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s, transform 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            font-weight: 600;
        }

        a.button:hover,
        a.button:focus {
            background: #0056b3;
            transform: scale(1.03);
            outline: 2px solid #fff;
            outline-offset: 3px;
        }

        @media (max-width: 600px) {
            .checkmark {
                width: 70px;
                height: 70px;
                border-width: 3px;
            }
            .checkmark::after {
                width: 18px;
                height: 32px;
                border-width: 3px;
            }
        }
    </style>
</head>
<body>
    <nav class="menu" aria-label="Menu principal">
        <div class="logo">Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?></div>
        <div class="menu-links">
            <a href="agendamentos.php">Meus Agendamentos</a>
            <a href="usuario.php">Usuário</a>
            <a href="backend/sair.php">Sair</a>
        </div>
    </nav>

    <main class="container" role="alert" aria-live="assertive" tabindex="-1" id="mensagem-confirmacao">
        <div class="checkmark" role="img" aria-label="Símbolo de confirmação"></div>
        <h1>Agendamento realizado com sucesso!</h1>
        <p>Seu horário foi reservado e está confirmado no sistema.</p>
        <p>Você receberá um lembrete próximo ao horário do agendamento.</p>
        <a class="button" href="agendamentos.php" role="button">Ver meus agendamentos</a>
    </main>

    <script>
        // Move o foco automaticamente para a mensagem de sucesso ao carregar
        window.addEventListener('load', () => {
            document.getElementById('mensagem-confirmacao').focus();
        });
    </script>
</body>
</html>