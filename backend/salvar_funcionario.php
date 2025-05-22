<?php
include 'conexao.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar e processar dados do formulário
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];
    $dias_folga = isset($_POST['dias_folga']) ? $_POST['dias_folga'] : [];

    // Processar foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_nome = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_ext = pathinfo($foto_nome, PATHINFO_EXTENSION);
        $foto_novo_nome = uniqid() . '.' . $foto_ext;

        // Definir o diretório de upload
        $diretorio = 'uploads/';
        if (!file_exists($diretorio)) {
            mkdir($diretorio, 0777, true); // Cria a pasta se não existir
        }

        // Mover o arquivo para o diretório de destino
        if (!move_uploaded_file($foto_tmp, $diretorio . $foto_novo_nome)) {
            echo "Erro ao fazer upload da foto.";
            exit;
        }
    } else {
        // Caso a foto não tenha sido enviada
        $foto_novo_nome = null;
    }

    // Inserir dados do funcionário
    $sql_funcionario = "INSERT INTO funcionarios (nome, email, senha, foto) VALUES ('$nome', '$email', '$password', '$foto_novo_nome')";
    
    if ($conn->query($sql_funcionario) === TRUE) {
        $funcionario_id = $conn->insert_id; // Pega o ID do funcionário recém inserido

        // Preparar a string de dias de folga
        $dias_folga_string = !empty($dias_folga) ? implode(",", $dias_folga) : '';

        // Inserir dados do expediente (incluindo dias de folga)
        $sql_expediente = "INSERT INTO expediente_funcionario (funcionario_id, hora_inicio, hora_fim, dias_folga) 
                           VALUES ('$funcionario_id', '$hora_inicio', '$hora_fim', '$dias_folga_string')";
        $conn->query($sql_expediente);

        // Inserir os serviços (se houver)
        if (isset($_POST['servicos']) && !empty($_POST['servicos'])) {
            foreach ($_POST['servicos'] as $servico_id) {
                $sql_servico = "INSERT INTO funcionario_servico (funcionario_id, servico_id) 
                                VALUES ('$funcionario_id', '$servico_id')";
                $conn->query($sql_servico);
            }
        }

        // Sucesso
        header("Location: ../admin_usuarios.php");
    } else {
        echo "Erro ao criar funcionário: " . $conn->error;
    }

    // Fechar conexão
    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
?>