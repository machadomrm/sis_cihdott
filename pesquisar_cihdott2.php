<?php
$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");            

if (mysqli_connect_errno()) {
    echo "Erro" . mysqli_connect_error();
}

$registro = $_POST['registro'];
$pesquisa = $conn->query("SELECT * FROM pacientes WHERE registro='$registro'");

if (mysqli_num_rows($pesquisa) < 1) {
    echo "<script>alert('Registro não encontrado');location.href='pagina_destino.php';</script>";
} else {
    while ($row = $pesquisa->fetch_assoc()) {
        $nome_paciente = $row['nome_paciente'];
        $idade = $row['idade'];
        $dt_nasc = $row['dt_nasc'];
        $dt_obito = $row['dt_obito'];
        $hora = $row['hora'];
        $registro = $row['registro'];
        $diag_obito = $row['diag_obito'];
        $setor = $row['setor'];
        $subsetor = $row['subsetor'];
        $codigo = $row['codigo'];
        $doacao = $row['doacao'];
        $responsavel = $row['responsavel'];
    }
    echo "<meta http-equiv='refresh' content='1;url=pagina_destino.php?registro=".$registro."'>";
}
?>
