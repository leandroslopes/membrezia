<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";

class DespesaTipoDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Cadastra tipo de despesa.
     * @param DespesaTipo $despesaTipo
     * @return boolean
     */
    public function cadastrar($despesaTipo) {
        $this->connection = new ConnectionMySql();
        $nome = $despesaTipo->getNome();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("INSERT INTO despesa_tipo (nome, id_usuario_cadastro) VALUES (?,?)");
        $statement->bind_param("si", $nome, $idUsuario);
        $cadastrou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($cadastrou) {
            return true;
        }
        return false;
    }

    /**
     * Edita tipo de despesa.
     * @param DespesaTipo $despesaTipo
     * @return boolean
     */
    public function editar($despesaTipo) {
        $this->connection = new ConnectionMySql();
        $id = $despesaTipo->getId();
        $nome = $despesaTipo->getNome();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("UPDATE despesa_tipo SET nome = ?, id_usuario_cadastro = ?, 
                                                    data_cadastro = CURRENT_TIMESTAMP WHERE id = ?");
        $statement->bind_param("sii", $nome, $idUsuario, $id);
        $editou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($editou) {
            return true;
        }
        return false;
    }

    /**
     * Exclui tipo de despesa.
     * @param int $id
     * @return boolean
     */
    public function excluir($id) {
        $this->connection = new ConnectionMySql();

        $statement = $this->connection->open()->prepare("DELETE FROM despesa_tipo WHERE id = ?");
        $statement->bind_param("i", $id);
        $excluiu = $statement->execute();

        $statement->close();
        $this->connection->close();

        return $excluiu;
    }

    /**
     * Verifica se um tipo de despesa estah associada a algum registro de Movimentacao.
     * @param int $idDespesaTipo
     * @return boolean
     */
    public function despesaTipoEstaAssociada($idDespesaTipo) {
        $this->connection = new ConnectionMySql();

        $statement = $this->connection->open()->prepare("SELECT a.id FROM movimentacao AS a WHERE a.id_despesa_tipo = ?");
        $statement->bind_param("i", $idDespesaTipo);
        $statement->execute();
        $result = $statement->get_result();
        $qtdMovimentacoes = $result->num_rows;
        if ($qtdMovimentacoes > 0) {
            return true;
        }
        return false;
    }

    /**
     * Lista os tipos de despesa.
     * @return string
     */
    public function listarDespesaTipos() {
        $this->connection = new ConnectionMySql();
        $conteudoHtmlTopo = "";
        $conteudoHtml = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";

        $statement = $this->connection->open()->prepare("SELECT a.id, a.nome, b.nome AS nomeUsuarioCadastro, 
                                                    DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dtCadastro
                                                    FROM despesa_tipo AS a, membro AS b
                                                    WHERE a.id_usuario_cadastro = b.id 
                                                    ORDER BY a.nome");
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;

        $arrayCabecalho = array("TIPO", "USUÁRIO CADASTRO", "DATA CADASTRO", "EDITAR", "EXCLUIR");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($despesaTipo = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . $despesaTipo["nome"] . "</td>";
            $conteudoHtml .= "<td>" . $despesaTipo["nomeUsuarioCadastro"] . "</td>";
            $conteudoHtml .= "<td>" . $despesaTipo["dtCadastro"] . "</td>";
            $conteudoHtml .= "<td>"; 
            $conteudoHtml .= "<input type='hidden' name='idDespesaTipo' id='idDespesaTipo' value='" . $despesaTipo["id"] . "'/>";                
            $conteudoHtml .= "<img src='../../public/images/icons/editar.png' title='Editar' alt='' class='editarDespesaTipo tam16 cursor' />";
            $conteudoHtml .= "</td>";
            
            $conteudoHtml .= "<td>";
            if ($this->despesaTipoEstaAssociada($despesaTipo["id"])) {
                $conteudoHtml .= "--";
            } else {
                $conteudoHtml .= "<img src='../../public/images/icons/excluir.png' title='Excluir' alt='' class='excluirDespesaTipo tam16 cursor'/>";
            }
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "</tr>";
        }

        $conteudoHtmlRodape .= TableUtil::criarRodape(5);
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(5);

        if ($qtdRegistros > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        } else {
            return $conteudoHtmlTopo . $conteudoHtmlVazio . $conteudoHtmlRodape;
        }
    }

    /**
     * Retorna um 'select' com os tipos de despesa.
     * @param int $idDespesaTipo
     * @return string
     */
    public function getSelect($idDespesaTipo = 0) {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";

        $statement = $this->connection->open()->prepare("SELECT a.* FROM despesa_tipo AS a ORDER BY a.nome");
        $statement->execute();
        $result = $statement->get_result();

        $conteudoHtml .= "<select name='idDespesaTipo' id='idDespesaTipo'>";
        $idDespesaTipo === 0 ? $selecione = "selected" : $selecione = "";
        $conteudoHtml .= "<option value='' $selecione>SELECIONE A DESPESA</option>";
        while ($despesaTipo = $result->fetch_assoc()) {
            if ($despesaTipo["id"] == $idDespesaTipo) {
                $conteudoHtml .= "<option value='" . $despesaTipo["id"] . "' selected>" . $despesaTipo["nome"] . "</option>";
            } else {
                $conteudoHtml .= "<option value='" . $despesaTipo["id"] . "'>" . $despesaTipo["nome"] . "</option>";
            }
        }
        $conteudoHtml .= "</select>";

        return $conteudoHtml;
    }
}