<?php

if (!isset($_SESSION)) {
    session_start();
}

if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
    unset($_SESSION['login']);
    unset($_SESSION['senha']);
    header('Location: http://example.com/pagina_destino.php');
    exit;
}

$logado = $_SESSION['login'];

$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");
if (mysqli_connect_errno()) {
    echo "Erro" . mysqli_connect_error();
    exit;
}

$id_reg = $_GET['id_reg'];
$pesquisa = $conn->query("SELECT * FROM pacientes WHERE id = '$id_reg'");

if ($pesquisa === false) {
    die("Erro na consulta: " . $conn->error);
}

$row = $pesquisa->fetch_assoc();
if (!$row) {
    die("Nenhum registro encontrado para o ID fornecido.");
}

?>

<!DOCTYPE HTML>
<html lang="pt-br">

<head>
    <title>★ CIHDOTT - Detalhes</title>
    <meta http-equiv="refresh" content="900">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="DEV">
    <meta name="viewport" content="width-device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
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
            <h4>Relação de CIHDOTT - Detalhes</h4>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsite">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsite">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="http://example.com/pagina_destino.php" class="nav-link"><span style="color: #B0E0E6;">SAIR</span></a>
                </li>
            </ul>
        </div>
    </nav>

    <br><br>

    <h3 style="margin: 0px auto;">DETALHES DO PACIENTE</h3></br>

    <div class="row">
        <div class="col-md-4" id="nome">
            <div class="form-group">
                <label for="nome"><strong>Nome Paciente</strong></label>
                <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['nome_paciente']); ?></p>
            </div>
        </div>
        <div class="col-md-2" id="idade">
            <div class="form-group">
                <label for="idade"><strong>Idade</strong></label>
                <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['idade']); ?></p>
            </div>
        </div>
        <div class="col-md-4" id="data_nasc">
            <div class="form-group">
                <label for="data_nasc"><strong>Data de Nascimento</strong></label>
                <p style='background:#DCDCDC;'><?php echo date("d/m/Y", strtotime($row['dt_nasc'])); ?></p>
            </div>
        </div>
    </div>
    <!-- ESPAÇO ENTRE LINHAS -->
    <div class="row">
        <div class="col-md-4" id="data_obito">
            <div class="form-group">
                <label for="data_obito"><strong>Data de Óbito</strong></label>
                <p style='background:#DCDCDC;'><?php echo date("d/m/Y", strtotime($row['dt_obito'])); ?></p>
            </div>
        </div>
        <div class="col-md-2" id="hora">
            <div class="form-group">
                <label for="hora"><strong>Hora</strong></label>
                <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['hora']); ?></p>
            </div>
        </div>
        <div class="col-md-2" id="registro">
            <div class="form-group">
                <label for="registro"><strong>Registro</strong></label>
                <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['registro']); ?></p>
            </div>
        </div>
        <div class="col-md-2" id="codigo">
            <div class="form-group">
                <label for="codigo"><strong>Código</strong></label>
                <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['codigo']); ?></p>
            </div>
        </div>
    </div>
    <!-- ESPAÇO ENTRE LINHAS -->
    <div class="row">
        <div class="col-md-4" id="diag_obito">
            <div class="form-group">
                <label for="diag_obito"><strong>Diagnóstico do Óbito</strong></label>
                <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['diag_obito']); ?></p>
            </div>
        </div>
        <div class="col-md-2" id="doacao">
            <div class="form-group">
                <label for="doacao"><strong>Doação</strong></label>
                <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['doacao']); ?></p>
            </div>
        </div>
        <div class="col-md-4" id="responsavel">
            <div class="form-group">
                <label for="responsavel"><strong>Responsável</strong></label>
                <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['responsavel']); ?></p>
            </div>
        </div>
    </div>
    <!-- ESPAÇO ENTRE LINHAS -->
    <div class="row">
        <div class="col-md-4" id="setor">
                <div class="form-group">
                    <label for="setor"><strong>Setor</strong></label>
                    <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['setor']); ?></p>
                </div>
        </div>
        <div class="col-md-6" id="subsetor">
            <div class="form-group">
                <label for="subsetor"><strong>Subsetor</strong></label>
                <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['subsetor']); ?></p>
            </div>
        </div>

    </div>
    <!-- ESPAÇO ENTRE LINHAS -->
    <div class="row">
        <div class="col-md-4" id="descricao">
                <div class="form-group">
                    <label for="descricao"><strong>Outros / Observação</strong></label>
                    <p style='background:#DCDCDC;'><?php echo htmlspecialchars($row['descricao']); ?></p>
                </div>
        </div>       
    </div>
 
    <br>
    <hr>

    <div class="row">
        <div class="col-md-7">
            <a href='pagina_destino.php?id_reg=<?php echo htmlspecialchars($row['id']); ?>'><button class="btn btn-success" type="button" style="width: 150px;">Editar Dados</button></a>&nbsp;&nbsp;
            <a href='pagina_destino.php?id_reg=<?php echo htmlspecialchars($row['id']); ?>'><button class="btn btn-primary" type="button" style="width: 150px;">Validar</button></a>&nbsp;&nbsp;
            <a href="pagina_destino.php"><button class="btn btn-danger" type="button" style="width: 150px;">Tabela Geral</button></a>
        </div>
    </div>
    <hr>

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
