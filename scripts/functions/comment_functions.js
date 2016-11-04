// JavaScript Document
var waitstr = "<div style='display=none; background-color:#303030; width:100%; height:200px; padding-top: 76px' id='load' align='center'><img src='/images/load_comment.gif' width='48' height='48' /></div>";
var tab_id, item_id, tmchk;

function vetcmt(doc, frm) {
    if (frm.com_body.value == "") {
        alert("Your Comment is blank, please type in a message");
        frm.com_body.focus();
    } else if (frm.captcha.value == "") {
        alert("Please type in the validation text");
        frm.captcha.focus();
    } else {
        doc.getElementById('frm').style.display = 'none';
        doc.getElementById('load').style.display = 'block';
        frm.submit();
    }
}

function hideit(itm) {
    $(itm).hide(800);
}

function setfrm(frm, pid, lvl, cid, bdy, tab, itm) {
    if (undefined==document.getElementById("tbox"+cid).contentDocument.getElementById('com_body'+cid)) {//
        pre = "<link href='/css/style.css' rel='stylesheet' type='text/css' /><link href='/css/style001.css' rel='stylesheet' type='text/css' /><div id='frm'><form action='/scripts/functions/comments_actions.php' method='post' name='frmcmt'><table id='tarea"+cid+"' bgcolor='#999999' width='100%' border='0' cellspacing='0' cellpadding='2'><tr><td class='Yellow-normal'><strong>Write your comment here</strong><a class='red-normal' href='javascript: void(0)' onclick='parent.hideit(\"#tbox"+cid+"\")' style='float:right' >[x]Close</a></td></tr><tr><td><textarea name='com_body' id='com_body"+cid+"' style='width:100%; height:100px;' onchange='parent.trimval(this)'>"+bdy+"</textarea></td></tr><tr><td><table border='0' cellspacing='0' cellpadding='0'><tr><td>";
        post = "</td></tr><tr><td align='center'><input type='button' name='button' id='button' value='Submit' onClick='parent.vetcmt(document, this.form)' /><input name='xpost' type='hidden' id='xpost' value='"+frm+"' /><input name='tabid' type='hidden' id='tabid' value='"+tab+"' /><input name='itemid' type='hidden' id='itemid' value='"+itm+"' /><input name='pid' type='hidden' id='pid' value='"+pid+"' /><input name='level' type='hidden' id='level' value='"+lvl+"' /><input name='cid' type='hidden' id='cid' value='"+cid+"' /></td></tr></table></td></tr></table></form></div><div style='display:none; background-color:#303030; width:100%; height:200px; padding-top: 76px' id='load' align='center'><img src='/images/load_comment.gif' width='48' height='48' /></div>";
	
        loadhtml_merge("/scripts/functions/bot_chk.php", "cmtform('tbox"+cid+"', ", pre, post);
    }
    $("#tbox"+cid).show(800);
}

function cmtform(elm, docstr){
    win = document.getElementById(elm).contentDocument;
    win.write(docstr);
    $("#"+elm).show(800);
}

function delcmt(id) {
    win = document.getElementById('tbox'+id);
    win.contentDocument.write(waitstr);
    $("#tbox"+id).show(800);
    win.src = "/scripts/functions/comments_actions.php?x=0&id="+id;
}

function setStage(){
    setfrm('new', 0, '0', 0, '', tab_id, item_id);
    jvscript = document.getElementById('js').innerHTML;
    eval(jvscript);
}

function chkloaded(){
    if (document.getElementById('tbox0')!=undefined) {
        clearInterval(tmchk);
        setStage();
    }
}