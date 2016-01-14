/**
 * Arquivo "crud", controla requisições ajax, destinadas para o crud de entidades
 * Versão: 1.0.0
 * Data: 02/10/2013
 * Programador: Allan Gonçalves da Cruz <allangcruz@gmail.com>
 * ************************************************************************** */

function salvar_alterar(id_form, metodo, datatype, antesEnviar, retorno) 
{
    var form = $(id_form).serialize();
    var url = $(id_form).attr("action");
    enviar(form, url + '/' + metodo, datatype, antesEnviar, retorno);
}


function consultar(id_form, metodo, datatype, antesEnviar, retorno) 
{
    var form = $(id_form).serialize();
    var url = $(id_form).attr("action");
    enviar(form, url + '/' + metodo, datatype, antesEnviar, retorno);
}


function excluir(id_form, metodo, nome, datatype, antesEnviar, retorno) 
{
    var url = $(id_form).attr("action");
    var status =confirm('Deseja excluir: ' + nome);
    if (status) {
        enviarPorUri(url + '/' + metodo, datatype, antesEnviar, retorno);
    }else{
        loading('#load_consulta', 0);

    }

}


function cancelar(id_form, metodo, nome, datatype, antesEnviar, retorno) 
{
    var url = $(id_form).attr("action");
    var status =confirm('Deseja cancelar o pedido do : ' + nome);
    if (status) {
        enviarPorUri(url + '/' + metodo, datatype, antesEnviar, retorno);
    }else{
        loading('#load_consulta', 0);
    }

}


function enviarDados(id_form, metodo, nome, datatype, antesEnviar, retorno) 
{
    var form = $(id_form).serialize();
    var url = $(id_form).attr("action");
    enviar(form, url + '/' + metodo, datatype, antesEnviar, retorno);
}


function pesquisar(id_form, metodo, datatype, antesEnviar, retorno) 
{
    var url = $(id_form).attr("action");
    enviarPorUri(url + '/' + metodo, datatype, antesEnviar, retorno);
}


function antesEnviar(id_resposta, id_load) {
    removerNotificacao(id_resposta);
    loading(id_load, 1);
    $('.ac-btn-disable').attr("disabled", "enabled");
}


function retornoConsulta(ret) {
    $('#resposta_consulta').html(ret);
    loading('#load_consulta', 0);
    loading('.load_consulta', 0);
}