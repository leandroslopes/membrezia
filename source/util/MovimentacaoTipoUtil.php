<?

class MovimentacaoTipoUtil 
{
    const DESPESA = "DE";
    const DIZIMO = "DI";
    const OFERTA = "OF";
    const RECEITA = "RE";

    /**
     * Retorna o nome do tipo.
     * @param string sigla
     * @return string
     */
    public static function getNomeTipo($sigla) {
        switch ($sigla) {
            case self::DESPESA:
                return "DESPESA";
            case self::DIZIMO:
                return "DIZIMO";
            case self::OFERTA:
                return "OFERTA";
            case self::RECEITA:
                return "RECEITA";
        }
    }

    /**
     * Retorna um 'select' com os tipos.
     * @param string $sigla
     * @return string
     */
    public static function getSelect($sigla = "") {
        $conteudoHtml = "";
        $selecione = "";
        $despesa = "";
        $dizimo = "";
        $oferta = "";
        $receita = "";

        switch ($sigla) {
            case "":
                $selecione = "selected";
                break;
            case self::DESPESA:
                $despesa = "selected";
                break;
            case self::DIZIMO:
                $dizimo = "selected";
                break;
            case self::OFERTA:
                $oferta = "selected";
                break;
            case self::RECEITA:
                $receita = "selected";
                break;
        }

        $conteudoHtml .= "<select name='movimentacaoTipo' id='movimentacaoTipo' >";
        $conteudoHtml .= "<option value='' $selecione>SELECIONE O TIPO DE MOVIMENTAÇÃO</option>";
        $conteudoHtml .= "<option value='" . self::DESPESA . "' $despesa>" . self::getNomeTipo(self::DESPESA) . "</option>";
        $conteudoHtml .= "<option value='" . self::DIZIMO . "' $dizimo>" . self::getNomeTipo(self::DIZIMO) . "</option>";
        $conteudoHtml .= "<option value='" . self::OFERTA . "' $oferta>" . self::getNomeTipo(self::OFERTA) . "</option>";
        $conteudoHtml .= "<option value='" . self::RECEITA . "' $receita>" . self::getNomeTipo(self::RECEITA) . "</option>";
        $conteudoHtml .= "</select>";
        return $conteudoHtml;
    }
}