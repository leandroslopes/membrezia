$(function() {
    $.noConflict();

    var getUrl = function() {
        var idModulo, idModuloCadastrado, url;

        idModulo = $("#idModulo").val();
        idModuloCadastrado = $("#idModuloCadastrado").val();
        url = "modulo.php?id=" + idModulo + "&idModuloCadastrado=" + idModuloCadastrado;

        return url;
    };

    /* CARGO */
    $("#adicionarAcessoCargo").click(function() {

        $("#dialogAdicionarAcessoCargo").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 180,
            width: 350,
            modal: true,
            resizable: false,
            title: "ADICIONAR ACESSO",
            buttons: {
                "ADICIONAR": function() {
                    var adicionou, url, cargo;

                    url = getUrl();
                    cargo = $("#idCargo");

                    if (cargo.val() !== "") {
                        $.ajax({
                            url: "adicionarCargo.php",
                            type: "post",
                            data: $("#formAdicionarCargo").serialize(),
                            success: function(retorno) {
                                adicionou = retorno.trim();

                                if (adicionou === "ADICIONADO") {
                                    mensagem(5, url, "Este cargo já está adicionado!");
                                } else if (adicionou === "TRUE") {
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
                        mensagem(5, "", "Selecione o cargo.");
                    }
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogAdicionarAcessoCargo").dialog("open");
    });

    $(document).on("click", ".excluirAcessoCargo", function() {
        var cargo, idModuloAcesso, nomeCargo;

        cargo = $(this).parent().parent()[0];
        idModuloAcesso = cargo.cells[3].getElementsByTagName("input")[0].value;
        nomeCargo = cargo.cells[0].innerHTML;

        $("#dialogExcluirAcessoCargo").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 370,
            modal: true,
            title: "EXCLUIR ACESSO \"" + nomeCargo + "\"?",
            buttons: {
                "EXCLUIR": function() {
                    $.ajax({
                        url: "excluirCargo.php",
                        type: "post",
                        data: "id=" + idModuloAcesso,
                        success: function(retorno) {
                            var excluiu, url;

                            excluiu = retorno.trim();
                            url = getUrl();

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