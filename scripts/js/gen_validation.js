function vetext(obj, typ) {
    var fname = obj.value;
    if (fname == "")
        return -1;
    var fext = "," + fname.substr(fname.lastIndexOf('.') + 1).toLowerCase()	+ ",";
    if (typ == 1) {
        var exts = ",jpg,jpe,jpeg,gif,png,bmp,wbmp,wbm,rle,dib,";
        if (exts.search(fext) < 0) {
            alert('You can only Upload pictures of type {jpg, jpe, jpeg, gif, png, bmp}!');
            obj.focus();
            return 0;
        }
    } else if (typ == 2) {
        var exts = ",gif,3g2,3gp,RoQ,aac,ac3,adts,aiff,alaw,amr,asf,ass,au,avi,avm2,daud,dirac,dnxhd,dts,dv,dvd,eac3,f32be,f32le,f64be,f64le,ffm,flac,flv,gxf,h261,h263,h264,image2,image2pipe,ipod,m4v,matroska,mjpeg,mmf,mov,mp4,m4a,3g2,mj2,mp2,mp3,mp4,mpeg,mpg,mpeg1video,mpeg2video,mpegts,mpegvideo,mulaw,mxf,nut,ogg,psp,raw,rm,s16be,s16le,s24be,s24le,s32be,s32le,s8,svcd,swf,u16be,u16le,u24be,u24le,u32be,u32le,u8,vcd,vob,voc,wav,yuv4mpegpipe,wma,wmv,";
        if (exts.search(fext) < 0) {
            alert('The file "' + fname + '" is not a supported video or audio format!');
            obj.focus();
            return 0;
        }
    }
    return 1;
}

function vetform(px, vid, cnt) {
    var ret = true, e, v;
    var holo = 0, vet;
    for ( var k = 1; k <= px; k++) {
        var sfx = px==1 ? '' : k;
        e = document.getElementById('picture' + sfx);
        vet = vetext(e, 1);
        if (vet == 0) {
            ret = false;
            break;
        } else if (vet == 1)
            holo++;
    }
    if (ret) {
        for ( var k = 1; k <= vid; k++) {
            v = document.getElementById('video' + k);
            vet = vetext(v, 2);
            if (vet == 0) {
                ret = false;
                break;
            } else if (vet == 1)
                holo++;
        }
    }
    if (cnt > holo) {
        ret = false;
        popmsg(e != null ? e : v, 'You must select at least ' + cnt + ' media to upload');
    }
    return ret;
}

function vetcontact() {
    var coy = v = document.getElementById('coyinfo');
    /*
     * switch(parseInt(coy)) { case 1: contactname cntaddress cntphone cntemail
     * cntstate cntcountry cntcity }
     */
    if (splitted == null)
        return false;
    if (splitted[1] != null) {
        var regexp_user = /^\"?[\w-_\.]*\"?$/;
        if (splitted[1].match(regexp_user) == null)
            return false;
    }
    if (splitted[2] != null) {
        var regexp_domain = /^[\w-\.]*\.[A-Za-z]{2,4}$/;
        if (splitted[2].match(regexp_domain) == null) {
            var regexp_ip = /^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
            if (splitted[2].match(regexp_ip) == null)
                return false;
        }
        return true;
    }
    return false;
}

function validateEmail(email) {
    // a very simple email validation checking.
    // you can add more complex email checking if it helps
    var splitted = email.match("^(.+)@(.+)$");
    if (splitted == null)
        return false;
    if (splitted[1] != null) {
        var regexp_user = /^\"?[\w-_\.]*\"?$/;
        if (splitted[1].match(regexp_user) == null)
            return false;
    }
    if (splitted[2] != null) {
        var regexp_domain = /^[\w-\.]*\.[A-Za-z]{2,4}$/;
        if (splitted[2].match(regexp_domain) == null) {
            var regexp_ip = /^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
            if (splitted[2].match(regexp_ip) == null)
                return false;
        }
        return true;
    }
    return false;
}

var msg = "";
function showpop() {
    awmShowGroup('fieldmsg-gr0',4,6,0,0);
    document.getElementById('frmmsg').innerHTML = showLines(28, msg, '<br>');
    $('#awmAnchor-fieldmsg-gr0').attr('id', holdname);
}

