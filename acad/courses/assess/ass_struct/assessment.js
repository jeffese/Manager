// JavaScript Document
var ass_itms = new Array();
var ass_grps = new Array(1);
var ass_typs = new Array();
var arith_str = new Array("Sum", "Average", "Maximum");
var isEdit, tot_per, isClass;

function ass_itm(name, code, ca, state, per, max, attach, grp, isNew) {
    var cnt = ass_itms.length;
    this.id = cnt;
    this.cmp = "assess_" + cnt;
    this.name = name;
    this.code = code;
    this.ca = parseInt(ca);
    this.state = parseInt(state);
    this.per = parseFloat(per);
    this.max = parseInt(max);
    this.grp = parseInt(grp);
    this.canEdit = isEdit && (this.grp == 0 && isClass || this.grp != 0 && !isClass);
    this.attach = attach;
    this.atch = attach.length > 0 ? attach.split("~#~") : new Array();
    this.parent = this.grp == 0 ? "bx_assess" : "box_" + this.grp;
    
    this.make = function() {
        var state_html = '&nbsp;';
        if (isNew) {
            state_html = '<a href="javascript: void(0)" onclick="ass_itms[' + this.id + 
            '].del()"><img src="/images/failed.png" width="16" height="16" /></a>';
        } else if (this.canEdit) {
            state_str = this.state == 1 ? ' checked="checked"' : "";
            state_html = '<input type="checkbox" name="chk_' + this.id + '" id="chk_' + this.id + 
            '" onclick="ass_itms[' + this.id + '].set()"' + state_str + ' />';
        }

        var txt_name = this.canEdit ? '<input name="name_' + this.id + '" type="text" id="name_' + this.id + 
        '" value="' + this.name + '" style="width:160px" onchange="ass_itms[' + this.id + 
        '].onChangeVal(this.value, 1)" />' : '<b>' + this.name + '</b>';
        var txt_code = this.canEdit ? '<input name="code_' + this.id + '" type="text" id="code_' + this.id + 
        '" value="' + this.code + '" style="width:34px" onchange="ass_itms[' + this.id + 
        '].onChangeVal(this.value, 2)" />' : '<b>' + this.code + '</b>';
        var txt_max = this.canEdit ? '<input name="max_' + this.id + '" type="text" id="max_' + this.id + 
        '" value="' + this.max + '" style="width:24px" onchange="numme(this, 0); ass_itms[' + this.id + 
        '].onChangeVal(this.value, 3)" />' : '<b id="max_' + this.id + '">' + this.max + '</b>';
        var txt_per = this.canEdit ? '<input name="per_' + this.id + '" type="text" id="per_' + this.id + 
        '" value="' + this.per + '" style="width:24px" onchange="numme(this, 0); ass_itms[' + this.id + 
        '].onChangeVal(this.value, 4)" />' : '<b id="per_' + this.id + '">' + this.per + '</b>';
        var txt_ca = this.canEdit ? '<select name="ca_' + this.id + '" id="ca_' + this.id + '" onchange="ass_itms[' + 
        this.id + '].onChangeVal(this.value, 6)"></select>' : '<b>' + getNameIdx(this.ca, ca_ids, ca_str) + '</b>';
    
        add_str = isEdit && !isClass ? '<a href="javascript: void(0)" onclick="ass_itms[' + this.id + 
        '].add_atch(-1)"><img src="/images/but_add.png" width="50" height="20" /></a>' : '';
        atch_box = isClass || !isEdit && this.atch.length == 0 ? '' : 
        '<table border="0" cellpadding="0" cellspacing="0" style="margin:1px" id="atch_' + this.id + '">' +
        '<tr><td class="bo_tl"></td><td class="bo_tp"></td><td class="bo_tr"></td></tr>' +
        '<tr><td rowspan="2" class="bo_lf"></td><td class="bo_title">Question Papers' +
        '<input name="prvw_' + this.id + '" id="prvw_' + this.id + '" value="' + this.attach + '" type="hidden"></td>' + 
        '<td rowspan="2" class="bo_rt"></td></tr><tr><td class="bo_center">' +
        '<table width="100%" border="0" cellpadding="0" cellspacing="0">' +
        '<tr><td class="black-normal" id="bx_atch_' + this.id + '"></td></tr><tr><td align="center">' + add_str + '</td>' +
        '</tr></table></td></tr><tr><td class="bo_bl"></td><td class="bo_bt"></td>' +
        '<td class="bo_br"></td></tr></table>';
        html = '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px; float:left" id="' + this.cmp + 
        '"><tr><td class="bp_tl"></td><td class="bp_tp"></td><td class="bp_tr"></td></tr><tr>' +
        '<td class="bp_lf"></td><td class="bp_center"><table border="0" cellspacing="0" cellpadding="2"><tr>' +
        '<td>Name:</td><td width="100%">' + txt_name + '</td><td>Code:</td><td>' + txt_code + 
        '</td><td>' + state_html + '</td></tr><tr><td colspan="5">' +
        '<table border="0" cellspacing="0" cellpadding="2"><tr><td>Type:</td><td>' + txt_ca + 
        '</td><td>&nbsp;</td><td><span id="mx_' + this.id + '">Max:</span></td><td>' + txt_max + 
        '</td><td>&nbsp;</td><td><span id="perc_' + this.id + '">%:</span></td><td>' + txt_per + 
        '</td></tr></table></td></tr><tr><td colspan="5">' + atch_box + 
        '</td></tr></table></td><td class="bp_rt"></td></tr><tr><td class="bp_bl"></td>' +
        '<td class="bp_bt"></td><td class="bp_br"></td></tr></table>';
        $("#" + this.parent).append(html);
        if (this.canEdit) {
            var cmb = document.getElementById("ca_" + this.id);
            var stat;
            for (var t=0; t<ca_ids.length; t++) {
                stat = this.ca == ca_ids[t];
                cmb.options[t] = new Option(ca_str[t], ca_ids[t], stat, stat);
            }
        }
        if (this.ca != 0) {
            $("#mx_" + this.id).hide();
            $("#max_" + this.id).hide();
            $("#atch_" + this.id).hide();
        }
    }
    
    this.add_atch = function(idx) {
        file = '';
        url = '<b>No File</b>';
        if (idx == -1) {
            num = this.atch.length;
            this.atch.push(null);
        } else {
            num = idx;
            file = this.atch[idx];
            url = '<a href="javascript: void(0)" onclick="return GB_showCenter(\'Assessment Questions\', \'/acad/courses/assess/archive/questions/' + ass_struct_id + '/' + this.id + '/' + file + '\', 600,600)" class="black-normal"><b>' + file + '</b></a>';
        }
        
        pv = this.id + '_' + num;
        del_str = isEdit ? '<td><a href="javascript: void(0)" onclick="ass_itms[' + this.id + 
        '].del_atch(' + num + ')"><img src="/images/b_drop.png" width="16" height="16" /></a></td>' : '';

        html = '<table border="0" cellpadding="0" cellspacing="0" id="prev_' + pv + '" style="float:left">' +
        '<tr><td class="bp_tl"></td><td class="bp_tp"></td><td class="bp_tr"></td></tr>' +
        '<tr><td class="bp_lf"></td><td class="bp_center"><table border="0" cellpadding="0" cellspacing="0"><tr>';
        html += isEdit ? '<td align="center">' + (num + 1) + '.&nbsp;' +
        '<input type="hidden" name="pv_' + pv + '" id="pv_' + pv + '" value="' + file + '" /></td>' +
        '<td id="pvw_' + pv + '">' + url + '&nbsp;</td>' +
        '<td width="120"><input type="file" name="preview_' + pv + '" id="preview_' + pv + '" value="" size="1" ' +
        'onchange="ass_itms[' + this.id + '].onChangeVal(this.value, 5,' + num + ')" /></td>' + del_str
        : '<td width="30" align="center">' + (num + 1) + '.&nbsp;</td><td>' + url + '</td>';
        html += '</tr></table></td><td class="bp_rt"></td></tr>' +
        '<tr><td class="bp_bl"></td><td class="bp_bt"></td><td class="bp_br"></td></tr></table>';
        $("#bx_atch_" + this.id).append(html);
    }
    
    this.load_atch = function() {
        for (j=0; j<this.atch.length; j++) {
            this.add_atch(j);
        }
    }
    
    this.set = function() {
        if($("#chk_" + this.id).is(':checked')){
            this.state = 1;
        } else {
            this.state = 0;
            this.per = 0;
            $("#per_" + this.id).val("0");
        }
    }
    
    this.del = function() {
        $("#" + this.cmp).remove();
        ass_itms[this.id] = null;
    }
    
    this.del_atch = function(idx) {
        $("#prev_" + this.id + '_' + idx).remove();
        this.atch[idx] = null;
    }
    
    this.onChangeVal = function(val, idx, fidx) {
        switch (idx) {
            case 1:
                this.name = trimme(val).replace(/\|/g,"#").replace(/:/g,";");
                break;
            case 2:
                this.code = trimme(val).replace(/\|/g,"#").replace(/:/g,";");
                break;
            case 3:
                this.max = parseInt(val);
                break;
            case 4:
                this.per = parseFloat(val);
                if (this.per != 0 && !$("#chk_" + this.id).is(':checked'))
                    $("#chk_" + this.id).attr("checked", "checked");
                break;
            case 5:
                this.atch[fidx] = val;
                $("#pvw_" + this.id + '_' + fidx).html(val);
                break;
            case 6:
                this.ca = parseInt(val);
                if (this.ca != 0) {
                    $("#mx_" + this.id).hide();
                    $("#max_" + this.id).hide();
                    $("#atch_" + this.id).hide();
                } else {
                    $("#mx_" + this.id).show();
                    $("#max_" + this.id).show();
                    $("#atch_" + this.id).show();
                }
                break;
            case 7:
                if (val == 0) {
                    $("#perc_" + this.id).hide();
                    $("#per_" + this.id).hide();
                } else {
                    $("#perc_" + this.id).show();
                    $("#per_" + this.id).show();
                }
                break;
        }
    }
}

