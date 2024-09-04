<?php
if (!isset($_GET['id_reg'])) {
    die('Erro: ID do registro não especificado.');
}

$id = $_GET['id_reg'];
?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <title>Identificação</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width-device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
</head>

<body>
<br/><br/><br/>
<div class="container">
    <div class="row mt-5">
        <div class="col-md-6">
            <center><img src="img/logo-procape.png" class="img-fluid" style="margin-left: 235px;"></center>
        </div>
        <div class="col-md-3">
            <img src="img/logo.png" class="img-fluid" style="position: relative; float:right">
        </div>
    </div>
    
    <nav class="navbar navbar-expand-lg navbar-dark mt-3" style="background-color:#34404e; border-radius: 3px; height: 50px;width: 550px;margin:0px auto;">
        <a class="navbar-brand" href="index.php">
            <h4>Validação de Formulário</h4></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsite">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsite">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link">PROCAPE</a>
                </li>
            </ul>
        </div>
    </nav>

    <br><br>    
    
    <center><h3 style="width: 550px;">Identifique-se !</h3></center></br>

    <form action="valida_form2.php" method="post">
        <div class="row">
            <div class="col-md-3"></div>

            <div class="col-md-3">      
                <div class="form-group">
                    <label for="nome"><strong>CPF</strong></label>       
                    <input type="text" name="login" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label for="senha"><strong>Senha</strong></label>       
                    <input type="password" name="senha" class="form-control" required> 
                    <input type="hidden" name="id_reg" value="<?php echo htmlspecialchars($id); ?>">
                </div>
            </div>

            <div class="col-md-3"></div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>

            <div class="col-md-6"><hr>
                <button class="btn btn-success" type="submit" style="width: 150px;"> Validar </button>&nbsp;&nbsp;&nbsp;
                <button class="btn btn-danger" type="button" style="width: 150px;" onclick='history.go(-1)'>Voltar</button>
            </div>

            <div class="col-md-3"></div>
        </div>
    </form>    
         
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
