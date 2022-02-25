<?

class NumeroUtil 
{
    const NUMERO_USA = 1;
    const NUMERO_BRA = 2;

    /**
     * Formata um numero decimal para um padrao escolhido.
     * @param string $numero
     * @param int $formato
     * @return string
     */
    public static function formatar($numero, $formato) {
        $numeroFormatado = "";
        
        if (!empty($numero)) {
            if ($formato == self::NUMERO_USA) {
                $source = array('.', ',');
                $replace = array('', '.');
                $numeroFormatado = str_replace($source, $replace, $numero);
            } else {
                $numeroFormatado = number_format($numero, 2, ',', '.');
            }
        }
        
        return $numeroFormatado;
    }

    /**
     * Multiplica dois numeros.
     * @param double $valor1
     * @param double $valor2
     * @return string
     */
    public static function multiplicar($valor1, $valor2) {
        $total = 0;
        if (!empty($valor1) && !empty($valor1)) {
            $total = $valor1 * $valor2;
            return $total;
        } else {
            return "0.00";
        }
    }

    /**
     * Calcula a porcentagem.
     * @param double $valor
     * @param double $total
     * @return double
     */
    public static function porcentagem($valor, $total) {
        return self::formatar((($valor / $total) * 100), NumeroUtil::NUMERO_BRA);
    }

    /**
     * Coloca zeros a esquerda de um numero.
     * @param double $numero
     * @param int $zeros
     * @return string
     */
    public static function setZeroEsquerda($numero, $zeros) {
        return str_pad($numero, $zeros, 0, STR_PAD_LEFT);
    }
}