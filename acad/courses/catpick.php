<html>
<head>
<link href="/css/style001.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<link href="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxtree.css" rel="STYLESHEET" type="text/css">
<script src="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxcommon.js"></script>
<script src="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxtree.js"></script>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#000000">
  <tr>
<td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td width="100%" valign="top" bgcolor="#FFFFFF"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
  <tr>
    <td>      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        
        <tr>
          <td width="100%" style="border:solid 2px #003300"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="darkgrey">
            <tr valign="baseline">
              <td align="left" valign="top" nowrap></td>
            </tr>
            <tr valign="baseline">
              <td align="center"><input type="button" name="Button" value="Pick Selected" onClick="setsel()"></td>
              </tr>
            <tr valign="baseline">
              <td align="left" valign="top"><div id="treeboxbox_tree" style="width:100%; height:360px;background-color:#f5f5f5;border :1px solid Silver;; overflow:auto;"></div></td>
                  </tr>
            <tr>
              <td align="left" valign="top" nowrap></td>
                  </tr>
            <tr valign="baseline" bgcolor="#006699" >
              <td align="left" valign="top" nowrap></td>
                  </tr>
            <tr valign="baseline">
              <td align="left" valign="top" nowrap></td>
                  </tr>
            <tr valign="baseline">
              <td align="left" valign="top" nowrap></td>
                  </tr>
            <tr valign="baseline" bgcolor="#006699" >
              <td align="left" valign="top" nowrap></td>
                  </tr>
            <tr valign="baseline">
              <td align="center" valign="top" nowrap></td>
                </tr>
            </table></td>
          </tr>
      </table></td>
  </tr>
</table></td></tr></table></td></tr></tr>
</table>
    <script>
        tree=new dhtmlXTreeObject("treeboxbox_tree","100%","100%",1);
        tree.setSkin('csh_yellowbooks');
        tree.setImagePath("/lib/dhtmlxSuite/dhtmlxTree/codebase/imgs/csh_yellowbooks/");
        tree.setXMLAutoLoading("clsxml.php");
        tree.loadXML("clsxml.php?id=1&chk=<?php echo $_GET['chk'] ?>");
        tree.attachEvent("onClick",function(id){
            typ = tree.getUserData(id,'type');
            if (typ == 4) {
                img = tree.isItemChecked(id) ? 'iconUncheckAll.gif' : 'iconCheckAll.gif';
                tree.setItemImage2(id, img, img, img);
                tree.setCheck(id, tree.isItemChecked(id) ? 0 : 1);
            }
        });
        
        function setsel() {
            var sel = tree.getAllChecked();
            if (sel == '') {
                alert('No Class selected');
            } else {
                var lst=parent.parent.document.frmcourse.lstclass;
                var lid=parent.parent.document.frmcourse.classes;
                
                keys = sel.split(',');
                var cnt = keys.length;
                var ids = new Array(cnt);
                var Vids = new Array();
                var Vitms = new Array();
                var Kids = new Array();
                
                var del = tree.getAllUnchecked();
                var deys = del.split(',');
                
                var cat = new Array(cnt);
                var ln = lst.length;
                while (lst.length > 0) {
                    Vids.push(lst.options[0].value);
                    Vitms.push(lst.options[0].text);
                    lst.remove(0);
                }
                
                roll:
                for (i=0; i<cnt; i++) {
                    ids[i] = tree.getUserData(keys[i], 'id');
                    for (j=0; j<ln; j++) {
                        if (Vids[j] == ids[i])
                            continue roll;
                    }
                    parid = tree.getParentId(keys[i]);
                    gparid = tree.getParentId(parid);
                    ggparid = tree.getParentId(gparid);
                    arm = tree.getUserData(keys[i], 'code');
                    cat[i] = tree.getItemText(ggparid) + ' > ' + 
                        tree.getUserData(gparid, 'code') + ' > ' + 
                        tree.getUserData(parid, 'code') +
                        (arm.length == 0 ? '' : ' > ' + arm);
                    
                    Vids.push(ids[i]);
                    Vitms.push(htmlspecialchars_decode(cat[i]));
                }
                l = 0;
                kill:
                for (j=0; j<Vids.length; j++) {
                    skip:
                    for (k=0; k<deys.length; k++) {
                        if (tree.getUserData(deys[k],'type') != 4)
                            continue skip;
                        if (tree.getUserData(deys[k], 'id') == Vids[j])
                            continue kill;
                    }
                    lst.options[l++] = new Option(Vitms[j], Vids[j], false, false);
                    Kids.push(Vids[j]);
                }
                
                lid.value = Kids.join('|');
                parent.parent.GB_hide();
            }
        }

	</script>
</body>
</html>