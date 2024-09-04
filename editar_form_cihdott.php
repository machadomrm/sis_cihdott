<?php
// Inicia a sessão para gerenciar informações do usuário logado
session_start();

// Verifica se o usuário está autenticado (login e senha devem estar na sessão)
if (!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
    header('location: pagina_destino.php');
    exit();
}

$logado = $_SESSION['login']; // Obtém o login do usuário da sessão

// Conecta ao banco de dados
$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");

// Verifica se a conexão ao banco de dados foi bem-sucedida
if (mysqli_connect_errno()) {
    echo "Erro: " . mysqli_connect_error(); // Exibe a mensagem de erro, se houver
    exit(); // Encerra o script
}

// Recebe o ID do paciente da URL
$id = $_GET['id_reg']; 

// Verifica se o ID foi fornecido
if (!$id) {
    die("ID não fornecido."); // Exibe mensagem de erro se o ID não estiver presente
}

// Executa a consulta para buscar o paciente com o ID fornecido
$pesquisa = $conn->query("SELECT * FROM pacientes WHERE id = '$id'");

// Verifica se a consulta foi bem-sucedida
if (!$pesquisa) {
    die("Erro na consulta: " . $conn->error); // Exibe mensagem de erro se a consulta falhar
}

// Obtém os dados do paciente como um array associativo
$row = $pesquisa->fetch_assoc();

// Verifica se o paciente foi encontrado
if (!$row) {
    die("Registro não encontrado."); // Exibe mensagem de erro se o registro não for encontrado
}

