// JavaScript Document
var crs_grps = new Array(1);
var course_itms = new Array();
var crs_names = new Array();
var crs_ids = new Array();
var lec_names = new Array();
var lec_ids = new Array();
var lst = null, isEdit;

function course_itm(crs_id, crs_nam, lec_id, lec_nam, gp, grp, elect, lst_ids) {
    var cnt = course_itms.length;
    this.id = cnt;
    this.cmp = "course_" + cnt;
    this.crs_id = crs_id;
    this.crs_nam = crs_nam;
    this.lec_id = lec_id;
    this.lec_nam = lec_nam;
    this.gp = gp;
    this.grp = parseInt(grp);
    this.elect = parseInt(elect);
    this.lst_ids = parseInt(lst_ids);
    this.parent = "";
    
    switch (this.elect) {
        case 0:
            this.parent = "coursewin";
            break;
        case 1:
            this.parent = "bx_core";
            break;
        case 2:
            this.parent = "bx_elect";
            break;
    }
    
    this.make = function() {
        var span = "";
        var br = '';
        var lect = '';
        if (this.lst_ids != -1) {
            span = ' colspan="3"';
            br = '</tr><tr>';
        } else {
            br = '<td>&nbsp;</td>';
            var newstr = isEdit ? cmb_lecturer.replace(/_cmb_/gi, "lecturer_" + this.id).replace(/_id_/gi, this.id) : this.lec_nam;
            lect = '<td class="titles" nowrap="nowrap">' + lect_abv + ':</td><td nowrap="nowrap">&nbsp;' + newstr + '</td></td>';
        }
        var imgdel = grp == 0 && isEdit ? '<a href="javascript: void(0)" onclick="course_itms[' + this.id + 
        '].del()"><img src="/images/failed.png" width="16" height="16" /></a>' : '&nbsp;';
        var txt_gp = isEdit ? '<input name="gp_' + this.id + '" type="text" id="gp_' + this.id + 
        '" value="' + this.gp + '" size="3" onchange="numme(this, 0); course_itms[' + this.id + '].onChangeVal(1, this.value)" />' :
        this.gp;
        
        var html = '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px" id="' + this.cmp + 
        '"><tr><td class="bp_tl"></td><td class="bp_tp"></td><td class="bp_tr"></td></tr><tr>' +
        '<td class="bp_lf"></td><td class="bp_center"><table border="0" cellspacing="0" cellpadding="2">' +
        '<tr><td class="black-normal" nowrap="nowrap"' + span + '><b>' + this.crs_nam + '</b></td>' + br +
        '<td class="black-normal">GP:</td><td class="black-normal">' + txt_gp + '</td>' + lect +
        '<td width="16" class="black-normal">' + imgdel + '</td></tr></table></td><td class="bp_rt"></td>' +
        '</tr><tr><td class="bp_bl"></td><td class="bp_bt"></td><td class="bp_br"></td></tr></table>';
        if (this.grp == 0)
            $("#" + this.parent).append(html);
        else
            $("#box_" + this.grp).append(html);
        if (isEdit && this.lst_ids == -1) {
            var cmb = document.getElementById('lecturer_' + this.id);
            var i = 0;
            while (i < cmb.length) {
                if (cmb.options[i].value == this.lec_id)
                    break;
                i++;
            }
            if (i == cmb.length && this.lec_id > 0)
                cmb.options[i] = new Option(this.lec_name, this.lec_id);
            if (cmb.length > i)
                cmb.selectedIndex = i;
        }
    }
    
    this.remove = function() {
        $("#" + this.cmp).remove();
        course_itms[this.id] = null;
        
        if (this.lst_ids != -1)
            lst.options[this.lst_ids].selected = false;
    }
    
    this.update = function() {
        collateObj();
    }
    
    this.del = function() {
        this.remove();
        this.update();
    }
    
    this.onChangeVal = function(cmp, val) {
        if (cmp == 1) {
            this.gp = val;
        } else {
            this.lec_id = val;
        }
        if (this.elect > 0)
            collateObj();
    }
}

