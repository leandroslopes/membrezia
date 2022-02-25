$(function() {
    $.noConflict();

    var getUrl = function(isIndex) {
        var url, idModulo, idCongregacao;

        idModulo = $("#idModulo").val();

        if (isIndex) {
            url = "index.php?&id=" + idModulo;
        } else {
            idCongregacao = $("#idCongregacao").val();
            url = "congregacao.php?id=" + idModulo + "&idCongregacao=" + idCongregacao;
        }

        return url;
    };

    var inicializarSelectCidades = function(idEstado, idCidade = "") {
        $.ajax({
            type: "post",
            url: "../../public/js/scripts/ajax.php",
            dataType: "json",
            data: {
                tipo: "cidades", 
                idEstado: idEstado,
                idCidade: idCidade
            },
            success: function(option) {
                var options, selected; 
                
                options = '<option value="">SELECIONE UMA CIDADE</option>';
                selected = "";
                
                for (var i = 0; i < option.length; i++) {
                    if (option[i].id == idCidade) {
                        selected = "selected";
                    }    
                    options += '<option ' + selected + ' value="' + option[i].id + '">' + option[i].nome + '</option>';
                    selected = "";
                }	
                $('#idCidade').html(options);
            }
        });
    };

    $('#idEstado').change(function() {
        if($(this).val()) {
            inicializarSelectCidades($(this).val());
        } else {
            $('#idCidade').html('<option value="">SELECIONE UMA CIDADE</option>');
        }
    });

    /* CONGREGACAO */
    $("#cadastrarCongregacao").click(function() {
        var $nome, $rua, $bairro, $complemento, $estado, $cidade, $fone, $data;

        $nome = $("#nome");
        $rua = $("#rua");
        $bairro = $("#bairro");
        $complemento = $("#complemento");
        $estado = $("#idEstado");
        $cidade = $("#idCidade");
        $fone = $("#fone");
        $data = $("#dataFundacao");

        $("#dialogCadastrarCongregacao").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 550,
            width: 475,
            modal: true,
            resizable: false,
            title: "CADASTRAR CONGREGAÇÃO",
            buttons: {
                "CADASTRAR": function() {
                    var cadastrou, url;

                    url = getUrl(true);

                    if ($nome.val() !== "" && $cidade.val() !== "") {
                        $.ajax({
                            url: "cadastrar.php",
                            type: "post",
                            data: $("#formCadastrarCongregacao").serialize(),
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
                        mensagem(5, url, "Preencha os campos obrigatórios!");
                    }
                },
                "CANCELAR": function() {
                    $nome.val("");
                    $rua.val("");
                    $bairro.val(""); 
                    $complemento.val(""); 
                    $estado.val("");
                    $cidade.val("");
                    $fone.val(""); 
                    $data.val("");
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogCadastrarCongregacao").dialog("open");
    });

    $(document).on("click", ".editarCadastro", function() {
        var congregacao, id, nome, endereco, arrayEndereco, idCidade, fone, dataFundacao, $txtNome, $txtRua, $txtBairro, 
        $txtComplemento, $selectCidade, $selectEstado, $txtFone, $txtData;

        congregacao = $(this).parent().parent()[0];
        id = congregacao.cells[6].getElementsByTagName("input")[0].value;
        nome = congregacao.cells[0].getElementsByTagName("a")[0].innerHTML;
        endereco = congregacao.cells[1].innerHTML;
        idEstado = congregacao.cells[6].getElementsByTagName("input")[1].value;
        idCidade = congregacao.cells[6].getElementsByTagName("input")[2].value;
        
        arrayEndereco = endereco.split(/\s*,\s*/);
        
        fone = congregacao.cells[2].innerHTML;
        dataFundacao = congregacao.cells[3].innerHTML;

        $txtNome = $("#nome");
        $txtRua = $("#rua");
        $txtBairro = $("#bairro");
        $txtComplemento = $("#complemento");
        $selectCidade = $("#idCidade");
        $selectEstado = $("#idEstado");
        $txtFone = $("#fone");
        $txtData = $("#dataFundacao");

        $txtNome.val(nome);
        $txtRua.val(arrayEndereco[0]);
        $txtBairro.val(arrayEndereco[1]);
        $txtComplemento.val(arrayEndereco[2]);
        
        $selectEstado.val(idEstado);
        inicializarSelectCidades(idEstado, idCidade);

        $txtFone.val(fone);
        $txtData.val(dataFundacao);

        $("#dialogCadastrarCongregacao").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 560,
            width: 475,
            modal: true,
            resizable: false,
            title: "EDITAR CONGREGAÇÃO",
            buttons: {
                "EDITAR": function() {
                    var editou, url, data;

                    url = getUrl(true);

                    if ($txtNome.val() !== "" && $selectCidade.val() !== "") {

                        data = "id=" + id + "&" + $("#formCadastrarCongregacao").serialize();

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
                        mensagem(5, url, "Preencha os campos obrigatórios!");
                    }
                },
                "CANCELAR": function() {
                    $txtNome.val("");
                    $txtRua.val("");
                    $txtBairro.val("");
                    $txtComplemento.val("");
                    $selectCidade.val("");
                    $selectEstado.val("");
                    $txtFone.val("");
                    $txtData.val("");
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogCadastrarCongregacao").dialog("open");
    });

    $("#adicionarCulto").click(function() {

        $("#dialogAdicionarCulto").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 180,
            width: 350,
            modal: true,
            resizable: false,
            title: "ADICIONAR CULTO",
            buttons: {
                "ADICIONAR": function() {
                    var url, $culto, adicionou;

                    $culto = $("#idCulto");
                    url = getUrl(false);

                    if ($culto.val() === "") {
                        mensagem(5, url, "Selecione um culto.");
                    } else {
                        $.ajax({
                            url: "adicionarCulto.php",
                            type: "post",
                            data: $("#formAdicionarCulto").serialize(),
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
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogAdicionarCulto").dialog("open");
    });

    $(document).on("click", ".desassociarCulto", function() {
        var congregacaoCulto, idCongregacaoCulto, nomeCulto;

        congregacaoCulto = $(this).parent().parent()[0];
        nomeCulto = congregacaoCulto.cells[0].innerHTML;
        idCongregacaoCulto = congregacaoCulto.cells[3].getElementsByTagName("input")[0].value;

        $("#dialogDesassociarCulto").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 400,
            modal: true,
            title: "DESASSOCIAR CULTO \"" + nomeCulto + "\"?",
            buttons: {
                "Desassociar": function() {
                    var url, desassociou;

                    url = getUrl(false);

                    $.ajax({
                        url: "desassociarCulto.php",
                        type: "post",
                        data: "id=" + idCongregacaoCulto,
                        success: function(retorno) {
                            desassociou = retorno.trim();

                            if (desassociou === "TRUE") {
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

    $("#btnSelecione").click(function() {
        var $mes, $ano, $idCongregacao, $totaisMes;

        $mes = $("#mes");
        $ano = $("#ano");
        $idCongregacao = $("#idCongregacao");
        $totaisMes = $("#totaisMes");

        $.ajax({
            type: "post",
            url: "../../public/js/scripts/ajax.php",
            data: {
                tipo: "congregacao",
                mes: $mes.val(),
                ano: $ano.val(),
                idCongregacao: $idCongregacao.val()
            },
            success: function(totais) {
                $totaisMes.html(totais);
            }
        });
    });

    /* OFERTA */
    $(".adicionarOferta").click(function() {
        var $valor, $dataMovimentacao, congregacaoCulto, idCongregacaoCulto;

        $valor = $("#valor");
        $dataMovimentacao = $("#dataMovimentacao");

        congregacaoCulto = $(this).parent().parent()[0];
        idCongregacaoCulto = congregacaoCulto.cells[3].getElementsByTagName("input")[0].value;

        $("#dialogAdicionarOferta").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 280,
            width: 450,
            modal: true,
            resizable: false,
            title: "ADICIONAR OFERTA",
            buttons: {
                "ADICIONAR": function() {
                    var adicionou, url, data;

                    if ($valor.val() !== "" && $dataMovimentacao.val() !== "") {
                        data = "idCongregacaoCulto=" + idCongregacaoCulto + "&" + $("#formAdicionarOferta").serialize()
                        
                        url = getUrl();
                        
                        $.ajax({
                            url: "../movimentacao/adicionarMovimentacao.php",
                            type: "post",
                            data: data,
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
                    $valor.val(""); 
                    $dataMovimentacao.val("");
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogAdicionarOferta").dialog("open");
    });

    $(document).on("click", ".excluirMovimentacao", function() {
        var oferta, idOferta, valor;

        oferta = $(this).parent().parent()[0];
        idOferta = oferta.cells[5].getElementsByTagName("input")[0].value;
        valor = oferta.cells[1].innerHTML;

        $("#dialogExcluirOferta").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 370,
            modal: true,
            title: "EXCLUIR OFERTA DE VALOR \"R$" + valor + "\"?",
            buttons: {
                "EXCLUIR": function() {
                    var excluiu, url;

                    url = getUrl();

                    $.ajax({
                        url: "../movimentacao/excluirMovimentacao.php",
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

    $(document).on("click", ".editarMovimentacao", function() {
        var oferta, idOferta, valor, data, $txtValor, $txtData;

        oferta = $(this).parent().parent()[0];
        idCongregacaoCulto = oferta.cells[4].getElementsByTagName("input")[0].value;
        idOferta = oferta.cells[5].getElementsByTagName("input")[0].value;
        valor = oferta.cells[1].innerHTML;
        data = oferta.cells[2].innerHTML;
        
        $txtValor = $("#valor"); 
        $txtValor.val(valor);

        $txtData = $("#dataMovimentacao"); 
        $txtData.val(data);

        $("#dialogAdicionarOferta").dialog({ 
            dialogClass: "no-close",
            autoOpen: false,
            height: 280,
            width: 450,
            modal: true,
            resizable: false,
            title: "EDITAR OFERTA",
            buttons: {
                "EDITAR": function() {
                    var url, editou, data;

                    url = getUrl();

                    if ($txtValor.val() !== "" && $txtData.val() !== "") {
                        data = "idMovimentacao=" + idOferta + "&idCongregacaoCulto=" + idCongregacaoCulto + "&" + $("#formAdicionarOferta").serialize();

                        $.ajax({
                            url: "../movimentacao/adicionarMovimentacao.php",
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
                    $txtValor.val("");
                    $txtData.val("");
                    $(this).dialog("destroy");
                }
            }
        });

        $("#dialogAdicionarOferta").dialog("open");
    });

    $(document).on("click", ".tornarPadrao", function() {
        var congregacao, nomeCongregacao, idCongregacao, idCongregacaoPadrao;

        congregacao = $(this).parent().parent()[0];
        idCongregacao = congregacao.cells[6].getElementsByTagName("input")[0].value;
        idCongregacaoPadrao = congregacao.cells[7].getElementsByTagName("input")[0].value;
        nomeCongregacao = congregacao.cells[0].getElementsByTagName("a")[0].innerHTML;

        $("#dialogIsPadrao").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 430,
            modal: true,
            title: "TORNAR \"" + nomeCongregacao + "\" PADRÃO?",
            buttons: {
                "OK": function() {
                    var excluiu, url;

                    url = getUrl(true);

                    $.ajax({
                        url: "tornarCongregacaoPadrao.php",
                        type: "post",
                        data: "idCongregacao=" + idCongregacao + "&idCongregacaoPadrao=" + idCongregacaoPadrao,
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