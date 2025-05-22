<?php
include 'backend/permissao_admin.php';
include 'backend/conexao.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID inválido.";
    exit;
}

// Buscar dados do funcionário
$stmt = $conn->prepare("SELECT f.nome, f.email, f.foto, e.hora_inicio, e.hora_fim, e.dias_folga FROM funcionarios f JOIN expediente_funcionario e ON f.funcionario_id = e.funcionario_id WHERE f.funcionario_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$funcionario = $result->fetch_assoc();

if (!$funcionario) {
    echo "Funcionário não encontrado.";
    exit;
}

// Formatando horários para HH:MM
$hora_inicio_formatada = substr($funcionario['hora_inicio'], 0, 5);
$hora_fim_formatada = substr($funcionario['hora_fim'], 0, 5);

// Serviços disponíveis
$servicos = [];
$result_servicos = $conn->query("SELECT servico_id, descricao FROM servicos");
while ($row = $result_servicos->fetch_assoc()) {
    $servicos[] = $row;
}

// Serviços do funcionário
$servicos_funcionario = [];
$result_sf = $conn->query("SELECT servico_id FROM funcionario_servico WHERE funcionario_id = $id");
while ($row = $result_sf->fetch_assoc()) {
    $servicos_funcionario[] = $row['servico_id'];
}

// Dias de folga do funcionário
$dias_folga_raw = explode(',', $funcionario['dias_folga']);
$dias_folga = array_map(function($dia) {
    return strtolower(trim($dia));
}, $dias_folga_raw);

// Função para remover acentos
function remover_acentos($string) {
    return preg_replace(
        array(
            '/[áàâãä]/u', '/[éèêë]/u', '/[íìîï]/u',
            '/[óòôõö]/u', '/[úùûü]/u', '/[ç]/u'
        ),
        array('a', 'e', 'i', 'o', 'u', 'c'),
        strtolower($string)
    );
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário</title>
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
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        input[type="file"],
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        select[multiple] {
            height: auto;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            color: #444;
        }

        .dias-folga-container {
            margin-top: 10px;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 5px;
        }

        .checkbox-item {
            background: #f0f0f0;
            border: 1px solid #ccc;
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .checkbox-item input {
            margin: 0;
        }

        button {
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #0056b3;
        }

        .servicos-container {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="menu">
        <div class="logo">Painel Admin</div>
        <div class="menu-links">
            <a href="admin_usuarios.php">Funcionários</a>
            <a href="backend/sair.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Editar Funcionário</h2>
        <form action="backend/atualizar_funcionario.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="funcionario_id" value="<?= $id ?>">

            <img class="preview-img" src="backend/uploads/<?= htmlspecialchars($funcionario['foto']) ?>" alt="Foto do funcionário">
            <input type="file" name="foto" accept="image/*">

            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($funcionario['nome']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($funcionario['email']) ?>" required>

            <label>Nova senha (opcional):</label>
            <input type="password" name="password" placeholder="Nova senha (opcional)">

            <label>Disponível das:</label>
            <select name="hora_inicio" required>
                <?php for ($h = 0; $h < 24; $h++): ?>
                    <?php $hora = str_pad($h, 2, '0', STR_PAD_LEFT) . ":00"; ?>
                    <option value="<?= $hora ?>" <?= ($hora == $hora_inicio_formatada) ? 'selected' : '' ?>><?= $hora ?></option>
                <?php endfor; ?>
            </select>

            <label>às:</label>
            <select name="hora_fim" required>
                <?php for ($h = 0; $h < 24; $h++): ?>
                    <?php $hora = str_pad($h, 2, '0', STR_PAD_LEFT) . ":00"; ?>
                    <option value="<?= $hora ?>" <?= ($hora == $hora_fim_formatada) ? 'selected' : '' ?>><?= $hora ?></option>
                <?php endfor; ?>
            </select>

            <div class="servicos-container">
                <label>Serviços oferecidos:</label>
                <div class="checkbox-group">
                    <?php foreach ($servicos as $servico): ?>
                        <?php $checked = in_array($servico['servico_id'], $servicos_funcionario) ? 'checked' : ''; ?>
                        <label class="checkbox-item">
                            <input type="checkbox" name="servicos[]" value="<?= $servico['servico_id'] ?>" <?= $checked ?>>
                            <?= htmlspecialchars($servico['descricao']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="dias-folga-container">
                <label>Dias de folga:</label>
                <div class="checkbox-group">
                    <?php
                    $dias_semana = ["domingo", "segunda", "terça", "quarta", "quinta", "sexta", "sábado"];
                    foreach ($dias_semana as $dia) {
                        $dia_sem_acento = remover_acentos($dia);
                        $checked = in_array($dia_sem_acento, $dias_folga) ? 'checked' : '';
                        echo "<label class='checkbox-item'><input type='checkbox' name='dias_folga[]' value='$dia_sem_acento' $checked> $dia</label>";
                    }
                    ?>
                </div>
            </div>

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
