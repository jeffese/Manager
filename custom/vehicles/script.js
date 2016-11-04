// JavaScript Document

function assetType(typ) {
    if (typ == 3) {
        $("#particulars").hide();
        $("#eqp").hide();
    } else {
        $("#particulars").show();
        $("#eqp").show();
    }
}

function designate(idx) {
    for (var i=0; i<8; i++) {
        $("#desg"+i).hide();
    }
    $("#desg"+idx).show();
    $("#occupant").val($("#desg"+idx).val());
}

function setdesigee(val) {
    $("#occupant").val(val);
}