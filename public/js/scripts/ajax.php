<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CidadeDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/RelatorioUtil.php";

$tipo = filter_input(INPUT_POST, "tipo");
$subtipo = filter_input(INPUT_POST, "subtipo");

if ($tipo == "relatorio") {
    $conteudo = "";

    $idSelect = filter_input(INPUT_POST, "idSelect");

    switch ($idSelect) {
        case RelatorioUtil::RELATORIO_CONGREGACAO:
            $congregacaoDAO = new CongregacaoDAO();
            $conteudo .= "<label>Congregação:</label> &nbsp;";
            $conteudo .= $congregacaoDAO->getSelect();
            break;
        case RelatorioUtil::RELATORIO_OFERTA_VOLUNTARIA:
            $conteudo .= "<label>Mês/Ano:</label> &nbsp;";
            $conteudo .= DataUtil::getSelectMes();
            $conteudo .= "&nbsp;";
            $conteudo .= DataUtil::getSelectAno();
            break;
        default:
            $conteudo = "";
            break;
    }

    echo $conteudo;
} else if ($tipo == "select") { 
    $conteudo = "";

    $selectValor = filter_input(INPUT_POST, "selectValor"); 

    if (!empty($selectValor)) {
        if ($subtipo == "congregacao") {
            $conteudo .= "<label>Opções da congregação:</label> &nbsp;";
            $conteudo .= RelatorioUtil::getSelectOpcoesCongregacao();
        } else if ($subtipo == "opcaoCongregacao") {
                $conteudo .= "<label>Mês/Ano:</label> &nbsp;";
                $conteudo .= DataUtil::getSelectMes();
                $conteudo .= "&nbsp;";
                $conteudo .= DataUtil::getSelectAno();
                $conteudo .= "<br /> <br />";
        }
    } else {
        $conteudo = "";
    }

    echo $conteudo;
} else if ($tipo == "cidades") {
    $cidadeDAO = new CidadeDAO();
    echo $cidadeDAO->getJsonCidades(filter_input(INPUT_POST, "idEstado"));         
}