function ass_grp(name, code, state, per, arith, isNew) {
    var cnt = ass_grps.length;
    this.id = cnt;
    this.ord = cnt;
    this.cmp = "group_" + cnt;
    this.name = name;
    this.code = code;
    this.state = parseInt(state);
    this.per = parseFloat(per);
    this.max = 100;
    this.arith = parseInt(arith);
    this.tot_per = 0;
    
    this.make = function() {
        var state_html = '&nbsp;';
        if (isNew) {
            state_html = '<a href="javascript: void(0)" onclick="ass_grps[' + this.id + 
            '].del()"><img src="/images/failed.png" width="16" height="16" /></a>';
        } else if (isEdit && isClass) {
            state_str = this.state == 1 ? ' checked="checked"' : "";
            state_html = '<input type="checkbox" name="grp_chk_' + this.id + '" id="grp_chk_' + this.id + 
            '" onclick="ass_grps[' + this.id + '].set()"' + state_str + ' />';
        }

        var txt_name = isEdit && isClass ? '<input name="grp_name_' + this.id + '" type="text" id="grp_name_' + this.id + 
        '" value="' + this.name + '" style="width:200px" onchange="ass_grps[' + this.id + 
        '].onChangeVal(this.value, 1)" />' : '<b>' + this.name + '</b>';
        var txt_code = isEdit && isClass ? '<input name="grp_code_' + this.id + '" type="text" id="grp_code_' + this.id + 
        '" value="' + this.code + '" style="width:34px" onchange="ass_grps[' + this.id + 
        '].onChangeVal(this.value, 2)" />' : '<b>' + this.code + '</b>';
        var txt_per = isEdit && isClass ? '<input name="grp_per_' + this.id + '" type="text" id="grp_per_' + this.id + 
        '" value="' + this.per + '" style="width:24px" onchange="numme(this, 0); ass_grps[' + this.id + 
        '].onChangeVal(this.value, 3)" />' : '<b>' + this.per + '</b>';
        var arith = isEdit && isClass ? '<select name="ass_typ_' + this.id + 
        '" id="cls_typ_' + this.id + '" onchange="ass_grps[' + this.id + 
        '].onChangeVal(this.value, 4)"><option value="0">Sum</option><option value="1">Average</option>' +
        '<option value="2">Maximum</option></select>' : arith_str[this.arith];
        var add_str = isEdit && !isClass ? '<a href="javascript: void(0)" onclick="addItm(' + this.id + 
        ')"><img src="/images/but_add.png" width="50" height="20" /></a>' : '';
    
        html = '<table border="0" cellpadding="0" cellspacing="0" style="margin:1px" id="' + this.cmp + 
        '"><tr><td class="bo_tl"></td><td class="bo_tp"></td><td class="bo_tr"></td></tr><tr>' +
        '<td class="bo_lf"></td><td class="bo_center"><table border="0" cellspacing="0" cellpadding="2">' + 
        '<tr><td>Name:</td><td width="100%">' + txt_name + '</td><td>&nbsp;</td><td>' + state_html + '</td></tr>' +
        '<tr><td colspan="4"><table border="0" cellspacing="0" cellpadding="2">' +
        '<tr><td>Code:</td><td>' + txt_code + '</td><td>&nbsp;</td><td>Collation:</td><td>' + arith + 
        '</td><td>&nbsp;</td><td>%:</td><td>' + txt_per + '</td></tr></table></td>' +
        '<tr><td colspan="4"><table width="100%" border="0" cellpadding="0" cellspacing="0">' +
        '<tr><td class="black-normal" id="box_' + this.id + '"></td></tr><tr><td align="center">' + 
        add_str + '</td></tr></table></td></tr></table></td><td class="bo_rt"></td></tr><tr>' +
        '<td class="bo_bl"></td><td class="bo_bt"></td><td class="bo_br"></td></tr></table>';
        $("#bx_assess").append(html);
        if (isEdit && isClass)
            document.getElementById("cls_typ_" + this.id).selectedIndex = this.arith;
    }
    
    this.set = function() {
        if($("#grp_chk_" + this.id).is(':checked')){
            this.state = 1;
        } else {
            this.state = 0;
            this.per = 0;
            $("#grp_per_" + this.id).val("0");
        }
    }
    
    this.del = function() {
        for (var j=0; j<ass_itms.length; j++) {
            if (ass_itms[j] != null && ass_itms[j].grp == this.id) {
                ass_itms[j].del();
            }
        }
        $("#" + this.cmp).remove();
        ass_grps[this.id] = null;
    }
    
    this.onChangeVal = function(val, idx) {
        switch (idx) {
            case 1:
                this.name = trimme(val).replace(/\|/g,"#").replace(/:/g,";");
                break;
            case 2:
                this.code = trimme(val).replace(/\|/g,"#").replace(/:/g,";");
                break;
            case 3:
                this.per = parseFloat(val);
                if (this.per != 0 && !$("#grp_chk_" + this.id).is(':checked'))
                    $("#grp_chk_" + this.id).attr("checked", "checked");
                break;
            case 4:
                this.arith = val;
                for (i=0; i<ass_itms.length; i++) {
                    if (ass_itms[i].grp == this.id) {
                        if (val == 0)
                            ass_itms[i].onChangeVal(1, 7);
                        else
                            ass_itms[i].onChangeVal(0, 7);
                    }
                }
                
                break;
        }
    }
}

