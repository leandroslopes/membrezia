<?

class DataUtil 
{
    const DATA_USA = 1;
    const DATA_BRA = 2;
    const JANEIRO = 1;
    const FEVEREIRO = 2;
    const MARCO = 3;
    const ABRIL = 4;
    const MAIO = 5;
    const JUNHO = 6;
    const JULHO = 7;
    const AGOSTO = 8;
    const SETEMBRO = 9;
    const OUTUBRO = 10;
    const NOVEMBRO = 11;
    const DEZEMBRO = 12;

    /**
     * Retorna o nome do dia.
     * @param int $diaSemana
     * @return string
     */
    public static function getNomeDia($diaSemana) {
        $nomeDia = "";

        switch ($diaSemana) {
            case 1:
                $nomeDia = "Segunda";
                break;
            case 2:
                $nomeDia = "Terça";
                break;
            case 3:
                $nomeDia = "Quarta";
                break;
            case 4:
                $nomeDia = "Quinta";
                break;
            case 5:
                $nomeDia = "Sexta";
                break;
            case 6:
                $nomeDia = "Sábado";
                break;
            case 0:
                $nomeDia = "Domingo";
                break;
        }

        return $nomeDia;
    }

    /**
     * Retorna o nome do mes.
     * @param int $mes
     * @return string
     */
    public static function getNomeMes($mes) {
        $nomeMes = "";

        switch ($mes) {
            case 1:
                $nomeMes = "Janeiro";
                break;
            case 2:
                $nomeMes = "Fevereiro";
                break;
            case 3:
                $nomeMes = "Março";
                break;
            case 4:
                $nomeMes = "Abril";
                break;
            case 5:
                $nomeMes = "Maio";
                break;
            case 6:
                $nomeMes = "Junho";
                break;
            case 7:
                $nomeMes = "Julho";
                break;
            case 8:
                $nomeMes = "Agosto";
                break;
            case 9:
                $nomeMes = "Setembro";
                break;
            case 10:
                $nomeMes = "Outubro";
                break;
            case 11:
                $nomeMes = "Novembro";
                break;
            case 12:
                $nomeMes = "Dezembro";
                break;
        }

        return $nomeMes;
    }

    /**
     * Imprimir a data atual.
     * @return string
     */
    public static function imprimirDataAtual() {
        return "Paço do Lumiar, " . date("d") . " de " . self::getNomeMes(date("n")) . " de " . date("Y");
    }

    /**
     * Formata uma data para um padra escolhido.
     * @param string $data - Data a ser formatada.
     * @param int $formato - Formato da data. DATA_USA ou DATA_BRA
     * @return string
     */
    public static function formatar($data, $formato) {
        if ($formato == self::DATA_USA) {
            $dataFormatada = substr($data, 6, 4) . "-" . substr($data, 3, 2) . "-" . substr($data, 0, 2);
        } else {
            $dataFormatada = substr($data, 8, 2) . "-" . substr($data, 5, 2) . "-" . substr($data, 0, 4);
        }
        return $dataFormatada;
    }

    /**
     * Retorna a data de hoje.
     * @return string
     */
    public static function getHoje() {
        return date("d/m/Y");
    }

    /**
     * Retorna o ano corrente.
     * @return string
     */
    public static function getAnoCorrente() {
        return date("Y");
    }

    /**
     * Retorna um 'select' de meses.
     * @param $mes
     * @return string
     */
    public static function getSelectMes($mes = 0) {
        $conteudo = "";
        $janeiro = "";
        $fevereiro = "";
        $marco = "";
        $abril = "";
        $maio = "";
        $junho = "";
        $julho = "";
        $agosto = "";
        $setembro = "";
        $outubro = "";
        $novembro = "";
        $dezembro = "";
        
        switch ($mes) {
            case self::JANEIRO:
                $janeiro = "selected";
                break;
            case self::FEVEREIRO:
                $fevereiro = "selected";
                break;
            case self::MARCO:
                $marco = "selected";
                break;
            case self::ABRIL:
                $abril = "selected";
                break;
            case self::MAIO:
                $maio = "selected";
                break;
            case self::JUNHO:
                $junho = "selected";
                break;
            case self::JULHO:
                $julho = "selected";
                break;
            case self::AGOSTO:
                $agosto = "selected";
                break;
            case self::SETEMBRO:
                $setembro = "selected";
                break;
            case self::OUTUBRO:
                $outubro = "selected'";
                break;
            case self::NOVEMBRO:
                $novembro = "selected";
                break;
            case self::DEZEMBRO:
                $dezembro = "selected";
                break;
        }

        $conteudo .= "<select name='mes' id='mes'>";
        $conteudo .= "<option value='01' $janeiro>JANEIRO</option>";
        $conteudo .= "<option value='02' $fevereiro>FEVEREIRO</option>";
        $conteudo .= "<option value='03' $marco>MARÇO</option>";
        $conteudo .= "<option value='04' $abril>ABRIL</option>";
        $conteudo .= "<option value='05' $maio>MAIO</option>";
        $conteudo .= "<option value='06' $junho>JUNHO</option>";
        $conteudo .= "<option value='07' $julho>JULHO</option>";
        $conteudo .= "<option value='08' $agosto>AGOSTO</option>";
        $conteudo .= "<option value='09' $setembro>SETEMBRO</option>";
        $conteudo .= "<option value='10' $outubro>OUTUBRO</option>";
        $conteudo .= "<option value='11' $novembro>NOVEMBRO</option>";
        $conteudo .= "<option value='12' $dezembro>DEZEMBRO</option>";
        $conteudo .= "</select>";

        return $conteudo;
    }

    /**
     * Retorna um 'select' com os anos.
     * @return string
     */
    public static function getSelectAno() {
        $conteudo = "";
        $selecionado = "";

        $anoInicio = 2000;
        $anoVigente = date('Y');
        $anoVigente = (int) $anoVigente;

        $conteudo .= "<select name='ano' id='ano'>";
        for ($index = $anoInicio; $index <= $anoVigente; $index++) {    
            $index === $anoVigente ? $selecionado = "selected" : $selecionado = "";
            $conteudo .= "<option value='$index' $selecionado>$index</option>";
        }
        $conteudo .= "</select>";

        return $conteudo;
    }
}