<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/MovimentacaoTipoUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";

class CultoDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Lista os cultos.
     * @return string
     */
    public function listarCultos() {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";
        $conteudoHtmlTopo = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";
        
        $statement = $this->connection->open()->prepare("SELECT a.id, a.nome, b.nome AS nomeUsuarioCadastro, 
                                                    DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dtCadastro
                                                    FROM culto AS a, membro AS b 
                                                    WHERE a.id_usuario_cadastro = b.id 
                                                    ORDER BY a.nome");
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;
        
        $arrayCabecalho = array("CULTO", "USUÁRIO CADASTRO", "DATA DE CADASTRO", "EDITAR", "EXCLUIR");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($culto = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . $culto["nome"] . "</td>";
            $conteudoHtml .= "<td>" . $culto["nomeUsuarioCadastro"] . "</td>";
            $conteudoHtml .= "<td>" . $culto["dtCadastro"] . "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<input type='hidden' name='idCulto' id='idCulto' value='" . $culto["id"] . "'/>";
            $conteudoHtml .= "<img src='../../public/images/icons/editar.png' title='Editar' alt='' class='editarCulto tam16 cursor'/>";
            $conteudoHtml .= "</td>";

            $conteudoHtml .= "<td>";
            if ($this->cultoTemOfertas($culto["id"])) {
                $conteudoHtml .= "--";
            } else {
                $conteudoHtml .= "<img src='../../public/images/icons/excluir.png' title='Excluir' alt='' class='excluirCulto tam16 cursor'/>";
            }
            $conteudoHtml .= "</td>";
            
            $conteudoHtml .= "</tr>";
        }

        $statement->close();
        $this->connection->close();

        $conteudoHtml .= "</tbody>";
        $conteudoHtmlRodape .= TableUtil::criarRodape(5);
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(5);

        if ($qtdRegistros > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        }
        return $conteudoHtmlTopo . $conteudoHtmlVazio;
    }

    /**
     * Cadastra culto.
     * @param Culto $culto
     * @return boolean
     */
    public function cadastrar($culto) {
        $this->connection = new ConnectionMySql();
        $nome = $culto->getNome();
        $idUsuario = $_SESSION["usuario"]["id"];
        
        $statement = $this->connection->open()->prepare("INSERT INTO culto (nome, id_usuario_cadastro) VALUES (?, ?)");
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
     * Edita culto.
     * @param Culto $culto
     * @return boolean
     */
    public function editar($culto) {
        $this->connection = new ConnectionMySql();
        $id = $culto->getId();
        $nome = $culto->getNome();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("UPDATE culto AS a SET a.nome = ?, a.id_usuario_cadastro = ?, 
                                                    a.data_cadastro = CURRENT_TIMESTAMP WHERE a.id = ?");
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
     * Exclui culto.
     * @param Culto $culto
     * @return boolean
     */
    public function excluir($culto) {
        $this->connection = new ConnectionMySql();
        $id = $culto->getId();

        $statement = $this->connection->open()->prepare("DELETE FROM culto WHERE id = ?");
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
     * Retorna um 'select' com os cultos a serem adicionados para a congregacao.
     * @param int $idCongregacao 
     * @return string
     */
    public function getSelectAdicionarCulto($idCongregacao) {
        $this->connection = new ConnectionMySql();
        $conteudoHtml = "";

        $statement = $this->connection->open()->prepare("SELECT a.id, a.nome FROM culto AS a 
                                                    WHERE a.id != ALL (SELECT b.id_culto FROM congregacao_culto AS b WHERE b.id_congregacao = ?) 
                                                    ORDER BY a.nome");
        $statement->bind_param("i", $idCongregacao);
        $statement->execute();
        $result = $statement->get_result();

        $conteudoHtml .= "<select name='idCulto' id='idCulto'>";
        $conteudoHtml .= "<option value=''>SELECIONE O CULTO</option>";
        while ($culto = $result->fetch_assoc()) {
            $conteudoHtml .= "<option value='" . $culto["id"] . "'>" . $culto["nome"] . "</option>";
        }
        $conteudoHtml .= "</select>";

        return $conteudoHtml;
    }

    /**
     * Verifica se um culto tem ofertas.
     * @param int $idCulto
     * @return boolean
     */
    public function cultoTemOfertas($idCulto) {
        $this->connection = new ConnectionMySql();
        $movimentacaoTipo = MovimentacaoTipoUtil::OFERTA;

        $statement = $this->connection->open()->prepare("SELECT * FROM movimentacao AS a, congregacao_culto AS b, culto AS c 
                                                    WHERE a.tipo = ? 
                                                    AND a.id_congregacao_culto = b.id
                                                    AND b.id_culto = c.id 
                                                    AND c.id = ?");
        $statement->bind_param("si", $movimentacaoTipo, $idCulto);
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;
        
        $statement->close();
       
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }
}