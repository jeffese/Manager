//JavaScript Document

function getPageEventCoords(evt) {
    var coords = {
        left: 0,
        top: 0
    };
    if (evt.pageX) {
        coords.left = evt.pageX;
        coords.top = evt.pageY;
    } else if (evt.clientX) {
        coords.left =
                evt.clientX + document.body.scrollLeft - document.body.clientLeft;
        coords.top =
                evt.clientY + document.body.scrollTop - document.body.clientTop;
        //		include html element space, if applicable
        if (document.body.parentElement && document.body.parentElement.clientLeft) {
            var bodParent = document.body.parentElement;
            coords.left += bodParent.scrollLeft - bodParent.clientLeft;
            coords.top += bodParent.scrollTop - bodParent.clientTop;
        }
    }
    return coords;
}

function getPositionedEventCoords(evt) {
    var elem = (evt.target) ? evt.target : evt.srcElement;
    var coords = {
        left: 0,
        top: 0
    };
    if (evt.layerX) {
        var borders = {
            left: parseInt(getElementStyle("progressBar",
                    "borderLeftWidth", "border-left-width")),
            top: parseInt(getElementStyle("progressBar",
                    "borderTopWidth", "border-top-width"))
        };
        coords.left = evt.layerX - borders.left;
        coords.top = evt.layerY - borders.top;
    }
    else if (evt.offsetX) {
        coords.left = evt.offsetX;
        coords.top = evt.offsetY;
    }
    evt.cancelBubble = true;
    return coords;
}

function getElementStyle(elemID, IEStyleAttr, CSSStyleAttr) {
    var elem = document.getElementById(elemID);
    if (elem.currentStyle) {
        return elem.currentStyle[IEStyleAttr];
    } else if (window.getComputedStyle) {
        var compStyle = window.getComputedStyle(elem, "");
        return compStyle.getPropertyValue(CSSStyleAttr);
    }
    return "";
}

function set_features(n) {
    eval("ft=document.Frm.f" + n);
    var feats = '';
    for (var i = 0; i < ft.length; i++) {
        feats += '-' + (ft[i].checked ? '1' : '0');
    }
    document.getElementById('features').value = feats.substr(1);
}

function collateFeats(ft, fld) {
    var feats = '';
    for (var i = 0; i < ft.length; i++) {
        if (ft[i].checked) {
            feats += '|' + ft[i].value;
        }
    }
    fld.value = feats.substr(1);
}

function explode_feats(ft, val) {
    var vals = val.split("|");
    for (var i = 0; i < vals.length; i++) {
        for (var j = 0; j < ft.length; j++) {
            if (vals[i] == ft[j].value) {
                ft[j].checked = "checked";
                break;
            }
        }
    }
}

function setfeatures(n, fval) {
    eval("ft=document.Frm.f" + n);
    var relval = fval.split("-");
    for (var i = 0; i < relval.length; i++) {
        if (relval[i] == 1) {
            ft[i].checked = true;
        }
    }
}

function set_feat(mother, kid) {
    var feats = '', fts;
    for (var i = 0; i < kid.length; i++) {
        eval("ft=document.Frm." + kid[i]);
        fts = '';
        for (var j = 0; j < ft.length; j++) {
            fts += '-' + (ft[j].checked ? '1' : '0');
        }
        feats += ':' + fts.substr(1);
    }
    document.getElementById(mother).value = feats.substr(1);
}

function getfeat(mother, kid) {
    if (mother != "" && mother != "0") {
        var rval = mother.split(":"), val;
        for (var i = 0; i < kid.length; i++) {
            eval("ft=document.Frm." + kid[i]);
            val = rval[i].split("-");
            for (var j = 0; j < val.length; j++) {
                if (val[j] == 1) {
                    ft[j].checked = true;
                }
            }
        }
    }
}

function setlatlng(f) {
    if (!(/*(f.address.value=='')||*/(f.city.value == '') || (f.state.value == '') || (f.country.value == 0))) {
        document.getElementById('getlatlng').src = '/scripts/Glatlng.php?addr=' + escape(f.address.value + ' ' + f.city.value + ' ' + f.state.value + ' ' + f.country.options[f.country.selectedIndex].text);
    }
}

function showbut(lat, lng) {
    var f = document.getElementsByName('address')[0].form;
    f.lat.value = lat;
    f.lng.value = lng;
    document.getElementById('map').style.display = 'block';
    document.getElementById('mapwin').focus();
    document.getElementById('mapwin').src = '/scripts/map.php?g=' + f.lng.value + ',%20' + f.lat.value + '&amp;typ=2';
}

function codeAddress(address) {
    var loc = [0, 0, 'We cannot locate this address on the map. To locate it manualy, click the button.'];
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        'address': address
    }, function (response) {
        if (response.status == google.maps.GeocoderStatus.OK) {
            var place = response.Placemark[0];
            loc = [place.Point.coordinates[1], place.Point.coordinates[0], 'Your address has been located on the map. Click on the button to view or change'];
        }
    });
    return loc;
}

function insopt() {
    if (companies.length > 0) {
        coyselx.options[0] = new Option("Select a Company", "", true, true);
        for (var i = 0; i < companies.length; i++) {
            coyselx.options[i + 1] = new Option(companies[i], compids[i], false, false);
        }
    } else {
        coyselx.options[0] = new Option("No Company to select from!", "", true, true);
    }
    offselx.options[0] = new Option("Select a Company first!", "", true, true);
}

function menuover(mod) {
    mod.style.border = "thin solid #FF0000";
}

function menuout(mod) {
    mod.style.border = "none";
}

function pixnav(dir, pfx) {
    var pixf = document.getElementById('picture').value;
    var picf = document.getElementById('fpath');
    if (pixf != '0') {
        for (var i = 1; i <= pixf; i++) {
            if (i <= pixf) {
                document.getElementById('px' + i).style.display = "block";
                if (document.getElementById('p' + i)) {
                    document.getElementById('p' + i).value = '1';
                }
                document.getElementById('pix' + i).src = dir + picf.value + pfx + i + ".jpg" + pixrnd;
            }
        }
    }
}

