
function delSerial() {
    $("#lst option:selected").remove();
    collateStaff();
}

function addSerial() {
    var id = $("#staff").val(), pass = true;
    $("#lst > option").each(function() {
        if (this.value == id) {
            alert('"' + this.text + '" is already in the list!');
            pass = false;
        }
    });
    if (pass) {
        $("#lst").append($("#staff option:selected").clone());
        collateStaff();
    }
    
}

function collateStaff() {
    var lst = "";
    $("#lst > option").each(function() {
        lst += "," + this.value;
    });
    $("#guests").val(lst);
}
