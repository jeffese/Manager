// JavaScript Document

function clientype(idx) {
    for (var i=0; i<8; i++) {
        $("#desg"+i).hide();
    }
    $("#desg"+idx).show();
    setclient(idx);
}

function isPayable() {
    if ($("#payable").is(":checked")) {
        $("#entrytype_1").click();
        $("#tabentry").hide();
        $("#InvoiceID").show();
        $("#titleEntry").html("Invoice No.:");
    } else {
        $("#InvoiceID").hide();
        $("#tabentry").show();
        $("#titleEntry").html("Entry Type:");
    }
}

function setclient(idx) {
    $("#VendorID").val($("#desg"+idx).val());
    $("#CustomerName").val($("#desg"+idx+" option:selected").text());
    $("#currency").val($("#desg"+idx+" option:selected").attr("currency"));
}

function Post() {
    if (confirm("Are you sure you want to Post this Journal Entry?"))
        $("#frmpost").submit();
}
