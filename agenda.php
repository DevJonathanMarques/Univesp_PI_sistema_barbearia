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

        header {
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

        .logo {
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        nav {
            display: flex;
            gap: 12px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            padding: 10px 16px;
            background: #007bff;
            border-radius: 8px;
            transition: background 0.3s;
        }

        nav a:hover {
            background: #0056b3;
        }

        main {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 95%;
            max-width: 900px;
            margin: 40px auto;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }

        ul.calendar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 20px;
            margin-top: 20px;
            list-style: none;
            padding: 0;
        }

        li.day-item a {
            display: block;
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

        li.day-item a:hover,
        li.day-item a:focus {
            background: #0056b3;
        }

        p {
            color: #444;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?></div>
        <nav aria-label="Menu principal">
            <a href="agendamentos.php">Meus Agendamentos</a>
            <a href="usuario.php">Usuário</a>
            <a href="backend/sair.php">Sair</a>
        </nav>
    </header>

    <main>
        <h1>Selecione um Dia</h1>

        <?php
            setlocale(LC_TIME, 'pt_BR.UTF-8');
            date_default_timezone_set('America/Sao_Paulo');

            // Abreviações e nomes completos
            $dias_semana_abrev = ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB'];
            $dias_semana_completo = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];

            if (!empty($datas_disponiveis)):
        ?>
            <ul class="calendar" aria-label="Dias disponíveis para agendamento">
                <?php foreach ($datas_disponiveis as $data): 
                    $timestamp = strtotime($data);
                    $dia_numero = date('w', $timestamp);
                    $dia_semana_abrev = $dias_semana_abrev[$dia_numero];
                    $dia_semana_completo = $dias_semana_completo[$dia_numero];
                    $data_formatada = date('d/m', $timestamp);
                    $data_completa = $dia_semana_completo . ", " . strftime('%d de %B', $timestamp);
                ?>
                    <li class="day-item">
                        <a href="horarios.php?date=<?php echo $data; ?>&barbeiro_id=<?php echo $barbeiro_id; ?>&servico_id=<?php echo $servico_id; ?>"
                        aria-label="<?php echo $data_completa; ?>">
                            <?php echo "{$dia_semana_abrev} {$data_formatada}"; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum horário disponível nos próximos dias.</p>
        <?php endif; ?>
    </main>
    
</body>
</html>
