<?php
require_once('../../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = $_access['Classes'];
vetAccess('Academics', 'Classes', 'print');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "", "", "", "", "", "", "", "");
$rec_status = 4;

$sql = "SELECT `sess_id`, `sess_name`
        FROM `{$_SESSION['DBCoy']}`.`sch_sessions`
        ORDER BY `active` DESC";
$TSessions = getDBData($dbh, $sql);

$sess_id = intval(_xget('sess_id'));
$_SESSION['rep_session'] = $TSessions[$sess_id]['sess_id'];
$_SESSION['rep_sess'] = $TSessions[$sess_id]['sess_name'];
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
<script charset="UTF-8" src="../../../programs/classes/menu.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/reports.jpg" width="240" height="300" />
          <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lbl_reports.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td><?php include('../../../../scripts/buttonset.php')?></td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1"><label for="report"></label>
                  <table border="0" cellspacing="2" cellpadding="2">
                    <tr>
                      <td>Report:</td>
                      <td><select name="report" id="report">
                        <option value="1">End of <?php echo TERM ?></option>
                      </select></td>
                      <td><input type="hidden" name="class" id="class" /></td>
                      <td>Session:</td>
                      <td><select name="session" id="session" onchange="catnamesel(this)">
                        <?php foreach ($TSessions as $row_TSessions) { ?>
                        <option value="<?php echo $row_TSessions['sess_id'] ?>" <?php if (!(strcmp($sess_id, $row_TSessions['sess_id']))) { echo "selected=\"selected\""; }?>><?php echo $row_TSessions['sess_name'] ?></option>
                        <?php } ?>
                      </select></td>
                      <td>&nbsp;</td>
                      <td>Class:</td>
                      <td class="black-normal" id="cls_str" style="border:1px #0F0 ridge; background-color:#FFF">Select a Class</td>
                      <td><input type="hidden" name="term" id="term" /></td>
                      <td><?php echo TERM ?>:</td>
                      <td class="black-normal" id="trm_str" style="border:1px #0F0 ridge; background-color:#FFF">Select a <?php echo TERM ?>
                    </td></tr>
                </table></td>
              </tr>
              <tr>
                <td><div id="treeboxbox_tree" style="width:100%; height:400px;background-color:#f5f5f5;border :1px solid Silver;; overflow:auto;"></div></td>
              </tr>
              <tr>
                <td align="center" class="h1"><link href="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxtree.css" rel="STYLESHEET" type="text/css">
				<script src="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxcommon.js"></script>
                  <script src="/lib/dhtmlxSuite/dhtmlxTree/codebase/dhtmlxtree.js"></script>
                  <script>
				  
					  var term_id,term_cd,term,arm_id,arm,rpt;
                              
                      tree=new dhtmlXTreeObject("treeboxbox_tree","100%","100%",1);
                      tree.setSkin('csh_yellowbooks');
                      tree.setImagePath("/lib/dhtmlxSuite/dhtmlxTree/codebase/imgs/csh_yellowbooks/");
                      tree.setXMLAutoLoading("repxml.php");
                      tree.loadXML("repxml.php?id=1");
                    
                      tree.attachEvent("onClick",function(id){
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
							  rpt = $('#report').val();
                              
                              cls = //tree.getItemText(par3) + ' > ' + 
                                  tree.getUserData(par2, 'code') + ' > ' + 
                                  tree.getUserData(par1, 'code') +
                                  (arm.length == 0 ? '' : ' > ' + arm);
                              $('#cls_str').html(cls);
                              $('#trm_str').html(term);
                              $('#class').val(arm_id);
                              $('#term').val(term_id);
                          }
                      });
                    
                  </script><a href="javascript: void(0)" onclick="if ($('#class').val().length==0) {alert('You need to select a class and a term')} else {location.href='term_report.php?cid='+arm_id+'&tid='+term_id+'&cls='+cls+'&trm='+term+'&rpt='+rpt}"><img src="/images/but_generate.png" width="80" height="20" /></a></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td><?php include('../../../../scripts/buttonset.php')?></td>
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