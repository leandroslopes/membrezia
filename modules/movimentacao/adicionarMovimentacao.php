<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MovimentacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Movimentacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/DespesaTipo.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Congregacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/CongregacaoCulto.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/NumeroUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";

$movimentacaoDAO = new MovimentacaoDAO();
$movimentacao = new Movimentacao();

$id = filter_input(INPUT_POST, "idMovimentacao");
$valor = filter_input(INPUT_POST, "valor");
$tipo = filter_input(INPUT_POST, "tipoMovimentacao");
$idDespesaTipo = filter_input(INPUT_POST, "idDespesaTipo");
$idCongregacao = filter_input(INPUT_POST, "idCongregacao");
$idCongregacaoCulto = filter_input(INPUT_POST, "idCongregacaoCulto");
$idContribuinte = filter_input(INPUT_POST, "idContribuinte");
$descricao = filter_input(INPUT_POST, "descricao");
$dataMovimentacao = filter_input(INPUT_POST, "dataMovimentacao");

$movimentacao->setId($id);
$movimentacao->setValor(NumeroUtil::formatar($valor, NumeroUtil::NUMERO_USA));
$movimentacao->setTipo($tipo);

$despesaTipo = new DespesaTipo();
$despesaTipo->setId($idDespesaTipo);
$movimentacao->setDespesaTipo($despesaTipo);

$congregacao = new Congregacao();
$congregacao->setId($idCongregacao);
$movimentacao->setCongregacao($congregacao);

$congregacaoCulto = new CongregacaoCulto();
$congregacaoCulto->setId($idCongregacaoCulto);
$movimentacao->setCongregacaoCulto($congregacaoCulto);

$contribuinte = new Membro();
$contribuinte->setId($idContribuinte);
$movimentacao->setContribuinte($contribuinte);

$movimentacao->setDescricao($descricao);
$movimentacao->setDataMovimentacao(DataUtil::formatar($dataMovimentacao, DataUtil::DATA_USA));
        
if (empty($movimentacao->getId())) {
    if ($movimentacaoDAO->adicionar($movimentacao)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
} else {
    if ($movimentacaoDAO->editar($movimentacao)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
}
?>