// JavaScript Document
var att_itms = new Array(), order;
var dt_header = new Array();
var col = 0;

function att_itm(name, attend) {
    var cnt = att_itms.length;
    this.cmp = "row_" + cnt;
    this.id = cnt;
    this.name = name;
    this.total = 0;
    this.attend = attend.length > 0 ? attend.split("|") : new Array();
}

function collate() {
    for (var i=0; i<att_itms.length; i++) {
        cummulate(i);
    }
    packDates();
}

function cummulate(id) {
    var tot = 0, val, attend = "";
    for (var a=0; a < att_dates.length; a++) {
        if (att_dates[a] != null) {
            val = $("#resume_"+id+"_"+ a).is(":checked") ? "1" : "0";
            val += $("#close_"+id+"_"+ a).is(":checked") ? "1" : "0";
            val = parseInt(val, 2);
            attend += "|" + val;
            tot += val;
        }
    }
    att_itms[id].total = tot;
    $("#attend_" + id).val(attend.substr(1));
    $("#total_" + id).val(tot);
}

function packDates() {
    var dates = "";
    for (var i=0; i<att_dates.length; i++) {
        if (att_dates[i] != null)
            dates += "|"+att_dates[i];
    }
    $("#attend_date").val(dates.substr(1));
}

function sort(y) {
    col = y;
    $("#img_sort").remove();
    var img = '<img id="img_sort" src="/images/descend.gif" width="10" height="10" border="0">';
    switch (col) {
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
    return att_itms[b].attend[col] - att_itms[a].attend[col];
}

function SortTotal(a, b) {
    return att_itms[b].total - att_itms[a].total;
}

function SortName(a, b) {
    if (att_itms[a].name < att_itms[b].name)
        return -1
    if (att_itms[a].name > att_itms[b].name)
        return 1
    return 0
}

function SetPosition() {
    var j, k;
    order.sort(SortTotal);
    for (var i=0; i < stud_cnt; i++) {
        j = order[i];
        pos = i + 1;
        if (i > 0) {
            k = order[i-1];
            pos = att_itms[j].total == att_itms[k].total ? att_itms[k].pos : pos;
        }
        att_itms[j].pos = pos;
        $("#pos_" + j).val(pos);
    }
}

function lay_view() {
    var arr, val;
    for (var i=0; i<att_dates.length; i++) {
        arr = (new Date(att_dates[i] * 1000)).toString().split(" ");
        dt_header.push(arr[0] + " " + arr[2] + " " + arr[1] + " " + arr[3]);
        insert_date();
    }
    for (var l=0; l<stud_cnt; l++) {
        att_itms.push(new att_itm($("#stud_name_" + l).html(), $("#attend_" + l).val()));
        order[l] = l;
        
        for (var j=0; j < att_dates.length; j++) {
            val = att_itms[l].attend[j];
            if (val == 1 || val == 3) {
                $("#close_"+l+"_"+j).attr("checked", "checked");
            }
            if (val == 2 || val == 3) {
                $("#resume_"+l+"_"+j).attr("checked", "checked");
            }
        }
    }
    collate();
}

function insert_date() {
    var chk_str = isEdit ? "" : ' disabled="disabled"';
    var att_str = '<td align="center" bgcolor="_clr_" id="att_###_@@@">\n\
            <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>\n\
            <input type="checkbox"'+chk_str+' name="resume_###_@@@" id="resume_###_@@@" value="1" onclick="cummulate(###)" /></td><td>\n\
            <input type="checkbox"'+chk_str+' name="close_###_@@@" id="close_###_@@@" value="1" onclick="cummulate(###)" /></td></tr></table></td>';
    var but_str = '<td align="center" bgcolor="#000000" id="blk_###">\n\
            <a href="javascript: void(0)" onclick="check_all(###)">\n\
            <img src="/images/but_mini_all.png" width="34" height="20" /><br /></a>\n\
            <a href="javascript: void(0)" onclick="edit_date(###)">\n\
            <img src="/images/but_mini_edit.png" width="34" height="20" /></a><br />\n\
            <a href="javascript: void(0)" onclick="del_date(###)">\n\
            <img src="/images/but_mini_del.png" width="34" height="20" /></a></td>'
    
    var idx = dt_header.length - 1;
    if (isEdit)
        $("#blk_add").before(but_str.replace(/###/gi, idx));
    $("#bl_tot").before('<td id="bl_'+idx+'"><a id="col_'+idx+'" class="boldwhite1" \n\
            href="javascript: void(0)" onclick="sort('+idx+')">'+dt_header[idx]+'</a></td>');
    
    for (var i=0; i < stud_cnt; i++) {
        var rowdefcolor = i%2==1 ? "#E5E5E5" : "#D5D5D5";
        ($("#tot_cell_" + i)).before(att_str.replace(/_clr_/gi, rowdefcolor).replace(/###/gi, i).replace(/@@@/gi, idx));
    }
}

function add_date(date) {
    var dt = $("#newdt").val();
    var msec = (new Date(date)).valueOf();
    att_dates.push(msec / 1000);
    dt_header.push(eng_date(dt));
    $("#newdt").hide();
    insert_date()
    collate();
}

function del_date(idx) {
    $("#blk_"+idx).remove();
    $("#bl_"+idx).remove();
    for (var i=0; i < stud_cnt; i++) {
        ($("#att_"+i+"_"+idx)).remove();
        att_dates[idx] = null;
        dt_header[idx] = null;
    }
    collate();
}

function edit_date(idx) {
    var txtbox = '<input name="txtdt" type="text" id="txtdt" style="width:70px" \n\
            value="'+dtStrFromDate(new Date(att_dates[idx] * 1000), true)+'" readonly="readonly" />';
    $("#bl_"+idx).html(txtbox);
    var dt_edit = new dhtmlxCalendarObject('txtdt', true, {
        isYearEditable: true, 
        isMonthEditable: true
    });
    dt_edit.attachEvent("onClick",function(date){   
        rem_txtdt(idx, date);
    })
    dt_edit.setSkin('dhx_black');
}

function rem_txtdt(idx, date) {
    var dt = $("#txtdt").val();
    var msec = (new Date(date)).valueOf();
    att_dates[idx] = msec / 1000;
    dt_header[idx] = eng_date(dt);
    $("#bl_"+idx).html(dt_header[idx]);
    packDates();
}

function check_all(idx) {
    if ($("#resume_0_"+idx).is(":checked")) {
        for (var i=0; i < stud_cnt; i++) {
            $("#resume_"+i+"_"+idx).removeAttr("checked");
            $("#close_"+i+"_"+idx).removeAttr("checked");
        }
    } else {
        for (var j=0; j < stud_cnt; j++) {
            $("#resume_"+j+"_"+idx).attr("checked", "checked");
            $("#close_"+j+"_"+idx).attr("checked", "checked");
        }
    }
    collate();
}

$(document).ready(function(){
    order = new Array(stud_cnt);
    lay_view();
    SetPosition();
});
    
