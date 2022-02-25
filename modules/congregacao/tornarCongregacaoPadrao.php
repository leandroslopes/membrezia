<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";

$congregacaoDAO = new CongregacaoDAO();

$idCongregacao = filter_input(INPUT_POST, "idCongregacao");
$idCongregacaoPadrao = filter_input(INPUT_POST, "idCongregacaoPadrao");

if ($congregacaoDAO->tornarPadrao($idCongregacao, $idCongregacaoPadrao)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  