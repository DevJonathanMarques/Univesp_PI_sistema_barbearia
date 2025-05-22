<?php
include 'backend/permissao_cliente.php';

$barbeiro_id = $_GET['barbeiro_id'] ?? null;
$servico_id = $_GET['servico_id'] ?? null;

if (!$barbeiro_id || !$servico_id) {
    echo "Barbeiro ou serviço não selecionado.";
    exit;
}

include 'backend/buscar_disponibilidade.php';
$datas_disponiveis = buscarDatasDisponiveis($barbeiro_id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
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
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 95%;
            max-width: 900px;
            margin: 40px auto;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 25px;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .day {
            background: #007bff;
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        .day:hover {
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
        <h2>Selecione um dia</h2>
        <div class="calendar">
            <?php
                setlocale(LC_TIME, 'pt_BR.UTF-8');
                date_default_timezone_set('America/Sao_Paulo');

                $dias_semana = ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB'];

                if (!empty($datas_disponiveis)) {
                    foreach ($datas_disponiveis as $data) {
                        $timestamp = strtotime($data);
                        $dia_semana = $dias_semana[date('w', $timestamp)];
                        $data_formatada = date('d/m', $timestamp);
                        echo "<a href='horarios.php?date={$data}&barbeiro_id={$barbeiro_id}&servico_id={$servico_id}' class='day'>{$dia_semana} {$data_formatada}</a>";
                    }
                } else {
                    echo "<p>Nenhum horário disponível nos próximos dias.</p>";
                }
            ?>
        </div>
    </div>
</body>
</html>
