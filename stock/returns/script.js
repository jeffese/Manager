// JavaScript Document
var ExpiryDate = new Array(), srlen = 0, itm;

function appendProd(id, name, price, qty, pack, serials, serialized) {
    var act = serialized==1 ? 'readonly="readonly" onkeydown="serials(###)" onclick="serials(###)"' : 
        'onChange="setthous(this, 1); if (vetProds()) calProd(###)"';
    var row = '<tr id="RetDet###"><td><a href="javascript: void(0)" onclick="removeProd(###)">\n\
<img src="/images/delete.png" width="16" height="16" /></a>\n\
<input type="hidden" name="OrderRetDetID###" id="OrderRetDetID###" />\n\
<input type="hidden" name="OrderDetailID###" id="OrderDetailID###" value="'+id+'" />\n\
<input type="hidden" name="QtyinStock###" value="'+qty+'" />\n\
<input type="hidden" name="unitsinpack###" value="'+pack+'" />\n\
<input type="hidden" name="SalePrice###" value="'+price+'" />\n\
<input type="hidden" name="allserials###" value="'+serials+'" />\n\
<input type="hidden" name="serialized###" value="'+serialized+'" />\n\
<input type="hidden" name="serials###" value="" />\n\
</td><td>&nbsp;</td><td id="ItemName###"><b>'+name+'</b></td><td id="Qty###"></td>\n\
<td><input type="text" name="units###" id="units###" value="0" '+act+' style="width:40px" /></td>\n\
<td id="SalePrice###">'+setMoney(price)+'</td>\n\
<td id="linetotal###"></td></tr>';
    for (var i=0; i<=OrdDetID; i++)
        if ($("#OrderDetailID"+i).length > 0 && $("#OrderDetailID"+i).val() == id) {
            alert('Item "'+name+'" is already in list!!')
            return;
        }
    GB_hide();
    $("#TabOrderdet tr:last").after(row.replace(/###/gi, OrdDetID));
    calProd(OrdDetID);
    OrdDetID++;
    $("#OrdDetID").val(OrdDetID);
}

function removeProd(idx) {
    var val = getnum("OrderRetDetID"+idx);
    if (val > 0)
        $("input[name='del_ids']").val(getnum("del_ids") + ',' + val)
    $("#RetDet"+idx).remove();
    sumProd();
}

function sumProd() {
    var tot = 0;
    for (var i=0; i<=OrdDetID; i++)
        if ($("#OrderDetailID"+i).length > 0) {
            tot += getnum("SalePrice"+i)*getnum("units"+i);
        }
    $("input[name='TotalValue']").val(setMoney(tot));
    toWords(tot);
}

function calProd(idx) {
    var unitp = getnum("SalePrice"+idx), 
    qty = getnum("units"+idx), pack = getnum("unitsinpack"+idx);
    $("#Qty"+idx).html(setMoney(qty/pack));
    $("#linetotal"+idx).html(setMoney(unitp*qty));
    sumProd();
}

function toWords(tot) {
    $("#ordwords").html(NumToWords(tot, curr, curunit));
}

function vetProds() {
    var units, rem;
    for (var i=0; i<=OrdDetID; i++)
        if ($("#OrderDetailID"+i).length > 0) {
            units = getnum("units"+i);
            if (units <= 0) {
                popmsg(document.getElementById("units"+i), 'No. of Units cannot be zero!');
                return false;
            }
            rem = getnum("QtyinStock"+i);
            if (units > rem) {
                popmsg(document.getElementById("units"+i), 'No. of Units cannot be more than Purchased Items left ('+rem+')!');
                return false;
            }
        }
    return true;
}

function ordPost() {
    if (confirm("Are you sure you want to Return these Items?"))
        $("#frmpost").submit();
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
        if (srlen >= getnum("QtyinStock"+itm)) {
            alert('All ('+srlen+') serials have been received!');
            return;
        }
        var pass = false;
        var str = $("[name=allserials"+itm+"]").val();
        var lst = str.split(','), c = str.length == 0 ? 0 : lst.length;
        for (var i=0; i<c; i++) {
            if (trimme(lst[i]) == ser) {
                pass = true;
                break;
            }
        }
        if (!pass) {
            alert('"' + ser + '" is not in the list of items to select from!');
        } else {
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
}
