var mensagem = function(tipoMensagem, url, informeMensagem) {
    var $msg, textoTitulo, textoMensagem;

    switch (tipoMensagem) {
        case 1:
            textoTitulo = "SUCESSO";
            textoMensagem = "Opera&ccedil;&atilde;o efetuada!";
            break;
        case 2:
            textoTitulo = "ERRO";
            textoMensagem = "Opera&ccedil;&atilde;o n&atilde;o efetuada!";
            break;
        case 3:
            textoTitulo = "CADASTRADO";
            textoMensagem = "Item j&aacute; cadastrado!";
            break;
        case 4:
            textoTitulo = "RELACIONADO";
            textoMensagem = "Item relacionado. N&atilde;o pode exclu&iacute;-lo!";
            break;
        case 5:
            textoTitulo = "INFORME";
            textoMensagem = informeMensagem;
            break;
        case 6:
            textoTitulo = "SENHA";
            textoMensagem = "A senha foi alterada com sucesso! <br /> Entre no sistema com a nova senha.";
            break;
        default:
            break;
    }

    $msg = $("#mensagem");
    $msg.html(textoMensagem);

    $msg.dialog({
        dialogClass: "no-close",
        show: "slow",
        title: textoTitulo,
        buttons: [{
                text: "OK",
                click: function() {
                    $msg.dialog("close");
                    if (tipoMensagem !== 5) {
                        $(location).attr("href", url);
                    }
                }
            }]
    });
};