<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoCultoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/CongregacaoCulto.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Congregacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Culto.php";

$congregacaoCultoDAO = new CongregacaoCultoDAO();
$congregacaoCulto = new CongregacaoCulto();
$congregacao = new Congregacao();
$culto = new Culto();

$congregacao->setId(filter_input(INPUT_POST, "idCongregacao"));
$congregacaoCulto->setCongregacao($congregacao);

$culto->setId(filter_input(INPUT_POST, "idCulto"));
$congregacaoCulto->setCulto($culto);

if ($congregacaoCultoDAO->adicionar($congregacaoCulto)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  