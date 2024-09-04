<?php
if (!isset($_SESSION)) {
    session_start();
}

if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('location:http://example.com/pagina_destino.php');
}

$logado = $_SESSION['login'];

$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");

if (mysqli_connect_errno()) {
    echo "Erro" . mysqli_connect_error();
}
?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <title>CIHDOTT</title>
    <meta http-equiv="refresh" content="900">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="Machado">
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
                <h4>CIHDOTT [ <span style="color: #66CDAA;">Pesquisar Registro</span> ]</h4>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsite">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsite">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="http://example.com/diretorio/pagina_destino.php" class="nav-link"><span style="color: #B0E0E6;">SAIR</span></a>
                    </li>
                </ul>
            </div>
        </nav>

        <br><br>
        <h3 style="margin: 0px auto;">FORMULÁRIO</h3></br>
        <form action="pagina_destino.php" method="post">
            <div class="row">
                <div class="col-md-3"><label><strong>Digite Registro do Paciente</strong></label></div>
            </div>
            <div class="row">
                <div class="col-md-2"><input type="text" id="registro" name="registro" class="form-control"></div>
                <div class="col-md-3"><button class="btn btn-success" type="submit"  style="width: 150px;"> Pesquisar </button></div>
            </div>
        </form> 
        <br>
        <hr>
        <div class="row">
            <div class="col-md-7">
                <a href="pagina_destino2.php"><button class="btn btn-danger" type="button" style="width: 150px;">Voltar</button></a>&nbsp;&nbsp;                
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
