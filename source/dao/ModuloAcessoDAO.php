<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";

class ModuloAcessoDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Adiciona o acesso do cargo a um modulo.
     * @param int $idModulo
     * @param int $idCargo
     * @return boolean
     */
    public function adicionarAcessoCargo($idModulo, $idCargo) {
        $this->connection = new ConnectionMySql();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("INSERT INTO modulo_acesso (id_modulo, id_cargo, id_usuario_cadastro) VALUES (?, ?, ?)");
        $statement->bind_param("iii", $idModulo, $idCargo, $idUsuario);
        $adicionou = $statement->execute();
        
        $statement->close();
        $this->connection->close();
        
        return $adicionou;
    }

    /**
     * Exclui o acesso de um cargo ao modulo.
     * @param int $idModuloAcesso
     * @return boolean
     */
    public function excluirAcessoCargo($idModuloAcesso) {
        $this->connection = new ConnectionMySql();
        
        $statement = $this->connection->open()->prepare("DELETE FROM modulo_acesso WHERE id = ?");
        $statement->bind_param("i", $idModuloAcesso);
        $excluiu = $statement->execute();
        
        $statement->close();
        $this->connection->close();
        
        return $excluiu;
    }

    /**
     * Verifica se uma cargo estah associado a algum registro de ModuloAcesso.
     * @return boolean
     */
    public function cargoEstaAdicionado($idModulo, $idCargo) {
        $this->connection = new ConnectionMySql();

        $statement = $this->connection->open()->prepare("SELECT * FROM modulo_acesso AS a WHERE a.id_modulo = ? AND a.id_cargo = ?");
        $statement->bind_param("ii", $idModulo, $idCargo);
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