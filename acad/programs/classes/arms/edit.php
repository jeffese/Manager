<?php require_once('../../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Classes'));
vetAccess('Academics', 'Classes', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmarm","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmarm") {
  $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`sch_arms` SET arm_name=%s, arm_code=%s, arm_teacher=%s, active=%s, Notes=%s WHERE arm_id=%s",
                       GSQLStr(_xpost('arm_name'), "text"),
                       GSQLStr(_xpost('arm_code'), "text"),
                       GSQLStr(_xpost('arm_teacher'), "int"),
                       _xpostchk('active'),
                       GSQLStr(_xpost('Notes'), "text"),
                       $_SESSION['arm_id']);
	runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_arms` WHERE arm_id={$_SESSION['arm_id']}";
$row_TArm = getDBDataRow($dbh, $sql);

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
	["arm_name", "", 
["req", "Enter <?php echo ARM ?> Name"]],
	["arm_code", "", 
["req", "Enter <?php echo ARM ?> Code"]]
	];
</script>
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
          <td width="240" valign="top"><img src="/images/arms.jpg" alt="" width="240" height="300" />
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
                <td><form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmarm" id="frmarm"><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
      <tr>
        <td class="titles">Name:</td>
        <td align="left"><input type="text" name="arm_name" id="arm_name" style="width:200px" value="<?php echo $row_TArm['arm_name'] ?>" /></td>
      </tr>
      <tr>
        <td class="titles">Code:</td>
        <td align="left"><input name="arm_code" type="text" id="arm_code" style="width:100px" value="<?php echo $row_TArm['arm_code'] ?>" /></td>
      </tr>
      <tr>
        <td class="titles"><?php echo LECTURER ?>:</td>
        <td><select name="arm_teacher" id="arm_teacher">
          <option value="">Select</option>
          <?php foreach ($TLecturer as $row_TLecturer) { ?>
          <option value="<?php echo $row_TLecturer['VendorID'] ?>" <?php if (!(strcmp($row_TArm['arm_teacher'], $row_TLecturer['VendorID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TLecturer['VendorName'] ?></option>
          <?php } ?>
        </select></td>
      </tr>
      <tr>
        <td class="titles">Active:</td>
        <td align="left"><input type="checkbox" name="active" id="active" <?php if (!(strcmp($row_TArm['active'], 1))) { echo "checked=\"checked\""; } ?> /></td>
      </tr>
      <tr>
        <td class="titles">Notes:</td>
        <td align="left"><textarea name="Notes" id="Notes" style="width:300px" rows="4"><?php echo $row_TArm['Notes'] ?></textarea></td>
      </tr>
      <tr>
        <td class="titles"><input type="hidden" name="MM_update" value="frmarm" />
          <input type="hidden" name="arm_id" value="<?php echo $row_TArm['arm_id'] ?>" /></td>
        <td align="center">&nbsp;</td>
      </tr>
    </table>
   	</form></td>
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