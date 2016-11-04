<?php
require_once("../../../scripts/init.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
vetAccess('Stock', 'Products', 'Edit');

if (_xpost("MM_update") == "Frm") {
    $id = intval(_xpost('WarantyID'));
    $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`warranty` SET WarantyCode=%s, `PeriodType`=%s, 
                    `Duration`=%s, Warantee=%s WHERE WarantyID=%s",
                       GSQLStr(_xpost('WarantyCode'), "text"),
                       GSQLStr(_xpost('PeriodType'), "int"),
                       GSQLStr(_xpost('Duration'), "int"),
                       GSQLStr(_xpost('Warantee'), "text"),
                       $id);
	runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}
$id = intval(_xget('id'));

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`warranty` WHERE WarantyID={$id}";
$row_TWar = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["WarantyCode", "", 
            ["req", "Enter Name"]]
    ];
</script>
</head>

<body>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="frm_tl"></td>
    <td class="frm_tp"></td>
    <td class="frm_tr"></td>
  </tr>
  <tr>
    <td class="frm_lf"></td>
    <td valign="top" nowrap="nowrap" bgcolor="#CFCFCF">
        <form id="Frm" name="Frm" method="post" action="" onsubmit="return validateFormPop(arrFormValidation)">
        <table border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td colspan="2" align="left" class="h1">Warrantees</td>
      </tr>
      <tr>
        <td class="titles">Name:</td>
        <td align="left"><input type="text" name="WarantyCode" id="WarantyCode" style="width:300px" value="<?php echo $row_TWar['WarantyCode'] ?>" /></td>
      </tr>
      <tr>
        <td class="titles">Duration:</td>
        <td align="left"><input name="Duration" type="text" style="width:50px" value="<?php echo $row_TWar['Duration'] ?>" />
          <select name="PeriodType">
            <option value="0"<?php echo $row_TWar['PeriodType']==0 ? ' selected=\"selected\"' : ''; ?>></option>
            <option value="1"<?php echo $row_TWar['PeriodType']==1 ? ' selected=\"selected\"' : ''; ?>>Day(s)</option>
            <option value="2"<?php echo $row_TWar['PeriodType']==2 ? ' selected=\"selected\"' : ''; ?>>Week(s)</option>
            <option value="3"<?php echo $row_TWar['PeriodType']==3 ? ' selected=\"selected\"' : ''; ?>>Month(s)</option>
            <option value="4"<?php echo $row_TWar['PeriodType']==4 ? ' selected=\"selected\"' : ''; ?>>Year(s)</option>
          </select></td>
      </tr>
      <tr>
        <td class="titles">Description:</td>
        <td align="left"><textarea name="Warantee" style="width:300px" rows="4"><?php echo $row_TWar['Warantee'] ?></textarea></td>
      </tr>
      <tr>
        <td class="titles"><input type="hidden" name="MM_update" value="Frm" />
            <input type="hidden" name="WarantyID" value="<?php echo $row_TWar['WarantyID'] ?>" /></td>
        <td align="center"><input type="submit" name="button" id="button" value="Update" />
            <input type="button" name="cancel" id="cancel" value="Cancel" onclick="location.href='view.php?id=<?php echo $row_TWar['WarantyID'] ?>'" /></td>
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
