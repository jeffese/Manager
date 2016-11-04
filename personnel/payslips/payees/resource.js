/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function prepRes(isEdit, id) {
    var names = $("#parts"+id).val().split('|');
    var flds = $("#flds"+id).val().split('|');
//    var state = $("#state"+id).val().split('|');
    var vals = $("#tax"+id).val().split('|');
    var read = isEdit ? '' : ' readonly="readonly"';
    
    var nam_lst = [[0, 'VALUE']];
    for (var s=1; s<names.length; s++)
        nam_lst.push([s, names[s]]);
    
    var head = "", row = "";
    if (names.length > 0)
        for (var i=1; i<names.length; i++) {
            if (undefined === vals[i])
                vals[i] = "0";
            if (flds[i].search(/^0$|^0#|#0$|#0#/) != -1) {
                head += '<td nowrap="nowrap"><b>'+getIdxName(i, nam_lst)+'</b></td>';
                row += '<td><input type="text" name="resource'+id+'" style="width:80px"'+read+' \n\
value="'+(isEdit ? vals[i] : setthous(vals[i]))+'" onchange="numme(this, 0); colsal(\''+id+'\')" /></td>';
            } else {
                head += '<td></td>';
                row += '<td><input type="hidden" name="resource'+id+'" value="'+vals[i]+'" /></td>';
            }
        }
    $('#reswin'+id).html('<table border="0" cellpadding="0" cellspacing="0">\n\
<tr align="center" class="blue-normal">'+head+'</tr><tr>'+row+'</tr></table>');
}

function colsal(id) {
    var obj = $('input[name="resource'+id+'"]')
    txtval='';
    for (var i=0; i<obj.length; i++) {
        txtval += '|'+obj[i].value;
    }	
    $("#tax"+id).val(txtval);
}
