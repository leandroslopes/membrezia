<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MovimentacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Congregacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Movimentacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/NumeroUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/MovimentacaoTipoUtil.php";

$movimentacaoDAO = new MovimentacaoDAO();
$ofertante = new Membro();
$congregacao = new Congregacao();
$oferta = new Movimentacao();

$oferta->setId(filter_input(INPUT_POST, "id"));
$oferta->setTipo(MovimentacaoTipoUtil::OFERTA);
$oferta->setValor(NumeroUtil::formatar(filter_input(INPUT_POST, "valor"), NumeroUtil::NUMERO_USA));

$ofertante->setId(filter_input(INPUT_POST, "idMembroAdicionarOferta"));
$oferta->setContribuinte($ofertante);

$congregacao->setId(filter_input(INPUT_POST, "idCongregacao"));
$oferta->setCongregacao($congregacao);

$oferta->setDataMovimentacao(DataUtil::formatar(filter_input(INPUT_POST, "dataMovimentacao"), DataUtil::DATA_USA));

if (empty($oferta->getId())) {
    if ($movimentacaoDAO->adicionar($oferta)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
} else {
    if ($movimentacaoDAO->editar($oferta)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
}
?>