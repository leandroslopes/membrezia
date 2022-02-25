<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MembroDAO.php";

$moduloDAO = new ModuloDAO();

$idModulo = filter_input(INPUT_GET, "id");

$nomeModulo = $moduloDAO->getModulo($idModulo)->getNome();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SystemUtil::TITLE_SYSTEM . ' | ' . $nomeModulo?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../public/images/icons/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../public/css/style.css"/>

        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-ui-1.10.1.custom.js"></script>
        <link type="text/css" rel="stylesheet" href="../../public/js/jquery-ui-1.10.1/css/custom-theme/jquery-ui-1.10.1.custom.css"/>

        <script type="text/javascript" src="../../public/js/jquery-validate-1.11.0/jquery.validate.js"></script> 
        <script type="text/javascript" src="../../public/js/scripts/jquery-validate-form.js"></script>

        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-msg.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-funcoes.js"></script>
    </head>
    <body>

        <? include '../../pageComponents/message.php'; ?>

        <?
        $btnAlterar = filter_input(INPUT_POST, "btnAlterar");

        if (!empty($btnAlterar)) {
            $membroDAO = new MembroDAO();

            $senha = md5(filter_input(INPUT_POST, "senha"));

            $urlAlterar = "formAlterarSenha.php?id=$idModulo";
            $urlSair = "../../sair.php";

            if ($membroDAO->alterarSenha($senha)) {
                ?>
                <script type="text/javascript">
                    mensagem(6, "<?= $urlSair ?>", "");
                </script>
                <?
            } else {
                ?>
                <script type="text/javascript">
                    mensagem(2, "<?= $urlAlterar ?>", "");
                </script>
                <?
            }
        }
        ?>        

        <div class="corpo">
            <? require SystemUtil::URL_SYSTEM . "pageComponents/header.php?idModulo=" . $idModulo ?>

            <div class="extras">
                <ul>
                    <li><?= DataUtil::imprimirDataAtual() ?></li>
                    <li>|</li>
                    <li>
                        <a href="../../sair.php" class="cursor">
                            <img src="../../public/images/icons/sair.png"/>
                            <label>Sair do sistema</label>
                        </a>
                    </li>
                </ul>
            </div> <br />

            <div class="modulos">
                <?= $moduloDAO->mostrarModulos($_SESSION["usuario"], TRUE) ?>
            </div>

            <div id="conteudo">
                <div class="menuEstrutural">
                    <a href="../../inicio.php">IN&Iacute;CIO</a>
                    ::
                    <a href="index.php?id=<?= $idModulo ?>"><?= $nomeModulo ?></a>
                    ::
                    <a href="formAlterarSenha.php?id=<?= $idModulo ?>">ALTERAR SENHA</a>
                </div>

                <form method="post" name="formAlterarSenha" id="formAlterarSenha" action="" >
                    <div class="formulario">
                        <br />
                        <div class="campo negrito">
                            <label for="senha">Senha:</label> <br />
                            <input type="password" name="senha" id="senha" class="focus" maxlength="6" size="20"/>
                        </div> <br />
                        <div class="campo negrito">
                            <label for="confirmacaoSenha">Confirma&ccedil;&atilde;o  de Senha:</label> <br />
                            <input type="password" name="confirmacaoSenha" id="confirmacaoSenha" maxlength="6" size="20"/>
                        </div> <br />
                        <div class="botao">
                            <input type="submit" name="btnAlterar" value="Alterar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>