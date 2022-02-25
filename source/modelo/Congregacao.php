<?

class Congregacao 
{
    const IS_PADRAO = 1;

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
    private $dataFundacao;
    private $isPadrao;
    /**
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

    public function getDataFundacao() {
        return $this->dataFundacao;
    }

    public function getIsPadrao() {
        return $this->isPadrao;
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

    public function setDataFundacao($dataFundacao) {
        $this->dataFundacao = $dataFundacao;
    }

    public function setIsPadrao($isPadrao) {
        $this->isPadrao = $isPadrao;
    }

    public function setUsuarioCadastro(Membro $usuarioCadastro) {
        $this->usuarioCadastro = $usuarioCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
}