function pixvnav(pfx, px, nav, dir) {
    var pixf = document.getElementById('px' + px).value;
    var prev = document.getElementById('prev' + px);
    var next = document.getElementById('next' + px);
    var pict = document.getElementById('pix' + px);
    var picf = document.getElementById('pxf' + px);
    var pixr = document.getElementById('pxr' + px);
    var pos = Number(pict.name);
    var npos = pos + nav;
    if (pixf < 2) {
        prev.style.display = "none";
        next.style.display = "none";
    }
    else {
        prev.style.display = "block";
        next.style.display = "block";
    }
    if (pixf == '0') {
        pict.src = '/images/noitem.jpg';
        pixr.style.display = "none";
    } else {
        if (npos > pixf) {
            npos = 1;
        } else if (npos < 1) {
            npos = pixf;
        }
        pict.name = String(npos);
        pixr.value = ' ' + (npos) + ' of ' + pixf + ' Pix';
        pict.src = dir + picf.value + pfx + npos + ".jpg" + pixrnd;
    }
}

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function setCookie(name, value, expdate, path, domain, secure) {
    var exp = new Date( );
    var expiretime = exp.getTime( ) + (expdate * 60 * 1000);
    exp.setTime(expiretime);
    var curCookie = name + "=" + escape(value) +
            ((expdate == 0) ? "" : "; expires=" + exp.toUTCString( )) +
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            ((secure == 1) ? "; secure" : "");
    document.cookie = curCookie;
}

function getCookie(Name) {
    var search = Name + "=";
    if (document.cookie.length > 0) { // if there are any cookies
        var offset = document.cookie.indexOf(search);
        if (offset != -1) { // if cookie exists
            offset += search.length;  // set index of beginning of value
            var end = document.cookie.indexOf(";", offset);  // set index of end
            // of cookie value
            if (end == -1)
                end = document.cookie.length;
            return unescape(document.cookie.substring(offset, end));
        }
    }
    return ""
}

function deleteCookie(name, path, domain, secure) {
    var exp = new Date();
    exp.setTime(exp.getTime() - 1000);
    setCookie(name, '', exp, path, domain, secure);
}

function fixchar(obj) {
    var parv = /[^A-Za-z0-9_\@\ \.\-]/gi;
    var otr = obj;
    var str = obj.value;
    if (str.search(parv) > -1) {
        // otr.focus();
        alert('Only Alphabets(a-z), Numbers(0-9) and characters (.,-,_,@, space) are allowed in this field');
    }
}

function remcom(badstr) {
    return badstr.replace(/[^A-Za-z0-9_\@\ \.\-]/gi, ' ');
}

function numposition(str) {
    switch (("" + str).charAt(str.length - 1)) {
        case "1":
            return "st";
        case "2":
            return "nd";
        case "3":
            return "rd";
        default:
            return "th";
    }
}

function numposit(cmp, div, str) {
    document.getElementById(div).innerHTML = numposition(cmp.value) + str;
}

function setnum(txtin) {
    var txt = (txtin + "").replace(/[^-\.\d]/g, "");
    txt = txt.length == 0 ? '0' : txt;
    txt = "" + parseFloat(txt);
    return txt;
}

function numme(me, min) {
    var val = setnum(me.value);
    if (min) {
        val = val < min ? min : val;
    }
    me.value = val;
}

function getnumbyid(id) {
    return setnum($("#" + id).val());
}

function getnum(name) {
    return parseFloat(setnum($("input[name='" + name + "']").val()));
}

function setMoney(txtin) {
    var val = rndup(setnum(txtin), 2);
    return setthous(val);
}

function MoneyMe(me, min) {
    me.value = setMoney(me.value);
}

function setintnum(txtin) {
    var txt = txtin.replace(/[^\d]/g, "");
    txt = txt == "" ? "0" : txt;
    txt = parseInt(txt);
    return txt;
}

function Numcheck(numsub) {
    switch (numsub) {
        case 0 :
            return 'Zero';
        case 1 :
            return 'One';
        case 2 :
            return 'Two';
        case 3 :
            return 'Three';
        case 4 :
            return 'Four';
        case 5 :
            return 'Five';
        case 6 :
            return 'Six';
        case 7 :
            return 'Seven';
        case 8 :
            return 'Eight';
        case 9 :
            return 'Nine';
        case 10:
            return 'Ten';
        case 11:
            return 'Eleven';
        case 12:
            return 'Twelve';
        case 13:
            return 'Thirteen';
        case 14:
            return 'Fourteen';
        case 15:
            return 'Fifteen';
        case 16:
            return 'Sixteen';
        case 17:
            return 'Seventeen';
        case 18:
            return 'Eighteen';
        case 19:
            return 'Nineteen';
    }
}

function Tencheck(numsub) {
    switch (numsub) {
        case 2:
            return 'Twenty';
        case 3:
            return 'Thirty';
        case 4:
            return 'Forty';
        case 5:
            return 'Fifty';
        case 6:
            return 'Sixty';
        case 7:
            return 'Seventy';
        case 8:
            return 'Eighty';
        case 9:
            return 'Ninety';
    }
}

function Hundcheck(numsub) {
    var a = Math.floor((numsub % 1000) / 100);
    var a1 = '';
    var b = Math.floor((numsub % 100) / 10);
    var b1 = '';
    var c = Math.floor(numsub % 10);
    var d = b * 10 + c;
    var andval;
    if (a > 0)
        a1 = Numcheck(a) + ' Hundred';
    if (b > 1)
        b1 = Tencheck(b);
    if (b > 1 && c > 0)
        b1 = b1 + '-' + Numcheck(c);
    if (b < 2 && d > 0)
        b1 = Numcheck(d);
    if (a1 == '' || b1 == '')
        andval = '';
    else
        andval = ' and ';
    return a1 + andval + b1;
}

