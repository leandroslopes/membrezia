<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MovimentacaoDAO.php";

$movimentacaoDAO = new MovimentacaoDAO();

$id = filter_input(INPUT_POST, "id");

if ($movimentacaoDAO->excluir($id)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  