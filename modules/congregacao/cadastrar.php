<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Congregacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Cidade.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";

$congregacaoDAO = new CongregacaoDAO();
$congregacao = new Congregacao();
$cidade = new Cidade();

$congregacao->setId(filter_input(INPUT_POST, "id"));
$congregacao->setNome(filter_input(INPUT_POST, "nome"));
$congregacao->setRua(filter_input(INPUT_POST, "rua"));
$congregacao->setBairro(filter_input(INPUT_POST, "bairro"));
$congregacao->setComplemento(filter_input(INPUT_POST, "complemento"));

$cidade->setId(filter_input(INPUT_POST, "idCidade"));
$congregacao->setCidade($cidade);

$congregacao->setFone(filter_input(INPUT_POST, "fone"));
$congregacao->setDataFundacao(filter_input(INPUT_POST, "dataFundacao"));

if (empty($congregacao->getId())) {
    if ($congregacaoDAO->cadastrar($congregacao)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
} else {
    if ($congregacaoDAO->editar($congregacao)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
}
?>