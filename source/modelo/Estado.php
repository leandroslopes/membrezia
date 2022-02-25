<?

class Estado 
{
    private $id;
    private $sigla;
    private $nome;

    public function getId() {
        return $this->id;
    }

    public function getSigla() {
        return $this->sigla;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setSigla($sigla) {
        $this->sigla = $sigla;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
}