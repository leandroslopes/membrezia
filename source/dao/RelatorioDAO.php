<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/NumeroUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/RelatorioUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/TableUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/MovimentacaoTipoUtil.php";

class RelatorioDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Chama o relatorio a ser impresso.
     * @param array $requisao
     * @return string
     */
    public function imprimir($requisao) {
        $selectTipoRelatorio = $requisao["selectTipoRelatorio"];
        switch ($selectTipoRelatorio) {
            case RelatorioUtil::RELATORIO_MEMBRO:
                return $this->imprimirMembro($requisao);
            case RelatorioUtil::RELATORIO_CONGREGACAO:
                return $this->imprimirCongregacao($requisao);
            default:
                break;
        }
    }

    /**
     * Gera o relatorio de membros ordenados por congregacao.
     * @param array $requisao
     * @return string
     */
    private function imprimirMembro($arrayRelatorio) {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $situacao = Membro::LIGADO;

        $statement = $this->connection->open()->prepare("SELECT a.nome, a.fone, a.rua, a.bairro, a.complemento, d.nome AS nomeCidade, c.sigla, 
                                                    b.nome AS nomeCongregacao 
                                                    FROM membro AS a, congregacao AS b, estado AS c, cidade AS d
                                                    WHERE a.id_congregacao = b.id 
                                                    AND a.id_cidade = d.id
                                                    AND d.id_estado = c.id
                                                    ORDER BY b.nome, a.nome");
        $statement->execute();
        $result = $statement->get_result();
        $numRows = $result->num_rows;

        $arrayTitulo = array("MEMBROS");
        $conteudoHtmlTopo .= TableUtil::criarTopoRelatorio($arrayTitulo);

        $arrayCabecalho = array("MEMBRO", "FONE", "ENDERECO", "CONGREGAÇÃO");
        $conteudoHtmlTopo .= TableUtil::criarConteudoRelatorio($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($membro = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . $membro["nome"] . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>" . $membro["fone"] . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>";
            $conteudoHtml .= $membro["rua"];
            $conteudoHtml .= $membro["complemento"] == "" ? ", " . $membro["complemento"] : ""; 
            $conteudoHtml .= ", " . $membro["bairro"] . ", " . $membro["nomeCidade"] . "/" . $membro["sigla"];  
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "<td class='textoDireito'>" . $membro["nomeCongregacao"] . "</td>";
            $conteudoHtml .= "</tr>";
        }

        $conteudoHtml .= "</tbody></table>";
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(4);

        $statement->close();
        $this->connection->close();

        if ($numRows > 0) {
            return $conteudoHtmlTopo . $conteudoHtml;
        }
        return $conteudoHtmlTopo . $conteudoHtmlVazio;
    }

    /**
     * Gera relatorio de uma congregacao.
     * @param array $arrayRelatorio
     * @return string
     */
    public function imprimirCongregacao($arrayRelatorio) { 
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $nomeCongregacao = "---";
        $mesAno = "";
        $opcaoCongregacao = "";

        if (!empty($arrayRelatorio["idCongregacao"])) {
            $congregacaoDAO = new CongregacaoDAO();
            $congregacao = $congregacaoDAO->getCongregacao($arrayRelatorio["idCongregacao"]);
            $nomeCongregacao = $congregacao->getNome();
            $opcaoCongregacao = RelatorioUtil::getOpcaoCongregacao($arrayRelatorio["selectOpcoesCongregacao"]);
        }

        if (!empty($arrayRelatorio["mes"])) {
            $mesAno = DataUtil::getNomeMes($arrayRelatorio["mes"]) . "/" . $arrayRelatorio["ano"];
        }

        $arrayTitulo = array("CONGREGAÇÃO: " . $nomeCongregacao, "&nbsp;", $mesAno);
        $conteudoHtmlTopo .= TableUtil::criarTopoRelatorio($arrayTitulo);

        if (isset($arrayRelatorio["selectOpcoesCongregacao"])) {

            switch ($arrayRelatorio["selectOpcoesCongregacao"]) {
                case RelatorioUtil::OPCAO_OFERTAS_VOLUNTARIAS:
                    $conteudoHtml .= $this->imprimirOfertasVoluntarias($arrayRelatorio);
                    break;
                case RelatorioUtil::OPCAO_OFERTAS:
                    $conteudoHtml .= $this->imprimirOfertas($arrayRelatorio);
                    break;
                case RelatorioUtil::OPCAO_DIZIMOS:
                    $conteudoHtml .= $this->imprimirDizimos($arrayRelatorio);
                    break;
                case RelatorioUtil::OPCAO_DESPESAS:
                    $conteudoHtml .= $this->imprimirDespesas($arrayRelatorio);
                    break;
                case RelatorioUtil::OPCAO_RECEITAS:
                    $conteudoHtml .= $this->imprimirReceitas($arrayRelatorio);
                    break;
                case RelatorioUtil::OPCAO_TODAS_AS_OPCOES:
                    $conteudoHtml .= $this->imprimirOfertasVoluntarias($arrayRelatorio);
                    $conteudoHtml .= $this->imprimirOfertas($arrayRelatorio);
                    $conteudoHtml .= $this->imprimirDizimos($arrayRelatorio);
                    $conteudoHtml .= $this->imprimirReceitas($arrayRelatorio);
                    $conteudoHtml .= $this->imprimirDespesas($arrayRelatorio);
                    $conteudoHtml .= $this->imprimirValoresTotais($arrayRelatorio);
                    break;
                default:
                    $conteudoHtml .= "";
                    break;
            }
        }

        return $conteudoHtmlTopo . $conteudoHtml;
    }

    /**
     * Imprimi as ofertas de uma congregacao.
     * @param $arrayRelatorio
     * @return string
     */
    private function imprimirOfertas($arrayRelatorio) {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $totalOfertas = 0;
        $mes = $arrayRelatorio["mes"];
        $ano = $arrayRelatorio["ano"];
        $tipo = MovimentacaoTipoUtil::OFERTA;
        $idCongregacao = $arrayRelatorio["idCongregacao"];

        $statement = $this->connection->open()->prepare("SELECT a.valor, d.nome AS nomeCulto, 
                                                    DATE_FORMAT(a.data_movimentacao, '%d/%m/%Y') AS dtMovimentacao 
                                                    FROM movimentacao AS a, congregacao_culto AS b, congregacao AS c, culto AS d 
                                                    WHERE a.tipo = ? 
                                                    AND a.id_congregacao_culto = b.id 
                                                    AND b.id_congregacao = c.id 
                                                    AND b.id_culto = d.id 
                                                    AND c.id = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = ? 
                                                    ORDER BY d.nome, a.data_movimentacao");
        $statement->bind_param("siss", $tipo, $idCongregacao, $mes, $ano);
        $statement->execute();
        $result = $statement->get_result();
        $numRows = $result->num_rows;

        $arrayTopo = array("OFERTAS");
        $conteudoHtmlTopo .= TableUtil::criarTopoRelatorio($arrayTopo);

        $arrayCabecalho = array("OFERTA (R$)", "CULTO", "DATA");
        $conteudoHtmlTopo .= TableUtil::criarConteudoRelatorio($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($oferta = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td class='textoCentro'>" . NumeroUtil::formatar($oferta["valor"], NumeroUtil::NUMERO_BRA) . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>" . $oferta["nomeCulto"] . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>" . $oferta["dtMovimentacao"] . "</td>";
            $conteudoHtml .= "</tr>";

            $totalOfertas += $oferta["valor"];
        }

        $conteudoHtml .= "<tr><td colspan='3'>&nbsp;</td></tr>";
        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoCentro'>" . NumeroUtil::formatar($totalOfertas, NumeroUtil::NUMERO_BRA) . "</td>";
        $conteudoHtml .= "<td class='textoCentro' colspan='2'>TOTAL (R$)</td>";
        $conteudoHtml .= "</tr> </tbody> </table>";

        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(3);

        if ($numRows > 0) {
            return $conteudoHtmlTopo . $conteudoHtml;
        } else {
            return $conteudoHtmlTopo . $conteudoHtmlVazio;
        }
    }

    /**
     * Imprimi relatorio de dizimos de uma congregacao.
     * @param $arrayRelatorio
     * @return string
     */
    private function imprimirDizimos($arrayRelatorio) {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $totalDizimos = 0;
        $mes = $arrayRelatorio["mes"];
        $ano = $arrayRelatorio["ano"];
        $tipo = MovimentacaoTipoUtil::DIZIMO;
        $idCongregacao = $arrayRelatorio["idCongregacao"];

        $statement = $this->connection->open()->prepare("SELECT b.nome AS nomeDizimista, a.valor, 
                                                    DATE_FORMAT(a.data_movimentacao, '%d/%m/%Y') AS dtMovimentacao 
                                                    FROM movimentacao AS a, membro AS b, congregacao AS c
                                                    WHERE a.tipo = ? 
                                                    AND a.id_contribuinte = b.id 
                                                    AND b.id_congregacao = c.id
                                                    AND c.id = ?
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = $mes
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = $ano    
                                                    ORDER BY a.data_movimentacao ASC, b.nome ASC");
        $statement->bind_param("si", $tipo, $idCongregacao);
        $statement->execute();
        $result = $statement->get_result();
        $numRows = $result->num_rows;

        $arrayTopo = array("DÍZIMOS");
        $conteudoHtmlTopo .= TableUtil::criarTopoRelatorio($arrayTopo);

        $arrayCabecalho = array("DIZIMISTA", "DIZIMO (R$)", "DATA");
        $conteudoHtmlTopo .= TableUtil::criarConteudoRelatorio($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($dizimo = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . $dizimo["nomeDizimista"] . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>" . NumeroUtil::formatar($dizimo["valor"], NumeroUtil::NUMERO_BRA) . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>" . $dizimo["dtMovimentacao"] . "</td>";
            $conteudoHtml .= "</tr>";

            $totalDizimos += $dizimo["valor"];
        }

        $conteudoHtml .= "<tr><td colspan='3'>&nbsp;</td></tr>";
        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoCentro'>TOTAL (R$)</td>";
        $conteudoHtml .= "<td class='textoCentro'>" . NumeroUtil::formatar($totalDizimos, NumeroUtil::NUMERO_BRA) . "</td>";
        $conteudoHtml .= "<td>&nbsp;</td>";
        $conteudoHtml .= "</tr> </tbody> </table>";

        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(3);

        if ($numRows > 0) {
            return $conteudoHtmlTopo . $conteudoHtml;
        }
        return $conteudoHtmlTopo . $conteudoHtmlVazio;
    }

    /**
     * Imprimi as despesa de uma congregacao.
     * @param array $arrayRelatorio
     * @return string
     */
    private function imprimirDespesas($arrayRelatorio) {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $totalDespesas = 0;
        $mes = $arrayRelatorio["mes"];
        $ano = $arrayRelatorio["ano"];
        $tipo = MovimentacaoTipoUtil::DESPESA;
        $idCongregacao = $arrayRelatorio["idCongregacao"];

        $statement = $this->connection->open()->prepare("SELECT b.nome AS nomeDespesa, a.valor, 
                                                    DATE_FORMAT(a.data_movimentacao, '%d/%m/%Y') AS dtMovimentacao
                                                    FROM movimentacao AS a, despesa_tipo AS b
                                                    WHERE a.tipo = ?
                                                    AND a.id_despesa_tipo = b.id 
                                                    AND a.id_congregacao = ?
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = $mes
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = $ano    
                                                    ORDER BY a.data_movimentacao ASC, b.nome ASC");
        $statement->bind_param("si", $tipo, $idCongregacao);
        $statement->execute();
        $result = $statement->get_result();
        $numRows = $result->num_rows;

        $arrayTopo = array("DESPESAS");
        $conteudoHtmlTopo .= TableUtil::criarTopoRelatorio($arrayTopo);

        $arrayCabecalho = array("DESPESA", "VALOR (R$)", "DATA");
        $conteudoHtmlTopo .= TableUtil::criarConteudoRelatorio($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($despesa = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . $despesa["nomeDespesa"] . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>" . NumeroUtil::formatar($despesa["valor"], NumeroUtil::NUMERO_BRA) . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>" . $despesa["dtMovimentacao"] . "</td>";
            $conteudoHtml .= "</tr>";

            $totalDespesas += $despesa["valor"];
        }

        $conteudoHtml .= "<tr><td colspan='3'>&nbsp;</td></tr>";
        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='negrito textoCentro'>TOTAL (R$)</td>";
        $conteudoHtml .= "<td class='textoCentro'>" . NumeroUtil::formatar($totalDespesas, NumeroUtil::NUMERO_BRA) . "</td>";
        $conteudoHtml .= "<td></td>";
        $conteudoHtml .= "</tr> </tbody> </table>";

        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(3);

        if ($numRows > 0) {
            return $conteudoHtmlTopo . $conteudoHtml;
        }
        return $conteudoHtmlTopo . $conteudoHtmlVazio;
    }

    /**
     * Imprimi as receitas. As receitas sao o restante do mes anterior cadastradas para o mes seguinte. 
     * @param $arrayRelatorio
     * @return string
     */
    private function imprimirReceitas($arrayRelatorio) {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $totalReceitas = 0;
        $mes = $arrayRelatorio["mes"];
        $ano = $arrayRelatorio["ano"];
        $tipo = MovimentacaoTipoUtil::RECEITA;
        $idCongregacao = $arrayRelatorio["idCongregacao"];

        $statement = $this->connection->open()->prepare("SELECT a.valor, a.descricao, DATE_FORMAT(a.data_movimentacao, '%d/%m/%Y') AS dtMovimentacao 
                                                    FROM movimentacao AS a, congregacao AS b 
                                                    WHERE a.tipo = ? 
                                                    AND a.id_congregacao = b.id 
                                                    AND b.id = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = ? 
                                                    ORDER BY a.data_movimentacao");
        $statement->bind_param("siss", $tipo, $idCongregacao, $mes, $ano);
        $statement->execute();
        $result = $statement->get_result();
        $numRows = $result->num_rows;

        $arrayTopo = array("RECEITAS");
        $conteudoHtmlTopo .= TableUtil::criarTopoRelatorio($arrayTopo);

        $arrayCabecalho = array("RECEITA (R$)", "DESCRIÇÃO", "DATA");
        $conteudoHtmlTopo .= TableUtil::criarConteudoRelatorio($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($receita = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td class='textoCentro'>" . NumeroUtil::formatar($receita["valor"], NumeroUtil::NUMERO_BRA) . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>{$receita["receita"]}</td>";
            $conteudoHtml .= "<td class='textoCentro'>{$receita["dtMovimentacao"]}</td>";
            $conteudoHtml .= "</tr>";

            $totalReceitas += $receita["valor"];
        }

        $conteudoHtml .= "<tr><td colspan='3'>&nbsp;</td></tr>";
        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoCentro'>" . NumeroUtil::formatar($totalReceitas, NumeroUtil::NUMERO_BRA) . "</td>";
        $conteudoHtml .= "<td class='textoCentro' colspan='3'>TOTAL (R$)</td>";
        $conteudoHtml .= "</tr> </tbody> </table>";

        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(3);

        if ($numRows > 0) {
            return $conteudoHtmlTopo . $conteudoHtml;
        } else {
            return $conteudoHtmlTopo . $conteudoHtmlVazio;
        }
    }

    /**
     * Imprimi os totais da receita.
     * @param array $arrayRelatorio
     * @return string
     */
    private function imprimirValoresTotais($arrayRelatorio) {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $idCongregacao = $arrayRelatorio["idCongregacao"];
        $mes = $arrayRelatorio["mes"];
        $ano = $arrayRelatorio["ano"];
        $oferta = MovimentacaoTipoUtil::OFERTA;
        $dizimo = MovimentacaoTipoUtil::DIZIMO;
        $despesa = MovimentacaoTipoUtil::DESPESA;
        $receita = MovimentacaoTipoUtil::RECEITA;

        $statement = $this->connection->open()->prepare("SELECT SUM(a.valor) AS total 
                                                    FROM movimentacao AS a, membro AS b 
                                                    WHERE a.tipo = ?
                                                    AND a.id_contribuinte = b.id
                                                    AND a.id_congregacao = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = ? 
                                                    ORDER BY a.data_movimentacao ASC, b.nome ASC");
        $statement->bind_param("siss", $oferta, $idCongregacao, $mes, $ano);
        $statement->execute();
        $result = $statement->get_result();
        $arrayOfertaVoluntaria = $result->fetch_assoc();
        $totalOfertaVoluntaria = (empty($arrayOfertaVoluntaria["total"]) ? "0.00" : $arrayOfertaVoluntaria["total"]);
        $totalOfertaVoluntariaFormatado = NumeroUtil::formatar($totalOfertaVoluntaria, NumeroUtil::NUMERO_BRA);

        $statement = $this->connection->open()->prepare("SELECT SUM(a.valor) AS total
                                                    FROM movimentacao AS a, congregacao_culto AS b, congregacao AS c
                                                    WHERE a.tipo = ?  
                                                    AND a.id_congregacao_culto = b.id
                                                    AND b.id_congregacao = c.id
                                                    AND c.id = ?
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = ?
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = ?");
        $statement->bind_param("siss", $oferta, $idCongregacao, $mes, $ano);
        $statement->execute();
        $result = $statement->get_result();
        $arrayOferta = $result->fetch_assoc();
        $totalOferta = (empty($arrayOferta["total"]) ? "0.00" : $arrayOferta["total"]);
        $totalOfertaFormatado = NumeroUtil::formatar($totalOferta, NumeroUtil::NUMERO_BRA);

        $statement = $this->connection->open()->prepare("SELECT SUM(a.valor) AS total 
                                                    FROM movimentacao AS a, membro AS b, congregacao AS c
                                                    WHERE a.tipo = ? 
                                                    AND a.id_contribuinte = b.id 
                                                    AND b.id_congregacao = c.id
                                                    AND c.id = ?
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = ?
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = ?");
        $statement->bind_param("siss", $dizimo, $idCongregacao, $mes, $ano);
        $statement->execute();
        $result = $statement->get_result();
        $arrayDizimo = $result->fetch_assoc();
        $totalDizimo = (empty($arrayDizimo["total"]) ? "0.00" : $arrayDizimo["total"]);
        $totalDizimoFormatado = NumeroUtil::formatar($totalDizimo, NumeroUtil::NUMERO_BRA);

        $statement = $this->connection->open()->prepare("SELECT SUM(a.valor) AS total
                                                    FROM movimentacao AS a, despesa_tipo AS b
                                                    WHERE a.tipo = ?
                                                    AND a.id_despesa_tipo = b.id 
                                                    AND a.id_congregacao = ?
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = ?
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = ?");
        $statement->bind_param("siss", $despesa, $idCongregacao, $mes, $ano);
        $statement->execute();
        $result = $statement->get_result();
        $arrayDespesa = $result->fetch_assoc();
        $totalDespesa = (empty($arrayDespesa["total"]) ? "0.00" : $arrayDespesa["total"]);
        $totalDespesaFormatado = NumeroUtil::formatar($totalDespesa, NumeroUtil::NUMERO_BRA);

        $statement = $this->connection->open()->prepare("SELECT SUM(a.valor) AS total   
                                                    FROM movimentacao AS a, congregacao AS b 
                                                    WHERE a.tipo = ? 
                                                    AND a.id_congregacao = b.id 
                                                    AND b.id = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = ? 
                                                    ORDER BY a.data_movimentacao");
        $statement->bind_param("siss", $receita, $idCongregacao, $mes, $ano);
        $statement->execute();
        $result = $statement->get_result();
        $arrayReceita = $result->fetch_assoc(); 
        $totalReceita = (empty($arrayReceita["total"]) ? "0.00" : $arrayReceita["total"]);
        $totalReceitaFormatado = NumeroUtil::formatar($totalReceita, NumeroUtil::NUMERO_BRA);

        $subtotal01 = $totalOfertaVoluntaria + $totalOferta + $totalDizimo + $totalReceita;
        $subtotal01Formatado = NumeroUtil::formatar((empty($subtotal01) ? "0.00" : $subtotal01), NumeroUtil::NUMERO_BRA);

        $totalRestante = $subtotal01 - $totalDespesa;
        $totalRestanteFormatado = NumeroUtil::formatar((empty($totalRestante) ? "0.00" : $totalRestante), NumeroUtil::NUMERO_BRA);

        $conteudoHtml .= "<br />";
        $conteudoHtml .= "<table id='tbl_imprimir_conteudo'>";
        $conteudoHtml .= "<tbody>";
        
        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoDireito negrito'>TOTAL DE OFERTAS VOLUNTÁRIAS (R$)</td>";
        $conteudoHtml .= "<td>&nbsp;$totalOfertaVoluntariaFormatado</td>";
        $conteudoHtml .= "</tr>";

        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoDireito negrito'>TOTAL DE OFERTAS (R$)</td>";
        $conteudoHtml .= "<td>&nbsp;$totalOfertaFormatado</td>";
        $conteudoHtml .= "</tr>";
        
        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoDireito negrito'>TOTAL DE DIZIMOS (R$)</td>";
        $conteudoHtml .= "<td>&nbsp;$totalDizimoFormatado</td>";
        $conteudoHtml .= "</tr>";
        
        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoDireito negrito'>TOTAL DE RECEITAS (R$)</td>";
        $conteudoHtml .= "<td>&nbsp;$totalReceitaFormatado</td>";
        $conteudoHtml .= "</tr>";

        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoDireito negrito'>SUBTOTAL (R$)</td>";
        $conteudoHtml .= "<td>&nbsp;$subtotal01Formatado</td>";
        $conteudoHtml .= "</tr>";

        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoDireito negrito'>TOTAL DE DESPESAS (R$)</td>";
        $conteudoHtml .= "<td>&nbsp;$totalDespesaFormatado</td>";
        $conteudoHtml .= "</tr>";

        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoDireito negrito'>TOTAL RESTANTE (R$)</td>";
        $conteudoHtml .= "<td>&nbsp;$totalRestanteFormatado</td>";
        $conteudoHtml .= "</tr>";
        $conteudoHtml .= "</tbody>";
        $conteudoHtml .= "</table>";

        return $conteudoHtml;
    }

    /**
     * Imprimi as ofertas voluntarias. Ofertas fora do culto feita por contribuintes individuais.
     * @param array $arrayRelatorio
     * @return string
     */
    public function imprimirOfertasVoluntarias($arrayRelatorio) {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $totalOfertas = 0;
        $mes = $arrayRelatorio["mes"];
        $ano = $arrayRelatorio["ano"];
        $tipo = MovimentacaoTipoUtil::OFERTA;
        $idCongregacao = $arrayRelatorio["idCongregacao"];

        $statement = $this->connection->open()->prepare("SELECT a.valor, b.nome AS nomeContribuinte, 
                                                    DATE_FORMAT(a.data_movimentacao, '%d/%m/%Y') AS dtMovimentacao 
                                                    FROM movimentacao AS a, membro AS b 
                                                    WHERE a.tipo = ?
                                                    AND a.id_contribuinte = b.id
                                                    AND a.id_congregacao = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = ? 
                                                    ORDER BY a.data_movimentacao ASC, b.nome ASC");
        $statement->bind_param("siss", $tipo, $idCongregacao, $mes, $ano);
        $statement->execute();
        $result = $statement->get_result();
        $numRows = $result->num_rows;

        $arrayTopo = array("OFERTAS VOLUNTÁRIAS");
        $conteudoHtmlTopo .= TableUtil::criarTopoRelatorio($arrayTopo);

        $arrayCabecalho = array("OFERTA (R$)", "CONTRIBUINTE", "DATA");
        $conteudoHtmlTopo .= TableUtil::criarConteudoRelatorio($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($oferta = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td class='textoCentro'>" . NumeroUtil::formatar($oferta["valor"], NumeroUtil::NUMERO_BRA) . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>" . $oferta["nomeContribuinte"] . "</td>";
            $conteudoHtml .= "<td class='textoCentro'>" . $oferta["dtMovimentacao"] . "</td>";
            $conteudoHtml .= "</tr>";

            $totalOfertas += $oferta["valor"];
        }

        $conteudoHtml .= "<tr><td colspan='3'>&nbsp;</td></tr>";
        $conteudoHtml .= "<tr>";
        $conteudoHtml .= "<td class='textoCentro'>" . NumeroUtil::formatar($totalOfertas, NumeroUtil::NUMERO_BRA) . "</td>";
        $conteudoHtml .= "<td class='textoCentro' colspan='2'>TOTAL (R$)</td>";
        $conteudoHtml .= "</tr> </tbody> </table>";

        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(3);

        if ($numRows > 0) {
            return $conteudoHtmlTopo . $conteudoHtml;
        } else {
            return $conteudoHtmlTopo . $conteudoHtmlVazio;
        }
    }
}