function course_grp(elect, choice, min, max, min_gp, max_gp) {
    var cnt = crs_grps.length;
    this.id = cnt;
    this.ord = cnt;
    this.cmp = "group_" + cnt;
    this.choice = choice;
    this.min = min;
    this.max = max;
    this.min_gp = min_gp;
    this.max_gp = max_gp;
    this.elect = parseInt(elect);
    
    if (elect == 1) {
        this.parent = "bx_core";
    } else {
        this.parent = "bx_elect";
    }
    
    this.make = function() {
        var alt = this.choice == "1" ? ' checked="checked"' : '';
        var sel = this.choice == "2" ? ' checked="checked"' : '';
        var choiceable = isEdit ? '' : ' disabled="disabled"';
        var imgdel = isEdit ? '<a href="javascript: void(0)" onclick="crs_grps[' + this.id + 
        '].del()"><img src="/images/failed.png" width="16" height="16" /></a>' : '&nbsp;';
        var choices = elect == 1 ? '<b>Alternatives</b>' + imgdel : '<table cellpadding="2" cellspacing="0"><tr>' +
        '<td><input name="choice_' + this.id + '" type="radio" id="choice_0_' + this.id + '" value="1"' + alt + 
        ' onclick="crs_grps[' + this.id + '].onChangeVal(this, 1)"' + choiceable + ' /></td>' +
        '<td class="black-normal"><b>Alternatives</b></td>' +
        '<td><input type="radio" name="choice_' + this.id + '" value="2" id="choice_1_' + this.id + '"' + sel + 
        ' onclick="crs_grps[' + this.id + '].onChangeVal(this, 2)"' + choiceable + ' /></td>' +
        '<td class="black-normal"><b>Selections</b></td><td>' + imgdel + '</td></tr></table>';
        var display = this.choice == "1" ? 'none' : 'block';
        var min_txt = isEdit ? '<input name="min_' + this.id + '" type="text" id="min_' + this.id + '" value="' + 
        this.min + '" size="3" onchange="crs_grps[' + this.id + '].onChangeVal(this, 3)" />' :
        '<b>' + this.min + '</b>';
        var max_txt = isEdit ? '<input name="max_' + this.id + '" type="text" id="max_' + this.id + '" value="' + 
        this.max + '" size="3" onchange="crs_grps[' + this.id + '].onChangeVal(this, 4)" />' : 
        '<b>' + this.max + '</b>';
        var min_gp_txt = isEdit ? '<input name="min_gp_' + this.id + '" type="text" id="min_gp_' + this.id + 
        '" value="' + this.min_gp + '" size="3" onchange="crs_grps[' + this.id + '].onChangeVal(this, 5)" />' : 
        '<b>' + this.min_gp + '</b>';
        var max_gp_txt = isEdit ? '<input name="max_gp_' + this.id + '" type="text" id="max_gp_' + this.id + 
        '" value="' + this.max_gp + '" size="3" onchange="crs_grps[' + this.id + '].onChangeVal(this, 6)" />' : 
        '<b>' + this.max_gp + '</b>';
    
        html = '<table border="0" cellpadding="0" cellspacing="0" style="margin:1px" id="' + this.cmp + '">' +
        '<tr><td class="bo_tl"></td><td class="bo_tp"></td><td class="bo_tr"></td></tr>' +
        '<tr><td class="bo_lf"></td><td class="bo_center"><table border="0" cellspacing="0" cellpadding="2">' +
        '<tr><td class="black-normal"><table width="100%" border="0" cellpadding="2" cellspacing="0">' +
        '<tr><td>' + choices + '</td></tr>' +
        '<tr><td><table border="0" cellspacing="0" cellpadding="2" style="display:' + display + 
        '" id="sel_box_' + this.id + '">' +
        '<tr><td bgcolor="#333333"><span class="boldwhite1">Selection Limits</span></td><td>Min:</td>' +
        '<td>' + min_txt + '</td><td>Max:</td><td>' + max_txt + '</td></tr>' +
        '<tr><td bgcolor="#333333"><span class="boldwhite1">GP Limits</span></td><td>Min:</td>' +
        '<td>' + min_gp_txt + '</td><td>Max:</td><td>' + max_gp_txt + '</td>' +
        '</tr></table></td></tr></table></td></tr><tr><td class="black-normal" id="box_' + this.id + 
        '"></td></tr></table></td><td class="bo_rt"></td></tr><tr>' +
        '<td class="bo_bl"></td><td class="bo_bt"></td><td class="bo_br"></td></tr></table>';
        $("#" + this.parent).append(html);
    }
    
    this.remove = function() {
        for (j=0; j<course_itms.length; j++) {
            if (course_itms[j] != null && course_itms[j].grp == this.id) {
                course_itms[j].remove();
            }
        }
        $("#" + this.cmp).remove();
        crs_grps[this.id] = null;
    }
    
    this.update = function() {
        collateObj();
    }
    
    this.del = function() {
        this.remove();
        this.update();
    }
    
    this.onChangeVal = function(elem, i) {
        if (i > 2) {
            numme(elem, 0);
            switch (i) {
                case 3:
                    this.min = elem.value;
                    break;
                case 4:
                    this.max = elem.value;
                    break;
                case 5:
                    this.min_gp = elem.value;
                    break;
                case 6:
                    this.max_gp = elem.value;
                    break;
            }
        } else {
            this.choice = i;
            if (i == 1) {
                $("#sel_box_" + this.id).hide();
                this.min = 0;
                this.max = 0;
                this.min_gp = 0;
                this.max_gp = 0;
            } else
                $("#sel_box_" + this.id).show();
        }
        this.update();
    }
}