var holdname = '', olditem;
function popmsg(xitem, message) {
    $('#awmAnchor-fieldmsg-gr0').attr('id', holdname);
    holdname = $(xitem).attr('id');
    if (!$(xitem).is(":hidden") && $(xitem).is(':visible')) {
        xitem.focus();
        $(xitem).attr('id', "awmAnchor-fieldmsg-gr0");
        msg = message;
        setTimeout("showpop()", 100);
    } else {
        awmHideMenu('fieldmsg');
        alert(message);
    }
}

function validateDataPop(strValidateStr, cmp, strError, disp) {
    var epos = strValidateStr.search("=");
    var command = "";
    var cmdvalue = "";
    var charpos, parts;
    var resp = true;
    if (epos >= 0) {
        command = strValidateStr.substring(0, epos);
        cmdvalue = strValidateStr.substr(epos + 1);
    } else {
        command = strValidateStr;
    }
    switch (command) {
        case "req":
        case "required": {
            if (eval(cmp.value.length) == 0) {
                if (!strError || strError.length == 0) {
                    strError = "Required Field";
                }
                resp = false;
            }
            break;
        }
        case "vet": {
            if (cmp.value != document.getElementsByName(cmdvalue)[0].value) {
                if (!strError || strError.length == 0) {
                    strError = cmp.name + " and " + cmdvalue + " do not match";
                }
                resp = false;
            }
            break;
        }
        case "nequiv": {
            if (cmp.value == document.getElementsByName(cmdvalue)[0].value) {
                if (!strError || strError.length == 0) {
                    strError = cmp.name + " and " + cmdvalue + " should not be the same value";
                }
                resp = false;
            }
            break;
        }
        case "if": {
            parts = cmdvalue.split('|');
            if (eval(parts[0])) {
                if (parts.length > 1)
                    return validateDataPop(parts[1], cmp, strError);
            } else if (parts.length == 1)
                return false;
            break;
        }
        case "eval": {
            resp = eval(cmdvalue);
            break;
        }
        case "or": {
            parts = cmdvalue.split('|');
            var pass = false, v = 1;
            while (!pass && v<parts.length) {
                pass = validateDataPop(parts[v], cmp, strError);
                v++;
            }
            return pass;
        }
        case "maxlength":
        case "maxlen": {
            if (eval(cmp.value.length) > eval(cmdvalue)) {
                if (!strError || strError.length == 0) {
                    strError = cmp.name + " : " + cmdvalue + " characters maximum " + "\n[Current length = " + cmp.value.length + " ]";
                }
                resp = false;
            }
            break;
        }
        case "minlength":
        case "minlen": {
            if (eval(cmp.value.length) < eval(cmdvalue)) {
                if (!strError || strError.length == 0) {
                    strError = cmp.name + " : " + cmdvalue + " characters minimum  " + "\n[Current length = " + cmp.value.length + " ]";
                }
                resp = false;
            }
            break;
        }
        case "alnum":
        case "alphanumeric": {
            charpos = cmp.value.search("[^A-Za-z0-9]");
            if (cmp.value.length > 0 && charpos >= 0) {
                if (!strError || strError.length == 0) {
                    strError = "Only alpha-numeric characters allowed " + "\n [Error character position " + eval(charpos + 1) + "]";
                }
                resp = false;
            }
            break;
        }
        case "num":
        case "numeric": {
            charpos = cmp.value.search("[^0-9]");
            if (cmp.value.length > 0 && charpos >= 0) {
                if (!strError || strError.length == 0) {
                    strError = "Only digits allowed " + "\n [Error character position " + eval(charpos + 1) + "]";
                }
                resp = false;
            }
            break;
        }
        case "alphabetic":
        case "alpha": {
            charpos = cmp.value.search("[^A-Za-z]");
            if (cmp.value.length > 0 && charpos >= 0) {
                if (!strError || strError.length == 0) {
                    strError = "Only alphabetic characters allowed " + "\n [Error character position " + eval(charpos + 1) + "]";
                }
                resp = false;
            }
            break;
        }
        case "alnumhyphen": {
            charpos = cmp.value.search("[^A-Za-z0-9\-_]");
            if (cmp.value.length > 0 && charpos >= 0) {
                if (!strError || strError.length == 0) {
                    strError = "Characters allowed are A-Z,a-z,0-9,- and _" + "\n [Error character position " + eval(charpos + 1) + "]";
                }
                resp = false;
            }
            break;
        }
        case "email": {
            if (!validateEmail(cmp.value)) {
                if (!strError || strError.length == 0) {
                    strError = "Enter a valid Email address ";
                }
                resp = false;
            }
            break;
        }
        case "lt":
        case "lessthan": {
            if (isNaN(cmp.value)) {
                strError = "Should be a number ";
                resp = false;
            }
            if (eval(cmp.value) >= eval(cmdvalue)) {
                if (!strError || strError.length == 0) {
                    strError = "Value should be less than " + cmdvalue;
                }
                resp = false;
            }
            break;
        }
        case "gt":
        case "greaterthan": {
            if (isNaN(cmp.value)) {
                strError = "Should be a number ";
                return false;
                resp = false;
            }
            if (eval(cmp.value) <= eval(cmdvalue)) {
                if (!strError || strError.length == 0) {
                    strError = "Value should be greater than " + cmdvalue;
                }
                resp = false;
            }
            break;
        }
        case "chk": {
            if (!strError || strError.length == 0) {
                strError = "Please wait while we verify your data :" + cmdvalue;
            }
            if (cmp.value == 0) {
                firechange(document.getElementsByName(cmdvalue)[0]);
                resp = false;
            } else if (eval(cmp.value) == -1) {
                resp = false;
            }  
            break;
        }
        case "regexp": {
            re = new RegExp(cmdvalue);
            if (!cmp.value.match(re)) {
                if (!strError || strError.length == 0) {
                    strError = "Invalid characters found ";
                }
                resp = false;
            }
            break;
        }
        case "notregexp": {
            re = new RegExp(cmdvalue);
            if (cmp.value.match(re)) {
                if (!strError || strError.length == 0) {
                    strError = "Invalid characters found ";
                }
                resp = false;
            }
            break;
        }
        case "dontselect": {
            if (cmp.selectedIndex == null) {
                strError = "BUG: dontselect command for non-select Item";
                resp = false;
            }
            if (cmp.selectedIndex == eval(cmdvalue)) {
                if (!strError || strError.length == 0) {
                    strError = "Please Select one option ";
                }
                resp = false;
            }
            break;
        }
    }
    if (!resp)
        popmsg(disp, strError);
    return resp;
}

