<?php

$servidor = "localhost"; // Servidor MySQL
$usuario = "root"; // Usuário padrão do MySQL no localhost
$senha = ""; // Senha padrão (em branco no XAMPP e WAMP, pode ser diferente se configurado)
$banco = "barbearia"; // Nome do banco de dados

// Criando a conexão
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

?>