<?php
	// Configura o PHP para exibir todos os erros, útil para depuração
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	// Inicia uma sessão se ainda não estiver iniciada
	if (!isset($_SESSION)) {
		session_start();
	}

	// Verifica se o usuário está autenticado
	if (!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
		// Remove variáveis de sessão de login e senha
		unset($_SESSION['login']);
		unset($_SESSION['senha']);
		// Redireciona o usuário para a página de login
		header('Location: http://example.com/pagina_destino.php');
		exit;
	}

    // Armazena o login do usuário autenticado na variável $logado
	$logado = $_SESSION['login'];

    // Conecta ao banco de dados MySQL
	$conn = new mysqli("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");

    // Verifica se houve erro na conexão com o banco de dados
	if ($conn->connect_error) {
		die("Erro de conexão: " . $conn->connect_error);
	}

	// Obtém o mês e ano atual
	$currentMonth = date('Y-m');
	

    // Consulta SQL para obter o maior valor de qtd_mes para o mês corrente
	$sql = "SELECT MAX(qtd_mes) AS max_qtd_mes FROM pacientes WHERE DATE_FORMAT(dt_obito, '%Y-%m') = ?";
	$stmt = $conn->prepare($sql);

	 // Verifica se a preparação da consulta foi bem-sucedida
	 if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

	// Vincula os parâmetros da consulta
	$stmt->bind_param("s", $currentMonth);

	// Executa a consulta
	$stmt->execute();
	$result = $stmt->get_result();

    // Se há resultados, obtém o maior valor de qtd_mes e incrementa em 1
	if ($result && $result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$last_qtd_mes = $row['max_qtd_mes'];

		// Define a numeração para o mês corrente
		$qtd_mes = ($last_qtd_mes !== null) ? $last_qtd_mes + 1 : 1; // Se não houver registros, começa com 1
	} else {
		// Se não há resultados, inicia com 1
		$qtd_mes = 1; 
	}

	//teste
	// echo "Valor de qtd_mes: " . $qtd_mes . "<br>";

    // Fecha a conexão com o banco de dados
	$conn->close();
?>

<!DOCTYPE HTML>
<html lang="pt-br">

	<head>
		<title>Form CIHDOTT ♥</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width-device-width, initial-scale = 1, shrink-to-fit=no">
		<!-- Inclui o CSS do Bootstrap -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- Ícone da página -->
		<link rel="icon" type="imagem/png" href="img/icone_procape.ico">
        <!-- Inclui o jQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<!-- Inclui o CSS personalizado -->
		<link rel="stylesheet" type="text/css" href="css/estilo.css">

		<script>
            // Função para validar o formulário antes do envio
			function validarFormulario() {
				var nomePaciente = document.forms["formRegistro"]["nome_paciente"].value;
				var age = document.forms["formRegistro"]["age"].value;
				var dataNasc = document.forms["formRegistro"]["data_nasc"].value;
				var dataObito = document.forms["formRegistro"]["data_obito"].value;
				var horaObito = document.forms["formRegistro"]["hora_obito"].value;
				var registro = document.forms["formRegistro"]["registro"].value;
				var diagObito = document.forms["formRegistro"]["diag_obito"].value;
				var setor = document.forms["formRegistro"]["setor"].value;
				var subSetor = document.forms["formRegistro"]["subsetoresx"].value;
				var doacao = document.forms["formRegistro"]["doacao"].value;
				var responsavel = document.forms["formRegistro"]["responsavel"].value;
				var descricao = document.forms["formRegistro"]["descricao"].value;

                // Verifica se todos os campos obrigatórios estão preenchidos
				if (nomePaciente == "" || age == "" || dataNasc == "" || dataObito == "" || horaObito == "" || registro == "" || diagObito == "" || setor == "" || subSetor == "" || doacao == "" || responsavel == "" || descricao == "") {
				alert("Por favor, preencha todos os campos obrigatórios.");
				return false;
			}

				return true;
			}
		</script>

