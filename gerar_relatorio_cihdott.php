<?php
// Início da sessão
session_start();

// Verificação de login
if (!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
    session_destroy();
    header('location:index.php');
    exit();
}

$logado = $_SESSION['login'];

// Conexão com o banco de dados
$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");
if (mysqli_connect_errno()) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

// Recebendo e formatando as datas
$tipo = $_POST['tipo'] ?? '';
$data_ini = $_POST['data_ini'] ?? null;
$data_fim = $_POST['data_fim'] ?? null;

// Verificar se as datas foram fornecidas
if (!$data_ini || !$data_fim) {
    die("Erro: Datas não fornecidas!");
}

// Convertendo datas para o formato 'YYYY-MM-DD' para a consulta
$data_ini_sql = date('Y-m-d', strtotime(str_replace('/', '-', $data_ini)));
$data_fim_sql = date('Y-m-d', strtotime(str_replace('/', '-', $data_fim)));

// Consulta SQL para selecionar registros com base na data de óbito
$query = "SELECT * FROM pacientes WHERE dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
$result = mysqli_query($conn, $query);

// Consultas SQL para obter os valores de cada número
$query1 = "SELECT COUNT(*) AS total_doadores FROM pacientes WHERE doacao = 'Sim' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
$query2 = "SELECT COUNT(*) AS total_negativas_familiares FROM pacientes WHERE doacao = 'Não' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
$query3 = "SELECT COUNT(*) AS total_obitos_perdidos_cim FROM motivos WHERE categoria = 'contraindicacao medica' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
$query4 = "SELECT COUNT(*) AS total_obitos_perdidos_logistica FROM motivos WHERE categoria = 'problemas logisticos' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";

// Executar as consultas e armazenar os resultados
$result1 = mysqli_query($conn, $query1);
$result2 = mysqli_query($conn, $query2);
$result3 = mysqli_query($conn, $query3);
$result4 = mysqli_query($conn, $query4);

$total_doadores = mysqli_fetch_assoc($result1)['total_doadores'] ?? 0;
$total_negativas_familiares = mysqli_fetch_assoc($result2)['total_negativas_familiares'] ?? 0;
$total_obitos_perdidos_cim = mysqli_fetch_assoc($result3)['total_obitos_perdidos_cim'] ?? 0;
$total_obitos_perdidos_logistica = mysqli_fetch_assoc($result4)['total_obitos_perdidos_logistica'] ?? 0;

// Cálculos para o relatório
$total_obitos_viaveis_cornea = $total_doadores + $total_negativas_familiares + $total_obitos_perdidos_logistica;
$total_entrevistas_realizadas = $total_doadores + $total_negativas_familiares;

// Verificar se a consulta retornou resultados
if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}

// Contar o número de registros encontrados
$total_registros = mysqli_num_rows($result);

