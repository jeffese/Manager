<?php require_once('../../scripts/init.php');
vetAccess('Personnel', 'Deductions', 'Edit');

$id = intval(_xget('id'));
if (_xpost("MM_update") == "Frm") {
  $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`deductions` SET `ded`=%s, `description`=%s WHERE ded_id=%s",
                       GSQLStr(_xpost('ded'), "double"),
                       GSQLStr(_xpost('description'), "text"),
                        $id);
	runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`deductions` WHERE `ded_id`=$id";
$row_TDed = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="Frm" name="Frm" method="post" action="">
  <table border="0" align="center" cellpadding="4" cellspacing="4">
    <tr>
      <td></td>
      <td align="center"><?php echo catch_error($errors) ?></td>
    </tr>
    <tr>
      <td class="titles">ID:</td>
      <td align="left"><?php echo $row_TDed['ded_id'] ?></td>
    </tr>
    <tr>
      <td class="titles">Batch ID:</td>
      <td align="left"><?php echo $row_TDed['paybatchid'] ?></td>
    </tr>
    <tr>
      <td class="titles">Title:</td>
      <td align="left"><?php echo $row_TDed['Title'] ?></td>
    </tr>
    <tr>
      <td class="titles">Amount:</td>
      <td align="left"><?php echo number_format($row_TDed['deduct'], 2) ?></td>
    </tr>
    <tr>
      <td class="titles">Deduction:</td>
      <td align="left"><input type="text" name="ded" id="ded" style="width:100px" value="<?php echo $row_TDed['ded'] ?>" onchange="numme(this, 0)" /></td>
    </tr>
    <tr>
      <td class="titles">DateTime:</td>
      <td align="left"><?php echo $row_TDed['dt'] ?></td>
    </tr>
    <tr>
      <td class="titles">Description:</td>
      <td align="left"><textarea name="description" id="description" style="width:400px" rows="4"><?php echo $row_TDed['description'] ?></textarea></td>
    </tr>
    <tr>
      <td class="titles"><input type="hidden" name="MM_update" value="Frm" /></td>
      <td align="center"><input type="submit" name="button" id="button" value="Submit" />
      .
      <input type="button" name="but" id="but" value="Cancel" onclick="location.href='view.php'" /></td>
    </tr>
  </table>
</form>
</body>
</html>