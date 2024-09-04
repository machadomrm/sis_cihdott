<?php

$usuario = $_POST['login'];

if ($usuario!='11111111111' // User teste
and $usuario!='22222222222' // User teste2
and $usuario!='33333333333' // User teste3
) {
  echo "<meta http-equiv='refresh' content=1;url='index.php'>";
  die('► CPF Não autorizado xx ! ◄');
}

			
if (!isset($_SESSION)) {
    session_start();
  }
			
  $usuario = $_POST['login'];
  $senha = $_POST['senha'];
  
  $servidor_ad = "111.222.333.4";
  
  $dominio = "empresa.sigla";
  
  $ad = ldap_connect($servidor_ad)
  or die ("Não foi possível conexão com Active Directoy");
  
  ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
  ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
  
  $bd = @ldap_bind($ad, $usuario."@".$dominio, $senha) or die ("<script>alert('Senha Incorreta.');window.history.back();</script>");
  
  $filter = "(sAMAccountName=".$usuario.")";
  $attributes = array('name', 'sAMAccountName');
  $search = ldap_search($ad,'DC=huprocape, DC=upe', $filter, $attributes);
  $data = ldap_get_entries($ad, $search);
  
  foreach ($data AS $key => $value)
  {
	  $nome_val= @$value["name"][0];
  
  }
  
  $_SESSION['login'] = $usuario;
  $_SESSION['senha'] = $senha;

  echo "<meta http-equiv='refresh' content=1;url='form_rel_pagina_destino.php'>";

?>

	</body>

</html>