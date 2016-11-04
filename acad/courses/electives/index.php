<?php
require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = $_access['Electives'];
vetAccess('Academics', 'Electives', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "", "", "", "", "", "", "", "");
$rec_status = 4;

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
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="../menu.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/course_elect.jpg" width="240" height="300" />
          <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/<?php echo COURSE=='Subject' ? 'lblsubjelectives' : 'lblelectives' ?>.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php')?></td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><div id="treeboxbox_tree" style="width:100%; height:400px;background-color:#f5f5f5;border :1px solid Silver;; overflow:auto;"></div></td>
              </tr>
              <tr>
                <td><link href="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxtree.css" rel="STYLESHEET" type="text/css">
				<script src="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxcommon.js"></script>
                  <script src="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxtree.js"></script>
                  <script>
                      tree=new dhtmlXTreeObject("treeboxbox_tree","100%","100%",1);
                      tree.setSkin('csh_yellowbooks');
                      tree.setImagePath("/lib/dhtmlxSuite/dhtmlxTree/codebase/imgs/csh_yellowbooks/");
                      tree.setXMLAutoLoading("clsxml.php");
                      tree.loadXML("clsxml.php?id=1");
                    
                      tree.attachEvent("onDblClick",function(id){
                          typ = tree.getUserData(id,'type');
                          if (typ == 5) {
                              par0 = tree.getParentId(id);
                              par1 = tree.getParentId(par0);
                              par2 = tree.getParentId(par1);
                              par3 = tree.getParentId(par2);
                              
                              term_id = tree.getUserData(id,'id');
                              term_cd = tree.getUserData(id,'code');
                              term = tree.getItemText(id);
                              arm_id = tree.getUserData(par0,'id');
                              arm = tree.getUserData(par0, 'code');
                              
                              cls = tree.getItemText(par3) + ' > ' + 
                                  tree.getUserData(par2, 'code') + ' > ' + 
                                  tree.getUserData(par1, 'code') +
                                  (arm.length == 0 ? '' : ' > ' + arm);
                              location.href = 'view.php?cid=' + arm_id + '&cls=' + escape(cls) + 
                                  '&tid=' + term_id + '&trm=' + escape(term) + '&tc=' + escape(term_cd);
                          } else {
                              tree.openItem(id);
                          }
                      });
                    
                  </script></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php'); ?></td>
              </tr>

            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">

          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>