<?php
session_start();

// Verificar se o ID foi passado na URL
if (!isset($_GET['id_reg'])) {
    die("ID não fornecido.");
}

$id_reg = $_GET['id_reg'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    // Aqui você deve validar o login e senha com seu banco de dados ou outro sistema de autenticação
    if ($login === 'CPF1' || // CPF1
        $login === 'CPF2' || // CPF2
        $login === 'CPF3') { // CPF3
        $_SESSION['autorizado'] = true;
        header("Location: pagina_destino.php?id_reg=$id_reg");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos.";
    }
}
?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <title>SIS ORGÃOS ♥</title>
    <meta charset="utf-8">
    <meta name="author" content="Machado">
    <meta name="viewport" content="width-device-width, initial-scale = 1, shrink-to-fit=no">
    <link rel="icon" type="imagem/png" href="img/icone_procape.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/estiloz.css">
</head>
<body>
    <br/><br/><br/>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6">
                <img src="img/logo-procape.png" class="img-fluid centralizada">
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark mt-3" style="background-color:#34404e; border-radius: 3px; height: 50px;width: 550px;margin:0px auto;">
            <a class="navbar-brand" href="#">
                <h4>Login para edição</h4>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsite">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsite">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">INTRANET</a>
                    </li>
                </ul>
            </div>
        </nav>
        <br><br>
        <h3 style="width: 550px;">Identifique-se !</h3></br>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?id_reg=<?php echo htmlspecialchars($id_reg); ?>" method="post" autocomplete='off'>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nome"><strong>Login</strong></label>
                        <input type="text" name="login" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="senha"><strong>Senha</strong></label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button class="btn btn-danger" type="submit" style="width: 150px;">Entrar</button>
                </div>
                <div class="col-md-3"></div>
            </div>
        </form>
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger mt-3">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
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