function collateObj() {
    var crs_val = "", lec_val = "", gps_val = "", cor_val = "", grp_val = "", inf_val = "1";
    var ord = 1;
    
    for (j=1; j<crs_grps.length; j++) {
        if (crs_grps[j] != null){
            inf_val += "|" + crs_grps[j].elect + ":" + crs_grps[j].choice 
            + ":" + crs_grps[j].min + ":" + crs_grps[j].max
            + ":" + crs_grps[j].min_gp + ":" + crs_grps[j].max_gp;
            crs_grps[j].ord = ord;
            ord++;
        }
    }
    
    for (i=0; i<course_itms.length; i++) {
        if (course_itms[i] != null) {
            crs_val += "|" + course_itms[i].crs_id;
            lec_val += "|" + course_itms[i].lec_id;
            gps_val += "|" + course_itms[i].gp;
            cor_val += "|" + course_itms[i].elect;
            grp_val += "|" + (course_itms[i].grp == 0 ? 0 : crs_grps[course_itms[i].grp].ord);
        }
    }
    
    $("#courses").val(crs_val.substr(1));
    $("#lecturers").val(lec_val.substr(1));
    $("#gps").val(gps_val.substr(1));
    $("#core_elec").val(cor_val.substr(1));
    $("#grp").val(grp_val.substr(1));
    $("#grp_inf").val(inf_val);
}

function setGroup(grp) {
    if (grp == 1) {
        $("#grp_box").hide();
        $("#lstcourse").removeAttr("multiple");
        $("#choice_0").attr("checked", "checked");
        $("#choice_1").removeAttr("checked");
        limitsOff();
    } else {
        if ($("#elective").val() == 2) {
            $("#grp_box").show();
        }
        $("#lstcourse").attr("multiple", "multiple");
    }
    setCourse();
}

function prepView() {
    var str = $("#courses").val();
    var crs_lst = str.length == 0 ? new Array() : str.split("|");
    str = $("#lecturers").val();
    var lec_lst = str.length == 0 ? new Array() : str.split("|");
    str = $("#gps").val();
    var gps_lst = str.length == 0 ? new Array() : str.split("|");
    str = $("#core_elec").val();
    var cor_lst = str.length == 0 ? new Array() : str.split("|");
    str = $("#grp").val();
    var grp_lst = str.length == 0 ? new Array() : str.split("|");
    str = $("#grp_inf").val();
    var inf_lst = str.length == 0 ? new Array() : str.split("|");

    delCourses();
    delGroups();
    crs_grps = new Array(1);
    course_itms = new Array();
    var grp_vals;
    
    for (i=1; i<inf_lst.length; i++) {
        grp_vals = inf_lst[i].split(":");
        crs_grps.push(new course_grp(grp_vals[0], grp_vals[1], grp_vals[2], grp_vals[3], grp_vals[4], grp_vals[5]));
        crs_grps[crs_grps.length - 1].make();
    }

    for (i=0; i<crs_lst.length; i++) {
        course_itms.push(new course_itm(crs_lst[i], getNameIdx(crs_lst[i], crs_ids, crs_names), lec_lst[i], getNameIdx(lec_lst[i], lec_ids, lec_names), gps_lst[i], grp_lst[i], cor_lst[i], -1));
        course_itms[course_itms.length - 1].make();
    }
}

