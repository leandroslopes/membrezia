<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/NumeroUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/MovimentacaoTipoUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Movimentacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MembroDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";

class MovimentacaoDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Lista as despesas referente ao mes corrente.
     * @return string
     */
    public function listarDespesas() {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";

        $statement = $this->connection->open()->prepare("SELECT a.id, b.id AS idDespesaTipo, b.nome AS despesa, a.valor, c.id AS idCongregacao, 
                                                    c.nome AS nomeCongregacao, DATE_FORMAT(a.data_movimentacao, '%d/%m/%Y') AS dtMovimentacao, 
                                                    d.nome AS nomeUsuarioCadastro,
                                                    DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dtCadastro                              
                                                    FROM movimentacao AS a, despesa_tipo AS b, congregacao AS c, membro AS d
                                                    WHERE a.id_despesa_tipo = b.id 
                                                    AND a.id_congregacao = c.id
                                                    AND a.id_usuario_cadastro = d.id
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = DATE_FORMAT(CURRENT_DATE, '%m')
                                                    ORDER BY a.data_movimentacao DESC");
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;
        
        $arrayCabecalho = array("DESPESA", "VALOR (R$)", "CONGREGAÇÃO", "DATA MOVIMENTAÇÃO", "USUÁRIO CADASTRO", "DATA CADASTRO", "EDITAR", 
                                "EXCLUIR");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($movimentacao = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . $movimentacao["despesa"] . "</td>";
            $conteudoHtml .= "<td>" . NumeroUtil::formatar($movimentacao["valor"], NumeroUtil::NUMERO_BRA) . "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["nomeCongregacao"] . "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["dtMovimentacao"]. "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["nomeUsuarioCadastro"] . "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["dtCadastro"] . "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<img src='../../public/images/icons/editar.png' title='Editar' alt='' class='editarDespesa tam16 cursor'/>";
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<input type='hidden' name='idMovimentacao' id='idMovimentacao' value='" . $movimentacao["id"] . "'/>";
            $conteudoHtml .= "<input type='hidden' name='idDespesaTipo' id='idDespesaTipo' value='" . $movimentacao["idDespesaTipo"] . "'/>";
            $conteudoHtml .= "<input type='hidden' name='idCongregacao' id='idCongregacao' value='" . $movimentacao["idCongregacao"] . "'/>";
            $conteudoHtml .= "<img src='../../public/images/icons/excluir.png' title='Excluir' alt='' class='excluirDespesa tam16 cursor'/>";
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "</tr>";
        }

        $conteudoHtmlRodape .= TableUtil::criarRodape(8);
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(8);

        if ($qtdRegistros > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        }
        return $conteudoHtmlTopo . $conteudoHtmlVazio;
    }

    /**
     * Lista as contribuicoes (dizimos ou ofertas) referente ao mes corrente de um contribuinte.
     * @param $tipoContribuicao
     * @return string
     */
    public function listarContribuicoes($idContribuinte, $tipoMovimentacao) {
        $this->connection = new ConnectionMySql();
        $congregacaoDAO = new CongregacaoDAO();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";

        $statement = $this->connection->open()->prepare("SELECT a.id, a.valor, a.id_congregacao, 
                                                    DATE_FORMAT(a.data_movimentacao, '%d/%m/%Y') AS dtMovimentacao, a.id_usuario_cadastro 
                                                    FROM movimentacao AS a 
                                                    WHERE a.id_contribuinte = ? 
                                                    AND a.tipo = ? 
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = DATE_FORMAT(CURRENT_DATE, '%m') 
                                                    ORDER BY a.data_movimentacao DESC");
        $statement->bind_param("is", $idContribuinte, $tipoMovimentacao);
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;

        $columnValue = "";
        $classEdit = "";
        $classDelete = "";
        if ($tipoMovimentacao === MovimentacaoTipoUtil::DIZIMO) {
            $columnValue = "DÍZIMO (R$)";
            $classEdit = "editarDizimo";
            $classDelete = "excluirDizimo";
        } else if ($tipoMovimentacao === MovimentacaoTipoUtil::OFERTA) {
            $columnValue = "OFERTA (R$)";
            $classEdit = "editarOferta";
            $classDelete = "excluirOferta";
        }

        $arrayCabecalho = array($columnValue, "CONGREGAÇÃO", "DATA MOVIMENTAÇÃO", "USUÁRIO CADASTRO", "EDITAR", "EXCLUIR");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";
        
        $contribuinteDAO = new MembroDAO();
        $usuarioCadastroDAO = new MembroDAO();
        while ($movimentacao = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . NumeroUtil::formatar($movimentacao["valor"], NumeroUtil::NUMERO_BRA) . "</td>";
            
            $idCongregacao = $movimentacao["id_congregacao"];
            if ($tipoMovimentacao === MovimentacaoTipoUtil::DIZIMO) {
                $idCongregacao = $contribuinteDAO->getMembro($idContribuinte)->getCongregacao()->getId();
            }
            $nomeCongregacao = $congregacaoDAO->getCongregacao($idCongregacao)->getNome();
            $conteudoHtml .= "<td>" . $nomeCongregacao . "</td>";
            
            $conteudoHtml .= "<td>" . $movimentacao["dtMovimentacao"]. "</td>";
            $conteudoHtml .= "<td>" . $usuarioCadastroDAO->getMembro($movimentacao["id_usuario_cadastro"])->getNome()  . "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<input type='hidden' name='idMovimentacao' id='idMovimentacao' value='" . $movimentacao["id"] . "'/>";
            $conteudoHtml .= "<input type='hidden' name='idCongregacao' id='idCongregacao' value='" . $movimentacao["id_congregacao"] . "'/>";
            $conteudoHtml .= "<img src='../../public/images/icons/editar.png' title='Editar' alt='' class='$classEdit tam16 cursor'/>";
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<img src='../../public/images/icons/excluir.png' title='Excluir' alt='' class='$classDelete tam16 cursor'/>";
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "</tr>";
        }

        $statement->close();
        $this->connection->close();

        $conteudoHtmlRodape .= TableUtil::criarRodape(6);
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(6);

        if ($qtdRegistros > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        }
        return $conteudoHtmlTopo . $conteudoHtmlVazio;
    }

    /**
     * Lista as ofertas referente ao mes corrente de uma congregacao.
     * @param int $idCongregacao
     * @return string
     */
    public function listarOfertas($idCongregacao) {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";
        $tipo = MovimentacaoTipoUtil::OFERTA;

        $statement = $this->connection->open()->prepare("SELECT a.id, d.nome AS nomeCulto, a.valor, a.id_congregacao_culto AS idCongregacaoCulto, 
                                                    DATE_FORMAT(a.data_movimentacao, '%d/%m/%Y') AS dtMovimentacao, e.nome AS nomeUsuarioCadastro
                                                    FROM movimentacao AS a, congregacao_culto AS b, congregacao AS c, culto AS d, membro AS e
                                                    WHERE a.tipo = ? 
                                                    AND a.id_congregacao_culto = b.id
                                                    AND b.id_congregacao = c.id
                                                    AND b.id_culto = d.id 
                                                    AND a.id_usuario_cadastro = e.id
                                                    AND c.id = ?
                                                    AND DATE_FORMAT(a.data_movimentacao, '%m') = DATE_FORMAT(CURRENT_DATE, '%m')
                                                    ORDER BY a.data_movimentacao DESC");
        $statement->bind_param("si", $tipo, $idCongregacao);
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;
        
        $arrayCabecalho = array("CULTO", "OFERTA (R$)", "DATA MOVIMENTAÇÃO", "USUÁRIO CADASTRO", "EDITAR", "EXCLUIR");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($movimentacao = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . $movimentacao["nomeCulto"] . "</td>";
            $conteudoHtml .= "<td>" . NumeroUtil::formatar($movimentacao["valor"], NumeroUtil::NUMERO_BRA) . "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["dtMovimentacao"]. "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["nomeUsuarioCadastro"]  . "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<input type='hidden' name='idCongregacaoCulto' id='idCongregacaoCulto' value='" . $movimentacao["idCongregacaoCulto"] . "'/>";
            $conteudoHtml .= "<img src='../../public/images/icons/editar.png' title='Editar' alt='' class='editarMovimentacao tam16 cursor'/>";
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<input type='hidden' name='idMovimentacao' id='idMovimentacao' value='" . $movimentacao["id"] . "'/>";
            $conteudoHtml .= "<img src='../../public/images/icons/excluir.png' title='Excluir' alt='' class='excluirMovimentacao tam16 cursor'/>";
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "</tr>";
        }

        $conteudoHtmlRodape .= TableUtil::criarRodape(6);
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(6);

        if ($qtdRegistros > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        }
        return $conteudoHtmlTopo . $conteudoHtmlVazio;
    }


    /**
     * Lista as receitas do mes corrente. Estas receitas sao o valor restante do mes anterior (podendo ser outro tipo de receita) do caixa da 
     * congregacao adicionadas para o mes seguinte. 
     * @return string
     */
    public function listarReceitas() {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";
        $tipo = MovimentacaoTipoUtil::RECEITA;

        $statement = $this->connection->open()->prepare("SELECT a.id, a.valor, a.descricao, a.id_congregacao, b.nome AS nomeCongregacao, 
                                                    DATE_FORMAT(a.data_movimentacao, '%d/%m/%Y') AS dtMovimentacao, 
                                                    c.nome AS nomeUsuarioCadastro,
                                                    DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dtCadastro                              
                                                    FROM movimentacao AS a, congregacao AS b, membro AS c
                                                    WHERE a.tipo = ? 
                                                    AND a.id_congregacao = b.id
                                                    AND a.id_usuario_cadastro = c.id
                                                    AND DATE_FORMAT(a.data_movimentacao, '%Y') = DATE_FORMAT(CURRENT_DATE, '%Y')
                                                    ORDER BY a.data_movimentacao DESC");
        $statement->bind_param("s", $tipo);
        $statement->execute();
        $result = $statement->get_result();
        $numRows = $result->num_rows;
        
        $arrayCabecalho = array("RECEITA (R$)", "DESCRIÇÃO", "CONGREGAÇÃO", "DATA MOVIMENTAÇÃO", "USUÁRIO CADASTRO", "DATA CADASTRO", "EDITAR", 
                                "EXCLUIR");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($movimentacao = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . NumeroUtil::formatar($movimentacao["valor"], NumeroUtil::NUMERO_BRA) . "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["descricao"] . "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["nomeCongregacao"] . "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["dtMovimentacao"]. "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["nomeUsuarioCadastro"] . "</td>";
            $conteudoHtml .= "<td>" . $movimentacao["dtCadastro"] . "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<img src='../../public/images/icons/editar.png' title='Editar' alt='' class='editarReceita tam16 cursor'/>";
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<input type='hidden' name='idMovimentacao' id='idMovimentacao' value='" . $movimentacao["id"] . "'/>";
            $conteudoHtml .= "<input type='hidden' name='idCongregacao' id='idCongregacao' value='" . $movimentacao["id_congregacao"] . "'/>";
            $conteudoHtml .= "<img src='../../public/images/icons/excluir.png' title='Excluir' alt='' class='excluirReceita tam16 cursor'/>";
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "</tr>";
        }

        $conteudoHtmlRodape .= TableUtil::criarRodape(8);
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(8);

        if ($numRows > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        }
        return $conteudoHtmlTopo . $conteudoHtmlVazio;
    }

    /**
     * Adiciona movimentacao.
     * @param Movimentacao $movimentacao
     * @return boolean
     */
    public function adicionar($movimentacao) {
        $this->connection = new ConnectionMySql();
        $valor = $movimentacao->getValor();
        $tipo = $movimentacao->getTipo();
        $idDespesaTipo = $movimentacao->getDespesaTipo() !== NULL ? $movimentacao->getDespesaTipo()->getId() : NULL;
        $idCongregacao =  $movimentacao->getCongregacao() !== NULL ? $movimentacao->getCongregacao()->getId() : NULL;
        $idCongregacaoCulto = $movimentacao->getCongregacaoCulto() !== NULL ? $movimentacao->getCongregacaoCulto()->getId() : NULL;
        $idContribuinte = $movimentacao->getContribuinte() !== NULL ? $movimentacao->getContribuinte()->getId() : NULL;
        $descricao = $movimentacao->getDescricao() !== "" ? $movimentacao->getDescricao() : NULL;
        $dataMovimentacao = $movimentacao->getDataMovimentacao();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("INSERT INTO movimentacao (valor, tipo, id_despesa_tipo, id_congregacao, 
                                                    id_congregacao_culto, id_contribuinte, descricao, data_movimentacao, id_usuario_cadastro) 
                                                    VALUES (?,?,?,?,?,?,?,?,?)");
        $statement->bind_param("dsiiiissi", $valor, $tipo, $idDespesaTipo, $idCongregacao, $idCongregacaoCulto, $idContribuinte, 
                                $descricao, $dataMovimentacao, $idUsuario);
        $adicionou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($adicionou) {
            return true;
        }
        return false;
    }

    /**
     * Edita movimentacao.
     * @param Movimentacao $movimentacao
     * @return boolean
     */
    public function editar($movimentacao) {
        $this->connection = new ConnectionMySql();
        $id = $movimentacao->getId();
        $valor = $movimentacao->getValor();
        $tipo = $movimentacao->getTipo();
        $idDespesaTipo = $movimentacao->getDespesaTipo() !== NULL ? $movimentacao->getDespesaTipo()->getId() : NULL;
        $idCongregacao =  $movimentacao->getCongregacao() !== NULL ? $movimentacao->getCongregacao()->getId() : NULL; 
        $idCongregacaoCulto = $movimentacao->getCongregacaoCulto() !== NULL ? $movimentacao->getCongregacaoCulto()->getId() : NULL;
        $idContribuinte = $movimentacao->getContribuinte() !== NULL ? $movimentacao->getContribuinte()->getId() : NULL;
        $descricao = $movimentacao->getDescricao() !== "" ? $movimentacao->getDescricao() : NULL;
        $dataMovimentacao = $movimentacao->getDataMovimentacao();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("UPDATE movimentacao AS a 
                                                    SET a.valor = ?, a.tipo = ?, a.id_despesa_tipo = ?, a.id_congregacao = ?, 
                                                    a.id_congregacao_culto = ?, a.id_contribuinte = ?, a.descricao = ?, a.data_movimentacao = ?, 
                                                    a.id_usuario_cadastro = ?, a.data_cadastro = CURRENT_TIMESTAMP 
                                                    WHERE a.id = ?");
        $statement->bind_param("dsiiiissii", $valor, $tipo, $idDespesaTipo, $idCongregacao, $idCongregacaoCulto, $idContribuinte, 
                                $descricao, $dataMovimentacao, $idUsuario, $id);
        $editou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($editou) {
            return true;
        }
        return false;
    }

    /**
     * Exclui movimentacao (despesa, dizimo e oferta).
     * @param int $id
     * @return boolean
     */
    public function excluir($id) {
        $this->connection = new ConnectionMySql();

        $statement = $this->connection->open()->prepare("DELETE FROM movimentacao WHERE id = ?");
        $statement->bind_param("i", $id);
        $excluiu = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($excluiu) {
            return true;
        }
        return false;
    }
}