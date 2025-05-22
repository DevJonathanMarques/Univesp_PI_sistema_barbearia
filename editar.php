<?php
include 'backend/permissao_cliente.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Agendamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f4f4f4;
            margin: 0;
        }
        .menu {
            width: 100%;
            background: #333;
            padding: 25px 25px 25px 0px;
            text-align: right;
        }
        .menu a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 20px;
            background: #007bff;
            border-radius: 5px;
            margin-left: 10px;
        }
        .menu a:hover {
            background: #0056b3;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            text-align: center;
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="menu">
        <a href="agendamentos.php">Meus Agendamentos</a>
        <a href="usuario.php">Usuário</a>
        <a href="backend/sair.php">Sair</a>
    </div>
    <div class="container">
        <h2>Editar Agendamento</h2>
        <form action="salvar_edicao.php" method="GET">
            <div class="form-group">
                <label for="date">Selecione um dia:</label>
                <select name="date" id="date">
                    <?php
                        setlocale(LC_TIME, 'pt_BR.UTF-8');
                        date_default_timezone_set('America/Sao_Paulo');
                        $dias_semana = ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB'];
                        for ($i = 0; $i < 14; $i++) {
                            $date = strtotime("+$i day");
                            $dia_semana = $dias_semana[date('w', $date)];
                            echo "<option value='" . date('Y-m-d', $date) . "'>" . $dia_semana . " " . date('d/m', $date) . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="time">Selecione um horário:</label>
                <select name="time" id="time">
                    <option value="09:00">09:00</option>
                    <option value="10:00">10:00</option>
                    <option value="11:00">11:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                </select>
            </div>
            <div class="form-group">
                <label>Selecione os serviços:</label><br>
                <input type="checkbox" name="service[]" value="Corte"> Corte - R$30<br>
                <input type="checkbox" name="service[]" value="Barba"> Barba - R$20<br>
                <input type="checkbox" name="service[]" value="Corte e Barba"> Corte e Barba - R$50<br>
            </div>
            <button type="submit">Salvar Alterações</button>
        </form>
        <br>
        <a href="agendamentos.php">Cancelar</a>
    </div>
</body>
</html>