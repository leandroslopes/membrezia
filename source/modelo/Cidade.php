<?

class Cidade 
{
    private $id;
    private $nome;
    private $cep;

    /**
     * @var Estado
     */
    private $estado;

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getCEP() {
        return $this->cep;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setCEP($cep) {
        $this->cep = $cep;
    }

    public function setEstado(Estado $estado) {
        $this->estado = $estado;
    }
}