function validateFormPop(arrObjDesc) {
    var fldobj, elems, i;
    for ( var x = 0; x < arrObjDesc.length; x++) {
        fldobj = document.getElementsByName(arrObjDesc[x][0])[0];
        if (arrObjDesc[x][1].length == 0 || validateDataPop(arrObjDesc[x][1], fldobj, ''))
            for ( var y = 2; y < arrObjDesc[x].length; y++) {
                fldobj.value = trimme(fldobj.value);
                if (arrObjDesc[x][y].length==3) {
                    elems = arrObjDesc[x][y][2].split(',');
                    i = 0;
                    do {
                        fldobj = $('#'+elems[i]).get(0);
                    } while (i < elems.length && $('#'+elems[i++]).is(":hidden"));
                }
                if (validateDataPop(arrObjDesc[x][y][0], fldobj, arrObjDesc[x][y][1], fldobj) == false) {
                    fldobj.focus();
                    return false;
                }
            }
    }
    return true;
}

function checkup(fobj, url) {
    var nm = fobj.name;
    document.getElementById(nm+'win').src = url;
    document.getElementById(nm+'progress').innerHTML = '<img src="/images/working.gif" width="16" height="16" />';
    document.getElementById('vet'+nm).value = -1;
}

function replycheck(fobj, response, show, msg) {
    var nm = fobj.name;
    if (response==true) {
        document.getElementById(nm+'progress').innerHTML = '<img src="/images/check.png" width="16" height="16" />';
        document.getElementById('vet'+nm).value = 1;
        awmHideMenu('fieldmsg');
    } else {
        document.getElementById(nm+'progress').innerHTML = '<img src="/images/cancel_.png" width="16" height="16" />';
        document.getElementById('vet'+nm).value = 0;
        if (show) popmsg(fobj, msg);
    }
}
