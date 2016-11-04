// JavaScript Document

function clientype(idx) {
    for (var i=0; i<8; i++) {
        $("#desg"+i).hide();
    }
    $("#desg"+idx).show();
    setclient(idx);
}

function setclient(idx) {
    $("#VendorID").val($("#desg"+idx).val());
    $("#Recipient").val($("#desg"+idx+" option:selected").text());
    $("#currency").val($("#desg"+idx+" option:selected").attr("currency"));
}

function paytype(idx) {
    $("#pay21").hide();
    $("#pay23").hide();
    $("#pay25").hide();
    $("#pay"+idx).show();
    setpaydet(idx);
}

function setpaydet(idx) {
    var ids = ['PaymentMethod', 'AccountName', 'AccountNumber', 'CheckNumber', 'CheckDate'];
    for (var i=0; i<5; i++) {
        if ($("#"+ids[i]+idx).length>0)
            $("input[name="+ids[i]+"]").val($("#"+ids[i]+idx).val());
        else
            $("#"+ids[i]).val('');
    }
}

function Post() {
    if (confirm("Are you sure you want to Post this Expense?"))
        $("#frmpost").submit();
}