function ass_typ(name, state, subs, sub_state, isNew) {
    var cnt = ass_typs.length;
    this.id = cnt;
    this.cmp = "ass_typ_" + cnt;
    this.name = name;
    this.state = parseInt(state);
    this.sub = subs.length > 0 ? subs.split('~#~') : new Array();
    this.sub_state = sub_state.length > 0 ? sub_state.split('#') : new Array();
    
    this.make = function() {
        var state_html = '&nbsp;';
        if (isNew) {
            state_html = '<a href="javascript: void(0)" onclick="ass_typs[' + this.id + 
            '].remove()"><img src="/images/failed.png" width="16" height="16" /></a>';
        } else if (isEdit) {
            state_str = this.state == 1 ? ' checked="checked"' : "";
            state_html = '<input type="checkbox" name="ass_chk_' + this.id + '" id="ass_chk_' + this.id + 
            '" onclick="ass_typs[' + this.id + '].set()"' + state_str + ' />';
        }

        var txt_name = isEdit ? '<input name="typ_name_' + this.id + '" type="text" id="typ_name_' + this.id + 
        '" value="' + this.name + '" style="width:200px" onchange="ass_typs[' + this.id + 
        '].name=trimme(this.value)" />' : '<b>' + this.name + '</b>';
    
        var add_str = isEdit ? '<a href="javascript: void(0)" onclick="ass_typs[' + this.id + 
        '].add(-1)"><img src="/images/but_add.png" width="50" height="20" /></a>' : '';
    
        html = '<table border="0" cellpadding="0" cellspacing="0" style="margin:10px; float:left" id="' + this.cmp + 
        '"><tr><td class="bp_tl"></td><td class="bp_tp"></td><td class="bp_tr"></td></tr><tr>' +
        '<td class="bp_lf"></td><td class="bp_center"><table border="0" cellspacing="0" cellpadding="2">' + 
        '<tr><td>Name:</td><td width="100%">' + txt_name + '</td><td>&nbsp;</td><td>' + state_html + '</td></tr>' +
        '<tr><td colspan="4"><table width="100%" border="0" cellpadding="0" cellspacing="0">' +
        '<tr><td class="black-normal" id="ass_box_' + this.id + '"></td></tr><tr><td align="center">' + 
        add_str + '</td></tr></table></td></tr></table></td><td class="bp_rt"></td></tr><tr>' +
        '<td class="bp_bl"></td><td class="bp_bt"></td><td class="bp_br"></td></tr></table>';
        $("#ass_box").append(html);
        for (var t=0; t<this.sub.length; t++) {
            this.add(t);
        }
    }
    
    this.set = function() {
        this.state = $("#ass_chk_" + this.id).is(':checked') ? 1 : 0;
    }
    
    this.set_sub = function(id) {
        this.sub_state[id] = $("#ass_chk_" + this.id + '_' + id).is(':checked') ? 1 : 0;
    }
    
    this.remove = function() {
        $("#" + this.cmp).remove();
        ass_typs[this.id] = null;
    }
    
    this.add = function(id) {
        var fresh = false;
        if (id == -1) {
            fresh = true;
            id = this.sub.length;
            this.sub.push("");
            this.sub_state.push(1);
        }
        
        var state_html = '&nbsp;';
        if (fresh || isNew) {
            state_html = '<a href="javascript: void(0)" onclick="ass_typs[' + this.id + 
            '].del(' + id + ')"><img src="/images/failed.png" width="16" height="16" /></a>';
        } else {
            var state_str = this.sub_state[id] == 1 ? ' checked="checked"' : "";
            var editable = isEdit ? ' onclick="ass_typs[' + this.id + '].set_sub(' + id + ')"' : ' disabled="disabled"';
            state_html = '<input type="checkbox" name="ass_chk_' + this.id + '_' + id + '" id="ass_chk_' + this.id + 
            '_' + id + '"' + state_str + editable +' />';
        }
        
        var content = isEdit ? 'Name:</td><td><input type="text" name="title_' + this.cmp + '_' + id + 
        '" id="title_' + this.cmp + '_' + id + '" value="' + this.sub[id] + 
        '" style="width:200px" onchange="ass_typs[' + this.id + 
        '].sub[' + id + ']=trimme(this.value)" />' : '<b>' + this.sub[id] + '</b>';
        html = '<table border="0" cellpadding="0" cellspacing="0" style="margin:1px" id="' + this.cmp + '_' + id + '">' +
        '<tr><td class="bo_tl"></td><td class="bo_tp"></td><td class="bo_tr"></td></tr>' +
        '<tr><td class="bo_lf"></td><td class="bo_center"><table border="0" cellspacing="2" cellpadding="0">' +
        '<tr><td>' + content + '</td><td>' + state_html + '</td></tr>' +
        '</table></td><td class="bo_rt"></td></tr>' + 
        '<tr><td class="bo_bl"></td><td class="bo_bt"></td><td class="bo_br"></td></tr></table>';
        $("#ass_box_" + this.id).append(html);
        if (isEdit)
            $("#title_" + this.cmp + "_" + id).focus()
    }
    
    this.del = function(id) {
        $("#" + this.cmp + "_" + id).remove();
        this.sub[id] = null;
        this.sub_state[id] = 0;
    }
}

