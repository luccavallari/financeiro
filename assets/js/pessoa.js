/** 
 * Arquivo "pessoa", possui as funções de beforeSend  e callback das requisições
 * relacionada com a entidade pessoa
 * Versão: 1.0.0
 * Data: 14/01/2016
 * Desenvolvedor: Allan Gonçalves da Cruz <allangcruz@gmail.com>
 * ************************************************************************** */

$(document).ready(function() {

    //regras de validacao do formulario
    var validacao = $('#form_pessoa').validate({
      rules: {
        tipo: { required: true},
        categoria: { required: true},
        nome: { required: true},
        sexo: { required: true},
        nascimento: { required: true},
        email: { required: true},
        telefone: { minlength: 14},
        celular: { minlength: 14},
        endereco: {required: true},
        bairro: {required: true},
        cep: { minlength: 9},
        estado: {required: true},
        cidade: {required: true}
      },
      messages: {
        tipo: { required: 'Preencha o campo Tipo cadastro' },
        categoria: { required: 'Preencha o campo Categoria' },
        nome: { required: 'Preencha o campo Nome' },
        sexo: { required: 'Preencha o campo Sexo' },
        nascimento: { required: 'Preencha o campo Nascimento' },
        email: { required: 'Preencha o campo Email' },
        endereco: { required: 'Preencha o campo Endereço'},
        estado: { required: 'Preencha o campo Estado'},
        bairro: { required: 'Preencha o campo Bairro'},
        cidade: { required: 'Preencha o campo Cidade'}
      }
    });


    //realiza a operação de salvar
    $('#salvar-pessoa').click(function(){
        if($("#form_pessoa").valid()){

            if($('#id').val() == '')
                salvar_alterar('#form_pessoa', 'create', 'json',antesEnviar('#resposta','#load'),retornoSalvar);
            else
                 salvar_alterar('#form_pessoa', 'update', 'json',antesEnviar('#resposta','#load'),retornoAlterar);
        }else {
            validacao.focusInvalid();
        }
    });


    //consulta os dados da tela automaticamente
    consultar('#form_pessoa_consultar', 'read', 'html', function() {}, retornoConsulta);

    $('#expressao').keyup(function() {
        consultar('#form_pessoa_consultar', 'read', 'html', function() {loading('#load_consulta', 1); }, retornoConsulta);
    });    

    $('#filtro').change(function() {
        consultar('#form_pessoa_consultar', 'read', 'html', function() {loading('#load_consulta', 1); }, retornoConsulta);
    });

    //atualiza as cidades refere aos seus respectivos estados
    $('#estado').change(function(){
        //remove todos os itens de um select
        $('#cidade').find('option').remove().end().append('<option value=""></option>').val('');

        //preenche o select da cidade
        consultar('#form_pessoa', 'getCidade/'+$('#estado').val(), 'json', function() {}, function(itens){
            $('#cidade').append(itens.cidades);
        });
    });

});


/*
 | -------------------------------------------------------------------
 | Funções "retornoSalvar"
 | -------------------------------------------------------------------
 | Função que retorna resultado da função 'salvar'
 |
 */
function retornoSalvar(json, erro) {
    notificacao(json.msg, '#resposta');

    if(json.msg.tipo == "s"){
        consultar('#form_usuario_consulta', 'consultar', 'html', function() {}, retornoConsulta);
        refreshForm('#form_usuario');
        $('input[type=tel]').val('');
    }
    loading('#load', 0);

    $('html, body').animate({
        scrollTop: $('#view-form-usuario').offset().top
    }, 1000);
}

 /*
 | -------------------------------------------------------------------
 | Funções "retornoAlterar"
 | -------------------------------------------------------------------
 | Função que retorna resultado da função 'alterar'
 |
 */
function retornoAlterar(json, erro) {
    notificacao(json.msg, '#resposta');

    if(json.msg.tipo == "s") {
        consultar('#form_usuario_consulta', 'consultar', 'html', function() {}, retornoConsulta);
    }
    
    loading('#load', 0);

    $('html, body').animate({
        scrollTop: $('#view-form-usuario').offset().top
    }, 1000);
}

/*
 | -------------------------------------------------------------------
 | Funções "retornoExcluir"
 | -------------------------------------------------------------------
 | Função que retorna resultado da função 'excluir'
 |
 */
function retornoExcluir(json,erro) {
    notificacao(json.msg, '#resposta_excluir');

    if(json.msg.tipo == "s") {
        consultar('#form_usuario_consulta', 'consultar', 'html', function() {loading('#load_consulta', 1); }, retornoConsulta);
        refreshForm('#form_usuario');
    }
    
    loading('#load_consulta', 0);
    
}

 /*
 | -------------------------------------------------------------------
 | Funções "retornoPesquisar"
 | -------------------------------------------------------------------
 | Função que retorna resultado da função 'pesquisar', alem de preencher
 | os dados no formulario
 |
 */
function retornoPesquisar(json, erro) {
    removerNotificacao('#resposta');
    $('#id').val(json.id);
    $('#vet_id').val(json.vet_id);
    $('#nome').val(json.nome);
    $('#email').val(json.email);
    $('#perfil').val(json.perfil);
    $('#nascimento').val(json.nascimento);
    $('#crmv').val(json.crmv);
    
    if(json.sexo == 'm'){
      $("input[name=sexo][value='m']").prop("checked",true);
    }else{
      $("input[name=sexo][value='f']").prop("checked",true);
    }
      
    $('#telefone1').val(json.telefone1);
    $('#telefone2').val(json.telefone2);

    $('#estado').val(json.estado);
    
    //preenche o select da cidade
    consultar('#form_usuario', 'getCidade/'+json.estado, 'json', function() {}, function(itens){
        $('#cidade').append(itens.cidades);
        $('#cidade').val(json.cidade);
    });
    
    $('#cep').val(json.cep);
    $('#bairro').val(json.bairro);
    $('#endereco').val(json.endereco);
    $('#complemento').val(json.complemento);

    $('#view-usuario').hide();
    $('#view-form-usuario').show();
    $('#view-detalhe').hide();
    $('.ac-toggle-senha').parent().removeClass('ls-display-none');

    var senha = $('#senha');
    var cofsenha = $('#cofsenha');

    if(senha.attr('name')=='senha'){          
      //remove o atributo name da senha
      console.info("O atributo name da senha e cofsenha forma removidos");
      senha.removeAttr('name');
      cofsenha.removeAttr('name');
      $('.ac-password').addClass('ls-display-none');

      //remove as regras de validacao da senha
      $("#senha").rules("remove");
      $("#cofsenha").rules("remove");

      //variavel flag para controlar a validacao 
      $('#existesenha').val('0');

    }

    var crmv  = $("#crmv");

    if($('#perfil').val() == '0'){
      console.info("Torna o crmv opcional");

      //remove a validacao obrigatoriedade
      crmv.rules("remove","required");

      //remove a formatacao de obrigatoriedade
      crmv.prev().removeClass('ac-req');
    }else{
      console.info("Torna o crmv obrigatorio");

      //adiciona novamente a validacao
      crmv.rules( "add", {required: true, messages: {required: "Preencha o campo CRMV"}});

      //adiciona a formatacao de obirgatoriedade
      crmv.prev().addClass('ac-req');

    }


} 

//habilita a view do grid
function showViewGrid()
{
    $('.view-grid').removeClass('hidden');
    $('.view-form').addClass('hidden');
}

//habilita a view do formulario
function showViewForm()
{
    $('.view-form').removeClass('hidden');
    $('.view-grid').addClass('hidden');
}
