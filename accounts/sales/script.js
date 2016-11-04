// JavaScript Document
var fromDT = new Array(), toDT = new Array(), srlen = 0, itm;

function appendItm(id, name, price, qty, serials, serialized, tax) {
    var act = serialized==1 ? ' readonly="readonly" onkeydown="serials(###)" onclick="serials(###)"' : 
        'onChange="setthous(this, 1); if (vetProds()) calItm(###)"';
    var row = '<tr id="itm_###"><td><a href="javascript: void(0)" onclick="removeItm(###)">\n\
<img src="/images/delete.png" width="16" height="16" /></a>\n\
<input type="hidden" name="ProductID_###" id="ProductID_###" value="'+id+'" />\n\
<input type="hidden" name="ProductName_###" id="ProductName_###" value="'+name+'" />\n\
<input type="hidden" name="ShopStock_###" id="ShopStock_###" value="'+qty+'" />\n\
<input type="hidden" name="UnitPrice_###" value="'+price+'" />\n\
<input type="hidden" name="LineTotal_###" />\n\
<input type="hidden" name="allserials_###" id="allserials_###" value="'+serials+'" />\n\
<input type="hidden" name="serials_###" id="serials_###" />\n\
<input type="hidden" name="serialized_###" id="serialized_###" value="'+serialized+'" /></td>\n\
<td>&nbsp;</td>\n\
<td id="Name_###">'+name+'</td>\n\
<td><input type="text" name="units_###" id="units_###" value="1"'+act+' style="width:40px" /></td>\n\
<td id="UnitPrice_###">'+setthous(price)+'</td>\n\
<td><input type="text" name="Discnt_###" id="Discnt_###" value="0" onChange="setthous(this, 0); dscItm(###)" style="width:30px" /></td>\n\
<td><input type="text" name="Discount_###" id="Discount_###" value="0" onChange="setthous(this, 0); discItm(###)" style="width:100px" /></td>\n\
<td><input type="text" name="TaxRate_###" id="TaxRate_###" value="'+tax+'" onchange="setthous(this, 0); calItm(###)" style="width:30px" /></td>\n\
<td id="SalePrice_###">0</td>\n\
<td id="LineTotal_###">0</td></tr>';
//    for (var i=0; i<=ItmID; i++)
//        if ($("#ProductID_"+i).length > 0 && $("#ProductID_"+i).val() == id) {
//            alert('Item "'+name+'" is already in list!!')
//            return;
//        }
    GB_hide();
    $("#Tabdet tr:last").after(row.replace(/###/gi, ItmID));
    calItm(ItmID);
    $("#ItmID").val(++ItmID);
}

function removeItm(idx) {
    var val = getnum("InvoiceDetailID_"+idx);
    if (val > 0)
        $("input[name='del']").val(getnum("del") + ',' + val)
    $("#itm_"+idx).remove();
    sumProd();
}

function sumProd() {
    var Tot=0, Dsc=0, qty;
    for (var j=0; j<ItmID; j++)
        if ($("#ProductID_"+j).length > 0) {
            qty = getnum("units_"+j);
            Tot += getnum("UnitPrice_"+j) * qty;
            Dsc += getnum("Discount_"+j) * qty;
        }
    
    $("input[name='TotalValue']").val(setMoney(Tot));
    $("input[name='TotDisc']").val(setMoney(Dsc));
    tot();
}

function calItm(idx, sum) {
    var unitp = getnum("UnitPrice_"+idx),
    qty = getnum("units_"+idx),
    dsc = getnum("Discount_"+idx),
    tax = getnum("TaxRate_"+idx),
    tot = (unitp - dsc + tax) * qty;
    $("#SalePrice_"+idx).html(setMoney(unitp - dsc));
    $("input[name='LineTotal_"+idx+"']").val(tot);
    $("#LineTotal_"+idx).html(setMoney(tot));
    if (!sum)
        sumProd();
}

function calItms(sum) {
    for (var j=0; j<ItmID; j++)
        calItm(j, true);
    if (!sum)
    sumProd();
}

function dscItm(idx) {
    var unitp = getnum("UnitPrice_"+idx),
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
    $("input[name='Grandvalue']").val(setMoney(getnum("TotalValue")
        +getnum("TotTax")-getnum("TotDisc")-getnum("Discount")));
}

function vetProds() {
    var units, rem, fld, cnt = 0;
    for (var i=0; i<=ItmID; i++)
        if ($("#ProductID_"+i).length > 0) {
            cnt++;
            fld = document.getElementById("units_"+i);
            units = getnum("units_"+i);
            if (units <= 0) {
                popmsg(fld, 'No. of Units cannot be zero!');
                return false;
            }
            if ($('#ShopStock_'+i).val() == '')
                continue;
            rem = getnum("ShopStock_"+i);
            if (units > rem) {
                popmsg(fld, 'No. of Units cannot be more than Items left ('+rem+')!');
                return false;
            }
        }
    return cnt > 0;
}

function setExchange() {
    var cur = getnumbyid("Currency");
    $("#xfrom").html(curArray[cur][3]);
    toWords(cur, getnum("OrderTotal"), getnum("TotalValue"));
}

function setExRate() {
    var cur = getnumbyid("Currency");
    $("input[name='ExchangeFrom']").val(curArray[cur][7]);
    $("input[name='ExchangeTo']").val(curArray[cur][8]);
    $("#xfrom").html(curArray[cur][3]);
    calCost();
}

function toWords(cur, ord, tot) {
    $("#ordwords").html(NumToWords(ord, curArray[cur][1], curArray[cur][4]));
    $("#totwords").html(NumToWords(tot, curArray[cur][1], curArray[cur][4]));
}

function Post() {
    if (confirm("Are you sure you want to Post this Invoice?"))
        $("#frmpost").submit();
}

function Refund() {
    if (confirm('Are you sure you want to Refund this Sale?'))
        $("#frmRefund").submit();
}

function serials(i) {
    itm = i;
    var str = $("[name=serials"+i+"]").val();
    var lst = str.split(','), c = str.length == 0 ? 0 : lst.length;
    srlen = c;
    $("#serials").empty();
    for (var x=0; x<c; x++)
        $("#serials").append('<option id="'+x+'">'+lst[x]+'</option>');
    $("#dialog-form").dialog("open");
}

function delSerial() {
    $("#serials option:selected").remove();
    srlen--;
}

function addSerial() {
    var ser = trimme($("#serialno").val());
    if (ser.length > 0) {
        if (srlen >= getnum("units"+itm)) {
            alert('All ('+srlen+') serials have been received!');
            return;
        }
        var pass = true;
        $("#serials > option").each(function() {
            if (this.text == ser) {
                alert('"' + ser + '" is already in the list!');
                pass = false;
            }
        });
        if (pass) {
            srlen++;
            $("#serials").append('<option id="'+srlen+'">'+ser+'</option>');
            $("#serialno").val('');
        }
    }
}

function clientype(idx) {
    for (var i=0; i<8; i++) {
        $("#desg"+i).hide();
    }
    $("#desg"+idx).show();
    setclient(idx);
}

function setclient(idx) {
    $("#VendorID").val($("#desg"+idx).val());
    $("#CustomerName").val($("#desg"+idx+" option:selected").text());
    $("#currency").val($("#desg"+idx+" option:selected").attr("currency"));
    $("#xfrom").html($("#currency option:selected").attr("cod"));
    if ($("#currency").val() == coycur) {
        $("#ExchangeFrom").val(1);
        $("#ExchangeTo").val(1);
        $("#xbox").hide();
    } else {
        $("#ExchangeFrom").val($("#currency option:selected").attr("from"));
        $("#ExchangeTo").val($("#currency option:selected").attr("to"));
        $("#xbox").show();
    }
    var cred = $("#desg"+idx+" option:selected").attr('creds');
    var creds = cred ? cred.split(';') : ['0', '0', '', '', ''];
    $("#credit").attr('checked', creds[0] == 1 ? 'checked' : '');
    $("#cheque").attr('checked', creds[1] == 1 ? 'checked' : '');
    $("#bal").html(creds[2]);
    $("#limit").html(creds[3]);
    $("#disc").html(creds[4]);
}
