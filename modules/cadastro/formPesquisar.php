<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MembroDAO.php";

$moduloDAO = new ModuloDAO();
$membroDAO = new MembroDAO();

$idModulo = filter_input(INPUT_GET, "id");
$cpf = filter_input(INPUT_POST, "cpf");
$nome = filter_input(INPUT_POST, "nome");

$nomeModulo = $moduloDAO->getModulo($idModulo)->getNome();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SystemUtil::TITLE_SYSTEM . ' | ' . $nomeModulo?> | Pesquisar</title>
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

        <script type="text/javascript" src="../../public/js/scripts/jquery-funcoes.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-validar-form-pesquisar.js"></script>
    </head>
    <body>
        <div class="corpo">
            <? include SystemUtil::URL_SYSTEM . "pageComponents/header.php?id_modulo=" . $idModulo ?>

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
                    <a href="formPesquisar.php?id=<?= $idModulo ?>">PESQUISAR</a>
                </div>

                <div class="modulo">
                
                    <div class="titulo">PESQUISAR CADASTRO</div>

                </div>

                <? if (empty($cpf) && empty($nome)) { ?>
                    <form method="post" name="formPesquisar" id="formPesquisar" action="">
                        <div class="formulario">
                            <br />
                            <div class="campo">
                                <b>CPF:</b> <br />
                                <input type="text" name="cpf" id="cpf" maxlength="11" size="15" class="maskNumero"/>
                            </div>
                            <div class="campo">
                                <b>Nome:</b> <br />
                                <input type="text" name="nome" id="nome" maxlength="255" size="70" class="retiraAcento focus"/>
                            </div>        
                            <br />
                            <div class="btnPesquisar">
                                <input type="submit" name="btnPesquisar" id="btnPesquisar" value="Pesquisar"/>
                            </div>
                            <div class="msgErroForm oculto"></div>
                        </div>
                    </form>
                <? } else { ?>
                    <div class="tabela">
                        <?= $membroDAO->listarMembros($_REQUEST, $idModulo) ?>
                    </div>
                <? } ?>
                
            </div>
        </div>
    </body>
</html>