// Formatar as datas para exibição
$data_ini_exibicao = date('d/m/Y', strtotime($data_ini));
$data_fim_exibicao = date('d/m/Y', strtotime($data_fim));
?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <title>Relatório CIHDOTT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="Machado">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
</head>
<body>
    <div class="container"> <!-- Início da Página -->
        <div class="row mt-5">
            <div class="col-md-6">
                <img src="../img/logo-procape.png" class="img-fluid" alt="Logo Procape">
            </div>
            <div class="col-md-6">
                <img src="../img/logo.png" class="img-fluid" style="position: relative; float:right" alt="Logo CIHDOTT">
            </div>
        </div>
        
        <nav class="navbar navbar-expand-lg navbar-dark mt-3" style="background-color:#34404e; border-radius: 3px; height: 50px;">
            <a class="navbar-brand" href="index.php">
                <h4>RELATÓRIO  &nbsp; <span style='color:#FFD700'>[ CIHDOTT ]</span></h4>
            </a>
        </nav>
        
        <br>
        
        <!-- Primeiro Bloco -->
        <h4>Relatório do Período: <small><strong><?php echo "$data_ini_exibicao até $data_fim_exibicao"; ?></strong></small></h4>
        <hr>

        <!-- Segundo Bloco -->
        <div class='row'>
            <div class='col-md-12'>
                <h3>Nº Total de Óbitos Hospitalares</h3>

                <?php
                // Consulta para contar o número total de registros dentro do intervalo de datas
                $query = $conn->query("SELECT COUNT(*) as total FROM pacientes WHERE dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                
                // Recuperar o número total de registros
                $row = $query->fetch_assoc();
                $total_viaveis = $row['total'] ?? 0;
                ?>

                <strong>Total: <?php echo $total_viaveis > 0 ? "<strong>$total_viaveis</strong>" : $total_viaveis; ?></strong><br>
            </div>
        </div><hr>

        <!-- Terceiro Bloco -->
        <div class='row'>
            <div class='col-md-12'>
                <h3>Total de Óbitos Registrados CIHDOTT</h3>

                <?php
                // Consulta para contar o número total de registros dentro do intervalo de datas
                $query = $conn->query("SELECT COUNT(*) as total FROM pacientes WHERE dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                
                // Recuperar o número total de registros
                $row = $query->fetch_assoc();
                $total_viaveis = $row['total'] ?? 0;
                ?>

                <strong>Total: <?php echo $total_viaveis > 0 ? "<strong>$total_viaveis</strong>" : $total_viaveis; ?></strong><br>
            </div>
        </div><hr>

        <!-- Quarto Bloco -->
        <div class='row'>
            <div class='col-md-12'>
                <h3>Nº Total de Óbitos Viáveis para Doação de Córneas</h3>

                <?php
                // Contagem de doações "Sim" e "Não"
                $query_sim = $conn->query("SELECT COUNT(*) as total_sim FROM pacientes WHERE doacao = 'Sim' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                $row_sim = $query_sim->fetch_assoc();
                $total_sim = $row_sim['total_sim'] ?? 0;

                $total_doacao = $total_sim + $total_nao;

                // Contagem de Negativa Familiar (Códigos 001 até 008)
                $negativa_familiar = [];
                $total_negativa_familiar = 0;
                for ($i = 1; $i <= 8; $i++) {
                    $codigo = sprintf('%03d', $i);
                    $query_negativa = $conn->query("SELECT COUNT(*) as total FROM pacientes WHERE codigo = '$codigo' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                    $row_negativa = $query_negativa->fetch_assoc();
                    $negativa_familiar[$codigo] = $row_negativa['total'] ?? 0;
                    $total_negativa_familiar += $negativa_familiar[$codigo];
                }

                // Contagem de Problemas Logísticos (Códigos 037 até 042)
                $problemas_logisticos = [];
                $total_problemas_logisticos = 0;
                for ($i = 37; $i <= 42; $i++) {
                    $codigo = sprintf('%03d', $i);
                    $query_logisticos = $conn->query("SELECT COUNT(*) as total FROM pacientes WHERE codigo = '$codigo' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                    $row_logisticos = $query_logisticos->fetch_assoc();
                    $problemas_logisticos[$codigo] = $row_logisticos['total'] ?? 0;
                    $total_problemas_logisticos += $problemas_logisticos[$codigo];
                }

                // Somando todos os valores para obter o total geral
                $total_geral = $total_doacao + $total_negativa_familiar + $total_problemas_logisticos;
                ?>

                <div class="row">
                    <div class="col-md-4">
                        <strong>(1) - Doação:</strong><br>
                        <?php
                        echo "Sim: " . ($total_sim > 0 ? "<strong>$total_sim</strong>" : $total_sim) . "<br>";
                        echo "Não: " . ($total_nao > 0 ? "<strong>$total_nao</strong>" : $total_nao) . "<br>";
                        echo "<strong>Total: $total_doacao</strong><br>";
                        ?>
                    </div>
                    
                    <div class="col-md-4">
                        <strong>(2) - Negativa Familiar:</strong><br>
                        <?php
                        foreach ($negativa_familiar as $codigo => $total) {
                            echo "Código $codigo: " . ($total > 0 ? "<strong>$total</strong>" : $total) . "<br>";
                        }
                        echo "<strong>Total: $total_negativa_familiar</strong><br>";
                        ?>
                    </div>
                    
                    <div class="col-md-4">
                        <strong>(4) - Problemas Logísticos:</strong><br>
                        <?php
                        foreach ($problemas_logisticos as $codigo => $total) {
                            echo "Código $codigo: " . ($total > 0 ? "<strong>$total</strong>" : $total) . "<br>";
                        }
                        echo "<strong>Total: $total_problemas_logisticos</strong><br>";
                        ?>
                    </div>

                    <div class="col-md-2 mt-3">
                        <div class="highlight-box">
                            <strong>Total Geral: <?php echo $total_geral; ?></strong><br>
                        </div>
                    </div>
                </div><hr>    
            
        <!-- Quinto Bloco -->
        <div class='row'>
            <div class='col-md-12'>
                <h3>Nº Total de Entrevistas Realizadas</h3>

                <?php
                // Contagem de doações "Sim" e "Não"
                $query_sim = $conn->query("SELECT COUNT(*) as total_sim FROM pacientes WHERE doacao = 'Sim' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                $row_sim = $query_sim->fetch_assoc();
                $total_sim = $row_sim['total_sim'] ?? 0;

                $total_doacao = $total_sim + $total_nao;

                // Contagem de Negativa Familiar (Códigos 001 até 008)
                $negativa_familiar = [];
                $total_negativa_familiar = 0;
                for ($i = 1; $i <= 8; $i++) {
                    $codigo = sprintf('%03d', $i);
                    $query_negativa = $conn->query("SELECT COUNT(*) as total FROM pacientes WHERE codigo = '$codigo' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                    $row_negativa = $query_negativa->fetch_assoc();
                    $negativa_familiar[$codigo] = $row_negativa['total'] ?? 0;
                    $total_negativa_familiar += $negativa_familiar[$codigo];
                }

                // Somando todos os valores para obter o total geral
                $total_geral = $total_doacao + $total_negativa_familiar;
                ?>

                <div class="row">
                    <div class="col-md-4">
                        <strong>(1) - Doação:</strong><br>
                        <?php
                        echo "Sim: " . ($total_sim > 0 ? "<strong>$total_sim</strong>" : $total_sim) . "<br>";
                        echo "Não: " . ($total_nao > 0 ? "<strong>$total_nao</strong>" : $total_nao) . "<br>";
                        echo "<strong>Total: $total_doacao</strong><br>";
                        ?>
                    </div>
                    
                    <div class="col-md-4">
                        <strong>(2) - Negativa Familiar:</strong><br>
                        <?php
                        foreach ($negativa_familiar as $codigo => $total) {
                            echo "Código $codigo: " . ($total > 0 ? "<strong>$total</strong>" : $total) . "<br>";
                        }
                        echo "<strong>Total: $total_negativa_familiar</strong><br>";
                        ?>
                    </div>
                </div>    
                <div class="row">
                    <div class="col-md-2 mt-3">
                        <div class="highlight-box">
                            <strong>Total Geral: <?php echo $total_geral; ?></strong><br>
                        </div>
                    </div>                
                </div>
                <hr>    
            </div>
        </div>

        <!-- Sexto Bloco -->         
        <div class="row">
            <div class="col-md-12">
                <h3>Nº Total de Doadores</h3>
                <?php
                // Contagem de doadores "Sim"
                $query_doadores = $conn->query("SELECT COUNT(*) as total_doadores FROM pacientes WHERE doacao = 'Sim' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                $row_doadores = $query_doadores->fetch_assoc();
                $total_doadores = $row_doadores['total_doadores'] ?? 0;
                ?>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 mt-3">
                    <div class="highlight-box2">
                        <strong>Concedidos: <?php echo $total_doadores; ?></strong>
                    </div>
            </div>
        </div>
        <hr>

        <!-- Setimo Bloco -->         
        <div class="row">
            <div class="col-md-12">
                <h3>Nº Total das Negativas Familiares</h3>
                <?php
                // Contagem de doadores "Não" dentro do período especificado
                $query_doadores = $conn->query("SELECT COUNT(*) as total_doadores FROM pacientes WHERE doacao = 'Não' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                $row_doadores = $query_doadores->fetch_assoc();
                $total_doadores = $row_doadores['total_doadores'] ?? 0;
                ?>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 mt-3">
                    <div class="highlight-box2">
                        <strong>Negados: <?php echo $total_doadores; ?></strong>
                    </div>
            </div>
        </div>
        <hr>
        
        <!-- Oitavo Bloco -->
        <div class='row'>
            <div class='col-md-12'>
                <h3>Nº Total de Óbitos Perdidos por CIM</h3>

                <?php
                // Contagem de Contraindicação Médica (CIM) para Códigos 009 até 032
                $cim = [];
                $total_cim = 0;
                for ($i = 9; $i <= 32; $i++) {
                    $codigo = sprintf('%03d', $i);
                    $query_cim = $conn->query("SELECT COUNT(*) as total FROM pacientes WHERE codigo = '$codigo' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                    $row_cim = $query_cim->fetch_assoc();
                    $cim[$codigo] = $row_cim['total'] ?? 0;
                    $total_cim += $cim[$codigo];
                }
                ?>

                <div class="row">
                    <div class="col-md-6">
                        <strong>(3) - Contraindicação Médica (CIM):</strong><br>
                        <?php
                        // Exibe códigos de 009 até 020
                        for ($i = 9; $i <= 20; $i++) {
                            $codigo = sprintf('%03d', $i);
                            $total = $cim[$codigo] ?? 0;
                            echo "Código $codigo: ";
                            if ($total > 0) {
                                echo "<strong>$total</strong>";
                            } else {
                                echo $total;
                            }
                            echo "<br>";
                        }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <strong>(3) - Contraindicação Médica (CIM):</strong><br>
                        <?php
                        // Exibe códigos de 021 até 032
                        for ($i = 21; $i <= 32; $i++) {
                            $codigo = sprintf('%03d', $i);
                            $total = $cim[$codigo] ?? 0;
                            echo "Código $codigo: ";
                            if ($total > 0) {
                                echo "<strong>$total</strong>";
                            } else {
                                echo $total;
                            }
                            echo "<br>";
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <div class="highlight-box">
                            <strong>Total de Óbitos Perdidos por CIM: <?php echo "<strong>$total_cim</strong>"; ?></strong><br>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <!-- Nono Bloco -->
        <div class='row'>
            <div class='col-md-12'>
                <h3>Nº Total de Óbitos Perdidos por Problemas Logísticos</h3>

                <?php
                // Contagem de Problemas Logísticos para os códigos de 037 até 042
                $problemas_logisticos = [];
                $total_problemas_logisticos = 0;

                for ($i = 37; $i <= 42; $i++) {
                    $codigo = sprintf('%03d', $i);
                    $query_problemas_logisticos = $conn->query("SELECT COUNT(*) as total FROM pacientes WHERE codigo = '$codigo' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'");
                    $row_problemas_logisticos = $query_problemas_logisticos->fetch_assoc();
                    $problemas_logisticos[$codigo] = $row_problemas_logisticos['total'] ?? 0;
                    $total_problemas_logisticos += $problemas_logisticos[$codigo];
                }
                ?>

                <div class="row">
                    <div class="col-md-6">
                        <strong>(4) - Problemas Logísticos:</strong><br>
                        <?php
                        // Exibe códigos de 037 até 042
                        for ($i = 37; $i <= 42; $i++) {
                            $codigo = sprintf('%03d', $i);
                            $total = $problemas_logisticos[$codigo] ?? 0;
                            echo "Código $codigo: ";
                            if ($total > 0) {
                                echo "<strong>$total</strong>";
                            } else {
                                echo $total;
                            }
                            echo "<br>";
                        }
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-3">
                        <div class="highlight-box">
                            <strong>Total de Óbitos Perdidos por Problemas Logísticos: <?php echo "<strong>$total_problemas_logisticos</strong>"; ?></strong><br>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>


        <!-- IDs Registrados Bloco -->
        <h3>Registros por Pacientes:</h3>
        <?php
        // Exibir os resultados da consulta
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='mb-4'>";
            echo "<div class='row'>";
            echo "<div class='col-md-6'>";
            echo "<p><strong>ID:</strong> " . $row['id'] . "</p>";
            echo "<p><strong>Nome Paciente:</strong> " . $row['nome_paciente'] . "</p>";
            echo "<p><strong>Data de Nascimento:</strong> " . date('d/m/Y', strtotime($row['dt_nasc'])) . "</p>";
            echo "<p><strong>Data de Óbito:</strong> " . date('d/m/Y', strtotime($row['dt_obito'])) . "</p>";
            echo "<p><strong>Hora:</strong> " . $row['hora'] . "</p>";
            echo "<p><strong>Outros / Observação:</strong> " . $row['descricao'] . "</p>";
            echo "</div>";
            echo "<div class='col-md-6'>";
            echo "<p><strong>Registro:</strong> " . $row['registro'] . "</p>";
            echo "<p><strong>Diagnóstico Óbito:</strong> " . $row['diag_obito'] . "</p>";
            echo "<p><strong>Setor:</strong> " . $row['setor'] . "</p>";
            echo "<p><strong>Subsetor:</strong> " . $row['subsetor'] . "</p>";
            echo "<p><strong>Doação:</strong> " . $row['doacao'] . "</p>";
            echo "<p><strong>Responsável:</strong> " . $row['responsavel'] . "</p>";
            echo "</div>";
            echo "</div>";
            echo "<hr>";
            echo "</div>";
        }
        ?>

        <!-- Formulário e Botões -->
        <div class="row">
            <div class="col-md-8">
                <form action="gerar_xls_relatorio_pagina_destino.php" method="post">
                    <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
                    <input type="hidden" name="data_ini" value="<?php echo $data_ini; ?>">
                    <input type="hidden" name="data_fim" value="<?php echo $data_fim; ?>">
                    <button class="btn btn-success" type="submit" style="width: 150px;">Gerar XLS</button>&nbsp;&nbsp;
                    <button class="btn btn-danger" type="button" style="width: 150px;" onclick='history.go(-1)'>Voltar</button>
                </form>
            </div>
        </div>

        <br>

        <!-- Rodapé -->
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

        </body>
        </html>

        <?php
        // Fechar a conexão com o banco de dados
        mysqli_close($conn);
        ?>

        <style>
            .highlight-box {
                border: 2px solid #000; /* Borda para destacar */
                border-radius: 5px; /* Cantos arredondados */
                padding: 10px; /* Espaçamento interno */
                text-align: left; /* Alinhamento do texto */
                font-weight: bold; /* Texto em negrito */
                font-size: .9rem; /* Tamanho da fonte ajustado */
                width: 80%; /* Ajuste a largura conforme necessário */
            }
            .highlight-box2 {
                border: 2px solid #000; /* Borda para destacar */
                border-radius: 5px; /* Cantos arredondados */
                padding: 10px; /* Espaçamento interno */
                text-align: left; /* Alinhamento do texto */
                font-weight: bold; /* Texto em negrito */
                font-size: .9rem; /* Tamanho da fonte ajustado */
                width: 80%; /* Ajuste a largura conforme necessário */
            }
        </style>
