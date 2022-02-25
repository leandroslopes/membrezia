<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Funcao.php";

class FuncaoDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Cadastra funcao.
     * @param Funcao $funcao
     * @return boolean
     */
    public function cadastrar($funcao) {
        $this->connection = new ConnectionMySql();
        $nome = $funcao->getNome();
        $sigla = $funcao->getSigla();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("INSERT INTO funcao (sigla, nome, id_usuario_cadastro) VALUES (?, ?, ?)");
        $statement->bind_param("ssi", $sigla, $nome, $idUsuario);
        $cadastrou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($cadastrou) {
            return true;
        }
        return false;
    }

    /**
     * Exclui funcao.
     * @param int $id
     * @return boolean
     */
    public function excluir($id) {
        $this->connection = new ConnectionMySql();
        
        $statement = $this->connection->open()->prepare("DELETE FROM funcao WHERE id = ?");
        $statement->bind_param("i", $id);
        $excluiu = $statement->execute();

        $statement->close();
        $this->connection->close();

        if ($excluiu) {
            return true;
        }
        return false;
    }

    /**
     * Verifica se uma funcao estah associada a algum registro de Membro.
     * @return boolean
     */
    public function funcaoEstaAssociada($idFuncao) {
        $this->connection = new ConnectionMySql();

        $statement = $this->connection->open()->prepare("SELECT a.id FROM membro AS a WHERE a.id_funcao = ?");
        $statement->bind_param("i", $idFuncao);
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;

        $statement->close();

        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Lista as funcoes.
     * @return string
     */
    public function listarFuncoes() {
        $this->connection = new ConnectionMySql();
        $conteudoHtmlTopo = "";
        $conteudoHtml = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";

        $statement = $this->connection->open()->prepare("SELECT a.id, a.nome AS nomeFuncao, a.sigla, b.nome AS nomeUsuarioCadastro,
                                                    DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dtCadastro   
                                                    FROM funcao AS a, membro AS b 
                                                    WHERE a.id_usuario_cadastro = b.id
                                                    ORDER BY a.nome");
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;

        $arrayCabecalho = array("FUNÇÃO", "SIGLA", "USUÁRIO CADASTRO", "DATA CADASTRO", "EXCLUIR");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($funcao = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . $funcao["nomeFuncao"] . "</td>";
            $conteudoHtml .= "<td>" . $funcao["sigla"] . "</td>";
            $conteudoHtml .= "<td>" . $funcao["nomeUsuarioCadastro"] . "</td>";
            $conteudoHtml .= "<td>" . $funcao["dtCadastro"] . "</td>";
            
            $conteudoHtml .= "<td>";
            if ($this->funcaoEstaAssociada($funcao["id"])) {
                $conteudoHtml .= "--";
            } else {
                $conteudoHtml .= "<input type='hidden' name='id_funcao' id='id_funcao' value='" . $funcao["id"] . "'/>";
                $conteudoHtml .= "<img src='../../public/images/icons/excluir.png' title='Excluir' alt='' class='excluirFuncao tam16 cursor' />";
            }
            $conteudoHtml .= "</td>";

            $conteudoHtml .= "</tr>";
        }

        $conteudoHtmlRodape .= TableUtil::criarRodape(5);
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(5);

        if ($qtdRegistros > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        }
        return $conteudoHtmlTopo . $conteudoHtmlVazio;
    }

    /**
     * Retorna um 'select' com os funcoes.
     * @param int $idFuncao
     * @return string
     */
    public function getSelect($idFuncao = 0) {
        $this->connection = new ConnectionMySql();
        $selecione = "";
        $conteudoHtml = "";
        
        $statement = $this->connection->open()->prepare("SELECT a.* FROM funcao AS a ORDER BY a.nome");
        $statement->execute();

        $result = $statement->get_result();

        $conteudoHtml .= "<select name='idFuncao'>";
        $idFuncao == 0 ? $selecione = "selected" : $selecione = "";
        $conteudoHtml .= "<option value='' $selecione>SELECIONE A FUNÇÃO</option>";        
        while ($funcao = $result->fetch_assoc()) {
            if ($funcao["id"] == $idFuncao) {
                $conteudoHtml .= "<option value='" . $funcao["id"] . "' selected>" . $funcao["nome"] . "</option>";
            } else {
                $conteudoHtml .= "<option value='" . $funcao["id"] . "'>" . $funcao["nome"] . "</option>";
            }
        }
        $conteudoHtml .= "</select>";

        $statement->close();

        return $conteudoHtml;
    }

    /**
     * Retorna uma funcao.
     * @param int $id
     * @return Funcao
     */
    public function getFuncao($id) {
        $this->connection = new ConnectionMySql();
        $funcao = new Funcao();
        $usuarioCadastro = new Membro();
        
        $statement = $this->connection->open()->prepare("SELECT a.nome, a.sigla, a.id_usuario_cadastro, 
                                                        DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dataCadastro 
                                                        FROM funcao AS a");
        $statement->execute();

        $arrayFuncao = $statement->get_result()->fetch_assoc();
        
        $statement->close();
        $this->connection->close();

        $funcao->setNome($arrayFuncao["nome"]);
        $funcao->setSigla($arrayFuncao["sigla"]);

        $usuarioCadastro->setId($arrayFuncao["id_usuario_cadastro"]);
        $funcao->setUsuarioCadastro($usuarioCadastro);

        $funcao->setDataCadastro($arrayFuncao["dataCadastro"]);

        return $funcao;
    }
}