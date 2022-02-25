<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/RelatorioUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/RelatorioDAO.php";

$moduloDAO = new ModuloDAO();
$relatorioDAO = new RelatorioDAO();

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
    </head>
    <body>
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
                    <a href="../../inicio.php">IN&Iacute;CIO</a>
                    ::
                    <a href=""><?= $nomeModulo ?></a>
                </div>

                <div class="modulo">

                    <br />

                    <div class="titulo">
                        <label class="vermelho italico">* Escolha as op&ccedil;&otilde;es do relat&oacute;rio</label> <br />
                        <form method="post" name="formGerarRelatorio" id="formGerarRelatorio" action="relatorio.php">
                            <div class="negrito">
                                <br /> 
                                <label>Tipo:</label> &nbsp; 
                                <select name="selectTipoRelatorio" id="selectTipoRelatorio">
                                    <option value="">SELECIONE O TIPO</option>
                                    <option value="<?= RelatorioUtil::RELATORIO_MEMBRO ?>">MEMBRO</option>
                                    <option value="<?= RelatorioUtil::RELATORIO_CONGREGACAO ?>">CONGREGA&Ccedil;&Atilde;O</option>
                                </select>
                                <br /> <br />
                                <div id="divSelectOpcoes01"></div>
                                <br />
                                <div id="divSelectOpcoes02"></div>
                                <br />
                                <div id="divSelectOpcoes03"></div>
                            </div>
                            <div class="btnGerarRelatorio">
                                <input type="submit" name="btnGerar" id="btnGerar" value="GERAR" onclick="$(this).novaAbaForm(this.form)"/>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-form-relatorio.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-funcoes.js"></script>
    </body>
</html>