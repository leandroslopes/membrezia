<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/FuncaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Funcao.php";

$funcaoDAO = new FuncaoDAO();
$funcao = new Funcao();

$funcao->setSigla(filter_input(INPUT_POST, "sigla"));
$funcao->setNome(filter_input(INPUT_POST, "nome"));

if ($funcaoDAO->cadastrar($funcao)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>