// JavaScript Document
var ExpiryDate = new Array(), srlen = 0, itm;

function appendProd(id, name, price, qty, pack) {
    var row = '<tr id="OrderDet###"><td><a href="javascript: void(0)" onclick="removeProd(###)">\n\
<img src="/images/delete.png" width="16" height="16" /></a>\n\
<input type="hidden" name="OrderDetailID###" id="OrderDetailID###" />\n\
<input type="hidden" name="ProductID###" id="ProductID###" value="'+id+'" />\n\
<input type="hidden" name="ProductName###" value="'+name+'" />\n\
<input type="hidden" name="calcost###" value="0" />\n\
<input type="hidden" name="qty###" id="'+qty+'###" />\n\
<input type="hidden" name="QtyinStock###" />\n\
</td><td>&nbsp;</td><td id="ProductName###"><b>'+name+'</b></td><td>\n\
<input type="text" name="Quantity###" id="Quantity###" value="0" onChange="setthous(this, 1); calProd(###)" style="width:40px" />\n\
</td><td><input type="text" name="unitsinpack###" id="unitsinpack###" value="'+setthous(Math.max(1, pack))+'" \n\
onChange="setthous(this, 1); calProd(###)" style="width:40px" /></td><td id="QtyinStock###">1</td>\n\
<td id="Received###">0</td><td><input type="text" name="UnitPrice###" \n\
id="UnitPrice###" value="0" onChange="numme(this, 0); calProd(###)" style="width:100px" /></td>\n\
<td id="linetotal###">0</td><td id="salesprice###">0</td>\n\
<td><input type="text" name="Margin###" id="Margin###" value="'+getnum("Margin")+'" onChange="setthous(this, 0); calProd(###)" \n\
style="width:30px" /></td><td id="calcost###">0</td><td><input type="text" name="sugsell###" \n\
id="sugsell###" style="width:100px" /></td><td id="oldsell###">'+price+'</td>\n\
<td align="center"><input type="checkbox" name="Expires###" id="Expires###" onclick="setExpires(###)" /></td><td>\n\
<input type="text" name="ExpiryDate###" id="ExpiryDate###" style="width:80px; display:none" /></td></tr>';
    for (var i=0; i<=OrdDetID; i++)
        if ($("#ProductID"+i).length > 0 && $("#ProductID"+i).val() == id) {
            alert('Product "'+name+'" is already in list!!')
            return;
        }
    GB_hide();
    $("#TabOrderdet tr:last").after(row.replace(/###/gi, OrdDetID));
    ExpiryDate[OrdDetID] = new dhtmlxCalendarObject('ExpiryDate'+OrdDetID, true, {
        isYearEditable: true, 
        isMonthEditable: true
    });
    ExpiryDate[OrdDetID].setSkin('dhx_black');
    calProd(OrdDetID);
    OrdDetID++;
    $("#OrdDetID").val(OrdDetID);
}

function removeProd(idx) {
    var val = getnum("OrderDetailID"+idx);
    if (val > 0)
        $("input[name='del_ids']").val(getnum("del_ids") + ',' + val)
    $("#OrderDet"+idx).remove();
    sumProd();
}

function setExpires(idx) {
    if ($("#Expires"+idx).is(":checked"))
        $("#ExpiryDate"+idx).show();
    else
        $("#ExpiryDate"+idx).hide();
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

function sumProd() {
    var tot = 0;
    for (var i=0; i<=OrdDetID; i++)
        if ($("#ProductID"+i).length > 0) {
            tot += getnumbyid("UnitPrice"+i)*getnumbyid("Quantity"+i);
        }
    $("input[name='OrderTotal']").val(setMoney(tot));
    calCost();
}

function calProd(idx) {
    var unitp = getnum("UnitPrice"+idx), 
    qty = getnum("Quantity"+idx), pack = getnum("unitsinpack"+idx);
    var val = qty*pack;
    $("input[name='QtyinStock"+idx+"']").val(val);
    $("#QtyinStock"+idx).html(val);
    $("#linetotal"+idx).html(setMoney(unitp*qty));
    $("#salesprice"+idx).html(setMoney(unitp / pack));
    sumProd();
}

function calCost() {
    var salp, tot, exp = getnum("FreightCharge") + getnum("expenses"),
    ord = getnum("OrderTotal"), xfrom = getnum("ExchangeFrom"), xto = getnum("ExchangeTo");
    tot = ord;
    tot -= getnum("Discount");
    tot += exp;
    $("input[name='TotalValue']").val(setMoney(tot));
    for (var i=0; i<=OrdDetID; i++)
        if ($("#ProductID"+i).length > 0) {
            salp = getnumbyid("UnitPrice"+i) / getnumbyid("unitsinpack"+i);
            salp += exp * salp/ord;
            $("#calcost"+i).html(setMoney(salp * xto / xfrom));
        }
    toWords(getnumbyid("Currency"), ord, tot);
}

function toWords(cur, ord, tot) {
    $("#ordwords").html(NumToWords(ord, curArray[cur][1], curArray[cur][4]));
    $("#totwords").html(NumToWords(tot, curArray[cur][1], curArray[cur][4]));
}

function calDsc() {
    $("input[name='Discount']").val(getnum("OrderTotal") * getnum("Dscnt") / 100);
    calCost();
}

function vetProds() {
    for (var i=0; i<=OrdDetID; i++)
        if ($("#ProductID"+i).length > 0) {
            if (getnumbyid("Quantity"+i) <= 0) {
                popmsg(document.getElementById("Quantity"+i), 'No. of Units cannot be zero!');
                return false;
            }
            if (getnumbyid("unitsinpack"+i) <= 0) {
                popmsg(document.getElementById("unitsinpack"+i), 'No. of Units in pack cannot be zero!');
                return false;
            }
            if (getnum("QtyinStock"+i) < setnum($("#Received"+i).text())) {
                popmsg(document.getElementById("Received"+i), 'Received Items is more than Purchased Items!');
                return false;
            }
        }
    return true;
}

function Recv() {
    $("#recv").hide();
    $("#post").hide();
    $("[name^=Received]").show();
    $("#accept").show();
    $("#cncl").show();
    $("#recvall").show();
}

function rcvAccept() {
    for (var i=0; i<=OrdDetID; i++)
        if (getnum("Received"+i) > getnum("QtyinStock"+i)) {
            popmsg($("input[name='Received"+i+"']")[0], 'Received Items is more than Purchased Items!');
            return;
        } else if (getnum("Received"+i) < 0) {
            popmsg($("input[name='Received"+i+"']")[0], 'Received Items cannot be less than 0!');
            return;
        }
    $("#frmreceive").submit();
}

function cancelRcv() {
    $("#recv").show();
    $("#post").show();
    $("[name^=Received]").hide();
    $("#accept").hide();
    $("#cncl").hide();
    $("#recvall").hide();
}

function rcvAll() {
    for (var i=0; i<=OrdDetID; i++)
        if (getnum("serialized"+i) == 0) {
            $("input[name='Received"+i+"']").val(getnum("QtyinStock"+i));
        }
}

function ordPost() {
    for (var i=0; i<=OrdDetID; i++)
        if (getnum("Received"+i) != getnum("QtyinStock"+i)) {
            popmsg($("input[name='Received"+i+"']")[0], '"' + $("#ProductName"+i).text() + '" has not been fully Received!');
            return;
        }
    if (confirm("Are you sure you want to Post the Order?"))
        $("#frmpost").submit();
}

function ordRet(i) {
    top.leftFrame.showMod('Purchase Returns', '/stock/returns/add.php?ord='+i);
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

function suggest() {
    var val;
    for (var i=0; i<=OrdDetID; i++)
        if ($("#ProductID"+i).length > 0) {
            val = setnum($("#calcost"+i).text()) * (1 + getnum("Margin"+i) / 100);
            $("#sugsell"+i).val(rndup(val, val < 100 ? 0 : -1))
        }
}

function addSerial() {
    var ser = trimme($("#serialno").val());
    if (ser.length > 0) {
        if (srlen >= getnum("QtyinStock"+itm)) {
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
