<?php
require_once("../scripts/init.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    if (window.innerWidth<720) {
        $('#subpx').hide();
    }
});
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><div id="treeboxbox_tree" style="width:100%; height:700px; background-color:#f5f5f5;border :1px solid Silver; overflow:auto;"></div></td>
              </tr>
              <tr>
                <td><link href="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxtree.css" rel="STYLESHEET" type="text/css">
				<script src="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxcommon.js"></script>
                  <script src="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxtree.js"></script>
                <script>
			tree=new dhtmlXTreeObject("treeboxbox_tree","100%","100%",1);
			tree.setSkin('dhx_skyblue');
			tree.setImagePath("/lib/dhtmlxSuite/dhtmlxTree/codebase/imgs/csh_books/");
			tree.setXMLAutoLoading("docxml.php");
			tree.loadXML("docxml.php?id=1");
                        tree.attachEvent("onDblClick",function(id){
                            var catid = tree.getUserData(id,'typ');
                            if (catid > 0)
                                location.href="docview.php?id=" + catid;
			});
	              </script></td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
          </table></td>
        </tr>
      </table>
</body>
</html>