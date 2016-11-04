<?php require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Classes'));
vetAccess('Academics', 'Classes', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","edit.php?id=$id","","","","","","","index.php");
$rec_status = 1;

$sql = "SELECT `sch_class`.*, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`sch_class`
LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_class`.`cls_teacher`=`vendors`.VendorID
WHERE class_id={$_SESSION['class_id']}";
$row_TClass = getDBDataRow($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_arms` WHERE `class`={$_SESSION['class_id']}";
$TArm = getDBData($dbh, $sql);

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
<script charset="UTF-8" src="menu.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/class.jpg" alt="" width="240" height="300" />
          <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblclass.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
            <tr>
              <td class="titles">Name:</td>
              <td align="left"><?php echo $row_TClass['class_name'] ?></td>
            </tr>
            <tr>
              <td class="titles">Code:</td>
              <td align="left"><?php echo $row_TClass['class_code'] ?></td>
            </tr>
            <tr>
              <td class="titles"><?php echo LECTURER ?>:</td>
              <td><?php echo $row_TClass['VendorName'] ?></td>
            </tr>
            <tr>
              <td class="titles">Notes:</td>
              <td align="left"><textarea name="Notes" rows="2" readonly="readonly" id="Notes" style="width:300px"><?php echo $row_TClass['Notes'] ?></textarea></td>
            </tr>
            </table></td>
              </tr>
              <tr>
                <td align="center"><table border="0" cellpadding="8" cellspacing="1" bordercolor="#FFFFFF">
                  <tr>
                    <td height="10" colspan="3" align="center" bordercolor="#003300" bgcolor="#666666" class="boldwhite1"><span class="yellowtxt"><strong><?php echo ARM ?>s</strong></span></td>
                  </tr>
                  <tr>
                    <td height="10" bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Name</td>
                    <td bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Code</td>
                    <td bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Active</td>
                  </tr>
                  <?php $j=0;
foreach ($TArm as $row_TArm) {
	$j++;
	$k = $j % 2;
	$rowdefcolor = ($k == 1) ? "#E5E5E5" : "#D5D5D5"; ?>
                  <tr class="black-normal" onclick="top.leftFrame.showMod('Class <?php echo ARM ?>s', '/acad/programs/classes/arms/view.php?id=<?php echo $row_TArm['arm_id'] ?>')" onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor; ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');">
                    <td bgcolor="<?php echo $rowdefcolor ?>"><strong><?php echo $row_TArm['arm_name']; ?></strong></td>
                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>"><strong><?php echo $row_TArm['arm_code']; ?></strong></td>
                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>"><input type="checkbox" name="active" id="active" <?php if (!(strcmp($row_TArm['active'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                  </tr>
                  <?php } ?>
                </table>
                <p><a href="javascript: void(0)" onclick="top.leftFrame.showMod('Class <?php echo ARM ?>s', '/acad/programs/classes/arms/add.php')"><img src="/images/but_add.png" width="50" height="20" /></a></p></td>
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