<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Congregacao.php";

class CongregacaoCultoDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Lista cultos de uma congregacao.
     * @param int $idCongregacao
     * @return string
     */
    public function listarCultos($idCongregacao) {
        $this->connection = new ConnectionMySql();
        $cultoDAO = new CultoDAO();
        $conteudoHtmlTopo = "";
        $conteudoHtml = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";

        $statement = $this->connection->open()->prepare("SELECT a.id AS idCongregacaoCulto, b.id AS idCulto, b.nome, c.nome AS nomeUsuarioCadastro,
                                                    DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dtCadastro
                                                    FROM congregacao_culto AS a, culto AS b, membro AS c 
                                                    WHERE a.id_culto = b.id
                                                    AND a.id_usuario_cadastro = c.id 
                                                    AND a.id_congregacao = ? ORDER BY b.nome");
        $statement->bind_param("i", $idCongregacao);
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;

        $arrayCabecalho = array("CULTO", "USUÁRIO CADASTRO", "DATA CADASTRO", "+ OFERTA", "DESASSOCIAR");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        while ($culto = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . $culto["nome"] . "</td>";
            $conteudoHtml .= "<td>" . $culto["nomeUsuarioCadastro"] . "</td>";
            $conteudoHtml .= "<td>" . $culto["dtCadastro"] . "</td>";
            
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<input type='hidden' name='idCongregacaoCulto' id='idCongregacaoCulto' value='" . $culto["idCongregacaoCulto"] . "'/>";    
            $conteudoHtml .= "<img src='../../public/images/icons/adicionar.png' title='Adicionar Oferta' alt='' class='adicionarOferta tam16 cursor'/>";
            $conteudoHtml .= "</td>";
            if ($cultoDAO->cultoTemOfertas($culto["idCulto"])) {
                $conteudoHtml .= "<td>--</td>";
            } else { 
                $conteudoHtml .= "<td>";
                $conteudoHtml .= "<img src='../../public/images/icons/excluir.png' title='Desassociar Culto' alt='' class='desassociarCulto tam16 cursor'/>";
                $conteudoHtml .= "</td>";
            }
            $conteudoHtml .= "</tr>";
        }

        $statement->close();
        $this->connection->close();

        $conteudoHtmlRodape .= TableUtil::criarRodape(5);
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(5);

        if ($qtdRegistros > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        } else {
            return $conteudoHtmlTopo . $conteudoHtmlVazio;
        }
    }

    /**
     * Adiciona um culto a uma congregacao.
     * @param CongregacaoCulto $congregacaoCulto
     * @return boolean
     */
    public function adicionar($congregacaoCulto) {
        $this->connection = new ConnectionMySql();
        $idCongregacao = $congregacaoCulto->getCongregacao()->getId();
        $idCulto = $congregacaoCulto->getCulto()->getId();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("INSERT INTO congregacao_culto (id_congregacao, id_culto, id_usuario_cadastro) VALUES (?, ?, ?)");
        $statement->bind_param("iii", $idCongregacao, $idCulto, $idUsuario);
        $adicionou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($adicionou) {
            return true;
        }
        return false;
    }

    /**
     * Desassocia um culto de uma congregacao.
     * @param int $idCongregacaoCulto
     * @return boolean
     */
    public function desassociar($idCongregacaoCulto) {
        $this->connection = new ConnectionMySql();

        $statement = $this->connection->open()->prepare("DELETE FROM congregacao_culto WHERE id = ?");
        $statement->bind_param("i", $idCongregacaoCulto);
        $excluiu = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($excluiu) {
            return true;
        }
        return false;
    }

    /**
     * Retorna um 'select' com os cultos de uma congregacao.
     * @param int $idCongregacao
     * @param int $idCongregacaoCulto
     * @return string
     */
    public function getSelect($idCongregacao, $idCongregacaoCulto) {
        $conteudo = "";

        
        $query = "SELECT a.id, b.nome 
                  FROM congregacao_culto AS a, culto AS b 
                  WHERE a.id_culto = b.id 
                  AND a.id_congregacao = $idCongregacao
                  ORDER BY b.nome";
        $retorno = $this->connection->executaQuery($query);

        $conteudo .= "<select name='idCongregacaoCulto' id='congregacaoCulto'>";
        $conteudo .= "<option value=''>SELECIONE O CULTO</option>";

            while ($congregacaoCulto = $this->connection->getRegistros($retorno)) {
                if ($congregacaoCulto["id"] == $idCongregacaoCulto) {
                    $conteudo .= "<option value='" . $congregacaoCulto["id"] . "' selected='selected'>" . $congregacaoCulto["nome"] . "</option>";
                } else {
                    $conteudo .= "<option value='" . $congregacaoCulto["id"] . "'>" . $congregacaoCulto["nome"] . "</option>";
                }                
            }

            $conteudo .= "</select>";
        

        return $conteudo;
    }
}