function addItm(grp) {
    ass_itms.push(new ass_itm("", "", 0, 1, 0, 0, "", grp, true));
    ass_itms[ass_itms.length - 1].make();
}

function addGrp() {
    ass_grps.push(new ass_grp("", "", 1, 0, 0, true));
    ass_grps[ass_grps.length - 1].make();
}

function addTyp() {
    ass_typs.push(new ass_typ("", 1, "", "", true));
    ass_typs[ass_typs.length - 1].make();
}

function prepView(isCopy) {
    var name = $("#ass_names").val().split('|');
    var code = $("#ass_codes").val().split('|');
    var ca = $("#ass_ca").val().split('|');
    var state = $("#ass_state").val().split('|');
    var per = $("#percentages").val().split('|');
    var max = $("#max_scores").val().split('|');
    var atch = $("#attachments").val().split('~~##~~');
    var grp = $("#ass_grp").val().split('|');
    var inf = $("#cls_grp_inf").val().split('|');
    var grp_vals, atche;
    
    var cls_name = $("#cls_names").val().split('|');
    var cls_code = $("#cls_codes").val().split('|');
    var cls_ca = $("#cls_ca").val().split('|');
    var cls_state = $("#cls_state").val().split('|');
    var cls_per = $("#cls_percentages").val().split('|');
    var cls_max = $("#cls_max_scores").val().split('|');
    
    var cls_ass = $("#cls_ass").val().split('~#~');
    var cls_ass_state = $("#cls_ass_state").val().split('|');
    var cls_sub = $("#cls_sub").val().split('~~##~~');
    var cls_sub_state = $("#cls_sub_state").val().split('|');
    
    for (var k=1; k<inf.length; k++) {
        grp_vals = inf[k].split(":");
        ass_grps.push(new ass_grp(grp_vals[0], grp_vals[1], grp_vals[2], grp_vals[3], grp_vals[4], isCopy));
        ass_grps[ass_grps.length - 1].make();
    }
    
    var i = 0;
    if ($("#ass_names").val().length > 0)
        for (i=0; i<name.length; i++) {
            ass_itms.push(new ass_itm(name[i], code[i], ca[i], state[i], per[i], max[i], atch[i], grp[i], isCopy));
            ass_itms[i].make();
            ass_itms[i].load_atch();
            ass_itms[i].onChangeVal(ass_grps[grp[i]].arith == 0 ? 1 :0, 7);
        }
    
    if ($("#cls_names").val().length > 0)
        for (var j=0; j<cls_name.length; j++) {
            atche = atch.length > i+j ? atch[i+j] : '';
            ass_itms.push(new ass_itm(cls_name[j], cls_code[j], cls_ca[j], cls_state[j], cls_per[j], cls_max[j], atche, 0, isCopy));
            ass_itms[i+j].make();
            ass_itms[i+j].load_atch();
        }
    
    if (isClass && $("#cls_ass").val().length > 0)
        for (i=0; i<cls_ass.length; i++) {
            ass_typs.push(new ass_typ(cls_ass[i], cls_ass_state[i], cls_sub[i], cls_sub_state[i], isCopy));
            ass_typs[i].make(isCopy);
        }
}

