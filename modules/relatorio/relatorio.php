<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/RelatorioDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/RelatorioUtil.php";

$relatorioDAO = new RelatorioDAO();
$congregacaoDAO = new CongregacaoDAO();

$congregacao = $congregacaoDAO->getCongregacaoPadrao();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>RELATÓRIO - IMPRESSÃO</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../public/images/icons/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../public/css/style.css"/>
    </head>
    <body id="semBackground">

        <div id="imprimir">

            <table id="tblImprimirCabecalho">
                <thead>
                    <tr><th>MINIST&Eacute;RIO QUERITE <?= $congregacao->getNome() ?></th></tr>
                    <tr>
                        <th>
                            <?= $congregacao->getRua() . ', ' . $congregacao->getBairro() . ', ' . $congregacao->getComplemento() . ', ' ?>
                            <?=  $congregacao->getCidade()->getNome() . '/' . $congregacao->getCidade()->getEstado()->getSigla() ?>
                        </th>
                    </tr>
                </thead>
            </table>

            <?= $relatorioDAO->imprimir($_REQUEST) ?>
        </div>
    </body>
</html>