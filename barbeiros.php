<?php
include 'backend/permissao_cliente.php';
include 'backend/listar_barbeiros.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolher Barbeiro</title>
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
            max-width: 1100px;
            margin: 40px auto;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 25px;
        }

        .barbeiros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 25px;
        }

        .card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .profile-img {
            width: 100%;
            height: 250px;
            object-fit: contain;
            background-color: #eaeaea; /* fundo neutro para imagens menores */
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .nome {
            font-size: 17px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        .btn-escolher {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-escolher:hover {
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
        <h2>Escolha um Barbeiro</h2>
        <div class="barbeiros-grid">
            <?php foreach ($funcionarios as $funcionario): ?>
                <div class="card">
                    <img src="backend/uploads/<?php echo $funcionario['foto']; ?>" alt="Foto do Barbeiro" class="profile-img">
                    <div class="nome"><?php echo htmlspecialchars($funcionario['nome']); ?></div>
                    <a class="btn-escolher" href="servicos.php?barbeiro_id=<?php echo $funcionario['funcionario_id']; ?>">Escolher</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
