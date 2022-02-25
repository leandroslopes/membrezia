<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Estado.php";

class EstadoDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Retorna um 'select' com os estados.
     * @param int $idEstado
     * @return string
     */
    public function getSelect($idEstado = 0) {
        $this->connection = new ConnectionMySql();
        $selecione = "";
        $conteudoHtml = "";
        
        $statement = $this->connection->open()->prepare("SELECT a.* FROM estado AS a ORDER BY a.nome");
        $statement->execute();

        $result = $statement->get_result();

        $conteudoHtml .= "<select name='idEstado' id='idEstado'>";
        $conteudoHtml .= "<option value='' $selecione>SELECIONE O ESTADO</option>";        
        
        while ($estado = $result->fetch_assoc()) {
            if ($estado["id"] == $idEstado) {
                $conteudoHtml .= "<option value='" . $estado["id"] . "' selected>" . $estado["nome"] . "</option>";
            } else {
                $conteudoHtml .= "<option value='" . $estado["id"] . "'>" . $estado["nome"] . "</option>";
            }
        }

        $statement->close();
        $this->connection->close();

        $conteudoHtml .= "</select>";
        return $conteudoHtml;
    }

    /**
     * Retorna um estado.
     * @param int $idEstado
     * @return Estado
     */
    public function getEstado($idEstado = 0) {
        $this->connection = new ConnectionMySql();
        $estado = new Estado();

        $statement = $this->connection->open()->prepare("SELECT a.* FROM estado AS a WHERE a.id= ?");
        $statement->bind_param("i", $idEstado);
        $statement->execute();

        $result = $statement->get_result();
        $arrayEstado = $result->fetch_assoc();
        $statement->close();
        $this->connection->close();

        $estado->setId($arrayEstado["id"]);
        $estado->setSigla($arrayEstado["sigla"]);
        $estado->setNome($arrayEstado["nome"]);

        return $estado;
    }
}