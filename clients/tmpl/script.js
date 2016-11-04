// JavaScript Document

function clientType(typ, vt) {
    $("#perstab").hide();
    $("#pers").hide();
    $("#coytab").show();
    $("#coy").show();
    if (typ == 1) {
        $("#coytab").hide();
        if (vt != 4) {
            $("#coy").hide();
            $("#perstab").show();
            $("#pers").show();
        }
    }
}
