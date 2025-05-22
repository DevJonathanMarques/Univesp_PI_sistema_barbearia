<?php
include 'backend/permissao_cliente.php';

$barbeiro_id = $_GET['barbeiro_id'] ?? null;
$servico_id = $_GET['servico_id'] ?? null;
$data = $_GET['date'] ?? null;

if (!$barbeiro_id || !$data || !$servico_id) {
    echo "Barbeiro, serviço ou data não informados.";
    exit;
}

include 'backend/buscar_horarios_disponiveis.php';

// Buscar duração do serviço
$stmt = $conn->prepare("SELECT duracao FROM servicos WHERE servico_id = ?");
$stmt->bind_param("i", $servico_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Serviço não encontrado.";
    exit;
}

$duracao_servico = (int) $resultado->fetch_assoc()['duracao'];

$horarios_disponiveis = buscarHorariosDisponiveis($conn, $barbeiro_id, $data, $servico_id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horários</title>
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
            max-width: 700px;
            margin: 40px auto;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        h3 {
            color: #666;
            margin-bottom: 20px;
        }

        .schedule {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .time {
            background: #007bff;
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
        }

        .time:hover {
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
        <h2>Selecione um horário</h2>
        <h3>Data Selecionada: <?php echo date('d/m/Y', strtotime($data)); ?></h3>
        <div class="schedule">
            <?php
                if (!empty($horarios_disponiveis)) {
                    $label_duracao = $duracao_servico . 'min';
                    foreach ($horarios_disponiveis as $horario) {
                        $url = "finalizar.php?date=" . urlencode($data)
                             . "&time=" . urlencode($horario)
                             . "&barbeiro_id=" . urlencode($barbeiro_id)
                             . "&servico_id=" . urlencode($servico_id);
                        echo "<a href='$url' class='time'>{$horario} ({$label_duracao})</a>";
                    }
                } else {
                    echo "<p>Nenhum horário disponível para esta data.</p>";
                }
            ?>
        </div>
    </div>
</body>
</html>
