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
            max-width: 1100px;
            margin: 40px auto;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }

        section.barbeiros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 25px;
        }

        article.card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        article.card:hover {
            transform: translateY(-5px);
        }

        .profile-img {
            width: 100%;
            height: 250px;
            object-fit: contain;
            background-color: #eaeaea;
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
    <header>
        <div class="logo">Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?></div>
        <nav aria-label="Menu principal">
            <a href="agendamentos.php">Meus Agendamentos</a>
            <a href="usuario.php">Usuário</a>
            <a href="backend/sair.php">Sair</a>
        </nav>
    </header>

    <main>
        <h1>Escolha um Barbeiro</h1>
        <section class="barbeiros-grid" aria-label="Lista de barbeiros disponíveis">
            <?php foreach ($funcionarios as $funcionario): ?>
                <article class="card">
                    <img 
                        src="backend/uploads/<?php echo $funcionario['foto']; ?>" 
                        alt="Foto do barbeiro <?php echo htmlspecialchars($funcionario['nome']); ?>" 
                        class="profile-img">
                    <div class="nome"><?php echo htmlspecialchars($funcionario['nome']); ?></div>
                    <a class="btn-escolher" 
                       href="servicos.php?barbeiro_id=<?php echo $funcionario['funcionario_id']; ?>">
                       Escolher o barbeiro <?php echo htmlspecialchars($funcionario['nome']); ?>
                    </a>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>