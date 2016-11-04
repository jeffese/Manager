// JavaScript Document
var crs_grps = new Array(1);
var course_itms = new Array();
var crs_names = new Array();
var crs_ids = new Array();
var isEdit;
var tot = 0, gp_tot = 0, radio = 0;

function course_itm(crs_id, name, gp, grp, elect, choice) {
    var cnt = course_itms.length;
    this.id = cnt;
    this.cmp = "course_" + cnt;
    this.name = name;
    this.crs_id = parseInt(crs_id);
    this.gp = parseInt(gp);
    this.grp = parseInt(grp);
    this.elect = parseInt(elect);
    this.choice = grp == 0 && this.elect == 1 ? 1 : parseInt(choice);
    this.parent = "";
    
    var sel = this.choice == 1 ? ' checked="checked"' : '';
    var dis = isEdit ? '' : ' disabled="disabled"';
    var chk = 0;
    var chkname = radio;
    var typ = "checkbox";
    var event = isEdit ? ' onclick="course_itms[' + this.id + '].onChangeVal()"' : '';
    if (grp > 0) {
        chk = crs_grps[grp].kids.length;
        crs_grps[grp].kids.push(this.id);
        chkname = 'g_' + crs_grps[grp].id;
    }
    if (this.elect == 1 || grp > 0 && crs_grps[grp].choice == 1) {
        typ = "radio";
    }
    radio++;
    
    switch (this.elect) {
        case 1:
            this.parent = "bx_core";
            break;
        case 2:
            this.parent = "bx_elect";
            break;
        case 3:
            this.parent = "bx_over";
            break;
    }
    
    this.make = function() {
        html = '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px" id="' + this.cmp + 
        '"><tr><td class="bp_tl"></td><td class="bp_tp"></td><td class="bp_tr"></td></tr><tr>' +
        '<td class="bp_lf"></td><td class="bp_center"><table border="0" cellspacing="0" cellpadding="2">' +
        '<tr><td width="16" class="black-normal"><input name="crs_sel_' + chkname + '" type="' + typ +
        '" id="crs_sel_' + chkname + '_' + chk + '" value="' + chk + '"' + sel + dis + event + ' /></td>' +
        '<td class="black-normal" width="100%"><b>' + this.name + '</b></td><td>&nbsp;</td>' +
        '<td class="black-normal">GP:</td><td width="100%" class="black-normal"><b>' + this.gp + '</b></td>' +
        '</tr></table></td><td class="bp_rt"></td></tr><tr>' +
        '<td class="bp_bl"></td><td class="bp_bt"></td><td class="bp_br"></td></tr></table>';
        if (this.grp == 0) {
            $("#" + this.parent).append(html);
        } else {
            $("#box_" + this.grp).append(html);
        }
    }
    
    this.onChangeVal = function() {
        if (typ == "radio") {
            kids = crs_grps[grp].kids;
            for (c = 0; c < kids.length; c++) {
                course_itms[kids[c]].choice = 0;
            }
            this.choice = 1;
        } else {
            this.choice = $('#crs_sel_' + chkname + '_' + chk).is(":checked") ? 1 : 0;
        }
        collateObj();
    }
}

function course_grp(elect, choice, min, max, min_gp, max_gp) {
    var cnt = crs_grps.length;
    this.id = cnt;
    this.cmp = "group_" + cnt;
    this.box = "box_" + cnt;
    
    this.min = parseInt(min);
    this.max = parseInt(max);
    this.min_gp = parseInt(min_gp);
    this.max_gp = parseInt(max_gp);
    this.choice = parseInt(choice);
    this.elect = parseInt(elect);
    this.kids = new Array();
    this.gp = 0;
    this.tot = 0;
    
    if (elect == 1) {
        this.parent = "bx_core";
    } else {
        this.parent = "bx_elect";
    }
    
    this.make = function() {
        var seltab = this.choice == 1 ? '' : 
        '<td bgcolor="#ff3333" class="boldwhite1">Limits</td><td bgcolor="#333333" class="boldwhite1">No.</td>' +
        '<td>Min:</td><td><b>' + this.min + '</b></td><td>&nbsp;</td><td>Max:</td><td><b>' +
        this.max + '</b></td><td>&nbsp;</td><td>Val:</td><td><input type="text" size="3" id="tot_' + this.id + 
        '" readonly="readonly" value="0" /></td><td>&nbsp;</td><td bgcolor="#333333" class="boldwhite1">GP</td>' +
        '<td>Min:</td><td><b>' + this.min_gp + '</b></td><td>&nbsp;</td><td>Max:</td><td><b>' + this.max_gp + 
        '</b></td><td>&nbsp;</td><td>Val:</td><td><input type="text" size="3" id="totgp_' + this.id + 
        '" readonly="readonly" value="0" /></td>';
        html = '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px" id="' + this.cmp + '">' +
        '<tr><td class="bo_tl"></td><td class="bo_tp"></td><td class="bo_tr"></td></tr>' +
        '<tr><td class="bo_lf"></td><td class="bo_center"><table border="0" cellspacing="0" cellpadding="2">' +
        '<tr><td class="black-normal"><table border="0" cellpadding="2" cellspacing="0">' +
        '<tr>' + seltab + '</tr></table></td></tr><tr><td class="black-normal" id="box_' + this.id + 
        '"></td></tr></table></td><td class="bo_rt"></td></tr><tr>' +
        '<td class="bo_bl"></td><td class="bo_bt"></td><td class="bo_br"></td></tr></table>';
        $("#" + this.parent).append(html);
    }
}

