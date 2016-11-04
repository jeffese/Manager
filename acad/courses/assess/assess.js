// JavaScript Document
var ass_itms = new Array(), order;
var file_cab;
var col = 0;

function ass_itm(name, attach) {
    var cnt = ass_itms.length;
    var lst = document.getElementById('cmb_atch_' + cnt);
    this.cmp = "row_" + cnt;
    this.id = cnt;
    this.name = name;
    this.attach = attach;
    this.scores = new Array(ass_names.length);
    this.grp = new Array(cls_grp_inf.length);
    this.total = 0;
    this.atch = attach.length > 0 ? attach.split("~#~") : new Array();
    this.newf = new Array();
    
    this.add = function(idx) {
        var num = idx == -1 ? this.atch.length : idx;
        var pv = this.id + '_' + num;
        var file = idx == -1 ? '' : this.atch[idx];
        if (isEdit) {
            html = '<div><input type="hidden" name="pv_' + pv + '" id="pv_' + pv + '" value="' + file + 
            '" /><input type="file" name="preview_' + pv + '" id="preview_' + pv + '" size="1" ' +
            'style="display:none" onchange="ass_itms[' + this.id + '].onChangeVal(this.value, ' + num + 
            ')" /></div>';
            file_cab.append(html);
        }
        
        if (idx == -1) {
            this.atch.push(null);
            this.newf.push("1");
            $('#preview_' + pv).show().focus().click().hide();
        } else {
            this.newf.push("0");
            lst.options[idx] = new Option(file, idx);
        }
    }
    
    this.load_atch = function() {
        for (j=0; j<this.atch.length; j++)
            this.add(j);
    }
    
    this.del = function() {
        var i = lst.selectedIndex;
        if (i > -1) {
            var idx = lst.value;
            $("#preview_" + this.id + '_' + idx).remove();
            lst.remove(i);
            this.atch[idx] = null;
        }
    }
    
    this.onChangeVal = function(val, idx) {
        this.atch[idx] = val;
        lst.options[lst.length] = new Option(val, idx);
    }
    
    this.show = function() {
        var i = lst.selectedIndex;
        if (i > -1) {
            var idx = lst.value;
            if (this.newf[idx] == "0") {
                GB_showCenter('Answer Sheets - ' + this.atch[idx], '/acad/courses/assess/archive/answers/' + 
                    $("#student_" + this.id).val() + '/' + $("#assess_id_" + this.id).val() + '/' + this.atch[idx], 600,600);
            } else 
                alert('This file has not been uploaded yet.');
        }
    }
}

function collateFiles() {
    var atchment;
    for (var idx=0; idx<ass_itms.length; idx++) {
        atchment = "";
        if (ass_itms[idx] != null){
            for (var j=0; j<ass_itms[idx].atch.length; j++) {
                if (ass_itms[idx].atch[j] != null)
                    atchment += ":" + j;
            }
        }
        $("#attach_" + idx).val(atchment.substr(1));
    }
}

function cummulate(id) {
    var tot = 0, fld, val, scores = "", max, arr, msg;
    for (var g=0; g<ass_itms[id].grp.length; g++)
        ass_itms[id].grp[g] = 0;
    
    for (var c=0; c < ass_names.length; c++) {
        fld = "ca_" + id + "_" + c;
        val = $("#" + fld).val();
        val = parseFloat(val == "" ? 0 : val);
        
        if (val > parseInt(max_scores[c])) {
            msg = "The maximum score for " + ass_names[c] + " (" + ass_codes[c] + ") should be " + max_scores[c];
            if (isEdit) {
                $("#" + fld).val("");
                popmsg(document.getElementById(fld), msg);
                return;
            } else {
                alert(msg + " for " + ass_itms[id].name);
            }
        }
        ass_itms[id].scores[c] = val;
        scores += "|" + val;
        if (ass_ca[c] == 0)
            max = max_scores[c];
        else if (ass_ca[c] == -1)
            max = att_cnt;
        else
            max = 100;
        g = parseInt(ass_grp[c]);
        if (g == 0) {
            tot = aggregate(cls_typ, tot, max, val, percentages[c]);
        } else {
            inf = cls_grp_inf[g].split(':');
            ass_itms[id].grp[g] = aggregate(parseInt(inf[4]), ass_itms[id].grp[g], max, val, percentages[c]);
        }
    }

    for (var i=1; i < ass_itms[id].grp.length; i++) {
        inf = cls_grp_inf[i].split(':');
        if (inf[4] == 1) {
            arr = ass_itms[id].grp[i].substr(2).split(',');
            ass_itms[id].grp[i] = sumArray(arr) / arr.length;
        }
        $("#grp_" + id + "_" + i).val(Math.round(ass_itms[id].grp[i]));
        tot = aggregate(cls_typ, tot, 100, ass_itms[id].grp[i], inf[3]);
    }
    
    if (cls_typ == 1) {
        arr = tot.substr(2).split(',');
        tot = sumArray(arr) / arr.length;
    }
    
    ass_itms[id].total = tot;
    $("#scores_" + id).val(scores.substr(1));
    $("#total_" + id).val(Math.round(tot));
}

