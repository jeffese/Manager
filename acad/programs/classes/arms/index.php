<?php require_once('../../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = $_access['Classes'];
vetAccess('Academics', 'Classes', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 1, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "", "", "", "", "", "", "", "");
$rec_status = 4;

$From = "FROM `{$_SESSION['DBCoy']}`.`sch_arms`
INNER JOIN `{$_SESSION['DBCoy']}`.`sch_class` ON `sch_arms`.`class`=`sch_class`.`class_id`";

$sql = "SELECT `sch_arms`.*, `class_name` {$From} ORDER BY `class_name`";

$currentPage = 'index.php';
$maxRows_TArm = 10;

$TabArray = 'TArm';
require_once (ROOT . '/scripts/fetchdata.php');

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
          <td width="240" valign="top"><img src="/images/arms.jpg" width="240" height="300" />
          <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblarms.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" class="boldwhite1">
                          <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Class</td>
            <td height="10" bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Name</td>
            <td bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Code</td>
            <td bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Active</td>
            </tr>
          <?php $j=0;
foreach ($TArm as $row_TArm) {
	$j++;
	$k = $j % 2;
	$rowdefcolor = ($k == 1) ? "#E5E5E5" : "#D5D5D5"; ?>
          <tr class="black-normal" 
	  onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor; ?>', '#CCFFCC', '#FFCC99');" 
	  onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
	  onclick="location.href='view.php?id=<?php echo $row_TArm['arm_id'] ?>'">
            <td bgcolor="<?php echo $rowdefcolor ?>"><?php echo $row_TArm['class_name']; ?></td>
            <td bgcolor="<?php echo $rowdefcolor ?>"><?php echo $row_TArm['arm_name']; ?></td>
            <td align="center" bgcolor="<?php echo $rowdefcolor ?>"><?php echo $row_TArm['arm_code']; ?></td>
            <td align="center" bgcolor="<?php echo $rowdefcolor ?>"><input type="checkbox" name="active" id="active" <?php if (!(strcmp($row_TArm['active'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
            </tr>
          <?php } ?>
          </table></td>
                            </tr>

                          </table></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
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
                      tree.setXMLAutoLoading("../clsxml.php");
                      tree.loadXML("../clsxml.php?id=1");
                    
                      tree.attachEvent("onDblClick",function(id){
                          typ = tree.getUserData(id,'type');
                          if (typ > 3) {
                              itm = tree.getUserData(id,'id');
                              location.href = 'view.php?id=' + itm;
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
                <td><?php include('../../../../scripts/buttonset.php'); ?></td>
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