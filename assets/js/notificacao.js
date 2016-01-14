function removerNotificacao(seletor) {
    $(seletor).html('');
    $(seletor).removeClass('alert-success sm-space');
    $(seletor).removeClass('alert-warning sm-space');
    $(seletor).removeClass('alert-danger sm-space');
}

function notificacao(msg, seletor) {
    removerNotificacao(seletor);

    $(seletor).html('<span onclick="removerNotificacao(\'' + seletor + '\')" data-ls-module="dismiss" class="ls-dismiss"><b style="color:#000;">&times;</b></span>'+msg.texto);

    switch(msg.tipo){
        case "a":
            $(seletor).addClass('alert-warning sm-space');
        break;
     
        case "e":
            $(seletor).addClass('alert-danger sm-space');
        break;
     
        case "s":
            $(seletor).addClass('alert-success sm-space');
        break;
    }

}
