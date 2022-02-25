<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MovimentacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Movimentacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/NumeroUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/MovimentacaoTipoUtil.php";

$movimentacaoDAO = new MovimentacaoDAO();
$dizimista = new Membro();
$dizimo = new Movimentacao();

$dizimo->setId(!empty(filter_input(INPUT_POST, "id")) ? filter_input(INPUT_POST, "id") : null);
$dizimo->setTipo(MovimentacaoTipoUtil::DIZIMO);
$dizimo->setValor(NumeroUtil::formatar(filter_input(INPUT_POST, "valor"), NumeroUtil::NUMERO_USA));

$dizimista->setId(filter_input(INPUT_POST, "idMembro"));
$dizimo->setContribuinte($dizimista); 

$dizimo->setDataMovimentacao(DataUtil::formatar(filter_input(INPUT_POST, "dataMovimentacao"), DataUtil::DATA_USA));

if (empty($dizimo->getId())) {
    if ($movimentacaoDAO->adicionar($dizimo)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
} else {
    if ($movimentacaoDAO->editar($dizimo)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
}
?>