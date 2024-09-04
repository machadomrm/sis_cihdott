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

// Configuração dos headers para download do arquivo Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=relatorio_pagina_destino" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

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

// Função para formatar datas para exibição
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

// Consultas SQL para obter os dados necessários
$total_obitos_query = "SELECT COUNT(*) AS total FROM pacientes WHERE dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
$total_obitos_result = mysqli_query($conn, $total_obitos_query);
$total_obitos = mysqli_fetch_assoc($total_obitos_result)['total'] ?? 0;

// Doação
$doacao_sim_query = "SELECT COUNT(*) AS total FROM pacientes WHERE doacao = 'Sim' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
// $doacao_nao_query = "SELECT COUNT(*) AS total FROM pacientes WHERE doacao = 'Não' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
$doacao_sim_result = mysqli_query($conn, $doacao_sim_query);
// $doacao_nao_result = mysqli_query($conn, $doacao_nao_query);
$total_doacao_sim = mysqli_fetch_assoc($doacao_sim_result)['total'] ?? 0;
// $total_doacao_nao = mysqli_fetch_assoc($doacao_nao_result)['total'] ?? 0;
$total_doacao = $total_doacao_sim; // Total calculado somente com "Sim"

// Negativas Familiares (Códigos 001 até 008) teste2
$negativas_familiares_total = 0;
$negativas_familiares = [];
for ($i = 1; $i <= 8; $i++) {
    $codigo = str_pad($i, 3, '0', STR_PAD_LEFT);
    $query = "SELECT COUNT(*) AS total FROM pacientes WHERE codigo = '$codigo' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
    $result = mysqli_query($conn, $query);
    $total = mysqli_fetch_assoc($result)['total'] ?? 0;
    $negativas_familiares[$codigo] = $total;
    $negativas_familiares_total += $total;
}

// Contraindicação Médica (CIM) (Códigos 009 até 032)
$cim_total = 0;
$cim = [];
for ($i = 9; $i <= 32; $i++) {
    $codigo = str_pad($i, 3, '0', STR_PAD_LEFT);
    $query = "SELECT COUNT(*) AS total FROM pacientes WHERE codigo = '$codigo' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
    $result = mysqli_query($conn, $query);
    $total = mysqli_fetch_assoc($result)['total'] ?? 0;
    $cim[$codigo] = $total;
    $cim_total += $total;
}

// Problemas Logísticos (Códigos 037 até 042)
$problemas_logisticos_total = 0;
$problemas_logisticos = [];
for ($i = 37; $i <= 42; $i++) {
    $codigo = str_pad($i, 3, '0', STR_PAD_LEFT);
    $query = "SELECT COUNT(*) AS total FROM pacientes WHERE codigo = '$codigo' AND dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
    $result = mysqli_query($conn, $query);
    $total = mysqli_fetch_assoc($result)['total'] ?? 0;
    $problemas_logisticos[$codigo] = $total;
    $problemas_logisticos_total += $total;
}

// Óbitos Viáveis para Doação de Córneas
$obitos_viaveis_cornea = $total_doacao + $negativas_familiares_total + $problemas_logisticos_total;

// Entrevistas Realizadas
$entrevistas_realizadas = $total_doacao + $negativas_familiares_total;

// Obtenção dos registros detalhados
$registros_query = "SELECT * FROM pacientes WHERE dt_obito BETWEEN '$data_ini_sql' AND '$data_fim_sql'";
$registros_result = mysqli_query($conn, $registros_query);

// Início da geração do conteúdo Excel
echo "<html xmlns:x=\"urn:schemas-microsoft-com:office:excel\">";
echo "<head>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "<style>
        .header {
            background-color: #cccccc;
            font-weight: bold;
            text-align: center;
        }
        .subheader {
            background-color: #efefef;
            font-weight: bold;
        }
        .total {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: right;
        }
        .highlight {
            background-color: #ffff99;
            font-weight: bold;
        }
        .info-cell {
            background-color: #ffff99; /* cor de fundo amarelo claro */
            font-weight: bold; /* texto em negrito */
        }        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
      </style>";
echo "</head>";
echo "<body>";
echo "<table border='1'>";

// Título do Relatório
echo "<tr><th colspan='6' class='header'>RELATÓRIO CIHDOTT</th></tr>";
echo "<tr><th colspan='6' class='center'>Período: ".formatDate($data_ini)." até ".formatDate($data_fim)."</th></tr>";

// Espaço
echo "<tr><td colspan='6'></td></tr>";

// Bloco 1: Nº Total de Óbitos Hospitalares
echo "<tr><td colspan='6' class='subheader'>Nº Total de Óbitos Hospitalares</td></tr>";
echo "<tr><td colspan='5' class='info-cell'>Total de Óbitos no Período</td><td class='info-cell'>$total_obitos</td></tr>";

// Espaço
echo "<tr><td colspan='6'></td></tr>";

// Bloco 1: Total de Óbitos Registrados CIHDOTT
echo "<tr><td colspan='6' class='subheader'>Total de Óbitos Registrados CIHDOTT</td></tr>";
echo "<tr><td colspan='5' class='info-cell'>Total de Óbitos no Período</td><td class='info-cell'>$total_obitos</td></tr>";

// Espaço
echo "<tr><td colspan='6'></td></tr>";

// Bloco 2: Nº Total de Óbitos Viáveis para Doação de Córneas teste 2
echo "<tr><td colspan='6' class='subheader'>Nº Total de Óbitos Viáveis para Doação de Córneas</td></tr>";
echo "<tr><td colspan='3'><strong>(1) - Doação:</strong></td><td>Sim</td><td class='right'>$total_doacao_sim</td><td></td></tr>";
echo "<tr><td colspan='3'></td><td>Não</td><td class='right'></td><td></td></tr>";
echo "<tr><td colspan='3'></td><td class='total'>Total</td><td class='right total'>$total_doacao_sim</td><td></td></tr>";

