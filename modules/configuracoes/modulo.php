<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/CargoUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloDAO.php";

$moduloDAO = new ModuloDAO();

$idModulo = filter_input(INPUT_GET, "id");
$idModuloCadastrado = filter_input(INPUT_GET, "idModuloCadastrado");

$nomeModulo = $moduloDAO->getModulo($idModulo)->getNome();

$moduloGerenciado = $moduloDAO->getModulo($idModuloCadastrado);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SystemUtil::TITLE_SYSTEM . ' | ' . $nomeModulo?> | GERENCIAR MÓDULO</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../public/images/icons/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../public/css/style.css"/>

        <link type="text/css" rel="stylesheet" href="../../public/js/jquery-ui-1.10.1/css/custom-theme/jquery-ui-1.10.1.custom.css"/>  
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-ui-1.10.1.custom.js"></script>

        <link type="text/css" rel="stylesheet" href="../../public/js/jquery-tablesorter-2.0.5b/themes/blue/style.css"/>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/jquery-latest.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/addons/pager/jquery.tablesorter.pager.js"></script> 
        <script type="text/javascript" src="../../public/js/scripts/jquery-tabelas.js"></script>

        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-msg.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-form-configuracoes.js"></script>
    </head>
    <body>

        <? include "../../pageComponents/message.php"; ?>

        <div class="corpo">
            <? require SystemUtil::URL_SYSTEM . "pageComponents/header.php?idModulo=" . $idModulo; ?>

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
                <?= $moduloDAO->mostrarModulos($_SESSION["usuario"], TRUE); ?>
            </div>

            <div id="conteudo">
                <div class="menuEstrutural">
                    <a href="../../inicio.php" title="">IN&Iacute;CIO</a>
                    ::
                    <a href="index.php?id=<?= $idModulo ?>" title=""><?= $nomeModulo ?></a>
                    ::
                    <a href="modulos.php?id=<?= $idModulo ?>" title="">M&Oacute;DULOS</a>
                    ::
                    <a href="modulo.php?id=<?= $idModulo . "&idModuloCadastrado=" . $idModuloCadastrado ?>" title="">GERENCIAR M&Oacute;DULO</a>
                </div>

                <div class="modulo">
                    <div class="titulo">GERENCIAMENTO DO M&Oacute;DULO "<?= $moduloGerenciado->getNome() ?>"</div> <br />

                    <div class="oculto txtMedio" id="dialogAdicionarAcessoCargo">
                        <br />
                        <form method="post" name="formAdicionarCargo" id="formAdicionarCargo" action="">
                            <input type="hidden" name="idModulo" id="idModulo" value="<?= $idModulo ?>"/>
                            <input type="hidden" name="idModuloCadastrado" id="idModuloCadastrado" value="<?= $idModuloCadastrado ?>"/>
                            <div class="negrito">
                                <label>Cargo:</label> &nbsp;
                                <?= CargoUtil::getSelect(CargoUtil::SEM_CARGO, "idCargo") ?>
                            </div>
                        </form>
                    </div>

                    <div class="oculto textoMedio" id="dialogExcluirAcessoCargo">
                        <p>
                            Tem certeza que quer excluir o acesso deste cargo?
                        </p>
                    </div>

                    <div class="extrasConteudo">
                        <ul>
                            <li id="adicionarAcessoCargo">
                                <a class="cursor">
                                    <img src="../../public/images/icons/adicionar.png" alt="" />
                                    <label title="Adicionar acesso">ADICIONAR ACESSO</label>
                                </a>
                            </li>
                        </ul>
                    </div> <br />

                    <?= $moduloDAO->listarCargos($idModuloCadastrado) ?> <br />

                </div>
            </div>
        </div>
    </body>
</html>