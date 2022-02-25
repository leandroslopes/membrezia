<?

class CartaUtil 
{
    const RECOMENDACAO = 1;
    const MUDANCA = 2;

    /**
     * Retorna o nome da carta.
     * @param int $carta
     * @return string
     */
    public static function getNomeCarta($carta) {
        switch ($carta) {
            case self::RECOMENDACAO:
                return "CARTA DE RECOMENDAÇÃO";
            case self::MUDANCA:
                return "CARTA DE MUDANÇA";
        }
    }
}