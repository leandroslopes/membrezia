$(function() {
    $(".maskDate").mask("99/99/9999");
    $(".maskFone").mask("99 99999-9999");
    $(".maskDecimal").maskMoney({
        decimal: ",",
        thousands: "."
    });
});