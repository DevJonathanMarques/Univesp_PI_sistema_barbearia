<?php
include 'backend/permissao_cliente.php';
include 'backend/meus_agendamentos.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos</title>
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

        .menu .menu-links a:hover,
        .menu .menu-links a:focus {
            background: #0056b3;
            outline: 2px solid #ffffff;
            outline-offset: 2px;
        }

        .container {
            background: white;
            padding: 30px;
            margin: 60px auto;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            max-width: 700px;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
            text-align: center;
        }

        ul.appointment-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li.appointment {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
        }

        li.appointment:nth-child(even) {
            background-color: #f9f9f9;
        }

        li.appointment:nth-child(odd) {
            background-color: #ffffff;
        }

        .no-appointments {
            font-size: 16px;
            color: #777;
            text-align: center;
        }

        @media (max-width: 600px) {
            li.appointment {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <nav class="menu" aria-label="Menu principal">
        <div class="logo">Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?></div>
        <div class="menu-links">
            <a href="barbeiros.php">Agenda</a>
            <a href="usuario.php">Usuário</a>
            <a href="backend/sair.php">Sair</a>
        </div>
    </nav>

    <main class="container" role="main">
        <h2 id="titulo-agendamentos">Meus Agendamentos</h2>

        <?php if (empty($meus_agendamentos)): ?>
            <p class="no-appointments" role="status" aria-live="polite">
                Você ainda não possui agendamentos.
            </p>
        <?php else: ?>
            <ul class="appointment-list" role="list" aria-labelledby="titulo-agendamentos">
                <?php foreach ($meus_agendamentos as $agendamento): ?>
                    <?php
                        $data = date('d/m', strtotime($agendamento['data_agendamento']));
                        $hora = date('H:i', strtotime($agendamento['data_agendamento']));
                        $servico = htmlspecialchars($agendamento['servico']);
                        $funcionario = htmlspecialchars($agendamento['funcionario']);
                    ?>
                    <li class="appointment" role="listitem" tabindex="0"
                        aria-label="Agendamento em <?php echo $data . ' às ' . $hora . ' para ' . $servico . ' com ' . $funcionario; ?>">
                        <strong><?php echo "$data às $hora"; ?></strong>
                        <span><?php echo "$servico com $funcionario"; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>
</body>
</html>
