<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/EstadoDAO.php";

$moduloDAO = new ModuloDAO();
$congregacaoDAO = new CongregacaoDAO();
$estadoDAO = new EstadoDAO();

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

        <link type="text/css" rel="stylesheet" href="../../public/js/jquery-ui-1.10.1/css/custom-theme/jquery-ui-1.10.1.custom.css"/>  
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-ui-1.10.1.custom.js"></script>

        <link type="text/css" rel="stylesheet" href="../../public/js/jquery-tablesorter-2.0.5b/themes/blue/style.css"/>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/jquery-latest.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/addons/pager/jquery.tablesorter.pager.js"></script> 
        <script type="text/javascript" src="../../public/js/scripts/jquery-tabelas.js"></script>

        <script type="text/javascript" src="../../public/js/masked-input-1.3.1/jquery.maskedinput.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-maskmoney/jquery.maskmoney.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-masks.js"></script>

        <script type="text/javascript" src="../../public/js/jquery-validate-1.11.0/jquery.validate.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-validate-form.js"></script>

        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-msg.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-funcoes.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-form-congregacao.js"></script>
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
                <?= $moduloDAO->mostrarModulos($_SESSION["usuario"], TRUE) ?>
            </div>

            <div id="conteudo">
                <div class="menuEstrutural">
                    <a href="../../inicio.php">IN&Iacute;CIO</a>
                    ::
                    <a href=""><?= $nomeModulo ?></a>
                </div>

                <div class="modulo">

                    <? include "../../pageComponents/message.php" ?>
                
                    <div class="oculto textoMedio" id="dialogCadastrarCongregacao">
                        <br />
                        <form method="post" name="formCadastrarCongregacao" id="formCadastrarCongregacao" action="">
                            <input type="hidden" name="idModulo" id="idModulo" value="<?= $idModulo ?>"/>
                            <div class="negrito">
                                <label class="vermelho">*</label><label>Nome:</label> &nbsp;
                                <input type="text" name="nome" id="nome" maxlength="70" size="48" class="caixaAlta retiraAcento"/> 
                                <br /> <br />
                                <label>Rua/Av.:</label> &nbsp;
                                <input type="text" name="rua" id="rua" maxlength="70" size="47" class="caixaAlta retiraAcento"/>
                                <br /> <br />
                                <label>Bairro:</label> &nbsp;
                                <input type="text" name="bairro" id="bairro" maxlength="70" size="49" class="caixaAlta retiraAcento"/>
                                <br /> <br />
                                <label>Complemento:</label> &nbsp;
                                <input type="text" name="complemento" id="complemento" maxlength="70" size="41" class="caixaAlta retiraAcento"/>
                                <br /> <br />
                                <label>Estado:</label> &nbsp;
                                <?= $estadoDAO->getSelect() ?>
                                <br /> <br />
                                <label class="vermelho">*</label><label>Cidade:</label> &nbsp;
                                <select name="idCidade" id="idCidade">
                                    <option value="">SELECIONE UMA CIDADE</option>
                                </select>
                                <br /> <br />
                                <label>Fone:</label> &nbsp;
                                <input type="text" name="fone" class="maskFone" id="fone" maxlength="15" size="15"/>
                                <br /> <br />
                                <label>Data de Fundação:</label> &nbsp;
                                <input type="text" name="dataFundacao" class="maskDate" id="dataFundacao" maxlength="10" size="12" />
                            </div>
                            <br />
                            <label class="vermelho italico">* Campos obrigat&oacute;rios</label>
                        </form>
                    </div>

                    <div class="oculto textoMedio textoCentro" id="dialogIsPadrao">
                        <p>Tem certeza que quer tornar esta congregação como padrão?</p>
                    </div>

                    <div class="extrasConteudo">
                        <ul>
                            <li id="cadastrarCongregacao">
                                <a class="cursor">
                                    <img src="../../public/images/icons/adicionar.png" alt="" />
                                    <label title="Cadastrar">CADASTRAR</label>
                                </a>
                            </li>
                        </ul>
                    </div> <br />

                    <?= $congregacaoDAO->listarCongregacoes($idModulo) ?>

                </div>
            </div>
        </div>
    </body>
</html>