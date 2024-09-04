<?php

$setor=$_POST['id_setor'];

$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");
if (mysqli_connect_errno())
{
echo "Erro" . mysqli_connect_error();
mysqli_set_charset($conn, 'utf8mb4');
$mysqli->set_charset('utf8mb4');
}

if ($setor) {
    $pesquisa = $conn->query("select nome from subsetores where id_setor='$setor'");
    $result  = $pesquisa->fetch_all(MYSQLI_ASSOC);
    echo json_encode($result);
} else {
    echo "<script>alert('Erro no sistema.');location.href='http://example.com/diretorio/login.php';</script>";
    die;
}    

?>