function Billcheck(numsub) {
    var Billval = '', commaval = '';
    var a = Math.floor(numsub / 1000000000);
    if (a > 0)
        Billval = Hundcheck(a) + ' Billion';

    var b = Math.floor((numsub % 1000000000) / 1000000);
    if (b > 0) {
        if (Billval != '')
            commaval = ', ';
        Billval = Billval + commaval + Hundcheck(b) + ' Million';
    }

    var c = Math.floor((numsub % 1000000) / 1000);
    if (c > 0) {
        if (Billval != '')
            commaval = ', ';
        Billval = Billval + commaval + Hundcheck(c) + ' Thousand';
    }

    var d = Math.floor(numsub % 1000);
    if (d > 0) {
        if (Billval != '')
            commaval = ', ';
        Billval = Billval + commaval + Hundcheck(d);
    }
    return Billval;
}

function NumToWords(numrealval, currVal, CurUnitVal) {
    var numval1 = Math.floor(numrealval);
    var frac = (numrealval - numval1) * 100;
    var numval2 = rndup(frac, 2);
    var zeroval1 = '';
    var zeroval2 = '';
    if (numval1 == 0)
        zeroval1 = 'Zero';
    if (numval2 == 0)
        zeroval2 = 'Zero';
    return Billcheck(numval1) + zeroval1 + ' ' + currVal + ' ' + Billcheck(numval2) + zeroval2 + ' ' + CurUnitVal;
}

function trimme(txtin) {
    var txt = txtin.replace(/^\s+/, '');
    txt = txt.replace(/\s+$/, '');
    return txt;
}

function trimval(itm) {
    var txt = itm.value;
    txt = txt.replace(/^\s+/, '');
    txt = txt.replace(/\s+$/, '');
    itm.value = txt;
}

function clearspace(txtin) {
    var txt = txtin.replace(/\s+/gi, '');
    return txt;
}

function setthous(txt) {
    txt = txt + "";
    var sign = '';
    if (txt[0] == '-') {
        sign = txt[0];
        txt = txt.substr(1);
    }
    var i = txt.length;
    var txtview = "";
    var x = 0;
    var parv = /\./g;
    if (txt.search(parv) > -1) {
        i = txt.search(parv);
        txtview = txt.substr(txt.search(parv));
    }
    while (i > 0) {
        x++;
        if (((x % 3) == 1) && (x > 1)) {
            txtview = "," + txtview;
        }
        txtview = txt.charAt(i - 1) + txtview;
        i--;
    }
    return sign + txtview;
}

function thousset(txt) {
    var i = txt.length;
    var txtview = "";
    var x = 0;

    while (i > 0) {
        x++;
        if (((x % 3) == 1) && (x > 1)) {
            txtview = "," + txtview;
        }
        txtview = txt.charAt(i - 1) + txtview;
        i--;
    }
    return txtview;
}

/**
 *Roundup fraction number (val)
 *x digit spaces.
 *y : -1 down; 0: to nearest; 1 :up
 */
function rndup(val, x, y) {
    y = (!y ? 0 : y);
    switch (y) {
        case 0:
            return Math.round(val * Math.pow(10, x)) / Math.pow(10, x);
        case 1:
            return Math.ceil(val * Math.pow(10, x)) / Math.pow(10, x);
        case -1:
            return Math.floor(val * Math.pow(10, x)) / Math.pow(10, x);
    }
}

/**
 * Significant Number
 * @param val: float input number
 * @param x: digits
 * @param y: -1:down; 0:to nearest; 1:up
 * @return: integer
 */
function Sig_Num(val, x, y) {
    var Val = val + "";
    var pos = Val.indexOf('.');
    var len = pos == -1 ? Val.length : pos + 1;
    var z = len - Math.min(len, x);
    var pw = Math.pow(10, z);
    var num = val / pw;
    var round = 0;
    switch (y) {
        case 0:
            if (Math.ceil(num) - num < 0.5) {
                round = Math.ceil(num) * pw;
            } else {
                round = Math.floor(num) * pw;
            }
            break;
        case 1:
            round = Math.ceil(num) * pw;
            break;
        case -1:
            round = Math.floor(num) * pw;
    }
    return round;
}

function trailzero(txt, z) {
    txt = txt + ''
    var str = txt.split('.')
    var dec = str.length > 1 ? str[1] : ''
    var len = dec.length;

    if (z > len) {
        dec = padstr(dec, z - len);
    } else if (z < len) {
        dec = dec.substr(0, z);
    }
    return str[0] + '.' + dec
}

function padstr(str, len, pad, trail) {
    str = str + "";
    if (!pad) {
        pad = "0";
    }
    var l = len - str.length;
    for (var i = 0; i < l; i++) {
        if (trail) {
            str += pad;
        } else {
            str = pad + str;
        }
    }
    return str;
}

function format_Num(objview, obj) {
    var txt = setnum(objview.value);
    var txtview = setthous(txt);
    obj.value = txt;
    objview.value = txtview;
}

function colchkval(chk, chkval) {
    var txtval = '';
    for (var i = 0; i < chk.length; i++) {
        txtval += (chk[i].checked == true) ? '^' + (i + 1) + '$|' : '';
    }
    chkval.value = (txtval.length == 0) ? '' : txtval.substr(0, txtval.length - 1);
}

function colval(obj, objval) {
    var txtval = '';
    for (var i = 0; i < obj.length; i++) {
        txtval += (obj[i].value != '') ? obj[i].value + '|' : '';
    }
    objval.value = (txtval.length == 0) ? '' : txtval.substr(0, txtval.length - 1);
}

function fixNum(ob) {
    var parv = /[^0-9]/g;
    var otr = ob;
    var str = ob.value;
    if (str.search(parv) > -1) {
        // otr.focus();
        alert('Only Numbers are allowed here.');
    }
}

function secPast(dt1, dt2) {
    dt1 = dt1.length < 14 ? dt1 + ' 00:00:00' : dt1;
    dt2 = dt2.length < 14 ? dt2 + ' 00:00:00' : dt2;
    var dtstr = dt1.replace(/\D/g, ",");
    var dt = dtstr.split(',');
    var actd1 = new Date(dt[0], dt[1] - 1, dt[2], dt[3], dt[4], dt[5]);
    dtstr = dt2.replace(/\D/g, ",");
    var ds = dtstr.split(',');
    var actd2 = new Date(ds[0], ds[1] - 1, ds[2], ds[3], ds[4], ds[5]);
    var sec1 = parseInt(actd1.valueOf() / (1000), 10);
    var sec2 = parseInt(actd2.valueOf() / (1000), 10);
    return (sec1 - sec2);
}

