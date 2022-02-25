$(function() {
    $("#tabela").tablesorter({
        widgets: ['zebra'],
        headers: {
            0: {sorter: false},
            1: {sorter: false},
            2: {sorter: false},
            3: {sorter: false},
            4: {sorter: false},
            5: {sorter: false},
            6: {sorter: false},
	    7: {sorter: false}
        }
    });
    $("#tabela").tablesorterPager({
        container: $("#paginacao")
    });
});