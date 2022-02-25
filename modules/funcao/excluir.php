<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/FuncaoDAO.php";

$funcaoDAO = new FuncaoDAO();

$id = filter_input(INPUT_POST, "id");

if ($funcaoDAO->funcaoEstaAssociada($id)) {
    echo "ASSOCIADA";
} else if ($funcaoDAO->excluir($id)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  