<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Programs'));
vetAccess('Academics', 'Programs', 'print');

$id = _xget('id');
$sql = "SELECT `sch_programs`.*, `schm_name`, `class_name`, Category, catname, cert_name, grade_sys
FROM `{$_SESSION['DBCoy']}`.`sch_programs` 
INNER JOIN `{$_SESSION['DBCoy']}`.`sch_schemes` ON `sch_programs`.`scheme`=`sch_schemes`.`schm_id`
INNER JOIN `{$_SESSION['DBCoy']}`.`status` ON `sch_programs`.`prog_type`=`status`.`CategoryID`
INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `sch_programs`.`department`=`classifications`.`catID`
INNER JOIN `{$_SESSION['DBCoy']}`.`sch_certificates` ON `sch_programs`.`certificate`=`sch_certificates`.`cert_id`
INNER JOIN `{$_SESSION['DBCoy']}`.`sch_grade_sys` ON `sch_programs`.`grade`=`sch_grade_sys`.`grade_sys_id`
WHERE `prog_id`={$_SESSION['prog_id']}";
$row_TProg = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script><script charset="UTF-8" src="menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/xxpix.jpg" ?>" /></td>
        <td><span class="coytxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblprograms.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
        </table>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>
              <td class="h1">&nbsp;</td>
            </tr>
            <tr>
              <td><table border="0" cellpadding="4" cellspacing="4">
                <tr>
                  <td class="titles">Name:</td>
                  <td align="left"><?php echo $row_TProg['prog_name'] ?></td>
                  </tr>
                <tr>
                  <td class="titles">Code:</td>
                  <td align="left"><?php echo $row_TProg['prog_code'] ?></td>
                  </tr>
                <tr>
                  <td class="titles">Type:</td>
                  <td><?php echo $row_TProg['Category'] ?></td>
                  </tr>
                <tr>
                  <td class="titles">Scheme:</td>
                  <td><?php echo $row_TProg['schm_name'] ?></td>
                  </tr>
                <tr>
                  <td class="titles">Department:</td>
                  <td><?php echo $row_TProg['catname'] ?></td>
                  </tr>
                <tr>
                  <td class="titles">Certificate:</td>
                  <td><?php echo $row_TProg['cert_name'] ?></td>
                  </tr>
                <tr>
                  <td class="titles">Grade System:</td>
                  <td><?php echo $row_TProg['grade_sys'] ?></td>
                  </tr>
                <tr>
                  <td class="titles">No. of <?php echo LEVEL ?>s:</td>
                  <td><?php echo $row_TProg['class_no'] ?></td>
                  </tr>
                <tr>
                  <td class="titles"><?php echo LEVEL ?> Prefix:</td>
                  <td><?php echo $row_TProg['class_pfx'] ?></td>
                  </tr>
                <tr>
                  <td width="120" class="titles">Notes:</td>
                  <td><?php echo $row_TProg['Notes'] ?></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td><table border="0" cellpadding="8" cellspacing="1" bordercolor="#FFFFFF">
                <tr>
                  <td height="10" colspan="3" align="center" bordercolor="#003300" bgcolor="#666666" class="boldwhite1"><span class="yellowtxt"><strong><?php echo $row_TProg['class_name'] ?></strong></span></td>
                  </tr>
                <tr>
                  <td height="10" bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Name</td>
                  <td bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Code</td>
                  <td bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Level</td>
                  </tr>
                <?php $j=0;
foreach ($TClass as $row_TClass) {
	$j++;
	$k = $j % 2;
	$rowdefcolor = ($k == 1) ? "#E5E5E5" : "#D5D5D5"; ?>
                <tr class="black-normal" onclick="top.leftFrame.showMod('Classes', '/acad/programs/classes/view.php?id=<?php echo $row_TClass['class_id'] ?>')" onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor; ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');">
                  <td bgcolor="<?php echo $rowdefcolor ?>"><strong><?php echo $row_TClass['class_name']; ?></strong></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>"><strong><?php echo $row_TClass['class_code']; ?></strong></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>"><strong><?php echo $row_TClass['cls_level']; ?></strong></td>
                  </tr>
                <?php } ?>
                </table></td>
            </tr>
            <tr>
              <td></td>
            </tr>

          </table>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">

          </table></td>
      </tr>
      <tr>
        <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?></span></td>
      </tr>
    </table></td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
	print();
});
</script>
</body>
</html>