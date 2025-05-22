<?php
include 'backend/permissao_cliente.php';

$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
$barbeiro_id = $_GET['barbeiro_id'] ?? '';
$service_id = $_GET['servico_id'] ?? '';

include 'backend/conexao.php';

$barbeiro_nome = "Não encontrado";
$descricao_servico = "Não encontrado";
$preco_servico = 0.0;
$duracao_servico = 0;

if ($barbeiro_id && $conn) {
    $stmt = $conn->prepare("SELECT nome FROM funcionarios WHERE funcionario_id = ?");
    $stmt->bind_param("i", $barbeiro_id);
    $stmt->execute();
    $stmt->bind_result($barbeiro_nome);
    $stmt->fetch();
    $stmt->close();
}

if ($service_id && $conn) {
    $stmt = $conn->prepare("SELECT descricao, preco, duracao FROM servicos WHERE servico_id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $stmt->bind_result($descricao_servico, $preco_servico, $duracao_servico);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Agendamento</title>
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
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 95%;
            max-width: 600px;
            margin: 40px auto;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #444;
            margin: 10px 0;
        }

        strong {
            color: #000;
        }

        form button {
            background: #28a745;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 30px;
            transition: background 0.3s;
        }

        form button:hover {
            background: #218838;
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
        <h2>Revisar Agendamento</h2>
        <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($date)); ?></p>
        <p><strong>Horário:</strong> <?php echo htmlspecialchars($time); ?></p>
        <p><strong>Barbeiro:</strong> <?php echo htmlspecialchars($barbeiro_nome); ?></p>
        <p><strong>Serviço:</strong> <?php echo htmlspecialchars($descricao_servico) . " - R$" . number_format($preco_servico, 2, ',', '.'); ?></p>
        <p><strong>Duração:</strong> <?php echo (int)$duracao_servico . " minutos"; ?></p>

        <form action="backend/salvar_agendamento.php" method="POST">
            <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
            <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
            <input type="hidden" name="barbeiro_id" value="<?php echo htmlspecialchars($barbeiro_id); ?>">
            <input type="hidden" name="servico_id" value="<?php echo htmlspecialchars($service_id); ?>">
            <input type="hidden" name="duracao" value="<?php echo (int)$duracao_servico; ?>">
            <button type="submit">Confirmar Agendamento</button>
        </form>
    </div>
</body>
</html>