<script>
        // Função para habilitar ou desabilitar o campo "Código" com base na seleção do campo "Doação"
        function atualizarCodigo() {
            var doacao = document.querySelector('select[name="doacao"]').value;
            var codigo = document.querySelector('select[name="codigo"]');
            
            if (doacao === "Sim") {
                codigo.disabled = true;
            } else {
                codigo.disabled = false;
            }
        }

        // Adiciona um evento para chamar a função quando o valor do dropdown "Doação" mudar
        document.addEventListener("DOMContentLoaded", function() {
            var doacaoSelect = document.querySelector('select[name="doacao"]');
            doacaoSelect.addEventListener('change', atualizarCodigo);
            
            // Chama a função uma vez para definir o estado inicial do campo "Código"
            atualizarCodigo();
        });
    </script>

	</head>

	<body> <!-- #F5E2E2 F5D6D6 -->

	<div class="container"> <!-- Inicio da Página -->

			<div class="row mt-5">
				<div class="col-md-6">
					<img src="img/logo-procape.png" class="img-fluid">
				</div>
				<div class="col-md-6">
					<!-- Logotipo adicional, posicionado à direita -->
					<img src="img/logo.png" class="img-fluid" style="position: relative; float:right">
				</div>
			</div>

            <!-- Barra de navegação -->
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
						<a href="http://example.com/pagina_destino.php" class="nav-link"><span style="color: #B0E0E6;">SAIR</span></a>
						</li>
					</ul>
				</div>
			</nav>

			<br><br>
	        
            <!-- Formulário de registro -->
	        <form name="formRegistro" action="pagina_destino.php" method="post" onsubmit="return validarFormulario()"> <!-- Começo dos Formulários -->

				<h3 style="margin: 0px auto;">REGISTRO DE ÓBITO</h3></br>

	      

				<div class="row">

					<div class="col-md-1" id="qtd_mes">
						<div class="form-group">
							<label for="qtd_mes"><strong>Qtd Mês</strong></label>                           
							<input type="text" id="qtd_mes" class="form-control" name="qtd_mes" value="<?php echo $qtd_mes; ?>" readonly>                  
						</div>
					</div>		
                    <!-- Outros campos de entrada, como nome, idade, etc. -->

					<div class="col-md-4" id="nome">
						<div class="form-group">
							<label for="nome_paciente"><strong>Nome Paciente </strong></label>
							<!-- Campo de entrada de texto para o nome do paciente -->
							<input type="text" name="nome_paciente" class="form-control validate-letters" placeholder="" style="text-transform: uppercase;" required pattern="[A-Za-zÀ-ÿ\s]+">
							<!-- Mensagem de erro que será exibida caso caracteres inválidos sejam digitados -->
							<div class="error-message" style="color: red; display: none;">Apenas letras e espaços são permitidos.</div>
						</div>
					</div>

					<div class="col-md-1" id="age">
						<div class="form-group">
						<label for="age"><strong>Idade</strong></label>
						<input type="text" class="form-control" name="age" maxlength="3" placeholder="00" pattern="\d*" oninput="this.value=this.value.replace(/[^0-9]/g,''); " required>
						</div>
					</div>			
					
					<div class="col-md-2" id="data_nasc">
						<div class="form-group">
						<label for="data_nasc"><strong>Data de Nascimento</strong></label>
						<input type="date" class="form-control" name="data_nasc" required>
						</div>
					</div>

					<div class="col-md-2" id="data_obito">
						<div class="form-group">
						<label for="data_obito"><strong>Data do Óbito</strong></label>
						<input type="date" class="form-control" name="data_obito" required>
						</div>
					</div>		

					<div class="col-md-2" id="hora_obito">
						<div class="form-group">
						<label for="hora_obito"><strong>Hora do Óbito</strong></label>
						<input type="time" class="form-control" name="hora_obito" required>
						</div>
					</div>	
									
					
				</div>

				<div class="row">

					<div class="col-md-2" id="registro">
						<div class="form-group">
						<label for="registro"><strong>Registro</strong></label>
						<input type="text" name="registro" class="form-control" placeholder="" style="text-transform: capitalize;" required>
						</div>
					</div>

					<div class="col-md-7" id="diag_obito">
						<div class="form-group">
							<label for="diag_obito"><strong>Diagnóstico do Óbito</strong></label>
							<!-- Campo de entrada de texto para diagnóstico do óbito -->
							<input type="text" name="diag_obito" class="form-control validate-letters" placeholder="" style="text-transform: uppercase;" required pattern="[A-Za-zÀ-ÿ\s]+">
							<!-- Mensagem de erro que será exibida caso caracteres inválidos sejam digitados -->
							<div class="error-message" style="color: red; display: none;">Apenas letras e espaços são permitidos.</div>
						</div>
					</div>					

					<div class="col-md-3">
						<div class="form-group">
							<label for="setor"><strong>Setor </strong></label>
							<select id="setor" name="setor" class="form-control" required onchange="subsetor();">
								<option value="">Selecione</option>
									
								<?php
									$conn = mysqli_connect("localhost","seu_usuario","sua_senha","seu_banco_de_dados");
									if (mysqli_connect_errno())
									{
									echo "Erro" . mysqli_connect_error();				
									}
									// $pesquisa = $conn->query("select * from setores order by nome asc");
									$pesquisa = $conn->query("SELECT id_setor, nome 
															  FROM setores 
															  ORDER BY nome ASC");
									
									while($row = $pesquisa->fetch_assoc()) {
										$nome_setor=$row['nome'];
										$id_setor=$row['id_setor'];
										
										echo "<option value='".$id_setor."'>".$nome_setor."</option>";
									}
								?>
							</select>
						</div>
					</div>

				</div>

				<div class="row">

					<div class="col-md-3">
						<div class="form-group">
							<label for="subsetoresx"><strong>Informe o Subsetor</strong></label>
							<select id="subsetoresx" name="subsetoresx" class="form-control" required>
								<option value="">Selecione</option>
							</select>
						</div>
					</div>

					<div class="col-md-2" id="doacao">
						<div class="form-group">
							<label for="doacao"><strong>Doação</strong></label>
							<select name="doacao" class="form-control" required>
								<option value="selecione">Selecione</option>
								<option value="Sim">Sim</option>
								<option value="Não">Não</option>
							</select>
						</div>
					</div>

					<div class="col-md-5">
						<div class="form-group">
						<label for="codigo"><strong>Código</strong></label>
						<select name="codigo" class="form-control">
                    <option value="">Selecione</option>
                    <optgroup label="NEGATIVA FAMILIAR">
                        <option value="001">001 - Desconhecimento do desejo do potencial doador</option>
                        <option value="002">002 - Doador contrário à doação em vida</option>
                        <option value="003">003 - Ausência de consenso familiar</option>
                        <option value="004">004 - Familiares desejam o corpo íntegro</option>
                        <option value="005">005 - Familiares insatisfeitos com o atendimento hospitalar</option>
                        <option value="006">006 - Receio de demora na liberação do corpo</option>
                        <option value="007">007 - Convicções religiosas</option>
                        <option value="008">008 - Outros</option>
                    </optgroup>
                    <optgroup label="CONTRAINDICAÇÃO MÉDICA">
                        <option value="009">009 - Sorologia Positiva HIV, HTLV I e II, hepatite B e C</option>
                        <option value="010">010 - SEPSE</option>
                        <option value="011">011 - Meningite</option>
                        <option value="012">012 - Neoplasias, hematológicas - Linfomas, Leucemias, Mieloma múltiplo</option>
                        <option value="013">013 - Neurocirurgia por motivo desconhecido</option>
                        <option value="014">014 - Raiva</option>
                        <option value="015">015 - Doença de Creutzfeldt-Jakob</option>
                        <option value="016">016 - Sindrome de Guillain Barré</option>
                        <option value="017">017 - Sindrome de reye</option>
                        <option value="018">018 - Rubéola congênita</option>
                        <option value="019">019 - Cirurgia de miopia</option>
                        <option value="020">020 - Retinoblastoma</option>
                        <option value="021">021 - Tumores malignos do segmento anterior do olho</option>
                        <option value="022">022 - Uso de imunossupressores</option>
                        <option value="023">023 - Transplante com dura-mater, Transplante de órgão, Xenotransplante (órgão/tecido animal)</option>
                        <option value="024">024 - Tuberculose ATIVA</option>
                        <option value="025">025 - Arboviroses (Dengue, Chikungunya, Zika nos últimos 30 dias)</option>
                        <option value="026">026 - Leptospirose</option>
                        <option value="027">027 - Realização de piercing, tatuagem e maquiagem definitiva no últimos 12 meses</option>
                        <option value="028">028 - Transplante de tecidos nos últimos 12 meses</option>
                        <option value="029">029 - Comportamento de risco nos últimos 12 meses</option>
                        <option value="030">030 - Fora da faixa etária</option>
                        <option value="031">031 - Causa da morte desconhecida</option>
                        <option value="032">032 - PD sem identificação</option>
                        <option value="033">033 - Tempo de retirada ultrapassado</option>
                        <option value="034">034 - COVID 19</option>
                        <option value="035">035 - Em Hemodiálise</option>
                        <option value="036">036 - Outros</option>
                    </optgroup>
                    <optgroup label="PROBLEMAS LOGÍSTICOS">
                        <option value="037">037 - Equipe do Banco de Olhos indispensável</option>
                        <option value="038">038 - Familia não localizada</option>
                        <option value="039">039 - Deficiência logistica do hospital</option>
                        <option value="040">040 - Equipe de entrevista indispensável</option>
                        <option value="041">041 - Óbitos em domicilio</option>
                        <option value="042">042 - Outros</option>
                    </optgroup>
                </select>
						</div>
					</div>

					

					<div class="col-md-2" id="responsavel">
						<div class="form-group">
						<label for="responsavel"><strong>Responsável</strong></label>
						<input type="text" name="responsavel" class="form-control" style="text-transform: uppercase;" required>
						</div>
					</div>				

				</div>

				

					<div class="row">
						<div class="col-md-6" id="descricao">
							<div class="form-group">
							<label for="descricao"><strong>Outros / Observação</strong></label>
							<textarea rows = "5" cols = "60" name = "descricao" placeholder="Use esse campo para informações adicionais." style="text-transform: uppercase;" required></textarea>
							</div>
						</div>
					</div>

			


					<hr>
				
				<div class="row">
					<div class="col-md-5">
						<!-- Botão para enviar o formulário -->
						<button class="btn btn-success" type="submit" style="width: 150px;">Cadastrar</button>&nbsp;&nbsp;
						<a href="pagina_destino.php"><button class="btn btn-danger" type="button" style="width: 150px;">Relação Geral</button></a>
					</div>

					<div class="col-md-2">
						
					</div>

				</div>

		</form> <!-- Fim do Formulário Geral -->


	  	 <footer class="my-5 pt-5 text-muted text-center text-small">
        	<p class="mb-1">&copy; 2024 Procape</p>
	        <ul class="list-inline">
	          <li class="list-inline-item"><a href="#">Privacidade</a></li>
	          <li class="list-inline-item"><a href="#">Termos</a></li>
	          <li class="list-inline-item"><a href="#">Suporte</a></li>
	        </ul>
      	</footer>

		</div> <!-- Fim da Página -->

		
        

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
		<script src="js/subsetores.js"></script>

		
	</body>

</html>