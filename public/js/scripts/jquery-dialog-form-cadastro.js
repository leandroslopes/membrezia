$(function() {

    var getUrl = function() {
        var url, idModulo, idCadastrado;

        idModulo = $("#idModulo").val();
        idCadastrado = $("#idMembro").val();
        url = "formCadastrar.php?idCadastrado=" + idCadastrado + "&id=" + idModulo;

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

    /* CADASTRO */
    $("#btnAdicionarUsuario").click(function() {

        $("#dialogAdicionarUsuario").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 180,
            width: 350,
            modal: true,
            resizable: false,
            title: "ADICIONAR USUÁRIO",
            buttons: {
                "ADICIONAR": function() {
                    var adicionou, url, $cargo;

                    url = getUrl();
                    $cargo = $("#selectAdicionarUsuario");

                    if ($cargo.val() !== "") {
                        $.ajax({
                            url: "adicionarUsuario.php",
                            type: "post",
                            data: $("#formAdicionarUsuario").serialize(),
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
                        mensagem(5, url, "Selecione o cargo.");
                    }
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogAdicionarUsuario").dialog("open");
    });

    $("#btnEditarUsuario").click(function() {

        $("#dialogEditarUsuario").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            height: 180,
            width: 350,
            modal: true,
            resizable: false,
            title: "EDITAR USUÁRIO",
            buttons: {
                "EDITAR": function() {
                    var url, editou, $cargo;

                    url = getUrl();
                    $cargo = $("#selectEditarUsuario");

                    if ($cargo.val() !== "") {
                        $.ajax({
                            url: "editarUsuario.php",
                            type: "post",
                            data: $("#formEditarUsuario").serialize(),
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
                        mensagem(5, url, "Selecione o cargo.");                    
                    }
                },
                "CANCELAR": function() {
                    $(this).dialog("destroy");
                }
            }
        });
        $("#dialogEditarUsuario").dialog("open");
    });

    $("#btnDesativarUsuario").click(function() {

        $("#dialogDesativarUsuario").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 370,
            modal: true,
            title: "DESATIVAR USUÁRIO",
            buttons: {
                "DESATIVAR": function() {
                    var url, idCadastrado, desativar;

                    idCadastrado = $("#idMembro").val();
                    url = getUrl();

                    $.ajax({
                        url: "desativarUsuario.php",
                        type: "post",
                        data: "id=" + idCadastrado,
                        success: function(retorno) {
                            desativar = retorno.trim();

                            if (desativar === "TRUE") {
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
    
    $("#btnDesligar").click(function() {
        var situacao;

        $("#dialogDesligarMembro").dialog({
            dialogClass: "no-close",
            resizable: false,
            height: 150,
            width: 340,
            modal: true,
            title: "DESLIGAR MEMBRO",
            buttons: {
                "OK": function() {
                    var url, idCadastrado, desligou;

                    idCadastrado = $("#idMembro").val();
                    url = getUrl();

                    $.ajax({
                        url: "desligarMembro.php",
                        type: "post",
                        data: "id=" + idCadastrado,
                        success: function(retorno) {
                            desligou = retorno.trim();

                            if (desligou === "TRUE") {
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