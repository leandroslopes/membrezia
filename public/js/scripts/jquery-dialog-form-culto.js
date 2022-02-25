$(function() {
    $.noConflict();

    var getUrl = function() {
        var url, idModulo;

        idModulo = $("#idModulo").val();
        url = "index.php?id=" + idModulo;

        return url;
    };

    /* CULTO */
    $("#cadastrarCulto").click(function() {

        $("#dialogCadastrarCulto").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 220,
            width: 500,
            modal: true,
            resizable: false,
            title: "CADASTRAR CULTO",
            buttons: {
                "CADASTRAR": function() {
                    var $nome, cadastrou, url;

                    $nome = $("#nome");
                    url = getUrl();

                    if ($nome.val() !== "") {
                        $.ajax({
                            url: "cadastrar.php",
                            type: "post",
                            data: $("#formCadastrarCulto").serialize(),
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
                        mensagem(5, "", "Informe o nome.");
                    }
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogCadastrarCulto").dialog("open");
    });

    $(document).on("click", ".editarCulto", function() {
        var culto, idCulto, nomeCulto, txtNome;

        culto = $(this).parent().parent()[0];
        idCulto = culto.cells[3].getElementsByTagName("input")[0].value;
        nomeCulto = culto.cells[0].innerHTML;
        
        txtNome = $("#nome");
        txtNome.val(nomeCulto);

        $("#dialogCadastrarCulto").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 220,
            width: 500,
            modal: true,
            resizable: false,
            title: "EDITAR CULTO",
            buttons: {
                "EDITAR": function() {
                    var url, editou;

                    url = getUrl();

                    if (txtNome.val() !== "") {

                        var data = "id=" + idCulto + "&" + $("#formCadastrarCulto").serialize();

                        $.ajax({
                            url: "cadastrar.php",
                            type: "post",
                            data: data,
                            success: function(retorno) {
                                editou = retorno.trim();
                                
                                if (editou === "TRUE") {
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
                        mensagem(5, "", "Informe o nome.");
                    }
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogCadastrarCulto").dialog("open");
    });

    $(document).on("click", ".excluirCulto", function() {
        var culto, idCulto, nomeCulto;

        culto = $(this).parent().parent()[0];
        idCulto = culto.cells[3].getElementsByTagName("input")[0].value;
        nomeCulto = culto.cells[0].innerHTML;

        $("#dialogExcluirCulto").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 170,
            width: 370,
            modal: true,
            title: "EXCLUIR CULTO \"" + nomeCulto + "\"?",
            buttons: {
                "EXCLUIR": function() {
                    var excluiu, url;

                    url = getUrl();

                    $.ajax({
                        url: "excluir.php",
                        type: "post",
                        data: "id=" + idCulto,
                        success: function(retorno) {
                            excluiu = retorno.trim();

                            if (excluiu === "TRUE") {
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