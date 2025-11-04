<?php
include 'backend/permissao_cliente.php';
include 'backend/conexao.php';

$cliente_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT nome, telefone, email, foto FROM clientes WHERE cliente_id = ?");
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            height: 100%;
            background: linear-gradient(to right, #1f1c2c, #928dab);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100%;
        }

        nav.menu {
            width: 100%;
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

        .menu .menu-links a:hover,
        .menu .menu-links a:focus {
            background: #0056b3;
            outline: 2px solid #fff;
            outline-offset: 2px;
        }

        main.container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 450px;
            margin: 40px auto;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 3px solid #ccc;
        }

        label {
            display: block;
            text-align: left;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            margin-top: 10px;
        }

        input[type="file"],
        input[type="text"],
        input[type="tel"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button, .back-button {
            flex: 1;
            padding: 12px;
            font-size: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: background 0.3s;
        }

        button.save-button {
            background: #28a745;
            color: white;
        }

        button.save-button:hover,
        button.save-button:focus {
            background: #218838;
            outline: 2px solid #fff;
            outline-offset: 2px;
        }

        .back-button {
            background: #007bff;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-button:hover,
        .back-button:focus {
            background: #0056b3;
            outline: 2px solid #fff;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <nav class="menu" aria-label="Menu principal">
        <div class="logo">Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?></div>
        <div class="menu-links">
            <a href="agendamentos.php">Meus Agendamentos</a>
            <a href="barbeiros.php">Agenda</a>
            <a href="backend/sair.php">Sair</a>
        </div>
    </nav>

    <main class="container" role="main">
        <h2 id="titulo-perfil">Meu Perfil</h2>

        <form action="backend/atualizar_usuario.php" method="POST" enctype="multipart/form-data" aria-labelledby="titulo-perfil">
            <input type="hidden" name="id" value="<?php echo $cliente_id; ?>">

            <img 
                src="<?php echo 'backend/uploads/' . ($cliente['foto'] ?? 'foto.png'); ?>" 
                alt="Foto atual do usuário <?php echo htmlspecialchars($cliente['nome']); ?>" 
                class="profile-img"
                id="foto-perfil"
            >

            <label for="foto">Alterar foto do perfil</label>
            <input type="file" id="foto" name="foto" accept="image/*" aria-describedby="foto-perfil">

            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>

            <label for="phone">Telefone</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>

            <div class="buttons">
                <button type="submit" class="save-button">Salvar</button>
                <a href="agendamentos.php" class="back-button" role="button">Voltar</a>
            </div>
        </form>
    </main>
</body>
</html>