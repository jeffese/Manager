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
              <td align="left" valign="top"><div id="treeboxbox_tree" style="width:100%; height:450px;background-color:#f5f5f5;border :1px solid Silver;; overflow:auto;"></div></td>
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
        tree.loadXML("clsxml.php?id=1");
		tree.attachEvent("onDblClick",function(id){
			typ = tree.getUserData(id,'type');
			if (typ == 4) {
			    par0 = tree.getParentId(id);
			    par1 = tree.getParentId(par0);
			    par2 = tree.getParentId(par1);
				
				arm = tree.getUserData(id, 'code');
				cls = tree.getItemText(par2) + ' > ' + 
					tree.getUserData(par1, 'code');
				
				var doc = parent.parent.document;
				doc.getElementById('DeptID').value = tree.getUserData(id,'id');
				doc.getElementById('prog_txt').innerHTML = cls;
				doc.getElementById('class_txt').innerHTML = tree.getUserData(par0, 'code') + ' ' + arm;
				parent.parent.GB_hide();
			} else {
				tree.openItem(id);
			}
		});
	</script>
</body>
</html>