// JavaScript Document
var ass_itms = new Array(), order;
var file_cab;
var grp, col, eval_nams, eval_chk, sub_nams, sub_chk;

function ass_itm(name, scores, attach, comment, Notes) {
    var cnt = ass_itms.length;
    var lst = document.getElementById('cmb_atch_' + cnt);
    this.cmp = "row_" + cnt
    var txt_id;
    var atch;
    var newf;
    this.id = cnt;
    this.name = name;
    this.scores = collections(scores, '|', ':', sub_nams);
    this.attach = collections_arr(attach, '~~||~~', '~~##~~', '~#~', sub_nams);
    this.comments = collections(comment, '~~##~~', '~#~', sub_nams);
    this.Notes = collections(Notes, '~~##~~', '~#~', sub_nams);
    
    this.add = function(idx) {
        var num = idx == -1 ? atch.length : idx;
        var pv = txt_id + num;
        var file = idx == -1 ? '' : atch[idx];
        if (isEdit) {
            var html = '<div><input type="hidden" name="pv_' + pv + '" id="pv_' + pv + '" value="' + file + 
            '" /><input type="file" name="preview_' + pv + '" id="preview_' + pv + '" size="1" ' +
            'style="display:none" onchange="ass_itms[' + this.id + '].onChangeVal(this.value, ' + num + 
            ')" /></div>';
            file_cab.append(html);
        }
        
        if (idx == -1) {
            atch.push(null);
            newf.push("1");
            $('#preview_' + pv).show().focus().click().hide();
        } else {
            newf.push("0");
            lst.options[idx] = new Option(file, idx);
        }
    }
    
    this.load = function() {
        newf = new Array();
        txt_id = this.id + '_' + grp + '_' + col + '_';
        if (this.scores[grp][col] != null)
            $("#ca_" + cnt).val(this.scores[grp][col]);
        if (this.comments[grp][col] != null)
            $("#com_" + cnt).val(this.comments[grp][col]);
        if (this.Notes[grp][col] != null)
            $("#not_" + cnt).val(this.Notes[grp][col]);
        while (lst.length > 0)
            lst.remove(0);
        atch = this.attach[grp][col];
        for (var j=0; j<atch.length; j++)
            this.add(j);
    }
    
    this.del = function() {
        var i = lst.selectedIndex;
        if (i > -1) {
            var idx = lst.value;
            $("#preview_" + txt_id + idx).remove();
            lst.remove(i);
            atch[idx] = null;
        }
    }
    
    this.onChangeVal = function(val, idx) {
        switch (idx) {
            case -1:
                if (val > 100) {
                    $("#ca_" + cnt).val('');
                    popmsg(document.getElementById("ca_" + cnt), "Score cannot be greater than 100!");
                    return;
                }
                this.scores[grp][col] = val;
                break;
            case -2:
                this.comments[grp][col] = val;
                break;
            case -3:
                this.Notes[grp][col] = val;
                break;
            default:
                atch[idx] = val;
                lst.options[lst.length] = new Option(val, idx);
        }
    }
    
    this.show = function() {
        var i = lst.selectedIndex;
        if (i > -1) {
            var idx = lst.value;
            if (newf[idx] == "0") {
                GB_showCenter('Answer Sheets - ' + atch[idx], '/acad/courses/assess/archive/answers/' + 
                    $("#student_" + this.id).val() + '/evaluations/' + $("#assess_id_" + this.id).val() + 
                    '/' + grp + '/' + col + '/' + atch[idx], 600,600);
            } else 
                alert('This file has not been uploaded yet.');
        }
    }
}

function collections_arr(str, glue, gum, evo, Ass_r) {
    var Arr = collections(str, glue, gum, Ass_r);
    for (var i=0; i<Arr.length; i++)
        for (var j=0; j<Arr[i].length; j++)
            Arr[i][j] = Arr[i][j] != null && Arr[i][j].length > 0 ? Arr[i][j].split(evo) : new Array();
    return Arr;
}

function collections(str, glue, gum, Ass_r) {
    var arry = collection(str, glue, Ass_r);
    var Arr = new Array();
    for (var i=0; i<arry.length; i++)
        Arr.push(collection(arry[i], gum, Ass_r != null ? Ass_r[i] : null));
    return Arr;
}

function collection(str, gum, Ass_r) {
    var arr = str != null && str.length > 0 ? str.split(gum) : new Array();
    var Arr = new Array(Ass_r == null ? arr.length : Ass_r.length);
    for (var i=0; i<Arr.length; i++)
        Arr[i] = arr.length > i ? arr[i] : '';
    return Arr;
}