function daysPast(dt1, dt2) {
    var dtstr = dt1.replace(/\D/g, ",");
    var dt = dtstr.split(',');
    var actd1 = new Date(dt[0], dt[1] - 1, dt[2]);
    dtstr = dt2.replace(/\D/g, ",");
    var ds = dtstr.split(',');
    var actd2 = new Date(ds[0], ds[1] - 1, ds[2]);
    var day1 = parseInt(actd1.valueOf() / (1000 * 60 * 60 * 24), 10);
    var day2 = parseInt(actd2.valueOf() / (1000 * 60 * 60 * 24), 10);
    return (day1 - day2);
}

function timepast(dt1, dt2, sh) {
    var lastactive = "";
    var dtstr = dt1.replace(/\D/g, ",");
    var dt = dtstr.split(',');
    var actd1 = new Date(dt[0], dt[1] - 1, dt[2], dt[3], dt[4], dt[5]);
    dtstr = dt2.replace(/\D/g, ",");
    var ds = dtstr.split(',');
    var actd2 = new Date(ds[0], ds[1] - 1, ds[2], ds[3], ds[4], ds[5]);
    var sec1 = parseInt(actd1.valueOf() / (1000), 10);
    var sec2 = parseInt(actd2.valueOf() / (1000), 10);
    var timegap = (sec1 - sec2);
    var daygap = (sec1 - sec2) / (60 * 60 * 24);
    daygap = parseInt(daygap, 10);
    var weekgap = (sec1 - sec2) / (60 * 60 * 24 * 7);
    weekgap = parseInt(weekgap, 10);
    var mthgap = (sec1 - sec2) / (60 * 60 * 24 * 30);
    mthgap = parseInt(mthgap, 10);
    var yeargap = (sec1 - sec2) / (60 * 60 * 24 * 365);
    yeargap = parseInt(yeargap, 10);
    if (timegap < 1440) {
        if (timegap < 2) {
            lastactive = timegap + " second ago";
        } else if (timegap < 60) {
            lastactive = timegap + " seconds ago";
        } else if (timegap < 120) {
            lastactive = "1 minute ago";
        } else if (timegap < 3600) {
            lastactive = parseInt(timegap / 60) + " minutes ago";
        } else if (timegap < 7200) {
            lastactive = "1 hour " + parseInt(timegap / 60) + " minutes ago";
        } else {
            lastactive = parseInt(timegap / (60 * 60)) + " hours " + parseInt(timegap / 60) + " minutes ago";
        }
    } else if (daygap < 2) {
        lastactive = "Yesterday";
    } else if (daygap < 7) {
        lastactive = daygap + " Days ago";
    } else if (daygap < 14) {
        lastactive = "Over a Week ago";
    } else if (daygap < 30) {
        lastactive = weekgap + " Weeks ago";
    } else if (daygap < 31) {
        lastactive = "Over a Month ago";
    } else if (daygap < 365) {
        lastactive = mthgap + " Months ago";
    } else if (daygap < 730) {
        lastactive = "Over a Year ago";
    } else if (daygap > 730) {
        lastactive = yeargap + " Years ago";
    }
    if (sh == true) {
        lastactive = lastactive + ' ' + ds[3] + ':' + ds[4] + ':' + ds[5] + ' ' + ds[2] + '/' + ds[1] + '/' + ds[0] + '';
    }
    return lastactive;
}

function hms(secs, t) {
    var time = [0, 0, secs];
    for (var i = 2; i > 2 - t; i--) {
        time[i - 1] = Math.floor(time[i] / 60);
        time[i] = time[i] % /**/60;
        if (time[i] < 10)
            time[i] = '0' + time[i];
        if ((t == 1) && (time[i - 1] == 0))
            break;
    }
    if (t == 2) {
        return time.join(':');
    } else
        return time[1] + ':' + time[2];
}

function sec2hms(tm) {
    var time = [0, 0, 0, 0, 0, 0];
    var rng = [1, 12, 30, 24, 60, 60];
    for (var i = 5; i > 0; i--) {
        time[i] = tm % rng[i];
        tm = Math.floor(tm / rng[i]);
    }
    time[0] = tm;
    var out = "";
    if (time[0] > 0)
        out += time[0] + 'yr ';
    if (time[1] > 0)
        out += time[1] + 'mth ';
    if (time[2] > 0)
        out += time[2] + 'dy ';
    if (time[3] > 0)
        out += time[3] + 'hr ';
    if (time[4] > 0)
        out += time[4] + 'min ';
    if (time[5] > 0 || out.length == 0)
        out += time[5] + 's';
    return out;
}

function parseDate(d) {
    if (Object.prototype.toString.call(d) === '[object Date]' && isFinite(d))
        return true;
    else
        return false;
}

function DateFromStr(str, now) {
    var dt = str.replace(/\D/g, ",");
    var dts = dt.split(',');
    switch (dts.length) {
        case 3:
            return new Date(dts[0], dts[1] - 1, dts[2]);
        case 6:
            return new Date(dts[0], dts[1] - 1, dts[2], dts[3], dts[4], dts[5]);
    }
    if (now)
        return new Date();
    else
        return null;
}

function dtStrFromDate(dt, D, T) {
    var yr = dt.getFullYear(),
            mth = padstr(dt.getMonth() + 1, 2, "0", false),
            dy = padstr(dt.getDate(), 2, "0", false),
            hr = padstr(dt.getHours(), 2, "0", false),
            mn = padstr(dt.getMinutes(), 2, "0", false),
            sc = padstr(dt.getSeconds(), 2, "0", false),
            d = yr + "-" + mth + "-" + dy,
            t = hr + ":" + mn + ":" + sc;
    if (D && T) {
        if (parseDate(dt))
            return d + " " + t;
        else
            return "0000-00-00 00:00:00";
    } else if (D) {
        if (parseDate(dt))
            return d;
        else
            return "0000-00-00";
    } else if (T) {
        if (parseDate(dt))
            return t;
        else
            return "00:00:00";
    }
    return null;
}

