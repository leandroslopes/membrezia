$(function() {

    $("#btnPesquisar").click(function() {
        var $cpf, $nome;

        $cpf = $("#cpf");
        $nome = $("#nome");

        if ($cpf.val() === "" && $nome.val() === "") {
            var $msgErro, mensagem;

            $msgErro = $(".msgErroForm");
            mensagem = "&bull; Preencha um campo";

            $msgErro.html(mensagem);
            $msgErro.removeClass("oculto");
            $msgErro.addClass("visivel");

            return false;
        }
    });
});