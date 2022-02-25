<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/MovimentacaoTipoUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoCultoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CultoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/EstadoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MovimentacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Congregacao.php";

$moduloDAO = new ModuloDAO();
$congregacaoDAO = new CongregacaoDAO();
$congregacaoCultoDAO = new CongregacaoCultoDAO();
$cultoDAO = new CultoDAO();
$estadoDAO = new EstadoDAO();
$movimentacaoDAO = new MovimentacaoDAO();

$idModulo = filter_input(INPUT_GET, "id");
$idCongregacao = filter_input(INPUT_GET, "idCongregacao");

$congregacao = $congregacaoDAO->getCongregacao($idCongregacao);

$nomeModulo = $moduloDAO->getModulo($idModulo)->getNome();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SystemUtil::TITLE_SYSTEM . ' | ' .$nomeModulo?></title>
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
                    <a href="index.php?id=<?= $idModulo ?>"><?= $nomeModulo ?></a>
                    ::
                    <a href=""><?= $congregacao->getNome() ?></a>
                </div>

                <div class="modulo">

                    <? include "../../pageComponents/message.php"; ?>

                    <div class="oculto textoMedio" id="dialogAdicionarCulto">
                        <br />
                        <form method="post" name="formAdicionarCulto" id="formAdicionarCulto" action="">
                            <input type="hidden" name="idModulo" id="idModulo" value="<?= $idModulo ?>"/>
                            <input type="hidden" name="idCongregacao" id="idCongregacao" value="<?= $idCongregacao ?>"/>
                            <div class="negrito">
                                <label>Culto:</label> &nbsp;
                                <?= $cultoDAO->getSelectAdicionarCulto($idCongregacao) ?>
                            </div>
                        </form>
                    </div>

                    <div class="oculto textoMedio textoCentro" id="dialogDesassociarCulto">
                        <p>
                            Tem certeza que quer desassociar este culto?
                        </p>
                    </div>

                    <div class="oculto textoMedio" id="dialogAdicionarOferta">
                        <br />
                        <form method="post" name="formAdicionarOferta" id="formAdicionarOferta" action="">
                            <input type="hidden" name="tipoMovimentacao" id="tipoMovimentacao" value="<?= MovimentacaoTipoUtil::OFERTA ?>"/>
                            <div class="negrito">
                                <label class="vermelho">*</label> <label>Valor:</label> &nbsp;
                                <input type="text" name="valor" id="valor" class="maskDecimal" size="15"/> <br /> <br />
                                <label class="vermelho">*</label> <label>Data:</label> &nbsp;
                                <input type="text" name="dataMovimentacao" id="dataMovimentacao" class="maskDate" size="10"/> <br /> <br />
                            </div> <br />
                            <label class="vermelho italico">* Campos obrigat&oacute;rios</label>
                        </form>
                    </div>

                    <div class="oculto textoMedio textoCentro" id="dialogExcluirOferta">
                        <p>Tem certeza que quer excluir esta oferta?</p>
                    </div>

                    <table class="tablesorter">
                        <thead>
                            <tr>
                                <th>CONGREGAÇÃO</th>
                                <th>ENDERE&Ccedil;O</th>
                                <th>FONE</th>
                                <th>FUNDA&Ccedil;&Atilde;O</th>
                                <th>USU&Aacute;RIO CADASTRO</th>
                                <th>DATA CADASTRO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $congregacao->getNome() ?></td>
                                <td>
                                <?= $congregacao->getRua() . ', ' . $congregacao->getBairro() . ', ' . $congregacao->getComplemento() . ', '?>
                                <?= $congregacao->getCidade()->getNome() . '/' . $congregacao->getCidade()->getEstado()->getSigla() ?>
                                </td>
                                <td><?= $congregacao->getFone() ?></td>
                                <td><?= $congregacao->getDataFundacao(); ?></td>
                                <td><?= $congregacao->getUsuarioCadastro()->getNome(); ?></td>
                                <td><?= $congregacao->getDataCadastro(); ?></td>
                            </tr>
                        </tbody>                        
                    </table> <br />

                    <div class="extrasConteudo">
                        <ul>
                            <li id="adicionarCulto">
                                <a class="cursor">
                                    <img src="../../public/images/icons/adicionar.png" alt="" />
                                    <label title="Adicionar">ADICIONAR CULTO</label>
                                </a>
                            </li>
                        </ul>
                    </div> <br />

                    <?= $congregacaoCultoDAO->listarCultos($idCongregacao) ?> <br />

                    <?= $movimentacaoDAO->listarOfertas($idCongregacao); ?> <br /> <br />

                </div>
            </div>
        </div>
    </body>
</html>