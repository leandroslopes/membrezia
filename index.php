<?php
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

require "source/util/SystemUtil.php";

$loginErro = filter_input(INPUT_GET, "q");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SystemUtil::TITLE_SYSTEM ?> | Login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="shortcut icon" href="public/images/icones/favicon.png"/>
        <link type="text/css" rel="stylesheet" href="public/css/style.css"/>
    </head>
    <body>
        <div class="corpo">
            <?php include SystemUtil::URL_SYSTEM . "pageComponents/header.php?idModulo=login" ?>

            <div id="login">
                <?php
                if (isset($loginErro)) {
                    if ($loginErro == 1) {
                        ?>
                        <div id="erroLogin">
                            <p>CPF e/ou senha incorretos!</p>
                        </div>
                        <?
                    }
                }
                ?>

                <div id="camposLogin" class="botao">
                    <form method="post" name="login" id="formLogin" action="login.php">
                        <div id="campoCpf" class="negrito">
                            <label>CPF:</label> <br />
                            <input type type="text" name="cpf" id="cpf" value="" class="focus" maxlength="11" size="32"/>
                        </div>
                        <br />
                        <div id="campoSenha" class="negrito">
                            <label>SENHA:</label> <br />
                            <input type="password" name="senha" id="senha" maxlength="6" size="32"/>
                        </div>
                        <br />
                        <div id="botaoLogin">
                            <input type="submit" name="btnLogin" id="btnLogin" value="ENTRAR" />
                        </div>
                        <div id="esqueceuSenha">
                            <a href="#">Esqueceu Sua Senha?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="public/js/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="public/js/jquery-validate-1.11.0/jquery.validate.js"></script>
        <script type="text/javascript" src="public/js/scripts/jquery-validate-form.js"></script>
        <script type="text/javascript" src="public/js/scripts/jquery-funcoes.js"></script>
    </body>
</html>