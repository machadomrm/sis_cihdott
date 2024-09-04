<?php
if (!isset($_SESSION)) {
    session_start();
}

if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:http://example.com/diretorio/index.php');
}

$logado = $_SESSION['login'];

$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");
if (mysqli_connect_errno()) {
    echo "Erro" . mysqli_connect_error();
}

$registro = $_GET['registro'];
$pesquisa = $conn->query("SELECT * FROM pacientes WHERE registro='$registro'");
$paciente = $pesquisa->fetch_assoc();

// Função para formatar a data do banco para dd/mm/aaaa
function formatarData($data) {
    if ($data && $data != '0000-00-00') {
        $dateTime = DateTime::createFromFormat('Y-m-d', $data);
        return $dateTime ? $dateTime->format('d/m/Y') : '';
    }
    return '';
}
?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <title>CIHDOTT</title>
    <meta http-equiv="refresh" content="900">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="Alex">
    <meta name="viewport" content="width-device-width, initial-scale = 1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" integrity="sha384-v8BU367qNbs/aIZIxuivaU55N5GPF89WBerHoGA4QTcbUjYiLQtKdrfXnqAcXyTv" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6">
                <img src="img/logo-procape.png" class="img-fluid">
            </div>
            <div class="col-md-6">
                <img src="img/logo.png" class="img-fluid" style="position: relative; float:right">
            </div>
        </div>
        
        <nav class="navbar navbar-expand-lg navbar-dark mt-3" style="background-color:#34404e; border-radius: 3px; height: 50px;">
            <a class="navbar-brand" href="index.php">
                <h4>CIHDOTT [ <span style="color: #66CDAA;">Resultado da Pesquisa</span> ]</h4>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsite">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsite">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="http://example.com/diretorio/index.php" class="nav-link"><span style="color: #B0E0E6;">SAIR</span></a>
                    </li>
                </ul>
            </div>
        </nav>

        <br>

        <div class="container">
            <div class="row mt-5">
                <table class='table table-striped'>
                    <thead>
                        <tr>                            
                            <th scope='col'>Nome</th>
                            <th scope='col'>Idade</th>
                            <th scope='col'>Data de Nascimento</th>
                            <th scope='col'>Data de Óbito</th>
                            <th scope='col'>Hora</th>
                            <th scope='col'>Registro</th>
                            <th scope='col'>Diagnóstico de Óbito</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>                            
                            <td><?php echo htmlspecialchars($paciente['nome_paciente']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['idade']); ?></td>
                            <td><?php echo htmlspecialchars(formatarData($paciente['dt_nasc'])); ?></td>
                            <td><?php echo htmlspecialchars(formatarData($paciente['dt_obito'])); ?></td>
                            <td><?php echo htmlspecialchars($paciente['hora']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['registro']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['diag_obito']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope='col'>Setor</th>
                            <th scope='col'>Subsetor</th>
                            <th scope='col'>Código</th>
                            <th scope='col'>Doação</th>
                            <th scope='col'>Responsável</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($paciente['setor']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['subsetor']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['codigo']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['doacao']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['responsavel']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope='col'>Outros / Observação</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($paciente['descricao']); ?></td>                           
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-md-7">
                <a href="pesquisar_pagina_destino.php"><button class="btn btn-danger" type="button" style="width: 150px;">Voltar</button></a>&nbsp;&nbsp;                
            </div>
            <div class="col-md-2"></div>
        </div><br>
        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">&copy; 2024 Procape</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">Privacidade</a></li>
                <li class="list-inline-item"><a href="#">Termos</a></li>
                <li class="list-inline-item"><a href="#">Suporte</a></li>
            </ul>
        </footer>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>    
    </div>
</body>
</html>
