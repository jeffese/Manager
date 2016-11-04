<?php require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Classes'));
vetAccess('Academics', 'Classes', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmclass","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmclass") {
  $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`sch_class` SET class_name=%s, class_code=%s, cls_teacher=%s, Notes=%s WHERE class_id=%s",
                       GSQLStr(_xpost('class_name'), "text"),
                       GSQLStr(_xpost('class_code'), "text"),
                       GSQLStr(_xpost('cls_teacher'), "int"),
                       GSQLStr(_xpost('Notes'), "text"),
                       $_SESSION['class_id']);
	$ran = runDBQry($dbh, $sql);
	if ($ran == 1) {
		$sql = "UPDATE `{$_SESSION['DBCoy']}`.`sch_class`
        INNER JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `sch_class`.`class_id`=`sch_arms`.`class`
		SET `arm_teacher`=`cls_teacher` WHERE class_id={$_SESSION['class_id']} AND `arm_teacher`=0";
		runDBQry($dbh, $sql);
	}
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_class` WHERE class_id={$_SESSION['class_id']}";
$row_TClass = getDBDataRow($dbh, $sql);

$TLecturer = getVendor(5, 1);

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
	var arrFormValidation=
	[
	["class_name", "", 
["req", "Enter Class Name"]],
	["class_code", "", 
["req", "Enter Class Code"]]
	];
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
        <td width="240" valign="top"><img src="/images/class.jpg" alt="" width="240" height="300" /></td>
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmclass" id="frmclass">
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
                    <td align="left"><input type="text" name="class_name" id="class_name" style="width:200px" value="<?php echo $row_TClass['class_name'] ?>" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Code:</td>
                    <td align="left"><input type="text" name="class_code" id="class_code" style="width:100px" value="<?php echo $row_TClass['class_code'] ?>" /></td>
                    </tr>
                  <tr>
                    <td class="titles"><?php echo LECTURER ?>:</td>
                    <td><select name="cls_teacher" id="cls_teacher">
                      <option value="">Select</option>
                      <?php foreach ($TLecturer as $row_TLecturer) { ?>
                      <option value="<?php echo $row_TLecturer['VendorID'] ?>" <?php if (!(strcmp($row_TClass['cls_teacher'], $row_TLecturer['VendorID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TLecturer['VendorName'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Notes:</td>
                    <td align="left"><textarea name="Notes" id="Notes" style="width:300px" rows="2"><?php echo $row_TClass['Notes'] ?></textarea></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php')?></td>
              </tr>

            </table>
<input type="hidden" name="MM_update" value="frmclass" />
            <input type="hidden" name="class_id" value="<?php echo $row_TClass['class_id'] ?>" />
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