function aggregate(typ, tot, max, val, per) {
    switch (typ) {
        case 1:
            return tot + "," + val;
        case 2:
            return tot > val ? tot : val;
        default:
            return tot + val / max * per;
    }
}

function sumArray(Arr) {
    var sum = 0;
    for (var i=0; i < Arr.length; i++) {
        sum += parseFloat(Arr[i]);
    }
    return sum;
}

function sort(y, grp) {
    col = y;
    $("#img_sort").remove();
    var img = '<img id="img_sort" src="/images/descend.gif" width="10" height="10" border="0">';
    switch (col) {
        case -3:
            col = grp
            order.sort(SortGroup);
            $("#col_grp_" + grp).append(img);
            break;
        case -2:
            order.sort(SortName);
            $("#col_nam").append(img);
            break;
        case -1:
            order.sort(SortTotal);
            $("#col_tot").append(img);
            break;
        default:
            order.sort(SortScore);
            $("#col_" + col).append(img);
            break;
    }
    if (stud_cnt > 0) {
        $("#row_" + order[0]).insertAfter($("#row_"));
        $("#num_" + order[0]).html("<b>1</b>");
    }
    for (var i=1; i<stud_cnt; i++) {
        $("#row_" + order[i]).insertAfter($("#row_" + order[i-1]));
        $("#num_" + order[i]).html("<b>"+(i+1)+"</b>");
    }
}

function SortScore(a, b) {
    return ass_itms[b].scores[col] - ass_itms[a].scores[col];
}

function SortTotal(a, b) {
    return ass_itms[b].total - ass_itms[a].total;
}

function SortName(a, b) {
    if (ass_itms[a].name < ass_itms[b].name)
        return -1
    if (ass_itms[a].name > ass_itms[b].name)
        return 1
    return 0
}

function SortGroup(a, b) {
    return ass_itms[b].grp[col] - ass_itms[a].grp[col];
}

function SetPosition() {
    var j, k;
    order.sort(SortTotal);
    for (var i=0; i < stud_cnt; i++) {
        j = order[i];
        pos = i + 1;
        if (i > 0) {
            k = order[i-1];
            pos = ass_itms[j].total == ass_itms[k].total ? ass_itms[k].pos : pos;
        }
        ass_itms[j].pos = pos;
        $("#pos_" + j).val(pos);
    }
}

function ArrayInsert(Arr, idx, val) {
    if (Arr[idx] != null) {
        var n = Arr.length-1;
        if (Arr[n] != null) {
            Arr.push(null);
            n++;
        }
        for (var i=n; i>idx; i--) {
            Arr[i] = Arr[i-1];
        }
    }
    Arr[idx] = val;
}

$(document).ready(function(){
    file_cab = $("#win_files");
    order = new Array(stud_cnt);
    for (var i=0; i < stud_cnt; i++) {
        ass_itms.push(new ass_itm($("#stud_name_" + i).html(), $("#prvw_" + i).val()));
        order[i] = i;
        ass_itms[i].load_atch();
        cummulate(i);
    }
    SetPosition();
});
    
