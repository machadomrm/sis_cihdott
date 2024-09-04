<?php

if (!isset($_SESSION)) {
    session_start();
}

if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:http://example.com/pagina_destino.php');
    exit; // Adicione exit após header para garantir que o script pare de executar
}

$logado = $_SESSION['login'];

$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");

if (mysqli_connect_errno()) {
    echo "Erro: " . mysqli_connect_error();
    exit; // Pare a execução se a conexão falhar
}

// Defina o número de registros por página
$limite = 10;

// Definindo o número da página atual com base no parâmetro 'pg'
$pg = (isset($_GET['pg']) && is_numeric($_GET['pg']) && $_GET['pg'] > 0) ? intval($_GET['pg']) : 1;

// Ajustar o offset com base no padrão de página personalizada
$offset = ($pg - 1) * $limite;

// Consulta para contar o número total de registros não validados (incluindo statusx NULL)
$contagem = $conn->query("SELECT COUNT(*) as total FROM pacientes WHERE statusx IS NULL OR statusx != 5");
$row_contagem = $contagem->fetch_assoc();
$quantidade = $row_contagem['total']; // Total de registros não validados
$qtd_paginas = ceil($quantidade / $limite);

// Consulta para obter os registros da página atual que não estão validados (incluindo statusx NULL)
$pesquisa = $conn->query("SELECT id, nome_paciente, idade, dt_nasc, dt_obito, hora, registro, diag_obito, setor, subsetor, codigo, doacao, responsavel 
                          FROM pacientes 
                          WHERE statusx IS NULL OR statusx != 5 
                          ORDER BY id DESC 
                          LIMIT $limite OFFSET $offset");

if (!$pesquisa) {
    die("Erro na consulta de pacientes: " . mysqli_error($conn));
}

?>

<!DOCTYPE HTML>
<html lang="pt-br">

<head>
    <title>Relação CIHDOTT</title>
    <meta http-equiv="refresh" content="900">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="dev">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="img/icone_procape.ico">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" integrity="sha384-v8BU367qNbs/aIZIxuivaU55N5GPF89WBerHoGA4QTcbUjYiLQtKdrfXnqAcXyTv" crossorigin="anonymous">
</head>

<body>
<div class="container"> <!-- Inicio da Página -->

    <div class="row mt-2">
        <div class="col-md-6">
            <img src="img/logo-procape.png" class="img-fluid">
        </div>
        <div class="col-md-6">
            <img src="img/logo.png" class="img-fluid" style="position: relative; float:right">
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark mt-3" style="background-color:#34404e; border-radius: 3px; height: 50px;">
        <a class="navbar-brand" href="index.php">
            <h4>Relação de CIHDOTT </h4>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsite">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsite">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <!-- <a href="clear.php" class="nav-link"><span style="color: #B0E0E6;">SAIR</span></a> -->
                    <a href="http://example.com/diretorio/pagina_destino.php" class="nav-link"><span style="color: #B0E0E6;">SAIR</span></a>

                </li>
            </ul>
        </div>
    </nav>

    <br>

    <div class="row">
        <div class="col-md-6">          
        </div>
        <div class="col-md-6" style="text-align: right;">
            <a href='pagina_destino.php'><button class="btn btn-warning btn-sm" type="button"><i class="fas fa-user-plus"></i>&nbsp;&nbsp;Cadastrar</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href='pagina_destino.php'><button class="btn btn-success btn-sm" type="button"><i class="fas fa-search"></i>&nbsp;&nbsp;Pesquisar</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href='pagina_destino/index.php'><button class="btn btn-primary btn-sm" type="button"><i class="fas fa-file-invoice"></i>&nbsp;&nbsp;Relatório</button></a>
        </div>
    </div>

    <div class='container'>
        <div class='row mt-5'>
            <table class='table table-striped'>
                <thead>
                    <tr>
                        <th scope='col'>ID</th>
                        <th scope='col'>Registro</th>
                        <th scope='col'>Nome Paciente</th>
                        <th scope='col'>Idade</th>  
                        <th scope='col'>Data Nasc.</th>                  
                        <th scope='col'>Data Óbito</th>
                        <th scope='col'>Hora</th>                          
                        <th scope='col'>Responsável</th>
                        <th scope='col'>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($row = $pesquisa->fetch_assoc()) {
                    $id = $row['id'];
                    $nome_paciente = $row['nome_paciente'];
                    $idade = $row['idade'];
                    $dt_nasc = implode("/", array_reverse(explode("-", $row['dt_nasc'])));
                    $dt_obito = implode("/", array_reverse(explode("-", $row['dt_obito'])));
                    $hora = date("H:i", strtotime($row['hora']));
                    $registro = $row['registro'];
                    $responsavel = $row['responsavel'];
                
                    $botaox = "<a href='detalhes_pagina_destino.php?id_reg={$row['id']}'><input type='button' value='visualizar'></a>";

                    echo "
                    <tr>
                        <th scope='row'>$id</th>
                        <td>$registro</td>
                        <td>$nome_paciente</td>
                        <td>$idade</td>
                        <td>$dt_nasc</td>
                        <td>$dt_obito</td>
                        <td>$hora</td>
                        <td>$responsavel</td>
                        <td>$botaox</td>
                    </tr>
                    ";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

<?php

echo "<ul class='pagination justify-content-center'>";

// Verifica se existe uma página anterior
if ($pg > 1) {
    echo "<li class='page-item'><a class='page-link' href='relacao_pagina_destino.php?pg=" . ($pg - 1) . "'>Página Anterior</a></li>";
}

// Exibe a numeração das páginas
for ($i = 1; $i <= $qtd_paginas; $i++) {        
    if ($pg == $i) {
        echo "<li class='page-item active'><a class='page-link' href='relacao_pagina_destino.php?pg=$i'>$i</a></li>";
    } else {
        echo "<li class='page-item'><a class='page-link' href='relacao_pagina_destino.php?pg=$i'>$i</a></li>";
    }
}

// Verifica se existe uma próxima página
if ($pg < $qtd_paginas) {
    echo "<li class='page-item'><a class='page-link' href='relacao_pagina_destino.php?pg=" . ($pg + 1) . "'>Próxima Página</a></li>";
}

echo "</ul>";

?>

    <hr><br>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; 2024 Procape</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Privacidade</a></li>
            <li class="list-inline-item"><a href="#">Termos</a></li>
            <li class="list-inline-item"><a href="#">Suporte</a></li>
        </ul>
    </footer>

</div>

</body>

</html>
