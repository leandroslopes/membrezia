<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";

class BackupDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Lista as tabelas do banco de dados.
     * @return string
     */
    public function listarTabelas() 
    {
        $this->connection = new ConnectionMySql();
        $conteudoHtmlTopo = "";
        $conteudoHtml = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";

        $statement = $this->connection->open()->prepare("SHOW TABLES");
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;

        $arrayCabecalho = array("&nbsp;", "TABELA");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";

        $i = 1;
        while ($table = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . $i++ . "</td>";
            $conteudoHtml .= "<td>" . $table["Tables_in_membrezia"] . "</td>";
            $conteudoHtml .= "</tr>";
        }

        $conteudoHtmlRodape .= TableUtil::criarRodape(2);
        $conteudoHtmlVazio .= TableUtil::criarConteudoVazio(2);

        if ($qtdRegistros > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        } else {
            return $conteudoHtmlTopo . $conteudoHtmlVazio . $conteudoHtmlRodape;
        }
    }

    /**
     * Realiza backup da base de dados.
     */
    public function backup() 
    {
        $this->connection = new ConnectionMySql();
        $output = "";

        $statement = $this->connection->open()->prepare("SHOW TABLES");
        $statement->execute();
        $showTables = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        
        foreach($showTables as $table) {
            $statement = $this->connection->open()->prepare("SHOW CREATE TABLE " . $table["Tables_in_membrezia"]);
            $statement->execute();
            $showCreateTables = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

            foreach ($showCreateTables as $showTableRow) {
                $output .= "\n\n" . $showTableRow["Create Table"] .";\n\n"; 
            }
            
            $statement = $this->connection->open()->prepare("SELECT * FROM " . $table["Tables_in_membrezia"]);
            $statement->execute();
            $resultSelect = $statement->get_result();
            $totalRow = $resultSelect->num_rows; 
            
            for ($count = 0; $count < $totalRow; $count++) { 
                $singleResult = $resultSelect->fetch_assoc();
                $tableColumnArray = array_keys($singleResult);
                $tableValueArray = array_values($singleResult);
                $output .= "\nINSERT INTO " . $table["Tables_in_membrezia"] . "(";
                $output .= "" . implode(", ", $tableColumnArray) . ") VALUES (";
                $output .= "'" . implode("', '", $tableValueArray) . "'); \n";                 
            }
        }

        $statement->close();
        $this->connection->close();

        $file_name = "database_backup_on_" . date("d-m-Y") . ".sql";
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        header("Content-Description: File Transfer");
        header("Content-Type: application/octec-stream");
        header("Content-Disposition: attachment; filename=".basename($file_name));
        header("Content-Transfer-Enconding: binary");
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length: " . filesize($file_name));
        ob_clean();
        flush();
        readfile($file_name);
        exec('rm ' . $backup_file_name);
    }
}