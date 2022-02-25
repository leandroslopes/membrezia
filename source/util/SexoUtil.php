<?

class SexoUtil 
{
    const MASCULINO = 'M';
    const FEMININO = 'F';

    /**
     * Retorna o nome do sexo.
     * @param string $sexo
     * @return string
     */
    public static function getNomeSexo($sexo) {
        switch ($sexo) {
            case self::MASCULINO:
                return "MASCULINO";
            case self::FEMININO:
                return "FEMININO";
        }
    }

    /**
     * Retorna um 'select' com os sexos.
     * @param string $sexo
     * @return string
     */
    public static function getSelect($sexo = '') {
        $conteudoHtml = "";
        $selecione = "";
        $masculino = "";
        $feminino = "";

        switch ($sexo) {
            case '':
                $selecione = "selected";
                break;
            case self::MASCULINO:
                $masculino = "selected";
                break;
            case self::FEMININO:
                $feminino = "selected";
                break;
        }

        $conteudoHtml .= "<select name='sexo' id='sexo' >";
        $conteudoHtml .= "<option value='' $selecione>SELECIONE O SEXO</option>";
        $conteudoHtml .= "<option value='" . self::MASCULINO . "' $masculino>" . self::getNomeSexo(self::MASCULINO) . "</option>";
        $conteudoHtml .= "<option value='" . self::FEMININO . "' $feminino>" . self::getNomeSexo(self::FEMININO) . "</option>";
        $conteudoHtml .= "</select>";
        
        return $conteudoHtml;
    }
}