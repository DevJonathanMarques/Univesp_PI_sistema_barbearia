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
        * { box-sizing: border-box; }

        html {
            height: 100%;
            background: linear-gradient(to right, #1f1c2c, #928dab);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            min-height: 100%;
        }

        header {
            width: 100%;
            background: #121212;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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

        nav a:hover { background: #0056b3; }

        main {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 95%;
            max-width: 600px;
            margin: 40px auto;
        }

        h1, h2, h3 {
            color: #333;
            text-align: center;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            color: #444;
            margin: 8px 0;
            text-align: center;
        }

        strong { color: #000; }

        form {
            margin-top: 25px;
        }

        fieldset {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        legend {
            font-weight: bold;
            padding: 0 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        small {
            display: block;
            color: #666;
            margin-top: 4px;
            font-size: 13px;
        }

        button {
            display: block;
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s;
        }

        button:hover, button:focus {
            background: #218838;
        }

        .divider {
            margin: 25px 0;
            border-top: 1px solid #ddd;
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
        <section aria-labelledby="resumo-agendamento">
            <h1 id="resumo-agendamento">Revisar Agendamento</h1>
            <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($date)); ?></p>
            <p><strong>Horário:</strong> <?php echo htmlspecialchars($time); ?></p>
            <p><strong>Barbeiro:</strong> <?php echo htmlspecialchars($barbeiro_nome); ?></p>
            <p><strong>Serviço:</strong> <?php echo htmlspecialchars($descricao_servico) . " - R$" . number_format($preco_servico, 2, ',', '.'); ?></p>
            <p><strong>Duração:</strong> <?php echo (int)$duracao_servico . " minutos"; ?></p>
        </section>

        <div class="divider" role="separator" aria-hidden="true"></div>

        <section aria-labelledby="pagamento">
            <form action="backend/salvar_agendamento.php" method="POST" aria-labelledby="pagamento">
                <fieldset>
                    <legend id="pagamento">Dados do Cartão de Crédito</legend>

                    <div class="form-group">
                        <label for="bandeira">Bandeira do Cartão</label>
                        <select id="bandeira" name="bandeira" required>
                            <option value="">Selecione</option>
                            <option value="Master">MasterCard</option>
                            <option value="Visa">Visa</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cpf_titular">CPF do Titular</label>
                        <input type="text" id="cpf_titular" name="cpf_titular" placeholder="Somente números" pattern="\d{11}" maxlength="11" required aria-describedby="cpf-desc">
                        <small id="cpf-desc">Digite apenas números, sem pontos ou traços.</small>
                    </div>

                    <div class="form-group">
                        <label for="nome_titular">Nome no Cartão</label>
                        <input type="text" id="nome_titular" name="nome_titular" required>
                    </div>

                    <div class="form-group">
                        <label for="numero_cartao">Número do Cartão</label>
                        <input type="text" id="numero_cartao" name="numero_cartao" maxlength="19" placeholder="XXXX XXXX XXXX XXXX" required>
                    </div>

                    <div class="form-group">
                        <label for="validade">Validade</label>
                        <input type="text" id="validade" name="validade" placeholder="MM/AAAA" pattern="(0[1-9]|1[0-2])\/[0-9]{4}" maxlength="7" required>
                    </div>

                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="number" id="cvv" name="cvv" maxlength="4" required aria-describedby="cvv-desc">
                        <small id="cvv-desc">Código de segurança de 3 ou 4 dígitos, localizado no verso do cartão.</small>
                    </div>
                </fieldset>

                <!-- Campos ocultos -->
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
                <input type="hidden" name="barbeiro_id" value="<?php echo htmlspecialchars($barbeiro_id); ?>">
                <input type="hidden" name="servico_id" value="<?php echo htmlspecialchars($service_id); ?>">
                <input type="hidden" name="duracao" value="<?php echo (int)$duracao_servico; ?>">
                <input type="hidden" name="preco" value="<?php echo htmlspecialchars($preco_servico); ?>">

                <button type="submit" aria-label="Confirmar pagamento e concluir agendamento">Confirmar e Pagar</button>
            </form>
        </section>
    </main>
</body>
</html>