<?php
require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Grades'));
vetAccess('Academics', 'Grades', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmgrdsys","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmgrdsys") {
	$sql = sprintf("UPDATE `%s`.`sch_grade_sys` SET `grade_sys`=%s, `min_pass`=%s, `Notes`=%s WHERE grade_sys_id=%s",
					   $_SESSION['DBCoy'],
                       GSQLStr(_xpost('grade_sys'), "text"),
                       GSQLStr(_xpost('min_pass'), "int"),
                       GSQLStr(_xpost('Notes'), "text"),
                       $_SESSION['grade_sys_id']);
	$update = runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_grade_sys` WHERE `grade_sys_id`={$_SESSION['grade_sys_id']}";
$row_TGradsys = getDBDataRow($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_grades` WHERE `grd_sys`={$_SESSION['grade_sys_id']} ORDER BY max DESC";
$TGrad = getDBData($dbh, $sql);

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
["grade_sys", "", 
["req", "Enter Grade System"]],
["min_pass", "", 
["req", "Select Min. Pass Grade"]]
]
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
        <td width="240" valign="top"><img src="/images/grades.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblgrades.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmgrdsys" id="frmgrdsys">
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
                    <td class="titles">Grade System:</td>
                    <td align="left"><input name="grade_sys" type="text" id="grade_sys" value="<?php echo $row_TGradsys['grade_sys'] ?>" size="32" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Min. Pass Grade:</td>
                    <td><select name="min_pass" id="min_pass">
                      <?php foreach ($TGrad as $row_TGrad) { ?>
                      <option value="<?php echo $row_TGrad['grade_id'] ?>" <?php if (!(strcmp($row_TGradsys['min_pass'], $row_TGrad['grade_id']))) { echo "selected=\"selected\""; }?>><?php echo $row_TGrad['grade_code'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" id="Notes" style="width:300px"><?php echo $row_TGradsys['Notes'] ?></textarea></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmgrdsys" />
            <input type="hidden" name="grade_sys_id" value="<?php echo $row_TGradsys['grade_sys_id']; ?>" />
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