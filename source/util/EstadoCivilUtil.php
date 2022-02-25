<?

class EstadoCivilUtil 
{
    const SOLTEIRO = 'S';
    const CASADO = 'C';
    const VIUVO = 'V';
    const DIVORCIADO = 'D';

    /**
     * Retorna o nome estado civil.
     * @param string $incial
     * @return string
     */
    public static function getNomeEstadoCivil($inicial) {
        switch ($inicial) {
            case self::SOLTEIRO:
                return "SOLTEIRO(A)";
            case self::CASADO:
                return "CASADO(A)";
            case self::VIUVO:
                return "VIUVO(A)";
            case self::DIVORCIADO:
                return "DIVORCIADO(A)";
        }
    }

    /**
     * Retorna um 'select' com os estados civis.
     * @param string $inicial
     * @return string
     */
    public static function getSelect($inicial = '') {
        $selecione = '';
        $solteiro = '';
        $casado = '';
        $viuvo = '';
        $divorciado = '';
        $conteudoHtml = "";
        
        switch ($inicial) {
            case '':
                $selecione = "selected";
                break; 
            case self::SOLTEIRO:
                $solteiro = "selected";
                break; 
            case self::CASADO:
                $casado = "selected";
                break; 
            case self::VIUVO:
                $viuvo = "selected";
                break; 
            case self::DIVORCIADO:
                $divorciado = "selected";
                break; 
        }

        $conteudoHtml .= "<select name='estadoCivil' id='estadoCivil'>";
        $conteudoHtml .= "<option value='' $selecione>SELECIONE O ESTADO CIVIL</option>";
        $conteudoHtml .= "<option value='" . self::SOLTEIRO . "' $solteiro>" . self::getNomeEstadoCivil(self::SOLTEIRO) . "</option>";
        $conteudoHtml .= "<option value='" . self::CASADO . "' $casado>" . self::getNomeEstadoCivil(self::CASADO) . "</option>";
        $conteudoHtml .= "<option value='" . self::VIUVO . "' $viuvo>" . self::getNomeEstadoCivil(self::VIUVO) . "</option>";
        $conteudoHtml .= "<option value='" . self::DIVORCIADO . "' $divorciado>" . self::getNomeEstadoCivil(self::DIVORCIADO) . "</option>";
        $conteudoHtml .= "</select>";
        
        return $conteudoHtml;
    }
}