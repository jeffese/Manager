<?php require_once('../../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Grades'));
vetAccess('Academics', 'Grades', 'Add');

if (_xpost("MM_insert") == "frmgrad") {
  $sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`sch_grades` (`grade`, `grade_code`, `grd_sys`, `min`, `max`, `Notes`) VALUES (%s, %s, %s, %s, %s, %s)",
                       GSQLStr(_xpost('grade'), "text"),
                       GSQLStr(_xpost('grade_code'), "text"),
					   $_SESSION['grade_sys_id'],
                       GSQLStr(_xpost('min'), "int"),
                       GSQLStr(_xpost('max'), "int"),
                       GSQLStr(_xpost('Notes'), "text"));
	$ran = runDBQry($dbh, $sql);	
	
    if ($ran>0) {
        $recid = mysqli_insert_id($dbh);
        header("Location: view.php?id=$recid");
        exit;
    }
}

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
	["grade", "", 
["req", "Enter Grade Name"]],
	["grade_code", "", 
["req", "Enter Grade Code"]],
	["min", "", 
["req", "Enter Minimum Score"]],
	["max", "", 
["req", "Enter Maximum Score"]]
	];
</script>
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="frm_tl"></td>
    <td class="frm_tp"></td>
    <td class="frm_tr"></td>
  </tr>
  <tr>
    <td class="frm_lf"></td>
    <td valign="top" nowrap="nowrap" bgcolor="#CFCFCF">
        <form id="frmgrad" name="frmgrad" method="post" onsubmit="return validateFormPop(arrFormValidation)" action="">
          <table border="0" cellspacing="4" cellpadding="4">
            <tr>
              <td colspan="2" align="left" class="h1"><b>Grade Info</b></td>
            </tr>
            <tr>
              <td></td>
              <td align="center"><?php echo catch_error($errors) ?></td>
            </tr>
            <tr>
              <td class="titles">Grade:</td>
              <td align="left"><input type="text" name="grade" id="grade" style="width:200px" /></td>
            </tr>
            <tr>
              <td class="titles">Code:</td>
              <td align="left"><input name="grade_code" type="text" id="grade_code" style="width:100px" /></td>
            </tr>
            <tr>
              <td class="titles">Min Score:</td>
              <td align="left"><input name="min" type="text" id="min" size="10" /></td>
            </tr>
            <tr>
              <td class="titles">Max Score:</td>
              <td align="left"><input name="max" type="text" id="max" size="10" /></td>
            </tr>
            <tr>
              <td class="titles">Notes:</td>
              <td align="left"><textarea name="Notes" id="Notes" style="width:300px" rows="4"></textarea></td>
            </tr>
            <tr>
              <td class="titles"><input type="hidden" name="MM_insert" value="frmgrad" /></td>
              <td align="center"><input type="submit" name="button" id="button" value="Submit" />
              <input type="button" name="but" id="but" value="Back" onclick="location.href='index.php'" /></td>
            </tr>
          </table>
    </form></td>
    <td background="/images/xbox_rt.png"></td>
  </tr>
  <tr>
    <td class="frm_bl"></td>
    <td class="frm_bt"></td>
    <td class="frm_br"></td>
  </tr>
</table>
</body>
</html>