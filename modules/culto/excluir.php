<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CultoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Culto.php";

$cultoDAO = new CultoDAO();
$culto = new Culto();

$culto->setId(filter_input(INPUT_POST, "id"));

if ($cultoDAO->excluir($culto)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>