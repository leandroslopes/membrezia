<?

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/connection/ConnectionMySql.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Congregacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Funcao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Estado.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Cidade.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";

class MembroDAO 
{
    /**
     *
     * @var ConnectionMySql 
     */
    private $connection;

    /**
     * Veirifica se o usuario tem acesso ao sistema. 
     * @param array $usuario
     * @return boolean
     */
    public function login($usuario) {
        $this->connection = new ConnectionMySql();

        $cpf = $usuario["cpf"];
        $senha = md5($usuario["senha"]);
        
        $statement = $this->connection->open()->prepare("SELECT * FROM membro AS a WHERE a.cpf = ? AND a.senha = ?");
        $statement->bind_param("ss", $cpf, $senha); 
        $statement->execute();
        $statement->store_result();
        $qtdRegistros = $statement->num_rows;
        
        $statement->close();
        $this->connection->close();

        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

    /**
     * Lista os membros.
     * @param array $requestForm
     * @param int $idModulo
     * @param boolean $isMesAtual
     * @return string
     */
    public function listarMembros($requestForm, $idModulo, $isMesAtual = false) {
        $this->connection = new ConnectionMySql();
        $congregacaoDAO = new CongregacaoDAO();
        $filtro = "";
        $filtroCpf = "";
        $filtroNome = "";
        $filtroMesAtual = "";
        $conteudoHtmlTopo = "";
        $conteudoHtml = "";
        $conteudoHtmlVazio = "";
        $conteudoHtmlRodape = "";
        
        if (!empty($requestForm)) {
            if (!empty($requestForm["cpf"]) && !empty($requestForm["nome"])) {
                $filtro = "WHERE a.cpf = " . $requestForm["cpf"] . "AND a.nome LIKE '%" . $requestForm["nome"] . "%'";
            } elseif (!empty($requestForm["cpf"])) {
                $filtroCpf = "WHERE a.cpf = " . $requestForm["cpf"];
            } else {
                $filtroNome = "WHERE a.nome LIKE '%" . $requestForm["nome"] . "%'";
            }
        }

        if ($isMesAtual) {
            $filtroMesAtual = "WHERE DATE_FORMAT(a.data_cadastro, '%m') = DATE_FORMAT(CURRENT_DATE, '%m')";
        }
        
        $statement = $this->connection->open()->prepare("SELECT a.*, DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS dtCadastro 
                                                    FROM membro AS a 
                                                    $filtro 
                                                    $filtroCpf 
                                                    $filtroNome
                                                    $filtroMesAtual 
                                                    ORDER BY a.nome"); 
        $statement->execute();
        $result = $statement->get_result();
        $qtdRegistros = $result->num_rows;
        
        $arrayMembro = array("CPF", "NOME", "FONE", "CONGREGAÇÃO", "EDITAR");
        $conteudoHtmlTopo .= TableUtil::criarTopo($arrayMembro);
        $conteudoHtml .= "<tbody>";

        while ($membro = $result->fetch_assoc()) {
            $conteudoHtml .= "<tr>";
            $conteudoHtml .= "<td>" . ($membro["cpf"] !== "" ? $membro["cpf"] : "--") . "</td>";
            $conteudoHtml .= "<td>" . $membro["nome"] . "</td>";
            $conteudoHtml .= "<td>" . ($membro["fone"] !== "" ? $membro["fone"] : "--") . "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .=  !empty($congregacaoDAO->getCongregacao($membro["id_congregacao"])->getNome()) ? 
                                $congregacaoDAO->getCongregacao($membro["id_congregacao"])->getNome() : 
                                "--";
            $conteudoHtml .=  "</td>";
            $conteudoHtml .= "<td>";
            $conteudoHtml .= "<a href='formCadastrar.php?id=$idModulo&idCadastrado=" . $membro["id"] . "'>";
            $conteudoHtml .= "<img src='../../public/images/icons/editar.png' title='Editar' alt='' class='editarCadastro tam16 cursor' />";
            $conteudoHtml .= "</a>";
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
     * Retorna um membro.
     * @param int $id
     * @return Membro
     */
    public function getMembro($id) {
        $this->connection = new ConnectionMySql();
        $membro = new Membro();
        $congregacao = new Congregacao();
        $funcao = new Funcao();
        $estado = new Estado();
        $cidade = new Cidade();
        $usuarioCadastro = new Membro();

        $statement = $this->connection->open()->prepare("SELECT a.*, b.id AS idEstado, b.nome AS nomeEstado, b.sigla, c.id AS idCidade, 
                                                    c.nome AS nomeCidade, DATE_FORMAT(a.data_cadastro, '%d/%m/%Y %H:%i:%s') AS dtCadastro 
                                                    FROM membro AS a, estado AS b, cidade AS c 
                                                    WHERE a.id_cidade = c.id 
                                                    AND c.id_estado = b.id
                                                    AND a.id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        
        $arrayMembro = $statement->get_result()->fetch_assoc();
        
        $statement->close();
        $this->connection->close();

        $membro->setId($arrayMembro["id"]);
        $membro->setNome($arrayMembro["nome"]);
        $membro->setRua($arrayMembro["rua"]);
        $membro->setBairro($arrayMembro["bairro"]);
        $membro->setComplemento($arrayMembro["complemento"]);
        
        $estado->setId($arrayMembro["idEstado"]);
        $estado->setNome($arrayMembro["nomeEstado"]);
        $estado->setSigla($arrayMembro["sigla"]);
        $cidade->setId($arrayMembro["idCidade"]);
        $cidade->setNome($arrayMembro["nomeCidade"]);
        $cidade->setEstado($estado);
        $membro->setCidade($cidade);
        
        $membro->setFone($arrayMembro["fone"]);
        $membro->setDataNascimento($arrayMembro["data_nascimento"]);
        $membro->setSexo($arrayMembro["sexo"]);
        $membro->setEstadoCivil($arrayMembro["estado_civil"]);
        $membro->setNaturalidade($arrayMembro["naturalidade"]);
        $membro->setRg($arrayMembro["rg"]);
        $membro->setCpf($arrayMembro["cpf"]);
        
        $congregacao->setId($arrayMembro["id_congregacao"]);
        $membro->setCongregacao($congregacao);

        $funcao->setId($arrayMembro["id_funcao"]);
        $membro->setFuncao($funcao);

        $membro->setCargo($arrayMembro["id_cargo"]);
        $membro->setDataBatismo($arrayMembro["data_batismo"]);
        $membro->setSenha($arrayMembro["senha"]);
        $membro->setSituacao($arrayMembro["situacao"]);
        
        $usuarioCadastro->setId($arrayMembro["id_usuario_cadastro"]);
        $membro->setUsuarioCadastro($usuarioCadastro);

        $membro->setDataCadastro($arrayMembro["dtCadastro"]);
        
        return $membro;
    }

    /**
     * Retorna os dados de um membro pelo CPF.
     * @param string $cpf
     * @return array
     */
    public function getMembroPorCPF($cpf) {
        $query = "SELECT a.*, b.nome AS nomeCongregacao, c.nome AS nomeFuncao 
                  FROM membro AS a, congregacao AS b, funcao AS c
                  WHERE a.id_congregacao = b.id
                  AND a.id_funcao = c.id
                  AND a.cpf = '$cpf'";
        $resultado = $this->connection->executaQuery($query);
        return $this->connection->getRegistros($resultado);
    }

    /**
     * Retorna um usuario.
     * @param array $usuario
     * @return array
     */
    public function getUsuario($usuario) {
        $this->connection = new ConnectionMySql();
        $cpf = $usuario["cpf"];

        $statement = $this->connection->open()->prepare("SELECT a.id, a.nome, a.id_cargo AS idCargo FROM membro AS a WHERE a.cpf = ?");
        $statement->bind_param("s", $cpf);
        $statement->execute();
        $arrayUsuario = $statement->get_result()->fetch_assoc();
        
        $statement->close();
        $this->connection->close();

        return $arrayUsuario;
    }

    /**
     * Cadastra membro.
     * @param Membro $membro
     * @return boolean
     */
    public function cadastrar($membro) {
        $this->connection = new ConnectionMySql();
        $nome = $membro->getNome();
        $rua = $membro->getRua();
        $bairro = $membro->getBairro();
        $complemento = $membro->getComplemento();
        $idCidade = $membro->getCidade()->getId();
        $fone = $membro->getFone();
        $dataNascimento = $membro->getDataNascimento();
        $sexo = $membro->getSexo();
        $estadoCivil = $membro->getEstadoCivil();
        $naturalidade = $membro->getNaturalidade();
        $rg = $membro->getRg();
        $cpf = $membro->getCpf();

        $idCongregacao = $membro->getCongregacao()->getId();
        $situacao = $membro->getSituacao(); // INICIALIZA COM STRING VAZIA
        if (!empty($idCongregacao)) { // MEMBRO
            $situacao = Membro::LIGADO;
        } else { // NAO MEMBRO
            $situacao = Membro::NAO_MEMBRO;
        }
        
        $idFuncao = $membro->getFuncao()->getId();
        $dataBatismo = $membro->getDataBatismo();
        $idUsuarioCadastro = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("INSERT INTO membro (nome, rua, bairro, complemento, id_cidade, fone, 
                                                    data_nascimento, sexo, estado_civil, naturalidade, rg, cpf, id_congregacao, id_funcao, 
                                                    data_batismo, situacao, id_usuario_cadastro) 
                                                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");                 
        $statement->bind_param("ssssisssssssiissi", $nome, $rua, $bairro, $complemento, $idCidade, $fone, $dataNascimento, $sexo, 
                            $estadoCivil, $naturalidade, $rg, $cpf, $idCongregacao, $idFuncao, $dataBatismo, $situacao, $idUsuarioCadastro);
        
        $cadastrou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($cadastrou) {
            return true;
        }
        return false;
    }

    /**
     * Edita membro.
     * @param Membro $membro
     * @return boolean
     */
    public function editar($membro) {
        $this->connection = new ConnectionMySql();
        $id = $membro->getId();
        $nome = $membro->getNome();
        $rua = $membro->getRua();
        $bairro = $membro->getBairro();
        $complemento = $membro->getComplemento();
        $idCidade = $membro->getCidade()->getId();
        $fone = $membro->getFone();
        $dataNascimento = $membro->getDataNascimento();
        $sexo = $membro->getSexo();
        $estadoCivil = $membro->getEstadoCivil();
        $naturalidade = $membro->getNaturalidade();
        $rg = $membro->getRg();
        $cpf = $membro->getCpf();
        
        $idCongregacao = $membro->getCongregacao()->getId();
        $situacao = $membro->getSituacao(); // INICIALIZA COM A SITUACAO
        if (!empty($idCongregacao)) { // MEMBRO
            $situacao = Membro::LIGADO;
        } elseif ($situacao === Membro::DESLIGADO) { // DESLIGADO
            $situacao = Membro::DESLIGADO;
        } else { // NAO MEMBRO
            $situacao = Membro::NAO_MEMBRO;
        }
        
        $idFuncao = $membro->getFuncao()->getId();
        $dataBatismo = $membro->getDataBatismo();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("UPDATE membro AS a
                                                    SET a.nome = ?, a.rua = ?, a.bairro = ?, a.complemento = ?, a.id_cidade = ?, a.fone = ?, 
                                                        a.data_nascimento = ?, a.sexo = ?, a.estado_civil = ?, a.naturalidade = ?,
                                                        a.rg = ?, a.cpf = ?, a.id_congregacao = ?, a.id_funcao = ?, a.data_batismo = ?, 
                                                        a.situacao = ?, a.id_usuario_cadastro = ?, a.data_cadastro = CURRENT_TIMESTAMP 
                                                    WHERE a.id = ?");
        $statement->bind_param("ssssisssssssiissii", $nome, $rua, $bairro, $complemento, $idCidade, $fone, $dataNascimento, $sexo,
                                $estadoCivil, $naturalidade, $rg, $cpf, $idCongregacao, $idFuncao, $dataBatismo, $situacao, $idUsuario, $id);
        $editou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($editou) {
            return true;
        }
        return false;
    }

    /**
     * Altera a senha do usuario.
     * @param string $senha
     * @return boolean
     */
    public function alterarSenha($senha) {
        $this->connection = new ConnectionMySql();
        $idUsuario = $_SESSION["usuario"]["id"];

        $statement = $this->connection->open()->prepare("UPDATE membro SET senha = ? WHERE id = ?");
        $statement->bind_param("si", $senha, $idUsuario);
        $alterou = $statement->execute();
        $statement->close();
        $this->connection->close();

        if ($alterou) {
            return true;
        }
        return false;
    }

    /**
     * Verifica se o membro cadastrado eh um usuario.
     * @param int $idUsuario
     * @return boolean
     */
    public function isUsuario($idUsuario) {
        $this->connection = new ConnectionMySql();

        $statement = $this->connection->open()->prepare("SELECT a.id_cargo FROM membro AS a 
                                                    WHERE a.id = ? AND a.id_cargo != 0 OR a.id_cargo = NULL");
        $statement->bind_param("i", $idUsuario);
        $statement->execute();

        $usuario = $statement->get_result()->fetch_assoc();
        
        if (!empty($usuario)) {
            return true;
        }
        return false;
    }

    /**
     * Adiciona um usuario.
     * @param Membro $membro
     * @return boolean
     */
    public function adicionarUsuario($membro) {
        $this->connection = new ConnectionMySql();
        $idCargo = $membro->getCargo();
        $senha = $membro->getSenha();
        $idMembro = $membro->getId();

        $statement = $this->connection->open()->prepare("UPDATE membro SET id_cargo = ?, senha = ?, data_cadastro = CURRENT_TIMESTAMP 
                                                    WHERE id = ?");
        $statement->bind_param("isi", $idCargo, $senha, $idMembro);
        $adicionou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($adicionou) {
            return true;
        }
        return false;
    }

    /**
     * Edita um usuario.
     * @param Membro $membro
     * @return boolean
     */
    public function editarUsuario($membro) {
        $this->connection = new ConnectionMySql();
        $idCargo = $membro->getCargo();
        $idMembro = $membro->getId();

        $statement = $this->connection->open()->prepare("UPDATE membro SET id_cargo = ?, data_cadastro = CURRENT_TIMESTAMP WHERE id = ?");
        $statement->bind_param("ii", $idCargo, $idMembro);
        $editou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($editou) {
            return true;
        }
        return false;
    }

    /**
     * Desativa um usuario.
     * @param Membro $membro
     * @return boolean
     */
    public function desativarUsuario($membro) {
        $this->connection = new ConnectionMySql();
        $idMembro = $membro->getId();

        $statement = $this->connection->open()->prepare("UPDATE membro SET id_cargo = NULL, senha = NULL, data_cadastro = CURRENT_TIMESTAMP 
                                                    WHERE id = ?");
        $statement->bind_param("i", $idMembro);
        $editou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($editou) {
            return true;
        }
        return false;
    }

    /**
     * Desliga um membro.
     * @param Membro $membro
     * @return boolean
     */
    public function desligar($membro) {
        $this->connection = new ConnectionMySql();
        $situacao = $membro->getSituacao();
        $idMembro = $membro->getId();

        $statement = $this->connection->open()->prepare("UPDATE membro SET id_congregacao = 0, situacao = ?, data_cadastro = CURRENT_TIMESTAMP 
                                                    WHERE id = ?");
        $statement->bind_param("si", $situacao, $idMembro);
        $desligou = $statement->execute();
        
        $statement->close();
        $this->connection->close();

        if ($desligou) {
            return true;
        }
        return false;
    }
}