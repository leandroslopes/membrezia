$(function() {
    $.noConflict();

    var getUrl = function() {
        var url, idModulo;

        idModulo = $("#idModulo").val();
        idMembro = $("#idMembro").val();
        url = "dizimosOfertas.php?id=" + idModulo + "&idMembro=" + idMembro;

        return url;
    };

    /* DIZIMO */
    $("#adicionarDizimo").click(function() {

        $("#dialogAdicionarDizimo").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 270,
            width: 450,
            modal: true,
            resizable: false,
            title: "ADICIONAR DÍZIMO",
            buttons: {
                "ADICIONAR": function() {
                    var $valor, $data, adicionou, url;

                    $valor = $("#valor");
                    $data = $("#dataMovimentacao");
                    url = getUrl();

                    if ($valor.val() !== "" && $data.val() !== "") {
                        $.ajax({
                            url: "adicionarDizimo.php",
                            type: "post",
                            data: $("#formAdicionarDizimo").serialize(),
                            success: function(retorno) {
                                adicionou = retorno.trim();

                                if (adicionou === "TRUE") {
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
                        mensagem(5, "", "Preencha os campos obrigatórios!");
                    }
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogAdicionarDizimo").dialog("open");
    });

    $(document).on("click", ".editarDizimo", function() {
        var dizimo, idDizimo, valor, dataMovimentacao;

        dizimo = $(this).parent().parent()[0];
        idDizimo = dizimo.cells[4].getElementsByTagName("input")[0].value;
        valor = dizimo.cells[0].innerHTML;
        dataMovimentacao = dizimo.cells[2].innerHTML;
        
        $valor = $("#valor");
        $valor.val(valor);

        $dataMovimentacao = $("#dataMovimentacao");
        $dataMovimentacao.val(dataMovimentacao);

        $("#dialogAdicionarDizimo").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 270,
            width: 450,
            modal: true,
            resizable: false,
            title: "EDITAR DÍZIMO",
            buttons: {
                "EDITAR": function() {
                    var url, editou, data;

                    url = getUrl();

                    if ($valor.val() !== ""  && $dataMovimentacao.val() !== "") {
                        data = "id=" + idDizimo + "&" + $("#formAdicionarDizimo").serialize();

                        $.ajax({
                            url: "adicionarDizimo.php",
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
                        mensagem(5, "", "Preencha os campos obrigatórios!");
                    }
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogAdicionarDizimo").dialog("open");
    });

    $(document).on("click", ".excluirDizimo", function() {
        var dizimo, idDizimo, valor;

        dizimo = $(this).parent().parent()[0];
        idDizimo = dizimo.cells[4].getElementsByTagName("input")[0].value;
        valor = dizimo.cells[0].innerHTML;

        $("#dialogExcluirDizimo").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 170,
            width: 370,
            modal: true,
            title: "EXCLUIR DÍZIMO DE VALOR \"R$" + valor + "\"?",
            buttons: {
                "EXCLUIR": function() {
                    var excluiu, url;

                    url = getUrl();

                    $.ajax({
                        url: "excluirDizimoOferta.php",
                        type: "post",
                        data: "id=" + idDizimo,
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

    /* OFERTA */
    $("#adicionarOferta").click(function() {
        var $valor, $data, $idCongregacao;

        $valor = $("#valorAdicionarOferta");
        $data = $("#dtMovimentacaoAdicionarOferta");
        $idCongregacao = $("#idCongregacao");

        $("#dialogAdicionarOferta").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 310,
            width: 450,
            modal: true,
            resizable: false,
            title: "ADICIONAR OFERTA",
            buttons: {
                "ADICIONAR": function() {
                    var adicionou, url;
                    
                    url = getUrl();

                    if ($valor.val() !== "" && $data.val() !== "" && $idCongregacao.val() !== "") {
                        $.ajax({
                            url: "adicionarOferta.php",
                            type: "post",
                            data: $("#formAdicionarOferta").serialize(),
                            success: function(retorno) {
                                adicionou = retorno.trim();

                                if (adicionou === "TRUE") {
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
                        mensagem(5, "", "Preencha os campos obrigatórios!");
                    }
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogAdicionarOferta").dialog("open");
    });

    $(document).on("click", ".editarOferta", function() {
        var oferta, idOferta, valor, dataMovimentacao, idCongregacao;

        oferta = $(this).parent().parent()[0];
        idOferta = oferta.cells[4].getElementsByTagName("input")[0].value;
        valor = oferta.cells[0].innerHTML;
        dataMovimentacao = oferta.cells[2].innerHTML;
        idCongregacao = oferta.cells[4].getElementsByTagName("input")[1].value;
        
        $valor = $("#valorAdicionarOferta");
        $valor.val(valor);

        $dataMovimentacao = $("#dtMovimentacaoAdicionarOferta");
        $dataMovimentacao.val(dataMovimentacao);

        $idCongregacao = $("#idCongregacao");
        $idCongregacao.val(idCongregacao);

        $("#dialogAdicionarOferta").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 310,
            width: 450,
            modal: true,
            resizable: false,
            title: "EDITAR OFERTA",
            buttons: {
                "EDITAR": function() {
                    var url, editou, data;

                    url = getUrl();

                    if ($valor.val() !== ""  && $dataMovimentacao.val() !== "" && $idCongregacao.val() !== "") {
                        data = "id=" + idOferta + "&" + $("#formAdicionarOferta").serialize();

                        $.ajax({
                            url: "adicionarOferta.php",
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
                        mensagem(5, "", "Preencha os campos obrigatórios!");
                    }
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogAdicionarOferta").dialog("open");
    });

    $(document).on("click", ".excluirOferta", function() {
        var oferta, idOferta, valor;

        oferta = $(this).parent().parent()[0];
        idOferta = oferta.cells[4].getElementsByTagName("input")[0].value;
        valor = oferta.cells[0].innerHTML;

        $("#dialogExcluirOferta").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 370,
            modal: true,
            title: "EXCLUIR OFERTA NO VALOR \"R$" + valor + "\"?",
            buttons: {
                "EXCLUIR": function() {
                    var excluiu, url;

                    url = getUrl();

                    $.ajax({
                        url: "excluirDizimoOferta.php",
                        type: "post",
                        data: "id=" + idOferta,
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