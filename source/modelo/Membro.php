<?

class Membro {

    const LIGADO = 'L';
    const DESLIGADO = 'D';
    const NAO_MEMBRO = 'N';

    private $id;
    private $nome;
    private $rua;
    private $bairro;
    private $complemento;
    
    /**
     * @var Cidade
     */
    private $cidade;
    private $fone;
    private $dataNascimento;
    private $sexo;
    private $estadoCivil;
    private $naturalidade;
    private $rg;
    private $cpf;
    /**
     * @var Congregacao 
     */
    private $congregacao;
    /**
     *
     * @var Funcao 
     */
    private $funcao; 
    private $cargo;
    private $dataBatismo;
    private $acesso;
    private $senha;
    private $situacao;
    /**
     *
     * @var Membro 
     */
    private $usuarioCadastro;
    private $dataCadastro;

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getRua() {
        return $this->rua;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function getComplemento() {
        return $this->complemento;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function getFone() {
        return $this->fone;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function getEstadoCivil() {
        return $this->estadoCivil;
    }

    public function getNaturalidade() {
        return $this->naturalidade;
    }

    public function getRg() {
        return $this->rg;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getCongregacao() {
        return $this->congregacao;
    }

    public function getFuncao() {
        return $this->funcao;
    }

    public function getCargo() {
        return $this->cargo;
    }

    public function getDataBatismo() {
        return $this->dataBatismo;
    }

    public function getAcesso() {
        return $this->acesso;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getSituacao() {
        return $this->situacao;
    }

    public function getNomeSituacao() {
        $nomeSituacao = "";

        if ($this->situacao === self::LIGADO) {
            $nomeSituacao = "LIGADO";
        } else if ($this->situacao === self::DESLIGADO) {
            $nomeSituacao = "DESLIGADO";
        } else {
            $nomeSituacao = "NAO MEMBRO";
        }
        
        return $nomeSituacao;
    }

    public function getUsuarioCadastro() {
        return $this->usuarioCadastro;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setRua($rua) {
        $this->rua = $rua;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    public function setCidade(Cidade $cidade) {
        $this->cidade = $cidade;
    }

    public function setFone($fone) {
        $this->fone = $fone;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function setEstadoCivil($estadoCivil) {
        $this->estadoCivil = $estadoCivil;
    }

    public function setNaturalidade($naturalidade) {
        $this->naturalidade = $naturalidade;
    }

    public function setRg($rg) {
        $this->rg = $rg;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function setCongregacao(Congregacao $congregacao) {
        $this->congregacao = $congregacao;
    }

    public function setFuncao(Funcao $funcao) {
        $this->funcao = $funcao;
    }

    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    public function setDataBatismo($dataBatismo) {
        $this->dataBatismo = $dataBatismo;
    }

    public function setAcesso($acesso) {
        $this->acesso = $acesso;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    public function setUsuarioCadastro(Membro $usuarioCadastro) {
        $this->usuarioCadastro = $usuarioCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    /**
     * Verifica se o usuário estah logado.
     * @param string $url 
     */
    public function estaLogado($url = "") {
        if (!isset($_SESSION["logado"])) {
            if ($url == "") {
                header("Location: index.php");
            }
        } else {
            if ($url == "index") {
                header("Location: inicio.php");
            }
        }
    }

}

?>