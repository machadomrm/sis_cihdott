<?php

// Verifica se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf_sol = $_POST['cpf_sol'];
    $senha_sol = $_POST['senha_sol'];

    $servidor_ad = "111.222.333.4";
    $dominio = "empresa.sigla";

    // Lista de CPFs autorizados
    $cpfs_autorizados = [
        '12345678909', // User teste
        '11111111111', // User teste2
        '22222222222', // User teste3
        '33333333333', // User teste4
        '44444444444', // User teste5 
        '55555555555', // User teste6
        '66666666666', // User teste7 
        '77777777777', // User teste8 
        '88888888888', // User teste9   
        '99999999999'  // User teste0
    ];

    // Verificar se o CPF está na lista de autorizados
    if (!in_array($cpf_sol, $cpfs_autorizados)) {
        echo "<meta http-equiv='refresh' content='1;url=login.php'>";
        die('► CPF Não autorizado para CIHDOTT ! ◄');
    }

    // Conectar ao servidor LDAP
    $ad = ldap_connect($servidor_ad) or die("Não foi possível conectar ao Active Directory");

    // Configurar as opções do LDAP
    ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);

    // Tentar autenticar
    $bind = @ldap_bind($ad, $cpf_sol . "@" . $dominio, $senha_sol);

    // Verificar se a autenticação foi bem-sucedida
    if ($bind) {
        // Iniciar a sessão
        if (!isset($_SESSION)) {
            session_start();
        }

        // Definir as variáveis de sessão
        $_SESSION['login'] = $cpf_sol;
        $_SESSION['senha'] = $senha_sol;

        // Conectar ao banco de dados MySQL
        $conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");
        if (mysqli_connect_errno()) {
            die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
        }

        // Verificar se o CPF está no banco de dados
        $usuario_escapado = mysqli_real_escape_string($conn, $cpf_sol);
        $query = "SELECT * FROM usuarios WHERE cpf = '$usuario_escapado'";
        $resultado = $conn->query($query);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            // CPF autorizado e existente no banco de dados, redirecionar para a página de produção
            header("Location: relacao_pagina_destino.php");
            exit;
        } else {
            // CPF não encontrado no banco de dados
            echo "<meta http-equiv='refresh' content='1;url=login.php'>";
            die('► CPF Não autorizado no banco de dados ! ◄');
        }

        mysqli_close($conn); // Fechar a conexão com o banco de dados
    } else {
        // Autenticação LDAP falhou
        echo "<script>alert('CPF ou Senha Incorreto.');window.history.back();</script>";
    }

    // Fechar a conexão LDAP
    ldap_close($ad);
} else {
    // Redireciona para a página de login se o acesso não for feito por POST
    header("Location: login.php");
    exit;
}
?>