function formatdate(dbdate) {
    var xdate = '';
    if (dbdate.length > 0) {
        var splitdate = dbdate.split('-');
        xdate = splitdate[2] + '/' + splitdate[1] + '/' + splitdate[0];
    }
    return xdate;
}

function fixDate(date) {
    var base = new Date(0);
    var skew = base.getTime();
    if (skew > 0)
        date.setTime(date.getTime() - skew);
}

function formatdatetime(dbdate) {
    var dt = dbdate.split(" ");
    return dt[1] + ' ' + formatdate(dt[0]);
}

function getmilliDate(actdate) {
    var rdt = actdate.replace(/\D/g, ",").split(",");
    var activsec = Date.UTC(rdt[0], rdt[1] - 1, rdt[2]);
    return new Date(activsec);
}

function eng_date(actdate) {
    var str = getmilliDate(actdate).toString().split(" ");
    return str[0] + " " + str[2] + " " + str[1] + " " + str[3];
}

function formatday(actdate) {
    var retdate = "";
    var refdate = actdate.replace(/\D/g, ",");
    var rdt = refdate.split(",");
    var activsec = Date.UTC(rdt[0], rdt[1], rdt[2], rdt[3], rdt[4], rdt[5], 0);
    var newdate = new Date(activsec);
    var weekday, month, time;

    switch (newdate.getDay()) {
        case 0:
            weekday = "Sun";
            break;
        case 1:
            weekday = "Mon";
            break;
        case 2:
            weekday = "Tue";
            break;
        case 3:
            weekday = "Wed";
            break;
        case 4:
            weekday = "Thu";
            break;
        case 5:
            weekday = "Fri";
            break;
        case 6:
            weekday = "Sat";
            break;
        default:
            weekday = "";
    }
    switch (newdate.getMonth()) {
        case 0:
            month = "Jan";
            break;
        case 1:
            month = "Feb";
            break;
        case 2:
            month = "Mar";
            break;
        case 3:
            month = "Apr";
            break;
        case 4:
            month = "May";
            break;
        case 5:
            month = "Jun";
            break;
        case 6:
            month = "Jul";
            break;
        case 7:
            month = "Aug";
            break;
        case 8:
            month = "Sep";
            break;
        case 9:
            month = "Oct";
            break;
        case 10:
            month = "Nov";
            break;
        case 11:
            month = "Dec";
            break;
        default:
            month = "";
    }

    retdate = weekday + " " + newdate.getDate() + " " + month + " " + newdate.getYear();
    if (time)
        retdate += " " + newdate.getHours() + ":" + newdate.getMinutes() + ":" + newdate.getSeconds()
    return retdate;
}

function setcolor(color) {
    switch (color) {
        case 'Beige':
            clrval = "#F5F5DC";
            break;
        case 'Black':
            clrval = "#000000";
            break;
        case 'Blue':
            clrval = "#0000FF";
            break;
        case 'Brown':
            clrval = "#A52A2A";
            break;
        case 'Burgundy':
            clrval = "#";
            break;
        case 'Champagne':
            clrval = "#";
            break;
        case 'Charcoal':
            clrval = "#";
            break;
        case 'Cream':
            clrval = "#";
            break;
        case 'Gold':
            clrval = "#FFD700";
            break;
        case 'Gray':
            clrval = "#808080";
            break;
        case 'Green':
            clrval = "#008000";
            break;
        case 'Maroon':
            clrval = "#800000";
            break;
        case 'Off White':
            clrval = "#";
            break;
        case 'Orange':
            clrval = "#FFA500";
            break;
        case 'Pewter':
            clrval = "#";
            break;
        case 'Pink':
            clrval = "#FFC0CB";
            break;
        case 'Purple':
            clrval = "#800080";
            break;
        case 'Red':
            clrval = "#FF0000";
            break;
        case 'Silver':
            clrval = "#C0C0C0";
            break;
        case 'Tan':
            clrval = "#D2B48C";
            break;
        case 'Teal':
            clrval = "#008080";
            break;
        case 'Titanium':
            clrval = "#";
            break;
        case 'Turquoise':
            clrval = "#40E0D0";
            break;
        case 'White':
            clrval = "#FFFFFF";
            break;
        case 'Yellow':
            clrval = "#FFFF00";
            break;
        case 'Other':
            clrval = "#";
            break;
        default:
            clrval = "";
    }
    return clrval;
}

function optval(opt) {
    return opt.options[opt.selectedIndex].value;
}

function chkuplow(txt1, txt2, val) {
    var str1 = txt1.value;
    var str2 = txt2.value;
    if ((str1.indexOf("-") > -1 && str2.indexOf("-") > -1 && secPast(str1, str2) > 0) ||
            (parseInt(str1) > parseInt(str2)) && (str1 != '') && (str2 != '')) {
        alert('Upper limit should be higher or equal to lower limit for "' + val + '"');
        txt1.focus();
        return false;
    } else {
        return true;
    }
}

function setposval(pos, len) {
    var retval = '';
    for (var i = 1; i <= len; i++) {
        if (pos == i)
        {
            retval += '1-';
        } else {
            retval += '_-';
        }
    }
    return "'" + retval.slice(0, 9) + "'";
}

function hidshow(divtag) {
    var thistag = document.getElementById(divtag);
    if (thistag.style.display == 'none') {
        thistag.style.display = 'block';
    } else
        thistag.style.display = 'none';
}

function genblank(val) {
    return val.replace(/0/g, "_");
}

