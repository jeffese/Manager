<?php require_once('../../../scripts/init.php');
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Students'));
vetAccess('Academics', 'Students', 'Add');
if (_xpost("MM_insert") == "Frm") {
  $sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`status` (Category, Description, cattype) VALUES (%s, %s, 'stud_status')",
                       GSQLStr(_xpost('Category'), "text"),
                       GSQLStr(_xpost('Description'), "text"));
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
        <form id="Frm" name="Frm" method="post" action="">
        <table border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td colspan="2" align="left" class="h1"><b>Student Status</b></td>
      </tr>
      <tr>
        <td></td>
        <td align="center"><?php echo catch_error($errors) ?></td>
      </tr>
      <tr>
        <td class="titles">Name:</td>
        <td align="left"><input type="text" name="Category" id="Category" style="width:300px" value="" /></td>
      </tr>
      <tr>
        <td class="titles">Description:</td>
        <td align="left"><textarea name="Description" id="Description" style="width:300px" rows="4"></textarea></td>
      </tr>
      <tr>
        <td class="titles"><input type="hidden" name="MM_insert" value="Frm" /></td>
        <td align="center"><input type="submit" name="button" id="button" value="Submit" /></td>
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