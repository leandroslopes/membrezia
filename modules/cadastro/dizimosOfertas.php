<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MovimentacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MembroDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";

$moduloDAO = new ModuloDAO();
$movimentacaoDAO = new MovimentacaoDAO();
$membroDAO = new MembroDAO();
$congregacaoDAO = new CongregacaoDAO();

$membro = "";

$idModulo = filter_input(INPUT_GET, "id");
$idMembro = filter_input(INPUT_GET, "idMembro");

$membro = $membroDAO->getMembro($idMembro);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SystemUtil::TITLE_SYSTEM ?> | DÍZIMOS E OFERTAS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../public/images/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../public/css/style.css"/>

        <link type="text/css" rel="stylesheet" href="../../public/js/jquery-ui-1.10.1/css/custom-theme/jquery-ui-1.10.1.custom.css"/>  
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-ui-1.10.1.custom.js"></script>

        <link type="text/css" rel="stylesheet" href="../../public/js/jquery-tablesorter-2.0.5b/themes/blue/style.css"/>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/jquery-latest.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/addons/pager/jquery.tablesorter.pager.js"></script> 
        <script type="text/javascript" src="../../public/js/scripts/jquery-tabelas.js"></script>

        <script type="text/javascript" src="../../public/js/jquery-maskmoney/jquery.maskmoney.js"></script>

        <script type="text/javascript" src="../../public/js/masked-input-1.3.1/jquery.maskedinput.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-masks.js"></script>

        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-msg.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-form-dizimo-oferta.js"></script>
    </head>
    <body>
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
                <?= $moduloDAO->mostrarModulos($_SESSION["usuario"], TRUE); ?>
            </div>

            <div id="conteudo">
                <div class="menuEstrutural">
                    <a href="../../inicio.php">IN&Iacute;CIO</a>
                    ::
                    <a href="index.php?id=<?= $idModulo ?>">CADASTRO</a>
                    ::
                    <a href="">D&Iacute;ZIMOS E OFERTAS</a>
                </div>

                <div id="conteudo">

                    <div class="modulo">

                        <? include "../../pageComponents/message.php"; ?>

                        <div class="oculto textoMedio" id="dialogAdicionarDizimo">
                            <br />
                            <form method="post" name="formAdicionarDizimo" id="formAdicionarDizimo" action="">
                                <input type="hidden" name="idModulo" id="idModulo" value="<?= $idModulo ?>"/>
                                <input type="hidden" name="idMembro" id="idMembro" value="<?= $idMembro ?>"/>
                                <div class="negrito">
                                    <label class="vermelho">*</label> <label>Valor:</label> &nbsp;
                                    <input type="text" name="valor" id="valor" class="maskDecimal" size="15"/> <br /> <br />
                                    <label class="vermelho">*</label> <label>Data:</label> &nbsp;
                                    <input type="text" name="dataMovimentacao" id="dataMovimentacao" class="maskDate" size="10"/>
                                </div> <br />
                                <label class="vermelho italico">* Campos obrigat&oacute;rios</label>
                            </form>
                        </div>

                        <div class="oculto textoMedio textoCentro" id="dialogExcluirDizimo">
                            <p>
                                Tem certeza que quer excluir este d&iacute;zimo?
                            </p>
                        </div>

                        <div class="oculto textoMedio" id="dialogAdicionarOferta">
                            <br />
                            <form method="post" name="formAdicionarOferta" id="formAdicionarOferta" action="">
                                <input type="hidden" name="idModuloAdicionarOferta" id="idModuloAdicionarOferta" value="<?= $idModulo ?>"/>
                                <input type="hidden" name="idMembroAdicionarOferta" id="idMembroAdicionarOferta" value="<?= $idMembro ?>"/>
                                <div class="negrito">
                                    <label class="vermelho">*</label> <label>Valor:</label> &nbsp;
                                    <input type="text" name="valor" id="valorAdicionarOferta" class="maskDecimal" size="15"/> <br /> <br />
                                    <label class="vermelho">*</label> <label>Data:</label> &nbsp;
                                    <input type="text" name="dataMovimentacao" id="dtMovimentacaoAdicionarOferta" class="maskDate" size="10"/> <br /> <br />
                                    <label>Congregação:</label> &nbsp;
                                    <?= $congregacaoDAO->getSelect($membro->getCongregacao()->getId()) ?>
                                </div> <br />
                                <label class="vermelho italico">* Campos obrigat&oacute;rios</label>
                            </form>
                        </div>

                        <div class="oculto textoMedio textoCentro" id="dialogExcluirOferta">
                            <p>
                                Tem certeza que quer excluir esta oferta?
                            </p>
                        </div>

                        <div class="titulo">CONTRIBUINTE: <?= $membro->getNome() ?></div>

                        <? if ($membro->getSituacao() === Membro::LIGADO) { ?>
                            <div class="extrasConteudo">
                                <ul>
                                    <li id="adicionarDizimo">
                                        <a class="cursor">
                                            <img src="../../public/images/icons/adicionar.png" alt="" />
                                            <label title="Adicionar">ADICIONAR DÍZIMO</label>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <? } ?> <br />

                        <?= $movimentacaoDAO->listarContribuicoes($idMembro, MovimentacaoTipoUtil::DIZIMO) ?> <br />

                        <div class="extrasConteudo">
                            <ul>
                                <li id="adicionarOferta">
                                    <a class="cursor">
                                        <img src="../../public/images/icons/adicionar.png" alt="" />
                                        <label title="Adicionar">ADICIONAR OFERTA</label>
                                    </a>
                                </li>
                            </ul>
                        </div> <br />

                        <?= $movimentacaoDAO->listarContribuicoes($idMembro, MovimentacaoTipoUtil::OFERTA) ?>

                    </div>

                </div>
            </div>
        </div>
    </body>
</html>