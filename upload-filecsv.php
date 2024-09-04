<html>
    <h1>PÁGINA DE TESTE</h1><BR><BR>
<form action="" method="post" enctype="multipart/form-data">
 <label for="csv">Carregar arquivo Excel</label>
 <input type="file" name="csv" id="csv">
 <input type="submit" value="Enviar"><br><br>
 <small> <strong style="color: red;">Atenção:</strong> <strong>Somente arquivos CSV (Excel) devem ser carregados. Com um tamanho máximo de 5MB</strong></small>
</form>
</html>

<?php

//se o arquivo for carregado clicando no botão de upload
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //se o arquivo for carregado com algum erro encontrado
    
try {    
    if(isset($_FILES['csv']) && $_FILES['csv']['error'] == 0){
        //configurando o formato de arquivo permitido
        $allowed = array("csv" => "application/vnd.ms-excel");
               
       //obtendo o nome, tamanho e tipo dos arquivos usando $_FILES //superglobal
        $filename = $_FILES['csv']['name'];
        $filesize = $_FILES['csv']['size'];
        $filetype = $_FILES['csv']['type'];
    
       //verificando a extensão do arquivo
        $ext = pathinfo($filename,PATHINFO_EXTENSION);
        if(!array_key_exists($ext,$allowed)) die("Erro: Oops! Formato do arquivo não é aceitável");
       
       //verificando o tamanho do arquivo
        $maxsize = 5 * 1024 * 1024;
       
        // if($filesize > $maxsize) die("Erro: tamanho do arquivo muito grande!!"); 
           if(in_array($filetype,$allowed)){
               if(file_exists("upload/".$filename)){
                   die("Desculpe, o arquivo já existe");
               }else{
        //    move_uploaded_file($_FILES['csv']['tmp_name'],'http://192.168.150.22/smce/arquivos_csv'.$filename); // caminho para armazenamento no bd
           move_uploaded_file($_FILES['csv']['tmp_name'],'http://192.168.151.31/smce/arquivos'.$filename); // caminho para armazenamento no bd
           echo "O arquivo foi enviado com sucesso!! <br><br>";
           }
           }else{
       echo "Desculpe, ocorreu um problema ao tentar carregar os dados!!";
       }
       //informações extras descrevendo o arquivo carregado com sucesso
       try {
           if($_FILES['csv']['error'] == 0){
               echo "Nome do Arquivo: ". $_FILES['csv']['name'] ."<br><br>";
            //    echo "Tipo de arquivo :". $_FILES['csv']['type'] . "<br><br>";
            //    echo "Tamanho do arquivo: ". ($_FILES['csv']['size'] / 1024) . "KB <br>";
            }else{
                // echo "Arquivo inválido";
            }
        } catch (\Throwable $th) {
            // echo "Arquivo inválido!";
        }
    }
    else{
    // echo "Error: Arquivo Inválido";
    }
    
    
} catch (\Throwable $th) {
        echo "Erro: tamanho do arquivo muito grande!!"; 
    }

    
}

?>