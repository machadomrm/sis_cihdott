<!-- TESTE LDAP -->

<?php

$cpf_sol = $_POST['cpf_sol'];
$senha_sol = $_POST['senha_sol'];

$usuario = $cpf_sol;
$senha = $senha_sol;

$servidor_ad = "ip_dominio";
$dominio = "empresa.sigla";

// Conectar ao servidor LDAP
$ad = ldap_connect($servidor_ad)
    or die ("Não foi possível conectar ao Active Directory");

// Configurar as opções do LDAP
ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);

// Tentar autenticar
$bind = @ldap_bind($ad, $usuario . "@" . $dominio, $senha);

// Verificar se a autenticação foi bem-sucedida
if ($bind) {
    // Autenticação bem-sucedida, redirecionar para sucesso.php
    header("Location: sucesso.php");
    exit;
} else {
    // Autenticação falhou, exibir uma mensagem de alerta e voltar à página anterior
    echo "<script>alert('CPF ou Senha Incorreto.');window.history.back();</script>";
}

// Fechar a conexão LDAP
ldap_close($ad);
?>
