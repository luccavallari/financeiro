function removerNotificacao(seletor) {
    $(seletor).html('');
    $(seletor).removeClass('alert alert-success');
    $(seletor).removeClass('alert alert-warning');
    $(seletor).removeClass('alert alert-danger');
}

function notificacao(msg, seletor) {
    removerNotificacao(seletor);

    $(seletor).html('<button onclick="removerNotificacao(\'' + seletor + '\')" class="close"><span>×</span></button>'+msg.texto);

    switch(msg.tipo){
        case "a":
            $(seletor).addClass('alert  alert-warning');
        break;
     
        case "e":
            $(seletor).addClass('alert  alert-danger');
        break;
     
        case "s":
            $(seletor).addClass('alert  alert-success');
        break;
    }

}
