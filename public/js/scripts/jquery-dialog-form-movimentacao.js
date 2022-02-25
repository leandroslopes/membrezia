$(function() {
    $.noConflict();

    var getUrl = function(page) {
        var url, idModulo;

        idModulo = $("#idModulo").val();
        url = page + ".php?&id=" + idModulo;

        return url;
    };

    /* TIPO DE DESPESA */
    $("#cadastrarDespesaTipo").click(function() {
        var $tipo = $("#tipo");
        
        $("#dialogCadastrarDespesaTipo").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 220,
            width: 480,
            modal: true,
            resizable: false,
            title: "CADASTRAR TIPO",
            buttons: {
                "CADASTRAR": function() {
                    var url, adicionou;

                    url = getUrl("despesaTipo");

                    if ($tipo.val() === "") {
                        mensagem(5, "", "Informe o tipo!");
                    } else {
                        $.ajax({
                            url: "cadastrarDespesaTipo.php",
                            type: "post",
                            data: $("#formCadastrarDespesaTipo").serialize(),
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
                    }
                },
                "CANCELAR": function() {
                    $tipo.val("");
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogCadastrarDespesaTipo").dialog("open");
    });  

    $(document).on("click", ".editarDespesaTipo", function() {
        var despesaTipo, id, $nome;

        despesaTipo = $(this).parent().parent()[0];
        id = despesaTipo.cells[3].getElementsByTagName("input")[0].value;
        nome = despesaTipo.cells[0].innerHTML;

        $nome = $("#tipo");
        $nome.val(nome);

        $("#dialogCadastrarDespesaTipo").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 220,
            width: 480,
            modal: true,
            title: "EDITAR TIPO",
            buttons: {
                "EDITAR": function() {
                    var url, editou;

                    url = getUrl("despesaTipo");

                    if ($nome.val() === "") {
                        mensagem(5, "", "Informe o tipo!");
                    } else {
                        $.ajax({
                            url: "cadastrarDespesaTipo.php",
                            type: "post",
                            data: "id=" + id + "&" + $("#formCadastrarDespesaTipo").serialize(),
                            success: function(retorno) {
                                editou = retorno.trim();

                                if (editou === "TRUE") {
                                    mensagem(1, url, "");
                                } else {
                                    mensagem(2, url, "");
                                }
                            }
                        });
                        $(this).dialog("destroy");
                    }
                },
                "CANCELAR": function() {
                    $nome.val("");
                    $(this).dialog("destroy");
                }
            }
        });
    });

    $(document).on("click", ".excluirDespesaTipo", function() {
        var despesaTipo, id;

        despesaTipo = $(this).parent().parent()[0];
        id = despesaTipo.cells[3].getElementsByTagName("input")[0].value;

        $("#dialogExcluirDespesaTipo").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 370,
            modal: true,
            title: "EXCLUIR TIPO",
            buttons: {
                "EXCLUIR": function() {
                    var url, editou;

                    url = getUrl("despesaTipo");
                    
                    $.ajax({
                        url: "excluirDespesaTipo.php",
                        type: "post",
                        data: "id=" + id,
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

    /* DESPESA */
    $("#cadastrarDespesa").click(function() {
        var $valor, $despesaTipo, $congregacao, $data;
        
        $valor = $("#valor");
        $despesaTipo = $("#idDespesaTipo");
        $congregacao = $("#idCongregacao");
        $data = $("#dataMovimentacao");
        
        $("#dialogCadastrarDespesa").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 350,
            width: 430,
            modal: true,
            resizable: false,
            title: "CADASTRAR DESPESA",
            buttons: {
                "CADASTRAR": function() {
                    var url, adicionou;

                    url = getUrl("despesas");
                    
                    if ($valor.val() === "" || $despesaTipo.val() === "" || $congregacao.val() === "" || $data.val() === "") {
                        mensagem(5, "", "Preencha os campos obrigatórios!");
                    } else {
                        $.ajax({
                            url: "adicionarMovimentacao.php",
                            type: "post",
                            data: $("#formCadastrarDespesa").serialize(),
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
                    }
                },
                "CANCELAR": function() {
                    $valor.val("");
                    $despesaTipo.val("");
                    $congregacao.val("");
                    $data.val("");
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogCadastrarDespesa").dialog("open");
    });

    $(document).on("click", ".editarDespesa", function() {
        var despesa, id, idDespesaTipo, $selectDespesaTipo, valor, $textValor, idCongregacao, $selectCongregacao, data, $data;

        despesa = $(this).parent().parent()[0];
        id = despesa.cells[7].getElementsByTagName("input")[0].value;
        idDespesaTipo = despesa.cells[7].getElementsByTagName("input")[1].value;
        valor = despesa.cells[1].innerHTML;
        idCongregacao = despesa.cells[7].getElementsByTagName("input")[2].value;
        data = despesa.cells[3].innerHTML;
        
        $valor = $("#valor"); 
        $valor.val(valor);
        
        $selectDespesaTipo = $("#idDespesaTipo"); 
        $selectDespesaTipo.val(idDespesaTipo);

        $selectCongregacao = $("#idCongregacao"); 
        $selectCongregacao.val(idCongregacao);

        $data = $("#dataMovimentacao"); 
        $data.val(data);

        $("#dialogCadastrarDespesa").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 350,
            width: 430,
            modal: true,
            title: "EDITAR DESPESA",
            buttons: {
                "EDITAR": function() {
                    var url, editou;

                    url = getUrl("despesas");
                    
                    if ($valor.val() === "" || $selectDespesaTipo.val() === "" || $selectCongregacao.val() === "" || $data.val() === "") {
                        mensagem(5, "", "Preencha os campos obrigatórios!");
                    } else {
                        $.ajax({
                            url: "adicionarMovimentacao.php",
                            type: "post",
                            data: "idMovimentacao=" + id + "&" + $("#formCadastrarDespesa").serialize(),
                            success: function(retorno) {
                                editou = retorno.trim();

                                if (editou === "TRUE") {
                                    mensagem(1, url, "");
                                } else {
                                    mensagem(2, url, "");
                                }
                            }
                        });
                        $(this).dialog("destroy");
                    }
                },
                "CANCELAR": function() {
                    $valor.val("");
                    $selectDespesaTipo.val("");
                    $selectCongregacao.val("");
                    $data.val("");
                    $(this).dialog("destroy");
                }
            }
        });
    });

    $(document).on("click", ".excluirDespesa", function() {
        var despesa, id;

        despesa = $(this).parent().parent()[0];
        id = despesa.cells[7].getElementsByTagName("input")[0].value;

        $("#dialogExcluirDespesa").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 370,
            modal: true,
            title: "EXCLUIR DESPESA",
            buttons: {
                "EXCLUIR": function() {
                    var url, editou;

                    url = getUrl("despesas");
                    
                    $.ajax({
                        url: "excluirMovimentacao.php",
                        type: "post",
                        data: "id=" + id,
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

    /* RECEITA */
    $("#cadastrarReceita").click(function() {
        var $valor, $descricao, $congregacao, $data;
        
        $valor = $("#valor");
        $descricao = $("#descricao");
        $congregacao = $("#idCongregacao");
        $data = $("#dataMovimentacao");
        
        $("#dialogCadastrarReceita").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 340,
            width: 430,
            modal: true,
            resizable: false,
            title: "CADASTRAR RECEITA",
            buttons: {
                "CADASTRAR": function() {
                    var url, cadastrou;

                    url = getUrl("receitas");
                    
                    if ($valor.val() === "" || $descricao.val() === "" || $congregacao.val() === "" || $data.val() === "") {
                        mensagem(5, "", "Preencha os campos obrigatórios!");
                    } else {
                        $.ajax({
                            url: "adicionarMovimentacao.php",
                            type: "post",
                            data: $("#formCadastrarReceita").serialize(),
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
                    }
                },
                "CANCELAR": function() {
                    $valor.val("");
                    $descricao.val("");
                    $congregacao.val("");
                    $data.val("");
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogCadastrarReceita").dialog("open");
    });

    $(document).on("click", ".editarReceita", function() {
        var receita, id, valor, descricao, idCongregacao, dataMovimentacao, $valor, $descricao, $idCongregacao, $data;

        receita = $(this).parent().parent()[0];
        id = receita.cells[7].getElementsByTagName("input")[0].value;
        valor = receita.cells[0].innerHTML;
        descricao = receita.cells[1].innerHTML;
        idCongregacao = receita.cells[7].getElementsByTagName("input")[1].value;
        dataMovimentacao = receita.cells[3].innerHTML;

        $valor = $("#valor");
        $valor.val(valor);
        
        $descricao = $("#descricao");
        $descricao.val(descricao);

        $idCongregacao = $("#idCongregacao");
        $idCongregacao.val(idCongregacao);
        
        $data = $("#dataMovimentacao");
        $data.val(dataMovimentacao);

        $("#dialogCadastrarReceita").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 340,
            width: 430,
            modal: true,
            title: "EDITAR RECEITA",
            buttons: {
                "EDITAR": function() {
                    var url, editou;

                    url = getUrl("receitas");

                    if ($valor.val() === "" || $descricao.val() === "" || $idCongregacao.val() === "" || $data.val() === "") {
                        mensagem(5, "", "Preencha os campos obrigatórios!");
                    } else {
                        $.ajax({
                            url: "adicionarMovimentacao.php",
                            type: "post",
                            data: "idMovimentacao=" + id + "&" + $("#formCadastrarReceita").serialize(),
                            success: function(retorno) {
                                editou = retorno.trim();

                                if (editou === "TRUE") {
                                    mensagem(1, url, "");
                                } else {
                                    mensagem(2, url, "");
                                }
                            }
                        });
                        $(this).dialog("destroy");
                    }
                },
                "CANCELAR": function() {
                    $valor.val("");
                    $descricao.val("");
                    $idCongregacao.val("");
                    $data.val("");
                    $(this).dialog("destroy");
                }
            }
        });
    });

    $(document).on("click", ".excluirReceita", function() {
        var receita, id;

        receita = $(this).parent().parent()[0];
        id = receita.cells[7].getElementsByTagName("input")[0].value;

        $("#dialogExcluirReceita").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 370,
            modal: true,
            title: "EXCLUIR RECEITA",
            buttons: {
                "EXCLUIR": function() {
                    var url, editou;

                    url = getUrl("receitas");
                    
                    $.ajax({
                        url: "excluirMovimentacao.php",
                        type: "post",
                        data: "id=" + id,
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