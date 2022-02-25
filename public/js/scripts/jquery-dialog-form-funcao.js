$(function() {
    $.noConflict();

    var getUrl = function() {
        var url, idModulo;

        idModulo = $("#idModulo").val();
        url = "index.php?&id=" + idModulo;

        return url;
    };

    /* FUNCAO */
    $("#cadastrarFuncao").click(function() {

        $("#dialogCadastrarFuncao").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 260,
            width: 450,
            modal: true,
            resizable: false,
            title: "CADASTRAR FUNÇÃO",
            buttons: {
                "CADASTRAR": function() {
                    var $nome, cadastrou, url;

                    $sigla = $("#sigla").val(); 
                    $nome = $("#nome").val();
                    url = getUrl();

                    if (($sigla !== "") && ($nome !== "")) {
                        $.ajax({
                            url: "cadastrar.php",
                            type: "post",
                            data: $("#formCadastrarFuncao").serialize(),
                            success: function(retorno) {
                                cadastrou = retorno.trim();

                                if (cadastrou === "TRUE") {
                                    mensagem(1, url, "");
                                } else {
                                    mensagem(2, url, "");
                                }
                            },
                            error: function() {
                                mensagem(2, url, "");
                            }
                        });
                        $(this).dialog("destroy");
                    } else {
                        mensagem(5, "", "Informe os campos obrigatórios!");
                    }
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogCadastrarFuncao").dialog("open");
    });

    $(document).on("click", ".excluirFuncao", function() {
        var funcao, idFuncao, nomeFuncao;

        funcao = $(this).parent().parent()[0];
        idFuncao = funcao.cells[4].getElementsByTagName("input")[0].value;
        nomeFuncao = funcao.cells[0].innerHTML;

        $("#dialogExcluirFuncao").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 170,
            width: 370,
            modal: true,
            title: "EXCLUIR FUNÇÃO \"" + nomeFuncao + "\"?",
            buttons: {
                "EXCLUIR": function() {
                    $.ajax({
                        url: "excluir.php",
                        type: "post",
                        data: "id=" + idFuncao,
                        success: function(retorno) {
                            var excluiu, url;

                            excluiu = retorno.trim();
                            url = getUrl();

                            if (excluiu ==  "ASSOCIADA") {
                                mensagem(5, url, "Função associada a algum membro! <br /> Não pode ser excluída.");
                            } else if (excluiu === "TRUE") {
                                mensagem(1, url, "");
                            } else {
                                mensagem(2, url, "");
                            }
                        }
                    });
                    $(this).dialog("destroy");
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });
    });
});