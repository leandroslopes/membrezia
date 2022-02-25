<?
include_once "../source/util/SystemUtil.php";
include_once "../source/dao/ModuloDAO.php";

$moduloDAO = new ModuloDAO();
$idModulo = filter_input(INPUT_GET, "idModulo");

if ($idModulo == "login") {
    $nomeModulo = "LOGIN";
} else if ($idModulo == "inicio") {
    $nomeModulo = "INÍCIO";
} else {
    $nomeModulo = $moduloDAO->getModulo($idModulo)->getNome();
}
?>
<div class="cabecalho">
    <div class="cabecalhoTitulo">  
        <h1><?= SystemUtil::TITLE_SYSTEM . ' | ' . $nomeModulo ?></h1>
        <label><?= SystemUtil::SUBTITLE_SYSTEM ?></label>
    </div>
</div>
<div class="linha"></div>