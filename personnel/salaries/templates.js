// JavaScript Document
var sal_parts = [null];
var sal_box = ["tax_bx", "sal_bx", "bonus_bx"];
var sal_scr = ["tax_script", "sal_script", "bon_script"];
var cum_str = ["&nbsp;&nbsp;&nbsp;", "(+)", "(-)"];
var oper_str = ["+", "-", "&times;", "&divide;"];
var comp_str = ["==", "!=", "<", ">", "<=", ">="];
var func_str = ["&nbsp;&nbsp;&nbsp;", "%", "Grad.%", "Deduct", "sum", "avg", "max", "min"];
var work_str = ["WORKED", "TOTAL PERIOD"];
var isEdit, nam_lst = [[0, 'VALUE']];
var OPR = '0', VAL = '1', PRD = '2', BRC = '3', IFF = '4';

function sal_part(name, cmls, ftyp, oprs, fncs, flds, wins, state, typ) {
    var cnt = sal_parts.length;
    this.id = cnt;
    this.typ = typ;
    this.cmp = "sal_"+cnt;
    this.name = name;
    this.cmls = parseInt(cmls);
    this.ftyp = ftyp == '' ? [] : ftyp.split('#');
    this.oprs = oprs == '' ? [] : oprs.split('#');
    this.fncs = fncs == '' ? [] : fncs.split('#');
    this.flds = flds == '' ? [] : flds.split('#');
    this.wins = wins == '' ? [] : wins.split('#');
    this.state = parseInt(state);
    this.itms = new Array();
    if (this.ftyp.length < this.flds.length || this.ftyp.length == this.flds.length && ftyp == '') {
        this.ftyp = genftyps(this.flds);
        for (var i=0; i<this.flds.length; i++)
            if (this.ftyp[i] == VAL || this.ftyp[i] == PRD)
                this.flds[i] = this.flds[i].split('~')[1];
    }
    
    this.make = function() {
        var state_html = '&nbsp;';
        if (isEdit) {
            state_html = '<table border="0" cellspacing="1" cellpadding="1"> \n\
<tr><td><img src="/images/upnav.png" width="16" height="16" \n\
onclick="sal_parts['+this.id+'].move(-1)" title="Move up" style="cursor: pointer" /></td><td> \n\
<img src="/images/downnav.png" width="16" height="16" \n\
onclick="sal_parts['+this.id+'].move(1)" title="Move down" style="cursor: pointer" /></td><td> \n\
<img src="/images/failed.png" width="16" height="16" \n\
onclick="sal_parts['+this.id+'].del()" title="Delete" style="cursor: pointer" /></td></tr></table>';
        }
        var add_str = isEdit ? '<td bgcolor="#666666"><img src="/images/add.png" width="16" height="16" \n\
onclick="sal_parts['+this.id+'].add(\'0\', \'0\', \'0\', \'0\', -1)" title="Add Operand" style="cursor: pointer" />\n\
<br /><img src="/images/textfield_add.png" width="16" height="16" \n\
onclick="sal_parts['+this.id+'].add(\'1\', \'0\', \'0\', \'0\', -1)" title="Add Value" style="cursor: pointer" />\n\
<br /><img src="/images/date.png" width="16" height="16" \n\
onclick="sal_parts['+this.id+'].add(\'2\', \'0\', \'0\', \'0\', -1)" title="Add Work Period" style="cursor: pointer" />\n\
<br /><img src="/images/tag.png" width="16" height="16" \n\
onclick="sal_parts['+this.id+'].add(\'3\', \'0\', \'0\', \'0\', -1)" title="Add Bracket" style="cursor: pointer" />\n\
<br /><img src="/images/help.png" width="16" height="16" \n\
onclick="sal_parts['+this.id+'].add(\'4\', \'0\', \'0\', \'0~0~0~0~0~0~0~0~0\', -1)" title="Add Conditional Statement" style="cursor: pointer" /></td>' : '';

        var txt_name = isEdit ? '<input name="name_'+this.id+'" type="text" id="name_'+this.id+
        '" value="'+this.name+'" style="width:160px" onchange="sal_parts['+this.id+
        '].onChangeVal(this.value, 1)" />' : '<b class="blue-normal">'+this.name+'</b>';
        
        var html = '<table border="0" cellpadding="0" cellspacing="0" style="margin:1px; float:left" id="'+this.cmp +
        '"><tr><td class="bp_tl"></td><td class="bp_tp"></td><td class="bp_tr"></td></tr><tr>\n\
<td class="bp_lf"></td><td class="bp_center"><table border="0" cellspacing="0" cellpadding="2"><tr>\n\
<td class="titles" width="20">Name:</td><td width="100%" align="left">'+txt_name+'</td><td>'+state_html+
        '</td></tr><tr><td colspan="3"><table border="0" cellspacing="0" cellpadding="2"><tr><td id="cum_'+this.id+
        '"></td><td id="calc_'+this.id+'"></td>'+add_str+'</tr></table></td></tr>\n\
<tr><td class="boldwhite1" bgcolor="#0" colspan="3" id="console_'+this.id+'">...</td><td></td><td></td></tr>\n\
</table></td><td class="bp_rt"></td>\n\
</tr><tr><td class="bp_bl"></td><td class="bp_bt"></td><td class="bp_br"></td></tr></table>';
        if (isEdit || this.state == 1) {
            $("#" + sal_box[this.typ]).append(html);
            this.showCuml(false);
            this.children(this.itms.length==0);
            $("#name_"+this.id).focus();
        }
        this.print();
    };
    
    this.children = function(make) {
        var c = make ? this.flds.length : this.itms.length;
        for(var p=0; p<c; p++) {
            if (make) {
                this.itms.push(new itm(this.ftyp[p], this.oprs[p], this.fncs[p], this.flds[p], this.id, this.wins[p]));
                this.itms[p].make();
            } else if (this.itms[p] != null) {
                this.itms[p].par = this.id;
                this.itms[p].vars();
                this.itms[p].make();
            }
        }
    };
    
    this.showCuml = function(edit) {
        var txt_cmls = edit ? '<select name="cum_'+this.id+'" id="cum_'+this.id+'" onchange="sal_parts['+
        this.id+'].onChangeVal(this.value, 2)" onblur="sal_parts['+this.id+'].showCuml(false)">'+
        genOptions(cum_str, 0, [this.cmls])+'</select>' : 
        '<b class="red-normal" style="cursor: pointer" onclick="sal_parts['+this.id+'].showCuml(true)">'+
        cum_str[this.cmls]+'</b>';
        $("#cum_"+this.id).html(box(this.cmp+'_fld', 'bl', txt_cmls));
    };
    
    this.set = function() {
        if($("#chk_"+this.id).is(':checked'))
            this.state = 1;
        else
            this.state = 0;
    };
    
    this.genFlds = function() {
        nam_lst = [[0, 'VALUE']];
        for (var s=1; s<sal_parts.length; s++)
            if (sal_parts[s] != null && sal_parts[s].name.length > 0 && sal_parts[s].state == 1)
                nam_lst.push([s, sal_parts[s].name]);
        for (var s=1; s<sal_parts.length; s++) {
            if (sal_parts[s] != null)
                for (var t=0; t<sal_parts[s].itms.length; t++)
                    if (sal_parts[s].itms[t] != null)
                        sal_parts[s].itms[t].set(1, 3, false);
            sal_parts[s].print();
        }
    };

    this.add = function(typ, oprs, fncs, flds, par) {
        this.itms.push(new itm(typ, oprs, fncs, flds, this.id, par));
        this.itms[this.itms.length - 1].make();
        this.print();
    };
    
    this.move = function(dir) {
        var id = this.id, mv = this.id + dir;
        if (mv > 0 && mv < sal_parts.length && sal_parts[mv] != null) {
            sal_parts[0] = sal_parts[id];
            sal_parts[id] = sal_parts[mv];
            sal_parts[mv] = sal_parts[0];
            sal_parts[0] = null;
            sal_parts[id].id = id;
            sal_parts[mv].id = mv;
            for (var i=0; i<3; i++) {
                $("#" + sal_box[i]).empty();
            }
            for (var i=0; i<sal_parts.length; i++)
                if (sal_parts[i] != null)
                    sal_parts[i].make();
            $("#name_"+this.id).focus();
        }
    };

    this.del = function() {
        for (var t=0; t<this.itms.length; t++) {
            $("#"+this.itms[t].cmp).remove();
            this.itms[t] = null;
        }
        $("#"+this.cmp).remove();
        sal_parts[this.id] = null;
        this.print();
    };

    this.print = function() {
        var str = "", idx = 0, prv = -1;
        for (var t=0; t<this.itms.length; t++) {
            if (this.itms[t] != null && this.itms[t].win == -1) {
                str += " " + this.itms[t].print(idx++, prv);
                prv = t;
            }
        }
        var script = cum_str[this.cmls]+str, ids = "#"+sal_scr[this.typ];
        $("#console_"+this.id).html(script);
        if ($(ids).length > 0 && this.cmls > 0) {
            var init = $(ids).html();
            $(ids).html(init + (init.length > 1 ? '<br />' : '')+script);
        }
    };

    this.onChangeVal = function(val, idx) {
        switch (idx) {
            case 1:
                this.name = trimme(val).replace(/[\|~#]/g,"%");
                $('#name_'+this.id).val(this.name);
                this.genFlds();
                break;
            case 2:
                this.cmls = val;
                break;
        }
        this.print();
    };
}

function itm(typ, opr, fnc, flds, par, wins) {
    this.id = sal_parts[par].itms.length;
    this.par = par;
    this.win = wins;
    this.typ = typ;
    this.opr = parseInt(opr);
    this.fnc = parseInt(fnc);
    this.flds = TabExplode(flds, '~', ':');
    this.cmp = '';
    this.pstr = '';
    this.opr_str = '';
    this.fnc_str = '';
    this.fld_str = '';
    this.grads = 0;
    this.mode = 1;
    this.cmp_ = 0;
    
    this.make = function() {
        this.vars();
        var state = !isEdit ? '' : '<td><table border="0" cellspacing="1" cellpadding="1"> \n\
<tr><td><img src="/images/upnav.png" width="16" height="16" \n\
onclick="'+this.pstr+'.move(-1)" title="Move up" style="cursor: pointer" /></td><td> \n\
<img src="/images/downnav.png" width="16" height="16" \n\
onclick="'+this.pstr+'.move(1)" title="Move down" style="cursor: pointer" /></td><td> \n\
<img src="/images/delete.png" width="16" height="16" \n\
onclick="'+this.pstr+'.del()" title="Delete Operand" style="cursor: pointer" /></td></tr></table></td>';
        
        var html = '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px" id="'+
        this.cmp+'"><tr><td id="oprbox_'+this.cmp+'"></td><td id="fncbox_'+this.cmp+
        '"></td><td id="fldbox_'+this.cmp+'"></td>'+state+'</tr></table>';
        $("#calc_"+this.par + (this.win > -1 ? '_'+this.win : '')).append(html);
        this.set(this.mode, this.cmp_, true);
    };
    
    this.vars = function() {
        var cmp = this.cmp;
        this.cmp = 'itm_'+this.par+'_'+this.id;
        this.pstr = 'sal_parts['+this.par+'].itms['+this.id+']';
        var re_c = new RegExp(cmp);        
        var re_p = new RegExp('sal_parts\\[\\d+\\].itms\\[\\d+\\]');
        this.opr_str = this.opr_str.replace(re_c, this.cmp).replace(re_p, this.pstr);
        this.fnc_str = this.fnc_str.replace(re_c, this.cmp).replace(re_p, this.pstr);
        this.fld_str = this.fld_str.replace(re_c, this.cmp).replace(re_p, this.pstr);
    };
    
    this.prep = function() {
        if (this.cmp_ == 0 || this.cmp_ == 1)
            this.opr_str = this.mode == 0 ? '<select name="opr_'+this.cmp+'" id="opr_'+this.cmp+'" \n\
onchange="'+this.pstr+'.onChangeVal(this.value, 1)" \n\
onblur="'+this.pstr+'.set(1, 1)" class="titles">'+
            genOptions(oper_str, 0, [this.opr])+'</select>' : 
            '<b class="red-normal" id="opr_'+this.cmp+'" style="cursor: pointer" \n\
onclick="'+this.pstr+'.set(0, 1)">'+oper_str[this.opr]+'</b>';
        
        if (this.cmp_ == 0 || this.cmp_ == 2)
            this.fnc_str = this.mode == 0 ? '<select name="fnc_'+this.cmp+'" id="fnc_'+this.cmp+'" \n\
onchange="'+this.pstr+'.onChangeVal(this.value, 2)" \n\
onblur="'+this.pstr+'.set(1, 2); '+this.pstr+'.set(1, 3);" class="titles" >'+
            genOptions(this.typ == OPR ? func_str : func_str.slice(0, 2), 0, [this.fnc])+'</select>' : 
            '<b class="red-normal" id="fnc_'+this.cmp+'" style="cursor: pointer" \n\
onclick="'+this.pstr+'.set(0, 2)">'+func_str[this.fnc]+'</b>';
        
        if (this.cmp_ == 0 || this.cmp_ == 3)
            switch (this.typ) {
                case OPR:
                    if (this.fnc == 2)
                        this.fld_str = this.makeGrad(this.mode);
                    else
                        this.fld_str =this.makeCmp(this.mode);
                    break;
                case VAL:
                    this.fld_str = this.makeVal(this.mode);
                    break;
                case PRD:
                    this.fld_str =this.makePer();
                    break;
                case BRC:
                    this.fld_str = this.makeBrace();
                    break;
                case IFF:
                    this.fld_str = this.makeIF();
                    break;
            }
    };
    
    this.set = function(mode, cmp, force) {
        if (!isEdit && !force)
            return;
        this.mode = mode;
        this.cmp_ = cmp;
        this.prep();
        $("#oprbox_"+this.cmp).html(box(this.cmp+'_op', 'bx', this.opr_str));
        $("#fncbox_"+this.cmp).html(box(this.cmp+'_fn', 'bl', this.fnc_str));
        if (this.typ != BRC || force)
            $("#fldbox_"+this.cmp).html(box(this.cmp+'_fd', 'bp', this.fld_str));
        if (mode == 0 && isEdit && this.typ != BRC)
            switch (cmp) {
                case 1:
                    document.getElementById('opr_' + this.cmp).focus();
                    break;
                case 2:
                    document.getElementById('fnc_' + this.cmp).focus();
                    break;
                case 3:
                    if (this.fnc != 2)
                        document.getElementById((this.typ == 4 ? 'opd_1_' : 'fld_') + this.cmp).focus();
                    break;
            }
    };

    this.makeBrace = function() {
        var add_str = !isEdit ? '' : '\n\
<td bgcolor="#666666"><img src="/images/add.png" \n\
onclick="sal_parts['+this.par+'].add(\'0\', \'0\', \'0\', \'0\', '+this.id+')" \n\
title="Add Operand" style="cursor: pointer" width="16" height="16" />\n\
<br /><img src="/images/textfield_add.png" \n\
onclick="sal_parts['+this.par+'].add(\'1\', \'0\', \'0\', \'0\', '+this.id+')"\n\
title="Add Value" style="cursor: pointer; margin: 1px" width="16" height="16" />\n\
<br /><img src="/images/date.png" width="16" height="16" \n\
onclick="sal_parts['+this.par+'].add(\'2\', \'0\', \'0\', \'0\', '+this.id+')" \n\
title="Add Work Period" style="cursor: pointer" />\n\
<br /><img src="/images/help.png" width="16" height="16" \n\
onclick="sal_parts['+this.par+'].add(\'4\', \'0\', \'0\', \'0~0~0~0~0~0~0~0~0\', '+this.id+')" \n\
title="Add Conditional Statement" style="cursor: pointer" /></td>';
        return '<table border="0" cellspacing="0" cellpadding="0"><tr><td>\n\
<img src="/images/tag_lft.png" width="16" height="32" style="height:100%" /></td>\n\
<td id="calc_'+this.par+'_'+this.id+'"></td><td>\n\
<img src="/images/tag_rt.png" width="16" height="32" style="height:100%" /></td>'+add_str+'</tr></table>';
    };
    
    this.makeVal = function(mode) {
        return mode == 0 ? '<input name="fld_'+this.cmp+'" type="text" id="fld_'+this.cmp+'\
" value="'+this.flds[0]+'" style="width:60px" \n\
onblur="numme(this, 0); '+this.pstr+'.onChangeVal(this.value, 3); \n\
'+this.pstr+'.set(1, 3)" />' : 
        '<b class="blue-normal" id="fld_'+this.cmp+'" style="cursor: pointer" \n\
onclick="'+this.pstr+'.set(0, 3)">'+setthous(this.flds[0])+'</b>';
    };

    this.makeCmp = function(mode) {
        var shape = mode == 0 && this.fnc > 3 ? 'size="5" multiple="multiple"' : '';
        return mode == 0 ? '<select name="fld_'+this.cmp+'" id="fld_'+this.cmp+'" \n\
onchange="'+this.pstr+'.onChangeVal(this.value, 4)" \n\
onblur="'+this.pstr+'.set(1, 3)"'+shape+'>'+
        genOptions(nam_lst, 1, this.flds, this.par)+'</select>' : 
        '<b class="blue-normal" id="fld_'+this.cmp+'" style="cursor: pointer" \n\
onclick="'+this.pstr+'.set(0, 3)">'+this.getFlds(this.flds)+'</b>';
    };
    
    this.makePer = function() {
        return this.mode == 0 ? '<select name="fld_'+this.cmp+'" id="fld_'+this.cmp+'" \n\
onchange="'+this.pstr+'.onChangeVal(this.value, 3)" \n\
onblur="'+this.pstr+'.set(1, 3)">'+
        genOptions(work_str, 0, this.flds)+'</select>' : 
        '<b class="blue-normal" id="fld_'+this.cmp+'" style="cursor: pointer" \n\
onclick="'+this.pstr+'.set(0, 3)">'+work_str[this.flds[0]]+'</b>';
    };
    
    this.makeIF = function() {
        var opd1 = this.getOpd(this.mode, 5), opd2 = this.getOpd(this.mode, 6), 
        opr = this.mode == 0 ? '<select name="ifopr_'+this.cmp+'" id="ifopr_'+this.cmp+'" \n\
onchange="'+this.pstr+'.onChangeVal(this.value, 3)" \n\
onblur="'+this.pstr+'.set(1, 3)">'+
        genOptions(comp_str, 0, [this.flds[0]])+'</select>' : 
        '<b class="red-normal" id="ifopr_'+this.cmp+'" style="cursor: pointer" \n\
onclick="'+this.pstr+'.set(0, 3)">'+comp_str[this.flds[0]]+'</b>',
        opt1 = this.getOpd(this.mode, 7), opt2 = this.getOpd(this.mode, 8);
        var cvar = '<td><input name="op&&typ_##_'+this.cmp+'" type="hidden" id="op&&typ_##_'+this.cmp+'" \n\
value="@" /><img src="/images/~.png" width="16" height="16" id="op&&img_##_'+this.cmp+'" \n\
onclick="'+this.pstr+'.chOpdimg(\'&&\',##,@@)" title="Operand Type" style="cursor: pointer" /></td>';
        
        return '<table border="0" cellpadding="1" cellspacing="1" class="red-normal">\n\
<tr><td><b>IF(</b></td>'+this.getOpdimg(cvar, 5)+'\n\
<td class="black-normal" id="opd_1_'+this.cmp+'">'+opd1+'</td>\n\
<td id="ifopr_'+this.cmp+'">'+opr+'</td>'+this.getOpdimg(cvar, 6)+'\n\
<td class="black-normal" id="opd_2_'+this.cmp+'">'+opd2+'</td><td><b>)</b></td>'+this.getOpdimg(cvar, 7)+'\n\
<td class="black-normal" id="opt_1_'+this.cmp+'">'+opt1+'</td><td><b>else</b></td>'+this.getOpdimg(cvar, 8)+'\n\
<td class="black-normal" id="opt_2_'+this.cmp+'">'+opt2+'</td></tr></table>';
    };

    this.chOpdimg = function(typ, id, r) {
        var hd = $('#op'+typ+'typ_'+id+'_'+this.cmp), img = $('#op'+typ+'img_'+id+'_'+this.cmp);
        var t = hd.val() == 1 ? 0 : 1;
        hd.val(t);
        img.attr('src', '/images/'+(t == 0 ? 'add':'textfield_add')+'.png');
        this.onChangeVal(t, 6, r);
    };

    this.getOpdimg = function(cvar, r) {
        return cvar.replace(/##/g, r == 5 || r == 7 ? '1' : '2')
        .replace(/&&/g, r > 6 ? 't' : 'd').replace(/~/g, this.flds[r-4]==0 ? 'add':'textfield_add')
        .replace(/@@/g, r-4).replace(/@/g, this.flds[r]);
    };

    this.getOpd = function(mode, r) {
        var str= "", typ = this.flds[r - 4], nm = 'op'+(r > 6 ? 't' : 'd') + '_' + (r == 5 || r == 7 ? '1' : '2') + '_';
        if (mode == 0) {
            str = typ == 0 ? '<select name="'+nm+this.cmp+'" id="'+nm+this.cmp+'" \n\
onchange="'+this.pstr+'.onChangeVal(this.value,6,'+r+')" \n\
onblur="'+this.pstr+'.set(1, 3)">'+
        genOptions(nam_lst, 1, this.flds[r], this.par)+'</select>' :
                '<input name="'+nm+this.cmp+'" type="text" id="'+nm+this.cmp+'\
" value="'+this.flds[r]+'" style="width:60px" \n\
onblur="numme(this, 0); '+this.pstr+'.onChangeVal(this.value,6,'+r+'); \n\
'+this.pstr+'.set(1, 3)" />';
        } else {
            str = '<b class="blue-normal" id="'+nm+this.cmp+'" style="cursor: pointer" \n\
onclick="'+this.pstr+'.set(0, 3)">'+(typ == 0 ? nam_lst[this.flds[r]][1] : setthous(this.flds[r]))+'</b>';
        }
        return str;
    };
    
    this.makeGrad = function() {
        var cmp = this.cmp+'_';
        var add = !isEdit ? '' : '<td><img src="/images/add.png" width="16" height="16" \n\
onclick="'+this.pstr+'.addGrad([\'0\',\'0\'])" style="cursor:pointer" /></td>';
        
        var rows = '';
        for (var i=0; i<this.flds.length-1; i++)
            rows += this.getGrad(this.flds[i]);
        var dval = this.flds.length > 0 ? this.flds[this.flds.length-1] : '0';
        var def = '<input name="'+cmp+'_" type="text" style="width:20px; display:none" value="'+dval+'" id="'+cmp+'_" \n\
onblur="numme(this, 0); '+this.pstr+'.setCol(1,\'\',\'\'); '+this.pstr+'.onChangeVal(0, 5)" maxlength="2" />\n\
<span id="'+cmp+'__" onclick="'+this.pstr+'.setCol(0,\'\',\'\')">'+dval+'</span>' + (isEdit ? '<td></td>' : '');
        return '<table border="1" cellspacing="2" cellpadding="2" id="'+cmp+'"><tr class="boldwhite1">\n\
<td align="center" bgcolor="#333333">Value</td><td align="center" bgcolor="#333333">%</td>'+add+'</tr>\n\
'+rows+'<tr id="'+cmp+'def"><td class="titles" bgcolor="#ccc">Default</td><td>'+def+
        '</td></tr></table>';
    };

    this.getGrad = function(val) {
        var id = this.grads++;
        var cmp0 = this.cmp+'_0_'+id;
        var cmp1 = this.cmp+'_1_'+id;
        var del = !isEdit ? '' : '<td><img src="/images/delete.png" width="16" height="16" \n\
onclick="'+this.pstr+'.delGrad('+id+')" style="cursor: pointer" title="Delete Graduated Percentage Value" /></td>';
        var grd = '<input name="'+cmp0+'" type="text" style="width:80px; display:none" value="'+val[0]+'" \n\
id="'+cmp0+'" onblur="numme(this, 0); '+this.pstr+'.setCol(1,'+id+',0); '+this.pstr+'.onChangeVal(0, 5)" />\n\
<span id="'+cmp0+'_" onclick="'+this.pstr+'.setCol(0,'+id+',0)">'+setthous(val[0])+'</span>';
        var per = '<input name="'+cmp1+'" type="text" style="width:20px; display:none" value="'+val[1]+'" \n\
id="'+cmp1+'" onblur="numme(this, 0); '+this.pstr+'.setCol(1,'+id+',1); '+this.pstr+'.onChangeVal(0, 5)" maxlength="2" />\n\
<span id="'+cmp1+'_" onclick="'+this.pstr+'.setCol(0,'+id+',1)">'+val[1]+'</span>';
        return '<tr id="'+this.cmp+'_row'+id+'"><td>'+grd+'</td><td>'+per+'</td>'+del+'</tr>';
    };

    this.printGrad = function() {
        var str = '';
        for (var i=0; i<this.flds.length-1; i++)
            str += ', ' + setthous(this.flds[i][0]) + ':' + this.flds[i][1] + '%';
        str += ', Default:' + (this.flds.length > 0 ? this.flds[this.flds.length-1] : '0') + '%';
        return str.substr(2);
    };
    
    this.addGrad = function(val) {
        $("#"+this.cmp+"_def").before(this.getGrad(val));
        this.onChangeVal(0, 5);
    };

    this.setCol = function(mode, id, x) {
        if (!isEdit)
            return;
        var cmp = this.cmp+'_'+x+"_"+id;
        if (mode == 0) {
            $("#"+cmp+"_").hide();
            $("#"+cmp).show();
        } else {
            $("#"+cmp+"_").html(setthous($("#"+cmp).val()));
            $("#"+cmp+"_").show();
            $("#"+cmp).hide();
        }
    };

    this.delGrad = function(id) {
        $("#"+this.cmp+'_row'+id).remove();
        this.onChangeVal(0, 5);
    };

    this.del = function() {
        if (confirm('Are you sure you want to delete this item?'))  {
            if (this.typ == BRC) {
                for (var t=0; t<sal_parts[this.par].itms.length; t++) {
                    if (sal_parts[this.par].itms[t] != null && 
                        sal_parts[this.par].itms[t].win == this.id) {
                        $("#"+sal_parts[this.par].itms[t].cmp).remove();
                        sal_parts[this.par].itms[t] = null;
                    }
                }
            }
            $("#"+this.cmp).remove();
            sal_parts[this.par].itms[this.id] = null;
            sal_parts[this.par].print();
        }
    };
    
    this.move = function(dir) {
        var id = this.id, mv = this.id + dir, c = sal_parts[this.par].itms.length;
        if (this.win > -1) {
            mv = dir == -1 ? -1 : c;
            for (var t=0; t<sal_parts[this.par].itms.length; t++) {
                if (sal_parts[this.par].itms[t] != null && 
                    sal_parts[this.par].itms[t].win == this.win) {
                    if (dir == -1 && sal_parts[this.par].itms[t].id < id) {
                        mv = Math.max(mv, sal_parts[this.par].itms[t].id);
                    } else if (dir == 1 && sal_parts[this.par].itms[t].id > id) {
                        mv = Math.min(mv, sal_parts[this.par].itms[t].id);
                    }
                }
            }
        }
        if (mv > -1 && mv < c && sal_parts[this.par].itms[mv] != null) {
            var hold = sal_parts[this.par].itms[id];
            sal_parts[this.par].itms[id] = sal_parts[this.par].itms[mv];
            sal_parts[this.par].itms[mv] = hold;
            sal_parts[this.par].itms[id].id = id;
            sal_parts[this.par].itms[mv].id = mv;
            $("#calc_"+this.par).empty();
            sal_parts[this.par].children(false);
            $("#name_"+this.id).focus();
            sal_parts[this.par].print();
        }
    };
    
    this.getFlds = function(flds, plain) {
        var lst = '', name;
        for (var l=0; l < flds.length; l++) {
            name = '';
            for (var m=0; m < nam_lst.length; m++)
                if (nam_lst[m][0] == flds[l]) {
                    name = nam_lst[m][1];
                    break;
                }
            if (name.length > 0)
                lst += plain ? ', '+name : '<span class="red-normal">, </span>'+name;
        }
        return plain ? lst.substr(2) : 
        '<span class="red-normal">[</span>'+lst.substr(34)+'<span class="red-normal">]</span>';
    };

    this.print = function(idx, prv) {
        var isPer = prv > -1 && (sal_parts[this.par].itms[prv].fnc == 1 || sal_parts[this.par].itms[prv].fnc == 2);
        var opr = this.opr != 1 && (idx == 0 || isPer) ? '' : oper_str[this.opr] + (isPer ? '' : ' ');
        var fnc = func_str[this.fnc];
        var val = "";
        switch (this.typ) {
            case OPR://Operand
                val = this.fnc == 2 ? this.printGrad() : this.getFlds(this.flds, true);
                break;
            case VAL://value
                val = setthous(this.flds[0]);
                break;
            case PRD://period
                val = work_str[this.flds[0]];
                break;
            case BRC:
                var _idx = 0, _prv = -1;
                for (var t=0; t<sal_parts[this.par].itms.length; t++)
                    if (sal_parts[this.par].itms[t] != null && sal_parts[this.par].itms[t].win == this.id) {
                        val += " " + sal_parts[this.par].itms[t].print(_idx++, _prv);
                        _prv = t;
                    }
                val = '( ' + val.substr(val.substring(2,3) == ' ' ? 3 : 1) + ' )';
                break;
            case IFF:
                val = "IF("
                    +(this.flds[1] == 0 ? nam_lst[this.flds[5]][1] : setthous(this.flds[5]))
                    +comp_str[this.flds[0]]
                    +(this.flds[2] == 0 ? nam_lst[this.flds[6]][1] : setthous(this.flds[6]))
                    +") {"
                    +(this.flds[3] == 0 ? nam_lst[this.flds[7]][1] : setthous(this.flds[7]))
                    +" else "
                    +(this.flds[4] == 0 ? nam_lst[this.flds[8]][1] : setthous(this.flds[8]))
                    +"}";
                break;
        }
        
        switch (this.fnc) {
            case 0:
                return opr+val;
            case 1:
                return opr+val+fnc+' of';
            case 2:
                return opr+'Grad(' + val + ')% of';
            default:
                return opr+fnc+'(' + val + ')';
        }
    };

    this.onChangeVal = function(val, idx, r) {
        switch (idx) {
            case 1://Operator
                this.opr = parseInt(val);
                break;
            case 2://Function
                this.fnc = parseInt(val);
                break;
            case 3://text Field
                this.flds[0] = val;
                break;
            case 4://Operand
                if (this.fnc > 3)
                    this.flds = $('#fld_'+this.cmp).val();
                else
                    this.flds = [val];
                break;
            case 5://Grad%
                var vals = $('input[name^="'+this.cmp+'_0_"]');
                var pers = $('input[name^="'+this.cmp+'_1_"]');
                this.flds = [];
                for (var i=0; i<vals.length; i++)
                    this.flds.push([$('#'+vals[i].id).val(), $('#'+pers[i].id).val()]);
                this.flds.push($('#'+this.cmp+'__').val());
                break;
            case 6://IFF
                this.flds[r] = val;
                break;
        }
        sal_parts[this.par].print();
    };
}

function box(id, clr, content) {
    return '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px" id="'+id +'"><tr>\n\
<td class="'+clr+'_tl"></td>\n\
<td class="'+clr+'_tp"></td>\n\
<td class="'+clr+'_tr"></td></tr><tr>\n\
<td class="'+clr+'_lf"></td>\n\
<td class="'+clr+'_center">'+content+'</td>\n\
<td class="'+clr+'_rt"></td></tr><tr>\n\
<td class="'+clr+'_bl"></td>\n\
<td class="'+clr+'_bt"></td>\n\
<td class="'+clr+'_br"></td></tr></table>';
}

function addPart(typ) {
    var cnt = sal_parts.length;
    sal_parts.push(new sal_part("", "0", "", "", "", "", "", 1, typ));
    sal_parts[cnt].make();
    for (var t=0; t<sal_parts[cnt].itms.length; t++)
        sal_parts[cnt].itms[t].set(1, 0, true);
    sal_parts[cnt].print();
}

function genOptions(text, val, flds, excl) {
    var options = '', value, txt, sel, v;
    for (var i=0; i<text.length; i++) {
        value = val == 0 ? i : text[i][0];
        if (excl && excl == value)
            continue;
        txt = val == 0 ? text[i] : text[i][1];
        sel = '';
        for (v=0; v<flds.length; v++)
            if (value == flds[v]) {
                sel =  ' selected="selected"';
                break;
            }
        options += '<option value="'+value+'"'+sel+'>'+txt+'</option>';
    }
    return options;
}

function genftyps(flds) {
    var ftyp = [], fld;
    for (var i=0; i<flds.length; i++) {
        fld = flds[i].split('~');
        if (fld.length == 2 && fld[0] == '0')
            ftyp[i] = BRC;
        else if (fld.length == 3)
            ftyp[i] = PRD;
        else if (fld.length == 2 && fld[0] == '')
            ftyp[i] = VAL;
        else 
            ftyp[i] = OPR;
    }
    return ftyp;
}

function prepView() {
    var names = $("#parts").val().split('|');
    var typs = $("#typs").val().split('|');
    var cmls = $("#cmls").val().split('|');
    var ftyp = $("#ftyp").val().split('|');
    var oprs = $("#oprs").val().split('|');
    var fncs = $("#fncs").val().split('|');
    var flds = $("#flds").val().split('|');
    var wins = $("#wins").val().split('|');
    var state = $("#state").val().split('|');
    if (ftyp.length < flds.length) {
        for (var i=0; i<flds.length; i++)
        ftyp[i] = '';
    }
    
    nam_lst = [[0, 'VALUE']];
    for (var s=1; s<names.length; s++)
        nam_lst.push([s, names[s]]);
    
    if ($("#parts").val().length > 0)
        for (var i=1; i<names.length; i++) {
            sal_parts.push(new sal_part(names[i], cmls[i], ftyp[i], oprs[i], fncs[i], flds[i], wins[i], state[i], typs[i]));
            sal_parts[i].make();
        }
}

function collateObj() {
    var nam_val = "", typ_val = "", cml_val = "", ftp_val = "", opr_val = "", fnc_val = "", 
    fld_val = "", win_val = "", sta_val = "";
    var idx = [0], flds, wins, ftyp, oprs, fncs, fdx, sal, itm;
    
    for (var i=1; i<sal_parts.length; i++)
        if (sal_parts[i] != null)
            idx.push(i);
            
    for (i=1; i<sal_parts.length; i++) {
        sal = sal_parts[i];
        if (sal != null) {
            if (sal.name.length == 0) {
                popmsg(document.getElementById('name_'+sal.id), "Please enter the name for this Salary Part!");
                return false;
            } else {
                switch (sal.name) {
                    case "VALUE" :
                    case "WORKED" :
                    case "TOTAL PERIOD" :
                        popmsg(document.getElementById('name_'+sal.id), "The name '" +sal.name+ "' is reserved for system use. Please enter a different name for this Salary Part!");
                        return false;
                }
            }
            wins = flds = ftyp = oprs = fncs = "";
            fdx = [];
            for (var k=0; k<sal.itms.length; k++) {
                itm = sal.itms[k];
                if (itm != null) {
                    fdx.push(k);
                    ftyp += "#"+itm.typ;
                    oprs += "#"+itm.opr;
                    fncs += "#"+itm.fnc;
                    wins += "#"+seek(fdx, itm.win);
//                    fd = "";
                    if (itm.fnc == 2) {
                        flds += "#"+TabImplode(itm.flds, '~', ':');
                    } else {//if (itm.typ == VAL || itm.typ == BRC || itm.typ == IFF)
                        flds += "#"+(itm.flds.join('~'));
//                    else {
//                        for (var l=0; l<itm.flds.length; l++)
//                            fd += "~"+seek(idx, itm.flds[l]);
//                        flds += "#"+fd.substr(1);
                    }
                }
            }
            nam_val += "|"+sal.name;
            typ_val += "|"+sal.typ;
            cml_val += "|"+sal.cmls;
            ftp_val += "|"+ftyp.substr(1);
            opr_val += "|"+oprs.substr(1);
            fnc_val += "|"+fncs.substr(1);
            fld_val += "|"+flds.substr(1);
            win_val += "|"+wins.substr(1);
            sta_val += "|"+sal.state;
        }
    }
    
    $("#parts").val(nam_val);
    $("#typs").val(typ_val);
    $("#cmls").val(cml_val);
    $("#ftyp").val(ftp_val);
    $("#oprs").val(opr_val);
    $("#fncs").val(fnc_val);
    $("#flds").val(fld_val);
    $("#wins").val(win_val);
    $("#state").val(sta_val);
    return true;
}

function TabExplode(str, gum, glue) {
    var Tab = [], rows;
    if (str.length > 0) {
        rows = str.split(gum);
        for (var i in rows)
            Tab.push(rows[i].indexOf(glue) == -1 ? rows[i] : rows[i].split(glue));
    }
    return Tab;
}

function TabImplode(Tab, gum, glue) {
    var str = "";
    if (Tab.length > 0)
        for (var i in Tab) {
            str += gum + (typeof Tab[i] == 'object' ? Tab[i].join(glue) : Tab[i]);
        }
    return str.substr(gum.length);
}

function vetTmpl() {
    if (!collateObj())
        return false;
    return validateFormPop(arrFormValidation);
}
