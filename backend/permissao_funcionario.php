<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['permissao'])) {
    session_destroy();
    header("Location: index.php"); // Redireciona para login se não estiver autenticado
    exit();
}

// Verificar se o usuário tem permissão para acessar esta página
if ($_SESSION['permissao'] == 'cliente') {
    header("Location: ../agendamentos.php");
    exit();
} else if ($_SESSION['permissao'] == 'admin') {
    header("Location: ../admin_agenda.php");
    exit();
}
?>