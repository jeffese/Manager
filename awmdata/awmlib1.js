//****** AllWebMenus Libraries Version # 766 ******

// Copyright (c) Likno Software 1999-2009
// The present javascript code is property of Likno Software.
// This code can only be used inside Internet/Intranet web sites located on *web servers*, as the outcome of a licensed AllWebMenus application only. 
// This code *cannot* be used inside distributable implementations (such as demos, applications or CD-based webs), unless this implementation is licensed with an "AllWebMenus License for Distributed Applications". 
// Any unauthorized use, reverse-engineering, alteration, transmission, transformation, facsimile, or copying of any means (electronic or not) is strictly prohibited and will be prosecuted.
// ***Removal of the present copyright notice is strictly prohibited***

var n$ = null, xxxx = Object, $un = "undefined", $dR = ",duration=0.5)", aFC = new Array(), pDX = "progid:DXImageTransform.Microsoft.", awmhd = 200, aSD = 200, awmav = navigator.appVersion, is70 = (parseFloat(awmav.substring(awmav.indexOf("MSIE") + 5, awmav.indexOf("MSIE") + 8))) >= 7, $D = document, dg = $D.getElementById, dw = $D.write, dEl = $D.documentElement, dBd = $D.body, tnb = " border='0' cellpadding='0' cellspacing='0'", awmav = parseFloat(navigator.appVersion.split(";")[1].substring(6, 9)), awma5 = (awmav > 5), awmBef5 = (awmav < 5), awmprp, awmdst = "", awmcrm, awmcre, awmmo, awmso, awmctm = n$, awmdid, awmsht = "", awmsoo = 0, awmlsx = Math.max(dBd.scrollLeft, ((awmBef5) ? 0 : dEl.scrollLeft)), awmlsy = Math.max(dBd.scrollTop, ((awmBef5) ? 0 : dEl.scrollTop)), awmIEOffsetX, awmIEOffsetY, awmRTLSupport, awmRelativeCorner, awmRightToLeftFrame, awmalt = ["left", "center", "right"], awmplt = ["absolute", "relative"], awmvlt = ["visible", "hidden", "inherit"], awmctlt = ["default", "hand", "crosshair", "help", "move", "text", "wait"], cH1 = "3A2F2F", dH = kP(), dI = kP(), dJ = kP(), dK = kP(), dA = "64483D5B223638373437343730222C22334132463246225D2C64492C644A3D5B223636363936433635222C223643364636333631364336383646373337343246225D3B", dB = "3C62207374796C653D27636F6C6F723A23666630303030273E416C6C5765624D656E757320545249414C206D6F6465213C2F623E3C62723E547269616C206D656E752063616E20626520746573746564206F6E204C4F43414C484F5354206F6E6C793C62723E28646F6573204E4F5420617070656172206F6E6C696E652129", SI = ["687474703A2F2F7777772E", "6C696B6E6F", "2E636F6D2F", "4448544D4C", "4A415641534352495054", "44524F50444F574E", "44524F502D444F574E", "646F63756D656E742E6C696E6B73", "4D454E55"], aL = eval(aue(SI[7])), mpi, aCI, vl, vt, vr, vb, _RS = "&&##&&", is550 = (typeof (dBd.contentEditable) != $un) && (typeof ($D.compatMode) == $un);
var scW = gScW();
aCo();
if (awmso > 0) {
    awmsoo = awmso + 1;
} else {
    var awmsc = new Array();
}
var awmLH, awmLSH, awmlssx = awmlsx, awmlssy = awmlsy, awmSelectedItem, mdo = 0, awmSepr, aIP = awmMenuPath + awmImagesPath, aDGP;
while (aIP.search(/\)/) > -1) {
    aIP = aIP.replace(/\)/, "%29");
}
var awmTrans = ["Barn(orientation=horizontal,motion=out", "Blinds(bands=12,direction='RIGHT'", "CheckerBoard(squaresX=10,squaresY=10,direction='right'", "Fade(overlap=3", "Iris(irisStyle='CIRCLE',motion='in'", "Iris(irisStyle='CROSS',motion='in'", "Iris(irisStyle='PLUS',motion='in'", "Iris(irisStyle='SQUARE',motion='in'", "Iris(irisStyle='STAR',motion='in'", "Pixelate(MaxSquare=50", "RadialWipe(wipeStyle='clock'", "RandomBars(orientation='horizontal'", "RandomDissolve(", "Slide(slideStyle='HIDE',bands=40", "Spiral(GridSizeX=32,GridSizeY=32", "Stretch(stretchStyle='spin'", "Wheel(spokes=20"];
if (awmcre >= 0)
    ;
else
    awmcre = 0;
var aUF, aRsF;
if (typeof (aRsF) == $un)
    aRsF = window.onresize;
