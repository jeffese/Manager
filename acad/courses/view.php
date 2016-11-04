<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Courses'));
vetAccess('Academics', 'Courses', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 1);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[".COURSE."]del.php?id=$id","","","find.php","return GB_showCenter('". COURSE ." Info', 'print.php?id=$id', 600,600)","index.php");
$rec_status = 1;

$sql = "SELECT `sch_courses`.*, `catname` AS dept, Category, $vendor_sql
FROM `{$_SESSION['DBCoy']}`.`sch_courses` 
INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `sch_courses`.`department`=`classifications`.`catID`
INNER JOIN `{$_SESSION['DBCoy']}`.`status` ON `sch_courses`.`course_type`=`status`.`CategoryID` 
LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_courses`.`lecturer`=`vendors`.VendorID
WHERE `course_id`={$_SESSION['course_id']}";
$row_TCourse = getDBDataRow($dbh, $sql);
DeptButs($row_TCourse['department']);

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
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
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
          <td width="240" valign="top"><img src="/images/courses.jpg" alt="" width="240" height="300" />
          <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/<?php echo COURSE=='Subject' ? 'lblsubjects' : 'lblcourses' ?>.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
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
                    <td><select name="lstclass" size="10" id="lstclass">
                      <?php foreach ($TClass as $row_TClass) { ?>
                      <option value="<?php echo $row_TClass['arm_id'] ?>"><?php echo $row_TClass['armlist'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Description:</td>
                    <td><textarea name="description" rows="3" readonly="readonly" id="description" style="width:300px"><?php echo $row_TCourse['description'] ?></textarea></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" readonly="readonly" id="Notes" style="width:300px"><?php echo $row_TCourse['Notes'] ?></textarea></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php'); ?></td>
              </tr>

            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>