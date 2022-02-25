<?php

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

$nomeModulo = $moduloDAO->getModulo($idModulo)->getNome();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SystemUtil::TITLE_SYSTEM . ' | ' . $nomeModulo ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../public/images/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../public/css/style.css"/>
        <link type="text/css" rel="stylesheet" href="../../public/js/jquery-ui-1.10.1/css/custom-theme/jquery-ui-1.10.1.custom.css"/>  
        <link type="text/css" rel="stylesheet" href="../../public/js/jquery-tablesorter-2.0.5b/themes/blue/style.css"/>
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
                <?= $moduloDAO->mostrarModulos($_SESSION["usuario"], TRUE) ?>
            </div>

            <div id="conteudo">
                <div class="menuEstrutural">
                    <a href="../../inicio.php">IN&Iacute;CIO</a>
                    ::
                    <a href=""><?= $nomeModulo ?></a>
                </div>

                <div class="modulo">
            
                    <div class="titulo">MEMBROS CADASTRADOS NO MÊS VIGENTE</div> <br />

                    <div class="extrasConteudo">
                        <ul>
                            <li>
                                <a class="textoPequeno cursor" href="formPesquisar.php?id=<?= $idModulo ?>">
                                    <img src="../../public/images/icons/pesquisar.png" alt="" />
                                    <label title="Cadastrar">PESQUISAR</label>
                                </a>
                            </li>
                            <li>
                                <a class="textoPequeno cursor" href="formCadastrar.php?id=<?= $idModulo ?>">
                                    <img src="../../public/images/icons/adicionar.png" alt="" />
                                    <label title="Cadastrar">CADASTRAR</label>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="tabela">
                    <?= $membroDAO->listarMembros("", $idModulo, true) ?>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-ui-1.10.1.custom.js"></script>

        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/jquery-latest.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-tablesorter-2.0.5b/addons/pager/jquery.tablesorter.pager.js"></script> 
        <script type="text/javascript" src="../../public/js/scripts/jquery-tabelas.js"></script>
    </body>
</html>