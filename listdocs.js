
	function changeSelect()
	{

		var select = document.getElementById('tipo');
		var selectSetor = document.getElementById('desc');
		var value = select.options[select.selectedIndex].value;

		var length = selectSetor.options.length;        
		var i;
		for(i = selectSetor.options.length-1 ; i>=0 ; i--)
		{
		selectSetor.remove(i);
		}

		if(value == 'Coordenacao_Servicos_Tecnicos') {

		var option0 = document.createElement('option');
		option0.value = '';
		option0.text = 'Selecione';

		var option1 = document.createElement('option');
		option1.value = 'Psicologia';
		option1.text = 'Psicologia';

		var option2 = document.createElement('option');
		option2.value = 'Fisioterapia';
		option2.text = 'Fisioterapia';

		var option3 = document.createElement('option');
		option3.value = 'Servico Social';
		option3.text = 'Serviço Social';

		var option4 = document.createElement('option');
		option4.value = 'Nutricao';
		option4.text = 'Nutrição';

		var option5 = document.createElement('option');
		option5.value = 'Farmacia';
		option5.text = 'Farmácia';

		var option6 = document.createElement('option');
		option6.value = 'Terapia Ocupacional';
		option6.text = 'Terapia Ocupacional';

		selectSetor.add(option0);
		selectSetor.add(option1);
		selectSetor.add(option2);
		selectSetor.add(option3);
		selectSetor.add(option4);
		selectSetor.add(option5);
		selectSetor.add(option6);
		}

		if(value == 'Gestao_Executiva') 
		{
			var option0 = document.createElement('option');
			option0.value = '';
			option0.text = 'Selecione';
	
			var option1 = document.createElement('option');
			option1.value = 'NST';
			option1.text = 'Núcleo de Segurança do Trabalho';

			var option2 = document.createElement('option');
			option2.value = 'CCIH';
			option2.text = 'CCIH';

			selectSetor.add(option0);
			selectSetor.add(option1);
			selectSetor.add(option2);
		}

		if(value == 'Administracao_Geral') 
		{
			var option0 = document.createElement('option');
			option0.value = '';
			option0.text = 'Selecione';
	
			selectSetor.add(option0);
			
		}

		if(value == 'Coordenacao_Enfermagem') 
		{
			var option0 = document.createElement('option');
			option0.value = '';
			option0.text = 'Sem Docs';
	
			

			

			selectSetor.add(option0);
			
		}
	}