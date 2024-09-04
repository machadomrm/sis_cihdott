<?php
// Ativa a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Recupera os dados enviados pelo formulário
if (!isset($_POST['id_reg'])) {
    die('Erro: ID do registro não recebido.');
}

$id = $_POST['id_reg'];
$user = $_POST['login'];
$senha = $_POST['senha'];

// Verifica se a senha está vazia
if (empty($senha)) {
    echo "<meta http-equiv='refresh' content='1;url=index.php'>";
    die('► Senha Inválida ! ◄');
}

// Verifica se o CPF é válido
$valid_cpfs = ['11111111111', // User teste1
               '11111111111', // User teste2
               '22222222222']; //User teste3
if (!in_array($user, $valid_cpfs)) {
    echo "<meta http-equiv='refresh' content='1;url=relacao_pagina_destino.php'>";
    die('► CPF Inválido ! ◄');
}

// Conexão com o banco de dados
$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");

// Verifica a conexão com o banco de dados
if (mysqli_connect_errno()) {
    die("Erro de conexão: " . mysqli_connect_error());
}

// Obtém o nome do usuário a partir do banco de dados
$pesquisa = $conn->query("SELECT nome FROM usuarios WHERE cpf='$user'");

if (!$pesquisa) {
    die("Erro na consulta de usuário: " . mysqli_error($conn));
}

$row = $pesquisa->fetch_assoc();

if (!$row) {
    die("Erro: CPF não encontrado na tabela de usuários.");
}

$nome = $row['nome'];

// Adiciona log para verificar o que está sendo atualizado
echo "Atualizando paciente com ID: $id para statusx = 5, validador = $nome";

// Atualiza o status do paciente e define o validador
$sql_code = "UPDATE pacientes SET statusx = 5, validador = ? WHERE id = ?";
$stmt = $conn->prepare($sql_code);
$stmt->bind_param("si", $nome, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows === 1) {
        echo "<script>alert('Formulário validado com sucesso.');location.href='relacao_pagina_destino.php';</script>";
    } else {
        echo "Nenhuma linha foi atualizada. Verifique se o ID do paciente está correto.";
    }
} else {
    die("Erro ao validar formulário: " . $conn->error);
}

// Fecha a conexão com o banco de dados
$stmt->close();
$conn->close();
?>