function tickinit() {
    var pos, l1, t1;
    if (ie) {
        if (l1 == 0 && t1 == 0) {
            pos = document.all['tickpos'];
            l1 = getLeft(pos);
            t1 = getTop(pos);
        }
        ticktext.style.posTop = t1;
    }
    else {
        if (l1 == 0 && t1 == 0) {
            pos = document.anchors['tickpos'];
            l1 = pos.x;
            t1 = pos.y;
        }
        document.ticktext.pageY = t1;
    }
    l2 = l1 + w1;
    l3 = l1 - l2;
    l = l2;
    setInterval('tick()', 200);
}

function getLeft(ll) {
    if (ll.offsetParent)
        return (ll.offsetLeft + getLeft(ll.offsetParent));
    else
        return (ll.offsetLeft);
}

function getTop(ll) {
    if (ll.offsetParent)
        return (ll.offsetTop + getTop(ll.offsetParent));
    else
        return (ll.offsetTop);
}

function tick() {
    l = l - 15;
    if (l < l3)
        l = l2;
    var cl = l1 - l;
    var cr = l2 - l;
    if (ie) {
        ticktext.style.posLeft = l;

        ticktext.style.posTop = t1;
        ticktext.style.clip = "rect(auto " + cr + "px auto " + cl + "px)";
        if (first)
            ticktext.style.visibility = "visible";
    }
    else {
        document.ticktext.pageX = l;
        document.ticktext.clip.left = cl;
        document.ticktext.clip.right = cr;
        if (first)
            document.ticktext.visibility = "show";
    }
    first = false;
}

/**
 * listbox redirection
 */
function goToUrl(selObj, goToLocation) {
    eval("document.location.href = '" + goToLocation + "pos=" + selObj.options[selObj.selectedIndex].value + "'");
}

/**
 * getElement
 */
function getElement(e, f) {
    if (document.layers) {
        f = (f) ? f : self;
        if (f.document.layers[e]) {
            return f.document.layers[e];
        }
        for (W = 0; i < f.document.layers.length; W++) {
            return(getElement(e, fdocument.layers[W]));
        }
    }
    if (document.all) {
        return document.all[e];
    }
    return document.getElementById(e);
}

function setSelectOptions(the_form, the_select, do_check)
{
    var selectObject = document.forms[the_form].elements[the_select];
    var selectCount = selectObject.length;

    for (var i = 0; i < selectCount; i++) {
        selectObject.options[i].selected = do_check;
    } // end for

    return true;
}

function setCheckboxColumn(theCheckbox) {
    if (document.getElementById(theCheckbox)) {
        document.getElementById(theCheckbox).checked = (document.getElementById(theCheckbox).checked ? false : true);
        if (document.getElementById(theCheckbox + 'r')) {
            document.getElementById(theCheckbox + 'r').checked = document.getElementById(theCheckbox).checked;
        }
    } else {
        if (document.getElementById(theCheckbox + 'r')) {
            document.getElementById(theCheckbox + 'r').checked = (document.getElementById(theCheckbox + 'r').checked ? false : true);
            if (document.getElementById(theCheckbox)) {
                document.getElementById(theCheckbox).checked = document.getElementById(theCheckbox + 'r').checked;
            }
        }
    }
}

function copyCheckboxesRange(the_form, the_name, the_clicked)
{
    if (typeof (document.forms[the_form].elements[the_name]) != 'undefined' && typeof (document.forms[the_form].elements[the_name + 'r']) != 'undefined') {
        if (the_clicked !== 'r') {
            if (document.forms[the_form].elements[the_name].checked == true) {
                document.forms[the_form].elements[the_name + 'r'].checked = true;
            } else {
                document.forms[the_form].elements[the_name + 'r'].checked = false;
            }
        } else if (the_clicked == 'r') {
            if (document.forms[the_form].elements[the_name + 'r'].checked == true) {
                document.forms[the_form].elements[the_name].checked = true;
            } else {
                document.forms[the_form].elements[the_name].checked = false;
            }
        }
    }
}

/**
 * This array is used to remember mark status of rows in browse mode
 */
var marked_row = new Array;

function setPointer(theRow, theRowNum, theAction, theDefaultColor, thePointerColor, theMarkColor) {
    var theCells = null;

    // 1. Pointer and mark feature are disabled or the browser can't get the
    // row -> exits
    if ((thePointerColor == '' && theMarkColor == '')
            || typeof (theRow.style) == 'undefined') {
        return false;
    }

    // 1.1 Sets the mouse pointer to pointer on mouseover and back to normal
    // otherwise.
    if (theAction == "over" || theAction == "click") {
        theRow.style.cursor = 'pointer';
    } else {
        theRow.style.cursor = 'default';
    }

    // 2. Gets the current row and exits if the browser can't get it
    if (typeof (document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof (theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    // 3. Gets the current color...
    var rowCellsCnt = theCells.length;
    var domDetect = null;
    var currentColor = null;
    var newColor = null;
    // 3.1 ... with DOM compatible browsers except Opera that does not return
    // valid values with "getAttribute"
    if (typeof (window.opera) == 'undefined'
            && typeof (theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('bgcolor');
        domDetect = true;
    }
    // 3.2 ... with other browsers
    else {
        currentColor = theCells[0].style.backgroundColor;
        domDetect = false;
    } // end 3

    // 3.3 ... Opera changes colors set via HTML to rgb(r,g,b) format so fix it
    if (currentColor.indexOf("rgb") >= 0)
    {
        var rgbStr = currentColor.slice(currentColor.indexOf('(') + 1,
                currentColor.indexOf(')'));
        var rgbValues = rgbStr.split(",");
        currentColor = "#";
        var hexChars = "0123456789ABCDEF";
        for (var i = 0; i < 3; i++)
        {
            var v = rgbValues[i].valueOf();
            currentColor += hexChars.charAt(v / 16) + hexChars.charAt(v % 16);
        }
    }

    // 4. Defines the new color
    // 4.1 Current color is the default one
    if (currentColor == ''
            || currentColor.toLowerCase() == theDefaultColor.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor = thePointerColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor = theMarkColor;
            marked_row[theRowNum] = true;
            // Garvin: deactivated onclick marking of the checkbox because it's
            // also executed
            // when an action (like edit/delete) on a single item is performed.
            // Then the checkbox
            // would get deactived, even though we need it activated. Maybe
            // there is a way
            // to detect if the row was clicked, and not an item therein...
            // document.getElementById('id_rows_to_delete' + theRowNum).checked
            // = true;
        }
    }
    // 4.1.2 Current color is the pointer one
    else if (currentColor.toLowerCase() == thePointerColor.toLowerCase()
            && (typeof (marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])) {
        if (theAction == 'out') {
            newColor = theDefaultColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor = theMarkColor;
            marked_row[theRowNum] = true;
            // document.getElementById('id_rows_to_delete' + theRowNum).checked
            // = true;
        }
    }
    // 4.1.3 Current color is the marker one
    else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
        if (theAction == 'click') {
            newColor = (thePointerColor != '')
                    ? thePointerColor
                    : theDefaultColor;
            marked_row[theRowNum] = (typeof (marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])
                    ? true
                    : null;
            // document.getElementById('id_rows_to_delete' + theRowNum).checked
            // = false;
        }
    } // end 4

    // 5. Sets the new color...
    if (newColor) {
        var c = null;
        // 5.1 ... with DOM compatible browsers except Opera
        if (domDetect) {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].setAttribute('bgcolor', newColor, 0);
            } // end for
        }
        // 5.2 ... with other browsers
        else {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].style.backgroundColor = newColor;
            }
        }
    } // end 5

    return true;
} // end of the 'setPointer()' function

