<?

class ModuloAcesso 
{
    private $id;
    /**
     *
     * @var Modulo 
     */
    private $modulo;
    /**
     *
     * @var Cargo 
     */
    private $cargo;
    /**
     *
     * @var Membro 
     */
    private $usuarioCadastro;
    private $dataCadastro;

    public function ModuloAcesso() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getModulo() {
        return $this->modulo;
    }

    public function getCargo() {
        return $this->cargo;
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

    public function setModulo(Modulo $modulo) {
        $this->modulo = $modulo;
    }

    public function setCargo(Cargo $cargo) {
        $this->cargo = $cargo;
    }

    public function setUsuarioCadastro(Membro $usuarioCadastro) {
        $this->usuarioCadastro = $usuarioCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
}