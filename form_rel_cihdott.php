<?php
session_start();

if (!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('Location: index.php');
    exit(); // Sempre use exit após header para garantir que o código seguinte não seja executado
}

$logado = $_SESSION['login'];
?>

<!DOCTYPE HTML>
<html lang="pt-br">

<head>
    <title>Relatório CIHDOTT</title>
    <meta http-equiv="refresh" content="900">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="dev">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6">
                <img src="../img/logo-procape.png" class="img-fluid" alt="Logo Procape">
            </div>
            <div class="col-md-6 text-right">
                <img src="../img/logo.png" class="img-fluid" alt="Logo">
            </div>
        </div>
        
        <nav class="navbar navbar-expand-lg navbar-dark mt-3" style="background-color:#34404e; border-radius: 3px; height: 50px;">
            <a class="navbar-brand" href="index.php">
                <h4>Relatório CIHDOTT</h4>
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

        <br><br>
        
        <h3 class="text-center">Selecione o período que deseja gerar o relatório</h3>
        <br><br>

        <form action="gerar_relatorio_pagina_destino.php" method="post">
            <div class="row">
                <div class="col-md-2">
                    <label for="data_ini"><strong>Data Inicial</strong></label>
                    <input type="date" name="data_ini" id="data_ini" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="data_fim"><strong>Data Final</strong></label>
                    <input type="date" name="data_fim" id="data_fim" class="form-control" required>
                </div>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="col-md-5">
                    <button class="btn btn-success" type="submit" style="width: 150px;">Gerar Relatório</button>
                    <button class="btn btn-danger" type="button" style="width: 150px;" onclick="window.location.href='<?php echo $base_url; ?>/pagina_destino.php';">Voltar</button> 
                </div>
            </div>
        </form>

        <hr>

        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">&copy; 2024 Procape</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">Privacidade</a></li>
                <li class="list-inline-item"><a href="#">Termos</a></li>
                <li class="list-inline-item"><a href="#">Suporte</a></li>
            </ul>
        </footer>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>
