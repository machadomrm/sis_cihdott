async function subsetor() {
    var select = document.getElementById("setor");
    var selectSetor = document.getElementById("subsetoresx");
    var value = select.options[select.selectedIndex].value;

    console.log(select.value);

    const formData = new FormData()
    formData.append('id_setor', select.value)

    const result = await fetch("selsub.php",{method: "post", body: formData })

    const data = await result.json()
    console.log(data)

    for (i = selectSetor.options.length - 1; i >= 0; i--) {
        selectSetor.remove(i);
    }

    if(data.length==0){
        var option = document.createElement("option");
        option.value = 'Inexistente';
        option.text = 'Inexistente';
        selectSetor.add(option);
    }else{

    for (i = 0;  i < data.length; i++) {
        var option = document.createElement("option");
        option.value = data[i].nome;
        option.text = data[i].nome;
        selectSetor.add(option);
    }
    }

    
}