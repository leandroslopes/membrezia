<?

class CongregacaoCulto 
{
    private $id;

    /**
     *
     * @var Congregacao 
     */
    private $congregacao;

    /**
     *
     * @var Culto 
     */
    private $culto;

    /**
     *
     * @var Membro 
     */
    private $usuarioCadastro;
    private $dataCadastro;

    public function getId() {
        return $this->id;
    }

    public function getCongregacao() {
        return $this->congregacao;
    }

    public function getCulto() {
        return $this->culto;
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

    public function setCongregacao(Congregacao $congregacao) {
        $this->congregacao = $congregacao;
    }

    public function setCulto(Culto $culto) {
        $this->culto = $culto;
    }

    public function setUsuarioCadastro(Membro $usuarioCadastro) {
        $this->usuarioCadastro = $usuarioCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
}