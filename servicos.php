<?php
include 'backend/permissao_cliente.php';
include 'backend/buscar_servicos.php';

$barbeiro_id = $_GET['barbeiro_id'] ?? null;

if (!$barbeiro_id) {
    echo "Barbeiro não informado.";
    exit;
}

$servicos = buscarServicos($barbeiro_id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços</title>
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
            max-width: 700px;
            margin: 40px auto;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }

        fieldset {
            border: none;
            text-align: left;
        }

        legend {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .services {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 10px;
        }

        .service {
            background: #f8f9fa;
            color: #333;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s, background 0.3s;
            display: flex;
            align-items: center;
        }

        .service:hover {
            transform: translateY(-3px);
            background: #e2e6ea;
        }

        .service input {
            margin-right: 10px;
        }

        button[type="submit"] {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 25px;
            transition: background 0.3s;
        }

        button[type="submit"]:hover {
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
        <h1>Selecione um Serviço</h1>

        <form action="agenda.php" method="GET" onsubmit="return validateForm()" aria-labelledby="form-title">
            <input type="hidden" name="barbeiro_id" value="<?php echo $barbeiro_id; ?>">

            <fieldset>
                <legend id="form-title">Lista de serviços disponíveis</legend>

                <div class="services">
                    <?php foreach ($servicos as $servico): ?>
                        <label class="service">
                            <input 
                                type="radio" 
                                name="servico_id" 
                                value="<?php echo $servico['servico_id']; ?>">
                            <?php echo htmlspecialchars($servico['descricao']); ?> — 
                            R$<?php echo number_format($servico['preco'], 2, ',', '.'); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <button type="submit">Continuar</button>
        </form>

        <script>
            function validateForm() {
                const selected = document.querySelector('input[name="servico_id"]:checked');
                if (!selected) {
                    alert('Selecione um serviço!');
                    return false;
                }
                return true;
            }
        </script>
    </main>
</body>
</html>