// Armazena os dados do paciente em variáveis
$nome_paciente = $row['nome_paciente'];
$idade = $row['idade'];
$data_nasc = $row['dt_nasc'];
$data_obito = $row['dt_obito'];
$hora_obito = $row['hora'];
$registro = $row['registro'];
$diag_obito = $row['diag_obito'];
$codigo = $row['codigo'];
$setor = $row['setor'];
$subsetor = $row['subsetor'];
$doacao = $row['doacao'];
$responsavel = $row['responsavel'];
$descricao = $row['descricao'];
?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <title>★ CIHDOTT</title>
    <meta http-equiv="refresh" content="900"> <!-- Atualiza a página a cada 15 minutos -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="DEV">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- Inclui o CSS do Bootstrap -->
    <link rel="stylesheet" type="text/css" href="css/estilo.css"> <!-- Inclui o CSS personalizado -->
    <script>
        function goBack() {
            window.history.back(); // Função JavaScript para voltar à página anterior
        }
    </script>
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
            <h4>CIHDOTT</h4>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsite">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsite">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="clear.php" class="nav-link"><span style="color: #B0E0E6;">SAIR</span></a>
                </li>
            </ul>
        </div>
    </nav>
    <br><br>

	<!-- SALVAR DADOS -->
	 <!-- Formulário para edição dos dados do paciente -->
    <form action="pagina_destino.php" method="post"> 

        <h3 style="margin: 0px auto;">EDITAR REGISTRO DE ÓBITO</h3></br>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nome"><strong>Nome do Paciente</strong></label>
                    <input type="text" name="nome_paciente" class="form-control validate-letters" style="text-transform: uppercase;"value="<?php echo htmlspecialchars($nome_paciente); ?>">
                    <div class="error-message" style="color: red; display: none;">Apenas letras e espaços são permitidos.</div>
                </div>
			</div>	

			<div class="col-md-1">
                <div class="form-group">
                    <label for="idade"><strong>Idade</strong></label>
                    <input type="text" name="idade" class="form-control" value="<?php echo htmlspecialchars($idade); ?>" 
                        pattern="\d{1,3}" inputmode="numeric" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
            </div>

			
			<div class="col-md-2">
				<div class="form-group">
					<label for="data_nasc"><strong>Data de Nascimento</strong></label>
					<input type="date" name="data_nasc" class="form-control" value="<?php echo htmlspecialchars($data_nasc); ?>">
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
                    <label for="data_obito"><strong>Data do Óbito</strong></label>
                    <input type="date" name="data_obito" class="form-control" value="<?php echo htmlspecialchars($data_obito); ?>">
                </div>
	 		</div>

			 <div class="col-md-2" id="hora_obito">
                <div class="form-group">
                    <label for="hora_obito"><strong>Hora do Óbito</strong></label>
                    <input type="time" name="hora_obito" class="form-control" value="<?php echo htmlspecialchars($hora_obito); ?>">
                </div>
			</div>
		</div>	

		<div class="row">

        <div class="col-md-2">
            <div class="form-group">
                <label for="registro"><strong>Registro</strong></label>
                <input type="text" name="registro" class="form-control" value="<?php echo htmlspecialchars($registro); ?>" 
                    pattern="\d*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
        </div>
			<div class="col-md-7">
                <div class="form-group">
                    <label for="diag_obito"><strong>Diagnóstico do Óbito</strong></label>
                    <input type="text" name="diag_obito" class="form-control validate-letters" placeholder="" style="text-transform: uppercase;" value="<?php echo htmlspecialchars($diag_obito); ?>">
                    <div class="error-message" style="color: red; display: none;">Apenas letras e espaços são permitidos.</div>
                </div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label for="codigo"><strong>Código</strong></label>
                    <input type="text" name="codigo" class="form-control" value="<?php echo htmlspecialchars($codigo); ?>"
                        pattern="\d*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
				</div>
			</div>	

        </div>


		<div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label for="setor"><strong>Setor</strong></label>
                    <input type="text" name="setor" class="form-control" value="<?php echo htmlspecialchars($setor); ?>">
                </div>
			</div>

			<div class="col-md-3">	
                <div class="form-group">
                    <label for="subsetor"><strong>Subsetor</strong></label>
                    <input type="text" name="subsetor" class="form-control" value="<?php echo htmlspecialchars($subsetor); ?>">
                </div>
			</div>

			<div class="col-md-2">
                <div class="form-group">
                    <label for="doacao"><strong>Doação</strong></label>
                    <input type="text" name="doacao" class="form-control" value="<?php echo htmlspecialchars($doacao); ?>">
                </div>
			</div>

			<div class="col-md-2">
                <div class="form-group">
                    <label for="responsavel"><strong>Responsável</strong></label>
                    <input type="text" name="responsavel" class="form-control" value="<?php echo htmlspecialchars($responsavel); ?>">
                </div>
			</div>
		</div>

        <div class="row">
            <div class="col-md-9">
                <div class="form-group">
                    <label for="descricao"><strong>Outros / Observação</strong></label>
                    <input type="text" name="descricao" class="form-control" style="text-transform: uppercase;" value="<?php echo htmlspecialchars($descricao); ?>">
                </div>
            </div>            
        </div>


            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

			<hr>

		<div class="row">
			<div class="col-md-7">
				<button class="btn btn-success" type="submit" style="width: 150px;">Salvar Dados</button></a>&nbsp;&nbsp;
                <button class="btn btn-danger" onclick="window.location.href='relacao_cihdott.php'" type="button" style="width: 150px;">Cancelar</button>
                </div>
        </div>
        
    </form>

	<br>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; 2024 Procape</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Privacidade</a></li>
            <li class="list-inline-item"><a href="#">Termos</a></li>
            <li class="list-inline-item"><a href="#">Suporte</a></li>
        </ul>
    </footer>
</div>

<!-- Script para validação em tempo real -->
<script>
    // Seleciona todos os campos de texto que devem ser validados
    document.querySelectorAll('.validate-letters').forEach(function (inputField) {
        inputField.addEventListener('input', function (e) {
            // Define um padrão que permite apenas letras (incluindo acentos) e espaços
            const pattern = /^[A-Za-zÀ-ÿ\s]*$/;
            // Obtém o valor atual do campo de entrada
            const input = e.target.value;
            // Obtém o elemento da mensagem de erro associado ao campo atual
            const errorMessage = e.target.parentNode.querySelector('.error-message');

            // Verifica se o valor do campo de entrada não corresponde ao padrão definido
            if (!pattern.test(input)) {
                // Se houver caracteres inválidos, exibe a mensagem de erro
                errorMessage.style.display = 'block';
                // Remove caracteres inválidos substituindo-os por uma string vazia
                e.target.value = input.replace(/[^A-Za-zÀ-ÿ\s]/g, '');
            } else {
                // Oculta a mensagem de erro se não houver caracteres inválidos
                errorMessage.style.display = 'none';
            }
        });
    });
</script>	

<!-- Inclui scripts JavaScript do Bootstrap e personalizados -->
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
