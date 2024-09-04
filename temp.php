<?php

			$conn = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados");
			if (mysqli_connect_errno())
			{
				echo "Erro" . mysqli_connect_error();
								
			}

			$conn2 = mysqli_connect("localhost", "seu_usuario", "sua_senha", "seu_banco_de_dados2");
			if (mysqli_connect_errno())
			{
				echo "Erro" . mysqli_connect_error();
								
			}

			$pesquisa = $conn->query("select * from setores");
	
				while($row = $pesquisa->fetch_assoc()) 
				{
	
					$setor= $row['nome_setor'];

					$pesquisa1 = $conn2->query("select * from os_externa where setor ='$setor' and (str_to_date(data_cad, '%d/%m/%Y') >= str_to_date('01/09/2021', '%d/%m/%Y')) and (str_to_date(data_cad, '%d/%m/%Y') <= str_to_date('30/09/2021', '%d/%m/%Y'))");
	
					$num_results = mysqli_num_rows($pesquisa1);

						if($num_results>0)
						{
							$nome_setor='';
						}
						else
						{
						$nome_setor=$setor."<br>";
						}
						
					echo $nome_setor;
				}
			
		?>


