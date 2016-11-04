<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Courses'));
vetAccess('Academics', 'Courses', 'print');

$sql = "SELECT `sch_courses`.*, `catname` AS dept, Category, $vendor_sql
FROM `{$_SESSION['DBCoy']}`.`sch_courses` 
INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `sch_courses`.`department`=`classifications`.`catID`
INNER JOIN `{$_SESSION['DBCoy']}`.`status` ON `sch_courses`.`course_type`=`status`.`CategoryID` 
INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_courses`.`lecturer`=`vendors`.VendorID
WHERE `course_id`={$_SESSION['course_id']}";
$row_TCourse = getDBDataRow($dbh, $sql);

$armlst = str_replace("|", ",", $row_TCourse['classes']);
$armlst = strlen($armlst) == 0 ? '0' : $armlst;
$sql = "SELECT arm_id, CONCAT(category_name, ' > ', prog_code, ' > ', class_code, IF(arm_code='', '', ' > '), arm_code) AS armlist
FROM `{$_SESSION['DBCoy']}`.`sch_arms` 
INNER JOIN `{$_SESSION['DBCoy']}`.`sch_class` ON `sch_arms`.`class`=`sch_class`.`class_id`
INNER JOIN `{$_SESSION['DBCoy']}`.`sch_programs` ON `sch_class`.`program`=`sch_programs`.`prog_id`
INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `sch_programs`.`department`=`classifications`.`catID`
WHERE `arm_id` IN ($armlst) ORDER BY armlist";
$TClass = getDBData($dbh, $sql);

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
                <td style="height:30px; min-width:500px; background-image:url(/images/<?php echo COURSE=='Subject' ? 'lblsubjects' : 'lblcourses' ?>.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><?php echo $row_TCourse['course_name'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Code:</td>
                    <td><?php echo $row_TCourse['course_code'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td><?php echo $row_TCourse['Category'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td><?php echo $row_TCourse['dept'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles"><?php echo LECTURER ?>:</td>
                    <td><?php echo $row_TCourse['VendorName'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Classes Offering:</td>
                    <td>
                      <?php foreach ($TClass as $row_TClass) {
						  echo $row_TClass['armlist'], "<br />";
                      } ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Description:</td>
                    <td><?php echo $row_TCourse['description'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><?php echo $row_TCourse['Notes'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
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
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
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