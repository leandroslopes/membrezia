<?

class TableUtil 
{
    /**
     * Cria o topo da tabela que contem o id 'tabela e class 'tablesorter'.  
     * @param array $arrayCabecalho
     * @return string
     */
    public static function criarTopo($arrayCabecalho) {
        $conteudoHtml = "";

        $conteudoHtml .= "<table id='tabela' class='tablesorter'>";
        $conteudoHtml .= "<thead>";
        $conteudoHtml .= "<tr>";

        foreach ($arrayCabecalho as $value) {
            $conteudoHtml .= "<th>$value</th>";
        }

        $conteudoHtml .= "</tr>";
        $conteudoHtml.= "</thead>";

        return $conteudoHtml;
    }

    /**
     * Cria o rodape da tabela com a paginacao.
     * @param int $colspan
     * @return string
     */
    public static function criarRodape($colspan) {
        $conteudoHtml = "";

        $conteudoHtml .= "<tfoot> <tr> <th colspan='$colspan'>";
        $conteudoHtml .= "<div id='paginacao' class='paginacao'> <form action=''>";
        $conteudoHtml .= "<img src='../../public/js/jquery-tablesorter-2.0.5b/addons/pager/icons/first.png' alt='' class='first'/>";
        $conteudoHtml .= "<img src='../../public/js/jquery-tablesorter-2.0.5b/addons/pager/icons/prev.png' alt='' class='prev'/>";
        $conteudoHtml .= "<input type='text' class='pagedisplay' size='5'/>";
        $conteudoHtml .= "<img src='../../public/js/jquery-tablesorter-2.0.5b/addons/pager/icons/next.png' alt='' class='next'/>";
        $conteudoHtml .= "<img src='../../public/js/jquery-tablesorter-2.0.5b/addons/pager/icons/last.png' alt='' class='last'/>";
        $conteudoHtml .= "<select class='pagesize'>";
        $conteudoHtml .= "<option selected='selected' value='10'>10</option>";
        $conteudoHtml .= "<option value='20'>20</option>";
        $conteudoHtml .= "</select> </form> </div> </th> </tr> </tfoot> </table>";

        return $conteudoHtml;
    }

    /**
     * Cria uma <tr> com <td> vazias. 
     * @param int $qtdColunas
     * @return string
     */
    public static function criarConteudoVazio($qtdColunas) {
        $conteudoHtml = "";

        $conteudoHtml .= "<tbody>";
        $conteudoHtml .= "<tr class='textoCentro'>";
        for ($index = 1; $index <= $qtdColunas; $index++) {
            $conteudoHtml .= "<td>--</td>";
        }
        $conteudoHtml .= "</tr></tbody></table>";

        return $conteudoHtml;
    }

    /**
     * Cria o topo do relatorio.
     * @param array $arrayTitulo
     * @return string
     */
    public static function criarTopoRelatorio($arrayTitulo) {
        $conteudoHtml = "";

        $conteudoHtml .= "<table id='tblImprimirTitulo'>";
        $conteudoHtml .= "<thead class='negrito'>";

        foreach ($arrayTitulo as $value) {
            $conteudoHtml .= "<tr><th>$value</th></tr>";
        }

        $conteudoHtml .= "</thead>";
        $conteudoHtml .= "</table>";

        return $conteudoHtml;
    }

    /**
     * Cria o cabecalho do conteudo do relatorio.
     * @param array $arrayCabecalho
     * @return string
     */
    public static function criarConteudoRelatorio($arrayCabecalho) {
        $conteudoHtml = "";

        $conteudoHtml .= "<table id='tblImprimirConteudo'> ";
        $conteudoHtml .= "<thead>";
        $conteudoHtml .= "<tr class='negrito'>";

        foreach ($arrayCabecalho as $value) {
            $conteudoHtml .= "<th>$value</th>";
        }

        $conteudoHtml .= "</tr>";
        $conteudoHtml .= "</thead>";

        return $conteudoHtml;
    }
}