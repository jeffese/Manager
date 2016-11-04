// JavaScript Document
var ExpiryDate = new Array(), srlen = 0, itm;

function appendProd(id, name, qty, serials, serialized) {
    var act = serialized==1 ? ' readonly="readonly" onkeydown="serials(###)" onclick="serials(###)"' : 
        'onChange="setthous(this, 1); vetProds()"';
    var row = '<tr id="RetDet###">\n\
<td align="center"><a href="javascript: void(0)" onclick="removeProd(###)">\n\
<img src="/images/delete.png" width="16" height="16" /></a>\n\
<input type="hidden" name="transfer_id###" id="transfer_id###" />\n\
<input type="hidden" name="ProductID###" id="ProductID###" value="'+id+'" />\n\
<input type="hidden" name="ProductName###" id="ProductName###" value="'+name+'" />\n\
<input type="hidden" name="ShopStock###" value="'+qty+'" />\n\
<input type="hidden" name="allserials###" value="'+serials+'" />\n\
<input type="hidden" name="serialized###" value="'+serialized+'" />\n\
<input type="hidden" name="serials###" value="" />\n\
</td><td align="center">&nbsp;</td><td id="ItemName###" align="center"><b>'+name+'</b></td>\n\
<td align="center">\n\
<input type="text" name="units###" id="units###" value="0"'+act+' style="width:40px" /></td></tr>';
    for (var i=0; i<=TransDetID; i++)
        if ($("#ProductID"+i).length > 0 && $("#ProductID"+i).val() == id) {
            alert('Item "'+name+'" is already in list!!')
            return;
        }
    GB_hide();
    $("#TabTransDet tr:last").after(row.replace(/###/gi, TransDetID));
    TransDetID++;
    $("#TransDetID").val(TransDetID);
    $("#transfertype").attr("disabled", "disabled");
}

function removeProd(idx) {
    var val = getnum("transfer_id"+idx);
    if (val > 0)
        $("input[name='del_ids']").val(getnum("del_ids") + ',' + val)
    $("#RetDet"+idx).remove();
}

function vetProds() {
    var units, rem, fld;
    for (var i=0; i<=TransDetID; i++)
        if ($("#ProductID"+i).length > 0) {
            fld = document.getElementById("units"+i);
            units = getnum("units"+i);
            if (units <= 0) {
                popmsg(fld, 'No. of Units cannot be zero!');
                return false;
            }
            rem = getnum("ShopStock"+i);
            if (units > rem) {
                popmsg(fld, 'No. of Units cannot be more than Items left ('+rem+')!');
                return false;
            }
        }
    return true;
}

function Transfer() {
    if (confirm("Are you sure you want to transfer these items?"))
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
        if (srlen >= getnum("ShopStock"+itm)) {
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

function setOutlets() {
    for (var i=1; i<4; i++) {
        $("#from"+i).hide();
        $("#to"+i).hide();
    }
    $("input[name='Outletout']").val(0);
    $("input[name='Outletin']").val(0);
    
    switch (parseInt($("#transfertype").val())) {
        case 11:
            setShow(1, 3);
            break;
        case 12:
            setShow(1, 2);
            break;
        case 13:
            setShow(1, 1);
            break;
        case 14:
            setShow(2, 3);
            break;
        case 15:
            setShow(2, 2);
            break;
        case 16:
            setShow(2, 1);
            break;
        case 17:
            setShow(3, 3);
            break;
        case 18:
            setShow(3, 2);
            break;
        case 19:
            setShow(3, 1);
            break;
    }
}

function setFrom(val) {
    $("input[name='Outletout']").val(val);
}

function setTo(val) {
    $("input[name='Outletin']").val(val);
}

function setShow(from, to) {
    $("#from"+from).show();
    $("#to"+to).show();
    setFrom(parseInt($("#from"+from).val()));
    setTo(parseInt($("#to"+to).val()));
}