function setSelOpt(opt) {
    if (opt == 1) {
        $("#sel_box").hide();
        limitsOff();
    } else {
        $("#sel_box").show();
    }
}

function limitsOff() {
    $("#min").val(0);
    $("#max").val(0);
    $("#min_gp").val(0);
    $("#max_gp").val(0);
}

function setCourse() {
    selCourse();
    delCourse();
}

function selCourse() {
    var lstval;
        roll:
        for (i=0; i<lst.length; i++) {
            if (lst.options[i].selected) {
                lstval = lst.options[i].value.split('#');
                for (j=0; j<course_itms.length; j++) {
                    if (course_itms[j] != null && course_itms[j].crs_id == lstval[0]){
                        continue roll;
                    }
                }
                course_itms.push(new course_itm(lstval[0], lst.options[i].text, lstval[1], lstval[2], 1, 0, 0, i));
                course_itms[course_itms.length - 1].make();
            }
        }
    delCourse();
}

function delCourse() {
    var lstval;
        kill:
        for (j=0; j<course_itms.length; j++) {
            for (i=0; i<lst.length; i++) {
                lstval = lst.options[i].value.split('#');
                if (course_itms[j] != null && course_itms[j].crs_id == lstval[0] && !lst.options[i].selected) {
                    course_itms[j].remove();
                    continue kill;
                }
            }
        }
}

function delCourses() {
    for (j=0; j<course_itms.length; j++) {
        if (course_itms[j] != null) {
            course_itms[j].remove();
        }
    }
}

function delGroups() {
    for (j=0; j<crs_grps.length; j++) {
        if (crs_grps[j] != null) {
            crs_grps[j].remove();
        }
    }
}

function addCourses() {
    var cnt = 0, grp, grpid = 0, cho;
    var doc = parent.parent.document;
    var txt_crs = doc.getElementById("courses");
    var txt_lec = doc.getElementById("lecturers");
    var txt_gps = doc.getElementById("gps");
    var txt_cor = doc.getElementById("core_elec");
    var txt_grp = doc.getElementById("grp");
    var txt_grpinf = doc.getElementById("grp_inf");
    var crs_val = "", lec_val = "", gps_val = "", cor_val = "", grp_val = "", inf_val = "";
    var elc = $("#elective").val();
    
    grp = $('input:radio[name=grp]:checked').val();
    if (grp == 2) {
        grpid = txt_grpinf.value.split("|").length;
        inf_val = "|" + elc;
        elc = 0;
        cho = $('input:radio[name=choice]:checked').val();
        inf_val += ':' + cho;
        if (cho == 2) {
            inf_val += ':' + $("#min").val() + ':' + $("#max").val() + 
            ':' + $("#min_gp").val() + ':' + $("#max_gp").val();
        } else {
            inf_val += ':0:0:0:0';
        }
    }
    
    for (j=0; j<course_itms.length; j++) {
        if (course_itms[j] != null){
            cnt++;
            crs_val += "|" + course_itms[j].crs_id;
            lec_val += "|" + course_itms[j].lec_id;
            gps_val += "|" + course_itms[j].gp;
            cor_val += "|" + elc;
            grp_val += "|" + grpid;
            parent.parent.crs_ids.push(course_itms[j].crs_id);
            parent.parent.crs_names.push(course_itms[j].crs_nam);
            parent.parent.lec_ids.push(course_itms[j].lec_id);
            parent.parent.lec_names.push(course_itms[j].lec_nam);
        }
    }
    
    if (cnt == 0) {
        alert("No "+top.CRS_NAME+" Selected!!");
    } else if (grp == 2 && cnt == 1) {
        alert("A Group must have two(2) or more "+top.CRS_NAME+"s Selected!!");
    } else {
        txt_crs.value = smartConcat(txt_crs.value, crs_val, "|");
        txt_lec.value = smartConcat(txt_cor.value, lec_val, "|");
        txt_gps.value = smartConcat(txt_gps.value, gps_val, "|");
        txt_cor.value = smartConcat(txt_cor.value, cor_val, "|");
        txt_grp.value = smartConcat(txt_grp.value, grp_val, "|");
        txt_grpinf.value += inf_val;
        
        parent.parent.prepView();
        parent.parent.GB_hide();
    }
}

function smartConcat(str0, str1, sep) {
    return str0 += str0.length == 0 ? str1.substr(sep.length) : str1;
}