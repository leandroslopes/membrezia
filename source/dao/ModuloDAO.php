<?
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/sistema/Modulo.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/TableUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/CargoUtil.php";

class ModuloDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Mostra os modulos que o funcionario tem acesso.
     * @param array $usuario
     * @param boolean $isModulo
     * @return string
     */
    public function mostrarModulos($usuario, $isModulo) {
        $this->connection = new ConnectionMySql();  
        $conteudoHtml = "";
        $diretorio = "modules/";

        if ($isModulo) {
            $diretorio = "../";
        }
        
        $statement = $this->connection->open()->prepare("SELECT a.id, a.nome, a.diretorio, a.icone 
                                                    FROM modulo AS a, modulo_acesso AS b 
                                                    WHERE a.id = b.id_modulo 
                                                    AND b.id_cargo = ? 
                                                    ORDER BY a.nome");
        $statement->bind_param("i", $usuario["idCargo"]);
        $statement->execute(); 
        
        $result = $statement->get_result();

        $conteudoHtml .= "<ul>";
        while ($modulo = $result->fetch_assoc()) { 
            $conteudoHtml .= $this->criarItemModulo($modulo, $diretorio);
        }
        $conteudoHtml .= "</ul>";

        $statement->close();
        $this->connection->close();

        return $conteudoHtml;
    }

    /**
     * Retorna um modulo.
     * @param int $idModulo
     * @return Modulo
     */
    public function getModulo($idModulo) {
        $this->connection = new ConnectionMySql();
        $modulo = new Modulo();
        
        $statement = $this->connection->open()->prepare("SELECT * FROM modulo AS a WHERE a.id = ?");
        $statement->bind_param("i", $idModulo);
        $statement->execute();

        $arrayModulo =  $statement->get_result()->fetch_assoc();

        $statement->close();
        $this->connection->close();

        $modulo->setId($arrayModulo["id"]);
        $modulo->setNome($arrayModulo["nome"]);
        $modulo->setDiretorio($arrayModulo["diretorio"]);
        $modulo->setIcone($arrayModulo["icone"]);

        return $modulo;
    }

    /**
     * Lista os modulos para possiveis modificacoes.
     * @param int $idModulo
     * @return string
     */
    public function listarModulos($idModulo) {
        $this->connection = new ConnectionMySql();
        $conteudoHtmlTopo = "";
        $conteudoHtml = "";
        $conteudoHtmlRodape = "";

        $statement = $this->connection->open()->prepare("SELECT a.id, a.nome, a.diretorio FROM modulo AS a ORDER BY a.nome");
        $statement->execute();
        $result = $statement->get_result();

        $arrayCabecalho = array("MÓDULO", "DIRETÓRIO");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        $conteudoHtml .= "<tbody>";
    
        while ($modulo = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td><a href='modulo.php?id=" . $idModulo . "&idModuloCadastrado=" . $modulo["id"] . "'>";
            $conteudoHtml .= $modulo["nome"] . "</a></td>";
            $conteudoHtml .= "<td>" . $modulo["diretorio"] . "</td>";
            $conteudoHtml .= "</tr>";
        }

        $conteudoHtml .= "</tbody>";
        $conteudoHtmlRodape .= TableUtil::criarRodape(4);
        return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
    }

    /**
     * Cria um item '<li>' para amostragem dos modulos.
     * @param array $modulo
     * @param string $diretorio
     * @return string
     */
    private function criarItemModulo($modulo, $diretorio) {
        $conteudoHtml = "";

        $conteudoHtml .= "<li>";
        $conteudoHtml .= "<a class='txtPequeno' href='" . $diretorio . $modulo["diretorio"] . "/index.php?id=" . $modulo["id"] . "'>";
        $conteudoHtml .= "<div id='" . $modulo["icone"] . "'></div> " . $modulo["nome"];
        $conteudoHtml .= "</a>";
        $conteudoHtml .= "</li>";

        return $conteudoHtml;
    }

    /**
     * Lista os cargos que tem acesso a um determinado modulo.
     * @param int $idModulo
     * @return string
     */
    public function listarCargos($idModulo) {
        $this->connection = new ConnectionMySql();
        $conteudoHtmlTopo = "";
        $conteudoHtml = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";

        $statement = $this->connection->open()->prepare("SELECT a.id AS idModuloAcesso, a.id_cargo, b.nome AS nomeUsuarioCadastro, 
                                                    DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dtCadastro 
                                                    FROM modulo_acesso AS a, membro AS b 
                                                    WHERE a.id_usuario_cadastro = b.id 
                                                    AND a.id_modulo = ?");
        $statement->bind_param("i", $idModulo);
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;

        $arrayCabecalho = array("CARGO", "USUÁRIO CADASTRO", "DATA DE CADASTRO", "EXCLUIR");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayCabecalho);
        
        $conteudoHtml .= "<tbody>";
        while ($cargo = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . CargoUtil::getNome($cargo["id_cargo"]) . "</td>";
            $conteudoHtml .= "<td>" . $cargo["nomeUsuarioCadastro"] . "</td>";
            $conteudoHtml .= "<td>" . $cargo["dtCadastro"] . "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<input type='hidden' name='idModuloAcesso' id='idModuloAcesso' value='" . $cargo["idModuloAcesso"] . "'/>";
            $conteudoHtml .= "<img src='../../public/images/icons/excluir.png' title='Excluir' alt='' class='excluirAcessoCargo tam16 cursor' />";
            $conteudoHtml .= "</td>";
            $conteudoHtml .= "</tr>";
        }

        $conteudoHtmlVazio .= "<tr> <td>-</td> <td>-</td> </tr>";

        $conteudoHtmlRodape .= "</tbody>";
        $conteudoHtmlRodape .= "</table>";

        if ($qtdRegistros > 0) {
            return $conteudoHtmlTopo . $conteudoHtml . $conteudoHtmlRodape;
        } else {
            return $conteudoHtmlTopo . $conteudoHtmlVazio . $conteudoHtmlRodape;
        }
    }

}

?>