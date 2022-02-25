$(function() {
    $("#formLogin").validate({
        rules: {
            cpf: {
                required: true,
                number: true
            },
            senha: {
                required: true
            }
        },
        messages: {
            cpf: {
                required: "Informe o CPF",
                number: "Informe somente número"
            },
            senha: {
                required: "Informe a senha"
            }
        }
    });

    $("#formAlterarSenha").validate({
        rules: {
            senha: {
                required: true
            },
            confirmacaoSenha: {
                required: true,
                equalTo: "#senha"
            }
        },
        messages: {
            senha: {
                required: "Informe a senha"
            },
            confirmacaoSenha: {
                required: "Informe a confirmação de senha",
                equalTo: "Confirmação de senha errada"
            }
        }
    });

    $("#formCadastrar").validate({
        rules: {
            nome: {
                required: true
            },
            dataNascimento: {
                required: true
            },
            idEstado: {
                required: true
            }
        },
        messages: {
            nome: {
                required: "Informe o nome"
            },
            dataNascimento: {
                required: "Informe a data de nascimento"
            },
            idEstado: {
                required: "Selecione o estado"
            }
        }
    });
});