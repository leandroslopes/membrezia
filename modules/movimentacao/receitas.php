<?php
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/MovimentacaoTipoUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MovimentacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/DespesaTipoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/DespesaTipo.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/CongregacaoCulto.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";

$moduloDAO = new ModuloDAO();
$movimentacaoDAO = new MovimentacaoDAO();
$despesaTipoDAO = new DespesaTipoDAO();
$congregacaoDAO = new CongregacaoDAO();

$idModulo = filter_input(INPUT_GET, "id");

$nomeModulo = $moduloDAO->getModulo($idModulo)->getNome();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SystemUtil::TITLE_SYSTEM . ' | ' . $nomeModulo?> | RECEITAS</title>
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

        <script type="text/javascript" src="../../public/js/jquery-maskmoney/jquery.maskmoney.js"></script>
        <script type="text/javascript" src="../../public/js/masked-input-1.3.1/jquery.maskedinput.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-masks.js"></script>

        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-msg.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-funcoes.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-form-movimentacao.js"></script>
    </head>
    <body>
        <div class="corpo">
            <? include_once SystemUtil::URL_SYSTEM . "pageComponents/header.php?idModulo=" . $idModulo; ?>

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
                    <a href="">RECEITAS</a>
                </div>

                <div class="modulo">

                    <? include "../../pageComponents/message.php"; ?>

                    <div class="oculto textoMedio" id="dialogCadastrarReceita">
                        <form method="post" name="formCadastrarReceita" id="formCadastrarReceita" action="">
                            <input type="hidden" name="idModulo" id="idModulo" value="<?= $idModulo ?>"/>
                            <input type="hidden" name="tipoMovimentacao" id="tipoMovimentacao" value="<?= MovimentacaoTipoUtil::RECEITA ?>"/>
                            <div class="negrito">
                                <label class="vermelho">*</label> <label>Valor:</label> &nbsp;
                                <input type="text" name="valor" id="valor" size="10" class="maskDecimal"/> 
                                <br /> <br />
                                <label class="vermelho">*</label> <label>Descrição:</label> &nbsp;
                                <input type="text" name="descricao" id="descricao" size="38" maxlenght="70" class="caixaAlta retiraAcento"/> 
                                <br /> <br />
                                <label class="vermelho">*</label> <label>Congregação:</label> &nbsp;
                                <?= $congregacaoDAO->getSelect() ?> 
                                <br /> <br />
                                <label class="vermelho">*</label> <label>Data de Movimentação:</label> &nbsp;
                                <input type="text" name="dataMovimentacao" id="dataMovimentacao" class="maskDate" size="10"/> 
                                <br /> <br />
                            </div>
                            <label class="vermelho italico">* Campos obrigat&oacute;rios</label>
                        </form>
                    </div>

                    <div class="oculto textoMedio textoCentro" id="dialogExcluirReceita">
                        <p>
                            Tem certeza que quer excluir esta receita?
                        </p>
                    </div>

                    <div class="titulo">RECEITAS ADICIONADAS NO ANO CORRENTE (<?= DataUtil::getAnoCorrente() ?>)</div> <br />

                    <div class="extrasConteudo">
                        <ul>
                            <li id="cadastrarReceita">
                                <a class="cursor">
                                    <img src="../../public/images/icons/adicionar.png" alt="" />
                                    <label title="Cadastrar">CADASTRAR</label>
                                </a>
                            </li>
                        </ul>
                    </div> <br />

                    <?= $movimentacaoDAO->listarReceitas() ?>

                </div>
            </div>
        </div>
    </body>
</html>