function implode_arr(Arr, glue, gum) {
    var str = "";
    for (var i=0; i<Arr.length; i++)
        str += glue + Arr[i].join(gum);
    return str.substr(glue.length);
}

function gen_ass() {
    var box = '<table border="0" cellpadding="0" cellspacing="0" style="float:left; margin:10px">' +
    '<tr><td class="bl_tl"></td><td class="bl_tp"></td><td class="bl_tr"></td></tr>' +
    '<tr><td rowspan="2" class="bl_lf"></td><td class="bl_title">###</td>' +
    '<td rowspan="2" class="bl_rt"></td></tr><tr><td class="bl_center" id="ass_box_@@@"></td></tr>' +
    '<tr><td class="bl_bl"></td><td class="bl_bt"></td><td class="bl_br"></td></tr></table>';
    var eval = '<table border="0" cellspacing="2" cellpadding="2" style="float:left; margin:2px">' +
    '<tr><td><input type="radio" name="ass" id="ass_@@_%%" value="radio" onclick="load_dt(@@, %%)" /></td>' +
    '<td><strong>###</strong></td></tr></table>';
    
    for (var i=0; i<eval_nams.length; i++) {
        $("#ass_nam_box").append(box.replace(/###/gi, eval_nams[i]).replace(/@@@/gi, i));              
        for (var j=0; j<sub_nams[i].length; j++) {
            if (sub_chk[i][j] == 1)
                $("#ass_box_" + i).append(eval.replace(/###/gi, sub_nams[i][j]).replace(/@@/gi, i).replace(/%%/gi, j));
        }
    }
    $("#ass_0_0").attr("checked", "checked");
}

function load_dt(i, j) {
    grp = i;
    col = j;
    for (var a=0; a<ass_itms.length; a++) {
        ass_itms[a].load();
    }
}

function collate() {
    var attachment, atchment, atch;
    for (var idx=0; idx<ass_itms.length; idx++) {
        attachment = "";
        for (var i=0; i<ass_itms[idx].attach.length; i++) {
            atchment = "";
            for (var j=0; j<ass_itms[idx].attach[i].length; j++) {
                atch = "";
                for (var k=0; k<ass_itms[idx].attach[i][j].length; k++)
                    if (ass_itms[idx].attach[i][j][k] != null)
                        atch += ":" + k;
                atchment += "#" + atch.substr(1);
            }
            attachment += "|" + atchment.substr(1);
        }
        
        $("#attach_" + idx).val(attachment.substr(1));
        $("#scores_" + idx).val(implode_arr(ass_itms[idx].scores, '|', ':'));
        $("#comments_" + idx).val(implode_arr(ass_itms[idx].comments, '~~##~~', '~#~'));
        $("#notes_" + idx).val(implode_arr(ass_itms[idx].Notes, '~~##~~', '~#~'));
    }
}

function sort(y) {
    $("#img_sort").remove();
    var img = '<img id="img_sort" src="/images/descend.gif" width="10" height="10" border="0">';
    switch (y) {
        case -1:
            order.sort(SortName);
            $("#col_nam").append(img);
            break;
        default:
            order.sort(SortScore);
            $("#col_score").append(img);
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
    return ass_itms[b].scores[grp][col] - ass_itms[a].scores[grp][col];
}

function SortName(a, b) {
    if (ass_itms[a].name < ass_itms[b].name)
        return -1
    if (ass_itms[a].name > ass_itms[b].name)
        return 1
    return 0
}

$(document).ready(function(){
    file_cab = $("#win_files");
    var str = $("#cls_ass").val()
    eval_nams = collection(str, '~#~', null);
    str = $("#cls_ass_state").val()
    eval_chk = collection(str, '|', null);
    str = $("#cls_sub").val()
    sub_nams = collections(str, '~~##~~', '~#~', null);
    str = $("#cls_sub_state").val()
    sub_chk = collections(str, '|', '#', null);
    gen_ass();
    
    order = new Array(stud_cnt);
    for (var i=0; i < stud_cnt; i++) {
        ass_itms.push(new ass_itm($("#stud_name_" + i).html(), $("#scores_" + i).val(),
            $("#prvw_" + i).val(), $("#comments_" + i).val(), $("#notes_" + i).val()));
        order[i] = i;
    }
    load_dt(0, 0);
});
    
