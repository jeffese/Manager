<?php require_once('../../../../scripts/init.php');





$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_grades` WHERE grade_id={$_SESSION['grade_id']}";
$row_TGrad = getDBDataRow($dbh, $sql);

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
        <form id="frmgrad" name="frmgrad" method="post" action="">
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
              <td align="left"><?php echo $row_TGrad['grade'] ?></td>
            </tr>
            <tr>
              <td class="titles">Code:</td>
              <td align="left"><?php echo $row_TGrad['grade_code'] ?></td>
            </tr>
            <tr>
              <td class="titles">Min Score:</td>
              <td align="left"><?php echo $row_TGrad['min'] ?></td>
            </tr>
            <tr>
              <td class="titles">Max Score:</td>
              <td align="left"><?php echo $row_TGrad['max'] ?></td>
            </tr>
            <tr>
              <td class="titles">Notes:</td>
              <td align="left"><textarea name="Notes" rows="4" readonly="readonly" id="Notes" style="width:300px"><?php echo $row_TGrad['Notes'] ?></textarea></td>
            </tr>
            <tr>
              <td class="titles">&nbsp;</td>
              <td align="center"><table border="0" cellspacing="4" cellpadding="4">
                <tr align="center">
                  <td><a href="index.php"><img src="/images/but_lst.png" width="60" height="20" border="0" /></a></td>
                  <td><a class="boldwhite1" href="edit.php?id=$id"><img src="/images/but_edit.png" alt="Edit" width="60" height="20" border="0" /></a></td>
                  <td><a class="boldwhite1" href="javascript: void(0)" onclick="if (confirm('Are you sure you want to delete this entry?')) document.location='del.php?id=$id'"><img src="/images/but_del.png" alt="Delete" width="60" height="20" border="0" /></a></td>
                </tr>
              </table></td>
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