function collateObj() {
    var crs_val = "";
    gp_tot = 0, tot = 0;
    
    for (j=1; j<crs_grps.length; j++) {
        crs_grps[j].tot = 0;
        crs_grps[j].gp = 0;
    }
    
    for (i=0; i<course_itms.length; i++) {
        crs = course_itms[i];
        if (crs.choice == 1) {
            crs_val += "|" + crs.crs_id;
            gp_tot += crs.gp;
            tot++;
            grp = crs.grp;
            
            if (grp > 0 && crs_grps[grp].choice == 2) {
                crs_grps[grp].gp += crs.gp;
                crs_grps[grp].tot++;
                $("#tot_" + grp).val(crs_grps[grp].tot);
                $("#totgp_" + grp).val(crs_grps[grp].gp);
            }
        }
    }
    
    $("#courses").val(crs_val.substr(1));
    $("#gp").val(gp_tot);
    $("#tot").val(tot);
    $("#totgp").val(gp_tot);
}

function prepView() {
    for (i=1; i<inf_lst.length; i++) {
        grp_vals = inf_lst[i].split(":");
        crs_grps.push(new course_grp(grp_vals[0], grp_vals[1], grp_vals[2], grp_vals[3], grp_vals[4], grp_vals[5]));
        crs_grps[crs_grps.length - 1].make();
    }

    for (i=0; i<crs_lst.length; i++) {
        chk = seek(sel_lst, crs_lst[i]) > -1 ? 1 : 0;
        course_itms.push(new course_itm(crs_lst[i], getNameIdx(crs_lst[i]), gps_lst[i], grp_lst[i], cor_lst[i], chk));
        course_itms[course_itms.length - 1].make();
    }
    collateObj();
}

function vetGPs() {
    collateObj();
    
    for (j=1; j<crs_grps.length; j++) {
        grp = crs_grps[j];
        
        if (grp.elect == 1) {
            for (k=0; k < grp.kids.length; k++) {
                if (course_itms[grp.kids[k]].choice == 1) {
                    break;
                } else if (k == grp.kids.length - 1) {
                    popmsg(document.getElementById(grp.cmp), 
                        "You must select one "+top.CRS_NAME+" in this group");
                    return false;
                }
            }
        }
        
        if ((grp.min > 0 && grp.tot < grp.min) || 
            (grp.max > 0 && grp.tot > grp.max)) {
            popmsg(document.getElementById(grp.cmp), "Total Selected "+top.CRS_NAME+" for this group cannot be " +
                (grp.min > 0 ? "less than " + grp.min : "") + (grp.min > 0 && grp.max > 0 ? " or " : "") +
                (grp.max > 0 ? "greater than " + grp.max : ""));
            return false;
        }
        
        if ((grp.min_gp > 0 && grp.gp < grp.min_gp) || 
            (grp.max_gp > 0 && grp.gp > grp.max_gp)) {
            popmsg(document.getElementById(grp.cmp), "Total GP for this group cannot be " +
                (grp.min_gp > 0 ? "less than " + grp.min_gp : "") + (grp.min_gp > 0 && grp.max_gp > 0 ? " or " : "") +
                (grp.max_gp > 0 ? "greater than " + grp.max_gp : ""));
            return false;
        }
    }
    
    if ((min > 0 && tot < min) || 
        (max > 0 && tot > max)) {
        popmsg(document.getElementById('tot'), "Total Selected "+top.CRS_NAME+" cannot be " +
            (min > 0 ? "less than " + min : "") + (min > 0 && max > 0 ? " or " : "") +
            (max > 0 ? "greater than " + max : ""));
        return false;
    }
    if ((min_gp > 0 && gp < min_gp) || 
        (max_gp > 0 && gp > max_gp)) {
        popmsg(document.getElementById('totgp'), "Total GP cannot be " +
            (min_gp > 0 ? "less than " + min_gp : "") + (min_gp > 0 && max_gp > 0 ? " or " : "") +
            (max_gp > 0 ? "greater than " + max_gp : ""));
        return false;
    }

    return true;
}

function getNameIdx(crsid) {
    for (m=0; m<crs_ids.length; m++) {
        if (crsid == crs_ids[m])
            return crs_names[m];
    }
    return "";
}