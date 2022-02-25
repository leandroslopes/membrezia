<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoCultoDAO.php";

$congregacaoCultoDAO = new CongregacaoCultoDAO();

$idCongregacaoCulto = filter_input(INPUT_POST, "id");

if ($congregacaoCultoDAO->desassociar($idCongregacaoCulto)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  