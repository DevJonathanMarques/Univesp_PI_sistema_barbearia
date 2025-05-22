<?php
include 'backend/permissao_admin.php';
include 'backend/listar_funcionarios.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários - Admin</title>
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
            margin: 30px auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .admin-item {
            display: flex;
            gap: 20px;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            background-color: #fafafa;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .admin-item img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .admin-info {
            flex-grow: 1;
            text-align: left;
            font-size: 14px;
            color: #444;
        }

        .admin-info strong {
            font-size: 16px;
            color: #222;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .edit-button, .remove-button, .indisponibilidade-button {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 14px;
        }

        .edit-button {
            background: #ffc107;
            color: white;
        }

        .edit-button:hover {
            background: #e0a800;
        }

        .remove-button {
            background: #dc3545;
            color: white;
        }

        .remove-button:hover {
            background: #c82333;
        }

        .indisponibilidade-button {
            background: #128c01;
            color: white;
        }

        .indisponibilidade-button:hover {
            background: #0a4f01;
        }
    </style>
</head>
<body>
    <div class="menu">
        <div class="logo">
            Painel Admin
        </div>
        <div class="menu-links">
            <a href="admin_criar.php">Criar Funcionário</a>
            <a href="backend/sair.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <h2>Funcionários</h2>
        <?php foreach ($funcionarios as $f): ?>
            <div class="admin-item">
                <img src="backend/uploads/<?php echo htmlspecialchars($f['foto']); ?>" alt="Foto de <?php echo htmlspecialchars($f['nome']); ?>">
                <div class="admin-info">
                    <strong><?php echo htmlspecialchars($f['nome']); ?></strong><br>
                    Email: <?php echo htmlspecialchars($f['email']); ?><br>
                    Expediente: <?php echo substr($f['hora_inicio'], 0, 5); ?> às <?php echo substr($f['hora_fim'], 0, 5); ?><br>
                    Folga(s): <?php echo htmlspecialchars($f['dias_folga']); ?><br>
                    Serviços: <?php echo implode(", ", array_map('htmlspecialchars', $f['servicos'])); ?>
                </div>
                <div class="buttons">
                    <a href="admin_editar.php?id=<?php echo $f['funcionario_id'] ?>" class="edit-button">Editar</a>
                    <a href="backend/remover_usuario.php?id=<?= $f['funcionario_id'] ?>" class="remove-button">Remover</a>
                    <a href="admin_data.php?id=<?= $f['funcionario_id'] ?>" class="indisponibilidade-button">Indisponibilidade</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