// Negativas Familiares
echo "<tr><td colspan='6'></td></tr>";
echo "<tr><td colspan='6'><strong>(2) - Negativas Familiares:</strong></td></tr>";
foreach ($negativas_familiares as $codigo => $total) {
    echo "<tr><td colspan='4'>Código $codigo</td><td class='right'>$total</td><td></td></tr>";
}
echo "<tr><td colspan='4' class='total'>Total</td><td class='right total'>$negativas_familiares_total</td><td></td></tr>";

// Problemas Logísticos
echo "<tr><td colspan='6'></td></tr>";
echo "<tr><td colspan='6'><strong>(4) - Problemas Logísticos:</strong></td></tr>";
foreach ($problemas_logisticos as $codigo => $total) {
    echo "<tr><td colspan='4'>Código $codigo</td><td class='right'>$total</td><td></td></tr>";
}
echo "<tr><td colspan='4' class='total'>Total</td><td class='right total'>$problemas_logisticos_total</td><td></td></tr>";

// Total Geral de Óbitos Viáveis para Doação de Córneas
echo "<tr><td colspan='4' class='info-cell'>Total Geral de Óbitos Viáveis para Doação de Córneas</td><td class='info-cell'>$obitos_viaveis_cornea</td><td></td></tr>";

// Espaço
echo "<tr><td colspan='6'></td></tr>";

// Bloco 3: Nº Total de Entrevistas Realizadas
echo "<tr><td colspan='6' class='subheader'>Nº Total de Entrevistas Realizadas</td></tr>";
echo "<tr><td colspan='4' class='info-cell'>Total de Entrevistas Realizadas</td><td class='info-cell'>$entrevistas_realizadas</td><td></td></tr>";

// Espaço
echo "<tr><td colspan='6'></td></tr>";

// Bloco 4: Nº Total de Doadores
echo "<tr><td colspan='6' class='subheader'>Nº Total de Doadores</td></tr>";
echo "<tr><td colspan='4' class='info-cell'>Total de Doadores</td><td class='info-cell'>$total_doacao_sim</td><td></td></tr>";

// Espaço
echo "<tr><td colspan='6'></td></tr>";

// Bloco 5: Nº Total de Negativas Familiares
echo "<tr><td colspan='6' class='subheader'>Nº Total de Negativas Familiares</td></tr>";
echo "<tr><td colspan='4' class='info-cell'>Total de Negativas Familiares</td><td class='info-cell'>$negativas_familiares_total</td><td></td></tr>";

// Espaço
echo "<tr><td colspan='6'></td></tr>";

// Bloco 6: Nº Total de Óbitos Perdidos por CIM
echo "<tr><td colspan='6' class='subheader'>Nº Total de Óbitos Perdidos por CIM</td></tr>";
foreach ($cim as $codigo => $total) {
    echo "<tr><td colspan='4'>Código $codigo</td><td class='right'>$total</td><td></td></tr>";
}
echo "<tr><td colspan='4' class='info-cell'>Total de Óbitos Perdidos por CIM</td><td class='info-cell'>$cim_total</td><td></td></tr>";

// Espaço
echo "<tr><td colspan='6'></td></tr>";

// Bloco 7: Nº Total de Óbitos Perdidos por Problemas Logísticos
echo "<tr><td colspan='6' class='subheader'>Nº Total de Óbitos Perdidos por Problemas Logísticos</td></tr>";
foreach ($problemas_logisticos as $codigo => $total) {
    echo "<tr><td colspan='4'>Código $codigo</td><td class='right'>$total</td><td></td></tr>";
}
echo "<tr><td colspan='4' class='info-cell'>Total de Óbitos Perdidos por Problemas Logísticos</td><td class='info-cell'>$problemas_logisticos_total</td><td></td></tr>";

// Espaço
echo "<tr><td colspan='6'></td></tr>";

// Bloco 8: Registros por Pacientes
echo "<tr><td colspan='6' class='subheader'>Registros por Pacientes</td></tr>";
echo "<tr>
        <th class='header'>ID</th> 
        <th class='header'>Nome Paciente</th> 
        <th class='header'>Data de Nascimento</th>
        <th class='header'>Data de Óbito</th>
        <th class='header'>Hora</th>


        <th class='header'>Outros / Observação</th>
        <th class='header'>Registro</th>
        <th class='header'>Diagnóstico Óbito</th>
        <th class='header'>Setor</th>
        <th class='header'>Subsetor</th>
        <th class='header'>Doação</th>
        <th class='header'>Responsável</th>
      </tr>";
while ($row = mysqli_fetch_assoc($registros_result)) {
    echo "<tr>
            <td class='center'>{$row['id']}</td>
            <td>{$row['nome_paciente']}</td>
            <td class='center'>".formatDate($row['dt_nasc'])."</td>
            <td class='center'>".formatDate($row['dt_obito'])."</td>
            <td class='center'>{$row['hora']}</td>

            <td>{$row['descricao']}</td>
            <td class='center'>{$row['registro']}</td>
            <td>{$row['diag_obito']}</td>
            <td class='center'>{$row['setor']}</td>
            <td class='center'>{$row['subsetor']}</td>
            <td class='center'>{$row['doacao']}</td>
            <td>{$row['responsavel']}</td>
          </tr>";
}

// Finalização da tabela
echo "</table>";
echo "</body>";
echo "</html>";

// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>
