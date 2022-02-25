<?

class CargoUtil 
{
    const SEM_CARGO = 0;
    const ADMINISTRADOR = 1;
    const SECRETARIA = 2;
    const TESOUREIRO = 3;

    /**
     * Retorna o nome do cargo.
     * @param int $idCargo
     * @return string
     */
    public static function getNome($idCargo) {
        switch ($idCargo) {
            case self::ADMINISTRADOR:
                return "ADMINISTRADOR";
            case self::SECRETARIA:
                return "SECRETARIA";
            case self::TESOUREIRO:
                return "TESOUREIRO";
        }
    }

    /**
     * Retorna um 'select' com os cargos.
     * @param int $idCargo
     * @param string $$nomeSelect
     * @return string
     */
    public static function getSelect($idCargo, $nomeSelect) {
        $conteudoHtml = "";
        $selecione = "";
        $administrador = "";
        $secretaria = "";
        $tesoureiro = "";

        switch ($idCargo) {
            case self::SEM_CARGO:
                $selecione = "selected";
                break;
            case self::ADMINISTRADOR:
                $administrador = "selected";
                break;
            case self::SECRETARIA:
                $secretaria = "selected";
                break;
            case self::TESOUREIRO:
                $tesoureiro = "selected";
                break;
        }

        $conteudoHtml .= "<select name='$nomeSelect' id='$nomeSelect'>";
        $conteudoHtml .= "<option value='' $selecione>SELECIONE O CARGO</option>";
        $conteudoHtml .= "<option value='" . self::ADMINISTRADOR . "' $administrador>" . self::getNome(self::ADMINISTRADOR) . "</option>";
        $conteudoHtml .= "<option value='" . self::SECRETARIA . "' $secretaria>" . self::getNome(self::SECRETARIA) . "</option>";
        $conteudoHtml .= "<option value='" . self::TESOUREIRO . "' $tesoureiro>" . self::getNome(self::TESOUREIRO) . "</option>";
        $conteudoHtml .= "</select>";
        return $conteudoHtml;
    }
}