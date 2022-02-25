<?php

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/CartaUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MembroDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/FuncaoDAO.php";

$membroDAO = new MembroDAO();
$membro = new Membro();
$congregacaoDAO = new CongregacaoDAO();
$funcaoDAO = new FuncaoDAO();

$idMembro = filter_input(INPUT_GET, "idMembro");
$tipo = filter_input(INPUT_GET, "tipo");

$congregacao = $congregacaoDAO->getCongregacaoPadrao();
$membro = $membroDAO->getMembro($idMembro);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>MINISTÉRIO QUERITE <?= CartaUtil::getNomeCarta($tipo) ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../public/images/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../public/css/style.css"/>
    </head>
    <body id="semBackground">
        
        <table id="tblImprimirCabecalhoSemBorda">
            <thead>
                <tr>
                    <th>
                        <img width="250px" src="../../public/images/others/logo_querite.jpg" alt="">
                    </th>
                </tr>
                <tr><th>&nbsp;</th></tr>
                <tr>
                    <th>
                        END.:
                        <?= $congregacao->getRua() . ', ' . $congregacao->getBairro() . ', ' . $congregacao->getComplemento() . ', ' ?>
                        <?=  $congregacao->getCidade()->getNome() . '/' . $congregacao->getCidade()->getEstado()->getSigla() ?>
                    </th>
                </tr>
                <tr><th>&nbsp;</th></tr>
                <tr><th>&nbsp;</th></tr>
                <tr><th>&nbsp;</th></tr>
                <tr><th>&nbsp;</th></tr>
                <tr><th>&nbsp;</th></tr>
                <tr><th>&nbsp;</th></tr>
                <tr><th class="textoMedio tam16 negrito"><?= CartaUtil::getNomeCarta($tipo) ?></th></tr>
            </thead>

        </table> <br /> <br /> <br /> <br /> <br /> <br />

        <p class="textoJustificado">Eu, Sandra Regina Silva Matos, pastora presidente do Ministério Querite em Paço do Lumiar - Maranhão, 
        recomendo o(a) irmão(ã) <?= $membro->getNome() ?> na função de <?= $funcaoDAO->getFuncao($membro->getFuncao()->getId())->getNome() ?> 
        para que a recebais no Senhor Jesus Cristo como fazem os santos.
        </p> <br /> <br />

        <p class="textoJustificado">
        Ressalto também que o(a) irmão(ã) está em plena comunhão com esta igreja, portanto, nada a
        impede de gozar dos direitos e desenvolver deveres pertinentes aos santos.
        </p> <br /> <br /> <br /> <br />

        <p>
        Em Cristo,
        </p>

        <p class="textoCentro"> <br /> <br />
        ____________________________________ <br />
             Sandra Regina Silva Matos <br />
                 Pastora Presidente <br />
        <br /> <br /> <br /> 
        </p>

        <p class="textoCentro"> 
        ____________________________________ <br />
                    Secretário(a) <br /> <br /> <br /> <br /> <br />
        </p>

        <p>
        <?= DataUtil::imprimirDataAtual(); ?> <br /> <br /> <br /> <br />

        OBS.: Este documento tem validade de 30 dias a partir da data de emissão.
        </p> <br /><br />

        <p>
        E-mail: sandrarsm48@gmail.com <br />
        Fone: (98) 98567-4710
        </p>

    </body>
</html>