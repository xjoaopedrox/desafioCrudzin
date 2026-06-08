
function validarForm() {

    var divMsgErro = document.querySelector("#msgErro");
    divMsgErro.innerHTML = "";
    divMsgErro.style.display = "none";

    //1- Pegar os valores dos inputs do formulário
    let nome = document.querySelector('#nome_tarefa').value;
    let categoria = document.querySelector("#categoria").value;
    let minutos = document.querySelector("#duracao_minutos").value;
    let prioridade = document.querySelector("#prioridade").value;
    let data = document.querySelector("#data_entrega").value;


    
    
    

    //alert(titulo + " - " + genero + " - " + autor + " - " + qtdPag);

    let erros = [];

    //2- Validar os dados preenchidos
    if(nome.trim() == '') {
        erros.push("Informe o nome da tarefa!");
    }

    if(minutos.trim() == '') {
        erros.push("Informe os minutos da tarefa!");        
    }

        else if((parseInt(minutos) <= 0)) {
            erros.push("A duração em minutos deve ser maior que zero!");
        }

    if(prioridade.trim() == '') {
        erros.push("Informe a prioridade da tarefa!");
    }

    if(data.trim() == '') {
        erros.push("Informe a data da tarefa!");
    }

    if(erros.length > 0) {
        var divMsgErro = document.querySelector("#msgErro");
        divMsgErro.innerHTML = erros.join("<br>");
        divMsgErro.style.display = "block";
        return false;
    }
    

    //3- Após validar, retorna verdadeiro para submeter o form
    return true;
}