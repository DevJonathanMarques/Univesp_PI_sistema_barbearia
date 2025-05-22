<?php
include 'backend/permissao_admin.php';
include 'backend/conexao.php';

$data = $_GET['data'] ?? date('Y-m-d');
$funcionario_id = $_GET['id'] ?? null;

if (!$funcionario_id) {
    echo "Funcionário inválido.";
    exit;
}

// Buscar horários indisponíveis salvos
$query = "SELECT HOUR(hora) as hora FROM horarios_indisponiveis WHERE funcionario_id = ? AND data = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $funcionario_id, $data);
$stmt->execute();
$result = $stmt->get_result();

$horarios_indisponiveis = [];
while ($row = $result->fetch_assoc()) {
    $horarios_indisponiveis[] = intval($row['hora']);
}

// Buscar horário de expediente do funcionário
$inicio = 0;
$fim = 23;
$queryExpediente = "SELECT hora_inicio, hora_fim FROM expediente_funcionario WHERE funcionario_id = ?";
$stmtExp = $conn->prepare($queryExpediente);
$stmtExp->bind_param("i", $funcionario_id);
$stmtExp->execute();
$resultExp = $stmtExp->get_result();

if ($rowExp = $resultExp->fetch_assoc()) {
    $inicio = intval(date('H', strtotime($rowExp['hora_inicio'])));
    $fim = intval(date('H', strtotime($rowExp['hora_fim']))) - 1;
}

// Buscar horários com agendamentos existentes
$queryAgendamentos = "SELECT HOUR(data_agendamento) as hora FROM agendamentos WHERE funcionario_id = ? AND DATE(data_agendamento) = ?";
$stmtAg = $conn->prepare($queryAgendamentos);
$stmtAg->bind_param("is", $funcionario_id, $data);
$stmtAg->execute();
$resultAg = $stmtAg->get_result();

$horarios_com_agendamento = [];
while ($row = $resultAg->fetch_assoc()) {
    $horarios_com_agendamento[] = intval($row['hora']);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Definir Disponibilidade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            max-width: 500px;
            margin: 40px auto;
            text-align: center;
        }

        h2, h3 {
            color: #333;
            margin-bottom: 15px;
        }

        form {
            margin-top: 20px;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            align-items: start;
            gap: 8px;
            max-height: 400px;
            overflow-y: auto;
            margin-top: 15px;
        }

        label {
            font-size: 15px;
            color: #333;
        }

        .disabled {
            color: #aaa;
        }

        button {
            margin-top: 25px;
            padding: 12px;
            width: 100%;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        input[type="checkbox"] {
            margin-right: 10px;
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
        <h2>Definir Horário Indisponível</h2>
        <h3><?php echo date('d/m/Y', strtotime($data)); ?></h3>

        <form action="backend/salvar_indisponibilidade.php" method="POST">
            <input type="hidden" name="data" value="<?php echo $data; ?>">
            <input type="hidden" name="funcionario_id" value="<?php echo $funcionario_id; ?>">

            <div class="checkbox-group">
                <label><input type="checkbox" id="selectAll"> Selecionar Todos</label>
                <?php
                    for ($hora = 0; $hora < 24; $hora++) {
                        $horaFormatada = sprintf('%02d:00', $hora);
                        $foraDoExpediente = ($hora < $inicio || $hora > $fim);
                        $temAgendamento = in_array($hora, $horarios_com_agendamento);
                        $checked = ($foraDoExpediente || in_array($hora, $horarios_indisponiveis)) ? 'checked' : '';
                        $disabled = ($foraDoExpediente || $temAgendamento) ? 'disabled' : '';
                        $classe = ($foraDoExpediente || $temAgendamento) ? 'class="disabled"' : '';

                        echo "<label $classe><input type='checkbox' name='horarios[]' value='$hora' $checked $disabled> $horaFormatada</label>";
                    }
                ?>
            </div>

            <button type="submit">Salvar</button>
        </form>
    </div>

    <script>
        document.getElementById("selectAll").addEventListener("change", function () {
            var checkboxes = document.querySelectorAll(".checkbox-group input[type='checkbox']:not(:disabled)");
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>

</body>
</html>
