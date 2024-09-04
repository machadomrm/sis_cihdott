<?php
// Inicia a sessão para gerenciar informações do usuário logado
session_start();

// Verifica se o usuário está autenticado (login e senha devem estar na sessão)
if (!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
    header('location: login_pagina_destino.php');
    exit();
}

// Conecta ao banco de dados
$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");

// Verifica se a conexão ao banco de dados foi bem-sucedida
if (mysqli_connect_errno()) {
    echo "Erro: " . mysqli_connect_error();
    exit();
}

// Recebe os dados do formulário
$id = $_POST['id'];
$nome_paciente = mb_strtoupper($_POST['nome_paciente'], "utf-8");
$idade = $_POST['idade'];
$data_nasc = $_POST['data_nasc'];
$data_obito = $_POST['data_obito'];
$hora_obito = $_POST['hora_obito'];
$registro = $_POST['registro'];
$diag_obito = $_POST['diag_obito'];
$codigo = $_POST['codigo'];
$setor = $_POST['setor'];
$subsetor = $_POST['subsetor'];
$doacao = $_POST['doacao'];
$responsavel = $_POST['responsavel'];
$descricao = $_POST['descricao'];

// Escapa caracteres especiais
$nome_paciente = mysqli_real_escape_string($conn, $nome_paciente);
$idade = mysqli_real_escape_string($conn, $idade);
$data_nasc = mysqli_real_escape_string($conn, $data_nasc);
$data_obito = mysqli_real_escape_string($conn, $data_obito);
$hora_obito = mysqli_real_escape_string($conn, $hora_obito);
$registro = mysqli_real_escape_string($conn, $registro);
$diag_obito = mysqli_real_escape_string($conn, $diag_obito);
$codigo = mysqli_real_escape_string($conn, $codigo);
$setor = mysqli_real_escape_string($conn, $setor);
$subsetor = mysqli_real_escape_string($conn, $subsetor);
$doacao = mysqli_real_escape_string($conn, $doacao);
$responsavel = mysqli_real_escape_string($conn, $responsavel);
$descricao = mysqli_real_escape_string($conn, $descricao);

// Atualiza os dados na tabela pacientes
$sql_code = "UPDATE pacientes SET 
    nome_paciente='$nome_paciente',
    idade='$idade',
    dt_nasc='$data_nasc',
    dt_obito='$data_obito',
    hora='$hora_obito',
    registro='$registro',
    diag_obito='$diag_obito',
    codigo='$codigo',
    setor='$setor',
    subsetor='$subsetor',
    doacao='$doacao',
    responsavel='$responsavel',
    descricao='$descricao'
    WHERE id='$id'";

$update = mysqli_query($conn, $sql_code);

// Verifica se a atualização foi bem-sucedida
if ($update) {
    echo "<script>alert('Informações atualizadas com sucesso.'); location.href='relacao_pagina_destino.php';</script>";
} else {
    // Exibe o erro e a consulta SQL para depuração
    echo "<script>alert('Erro ao atualizar informações: " . $conn->error . "');</script>";
    echo "<p>Erro ao atualizar informações. Query SQL: $sql_code</p>";
    echo "<a href='editar_form_pagina_destino.php?id_reg=$id'>Voltar</a>";
}
?>
