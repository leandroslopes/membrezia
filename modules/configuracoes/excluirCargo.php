<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloAcessoDAO.php";

$moduloAcessoDAO = new ModuloAcessoDAO();

if ($moduloAcessoDAO->excluirAcessoCargo(filter_input(INPUT_POST, "id"))) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  