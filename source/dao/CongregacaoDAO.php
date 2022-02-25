<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/NumeroUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/TableUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Congregacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Estado.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Cidade.php";

class CongregacaoDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Retorna um 'select' com as congregacoes.
     * @param int $idCongregacao
     * @return string
     */
    public function getSelect($idCongregacao = 0) {
        $this->connection = new ConnectionMySql();
        $conteudo = "";
        $selecione = "";

        $statement = $this->connection->open()->prepare("SELECT * FROM congregacao AS a ORDER BY a.nome");
        $statement->execute();
        $result = $statement->get_result();

        $conteudo .= "<select name='idCongregacao' id='idCongregacao'>";
        $idCongregacao === 0 ? $selecione = "selected" : $selecione = "";
        $conteudo .= "<option value='' $selecione>SELECIONE A CONGREGAÇÃO</option>";
        while ($congregacao = $result->fetch_assoc()) {
            if ($congregacao["id"] == $idCongregacao) {
                $conteudo .= "<option value='" . $congregacao["id"] . "' selected>" . $congregacao["nome"] . "</option>";
            } else {
                $conteudo .= "<option value='" . $congregacao["id"] . "'>" . $congregacao["nome"] . "</option>";
            }
        }
        $conteudo .= "</select>";

        $statement->close();

        return $conteudo;
    }

    /**
     * Lista as congregacoes.
     * @param int $idModulo
     * @return string
     */
    public function listarCongregacoes($idModulo) {
        $this->connection = new ConnectionMySql();
        $idCongregacaoPadrao = 0;
        $conteudoHtmlTopo = "";
        $conteudoHtml = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";

        
        $statement = $this->connection->open()->prepare("SELECT a.id AS idCongregacao, a.nome, a.rua, a.bairro, a.complemento, d.id_estado, 
                                                    a.id_cidade, c.sigla AS siglaEstado, d.nome AS nomeCidade, 
                                                    a.fone, a.data_fundacao, b.nome AS nomeUsuarioCadastro, 
                                                    DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dtCadastro
                                                    FROM congregacao AS a, membro AS b, estado AS c, cidade AS d 
                                                    WHERE a.id_usuario_cadastro = b.id
                                                    AND a.id_cidade = d.id
                                                    AND d.id_estado = c.id
                                                    ORDER BY a.is_padrao DESC");
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;

        $arrayCabecalho = array("CONGREGAÇÃO", "ENDEREÇO", "FONE", "FUNDAÇÃO", "USUÁRIO CADASTRO", "DATA CADASTRO", "EDITAR", "PADRÃO");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($congregacao = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<a class='cursor' href='congregacao.php?id=$idModulo&idCongregacao=" . $congregacao["idCongregacao"] . "'>";
            $conteudoHtml .= $congregacao["nome"];
            $conteudoHtml .= "</a>";
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "<td>" . $congregacao["rua"] . ', ' . $congregacao["bairro"] . ', '; 
            $conteudoHtml .= $congregacao["complemento"] . ', ' . $congregacao["nomeCidade"] . '/' . $congregacao["siglaEstado"] . "</td>";
            $conteudoHtml .= "<td>{$congregacao["fone"]}</td>";
            $conteudoHtml .= "<td>{$congregacao["data_fundacao"]}</td>";
            $conteudoHtml .= "<td>{$congregacao["nomeUsuarioCadastro"]}</td>";
            $conteudoHtml .= "<td>{$congregacao["dtCadastro"]}</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<input type='hidden' name='idCongregacao' id='idCongregacao' value='" . $congregacao["idCongregacao"] . "'/>";
            $conteudoHtml .= "<input type='hidden' name='idEstadoCadastrado' id='idEstadoCadastrado' value='".$congregacao["id_estado"]."'/>"; 
            $conteudoHtml .= "<input type='hidden' name='idCidadeCadastrado' id='idCidadeCadastrado' value='".$congregacao["id_cidade"]."'/>"; 
            $conteudoHtml .= "<img src='../../public/images/icons/editar.png' title='Editar' alt='' class='editarCadastro tam16 cursor' />";
            $conteudoHtml .= "</td>";

            $conteudoHtml .= "<td>";
            if ($this->isPadrao($congregacao["idCongregacao"])) {
                $conteudoHtml .= "--";
                $idCongregacaoPadrao = $congregacao["idCongregacao"];
            } else {
                $conteudoHtml .= "<input type='hidden' name='idCongregacaoPadrao' id='idCongregacaoPadrao' value='$idCongregacaoPadrao'/>";
                $conteudoHtml .= "<img src='../../public/images/icons/padrao.png' title='Tornar padrão' alt='' class='tornarPadrao tam16 cursor'/>";
            }
            $conteudoHtml .= "</td>";
            
            $conteudoHtml .= "</tr>";
        }

        $statement->close();
        $this->connection->close();

        $conteudoHtml .= "</tbody>";
        $conteudoHtmlRodape .= TableUtil::criarRodape(8);
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(8);

        if ($qtdRegistros > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        }
        return $conteudoHtmlTopo . $conteudoHtmlVazio;
    }

    /**
     * Cadastra congregacao.
     * @param Congregacao $congregacao
     * @return boolean
     */
    public function cadastrar($congregacao) {
        $this->connection = new ConnectionMySql();
        $nome = $congregacao->getNome();
        $rua = $congregacao->getRua();
        $bairro = $congregacao->getBairro();
        $complemento = $congregacao->getComplemento();
        $idCidade = $congregacao->getCidade()->getId();
        $fone =$congregacao->getFone(); 
        $dataFundacao = $congregacao->getDataFundacao();
        $idUsuario = $_SESSION["usuario"]["id"];
        
        $statement = $this->connection->open()->prepare("INSERT INTO congregacao (nome, rua, bairro, complemento, id_cidade, fone, 
                                                    data_fundacao, id_usuario_cadastro) VALUES (?,?,?,?,?,?,?,?)");
        $statement->bind_param("ssssissi", $nome, $rua, $bairro, $complemento, $idCidade, $fone, $dataFundacao, $idUsuario);
        $cadastrou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if (true) {
            return true;
        }
        return false;
    }

    /**
     * Edita congregacao.
     * @param Congregacao $congregacao
     * @return boolean
     */
    public function editar($congregacao) {
        $this->connection = new ConnectionMySql();
        $id = $congregacao->getId();
        $nome = $congregacao->getNome();
        $rua = $congregacao->getRua();
        $bairro = $congregacao->getBairro();
        $complemento = $congregacao->getComplemento();
        $idCidade = $congregacao->getCidade()->getId();
        $fone = $congregacao->getFone();
        $dataFundacao = $congregacao->getDataFundacao();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("UPDATE congregacao AS a
                                                    SET a.nome = ?, a.rua = ?, a.bairro = ?, a.complemento = ?, a.id_cidade = ?, a.fone = ?, 
                                                        a.data_fundacao = ?, a.id_usuario_cadastro = ?, a.data_cadastro = CURRENT_TIMESTAMP 
                                                    WHERE a.id = ?");
        $statement->bind_param("ssssissii", $nome, $rua, $bairro, $complemento, $idCidade, $fone, $dataFundacao, $idUsuario, $id);
        $editou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($editou) {
            return true;
        }
        return false;
    }

    /**
     * Retorna uma congregacao.
     * @param int $idCongregacao
     * @return Congregacao
     */
    public function getCongregacao($idCongregacao) {
        $this->connection = new ConnectionMySql();
        $congregacao = new Congregacao();
        $cidade = new Cidade();
        $estado = new Estado();
        $membro = new Membro();

        $statement = $this->connection->open()->prepare("SELECT a.nome, a.rua, a.bairro, a.complemento, d.id_estado, a.id_cidade, 
                                                    c.nome AS nomeEstado, c.sigla, d.nome AS nomeCidade, a.fone, a.data_fundacao, 
                                                    b.nome AS nomeUsuarioCadastro, 
                                                    DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dtCadastro
                                                    FROM congregacao AS a, membro AS b, estado AS c, cidade AS d
                                                    WHERE a.id_usuario_cadastro = b.id
                                                    AND a.id_cidade = d.id
                                                    AND d.id_estado = c.id
                                                    AND a.id = ?");
        $statement->bind_param("i", $idCongregacao);
        $statement->execute();
        $arrayCongregacao = $statement->get_result()->fetch_assoc();

        $statement->close();
        $this->connection->close();
        
        $congregacao->setNome($arrayCongregacao["nome"]);
        $congregacao->setRua($arrayCongregacao["rua"]);
        $congregacao->setBairro($arrayCongregacao["bairro"]);
        $congregacao->setComplemento($arrayCongregacao["complemento"]);
        
        $cidade->setId($arrayCongregacao["id_cidade"]);
        $cidade->setNome($arrayCongregacao["nomeCidade"]);
        $estado->setId($arrayCongregacao["id_estado"]);
        $estado->setNome($arrayCongregacao["nomeEstado"]);
        $estado->setSigla($arrayCongregacao["sigla"]);
        $cidade->setEstado($estado);
        $congregacao->setCidade($cidade);

        $congregacao->setFone($arrayCongregacao["fone"] !== "" ? $arrayCongregacao["fone"] : "");
        $congregacao->setDataFundacao($arrayCongregacao["data_fundacao"] !== "" ? $arrayCongregacao["data_fundacao"] : "");

        $membro->setNome($arrayCongregacao["nomeUsuarioCadastro"]);
        $congregacao->setUsuarioCadastro($membro);

        $congregacao->setDataCadastro($arrayCongregacao["dtCadastro"]);

        return $congregacao;
    }

    /**
     * Retorna a congregacao padrao.
     * @return Congregacao
     */
    public function getCongregacaoPadrao() {
        $this->connection = new ConnectionMySql();
        $congregacao = new Congregacao();
        $estado = new Estado();
        $cidade = new Cidade();
        $isPadrao = Congregacao::IS_PADRAO;

        $statement = $this->connection->open()->prepare("SELECT a.nome, a.rua, a.bairro, a.complemento, b.id AS idEstado, b.nome AS nomeEstado,
                                                    b.sigla, a.id_cidade AS idCidade, c.nome AS nomeCidade, a.fone 
                                                    FROM congregacao AS a, estado AS b, cidade AS c 
                                                    WHERE a.id_cidade = c.id
                                                    AND c.id_estado = b.id
                                                    AND a.is_padrao = ?");
        $statement->bind_param("i", $isPadrao);
        $statement->execute();
        $arrayCongregacao = $statement->get_result()->fetch_assoc();

        $statement->close();
        $this->connection->close();
        
        $congregacao->setNome($arrayCongregacao["nome"]);
        $congregacao->setRua($arrayCongregacao["rua"]);
        $congregacao->setBairro($arrayCongregacao["bairro"]);
        $congregacao->setComplemento($arrayCongregacao["complemento"]);
        
        $cidade->setId($arrayCongregacao["idCidade"]);
        $cidade->setNome($arrayCongregacao["nomeCidade"]);
        $estado->setId($arrayCongregacao["idEstado"]);
        $estado->setNome($arrayCongregacao["nomeEstado"]);
        $estado->setSigla($arrayCongregacao["sigla"]);
        $cidade->setEstado($estado);
        $congregacao->setCidade($cidade);
        
        $congregacao->setFone($arrayCongregacao["fone"] !== "" ? $arrayCongregacao["fone"] : "--");

        return $congregacao;
    }

    /**
     * Verifica se uma congregacao eh padrao.
     * @param int $idCongregacao
     * @return boolean
     */
    public function isPadrao($idCongregacao) {
        $this->connection = new ConnectionMySql();
        $isPadrao = Congregacao::IS_PADRAO;

        $statement = $this->connection->open()->prepare("SELECT a.id FROM congregacao AS a WHERE a.is_padrao = ? AND a.id = ?");
        $statement->bind_param("ii", $isPadrao, $idCongregacao);
        $statement->execute();

        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }

    /**
     * Torna uma congregacao padrao.
     * @param int $idCongregacao - ID da congregacao para se tornar padrao
     * @param int $idCongregacaoPadrao - ID da congregacao atual padrao
     * @return boolean
     */
    public function tornarPadrao($idCongregacao, $idCongregacaoPadrao) {
        $this->connection = new ConnectionMySql();
        $isPadrao = Congregacao::IS_PADRAO;
        $tornouPadrao = FALSE;

        //DESABILITAR CONGREGACAO PADRAO
        if (!empty($idCongregacaoPadrao)) {
            $statement = $this->connection->open()->prepare("UPDATE congregacao AS a 
                                                        SET a.is_padrao = 0, a.data_cadastro = CURRENT_TIMESTAMP
                                                        WHERE a.id = ?");
            $statement->bind_param("i", $idCongregacaoPadrao);

            //TORNAR NOVA CONGREGACAO PADRAO
            if ($statement->execute()) {
                $statement = $this->connection->open()->prepare("UPDATE congregacao AS a 
                                                            SET a.is_padrao = ?, a.data_cadastro = CURRENT_TIMESTAMP 
                                                            WHERE a.id = ?");
                $statement->bind_param("ii", $isPadrao, $idCongregacao);
                $tornouPadrao = $statement->execute();

                $statement->close();
                $this->connection->close();

                if ($tornouPadrao) {
                    return true;
                }
                return false;
            }
        }
        //TORNAR NOVA CONGREGACAO PADRAO
        $statement = $this->connection->open()->prepare("UPDATE congregacao AS a 
                                                    SET a.is_padrao = ?, a.data_cadastro = CURRENT_TIMESTAMP 
                                                    WHERE a.id = ?");
        $statement->bind_param("ii", $isPadrao, $idCongregacao);
        $tornouPadrao = $statement->execute();

        $statement->close();
        $this->connection->close();

        if ($tornouPadrao) {
            return true;
        }
        return false;
    }
}