function setCheckboxesRange(the_form, do_check, basename, min, max)
{
    for (var i = min; i < max; i++) {
        if (typeof (document.forms[the_form].elements[basename + i]) != 'undefined') {
            document.forms[the_form].elements[basename + i].checked = do_check;
        }
        if (typeof (document.forms[the_form].elements[basename + i + 'r']) != 'undefined') {
            document.forms[the_form].elements[basename + i + 'r'].checked = do_check;
        }
    }

    return true;
}

function navpagesjump(nav_tot, nav_i) {
    var cmbpages = document.getElementsByName("cmbpage");
    var cmbpage = cmbpages[cmbpages.length - 1];
    var nav_x = 0, nav_f;
    nav_tot += 1;
    var nav_gap = Math.round((nav_tot - 10) / 10);
    if (nav_gap == 0)
        nav_gap = 1;
    if (nav_i > 5)
        nav_f = nav_i - 5;
    else
        nav_f = 0;
    var nav_st = 0;
    while (nav_st < nav_f) {
        cmbpage.options[nav_x] = new Option(nav_st + 1, nav_st, false, false);
        nav_st += nav_gap;
        nav_x++;
    }
    if (nav_f > 0) {
        cmbpage.options[nav_x] = new Option('...', '', false, false);
        nav_x++;
    }
    var x = 0, sel;
    nav_st = nav_f;
    while ((nav_tot > nav_st) && (x < 10)) {
        sel = (nav_st == nav_i) ? true : false;
        cmbpage.options[nav_x] = new Option(nav_st + 1, nav_st, false, sel);
        x++;
        nav_x++;
        nav_st++;
    }
    if (nav_st < nav_tot) {
        cmbpage.options[nav_x] = new Option('...', '', false, false);
        nav_x++;
    }
    while (nav_st < nav_tot) {
        cmbpage.options[nav_x] = new Option(nav_st + 1, nav_st, false, false);
        nav_st += nav_gap;
        nav_x++;
    }
}

function showLines(max, text, sep) {
    text = "" + text;
    var cnt = max;
    var temp = "";
    var str = text.split(' ');
    for (var i = 0; i < str.length; i++) {
        if (cnt - 1 > str[i].length) {
            temp += str[i] + ' ';
            cnt -= str[i].length;
        } else {
            temp += sep + str[i] + ' ';
            cnt = max - str[i].length;
        }
    }
    return temp;
}

function firechange(obj) {
    //	On IE
    if (obj.fireEvent) {
        obj.fireEvent('onchange');
    }
    //	On Gecko based browsers
    if (document.createEvent) {
        var evt = document.createEvent('HTMLEvents');
        if (evt.initEvent) {
            evt.initEvent('change', true, true);
        }
        if (obj.dispatchEvent) {
            obj.dispatchEvent(evt);
        }
    }
}

function RadioChk(r) {
    for (var i in r) {
        if (r[i].checked) {
            return r[i].value;
        }
    }
    return null
}

function ChkRadio(r, v) {
    for (var i in r) {
        if (r[i].value == v) {
            r[i].checked = "checked";
            return;
        }
    }
}

function dropDnVal(sel, val) {
    for (var i = 0; i < sel.length; i++) {
        if (sel.options[i].value == val) {
            return sel.options[i].index;
        }
    }
    return null
}

function dropDnTxt(sel, txt) {
    for (var i = 0; i < sel.length; i++) {
        if (sel.options[i].text == txt) {
            return sel.options[i].index;
        }
    }
    return null
}

function dropDnSel(sel, val) {
    for (var i = 0; i < sel.length; i++) {
        if (sel.options[i].value == val) {
            sel.options[i].selected = true;
            return;
        }
    }
}

function loadhtml(fragment_url, element_id) {
    var element = document.getElementById(element_id);
    element.innerHTML = waitstr;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", fragment_url);
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            element.innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.send(null);
}

function loadhtml_merge(fragment_url, cmd, pre, post) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", fragment_url);
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var str = pre + xmlhttp.responseText + post;
            eval(cmd + 'str)');
        }
    }
    xmlhttp.send(null);
}

function chkifloaded() {
    if (document.getElementById('loaded') != undefined) {
        clearInterval(tmchk);
        runJSstr();
    }
}

function runJSstr() {
    var jvscript = document.getElementById('js').innerHTML;
    eval(jvscript);
}

function votes(vt, id, t) {
    document.getElementById('vtupx' + t + '_' + id).innerHTML = '<img src="/images/load_vote.gif" width="16" height="16" />';
    document.getElementById('vtdpx' + t + '_' + id).innerHTML = '<img src="/images/load_vote.gif" width="16" height="16" />';
    document.getElementById('booth' + t + '_' + id).src = '/scripts/functions/inec.php?t=' + t + '&id=' + id + '&v=' + vt;
}

