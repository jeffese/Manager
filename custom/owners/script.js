// JavaScript Document

function clientType(typ, vt) {
    $("#perstab").hide();
    $("#pers").hide();
    $("#coytab").show();
    $("#coy").show();
    if (typ == 1 && vt != 4) {
        $("#coytab").hide();
        $("#perstab").show();
        $("#coy").hide();
        $("#pers").show();
    }
}