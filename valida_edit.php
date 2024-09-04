<?php
session_start();

// IDs de usuários permitidos
$usuarios_permitidos = [
    '12345678909', // User teste
    '11111111111', // User teste2
    '22222222222', // User teste3
];

// Verifica se o usuário está autorizado
if (!isset($_SESSION['autorizado']) || !$_SESSION['autorizado']) {
    header('Location: login_edit.php');
    exit;
}

// Verifica se o ID do usuário está na lista de permitidos
if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['id_usuario'], $usuarios_permitidos)) {
    die("Acesso negado.");
}

if (!isset($_GET['id_reg'])) {
    die("ID não fornecido.");
}

$id_reg = $_GET['id_reg'];

// Aqui você pode adicionar a lógica para validar a sessão do usuário
// Por exemplo, verificar se o usuário tem permissões para editar

header("Location: editar_form_pagina_destino.php?id_reg=$id_reg");
exit;
?>