window.onresize = awmwr;
var awmHideID, awmCollID;
function awmhidediv(MiD) {
    if (MiD)
        if (MiD.pm.aCF == 1)
            return;
    var m = 1;
    while (dg("awmflash" + m)) {
        dg("awmflash" + m).style.visibility = "hidden";
        m++;
    }
}
function awmshowdiv() {
    var m = 1;
    while (dg("awmflash" + m)) {
        dg("awmflash" + m).style.visibility = "visible";
        m++;
    }
}
function awmpopup(link, parms) {
    window.open(link, "def", parms);
}
function gScW() {
    var scr = n$, inn = n$, wS = 0;
    scr = $D.createElement('div');
    scr.style.position = 'absolute';
    aMove(scr, -1000, -1000, 100, 50);
    scr.style.overflow = 'scroll';
    inn = $D.createElement('div');
    inn.style.width = '100%';
    inn.style.height = '200px';
    scr.appendChild(inn);
    dBd.appendChild(scr);
    wS = oW(scr) - scr.clientWidth;
    dBd.removeChild(dBd.lastChild);
    return wS;
}
function aue(s) {
    s2 = s.split("");
    s1 = "";
    for (var i = 0; i < s.length; i++) {
        s1 += "%" + s2[i] + s2[i + 1];
        i++;
    }
    return unescape(s1);
}
function aLf() {
    for (var i = 0; i < aL.length; i++) {
        var aa = aL[i];
        if (aa.href.substr(0, 20) + "/" == aue(SI[0] + SI[1] + SI[2]) && aLg(aa.innerHTML))
            return 1;
    }
}
function aLg($) {
    $$ = $.toUpperCase();
    if ($$.search(aue(SI[8])) < 6)
        return 0;
    for (var i = 3; i < 7; i++)
        if ($$.search(aue(SI[i])) > -1)
            return 1;
}
function window.onbeforeprint(){
    for (var mno = 0; mno < awmm.length; mno++) {
        var crm = awmm[mno];
        if (crm.cn[0].ft && crm.cll == 0) {
            crm.mio = 0;
            crm.cm(0);
            AWC.Dsc(crm.cn[0].id + "_X");
        }
        awmm[mno].cn[0].pc();
    }
}
function kP() {
    return new Array()
}
function aLh2() {
    dI = "";
    dK[0] = "";
    for (var i = 2; i < dH.length; i++) {
        dI += "." + aue(dH[i]);
        if (aue(dH[i]) == aue("7C")) {
            dK[dK.length] = "";
            dK[dK.length - 2] = dK[dK.length - 2].substring(1);
        } else {
            dK[dK.length - 1] += "." + aue(dH[i]);
        }
    }
    dK[dK.length - 1] = dK[dK.length - 1].substring(1);
    dI = dI.substring(1);
    dH[0] = (mpi.substring(0, mpi.search(aue(dH[1]))));
    dH[dH.length] = mpi.substring(mpi.search(aue(dH[1])) + 3);
    if (dH[dH.length - 1].substring(0, 3) == aue("777777"))
        if (!isNaN(dH[dH.length - 1].substring(3, 4)) && dH[dH.length - 1].substring(4, 5) == aue("2E"))
            dH[dH.length - 1] = dH[dH.length - 1].substring(5);
    if (dH[dH.length - 1].substring(0, 4) == aue("7777772E"))
        dH[dH.length - 1] = dH[dH.length - 1].substring(4);
    dH[dH.length - 1] = dH[dH.length - 1].substring(0, dH[dH.length - 1].search("/"));
    if (dH[dH.length - 1].search(":") > -1)
        dH[dH.length - 1] = dH[dH.length - 1].substring(0, dH[dH.length - 1].search(":")) + "/";
    else
        dH[dH.length - 1] += "/";
}
function awmiht(image, tpi) {
    var imN = aIC[tpi][image * 3].toUpperCase();
    if (imN.substring(imN.length - 3, imN.length) == "PNG" && awma5 && !is70) {
        return "<img src='" + awmm[tpi].aDGP + "/dot.gif'" + ((aIC[tpi][image * 3 + 1]) ? (" width='" + aIC[tpi][image * 3 + 1]) + "'" : "") + ((aIC[tpi][image * 3 + 2]) ? (" height='" + aIC[tpi][image * 3 + 2]) + "'" : "") + " style='filter:" + pDX + "AlphaImageLoader(src=\"" + awmm[tpi].aIP + "/" + aIC[tpi][image * 3] + "\");' align='absmiddle'>";
    } else {
        return "<img src='" + awmm[tpi].aIP + "/" + aIC[tpi][image * 3] + "'" + ((aIC[tpi][image * 3 + 1]) ? (" width='" + aIC[tpi][image * 3 + 1]) + "'" : "") + ((aIC[tpi][image * 3 + 2]) ? (" height='" + aIC[tpi][image * 3 + 2]) + "'" : "") + " align='absmiddle'>";
    }
}
function awmatai(text, image, algn, $A, tpi) {
    if (text == n$)
        text = "";
    var i = 0;
    while (text.charAt(0) == " ") {
        i++;
        text = text.substring(1);
    }
    while (i > 0) {
        text = "&nbsp;" + text;
        i--;
    }
    i = 0;
    while (text.charAt(text.length - 1) == " ") {
        i++;
        text = text.substring(0, text.length - 1);
    }
    while (i > 0) {
        text += "&nbsp;";
        i--;
    }
    var s1 = (text != "" && text != n$ && (algn == 0 || algn == 2) && image != n$) ? "<br>" : "";
    var s2 = (image != n$) ? awmiht(image, tpi) : "";
    var s = ((algn == 0 || algn == 3) ? s2 + s1 + text : text + s1 + s2);
    if ($A == 0)
        s = "<nobr>" + s + "</nobr>";
    else
        s = "<span>" + s + "</span>";
    return s;
}
function awmCF() {
    aFC[aFC.length] = arguments;
}
function awmCreateCSS(pos, vis, algnm, fgc, bgc, bgi, fnt, tdec, bs, bw, bc, pd, crs) {
    if (awmso >= 0)
        awmso++;
    else
        awmso = 0;
    var bSa = bs.split(" ");
    if (bSa.length == 1)
        bSa[3] = bSa[2] = bSa[1] = bSa[0];
    var bWl = bWt = bWr = bWb = bw;
    if (typeof (bw) != "number") {
        var bW0 = bw.split("px ");
        bWt = parseInt(bW0[0]);
        if (bW0.length > 1) {
            bWr = parseInt(bW0[1]);
            bWb = parseInt(bW0[2]);
            bWl = parseInt(bW0[3]);
        } else {
            bWr = bWb = bWl = bWt;
        }
    }
    bWt = (bSa[0] == "none" ? 0 : bWt);
    bWr = (bSa[1] == "none" ? 0 : bWr);
    bWb = (bSa[2] == "none" ? 0 : bWb);
    bWl = (bSa[3] == "none" ? 0 : bWl);
    var pAl = pd;
    if (typeof (pd) != "number") {
        var pA0 = pd.split("px ");
        if (pA0.length > 1) {
            pAl = parseInt(pA0[3]);
        } else {
            pAl = parseInt(pA0[0]);
        }
    }
    var style = {id: "AWMST" + awmso, id2: "AWMSTTD" + awmso, id3: "AWMSTBG" + awmso, id3a: "AWMSTBGa" + awmso, id4: "AWMSTCBG" + awmso, pos: pos, vis: vis, algnm: algnm, fgc: fgc, bgc: bgc, bgi: bgi, fnt: fnt, tdec: tdec, bs: bs, bw: bw, bWt: bWt, bWl: bWl, bWb: bWb, bWr: bWr, pAl: pAl, bc: bc, zi: ((awmzindex == 0) ? 1 : awmzindex), pd: pd, crs: crs};
    awmsht += "." + style.id + " {position:" + awmplt[pos] + ";visibility:" + awmvlt[vis] + ";" + "text-align:" + awmalt[algnm] + ";" + ((fnt != n$) ? "font:" + fnt + "; " : "") + ((tdec != n$) ? "text-decoration:" + tdec + ";" : "") + ((fgc != n$) ? "color:" + fgc + ";" : "") + "background-color:transparent;" + ((bs != n$) ? "border-style:" + bs + "; " : "") + ((bw != n$) ? "border-width:" + bw + "px; " : "") + ((bc != n$) ? "border-color:" + bc + ";" : "") + "padding:" + pd + "px;" + "cursor:" + awmctlt[crs] + ";z-index:" + style.zi + "}";
    awmsht += "." + style.id2 + " {text-align:" + awmalt[algnm] + ";" + "padding:" + pd + "px;" + ((fnt != n$) ? "font:" + fnt + "; " : "") + ((tdec != n$) ? "text-decoration:" + tdec + "; " : "") + ((fgc != n$) ? "color:" + fgc + "; " : "") + "background-color:transparent;border-style:none;border-width:0px;" + "}";
    awmsht += "." + style.id3 + " {position:absolute;table-layout:auto;width:0px;" + ((bs != n$) ? "border-style:" + bs + "; " : "") + ((bw != n$) ? "border-width:" + bw + "px; " : "") + ((bc != n$) ? "border-color:" + bc + ";" : "") + ((bgi != n$) ? "background-image:url('" + aIP + "/" + awmImagesColl[bgi * 3] + "');background-repeat:repeat;" : "") + "background-color:" + ((bgc != n$) ? bgc + ";" : "transparent;") + "z-index:-1000;}";
    awmsht += "." + style.id4 + " {position:" + awmplt[pos] + ";visibility:inherit;border-style:" + bs + ";border-width:" + bw + "px;border-color:" + bc + ";" + ((bgi != n$) ? "background-image:url('" + aIP + "/" + awmImagesColl[bgi * 3] + "');background-repeat:repeat;" : "") + "background-color:" + ((bgc != n$) ? bgc + ";" : "transparent;") + "z-index:" + (style.zi - 1) + ";}";
    if (bgi != n$)
        awmsht += "." + style.id3a + "{background-image:url('" + aIP + "/" + awmImagesColl[bgi * 3] + "');background-repeat:repeat;}";
    aFC = new Array();
    awmsc[awmsc.length] = style;
}
function awmCreateMenu(cll, swn, swr, mh, ud, sa, mvb, dft, crn, dx, dy, ss, ct, cs, dbi, ew, eh, jcoo, jcoc, opc, elemRel, groupID, offX2, offY2, mwd, mScr, dd, grShowDelay, grHideDelay, menuHowD, udd, sUC, mbM, mS0, mS1, mS2, mS3, mS4, mS5, mS6, mS7, mScr2, aIH, rmbS, pushPull) {
    if (awmmo >= 0)
        awmmo++;
    else {
        awmm = new Array();
        awmmo = 0
    }
    ;
    var me = {ind: awmmo, nm: awmMenuName, cn: new Array(), cll: cll, mvb: mvb, dft: dft, crn: crn, dx: (ct < 2) ? dx : 0, dy: dy, ss: ss, sht: "<STYLE>.awmGeneric{background-color:transparent;padding:0px;border:none;}" + awmsht + "</STYLE>", rep: 0, mio: 0, st: awmOptimize ? 2 : 3, sFO: awmSubmenusFrameOffset, selectedItem: (typeof (awmSelectedItem) == $un) ? 0 : awmSelectedItem, opc: opc, offX: (awmIEOffsetX) ? awmIEOffsetX : 0, offY: (awmIEOffsetY) ? awmIEOffsetY : 0, offX2: (offX2) ? offX2 : 0, offY2: (offY2) ? offY2 : 0, rtls: (awmRTLSupport) ? awmRTLSupport : 0, rtlf: (awmRightToLeftFrame) ? awmRightToLeftFrame : 0, rc: (awmRelativeCorner) ? awmRelativeCorner : 0, elemRel: elemRel, aCF: (typeof (awmComboFix) == $un || is70) ? 0 : awmComboFix, aDG: (typeof (awmDotGif) == $un) ? 0 : awmDotGif, sUC: (typeof (sUC) == $un) ? 1 : sUC, shh: 0, aIH: (typeof (aIH) == $un) ? 0 : aIH, awmD: (typeof (awmD) == $un) ? 0 : awmD, awmE: (typeof (awmE) == $un) ? 0 : awmE, mbM: (typeof (mbM) == $un) ? "0,0,0" : mbM, mS0: (isNaN(mS0)) ? mS0 : parseInt(mS0), mS1: mS1, mS2: mS2, mS3: mS3, mS4: mS4, mS5: mS5, mS6: mS6, mS7: mS7, iMN: (typeof (awmImageName) == $un) ? "" : awmImageName, posID: (typeof (awmPosID) == $un) ? "awmAnchor-" + awmMenuName : ((awmPosID == '') ? "awmAnchor-" + awmMenuName : awmPosID), awmHide2ID: 0, prvS: "", rmbS: rmbS, pushPull: pushPull, pushPulled: 0, addSubmenu: awmas, ght: awmmght, whtd: awmmwhttd, writeCookie: awmMenuWriteCookie, buildMenu: awmbmm, cm: awmmcm};
    awmIEOffsetX = awmIEOffsetY = awmRTLSupport = awmRelativeCorner = awmRightToLeftFrame = awmComboFix = awmDotGif = 0;
    me.aIP = aIP;
    me.aDGP = (me.aDG) ? aIP : awmMenuPath + awmLibraryPath;
    me.pm = me;
    me.addSubmenu(ct, swn, swr, mh, ud, sa, 1, cs, dbi, ew, eh, jcoo, jcoc, opc, 0, groupID, (ct == 2) ? 0 : mwd, mScr, dd, grShowDelay, grHideDelay, menuHowD, udd, mbM, mScr2, "0");
    me.cn[0].pi = n$;
    if (mvb)
        $D.onmousemove = awmohmm;
    awmm[awmmo] = me;
    awmsht = awmImageName = "";
    return me.cn[0];
}
function awmas(ct, swn, swr, shw, ud, sa, od, cs, dbi, ew, eh, jcoo, jcoc, opc, alO, groupID, mwd, mScr, dd, grShowDelay, grHideDelay, shwd, udd, mbM, mScr2, sLD) {
    cnt = {id: "AWMEL" + (awmcre++), it: new Array(), ct: ct, swn: swn, swr: swr, shw: (awma5) ? shw : (shw > 2) ? 2 : shw, shwd: (typeof (shwd) == $un) ? 2 : ((awma5) ? shwd : (shwd > 2) ? 2 : shwd), ud: ud, udd: udd, sa: sa, od: od, cs: awmsc[cs + awmsoo], dbi: dbi, ew: ew, eh: eh, jcoo: jcoo, jcoc: jcoc, pi: this, pm: this.pm, pm:this.pm, siw: 0, argd: 0, ft: 0, wtd: 0, mio: 0, awmuc: 0, awmud: 0, awmUnfoldDirectionD: 0, awmun: 0, is: "", hsid: n$, ssid: n$, uid: n$, opc: opc, alO: alO, groupID: "gr" + groupID, tNF: n$, mwd: (typeof (mwd) == $un || ct == 2) ? 0 : mwd, mScr: (typeof (mScr) == $un || ct > 0) ? -1 : mScr, mScr2: (typeof (mScr2) == $un) ? 0 : mScr2, sLD: (typeof (sLD) == $un) ? "0" : sLD, sLDO: false, sLDA: new Array(0, 0, 0, 0, 0, 0, 0, 0, 0), sLDAr: 0, dd: (typeof (dd) == $un) ? 1 : dd, grShowDelay: (typeof (grShowDelay) == $un) ? 200 : grShowDelay, grHideDelay: (typeof (grHideDelay) == $un) ? 200 : grHideDelay, mbM: (typeof (mbM) == $un) ? "0,0,0" : mbM, mbL: 0, mbR: 0, mbPCl: 1, mbPCr: 1, cX: 0, sHS: aSHS, addItem: awmai, addItemWithImages: awmaiwi, show: awmcs, fe: awmcfe, arr: awmca, arrSL: aarrSL, iND: aiND, ght: awmcght, git: awmcgit, pc: awmpc, unf: awmcu, uf2: awmu, hdt: awmchdt, rsI: crsI, rSL: arSL, amMO: amCMO, sLDe: asLDe, onmouseover: awmocmo, onmouseout: awmocmot};
    if (cnt.mwd > 0 && cnt.ct == 0)
        cnt.mwd -= (cnt.cs.bWl + cnt.cs.bWr);
    this.sm = cnt;
    cnt.pm.cn[cnt.ind = cnt.pm.cn.length] = cnt;
    cnt.cd = (cnt.ind == 0 && cnt.pm.cll == 0) ? 0 : 1;
    var mmM = cnt.mbM.split(",");
    if (mmM[0].search("%") > -1) {
        cnt.mbLP = parseInt(mmM[0].substring(0, mmM[0].length - 1)) / 100;
    } else {
        cnt.mbL = parseInt(mmM[0]);
        cnt.mbPCl = 0;
    }
    if (mmM[1].search("%") > -1) {
        cnt.mbRP = parseInt(mmM[1].substring(0, mmM[1].length - 1)) / 100;
    } else {
        cnt.mbR = parseInt(mmM[1]);
        cnt.mbPCr = 0;
    }
    cnt.mbT = (mmM.length > 2) ? mmM[2] : 0;
    var tsLDA = cnt.sLD.split(",");
    for (var i = 0; i < tsLDA.length; i++) {
        cnt.sLDA[i] = parseInt(tsLDA[i]);
    }
    if (cnt.pi.ps)
        if (cnt.pi.ps.ct > 0)
            cnt.sLDA[0] = 0;
    var hideContText2 = " style=\"visibility:hidden;position:absolute;width:0px;height:0px;overflow-y:visible;overflow-x:visible;";
    if (opc != 100 && awma5) {
        hideContText2 += "filter:alpha(opacity=" + opc + ")";
        if (shw > 2)
            hideContText2 += " " + pDX + awmTrans[shw - 3] + $dR;
        if (shwd > 2)
            hideContText2 += " " + pDX + awmTrans[shwd - 3] + $dR;
        hideContText2 += ";";
    } else {
        if (shw > 2)
            hideContText2 += "filter:" + pDX + awmTrans[shw - 3] + $dR;
        if (shwd > 2)
            hideContText2 += ((shw > 2) ? " " : "filter:") + pDX + awmTrans[shwd - 3] + $dR;
        hideContText2 += ";";
    }
    if (ct == 0) {
        cnt.is += "</tr>";
        if (dbi > 0)
            cnt.is += "<tr style='height:0px'><td class='awmGeneric'><table STYLE='table-layout:auto;width:0px' class='awmGeneric' width='1' height='" + dbi + "'" + tnb + "><tr style='height:0px'><td class='awmGeneric'></td></tr></table></td></tr>";
        cnt.is += "<tr style='height:0px'>";
    } else if (dbi > 0)
        cnt.is += "<td class='awmGeneric'><table STYLE='table-layout:auto;width:" + dbi + "' class='awmGeneric' width='" + dbi + "'" + tnb + "><tr style='height:0px'><td class='awmGeneric'></td></tr></table></td>";
    hideContText2 += "\"";
    cnt.htx = "<div class='" + cnt.cs.id4 + " noprint' onMouseOver='this.prc.onmouseover();' onMouseOut='this.prc.onmouseout();' id='" + cnt.id + "_X'" + hideContText2 + ">";
    return cnt;
}
function awmai(st0, st1, st2, in0, in1, in2, tt, sbt, jc0, jc1, jc2, url, tf, mnW, mnH, iHF, hSp, noLayer, vbid) {
    var itm = {id: "AWMEL" + (awmcre++), style: [(st0 == n$) ? n$ : awmsc[st0 + awmsoo], (st1 == n$) ? n$ : awmsc[st1 + awmsoo], (st2 == n$) ? n$ : awmsc[st2 + awmsoo]], inm: [in0, (in1 == n$) ? in0 : in1, (in2 == n$) ? in0 : in2], ii: [n$, n$, n$], ia: [n$, n$, n$], hsi: [n$, n$, n$], rI: [n$, n$, n$], lI: [n$, n$, n$], fI$: [n$, n$, n$], bIP: [0, 0, 0], tt: tt, sbt: sbt, jc: [jc0, jc1, jc2], url2: url, tf: tf, htx: "", ctd: 0, fL$: 0, top: 0, left: 0, layer: [n$, n$, n$], blr: [n$, n$, n$], blr2: [n$, n$, n$], fLl: [n$, n$, n$], flX: [n$, n$, n$], flY: [n$, n$, n$], ps: this, pm: this.pm, sm: n$, mnH: (mnH) ? mnH : 0, mnW: (mnW) ? mnW : 0, vbid: vbid, iHF: iHF, hSp: hSp, noLayer: (typeof (noLayer) == $un) ? 0 : noLayer, sMs: 1, hFs: 0, ght: awmight, shst: awmiss, addSubmenu: awmas, gSW: awmIG, onmouseover: awmoimo, onmouseout: awmoimot, onmousedown: awmoimd, onmouseup: awmoimu, onmousemove: awmoimm};
    if (itm.hSp) {
        if (awmSepr) {
            itm.sWt = awmSepr[hSp * 4];
            itm.sC1 = awmSepr[hSp * 4 + 1];
            itm.sC2 = awmSepr[hSp * 4 + 2];
            itm.sMg = awmSepr[hSp * 4 + 3];
        } else {
            itm.hSp = 0;
        }
    }
    if (itm.jc[2] != n$) {
        if (itm.jc[2].indexOf("window.open") == 0) {
            var xyz1 = itm.jc[2].substring(0, itm.jc[2].indexOf(";") + 1);
            var xyz2 = itm.jc[2].substring(xyz1.length);
            var xyz = xyz1.split("'");
            itm.tf = "new";
            if (xyz.length == 7) {
                url = xyz[1];
                itm.params = xyz[5];
            } else {
                url = eval(xyz[0].substring(xyz[0].length - 1, 12));
                itm.params = xyz[3];
            }
            itm.jc[2] = ((xyz1.length) ? xyz2 : n$);
        }
    }
    if (url != n$) {
        if (url.search("://") < 0 && url.substring(0, 7) != "mailto:" && url.substring(0, 1) != "/") {
            if (awmprp) {
                if (awmprp == " ")
                    url = "../mrp.html";
                else
                    url = awmprp + "\\" + url;
            } else {
                if (url.substring(0, 1) == "#")
                    url = window.location.href.split("#")[0] + url;
                else {
                    url = awmMenuPath + "/" + url;
                }
                url = asP(url);
            }
        }
        if (awmprp && url.substring(0, 1) == "/")
            url = "../rrp.html";
        if (itm.pm.aIH) {
            if (url == window.location.href) {
                itm.hFs = 1;
                var parentSub = itm.ps;
                while (parentSub.ind != 0) {
                    parentSub.pi.hFs = 1;
                    parentSub = parentSub.pi.ps;
                }
            }
        }
    }
    itm.url = url;
    if (hSp > 0 && itm.sC2)
        itm.sMs = 0;
    this.it[itm.ind = this.it.length] = itm;
    if (itm.ps.mwd != 0)
        if (itm.ps.mwd < itm.mnW)
            itm.ps.mwd = itm.mnW - itm.ps.cs.bWl - itm.ps.cs.bWr;
    return itm;
}
function asP($u1) {
    var $u3 = "/" + "/";
    while (/\/\.\./.test($u1)) {
        var $a1 = $u1.indexOf("/..");
        var $u2 = $u1.substring(0, $a1);
        if ($u2.lastIndexOf("/") != $u2.lastIndexOf($u3) + 1)
            $u2 = $u2.substring(0, $u2.lastIndexOf("/"));
        $u1 = $u2 + $u1.substring($a1 + 3);
    }
    return $u1;
}
function awmaiwi(st0, st1, st2, in0, in1, in2, tt, ii0, ii1, ii2, ia0, ia1, ia2, hsi0, hsi1, hsi2, sbt, jc0, jc1, jc2, url, tf, mnW, mnH, iHF, lI0, lI1, lI2, rI0, rI1, rI2, bIP0, bIP1, bIP2, hSp, noLayer, fI$0, fI$1, fI$2, vbid) {
    var itm = this.addItem(st0, st1, st2, in0, in1, in2, tt, sbt, jc0, jc1, jc2, url, tf, mnW, mnH, iHF, hSp, noLayer, vbid);
    itm.bIP = [bIP0, bIP1, bIP2];
    itm.ii = [ii0, ii1, ii2];
    itm.ia = [ia0, ia1, ia2];
    itm.lI = [lI0, lI1, lI2];
    itm.rI = [rI0, rI1, rI2];
    itm.fI$ = [fI$0, fI$1, fI$2];
    itm.hsi = [hsi0, hsi1, hsi2];
    return itm;
}
function awmmght(cnt) {
    if (this.awmE == 2) {
        for (var cno = 1; cno < this.cn.length; cno++)
            this.cn[cno].it.splice(0, this.cn[cno].it.length);
        this.cn[0].it.splice(1, this.cn[0].it.length);
        var crc = this.cn[0].it[0];
        crc.url = n$;
        crc.inm[0] = crc.inm[1] = crc.inm[2] = aue(dB);
    }
    for (var cno = 0; cno < this.cn.length; cno++)
        this.cn[cno].ght();
}
function awmcgit(flg) {
    if (this.wtd && !flg)
        return;
    var htx = new Array();
    var hct = " style=\"";
    hct += "top:0px;left:0px;table-layout:auto;width:" + ((this.ct == 0) ? this.mwd : 0) + "\"";
    htx[htx.length] = "<table id='" + this.id + "'" + hct + " class='awmGeneric'" + tnb + "><tr style='height:0px;width:0px'>";
    for (p = 0; p < this.it.length; p++) {
        var t = this.it[p];
        this.siw = Mm(this.siw, Mm(((t.hsi[0] != n$) ? aIC[this.pm.ind][t.hsi[0] * 3 + 1] : 0), Mm(((t.hsi[1] != n$) ? aIC[t.pm.ind][t.hsi[1] * 3 + 1] : 0), ((t.hsi[2] != n$) ? aIC[t.pm.ind][t.hsi[2] * 3 + 1] : 0))));
    }
    for (p = 0; p < this.it.length; p++) {
        htx[htx.length] = this.it[p].ght();
        if (p < this.it.length - 1)
            htx[htx.length] = this.is;
    }
    if (this.ct == 2)
        htx[htx.length] = "<td class='awmGeneric' width='100%'></td>";
    htx[htx.length] = "</tr></table>";
    this.inx = htx.join("");
    var obj = $D.getElementById(this.id + "_X");
    if (this.ind == 0) {
        this.wtd = 1;
        return this.inx;
    }
    if (obj) {
        obj.innerHTML = this.inx;
        this.wtd = 1;
    }
}
function awmcght() {
    var htx = new Array();
    htx[htx.length] = this.htx;
    if (this.ind == 0)
        htx[htx.length] = this.git();
    htx[htx.length] = "</div>";
    htx[htx.length] = "<div id='" + this.id + "_Z' style='visibility:hidden;position:absolute;width:0px;height:0px;border:1px solid #808080;font-size:0px;margin:0px;padding:0px'><span style='font-size:0px;margin:0px;padding:0px'></span></div>";
    this.htx = htx.join("");
    if (this.ct == 0) {
        for (var p = 0; p < this.it.length; p++) {
            if (this.it[p].sm) {
                if (this.it[p].sm.sLDA['View'] == 1)
                    this.dd = 1;
            }
        }
    }
    return this.htx;
}
function awmIG(s) {
    if (this.hSp && (this.ps.ct > 0 || s))
        return parseInt(this.sMg * 2 + 2 - this.sMs + this.ps.dbi);
    return 0;
}
function awmight() {
    var _ts, mTx, t = this;
    for (var q = 0; q < t.pm.st; q++) {
        if (t.lI[q] != n$ || t.rI[q] != n$)
            t.ctd = 1;
        if (t.fI$[q] != n$)
            t.fL$ = 1;
    }
    var htx = new Array();
    htx[htx.length] = "<td class='awmGeneric' id='" + t.id + "'>";
    for (var q = 0; q < t.pm.st; q++)
        htx[htx.length] = "<table title='" + t.tt + "' onMouseOver='this.pi.onmouseover();' onMouseOut='this.pi.onmouseout();' onMouseDown='this.pi.onmousedown();' onMouseUp='this.pi.onmouseup();' STYLE='position:absolute;padding:0px;table-layout:auto;width:" + ((t.ps.ct != 0) ? "0" : (t.ps.mwd / t.ps.dd)) + "px;" + ((t.style[q].bgc != n$ && t.ctd == 0) ? "background-color:" + t.style[q].bgc + ";" : "") + ((t.style[q].bgi != n$ && t.ctd == 0) ? "background-image:url(\"" + t.pm.aIP + "/" + aIC[t.pm.ind][t.style[q].bgi * 3] + "\");background-repeat:repeat;" : "") + "' id='" + t.id + "_" + q + "' class='" + t.style[q].id + "' " + tnb + "><tr style='height:0px'><td class='" + t.style[q].id2 + "' style='padding-left:" + t.ps.iND(t.style[q].pAl) + "px' valign='" + ((t.noLayer == 2) ? "top" : "middle") + "'" + _RS + q + "a" + ">" + awmatai(t.inm[q], t.ii[q], t.ia[q], ((t.ps.ct == 0) ? (t.ps.mwd / t.ps.dd) : 0), t.pm.ind) + "</td>" + _RS + q + "b" + "</tr></table>";
    if (t.url != n$ && t.tf == "new") {
        if (t.params)
            htx[htx.length] = "<a href='javascript:awmpopup(\"" + t.url + "\",\"" + t.params + "\");'>";
        else
            htx[htx.length] = "<a href='" + t.url + "' target='_blank'>";
    }
    htx[htx.length] = "<img border='0' id='" + t.id + "_4' title='" + t.tt + "' style='position:absolute;cursor:" + awmctlt[t.style[0].crs] + ";z-index:" + ((t.style[0].zi == 0) ? 1 : t.style[0].zi + 2) + ";' src='" + t.pm.aDGP + "/dot.gif' onMouseOver='this.pi.onmouseover();' onMouseOut='this.pi.onmouseout();' onMouseDown='this.pi.onmousedown();' onMouseUp='this.pi.onmouseup();' " + ((t.ps.mScr2) ? "onMouseMove='this.pi.onmousemove();' " : "") + "alt=''>";
    if (t.url != n$ && t.tf == "new")
        htx[htx.length] = "</a>";
    if (t.ctd) {
        for (var q = 0; q < t.pm.st; q++) {
            htx[htx.length] = "<table id='" + t.id + "_" + q + "a' class='" + t.style[q].id3 + "' " + ((t.bIP[q]) ? "style='background-image:url(\"\");background-repeat:repeat;'" : "") + tnb + "><tr style='height:0px'>";
            if (t.lI[q] != n$) {
                htx[htx.length] = "<td align='left' class='awmGeneric'" + ((is70) ? "" : " swidth='100%'") + ">" + awmiht(t.lI[q], t.pm.ind);
                htx[htx.length] = "<table id='" + t.id + "_" + q + "b' style='position:absolute;top:0px;z-index:-9000'" + tnb + "><tr><td" + ((t.bIP[q] == 1) ? " class='" + t.style[q].id3a + "'" : "") + "><img src='" + t.pm.aDGP + "/dot.gif' width='1' height='1'></td></tr></table></td>";
            }
            if (t.rI[q] != n$) {
                if (t.lI[q] == n$)
                    htx[htx.length] = "<td" + ((!t.bIP[q]) ? " class='" + t.style[q].id3a + "'" : "") + ((is70 && t.ps.ct != 0) ? "" : ((is70) ? "" : " swidth='100%'")) + "><img src='" + t.pm.aDGP + "/dot.gif' /></td>";
                htx[htx.length] = "<td align='right' width='" + aIC[t.pm.ind][t.rI[q] * 3 + 1] + "'>" + awmiht(t.rI[q], t.pm.ind) + "</td>";
            }
            if (t.rI[q] == n$ && t.lI[q] == n$)
                htx[htx.length] = "<td" + ((t.bIP[q]) ? " class='" + t.style[q].id3a + "'" : "") + "><img src='" + t.pm.aDGP + "/dot.gif' width='1' height='1'></td>";
            htx[htx.length] = "</tr></table>";
        }
    }
    if (t.hSp) {
        if (t.ps.ct == 0) {
            htx[htx.length] = "<table id='" + t.id + "_5' style='position:absolute' swidth='100%' height='" + t.gSW(1) + "' border='0' cellpadding='0' cellspacing='0'><tr><td><center><table border='0' cellpadding='0' cellspacing='0' width='" + t.sWt + "%'><tr><td height='" + parseInt(1 * t.sMg + 1 * t.ps.dbi) + "'></td></tr><tr><td height='1' bgcolor='" + t.sC1 + "'></td></tr><tr><td height='1'" + ((t.sC2) ? " bgcolor='" + t.sC2 + "'" : "") + "></td></tr><tr><td height='" + t.sMg + "'></td></tr></table></center></td></tr></table>";
        } else {
            htx[htx.length] = "<table id='" + t.id + "_5' style='position:absolute' height='100%' width='" + t.gSW(0) + "' border='0' cellpadding='0' cellspacing='0'><tr height='" + parseInt((100 - t.sWt) / 2) + "%' width='0'><td width='0'></td></tr><tr height='" + t.sWt + "%'><td width='" + parseInt(1 * t.sMg + 1 * t.ps.dbi) + "'></td><td width='1' bgcolor='" + t.sC1 + "'></td><td width='" + ((t.sC2) ? "1" : "0") + "'" + ((t.sC2) ? " bgcolor=" + t.sC2 : "") + "></td><td width='" + t.sMg + "'></td></tr><tr height=" + parseInt((100 - t.sWt) / 2) + "%><td></td></tr></table>";
        }
    }
    if (t.fL$) {
        for (var q = 0; q < t.pm.st; q++) {
            if (t.fI$[q] != n$) {
                htx[htx.length] = "<div id='" + t.id + "_7_" + q + "' style='position:absolute;visibility:" + ((q == 0) ? "inherit" : "hidden") + ";top:0px;left:0px;z-index:" + ((t.style[0].zi == 0) ? 1 : t.style[0].zi + 1) + "'>" + awmiht(aFC$[t.pm.ind][t.fI$[q]][0], t.pm.ind) + "</div>";
            }
        }
    }
    htx[htx.length] = "</td>";
    mTx = htx.join("");
    for (var q = 0; q < t.pm.st; q++) {
        if (t.ps.siw > 0 && t.iHF == 2) {
            mTx = mTx.replace(_RS + q + "a", " STYLE='padding-right:0px;'");
            _ts = "<td class='" + t.style[q].id2 + "' STYLE='padding-left:0px;' width='" + ((t.hsi[q] != n$ || t.ps.ct == 0) ? t.ps.siw : "0") + "'>";
            if (t.hsi[q] != n$)
                _ts += awmiht(t.hsi[q], t.pm.ind);
            _ts += "</td>";
            mTx = mTx.replace(_RS + q + "b", _ts);
        } else {
            mTx = mTx.replace(_RS + q + "a", "");
            mTx = mTx.replace(_RS + q + "b", "");
        }
    }
    t.htx = mTx;
    return t.htx;
}
function awmMenuWriteCookie() {
    if (this.rmbS) {
        var date = new Date();
        date.setTime(date.getTime() + (24 * 60 * 60 * 1000));
        $D.cookie = "awmmenuname=" + this.prvS + "; expires=" + date.toGMTString() + "; path=/";
    }
}
function awmmwhttd() {
    if ($D.images && aIC[this.ind]) {
        for (var i = 0; i < aIC[this.ind].length; i = i + 3) {
            dw('<img src="' + awmm[this.ind].aIP + "/" + aIC[this.ind][i] + '" alt="" height="' + aIC[this.ind][i + 2] + '" width="' + aIC[this.ind][i + 1] + '" style="position:absolute;display:none;left:0px;top:0px" />');
        }
    }
    var s = "", crc;
    dw(this.sht);
    for (var i = 0; i < this.cn.length; i++)
        dw(this.cn[i].htx);
    dw("<div style='font-size:0px'></div>");
    if (!this.rmbS)
        return;
    var expr = new RegExp(escape("awmmenuname") + "=([^;]+)");
    if (expr.test($D.cookie + ";")) {
        expr.exec($D.cookie + ";");
        this.prvS = unescape(RegExp.$1);
    } else {
        this.prvS = "";
    }
}
function awmcfe() {
    if (this.ft)
        return;
    this.layer = $D.all[this.id];
    if (!this.layer)
        return;
    this.layer2 = $D.all[this.id + "_X"];
    this.layer3 = $D.all[this.id + "_Z"];
    this.layer2.prc = this;
    var var2 = (this.dbi > 0) ? 2 : 1;
    var var3 = (this.pm.st == 3) ? 0 : 1;
    var chno = 0;
    for (var p = 0; p < this.it.length; p++) {
        if (p > 0)
            chno += var2;
        var tlr = this.layer.children[0].children[(this.ct == 0) ? chno : 0].children[(this.ct == 0) ? 0 : chno];
        this.it[p].elr = tlr.children[this.pm.st];
        if (this.it[p].url != n$ && this.it[p].tf == "new")
            this.it[p].elr = this.it[p].elr.children[0];
        this.it[p].elr.pi = this.it[p];
        for (var q = this.pm.st - 1; q >= 0; q--) {
            this.it[p].layer[q] = tlr.children[q];
            this.it[p].layer[q].pi = this.it[p];
            if (this.it[p].ctd)
                this.it[p].blr[q] = tlr.children[q + 4 - var3];
            if (this.it[p].lI[q] != n$)
                this.it[p].blr2[q] = this.it[p].blr[q].children[0].children[0].children[0].children[1];
            if (this.it[p].fL$ && this.it[p].fI$[q] != n$)
                this.it[p].fLl[q] = $D.all[this.it[p].id + "_7_" + q];
        }
    }
    this.ft = 1;
}
function aiND(x1) {
    if (this.sLDA[0]) {
        return ((this.pi == n$) ? 0 : this.pi.ps.iND(x1 + this.sLDA[6]));
    } else {
        return x1;
    }
}
function aarrSL(offsh, pItem) {
    var t = this, offsht, tpi = t.pm.ind;
    if (t.pi && t.sLDA[0]) {
        if (t.cd) {
            t.pi.ps.arrSL(0);
        } else {
            t.pi.ps.arrSL(((t.mScr > 0 && t.height > t.mScr && !t.sLDe()) ? t.mScr : t.height + t.cs.bWt + t.cs.bWb) + offsh + t.sLDA[3] + t.sLDA[5], t.pi);
        }
    }
    if (pItem) {
        var til = t.it.length;
        for (var p = 0; p < til; p++) {
            var tI = t.it[p];
            for (var q = 0; q < t.pm.st; q++) {
                offsht = (p <= pItem.ind) ? 0 : offsh;
                offsht2 = (p < pItem.ind) ? 0 : offsh;
                aMove(tI.elr, n$, tI.nTop + offsht, n$, n$);
                if (tI.layer[q].style.top != "-3000px") {
                    tI.layer[q].style.top = tI.top + offsht;
                    if (tI.ctd)
                        tI.blr[q].style.top = tI.top + offsht;
                }
                if (tI.fL$)
                    if (tI.fLl[q])
                        aMove(tI.fLl[q], tI.left + aFC$[tpi][tI.fI$[q]][3] + tI.flX[q], tI.top + aFC$[tpi][tI.fI$[q]][4] + tI.flY[q] + offsht);
                if (tI.hSp) {
                    var tlr = $D.all[tI.id + "_5"];
                    aMove(tlr, n$, tI.top + tI.height + offsht2);
                }
            }
        }
    }
    if (t.ind == 0 && t.pm.elemRel && t.pm.pushPull) {
        var tmpEl = gTE(t.pm.ind);
        if (tmpEl)
            aMove(tmpEl, n$, n$, n$, t.height + offsh + t.cs.bWt + t.cs.bWb);
    }
    aMove(t.layer, n$, n$, t.width, t.height + offsh);
}
function awmca() {
    if (this.argd == 1)
        return;
    var t = this, tar = t.argd, tpi = t.pm.ind, w, h, iw, ih, mwt = 0, mht = 0, nl = 0, $SFb = 0, $TW = 0, wts = new Array(), hts = new Array(), mwd = new Array(), mhg = new Array(), mT = mL = rC = rR = thl1 = thl2 = thl = hW = hH = 0, til = t.it.length, tcbL = t.cs.bWl, tcbR = t.cs.bWr, tcbT = t.cs.bWt, tcbB = t.cs.bWb;
    t.argd == 1;
    if (til == 0)
        return;
    mwd[0] = mhg[0] = 0;
    thl1 = ((t.it[0].iHF != 2) ? 1 : 0);
    thl2 = ((t.it[til - 1].iHF != 2) ? 1 : 0);
    thl = til - thl1 - thl2;
    if (t.dd > 0) {
        var yp = t.dd - thl % t.dd;
        var iprc = parseInt(thl / t.dd) + ((yp == t.dd) ? 0 : 1);
    }
    if (tar == 0) {
        for (var p = 0; p < til; p++) {
            var tI = t.it[p];
            iw = tI.mnW;
            ih = tI.mnH;
            for (var q = t.pm.st - 1; q >= 0; q--) {
                if (q > 0)
                    aMove(tI.layer[q], (t.pm.rtls) ? n$ : -3000, -3000);
                if (q > 0 && tI.ctd)
                    aMove(tI.blr[q], (t.pm.rtls) ? n$ : -3000, -3000);
                iw = Mm(iw, oW(tI.layer[q]));
                ih = Mm(ih, oH(tI.layer[q]));
                if (tI.ctd) {
                    iw = Mm(iw, oW(tI.blr[q]));
                    ih = Mm(ih, oH(tI.blr[q]));
                }
                mwt = Mm(iw + tI.gSW(0), mwt);
                mht = Mm(ih, mht);
            }
            wts[p] = iw;
            hts[p] = ih;
            if (t.ct == 0) {
                if ((p - thl1) % iprc == 0 && p - thl1 > 0 && !(p == 0 && thl1) && !(p == til - 1 && thl2) && t.dd > 1) {
                    tI.nC = 1;
                    rC++;
                    mwd[rC] = iw;
                    hW += t.dbi + mwd[rC - 1];
                } else {
                    if (!(thl1 && p == 0) && !(thl2 && p == til - 1)) {
                        mwd[rC] = Mm(iw, mwd[rC]);
                    }
                }
            } else {
                if ((p - thl1) % iprc == 0 && p - thl1 > 0 && !(p == 0 && thl1) && !(p == til - 1 && thl2) && t.dd > 1) {
                    tI.nC = 1;
                    rC++;
                    rR = thl1;
                    mhg[rC] = ih;
                    mwd[rR] = Mm(iw + tI.gSW(0), mwd[rR]);
                    hH += t.dbi + mhg[rC - 1];
                    rR++;
                } else {
                    mhg[rC] = Mm(ih, mhg[rC]);
                    if (rC != 0) {
                        if (thl2 && p == til - 1) {
                            mwd[mwd.length] = iw;
                        } else {
                            mwd[rR] = Mm((iw + tI.gSW(0)), mwd[rR]);
                        }
                    } else {
                        mwd[rR] = iw + tI.gSW(0);
                    }
                    rR++;
                }
            }
        }
        hW += mwd[rC];
        hH += mhg[rC];
        t.hH = hH;
    }
    if ((thl1 || thl2) && t.ct == 0) {
        var dvd1 = dvd2 = tdvd = 0;
        if (hW < wts[0] && thl1)
            dvd1 = wts[0];
        if (hW < wts[til - 1] && thl2)
            dvd2 = wts[til - 1];
        var dvd = Mm(dvd1, dvd2);
        if (dvd > 0) {
            for (var p = 0; p < t.dd - 1; p++) {
                mwd[p] = parseInt(mwd[p] * dvd / hW);
                tdvd += mwd[p] + t.dbi;
            }
            mwd[t.dd - 1] = dvd - tdvd;
            if (thl1)
                wts[0] = dvd;
            if (thl2)
                wts[til - 1] = dvd;
            hW = dvd;
        }
    }
    if (tar == 0) {
        var hTop = ((thl1) ? (((t.eh) ? mht : hts[0]) + t.dbi) : 0);
    }
    wts[0] = (t.ct == 0) ? ((thl1 == 0) ? mwt : mwd[0]) : ((t.ew && thl1 == 0) ? mwt : mwd[0]) - t.it[0].gSW(0);
    var hLeft = (thl1) ? (wts[0] + t.dbi) : 0;
    t.it[0].nTop = t.it[0].nL = rC = 0;
    if (tar == 0) {
        rR = 1;
        t.it[0].nW = wts[0];
        mT = Mm(mT, t.it[0].gSW(1) + t.it[0].nTop + ((t.eh) ? mht : hts[0]));
        mL = Mm(mL, t.it[0].nL + wts[0] + t.it[0].gSW(0));
        for (var p = 1; p < til; p++) {
            var tI = t.it[p];
            if (t.ct == 0) {
                h = (t.eh) ? mht : hts[p - 1];
                if (tI.nC) {
                    tI.nTop = hTop;
                    tI.nL = t.it[p - 1].nL + mwd[rC] + t.dbi;
                    rC++;
                } else {
                    tI.nTop = t.it[p - 1].nTop + h + t.dbi;
                    tI.nL = t.it[p - 1].nL;
                    tI.nTop += t.it[p - 1].gSW(1);
                }
                if (thl2 && p == til - 1) {
                    tI.nTop = mT + t.dbi;
                    tI.nL = 0;
                }
                mT = Mm(mT, tI.gSW(1) + tI.nTop + ((t.eh) ? mht : hts[p]));
            } else {
                if (tI.nC) {
                    tI.nTop = t.it[p - 1].nTop + mhg[rC] + t.dbi;
                    tI.nL = hLeft;
                    rC++;
                    rR = thl1;
                } else {
                    tI.nTop = t.it[p - 1].nTop;
                    tI.nL = t.it[p - 1].nL + wts[p - 1] + t.dbi;
                    tI.nL += t.it[p - 1].gSW(0);
                }
                tI.nW = wts[p] = ((t.ew && p != til - thl2) ? mwt : mwd[rR]) - tI.gSW(0);
                rR++;
                if (thl2 && p == til - 1) {
                    tI.nTop = 0;
                    tI.nL = mL + t.dbi;
                    tI.nW = wts[p] = mwd[mwd.length - 1];
                }
                mL = Mm(mL, tI.nL + wts[p] + tI.gSW(0));
            }
        }
        t.mL = mL;
    }
    rR = 1;
    var $SF = 1;
    if (t.ct == 2) {
        var xyz = tcbL + tcbR + t.mL;
        if (t.mbPCl) {
            t.mbL = Mr(dBd.clientWidth * t.mbLP);
        }
        if (t.mbPCr) {
            t.mbR = Mr(dBd.clientWidth * t.mbRP);
        }
        if (dBd.clientWidth > xyz + t.mbL + t.mbR) {
            if (t.mbT == 3)
                t.mwd = dBd.clientWidth - t.mbL - t.mbR;
            t.cX = t.mbL;
        } else {
            if (t.mbT == 3)
                t.mwd = xyz;
            if (dBd.clientWidth > xyz + t.mbL) {
                t.cX = t.mbL;
            } else {
                if (dBd.clientWidth > xyz) {
                    t.cX = dBd.clientWidth - xyz;
                } else {
                    t.cX = 0;
                }
            }
        }
    }
    if (t.ct > 0 && t.mwd >= tcbL + tcbR + t.mL) {
        $TW = (thl1) ? t.it[0].nW : 0;
        $TW += (thl2) ? t.it[til - 1].nW : 0;
        $TW += ((t.dd > 0) ? (iprc + thl1 + thl2 - 1) : (til - 1)) * t.dbi;
        $SF = (t.mwd - $TW - tcbR - tcbL) / (t.mL - $TW);
        mL = t.mwd - tcbR - tcbL;
        if (thl2) {
            t.it[til - 1].nL = mL - t.it[til - 1].nW;
            wts[til - 1] = t.it[til - 1].nW;
        }
        if (thl1) {
            wts[0] = t.it[0].nW;
        }
        for (var p = thl1; p < til - thl2; p++) {
            if (t.it[p].nC) {
                rR = 1;
            } else {
                if (p != 0)
                    t.it[p].nL = t.it[p - 1].nL + wts[p - 1] + t.dbi + t.it[p - 1].gSW(0);
                else
                    wts[0] = t.it[0].nW;
            }
            wts[p] = Mr($SF * (t.it[p].nW + t.it[p].gSW(0))) - t.it[p].gSW(0);
            if (rR == iprc) {
                wts[p] += ((thl2) ? (t.it[til - 1].nL - t.dbi) : mL) - (t.it[p].nL + wts[p]) - t.it[p].gSW(0);
            }
            rR++;
        }
    }
    rC = 0;
    hW = Mm(hW, wts[0]);
    var plO = 0;
    if (t.ct == 2) {
        if (t.mbT == 1)
            plO = Mr((dBd.clientWidth - t.mL - tcbL - tcbR - t.mbR - t.mbL) / 2);
        if (t.mbT == 2)
            plO = Mr((dBd.clientWidth - t.mL - tcbL - tcbR)) - t.mbR - t.mbL;
        if (plO < 0)
            plO = 0;
    }
    if (t.sLDA[0] && t.pi) {
        var tPPw = t.pi.ps.width - t.cs.bWr - t.cs.bWl;
        if (mwt + t.sLDA[2] + t.sLDA[4] <= tPPw) {
            if (t.sLDA[8] && t.dd == 1)
                mwt = tPPw - t.sLDA[2] - t.sLDA[4];
        } else {
            if (t.sLDAr == 0 && t.sLDA[7] && t.dd == 1) {
                t.mwd = tPPw - t.sLDA[2] - t.sLDA[4];
                t.git(1);
                t.ft = 0;
                t.fe();
                t.argd = 0;
                t.sLDAr = 1;
                t.arr();
                return;
            }
        }
    }
    for (var p = 0; p < til; p++) {
        var tI = t.it[p];
        if (tI.nC)
            rC++;
        if (t.ct == 0) {
            w = (thl1 && p == 0) ? ((t.dd > 1) ? hW : mwt) : ((thl2 && p == til - 1) ? ((t.dd > 1) ? hW : mwt) : (t.sLDA[0]) ? mwt : mwd[rC]);
            h = (t.eh) ? mht : hts[p];
        } else {
            h = ((thl1 && p == 0) || (thl2 && p == til - 1)) ? t.hH : mhg[rC];
            w = wts[p];
        }
        for (var q = 0; q < t.pm.st; q++) {
            aMove(tI.layer[q], (tI.left = tI.nL + plO), ((tar == 0) ? (tI.top = tI.nTop) : tI.top), ((tar == 0 || t.mbT == 3) ? (tI.width = w) : tI.width), ((tar == 0) ? (tI.height = h) : tI.height));
            if (tI.ctd)
                aMove(tI.blr[q], tI.left, tI.top, tI.width, tI.height);
            if (tI.blr2[q])
                aMove(tI.blr2[q], n$, n$, (oW(tI.blr[0]) - aIC[tpi][tI.lI[0] * 3 + 1] - ((tI.rI[0]) ? aIC[tpi][tI.rI[0] * 3 + 1] : 0) - tI.style[0].bWl - tI.style[0].bWr), (oH(tI.blr[0]) - tI.style[0].bWt - tI.style[0].bWb));
            if (tI.fL$) {
                if (tI.fLl[q]) {
                    var x = 0, y = 0, eCr = aFC$[tpi][tI.fI$[q]][1], gCr = aFC$[tpi][tI.fI$[q]][2];
                    if (eCr == 1 || eCr == 2 || eCr == 6)
                        x -= oW(tI.fLl[q]);
                    if (eCr == 2 || eCr == 3 || eCr == 7)
                        y -= oH(tI.fLl[q]);
                    if (eCr == 5 || eCr == 7 || eCr == 8)
                        x -= oW(tI.fLl[q]) / 2;
                    if (eCr == 4 || eCr == 6 || eCr == 8)
                        y -= oH(tI.fLl[q]) / 2;
                    if (gCr == 1 || gCr == 2 || gCr == 6)
                        x += tI.width;
                    if (gCr == 2 || gCr == 3 || gCr == 7)
                        y += tI.height;
                    if (gCr == 5 || gCr == 7 || gCr == 8)
                        x += tI.width / 2;
                    if (gCr == 4 || gCr == 6 || gCr == 8)
                        y += tI.height / 2;
                    tI.flX[q] = x;
                    tI.flY[q] = y;
                    aMove(tI.fLl[q], tI.left + aFC$[tpi][tI.fI$[q]][3] + x, tI.top + aFC$[tpi][tI.fI$[q]][4] + y);
                }
            }
        }
        aMove(tI.layer[1], (t.pm.rtls) ? n$ : -3000, -3000);
        if (tI.ctd)
            aMove(tI.blr[1], (t.pm.rtls) ? n$ : -3000, -3000);
        if (t.pm.st != 2) {
            aMove(tI.layer[2], (t.pm.rtls) ? n$ : -3000, -3000);
            if (tI.ctd)
                aMove(tI.blr[2], (t.pm.rtls) ? n$ : -3000, -3000);
        }
        tI.elr.style.visibility = (tI.noLayer) ? "hidden" : "inherit";
        aMove(tI.elr, tI.nL + plO, ((tar == 0) ? tI.nTop : n$), tI.width, ((tar == 0) ? h : n$));
        if (tI.hSp) {
            var tlr = $D.all[tI.id + "_5"];
            if (t.ct == 0) {
                aMove(tlr, tI.left, tI.top + h, w);
            } else {
                aMove(tlr, (tI.left + tI.width), tI.top, n$, (tar == 0) ? h : n$);
            }
        }
    }
    if (t.ct == 0) {
        t.width = (t.dd > 1) ? hW : mwt;
        t.height = mT;
    } else {
        t.width = mL;
        t.height = t.hH;
    }
    aMove(t.layer, n$, n$, t.width, t.height);
    aMove(t.layer2, n$, n$, t.width + tcbR + tcbL, t.height + tcbT + tcbB);
    t.layer2.style.pixelHeight -= oH(t.layer2) - t.layer2.style.pixelHeight;
    if (t.mScr > 0 && oH(t.layer2) > t.mScr && !t.sLDe()) {
        aMove(t.layer2, n$, n$, (t.width + ((t.mScr2 == 2) ? 0 : scW) + tcbR + tcbL), t.mScr);
        t.layer2.style.overflowY = (t.mScr2 == 2) ? "hidden" : "scroll";
        t.layer2.style.pixelHeight -= oH(t.layer2) - t.layer2.style.pixelHeight;
    }
    t.layer2.style.pixelWidth -= oW(t.layer2) - t.layer2.style.pixelWidth;
    if (t.ct == 2 && t.mbT < 3) {
        t.layer2.style.pixelWidth = Mm((dBd.clientWidth - t.mbR - t.mbL), t.mL + tcbR + tcbL);
        t.layer.style.pixelWidth = t.layer2.style.pixelWidth - tcbL - tcbR;
    }
    t.argd = 1;
}
function asLDe() {
    var rS = 0;
    for (var i = 0; i < this.it.length; i++) {
        if (this.it[i].sm) {
            if (this.it[i].sm.sLDA[0])
                rS = 1;
        }
    }
    return rS;
}
function gLT(o) {
    var d = new Array();
    d[0] = oL(o);
    d[1] = oT(o);
    var z = o.offsetParent;
    while (z != n$) {
        d[0] += oL(z);
        d[1] += oT(z);
        z = z.offsetParent;
    }
    d[2] = oW(o);
    d[3] = oH(o);
    return d;
}
function gX(o) {
    var x = new Array();
    var mS0 = (isNaN(o.mS0)) ? Mr((vb - vt) * o.mS0.substring(0, o.mS0.length - 1) / 100) : o.mS0;
    x[2] = Mr(((o.mS1 == 1) ? ((vb - vt - oH(o.cn[0].layer2)) / 2) : ((o.mS1 == 2) ? (vb - vt - oH(o.cn[0].layer2)) : 0)) + mS0);
    x[0] = o.y - x[2];
    if (o.mS3 == 2) {
        var i = 0, txt = "", tImg = n;
        while (i < $D.images.length && txt != o.mS4) {
            var obj = $D.images[i].src.split("/");
            var txt = obj[obj.length - 1];
            if (txt == o.mS4)
                tImg = $D.images[i];
            i++;
        }
        x[1] = ((tImg) ? gLT(tImg)[1] + o.mS5 + ((o.mS2 == 1) ? oH(tImg) : 0) : 0);
    } else {
        if (o.mS3 > 0)
            var obj = dg(o.mS4);
        x[1] = ((o.mS3 == 1) ? (((obj) ? gLT(obj)[1] : 0) + o.mS5 + ((o.mS2 == 1) ? ((obj) ? oH(obj) : 0) : 0)) : (((o.mS2 == 1) ? viewHeight : 0) + o.mS5));
    }
    x[1] += -x[2] - oH(o.cn[0].layer2);
    if (x[0] > x[1])
        x[1] = x[0];
    return x;
}
function isOverlap(o1, o2) {
    var d1 = d2 = new Array();
    d1 = gLT(o1);
    d2 = gLT(o2);
    if (d1[1] > d2[1] + d2[3])
        return 0;
    if (d1[1] + d1[3] < d2[1])
        return 0;
    if (d1[0] > d2[0] + d2[2])
        return 0;
    if (d1[0] + d1[2] < d2[0])
        return 0;
    return 1;
}
function aSHS(flg) {
    var o = $D.getElementsByTagName("select");
    for (var i = 0; i < o.length; i++)
        if (isOverlap(this.layer2, o[i]))
            if (flg)
                this.pm.shh--;
            else
                this.pm.shh++;
    for (var i = 0; i < o.length; i++)
        if (isOverlap(this.layer2, o[i]))
            o[i].style.visibility = (this.pm.shh == 0) ? "visible" : "hidden";
}
function awmcs(sf, x, y) {
    var flt;
    if (this.it.length == 0)
        return;
    if (sf) {
        if (mpi || aLf() || awmprp) {
            this.cd = 0;
            this.git();
            this.fe();
            this.layer2.style.visibility = "hidden";
            if (this.ct == 2 && this.argd == 1)
                this.argd = 2;
            this.arr();
            if (this.sLDA[0])
                this.arrSL(0, n$);
            if (arguments.length == 1)
                this.pc();
            else {
                this.left = this.layer2.style.pixelLeft = x;
                this.top = this.layer2.style.pixelTop = y;
            }
            if (this.shw > 2 && sf == 1) {
                flt = ((this.opc == 100) ? 0 : 1);
                this.layer2.filters[flt].apply();
                this.layer2.filters[flt].play();
            }
            this.layer2.style.visibility = "visible";
            if ($D.location.protocol != "https:" && this.pm.aCF == 1)
                if (this.ind == 0) {
                    if (!is550)
                        AWC.Apl(this.id + "_X", n$, true);
                } else {
                    AWC.Apl(this.id + "_X", n$, true);
                }
            if ((this.ind != 0 || this.pm.sFO > -9000) && this.pm.aCF == 2)
                this.sHS(0);
            if (sf == 2) {
                this.pi.shst(1);
                return;
            }
            if (this.shw > 0 && this.shw < 3 && !this.awmun)
                this.unf(0);
            if (this.jcoo != n$) {
                if (this.jcoo.search(/awmhidediv\(\)/) > -1)
                    this.jcoo = this.jcoo.replace(/awmhidediv\(\)/, "awmhidediv(this)");
                eval(this.jcoo);
            }
            if (this.ind == 0)
                if (this.pm.selectedItem > 0)
                    this.it[this.pm.selectedItem - ((this.it[0].iHF == 2) ? 1 : 0)].shst(2);
            for (var i = 0; i < this.it.length; i++) {
                if (this.it[i].hFs) {
                    this.it[i].shst(this.pm.aIH);
                }
            }
        }
    } else {
        if (!this.ft || this.mio || this.cd || this.sLDO)
            return;
        if (this.shwd == 0)
            this.layer2.style.visibility = this.layer3.style.visibility = "hidden";
        if (this.shwd > 0 && this.shwd < 3) {
            this.unf(1);
        }
        if (this.shwd > 2) {
            flt = ((this.opc == 100) ? 0 : 1) + ((this.shw > 2) ? 1 : 0);
            this.layer2.filters[flt].apply();
            this.layer2.style.visibility = this.layer3.style.visibility = "hidden";
            this.layer2.filters[flt].play();
        }
        if (this.pi != n$)
            if (this.pm.selectedItem < 1) {
                this.pi.shst((this.pi.hFs) ? this.pm.aIH : 0);
            } else {
                if (this.pi.ind == this.pm.selectedItem - ((this.pi.ps.it[0].iHF == 2) ? 1 : 0) && this.pi.ps.ind == 0) {
                    this.pi.shst(2);
                } else {
                    if (!this.pi.hFs)
                        this.pi.shst(0);
                }
            }
        if (this.pm.aCF == 1)
            AWC.Dsc(this.id + "_X");
        if ((this.ind != 0 || this.pm.sFO > -9000) && this.pm.aCF == 2)
            this.sHS(1);
        if (this.jcoc != n$ && !this.cd)
            eval(this.jcoc);
        if (this.sLDA[0])
            this.arrSL(-this.height - this.cs.bWt - this.cs.bWb - this.sLDA[3] - this.sLDA[5]);
        this.cd = 1;
    }
}
function amCMO(flg) {
    for (var p = 0; p < this.it.length; p++)
        if (this.it[p].ps.pi != n$)
            this.it[p].ps.pi.ps.amMO(flg);
    this.mio = flg;
}
function awmchdt(flg) {
    this.fe();
    if (this.sLDA[0] == 0 && this.ind != 0) {
        this.rSL();
    }
    for (var p = 0; p < this.it.length; p++) {
        if (this.it[p].sm != n$)
            if (awmSubmenusFrame == '' && !(this.pm.sFO > -9000)) {
                if (xxxx != this.it[p].sm)
                    this.it[p].sm.hdt(0);
                else if (flg == 0)
                    this.it[p].sm.hdt(0);
            } else {
                this.it[p].sm.hdt(0);
            }
        if (this.pi != n$)
            if (this.pm.selectedItem < 1 && awmSubmenusFrame != '')
                this.pi.shst(0);
    }
    if (flg == 0 && !this.cd)
        this.show(0);
}
function arSL() {
    this.fe();
    for (var p = 0; p < this.it.length; p++) {
        if (this.it[p].sm != n$) {
            this.it[p].sm.sLDO = false;
            this.it[p].sm.mio = 0;
            if (xxxx != this.it[p].sm)
                this.it[p].sm.rSL();
        }
    }
}
function crsI() {
    for (var p = 0; p < this.it.length; p++) {
        if (this.ind != 0 || p + ((this.it[0].iHF == 2) ? 1 : 0) != this.pm.selectedItem)
            this.it[p].shst((this.it[p].hFs) ? this.pm.aIH : 0);
    }
}
function awmmcm(flg) {
    if (this.mio && !flg)
        return;
    for (var cno = (this.cll && awmctm == n$) ? 0 : 1; cno < this.cn.length; cno++) {
        if (flg) {
            this.cn[cno].mio = 0;
        }
        if (this.cn[cno].ind != 0 && this.cn[cno].cd == 0)
            if (this.cn[cno].pi.ps.ind != 0)
                this.cn[cno].sLDO = (this.cn[cno].pi.ps.sLDO && this.cn[cno].sLDA[0]);
        this.cn[cno].show(0);
    }
    if (awmSubmenusFrame != "") {
        for (p = 0; p < this.cn[0].it.length; p++) {
            if (this.cn[0].it[p].sm != n$)
                this.cn[0].it[p].sm.pm.cm(flg);
        }
    }
}
function awmodmd() {
    for (mno = 0; mno < awmm.length; mno++) {
        awmm[mno].cm(0);
    }
}
function awmocmo() {
    this.mio = 1;
    this.amMO(1);
    if (this.awmun)
        return;
    clearTimeout(this.pm.awmHide2ID);
    mdo = 0;
    this.pm.mio = 1;
    if (this.pi != n$)
        this.pi.shst((this.swn == 0) ? 1 : 2);
    if (this.ind > 0)
        clearTimeout(this.pi.ps.hsid);
    clearTimeout(this.hsid);
}
function awmocmot() {
    this.mio = 0;
    this.amMO(0);
    this.pm.mio = 0;
    if (!this.pm.ss && this.sLDA[0] == 0) {
        if (!mdo) {
            clearTimeout(awmCollID);
            awmCollID = setTimeout("awmm[" + this.pm.ind + "].cm(0);", this.grHideDelay);
            if (awmSubmenusFrame == "") {
                this.hsid = setTimeout("awmm[" + this.pm.ind + "].cn[0].hdt();", this.grHideDelay);
                awmHideID = this.hsid;
            }
        }
    }
}
function awmiss(sts) {
    var tpi = this.pm.ind;
    if (sts < 2 && this.sm != n$)
        if (this.sm.sLDO)
            sts = 2;
    if (sts == 2 && this.pm.st == 2)
        sts = 1;
    if (sts == 0) {
        if (this.layer[0])
            this.layer[0].style.visibility = "inherit";
        if (this.ctd)
            this.blr[0].style.visibility = "inherit";
        if (this.fL$)
            if (this.fLl[0])
                this.fLl[0].style.visibility = "inherit";
    }
    if (this.noLayer == 1) {
        var tls = this.layer[0].style, tss = this.style[sts], tlc = this.layer[0].children[0].children[0].children[0].style;
        tlc.color = tss.fgc;
        if (tss.fnt != n$)
            tlc.font = tss.fnt;
        tlc.textDecoration = (tss.tdec != n$) ? tss.tdec : "";
        tlc.textAlign = awmalt[tss.algnm];
        tlc.padding = tss.pd + "px";
        if (this.ps.siw)
            tlc.paddingRight = "0px";
        tls.borderStyle = tss.bs;
        tls.borderWidth = tss.bw + "px";
        tls.borderColor = tss.bc;
        if (this.ctd == 0)
            tls.backgroundColor = (tss.bgc == n$) ? "transparent" : tss.bgc;
    }
    for (var q = 1; q < this.pm.st; q++) {
        if (q == sts) {
            if (this.layer[q] == n$)
                return;
            if (this.noLayer == 0)
                aMove(this.layer[q], oL(this.layer[0]), oT(this.layer[0]), oW(this.layer[0]), oH(this.layer[0]));
            if (this.ctd)
                aMove(this.blr[q], oL(this.layer[0]), oT(this.layer[0]), oW(this.layer[0]), oH(this.layer[0]));
            if (this.blr2[q])
                aMove(this.blr2[q], n$, n$, this.blr[q].style.pixelWidth - aIC[tpi][this.lI[q] * 3 + 1] - ((this.rI[q]) ? aIC[tpi][this.rI[q] * 3 + 1] : 0) - (this.style[q].bWl + this.style[q].bWr), this.blr[q].style.pixelHeight - (this.style[q].bWt + this.style[q].bWb));
            if (this.fL$)
                if (this.fLl[q])
                    this.fLl[q].style.visibility = "inherit";
            if (this.noLayer == 0)
                this.layer[0].style.visibility = "hidden";
            if (this.ctd)
                this.blr[0].style.visibility = "hidden";
            if (this.fL$)
                if (this.fLl[0])
                    this.fLl[0].style.visibility = "hidden";
        } else {
            if (this.noLayer == 0)
                if (this.layer[q])
                    this.layer[q].style.pixelTop = -3000;
            if (this.ctd)
                this.blr[q].style.pixelTop = -3000;
            if (this.fL$)
                if (this.fLl[q])
                    this.fLl[q].style.visibility = "hidden";
        }
    }
}
function awmoimo() {
    if (this.ps.awmun)
        return;
    mdo = 0;
    if (awmctm != n$)
        return;
    if (awmSubmenusFrame != "") {
        eval("var frex=parent." + awmSubmenusFrame);
        if (frex) {
            eval("this.sm=parent." + awmSubmenusFrame + ".awm" + this.pm.nm + "_sub_" + (this.ind + 1));
            if (this.sm) {
                this.sm.pi = this;
                this.sm.pm.ss = this.pm.ss;
            } else
                this.sm = n$;
        }
    }
    this.ps.mio = 1;
    this.pm.mio = 1;
    var ab$ = (awmSubmenusFrame == '' && !(this.pm.sFO > -9000)) ? 1 : 0;
    if (this.pm.ss) {
        if (this.pm.sUC > 1) {
            if (this.sm != n$)
                if (!this.sm.swn)
                    if (this.sm.cd) {
                        if (ab$) {
                            this.ps.rsI();
                            setTimeout("awmm[" + this.pm.ind + "].cn[" + this.ps.ind + "].hdt();", 200);
                        } else {
                            this.ps.hdt();
                        }
                    }
        } else {
            if (ab$) {
                this.ps.rsI();
                setTimeout("awmm[" + this.pm.ind + "].cn[" + this.ps.ind + "].hdt();", 200);
            } else {
                this.ps.hdt();
            }
        }
    } else {
        this.ps.rsI();
    }
    xxxx = n$;
    if (this.sm != n$ && this.pm.ss && ab$)
        if (this.sm.sLDA[0] == 0)
            xxxx = this.sm;
    if (this.sm != n$)
        if (this.sm.sLDA[0] && this.sbt == this.url2)
            this.sbt = "";
    this.shst(1);
    if (!is70)
        status = this.sbt;
    if (this.sm != n$)
        if (!this.sm.swn && !this.sm.sLDA[0]) {
            clearTimeout(this.sm.hsid);
            clearTimeout(this.ps.ssid);
            this.sm.mio = 0;
            if (this.sm.cd)
                this.ps.ssid = (awmSubmenusFrame == '') ? setTimeout("awmm[" + this.pm.ind + "].cn[" + this.sm.ind + "].show(1);", this.sm.grShowDelay) : setTimeout("parent." + awmSubmenusFrame + ".awm" + this.pm.nm + "_sub_" + (this.ind + 1) + ".show(1)", this.sm.grShowDelay);
        }
    if (this.jc[1] != n$ && !this.noLayer)
        eval(this.jc[1]);
}
function awmoimot() {
    if (this.sm == n$ || (this.sm != n$ && this.sm.cd)) {
        if (this.pm.selectedItem < 1) {
            this.shst((this.hFs) ? this.pm.aIH : 0);
        } else {
            if (this.ps.ind == 0 && this.ind == this.pm.selectedItem - ((this.ps.it[0].iHF == 2) ? 1 : 0)) {
                this.shst(2);
            } else {
                this.shst((this.hFs) ? this.pm.aIH : 0);
            }
        }
    }
    if (this.sm != n$) {
        if (!this.pm.ss)
            this.sm.mio = 0;
        clearTimeout(this.sm.hsid);
        clearTimeout(this.ps.ssid);
        if (!this.sm.cd && !this.pm.ss) {
            this.sm.hsid = (awmSubmenusFrame == '') ? setTimeout("awmm[" + this.pm.ind + "].cn[" + this.sm.ind + "].hdt(0);", this.sm.grHideDelay) : setTimeout("parent." + awmSubmenusFrame + ".awm" + this.pm.nm + "_sub_" + (this.ind + 1) + ".hdt(0)", this.sm.grHideDelay);
            awmHideID = this.sm.hsid;
        }
    }
    if (!is70)
        status = awmdst;
    if (this.jc[0] != n$ && !this.noLayer)
        eval(this.jc[0]);
}
function awmoimd() {
    if (this.noLayer == 2)
        return;
    this.shst(2);
    if (this.iHF == 1) {
        this.pm.mio = 0;
        awmctm = this.ps;
        this.pm.cm(0);
        this.pm.mio = 1;
        awmmox = event.clientX - oL(awmctm.layer2);
        awmmoy = event.clientY - oT(awmctm.layer2);
        awmml = oL(awmctm.layer2) - awmctm.layer2.style.pixelLeft;
        awmmt = oT(awmctm.layer2) - awmctm.layer2.style.pixelTop;
    } else {
        if (event.button == 2)
            return;
        if (this.sm != n$)
            if (this.sm.swn || this.sm.sLDA[0]) {
                var sOP = this.sm.sLDO;
                clearTimeout(this.sm.hsid);
                if (this.sm.sLDA[0])
                    this.ps.rSL();
                this.ps.hdt();
                if (this.sm.sLDA[0]) {
                    this.url = n$;
                    if (sOP) {
                        this.sm.show(0);
                    } else {
                        this.sm.sLDO = true;
                        this.sm.show(1);
                    }
                    if (this.pm.rmbS) {
                        this.pm.prvS = this.pm.nm;
                        for (var i = 0; i < this.pm.cn.length; i++) {
                            if (this.pm.cn[i].sLDA[0]) {
                                if (this.pm.cn[i].sLDO) {
                                    this.pm.prvS += "-" + this.pm.cn[i].ind;
                                }
                            }
                        }
                        this.pm.writeCookie();
                    }
                } else {
                    this.sm.show(1);
                }
            }
        mdo = 0;
        if (this.noLayer)
            return;
        if (this.jc[2] != n$)
            eval("setTimeout(\"" + this.jc[2] + "\",10);");
        if (this.url != n$) {
            if (event.shiftKey)
                window.open(this.url);
            else if (this.tf == n$) {
                window.location = this.url;
                setTimeout("awmm[" + this.pm.ind + "].cn[" + this.ps.ind + "].rsI();", 100);
            } else if (this.tf == "new")
                ;
            else if (this.tf == "top")
                window.top.location = this.url;
            else
                eval("parent." + this.tf + ".location=this.url");
        }
    }
}
function awmoimu() {
    if (this.iHF == 1) {
        if (awmctm != n$) {
            awmctm.pm.rep = 1;
            awmctm = n$;
        }
    } else {
        mdo = 0;
        this.shst(1);
        if (this.sm == n$ && this.ps.sLDA[0] == 0 && !this.pm.ss)
            this.pm.cm(1);
        if (this.noLayer == 0)
            $D.selection.empty();
    }
}
function awmoimm() {
    var t = this.ps, oH2 = oH(t.layer2) - (t.cs.bWt + t.cs.bWb);
    if (t.mScr != 0 && t.ct == 0 && t.mScr2) {
        if (oH(t.layer) > oH2) {
            var x$$ = (t.layer2.scrollHeight - oH2) / (0.74 * oH2);
            t.layer2.scrollTop = Mr((event.clientY + vt - oT(t.layer2) - t.cs.bWt - 0.13 * oH2) * x$$);
        }
    }
}
function awmohmm() {
    if (awmctm != n$) {
        awmctm.pm.rep = 1;
        awmctm.left = awmctm.layer2.style.pixelLeft = event.clientX - awmmox;
        awmctm.top = awmctm.layer2.style.pixelTop = event.clientY - awmmoy;
        return false;
    }
}
function awmpc(flg) {
    if (flg && this.wtd == 0)
        return;
    this.git();
    if (!this.ft)
        return;
    var me = this.pm;
    if (this.pi == n$) {
        var tmpEl = n$;
        if (this.pm.elemRel) {
            if (this.tNF == n$) {
                tmpEl = gTE(this.pm.ind);
                this.tNF = tmpEl;
            } else {
                tmpEl = this.tNF;
            }
        }
        var x = this.pm.offX, y = this.pm.offY;
        if (tmpEl) {
            x += oL(tmpEl) + this.pm.offX2;
            y += oT(tmpEl) + this.pm.offY2;
            var z = tmpEl.offsetParent;
            while (z != n$) {
                x += oL(z);
                y += oT(z);
                z = z.offsetParent;
            }
            var crn = me.rc;
            if (crn == 4 || crn == 6 || crn == 8)
                y -= oH(this.layer) / 2;
            if (crn == 5 || crn == 7 || crn == 8)
                x -= oW(this.layer) / 2;
            if (crn == 1 || crn == 2 || crn == 6)
                x -= oW(this.layer);
            if (crn == 2 || crn == 3 || crn == 7)
                y -= oH(this.layer);
            if (tmpEl.tagName != "SPAN") {
                crn = me.crn;
                if (crn == 1 || crn == 2 || crn == 6)
                    x += oW(tmpEl);
                if (crn == 2 || crn == 3 || crn == 7)
                    y += oH(tmpEl);
                if (crn == 5 || crn == 7 || crn == 8)
                    x += oW(tmpEl) / 2;
                if (crn == 4 || crn == 6 || crn == 8)
                    y += oH(tmpEl) / 2;
            }
        } else {
            var crn = me.crn;
            x += this.cX + ((crn == 0 || crn == 4 || crn == 3) ? (me.dx) : ((crn == 1 || crn == 6 || crn == 2) ? vr - vl - oW(this.layer) - me.dx : Mr((vr - vl - oW(this.layer)) / 2) + me.dx));
            y += (crn == 0 || crn == 5 || crn == 1) ? (me.dy) : ((crn == 3 || crn == 7 || crn == 2) ? vb - vt - oH(this.layer) - me.dy : Mr((vb - vt - oH(this.layer)) / 2) + me.dy);
        }
        if ((this.left != x + awmlssx || this.top != y + awmlssy) && !this.pm.rep && this.cd == 0) {
            if (this.sLDe())
                this.pm.dft = 0;
            x += (this.pm.dft == 1 || this.pm.dft == 3 || this.pm.dft == 4 || this.pm.dft == 6) ? vl : 0;
            y += (this.pm.dft == 1 || this.pm.dft == 2 || this.pm.dft == 4 || this.pm.dft == 5) ? vt : 0;
            this.layer2.style.pixelLeft = this.left = x;
            this.pm.y = y;
            if (this.pm.dft < 7 || typeof (this.top) == $un)
                this.layer2.style.pixelTop = this.top = y;
            if (this.pm.aCF == 1)
                AWC.Dsc(this.id + "_X");
            if (this.pm.elemRel && this.pm.pushPull && !this.pm.pushPulled) {
                var tmpEl = gTE(this.pm.ind);
                if (tmpEl) {
                    if (tmpEl.style.display == "")
                        tmpEl.style.display = "inline-block";
                    aMove(tmpEl, n$, n$, n$, this.height + this.cs.bWt + this.cs.bWb);
                    this.pm.pushPulled = 1;
                }
            }
        }
    } else {
        if (flg)
            return;
        var psl = this.pi.ps.layer2;
        var pil = this.pi.layer[0];
        var parentBorder = this.pi.ps.cs;
        var pBl = parentBorder.bWl, pBr = parentBorder.bWr, pBt = parentBorder.bWt, pBb = parentBorder.bWb;
        this.lod = this.od;
        if (this.lod == 0) {
            if (this.pi.ps.ct == 0) {
                if (this.pm.rtls)
                    this.lod = ((oL(psl) - this.swr - oW(this.layer) > vl) ? 2 : 1);
                else
                    this.lod = ((oL(psl) + oW(psl) + this.swr + oW(this.layer) > vr) && (oL(psl) - this.swr - oW(this.layer) > vl)) ? 2 : 1;
            } else {
                this.lod = ((oT(psl) + oH(psl) + this.swr + oH(this.layer) > vb) && (oT(psl) - this.swr - oH(this.layer) > vl)) ? 2 : 1;
            }
        }
        if (this.pi.ps.ct == 0) {
            if (this.sLDA[0]) {
                this.left = this.layer2.style.pixelLeft = oL(psl) + pBl + this.sLDA[2] + ((this.sLDA[1] == 2) ? (oW(psl) - oW(this.layer2) - pBl - pBr) : ((this.sLDA[1] == 1) ? (oW(psl) - oW(this.layer2) - pBl - pBr) / 2 : 0));
                this.layer2.style.pixelTop = this.top = this.pi.top + oT(psl) + oH(pil) + pBt + this.sLDA[3];
            } else {
                this.left = this.layer2.style.pixelLeft = (this.lod == 1) ? ((this.pm.sFO > -9000 && this.ind == 0) ? vl : oL(psl) + this.pi.left + this.pi.width + pBl + pBr) + this.swr : oL(psl) - oW(this.layer) - this.swr;
                if (this.pm.sFO > -9000 && this.ind == 0 && ((this.pm.rtls && this.pm.rtlf != 2) || this.pm.rtlf == 1)) {
                    this.left = this.layer2.style.pixelLeft = Mm(dBd.clientWidth, ((awmBef5) ? 0 : dEl.clientWidth)) - oW(this.layer) - this.swr;
                }
                this.top = ((this.sa == 0) ? oT(psl) + oT(pil) + pBt - psl.scrollTop : ((this.sa == 1) ? oT(psl) : ((this.sa == 2) ? oT(psl) + oH(psl) - oH(this.layer2) : oT(psl) + (oH(psl) - oH(this.layer2)) / 2)));
                this.layer2.style.pixelTop = this.top += ((this.pm.sFO > -9000 && this.ind == 0) ? this.pm.sFO + vt - Mm(pil.document.body.scrollTop, ((awmBef5) ? 0 : pil.document.documentElement.scrollTop)) : 0) + this.alO;
            }
        } else {
            this.left = (this.sa == 0) ? (oL(psl) + oL(pil) + pBl + ((this.pm.rtls) ? (oW(pil) - oW(this.layer)) : 0)) : ((this.sa == 1) ? oL(psl) : ((this.sa == 2) ? oL(psl) + oW(psl) - (oW(this.layer) + this.cs.bWl + this.cs.bWr) : oL(psl) + (oW(psl) - (oW(this.layer) + this.cs.bWl + this.cs.bWr)) / 2));
            this.layer2.style.pixelLeft = this.left += ((this.pm.sFO > -9000 && this.ind == 0) ? this.pm.sFO + vl : 0) + this.alO;
            if (this.left + oW(this.layer) > vr)
                this.layer2.style.pixelLeft = this.left = vr - oW(this.layer);
            this.top = this.layer2.style.pixelTop = (this.lod == 1) ? ((this.pm.sFO > -9000 && this.ind == 0) ? vt : oT(psl) + this.pi.top + this.pi.height + pBt + pBb + 0 * oH(psl)) + this.swr : oT(psl) - oH(this.layer) - this.swr - pBt - pBb;
        }
        if (this.ct == 2)
            this.layer2.style.pixelLeft = this.left = this.cX;
        if (this.mScr == -1 && this.sLDA[0] == 0 && !this.sLDe()) {
            if (oH(this.layer) > (vb - vt)) {
                aMove(this.layer2, n$, n$, oW(this.layer) + ((this.mScr2 == 2) ? 0 : scW) + this.cs.bWl + this.cs.bWr, vb - vt);
                this.layer2.style.overflowY = (this.mScr2 == 2) ? "hidden" : "scroll";
                this.layer2.style.pixelHeight += this.layer2.style.pixelHeight - oH(this.layer2);
            } else {
                aMove(this.layer2, n$, n$, oW(this.layer) + this.cs.bWl + this.cs.bWr, oH(this.layer));
                this.layer2.style.overflowY = "visible";
            }
            this.layer2.style.pixelWidth -= oW(this.layer2) - this.layer2.style.pixelWidth;
        }
        if (this.mScr != 0)
            this.layer2.scrollTop = 0;
        if (this.top + oH(this.layer2) > vb && this.sLDA[0] == 0)
            this.layer2.style.pixelTop = this.top = vb - oH(this.layer2);
        if (this.top < vt && this.sLDA[0] == 0)
            this.layer2.style.pixelTop = this.top = vt;
    }
}
function awmu(fold) {
    var t = this;
    var layer = t.layer2, w1 = oW(layer), h1 = oH(layer);
    if (fold) {
        var shw = t.shwd;
        var awmud = t.awmUnfoldDirectionD;
        if (t.awmuc < 0) {
            clearInterval(t.uid);
            t.awmun = 0;
            layer.style.visibility = "hidden";
            t.layer3.style.visibility = "hidden";
            layer.style.clip = 'rect(-3000px,3000px,3000px,-3000px)';
            return;
        }
    } else {
        var shw = t.shw;
        var awmud = t.awmud;
        if (t.awmuc > 10) {
            t.layer3.style.visibility = "hidden";
            clearInterval(t.uid);
            t.awmun = 0;
            layer.style.clip = 'rect(-3000px,3000px,3000px,-3000px)';
            return;
        }
    }
    switch (awmud) {
        case 1:
            if (shw == 1) {
                layer.style.pixelLeft = t.left - w1 * (10 - t.awmuc) / 10;
                lSC(layer, 0, w1, h1, Mr(w1 * (10 - t.awmuc) / 10));
            } else
                lSC(layer, 0, Mr(w1 * t.awmuc / 10), h1, 0);
            break;
        case 2:
            if (shw == 1) {
                layer.style.pixelLeft = t.left + w1 * (10 - t.awmuc) / 10;
                lSC(layer, 0, Mr(w1 * t.awmuc / 10), h1, 0);
            } else
                lSC(layer, 0, w1, h1, w1 * (10 - t.awmuc) / 10);
            break;
        case 3:
            if (shw == 1) {
                layer.style.pixelTop = t.top - h1 * (10 - t.awmuc) / 10;
                lSC(layer, Mr(h1 * (10 - t.awmuc) / 10), w1, h1, 0);
            } else
                lSC(layer, 0, w1, Mr(h1 * t.awmuc / 10), 0);
            break;
        case 4:
            if (shw == 1) {
                layer.style.pixelTop = t.top + h1 * (10 - t.awmuc) / 10;
                lSC(layer, 0, w1, Mr(h1 * t.awmuc / 10), 0);
            } else
                lSC(layer, Mr(h1 * (10 - t.awmuc) / 10), w1, h1, 0);
            break;
        case 5:
            if (shw == 1) {
                aMove(layer, (t.left - w1 * (10 - t.awmuc) / 10), (t.top - h1 * (10 - t.awmuc) / 10));
                lSC(layer, Mr(h1 * (10 - t.awmuc) / 10), w1, h1, Mr(w1 * (10 - t.awmuc) / 10));
            } else
                lSC(layer, 0, Mr(w1 * t.awmuc / 10), Mr(h1 * t.awmuc / 10), 0);
            break;
        case 6:
            if (shw == 1) {
                aMove(layer, (t.left - w1 * (10 - t.awmuc) / 10), (t.top + h1 * (10 - t.awmuc) / 10));
                lSC(layer, 0, w1, Mr(h1 * t.awmuc / 10), Mr(w1 * (10 - t.awmuc) / 10));
            } else
                lSC(layer, Mr(h1 * (10 - t.awmuc) / 10), Mr(w1 * t.awmuc / 10), h1, 0);
            break;
        case 7:
            if (shw == 1) {
                aMove(layer, (t.left + w1 * (10 - t.awmuc) / 10), (t.top - h1 * (10 - t.awmuc) / 10));
                lSC(layer, Mr(h1 * (10 - t.awmuc) / 10), Mr(w1 * t.awmuc / 10), h1, 0);
            } else
                lSC(layer, 0, w1, Mr(h1 * t.awmuc / 10), w1 * (10 - t.awmuc) / 10);
            break;
        case 8:
            if (shw == 1) {
                aMove(layer, (t.left + w1 * (10 - t.awmuc) / 10), (t.top + h1 * (10 - t.awmuc) / 10));
                lSC(layer, 0, Mr(w1 * t.awmuc / 10), Mr(h1 * t.awmuc / 10), 0);
            } else
                lSC(layer, Mr(h1 * (10 - t.awmuc) / 10), w1, h1, w1 * (10 - t.awmuc) / 10);
            break;
        case 9:
            if (shw == 1) {
                aMove(layer, (t.left + w1 * (10 - t.awmuc) / 20), (t.top + h1 * (10 - t.awmuc) / 20));
                lSC(layer, 0, Mr(w1 * t.awmuc / 10), Mr(h1 * t.awmuc / 10), 0);
            } else
                lSC(layer, Mr(h1 * (10 - t.awmuc) / 20), ((w1 / 2) * (1 + t.awmuc / 10)), ((h1 / 2) * (1 + t.awmuc / 10)), w1 * (10 - t.awmuc) / 20);
            break;
        case 10:
            t.layer3.style.visibility = "visible";
            if (fold)
                layer.style.visibility = "hidden";
            aMove(t.layer3, t.left + w1 * (10 - t.awmuc) / 20, t.top + h1 * (10 - t.awmuc) / 20, w1 * (t.awmuc / 10), h1 * t.awmuc / 10);
            break;
    }
    if (fold)
        t.awmuc -= 0.5;
    else
        t.awmuc += 0.5;
}
function awmcu(fold) {
    clearInterval(this.uid);
    if (typeof (this.lod == $un))
        this.lod = (this.od == 0) ? 1 : this.od;
    var $tlO = this.lod + ((this.pi) ? ((this.pi.ps.ct == 0) ? 0 : 2) : 2);
    if (fold == 0) {
        lSC(this.layer2, 0, 0, 0, 0);
        this.layer2.style.visibility = "visible";
        this.awmuc = 0;
        this.awmud = (this.ud != 0) ? this.ud : $tlO;
    } else {
        this.awmuc = 10;
        this.awmUnfoldDirectionD = (this.udd != 0) ? this.udd : $tlO;
    }
    this.awmun = 1;
    this.uid = setInterval("awmm[" + this.pm.ind + "].cn[" + this.ind + "].uf2(" + fold + ");", 10);
}
function awmcm() {
    var n$ = null, crc;
    for (var i = 0; i < awmsc.length; i++)
        awmsc[i] = n$;
    for (var i = 0; i < awmm.length; i++) {
        for (var j = 0; j < awmm[i].cn.length; j++) {
            crc = awmm[i].cn[j];
            for (var k = 0; k < crc.it.length; k++) {
                crc.it[k].pm = n$;
                crc.it[k].ps = n$;
                crc.it[k].sm = n$;
                if (crc.ft) {
                    crc.it[k].elr.pi = n$;
                    crc.it[k].elr = n$;
                }
                for (var l = 0; l < awmm[i].st; l++) {
                    if (crc.ft) {
                        crc.it[k].layer[l].pi = n$;
                        crc.it[k].layer[l] = n$;
                    }
                    crc.it[k].style[l] = n$;
                }
                crc.it[k] = n$;
            }
            if (crc.ft) {
                crc.layer.prc = n$;
                crc.layer = n$;
                crc.layer2 = n$;
            }
            crc.cs = n$;
            crc.pi = n$;
            crc.pm = n$;
            crc.pm = n$;
            awmm[i].cn[j] = n$;
        }
        awmm[i].sm = n$;
        awmm[i].pm = n$;
        awmm[i] = n$;
    }
}
function awmwr() {
    if (aRsF != n$)
        aRsF();
    if (!(awmSubmenusFrameOffset > -9000)) {
        if (typeof (awmm) == $un)
            return;
        for (var mno = 0; mno < awmm.length; mno++) {
            if (awmm[mno].cn[0].ft) {
                if (awmm[mno].cn[0].ct == 2) {
                    awmm[mno].cn[0].argd = 2;
                    awmm[mno].cn[0].arr();
                    if (awmm[mno].selectedItem > 0)
                        awmm[mno].cn[0].it[awmm[mno].selectedItem - ((awmm[mno].cn[0].it[0].iHF == 2) ? 1 : 0)].shst(2);
                }
                if (!awmm[mno].rep)
                    awmm[mno].cn[0].pc();
                awmm[mno].cm(0);
                if (awmm[mno].cll && !awmm[mno].cd) {
                    awmm[mno].cn[0].hdt(0);
                    awmm[mno].cn[0].layer2.style.pixelLeft = awmm[mno].cn[0].left = -3000;
                    awmm[mno].cn[0].layer2.style.pixelTop = awmm[mno].cn[0].top = -3000;
                }
            }
        }
        awmLSH = -10;
        awmLH = -10;
    }
}
function awmwu() {
    if (typeof (awmm) == $un)
        return;
    if (awmSubmenusFrameOffset > -9000) {
        for (var mno = 0; mno < awmm.length; mno++) {
            if (awmm[mno].cn[0].pi && awmm[mno].cn[0].pi.layer[0] != n$) {
                awmm[mno].cn[0].pi.shst(0);
                awmm[mno].cn[0].pi.sm = n$;
            }
        }
    }
    awmcm();
}
function awmDS() {
    var clientX = Mm(dBd.clientWidth, ((awmBef5) ? 0 : dEl.clientWidth));
    var clientY = Mm(dBd.clientHeight, ((awmBef5) ? 0 : dEl.clientHeight));
    var sx = 2;
    var sy = 2;
    var dd = 5;
    var snx, sny;
    if (vl != awmlssx || vt != awmlssy || vb - vt != awmLSH) {
        for (var mno = 0; mno < awmm.length; mno++) {
            var crm = awmm[mno];
            if (crm.cn[0].ft && crm.cll == 0) {
                if ((crm.dft == 4 || crm.dft == 6) && vl != awmlssx) {
                    crm.mio = 0;
                    snx = Ma(vl - awmlssx) / (vl - awmlssx);
                    if ((Mr(Ma(vl - awmlssx) / dd)) >= sx)
                        sx = Mr(Ma(vl - awmlssx) / dd);
                    if (Ma(vl - awmlssx) < sx)
                        sx = Ma(vl - awmlssx);
                    crm.cn[0].left = crm.cn[0].layer2.style.pixelLeft += snx * sx;
                }
                if ((crm.dft == 4 || crm.dft == 5) && vt != awmlssy) {
                    crm.mio = 0;
                    sny = Ma(vt - awmlssy) / (vt - awmlssy);
                    if ((Mr(Ma(vt - awmlssy) / dd)) >= sy)
                        sy = Mr(Ma(vt - awmlssy) / dd);
                    if (Ma(vt - awmlssy) < sy)
                        sy = Ma(vt - awmlssy);
                    crm.cn[0].top = crm.cn[0].layer2.style.pixelTop += sny * sy;
                }
                if (crm.dft == 8 && (vt != awmlssy || vb - vt != awmLSH)) {
                    crm.mio = 0;
                    crm.cm(1);
                    sny = Ma(vt - awmlssy) / (vt - awmlssy);
                    if ((Mr(Ma(vt - awmlssy) / dd)) >= sy)
                        sy = Mr(Ma(vt - awmlssy) / dd);
                    if (Ma(vt - awmlssy) < sy)
                        sy = Ma(vt - awmlssy);
                    if (crm.cn[0].layer2) {
                        var x = gX(crm);
                        var tmp = ((vt >= x[0] && vt <= x[1]) ? awmlssy : ((vt < x[0]) ? x[0] : x[1]));
                        crm.cn[0].top = crm.cn[0].layer2.style.pixelTop = tmp + x[2];
                        if ((tmp == x[0] && crm.mS6) || (tmp == x[1] && crm.mS7)) {
                            if (!crm.cn[0].cd) {
                                crm.cn[0].mio = 0;
                                crm.cn[0].show(0);
                            }
                        } else {
                            if (crm.cn[0].cd)
                                crm.cn[0].show(1);
                        }
                    }
                }
            }
        }
        if (vl != awmlssx)
            awmlssx += snx * sx;
        if (vt != awmlssy)
            awmlssy += sny * sy;
        if (awmLSH != vb - vt)
            awmLSH = vb - vt;
    }
}
function awmd() {
    if (vl != awmlsx || vt != awmlsy || awmLH != (vb - vt)) {
        for (var mno = 0; mno < awmm.length; mno++) {
            var crm = awmm[mno];
            if (crm.cn[0].ft && crm.cll == 0) {
                if (crm.dft) {
                    crm.mio = 0;
                    crm.cm(1);
                }
                if (crm.dft == 1 || crm.dft == 3) {
                    crm.cn[0].left = crm.cn[0].layer2.style.pixelLeft += vl - awmlsx;
                }
                if (crm.dft == 1 || crm.dft == 2) {
                    crm.cn[0].top = crm.cn[0].layer2.style.pixelTop += vt - awmlsy;
                }
                if (crm.dft == 7) {
                    if (crm.cn[0].layer2) {
                        var x = gX(crm);
                        var tmp = ((vt >= x[0] && vt <= x[1]) ? vt : ((vt < x[0]) ? x[0] : x[1]));
                        crm.cn[0].top = crm.cn[0].layer2.style.pixelTop = tmp + x[2];
                        if ((tmp == x[0] && crm.mS6) || (tmp == x[1] && crm.mS7)) {
                            if (!crm.cn[0].cd) {
                                crm.cn[0].mio = 0;
                                crm.cn[0].show(0);
                            }
                        } else {
                            if (crm.cn[0].cd)
                                crm.cn[0].show(1);
                        }
                    }
                }
            }
        }
        awmlsx = vl;
        awmlsy = vt;
        awmLH = vb - vt;
    }
    awmDS();
    if (awmm)
        for (var mno = 0; mno < awmm.length; mno++)
            if (awmm[mno])
                if (awmm[mno].cn[0].ft && awmm[mno].cll == 0)
                    awmm[mno].cn[0].pc(1);
}
function aCo() {
    vl = Mm(dBd.scrollLeft, ((awmBef5) ? 0 : dEl.scrollLeft));
    vt = Mm(dBd.scrollTop, ((awmBef5) ? 0 : dEl.scrollTop));
    viewHeight = Mm(dBd.scrollHeight, ((awmBef5) ? 0 : dEl.scrollHeight));
    vr = vl + ((awmBef5 || !dEl.clientWidth) ? dBd.clientWidth : dEl.clientWidth);
    vb = vt + ((awmBef5 || !dEl.clientHeight) ? dBd.clientHeight : dEl.clientHeight);
}
function gTE(mi) {
    if (awmm[mi].iMN && awmm[mi].elemRel == 2) {
        var i = 0;
        while (i < $D.images.length && txt != awmm[mi].iMN) {
            var obj = $D.images[i].src.split("/");
            var txt = obj[obj.length - 1];
            if (txt == awmm[mi].iMN)
                return $D.images[i];
            i++;
        }
    } else {
        if (awmm[mi].elemRel == 1)
            return $D.all(awmm[mi].posID);
    }
}
function awmOpenSubmenus(mi) {
    var xx = awmm[mi].prvS.split("-");
    if (xx.length < 2)
        return;
    if (xx[0] != awmm[mi].nm)
        return;
    for (var i = 1; i < xx.length; i++) {
        var crc = awmm[mi].cn[xx[i]];
        if (!crc.sLDA[0] || !crc.pi.ps.ft)
            return;
        crc.sLDO = true;
        crc.show(1);
        crc.pi.shst(2);
    }
}
function awmdb(mi) {
    if (awmm[mi].awmD)
        for (var i = 0; i < aIC[mi].length; i = i + 3)
            if (aIC[mi][i + 1] == 0 || aIC[mi][i + 2] == 0) {
                setTimeout("awmdb(" + mi + ")", 10);
                return;
            }
    var crc = awmm[mi].cn[0];
    crc.git();
    crc.fe();
    crc.arr();
    var CC = 'A1';
    var xx = dg('MenuWidthHeight');
    if (xx)
        xx.innerHTML = "Menu Size: " + crc.layer2.style.pixelWidth + " x " + crc.layer2.style.pixelHeight + " pixels";
    var CC = 'A2';
    if (awmm[mi].cll == 0) {
        var tmpEl = gTE(mi);
        if (awmm[mi].elemRel == 0 && (mpi || aLf() || awmprp)) {
            if (awmm[mi].dft < 7) {
                crc.show(1);
                awmOpenSubmenus(mi);
            } else {
                crc.cd = 0;
                crc.pc();
                crc.cd = 1;
            }
        } else {
            if (tmpEl) {
                x = oL(tmpEl);
                y = oT(tmpEl);
                var z = tmpEl.offsetParent;
                while (z != n$) {
                    x += oL(z);
                    y += oT(z);
                    z = z.offsetParent;
                }
                if ((y > 0 || x > 1 || !awma5 || $D.readyState == "complete") && (mpi || aLf() || awmprp)) {
                    crc.git();
                    if (awmm[mi].dft < 7) {
                        crc.show(1);
                        awmOpenSubmenus(mi);
                    } else {
                        crc.cd = 0;
                        crc.pc();
                        crc.cd = 1;
                        awmLSH = awmLH = -10;
                    }
                } else {
                    setTimeout("awmdb(" + mi + ")", 10);
                }
            } else {
                setTimeout("awmdb(" + mi + ")", 10);
            }
        }
    }
}
function awmbmm() {
    if (typeof (aIC) == $un)
        aIC = new Array();
    if (typeof (aFC$) == $un)
        aFC$ = new Array();
    if (typeof (awmImagesColl) != $un)
        aIC[this.ind] = awmImagesColl;
    aFC$[this.ind] = aFC;
    eval(aue(dA));
    aLh2();
    if (typeof (awmTarget) != $un && this.ind > 0)
        return;
    if (typeof (cKc) == $un)
        cCc = true;
    else
        cCc = !cKc(cH[cH.length - 1]);
    if (this.awmD)
        if (cH[0] != aue(cJ[0]) && cCc && cH[cH.length - 1] != cI && cH[cH.length - 1] != aue(cJ[1]))
            return;
    if (this.awmE)
        if (dH[0] != aue(dJ[0]) && dH[dH.length - 1] != aue(dJ[1]))
            this.awmE = 2;
    if (this.sUC % 2)
        $D.onmousedown = awmodmd;
    if (!is70)
        status = "." + (this.ind + 1);
    this.ght();
    this.whtd();
    awmdb(this.ind);
    if (!is70)
        status = awmdst;
    clearInterval(aCI);
    aCI = setInterval("aCo()", 25);
    clearInterval(awmdid);
    awmdid = setInterval("awmd()", 25);
    awmsoo = awmso + 1;
    if (this.ind == 0) {
        aUF = window.onunload;
        window.onunload = awmwu;
    }
}
function awmHideMenu(mNm) {
    var ml = awmm;
    if (ml) {
        var i = 0;
        $D.onmousedown = n$;
        while (i < ml.length) {
            if (ml[i].nm == mNm || mNm == n$) {
                ml[i].cn[0].cd = 0;
                ml[i].cm(1);
                ml[i].cn[0].show(0);
            }
            i++;
        }
        ml = n$;
    }
}
function aMove(o1, l1, t1, w1, h1) {
    var o2 = o1.style;
    if (l1 != n$)
        o2.pixelLeft = l1;
    if (t1 != n$)
        o2.pixelTop = t1;
    if (w1 != n$)
        o2.pixelWidth = w1;
    if (h1 != n$)
        o2.pixelHeight = h1;
}
function oL(o1) {
    return o1.offsetLeft;
}
function oT(o1) {
    return o1.offsetTop;
}
function oW(o1) {
    return o1.offsetWidth;
}
function oH(o1) {
    return o1.offsetHeight;
}
function Mr(o1) {
    return Math.round(o1);
}
function Ma(o1) {
    return Math.abs(o1);
}
function Mm(o1, o2) {
    return Math.max(o1, o2);
}
function lSC(o1, t1, w1, h1, l1) {
    o1.style.clip = "rect(" + t1 + "px," + w1 + "px," + h1 + "px," + l1 + "px)";
}
function awmShowMenu(mNm, x, y, frame) {
    var ml;
    if (arguments.length < 4 || frame == n$)
        ml = awmm;
    else {
        eval("var frex=parent." + awmSubmenusFrame);
        if (!frex)
            return;
        eval("ml=parent." + frame + ".awmm;");
    }
    if (ml) {
        var i = 0;
        while (ml[i].nm != mNm && i < ml.length - 1)
            i++;
        if (ml[i].nm == mNm) {
            if (arguments.length < 3 || x == n$ || y == n$) {
                $D.onmousedown = n$;
                ml[i].cn[0].show(1);
                setTimeout("document.onmousedown=awmodmd", 0);
            } else {
                ml[i].cn[0].pm.rep = 1;
                ml[i].cn[0].show(1, x, y);
            }
        }
        ml = n$;
    }
}
function awmHideGroup() {
    if (typeof (awmTarget) != $un)
        return;
    for (i = 0; i < awmm.length; i++) {
        awmm[i].awmHide2ID = setTimeout("awmHideMenu('" + awmm[i].nm + "');", awmhd);
    }
}
function awmShowGroup(gNm, gCr, eCr, ofX, ofY) {
    if (typeof (awmTarget) != $un)
        return;
    var tmp;
    var mNm = "";
    for (var i = 0; i < gNm.split("-").length - 1; i++)
        mNm += (i == 0) ? gNm.split("-")[0] : "-" + gNm.split("-")[i];
    gNm = gNm.split("-")[gNm.split("-").length - 1];
    clearTimeout(awmHideID);
    awmHideMenu(mNm);
    for (i = 0; i < awmm.length; i++) {
        for (j = 0; j < awmm[i].cn.length; j++) {
            if (awmm[i].cn[j].groupID == gNm)
                if (mNm == awmm[i].nm || mNm == n$)
                    tmp = awmm[i].cn[j];
            if (tmp) {
                clearTimeout(awmm[i].awmHide2ID);
                imgs = $D.all("awmAnchor-" + mNm + "-" + gNm);
                if (imgs == n$) {
                    if (gNm == "gr0") {
                        tmp.cd = 0;
                        tmp.git();
                        tmp.fe();
                        tmp.arr();
                        tmp.show(1);
                        setTimeout("document.onmousedown=awmodmd", 0);
                    }
                    return;
                }
                x = oL(imgs);
                y = oT(imgs);
                var z = imgs.offsetParent;
                while (z != n$) {
                    x += oL(z);
                    y += oT(z);
                    z = z.offsetParent;
                }
                tmp.cd = 0;
                tmp.git();
                tmp.fe();
                tmp.arr();
                if (gCr != n$ && eCr != n$) {
                    if (eCr == 1 || eCr == 2 || eCr == 6)
                        x += oW(imgs);
                    if (eCr == 2 || eCr == 3 || eCr == 7)
                        y += oH(imgs);
                    if (eCr == 5 || eCr == 7 || eCr == 8)
                        x += oW(imgs) / 2;
                    if (eCr == 4 || eCr == 6 || eCr == 8)
                        y += oH(imgs) / 2;
                    if (gCr == 1 || gCr == 2 || gCr == 6)
                        x -= oW(tmp.layer);
                    if (gCr == 2 || gCr == 3 || gCr == 7)
                        y -= oH(tmp.layer);
                    if (gCr == 5 || gCr == 7 || gCr == 8)
                        x -= oW(tmp.layer) / 2;
                    if (gCr == 4 || gCr == 6 || gCr == 8)
                        y -= oH(tmp.layer) / 2;
                } else {
                    if (tmp.pi) {
                        if (tmp.pi.ps.ct == 0) {
                            x += (oW(imgs) + tmp.swr);
                            y += tmp.alO;
                        } else {
                            x += tmp.alO;
                            y += (oH(imgs) + tmp.swr);
                        }
                    } else {
                        y += oH(imgs);
                    }
                }
                if (ofX)
                    x += ofX;
                if (ofY)
                    y += ofY;
                y = Math.min(y, vb - oH(tmp.layer));
                x = Math.min(x, vr - oW(tmp.layer));
                y = Mm(y, vt);
                x = Mm(x, vl);
                tmp.show(1, x, y);
                setTimeout("document.onmousedown=awmodmd", 0);
                return;
            }
        }
    }
}
var AWM_C = function () {
    var is55 = false, is6 = false, zR = n$, zS = true, zF = this;
    this.Apl = function (xL, xC, xR) {
        if (zS)
            Stp();
        if (is55 && (xI = Hdr(xL, xC, xR))) {
            xI.style.visibility = "visible";
        } else if (zR != n$) {
            zR.style.visibility = "hidden";
        }
    };
    this.Dsc = function (xL, xC) {
        if (is55 && (xI = Hdr(xL, xC, false))) {
            xI.style.visibility = "hidden";
        } else if (zR != n$) {
            zR.style.visibility = "visible";
        }
    };
    function Hdr(xL, xC, xR) {
        var zL = GOj(xL);
        var zC = ((oTmp = GOj(xC)) ? oTmp : $D.getElementsByTagName("body")[0]);
        if (!zL || !zC)
            return;
        var xI = dg("Ahd" + zL.id);
        if (!xI) {
            var zG = (is6) ? "filter:" + pDX + "Alpha(style=0,opacity=0);" : "";
            var zX = zL.style.zIndex;
            if (zX == "")
                zX = zL.currentStyle.zIndex;
            zX = parseInt(zX);
            if (isNaN(zX))
                return n$;
            if (zX < 2)
                return n$;
            zX--;
            var sD = "Ahd" + zL.id;
            zC.insertAdjacentHTML("afterBegin", '<iframe class="Aif" src="" id="' + sD + '" scroll="no" scrolling="no" frameborder="0" style="position:absolute;visibility:hidden;' + zG + 'border:0;top:0;left;0;width:0;height:0;background-color:#ccc;z-index:' + zX + ';"></iframe>');
            xI = dg(sD);
            SPs(xI, zL);
        } else if (xR) {
            SPs(xI, zL);
        }
        return xI;
    }
    ;
    function SPs(xI, zL) {
        xI.style.width = oW(zL) + "px";
        xI.style.height = oH(zL) + "px";
        xI.style.left = oL(zL) + "px";
        xI.style.top = oT(zL) + "px";
    }
    ;
    function GOj(vObj) {
        var oObj = n$;
        switch (typeof (vObj)) {
            case "object":
                oObj = vObj;
                break;
            case "string":
                oObj = dg(vObj);
                break;
        }
        return oObj;
    }
    ;
    function Stp() {
        is55 = (typeof (dBd.contentEditable) != $un);
        is6 = (typeof ($D.compatMode) != $un);
        if (!is55) {
            if ($D.styleSheets.length == 0)
                $D.createStyleSheet();
            var oSheet = $D.styleSheets[0];
            oSheet.addRule(".Ahd", "visibility:visible");
            zR = oSheet.rules(oSheet.rules.length - 1);
        }
        zS = false;
    }
    ;
};
var AWC = new AWM_C();/*6*/