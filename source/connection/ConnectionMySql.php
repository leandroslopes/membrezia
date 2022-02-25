<?

require "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/Connection.php";

class ConnectionMySql extends Connection 
{
    const HOST = "localhost";
    const USER = "root";
    const PASSWORD = "";
    const DATABASE = "membrezia";

    /**
     * @var mysqli
     */
    private $connection;

    /**
     * Retorna conexao com o banco de dados.
     * @return mysqli
     */
    public function open() 
    {
        $this->connection = new mysqli(ConnectionMySql::HOST, ConnectionMySql::USER, ConnectionMySql::PASSWORD, ConnectionMySql::DATABASE);
        $this->connection->set_charset("utf8");
        if ($this->connection->connect_error) {
            die("Erro ao conectar com o banco de dados: " . $this->connection->connect_error);
        }
        return $this->connection;
    }
    
    /**
     * Fecha conexao.
     */
    public function close() 
    {
        $this->connection->close();
    }
}