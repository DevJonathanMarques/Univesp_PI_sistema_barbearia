<?php
include 'backend/permissao_admin.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Admin</title>
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

        .menu .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .menu .logo svg {
            width: 24px;
            height: 24px;
            fill: white;
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
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 700px;
            margin: 40px auto;
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }

        .day {
            background: #28a745;
            color: white;
            padding: 14px 8px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .day:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="menu">
        <div class="logo">
            Painel Admin
        </div>
        <div class="menu-links">
            <a href="admin_usuarios.php">Funcionários</a>
            <a href="backend/sair.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Indisponibilidade - Selecionar Dia</h2>
        <div class="calendar">
            <?php
                setlocale(LC_TIME, 'pt_BR.UTF-8');
                date_default_timezone_set('America/Sao_Paulo');

                $dias_semana = ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB'];

                for ($i = 0; $i < 30; $i++) {
                    $date = strtotime("+$i day");
                    $dia_semana = $dias_semana[date('w', $date)];
                    echo "<a href='admin_horarios.php?id=" . $_GET["id"] . "&data=" . date('Y-m-d', $date) . "' class='day'>" . $dia_semana . " " . date('d/m', $date) . "</a>";
                }
            ?>
        </div>
    </div>
</body>
</html>