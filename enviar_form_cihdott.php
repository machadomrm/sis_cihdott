<?php

$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");

if (mysqli_connect_errno()) {
    echo "Erro: " . mysqli_connect_error();
    exit();
}

// Receber dados do formulário
$registro = $_POST['registro'];
$nome_paciente = strtoupper($_POST['nome_paciente']);
$idade = trim($_POST['age']); 
$dt_nasc = $_POST['data_nasc']; 
$dt_obito = $_POST['data_obito']; 
$hora = $_POST['hora_obito']; 
$diag_obito = strtoupper($_POST['diag_obito']);
$setor_id = strtoupper($_POST['setor']); 
$subsetor = strtoupper($_POST['subsetoresx']); 
$codigo = $_POST['codigo'];
$doacao = $_POST['doacao'];
$responsavel = strtoupper($_POST['responsavel']);
$descricao = strtoupper($_POST['descricao']);

// Verificar se o registro já existe
$pesquisa = $conn->query("SELECT * FROM pacientes WHERE registro = '$registro'");

if (mysqli_num_rows($pesquisa) >= 1) {
    echo "<meta http-equiv='refresh' content='4;url=pagina_destino.php'>";
    die('► ERRO! Este Registro já foi cadastrado. ◄');
}

// Validar os dados
if (empty($registro) || empty($nome_paciente) || empty($dt_nasc) || empty($dt_obito) || empty($descricao)) {
    die('► ERRO! Campos obrigatórios não foram preenchidos. ◄');
}

// Verificar se a idade é um número inteiro
if (!is_numeric($idade) || (int)$idade != $idade) {
    die('► ERRO! O campo idade deve ser um número inteiro. ◄');
}

// Validar se a data de óbito não é do mês corrente
$currentMonth = date('Y-m');
$obitoMonth = date('Y-m', strtotime($dt_obito)); // Mês e ano do óbito

// Obter o nome do setor com base no ID
$setor_result = $conn->query("SELECT nome FROM os.setores WHERE id_setor = '$setor_id'");
if ($setor_result->num_rows > 0) {
    $setor_row = $setor_result->fetch_assoc();
    $setor_nome = $setor_row['nome'];
} else {
    die('► ERRO! Setor inválido. ◄');
}

// Obter o próximo valor de qtd_mes para o mês correto
$sql_qtd_mes = "SELECT MAX(qtd_mes) AS max_qtd_mes FROM pacientes WHERE DATE_FORMAT(dt_obito, '%Y-%m') = ?";
$stmt_qtd_mes = $conn->prepare($sql_qtd_mes);
if (!$stmt_qtd_mes) {
    die("Erro na preparação da consulta: " . $conn->error);
}
$stmt_qtd_mes->bind_param("s", $obitoMonth);
$stmt_qtd_mes->execute();
$result_qtd_mes = $stmt_qtd_mes->get_result();

if ($result_qtd_mes && $result_qtd_mes->num_rows > 0) {
    $row_qtd_mes = $result_qtd_mes->fetch_assoc();
    $last_qtd_mes = $row_qtd_mes['max_qtd_mes'];
    $qtd_mes = ($last_qtd_mes !== null) ? $last_qtd_mes + 1 : 1; // Se não houver registros, começa com 1
} else {
    $qtd_mes = 1; // Se não houver registros, inicia com 1
}

// Inserir dados na tabela pacientes
$sql_code = "INSERT INTO pacientes (
    registro, 
    nome_paciente, 
    idade, 
    dt_nasc, 
    dt_obito, 
    hora, 
    diag_obito, 
    setor, 
    subsetor, 
    codigo, 
    doacao, 
    responsavel,
    descricao,
    qtd_mes
) VALUES (
    '$registro',
    '$nome_paciente', 
    ".($idade !== '' ? (int)$idade : 'NULL').", 
    '$dt_nasc', 
    '$dt_obito', 
    '$hora', 
    '$diag_obito', 
    '$setor_nome', 
    '$subsetor', 
    '$codigo', 
    '$doacao', 
    '$responsavel',
    '$descricao',
    $qtd_mes
)";

$insert = mysqli_query($conn, $sql_code);

if (!$insert) {
    die("Erro: " . mysqli_error($conn));
}

echo "<script>alert('Registro cadastrado com sucesso.');location.href='pagina_destino.php';</script>";

?>
