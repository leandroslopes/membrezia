<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/DespesaTipoDAO.php";

$despesaTipoDAO = new DespesaTipoDAO();

if ($despesaTipoDAO->excluir(filter_input(INPUT_POST, "id"))) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  