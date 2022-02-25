<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Estado.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Cidade.php";

class CidadeDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Retorna um json com as cidades de um estado.
     * @param int $idEstado
     * @return string
     */
    public function getJsonCidades($idEstado) {
        $this->connection = new ConnectionMySql();
        $selecione = "";
        $conteudoHtml = "";
        $cidades = array();

        $statement = $this->connection->open()->prepare("SELECT a.* FROM cidade AS a WHERE a.id_estado = ? ORDER BY a.nome");
        $statement->bind_param("i", $idEstado);
        $statement->execute();

        $result = $statement->get_result();

        while ($cidade = $result->fetch_assoc()) {
            $cidades[] = array(
                "id"	=> $cidade["id"],
                "nome"	    => $cidade["nome"],
            );
        }

        $statement->close();
        $this->connection->close();

        return json_encode($cidades);
    }

    /**
     * Retorna uma cidade.
     * @param int $idCidade
     * @return Cidade
     */
    public function getCidade($idCidade = 0) {
        $this->connection = new ConnectionMySql();
        $estado = new Estado();
        $cidade = new Cidade();
        
        $statement = $this->connection->open()->prepare("SELECT a.* FROM cidade AS a WHERE a.id= ?");
        $statement->bind_param("i", $idCidade);
        $statement->execute();

        $result = $statement->get_result();
        $arrayCidade = $result->fetch_assoc();
        $statement->close();
        $this->connection->close();

        $cidade->setId($arrayCidade["id"]);
        $cidade->setSigla($arrayCidade["sigla"]);
        $cidade->setNome($arrayCidade["nome"]);
        
        $estado->setId($arrayCidade["id_estado"]);
        $cidade->setEstado($estado);

        return $cidade;
    }
}