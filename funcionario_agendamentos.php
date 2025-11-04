<?php
include 'backend/permissao_funcionario.php';
include 'backend/buscar_agendamentos.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Agenda</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            height: 100%;
            background: linear-gradient(to right, #1f1c2c, #928dab);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100%;
        }

        .menu {
            width: 100vw;
            background: #121212;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .menu .usuario {
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        .menu a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            padding: 10px 16px;
            background: #007bff;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .menu a:hover {
            background: #0056b3;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 700px;
            margin: 40px auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .appointment {
            display: grid;
            grid-template-columns: 25% 35% 40%;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            background-color: #f9f9f9;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 14px;
            text-align: left;
            gap: 10px;
        }

        .appointment span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .appointment:last-child {
            border-bottom: none;
        }

        .no-appointments {
            text-align: center;
            color: #666;
            font-size: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="menu">
        <div class="usuario">
            Agenda de
            <?php
            echo isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : 'Funcionário';
            ?>
        </div>
        <a href="backend/sair.php">Sair</a>
    </div>
    
    <div class="container">
        <h2>Agendamentos</h2>
        
        <?php if (!empty($agendamentos)): ?>
            <?php foreach ($agendamentos as $agendamento): ?>
                <div class="appointment">
                    <span><?= date('d/m - H:i', strtotime($agendamento['data_agendamento'])) ?></span>
                    <span><?= htmlspecialchars($agendamento['cliente']) ?></span>
                    <span><?= htmlspecialchars($agendamento['servico']) ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-appointments">Nenhum agendamento nos próximos dias.</p>
        <?php endif; ?>
        
    </div>
</body>
</html>