<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CultoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Culto.php";

$cultoDAO = new CultoDAO();
$culto = new Culto();

$culto->setId(filter_input(INPUT_POST, "id"));
$culto->setNome(filter_input(INPUT_POST, "nome"));

if (empty($culto->getId())) {
    if ($cultoDAO->cadastrar($culto)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
} else {
    if ($cultoDAO->editar($culto)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
}
?>