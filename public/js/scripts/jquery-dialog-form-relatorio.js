$(function() {

    /* RELATORIO */
    $("#selectTipoRelatorio").change(function() {
        var tipo, divSelectOpcoes01;

        tipo = $(this);
        divSelectOpcoes01 = $("#divSelectOpcoes01");        
        divSelectOpcoes02 = $("#divSelectOpcoes02");
        divSelectOpcoes03 = $("#divSelectOpcoes03");
        
        $.ajax({
            type: "post",
            url: "../../public/js/scripts/ajax.php",
            data: {
                tipo: "relatorio",
                idSelect: tipo.val()
            },
            success: function(select) { 
                if (tipo.val() == 1 || tipo.val() === "") { //MEMBRO //NADA SELECIONADO
                    divSelectOpcoes02.html("");
                    divSelectOpcoes03.html("");
                }
                divSelectOpcoes01.html(select);
            }
        });
    });

    $(document).on("change", "#idCongregacao", function() {
        var selectCongregacao, divSelectOpcoes02, divSelectOpcoes03;

        selectCongregacao = $(this);
        divSelectOpcoes02 = $("#divSelectOpcoes02");
        divSelectOpcoes03 = $("#divSelectOpcoes03");

        $.ajax({
            type: "post",
            url: "../../public/js/scripts/ajax.php",
            data: {
                tipo: "select",
                subtipo: "congregacao",
                selectValor: selectCongregacao.val()
            },
            success: function(select) {
                divSelectOpcoes02.html(select);
                if (select === "") {
                    divSelectOpcoes03.html("");
                }
            }
        });
    });

    $(document).on("change", "#selectOpcoesCongregacao", function() {
        var selectOpcoesCongregacao, divSelectOpcoes03;

        selectOpcoesCongregacao = $(this);
        divSelectOpcoes03 = $("#divSelectOpcoes03");

        $.ajax({
            type: "post",
            url: "../../public/js/scripts/ajax.php",
            data: {
                tipo: "select",
                subtipo: "opcaoCongregacao",
                selectValor: selectOpcoesCongregacao.val()
            },
            success: function(select) {
                divSelectOpcoes03.html(select);
            }
        });
    });
});