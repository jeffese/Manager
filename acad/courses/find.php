<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Courses'));
vetAccess('Academics', 'Courses', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print ~ 12 List
$buttons_links = array("","","","","","","","","","","frmcourse","","index.php");
$rec_status = 0;

$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_POST["category_name"])) {
	header("Location: index.php");
	exit;
}

$sql = "SELECT catID, category_id, catname FROM `{$_SESSION['DBCoy']}`.`classifications` WHERE catype=1 AND catID<>{$_SESSION['course_id']} ORDER BY `catname`";
$TPar = getDBData($dbh, $sql);

$TCourseType = getCat('coursecat');
$TLecturer = getVendor(5, 1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Find <?php echo COURSE ?></title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="240" valign="top"><img src="/images/courses.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/<?php echo COURSE=='Subject' ? 'lblsubjects' : 'lblcourses' ?>.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          </table>
          <form action="index.php" method="post" name="frmcourse" id="frmcourse">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">Find</td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><input name="course_name" type="text" id="course_name" size="32" style="width:300px" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Code:</td>
                    <td><input name="course_code" type="text" id="course_code" size="20" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td><select name="course_type" id="course_type">
                      <option value="">Select</option>
                      <?php foreach ($TCourseType as $row_TCourseType) { ?>
                      <option value="<?php echo $row_TCourseType['CategoryID'] ?>"><?php echo $row_TCourseType['Category'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td><select name="department" id="department">
                      <option value="">Select</option>
                      <?php foreach ($TPar as $row_TPar) { ?>
                      <option value="<?php echo $row_TPar['catID'] ?>"><?php echo $row_TPar['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles"><?php echo LECTURER ?>:</td>
                    <td><select name="lecturer" id="lecturer">
                      <option value="">Select</option>
                      <?php foreach ($TLecturer as $row_TLecturer) { ?>
                      <option value="<?php echo $row_TLecturer['VendorID'] ?>"><?php echo $row_TLecturer['VendorName'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Description:</td>
                    <td id="catview"><textarea name="description" rows="3" id="description" style="width:300px"></textarea></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" id="Notes" style="width:300px"></textarea></td>
                    </tr>
                  </table></td>
              </tr>

            </table>
            <input type="hidden" name="MM_insert" value="frmcourse" />
            <?php include('../../scripts/buttonset.php')?>
          </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>            </tr>
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