function showvote(vt, id, t, up, dn) {
    if (vt == 1) {
        document.getElementById('vtupx' + t + '_' + id).innerHTML = '<img src="/images/thumbs_up1.png" width="16" height="16" title="Voted Up" />';
        document.getElementById('vtdpx' + t + '_' + id).innerHTML = '<img src="/images/thumbs_dn0.png" width="16" height="16" title="Voted Up" />';
    } else {
        document.getElementById('vtupx' + t + '_' + id).innerHTML = '<img src="/images/thumbs_up0.png" width="16" height="16" title="Voted Down" />';
        document.getElementById('vtdpx' + t + '_' + id).innerHTML = '<img src="/images/thumbs_dn1.png" width="16" height="16" title="Voted Down" />';
    }
    agr = up - dn;
    document.getElementById('votes' + t + '_' + id).innerHTML = agr;
    document.getElementById('vtup' + t + '_' + id).innerHTML = up;
    document.getElementById('vtdn' + t + '_' + id).innerHTML = dn;
}

function serialize(_obj) {
    // Let Gecko browsers do this the easy way
    if (typeof _obj.toSource !== 'undefined' && typeof _obj.callee === 'undefined')
    {
        return _obj.toSource();
    }

    // Other browsers must do it the hard way
    switch (typeof _obj)
    {
        // numbers, booleans, and functions are trivial:
        // just return the object itself since its default .toString()
        // gives us exactly what we want
        case 'number':
        case 'boolean':
        case 'function':
            return _obj;
            break;

            // for JSON format, strings need to be wrapped in quotes
        case 'string':
            return '\'' + _obj + '\'';
            break;

        case 'object':
            var str;
            if (_obj.constructor === Array || typeof _obj.callee !== 'undefined')
            {
                str = '[';
                var i, len = _obj.length;
                for (i = 0; i < len - 1; i++) {
                    str += serialize(_obj[i]) + ',';
                }
                str += serialize(_obj[i]) + ']';
            }
            else
            {
                str = '{';
                var key;
                for (key in _obj) {
                    str += key + ':' + serialize(_obj[key]) + ',';
                }
                str = str.replace(/\,$/, '') + '}';
            }
            return str;
            break;

        default:
            return 'UNKNOWN';
            break;
    }
}

function htmlspecialchars_decode(str, quote_style) {
    var c = {
        '&amp;': '&',
        '&lt;': '<',
        '&gt;': '>',
        '&quot;': '"',
        '&#039;': '\''
    };
    if (quote_style === 'ENT_QUOTES') {
        return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function (a) {
            return c[a];
        });
    }
    else if (quote_style === 'ENT_NOQUOTES') {
        return str.replace(/&amp;|&lt;|&gt;/g, function (a) {
            return c[a];
        });
    }
    else {
        return str.replace(/&amp;|&lt;|&gt;|&quot;/g, function (a) {
            return c[a];
        });
    }
}

function seek(Array, pin) {
    for (var key = 0; key < Array.length; key++) {
        if (Array[key] == pin)
            return key;
    }
    return -1;
}

function getNameIdx(pin, ids, names) {
    for (var m = 0; m < ids.length; m++) {
        if (pin == ids[m])
            return names[m];
    }
    return "";
}

function setFldVal(me, fld) {
    $('input[name="' + fld + '"]').val(me.value);
    var i = 1;
    while ($('input[name="' + fld + i + '"]').length) {
        $('input[name="' + fld + i + '"]').val(me.value);
        i++;
    }
}

function getIdxName(id, namelst) {
    for (var m = 0; m < namelst.length; m++) {
        if (id == namelst[m][0])
            return namelst[m][1];
    }
    return "";
}

function hideshow(div, typ, id) {
    div += id;
    if (typ == 0) {
        $('#show_' + div).show(800);
        $('#hide_' + div).hide(800);
        $('#bx_' + div).hide(800);
    } else {
        $('#show_' + div).hide(800);
        $('#hide_' + div).show(800);
        $('#bx_' + div).show(800);
    }
}

function sleep(delay) {
    var start = new Date().getTime();
    while (new Date().getTime() < start + delay)
        ;
}
/**
 * number: number to round
 * decimal: number of decimal places
 */
function roundNumber(number, decimals) { // Arguments: 
    var newnumber = new Number(number + '').toFixed(parseInt(decimals));
    return parseFloat(newnumber); // Output the result to the form field (change for your purposes)
}

function BitsSize(sz) {
    var b = 1024;
    if (sz < b)
        return new Array(sz, "B", 0);
    else if (sz >= b && sz < Math.pow(b, 2))
        return new Array(roundNumber(sz / b, 1), "KB", 1);
    else if (sz >= Math.pow(b, 2) && sz < Math.pow(b, 3))
        return new Array(roundNumber(sz / Math.pow(b, 2), 1), "MB", 2);
    else if (sz >= Math.pow(b, 3) && sz < Math.pow(b, 4))
        return new Array(roundNumber(sz / Math.pow(b, 3), 1), "GB", 3);
    else
        (sz >= Math.pow(b, 4) && sz < Math.pow(b, 5))
    return new Array(roundNumber(sz / Math.pow(b, 4), 1), "TB", 4);
}

function splitLen(Str, len, sep) {
    var idx = 0;
    var slen = Str.length;
    var ftr = '';
    while (idx < slen) {
        ftr += sep + Str.substr(idx, len);
        idx += len;
    }
    return ftr.substr(1);
}

function setlistsel(lst, obj) {
    var strval = "";
    for (var i = 0; i < lst.length; i++) {
        if (lst.options[i].selected == true && lst.options[i].value != '') {
            strval += '|' + lst.options[i].value;
        }
    }
    obj.value = strval;
}

function printSys(url) {
    var win = $('#printwin');
    if (win.length == 0) {
        win = $('<iframe />').appendTo('body');
        win.attr('id', 'printwin');
        win.hide();
    }
    win.attr('src', url.substr(1));
}

