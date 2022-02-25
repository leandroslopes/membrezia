<?

class Culto 
{
    private $id;
    private $nome;

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

    public function setUsuarioCadastro(Membro $usuarioCadastro) {
        $this->usuarioCadastro = $usuarioCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
}