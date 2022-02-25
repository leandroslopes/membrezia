<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloAcessoDAO.php";

$moduloAcessoDAO = new ModuloAcessoDAO();

$idModulo = filter_input(INPUT_POST, "idModuloCadastrado"); 
$idCargo = filter_input(INPUT_POST, "idCargo");

if ($moduloAcessoDAO->cargoEstaAdicionado($idModulo, $idCargo)) {
    echo "ADICIONADO";
} else if ($moduloAcessoDAO->adicionarAcessoCargo($idModulo, $idCargo)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  