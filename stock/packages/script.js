// JavaScript Document
var itm;

function appendItm(id, name, price) {
    var row = '<tr id="itm_###"><td><a href="javascript: void(0)" onclick="removeItm(###)">\n\
<img src="/images/delete.png" width="16" height="16" /></a>\n\
<input type="hidden" name="subid_###" id="subid_###" />\n\
<input type="hidden" name="itmid_###" id="itmid_###" value="'+id+'" /></td>\n\
<td>&nbsp;</td><td id="Name_###">'+name+'</td>\n\
<td><input type="text" name="Quantity_###" id="Quantity_###" value="1" onChange="setthous(this, 1); calItm(###)" style="width:40px" /></td>\n\
<td id="unitprice_###">'+setthous(price)+'</td>\n\
<td><input type="text" name="Discnt_###" id="Discnt_###" value="0" onChange="setthous(this, 0); dscItm(###)" style="width:30px" /></td>\n\
<td><input type="text" name="Discount_###" id="Discount_###" value="0" onChange="setthous(this, 0); discItm(###)" style="width:100px" /></td>\n\
<td id="salesprice_###">'+setthous(price)+'</td>\n\
<td id="Total_###">'+setthous(price)+'</td></tr>';
    for (var i=0; i<=ItmID; i++)
        if ($("#itmid_"+i).length > 0 && $("#itmid_"+i).val() == id) {
            alert('Item "'+name+'" is already in list!!')
            return;
        }
    GB_hide();
    $("#Tabdet tr:last").after(row.replace(/###/gi, ItmID));
    $("#ItmID").val(++ItmID);
    calItm(ItmID);
}

function removeItm(idx) {
    var val = getnum("subid_"+idx);
    if (val > 0)
        $("input[name='del']").val($("input[name='del']").val() + ',' + val)
    $("#itm_"+idx).remove();
    sumProd();
}

function sumProd() {
    var Tot=0, Dsc=0, qty;
    for (var j=0; j<ItmID; j++)
        if ($("#itmid_"+j).length > 0) {
            qty = getnum("Quantity_"+j);
            Tot += parseFloat(setnum($("#unitprice_"+j).text())) * qty;
            Dsc += getnum("Discount_"+j) * qty;
        }
    
    $("input[name='TotalValue']").val(setMoney(Tot));
    $("input[name='TotDisc']").val(setMoney(Dsc));
    tot();
}

function calItm(idx) {
    var unitp = parseFloat(setnum($("#unitprice_"+idx).text())), 
    qty = getnum("Quantity_"+idx),
    dsc= getnum("Discount_"+idx);
    $("#salesprice_"+idx).html(setMoney(unitp - dsc));
    $("#Total_"+idx).html(setMoney((unitp - dsc) * qty));
    sumProd();
}

function calItms() {
    for (var j=0; j<ItmID; j++)
        calItm(j);
}

function dscItm(idx) {
    var unitp = parseFloat(setnum($("#unitprice_"+idx).text())), 
    dsc= getnum("Discnt_"+idx);
    $("#Discount_"+idx).val(setMoney(unitp * dsc / 100));
    calItm(idx);
}

function discItm(idx) {
    $("#Discnt_"+idx).val('0');
    calItm(idx);
}

function dsc() {
    $("input[name='Discount']").val(setMoney(getnum("TotalValue") * getnum("Dscnt") / 100));
    tot();
}

function disc() {
    $("input[name='Dscnt']").val('0');
    tot();
}

function tot() {
    $("input[name='Grandvalue']").val(
    setMoney(getnum("TotalValue")-getnum("TotDisc")-getnum("Discount")));
}

function vetProds() {
    for (var j=0; j<ItmID; j++)
        if ($("#itmid_"+j).length > 0 && getnum("Quantity_"+j) <= 0) {
            popmsg(document.getElementById("Quantity_"+j), 'No. of Units cannot be zero!');
            return false;
        }
    return true;
}

function setdays() {
    var days = $("#wkday").val().split('|');
    var wks = ["", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    for (var i=0; i<days.length; i++) {
        $("#"+wks[days[i]]).attr("checked", "checked");
    }
}

function getdays() {
    var wks = ["", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    var val = "";
    for (var i=1; i<wks.length; i++) {
        if ($("#"+wks[i]).is(":checked")) {
            val += "|" + i;
        }
    }
    $("#wkday").val(val.substr(1));
}

function pushRule(from, to, src, val) {
    var i = from.selectedIndex
    if (i > -1){
        to.options[to.length] = from.options[i];
    }
    stackList(src, val);
}

function pushRules(from, to, src, val) {
    for (var i = from.length - 1; i >= 0; i--) {
        to.options[to.length] = from.options[i];
    }
    stackList(src, val);
}

function stackList(lst, val) {
    var itms = "";
    for (var i = 0; i < lst.length; i++) {
        itms += "," + lst.options[i].value;
    }
    val.value = itms.substr(1);
}
