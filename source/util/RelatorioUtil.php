<?

class RelatorioUtil 
{
    const RELATORIO_MEMBRO = 1;
    const RELATORIO_CONGREGACAO = 2;
    const RELATORIO_OFERTA_VOLUNTARIA = 3;
    const OPCAO_OFERTAS_VOLUNTARIAS = 1;
    const OPCAO_OFERTAS = 2;
    const OPCAO_DIZIMOS = 3;
    const OPCAO_DESPESAS = 4;
    const OPCAO_RECEITAS = 5;
    const OPCAO_TODAS_AS_OPCOES = 6;

    /**
     * Retorna um 'select' com as opcoes da congregacao.
     * @return string
     */
    public static function getSelectOpcoesCongregacao() {
        $conteudo = "";
        $conteudo .= "<select name='selectOpcoesCongregacao' id='selectOpcoesCongregacao'>";
        $conteudo .= "<option value=''>SELECIONE UMA OPÇÃO</option>";
        $conteudo .= "<option value='" . self::OPCAO_OFERTAS_VOLUNTARIAS . "'>OFERTAS VOLUNTÁRIAS</option>";
        $conteudo .= "<option value='" . self::OPCAO_OFERTAS . "'>OFERTAS</option>";
        $conteudo .= "<option value='" . self::OPCAO_DIZIMOS . "'>DIZIMOS</option>";
        $conteudo .= "<option value='" . self::OPCAO_DESPESAS . "'>DESPESAS</option>";
        $conteudo .= "<option value='" . self::OPCAO_RECEITAS . "'>RECEITAS</option>";
        $conteudo .= "<option value='" . self::OPCAO_TODAS_AS_OPCOES . "'>TODAS AS OPÇÕES</option>";
        $conteudo .= "</select>";
        return $conteudo;
    }

    /**
     * Retorna o tipo de relatorio.
     * @param int $tipo
     * @return string
     */
    public static function getOpcaoCongregacao($tipo) {
        $opcao = "";

        switch ($tipo) {
            case self::OPCAO_OFERTAS:
                $opcao = "OFERTAS";
                break;
            case self::OPCAO_DIZIMOS:
                $opcao = "DÍZIMOS";
                break;
            case self::OPCAO_DESPESAS:
                $opcao = "DESPESAS";
                break;
            case self::OPCAO_RECEITAS:
                $opcao = "RECEITAS";
                break;
            case self::OPCAO_TODAS_AS_OPCOES:
                $opcao = "OFERTAS VOLUNTÁRIAS - OFERTAS - DÍZIMOS - DESPESAS - RECEITAS";
                break;
            default:
                $opcao = "";
                break;
        }
        return $opcao;
    }
}