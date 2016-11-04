<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Courses'));
vetAccess('Academics', 'Courses', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, $access['Print'], 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmcourse","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmcourse") {
	$sql = sprintf("UPDATE IGNORE `%s`.`sch_courses` SET `course_name`=%s, `course_code`=%s, `course_type`=%s, `department`=%s, `lecturer`=%s, `classes`=%s, `description`=%s, `Notes`=%s WHERE course_id=%s",
                       $_SESSION['DBCoy'],
                       GSQLStr(_xpost('course_name'), "text"),
                       GSQLStr(_xpost('course_code'), "text"),
                       GSQLStr(_xpost('course_type'), "int"),
                       GSQLStr(_xpost('department'), "int"),
                       GSQLStr(_xpost('lecturer'), "int"),
                       GSQLStr(_xpost('classes'), "text"),
                       GSQLStr(_xpost('description'), "text"),
                       GSQLStr(_xpost('Notes'), "text"),
                       $_SESSION['course_id']);
	$update = runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_courses` WHERE `course_id`={$_SESSION['course_id']}";
$row_TCourse = getDBDataRow($dbh, $sql);

$TPar = getClassify(1);
$TCourseType = getCat('coursecat');
$TLecturer = getVendor(5, 1);

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
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=	[
	["course_name", "", 
            ["req", "Enter <?php echo COURSE ?> Name"]],
	["course_code", "", 
            ["req", "Enter <?php echo COURSE ?> Code"]],
	["course_type", "", 
            ["req", "Select <?php echo COURSE ?> Type"]],
	["department", "", 
            ["req", "Select Department"]],
	["lecturer", "", 
            ["req", "Select <?php echo COURSE ?> Lecturer"]]
    ];
	
    function setlist(lst, lid) {
        var x=lst.length;
        str = "";
        for (var i=0; i<x; i++) {
            if (lst.options[i].value != ''){
                str += '|' + lst.options[i].value;
            }
        }
        lid.value = str.substr(1);
    }

</script>
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
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmcourse" id="frmcourse">
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
                    <td align="left"><input name="course_name" type="text" id="course_name" value="<?php echo $row_TCourse['course_name'] ?>" size="32" style="width:300px" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Code:</td>
                    <td><input name="course_code" type="text" id="course_code" value="<?php echo $row_TCourse['course_code'] ?>" size="20" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td><select name="course_type" id="course_type">
                      <option value="">Select</option>
                      <?php foreach ($TCourseType as $row_TCourseType) { ?>
                      <option value="<?php echo $row_TCourseType['CategoryID'] ?>" <?php if (!(strcmp($row_TCourse['course_type'], $row_TCourseType['CategoryID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TCourseType['Category'] ?></option>
                      <?php } ?>
                      </select>
                      <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('<?php echo COURSE ?> Types', '/acad/tools/coursecat/index.php', 480,520)" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td><select name="department" id="department">
                      <option value="">Select</option>
                      <?php foreach ($TPar as $row_TPar) { ?>
                      <option value="<?php echo $row_TPar['catID'] ?>" <?php if (!(strcmp($row_TCourse['department'], $row_TPar['catID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TPar['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles"><?php echo LECTURER ?>:</td>
                    <td><select name="lecturer" id="lecturer">
                      <option value="">Select</option>
                      <?php foreach ($TLecturer as $row_TLecturer) { ?>
                      <option value="<?php echo $row_TLecturer['VendorID'] ?>" <?php if (!(strcmp($row_TCourse['lecturer'], $row_TLecturer['VendorID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TLecturer['VendorName'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Classes Offering:</td>
                    <td><table border="0" cellspacing="4" cellpadding="4">
                      <tr>
                        <td rowspan="3"><select name="lstclass" size="10" id="lstclass">
                          <?php foreach ($TClass as $row_TClass) { ?>
                          <option value="<?php echo $row_TClass['arm_id'] ?>"><?php echo $row_TClass['armlist'] ?></option>
                          <?php } ?>
                          </select></td>
                        <td><input type="button" name="btadd" id="btadd" value="Add" onclick="return GB_showCenter('Select Classes', '/acad/courses/catpick.php?chk='+frmcourse.classes.value, 480,520)" /></td>
                        </tr>
                      <tr>
                        <td><input type="button" name="btdel" id="btdel" value="Del" onclick="frmcourse.lstclass.remove(frmcourse.lstclass.selectedIndex); setlist(frmcourse.lstclass, frmcourse.classes)" /></td>
                        </tr>
                      <tr>
                        <td><input type="hidden" name="classes" id="classes" value="<?php echo $row_TCourse['classes'] ?>" /></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Description:</td>
                    <td><textarea name="description" rows="3" id="description" style="width:300px"><?php echo $row_TCourse['description'] ?></textarea></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" id="Notes" style="width:300px"><?php echo $row_TCourse['Notes'] ?></textarea></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmcourse" />
            <input name="course_id" type="hidden" id="course_id" value="<?php echo $row_TCourse['course_id']; ?>" />
          </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>            </tr>
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>