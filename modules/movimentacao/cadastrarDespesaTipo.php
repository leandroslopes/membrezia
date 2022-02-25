<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/DespesaTipoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/DespesaTipo.php";

$despesaTipoDAO = new DespesaTipoDAO();
$despesaTipo = new DespesaTipo();

$despesaTipo->setId(filter_input(INPUT_POST, "id"));
$despesaTipo->setNome(filter_input(INPUT_POST, "tipo"));

if (empty($despesaTipo->getId())) {
    if ($despesaTipoDAO->cadastrar($despesaTipo)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
} else {
    if ($despesaTipoDAO->editar($despesaTipo)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
}
?>  