<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/CargoUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloDAO.php";

$moduloDAO = new ModuloDAO();
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

                <div class="meuPerfil">
                    <div class="fotoPerfil">
                        <img src="../../public/images/others/sem_foto.jpg" alt="" title=""/>
                    </div>
                    <div class="nomePerfil">
                        <p class="textoPequeno">
                            <?= $_SESSION["usuario"]["nome"] ?> <br />
                            <?= CargoUtil::getNome($_SESSION["usuario"]["idCargo"]) ?>
                        </p>
                    </div>
                </div>

                <div class="opcoes">
                    <ul>
                        <li>
                            <a href="formAlterarSenha.php?id=<?= $idModulo ?>">
                                Alterar Senha
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>