function collateObj() {
    var nam_val = "", cod_val = "", ca_val = "", chk_val = "", per_val = "", max_val = "", 
    atc_val = "", cls_atc = "", atchment = "", grp_val = "", inf_val = "1", act,
    cls_ass = "", cls_ass_state = "", cls_sub = "", cls_sub_state = "", sub, state;
    tot_per = 0;
    var ord = 1;
    
    for (var j=1; j<ass_grps.length; j++) {
        if (ass_grps[j] != null){
            inf_val += "|" + ass_grps[j].name + ":" + ass_grps[j].code + ":" + 
            ass_grps[j].state + ":" + ass_grps[j].per + ":" + ass_grps[j].arith;
            ass_grps[j].tot_per = 0;
            tot_per += ass_grps[j].per;
            ass_grps[j].ord = ord;
            ord++;
        }
    }
    
    for (j=ass_grps.length-1; j>=0; j--) {
        for (i=0; i<ass_itms.length; i++) {
            if (ass_itms[i] != null && ass_itms[i].grp == j) {
                atchment = "";
                for (k=0; k<ass_itms[i].atch.length; k++) {
                    if (ass_itms[i].atch[k] != null)
                        atchment += ":" + k;
                }
                atchment = atchment.substr(1);
                
                act = !isClass && ass_itms[i].grp != 0 || isClass
                nam_val += !act ? '' : "|" + ass_itms[i].name;
                cod_val += !act ? '' : "|" + ass_itms[i].code;
                ca_val += !act ? '' : "|" + ass_itms[i].ca;
                chk_val += !act ? '' : "|" + ass_itms[i].state;
                per_val += !act ? '' : "|" + ass_itms[i].per;
                max_val += !act ? '' : "|" + ass_itms[i].max;
                atc_val += ass_itms[i].grp == 0 ? '' : "|" + i + '#' + atchment;
                cls_atc += ass_itms[i].grp != 0 ? '' : "|" + i + '#' + atchment;
                if (ass_itms[i].grp == 0) {
                    grp_val += "|" + 0;
                    tot_per += ass_itms[i].per;
                } else {
                    grp_val += "|" + ass_grps[ass_itms[i].grp].ord;
                    ass_grps[ass_itms[i].grp].tot_per += ass_itms[i].per;
                }
            }
        }
    }
    
    for (j=0; j<ass_typs.length; j++) {
        if (ass_typs[j] != null){
            if (ass_typs[j].name.length == 0) {
                popmsg(document.getElementById(ass_typs[j].cmp), "Please enter the Name");
                return false;
            }
            cls_ass += "~#~" + ass_typs[j].name;
            cls_ass_state += "|" + ass_typs[j].state;
            sub = "";
            state = "";
            
            for (i=0; i<ass_typs[j].sub.length; i++) {
                if (ass_typs[j].sub[i] != null) {
                    if (ass_typs[j].sub[i].length == 0) {
                        popmsg(document.getElementById("title_" + ass_typs[j].cmp + "_" + i), "Please enter the Name");
                        return false;
                    }
                    sub += "~#~" + ass_typs[j].sub[i];
                    state += "#" + ass_typs[j].sub_state[i];
                }
            }
            cls_sub += "~~##~~" + sub.substr(3);
            cls_sub_state += "|" + state.substr(1);
        }
    }
    
    $("#ass_names").val(nam_val.substr(1));
    $("#ass_codes").val(cod_val.substr(1));
    $("#ass_ca").val(ca_val.substr(1));
    $("#ass_state").val(chk_val.substr(1));
    $("#ass_grp").val(grp_val.substr(1));
    $("#cls_grp_inf").val(inf_val);
    $("#percentages").val(per_val.substr(1));
    $("#max_scores").val(max_val.substr(1));
    $("#attachments").val((atc_val + cls_atc).substr(1));
    
    $("#cls_ass").val(cls_ass.substr(3));
    $("#cls_ass_state").val(cls_ass_state.substr(1));
    $("#cls_sub").val(cls_sub.substr(6));
    $("#cls_sub_state").val(cls_sub_state.substr(1));
    return true;
}

function vetAss() {
    if (!collateObj())
        return false;
    for (ca=0; ca < ass_itms.length; ca++) {
        if (ass_itms[ca].ca == 0 && ass_itms[ca].max <= 0) {
            popmsg(document.getElementById(ass_itms[ca].cmp), "Maximum score must be greater 0");
            return false;
        }
        var grp = ass_grps[ass_itms[ca].grp];
        if (ass_itms[ca].ca == 0 && ass_itms[ca].grp > 0 && grp.arith > 0 && ass_itms[ca].max != 100) {
            popmsg(document.getElementById(ass_itms[ca].cmp), "Maximum score must be equal to 100");
            return false;
        }
    }
    
    if (!isClass)
        for (var j=0; j<ass_grps.length; j++) {
            if (ass_grps[j] != null && ass_grps[j].arith == 0 && ass_grps[j].tot_per != 100) {
                popmsg(document.getElementById(ass_grps[j].cmp), 'The summation of all "% of Total" for '+ass_grps[j].name+' must be equal to 100');
                return false;
            }
        }
    
    if (parseInt($("#cls_typ").val()) == 0 && tot_per != 100) {
        alert('The summation of all "% of Total" must be equal to 100');
        return false;
    }
    return true;
}
