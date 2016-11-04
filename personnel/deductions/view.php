<?php require_once('../../scripts/init.php');
vetAccess('Personnel', 'Deductions', 'View');

$id = intval(_xget('id'));
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
      <td align="left"><?php echo number_format($row_TDed['ded'], 2) ?></td>
    </tr>
    <tr>
      <td class="titles">Balance:</td>
      <td align="left"><?php echo number_format($row_TDed['bal'], 2) ?></td>
    </tr>
    <tr>
      <td class="titles">DateTime:</td>
      <td align="left"><?php echo $row_TDed['dt'] ?></td>
    </tr>
    <tr>
      <td class="titles">Description:</td>
      <td align="left"><textarea name="description" rows="4" readonly="readonly" id="description" style="width:400px"><?php echo $row_TDed['description'] ?></textarea></td>
    </tr>
    <tr>
      <td class="titles">&nbsp;</td>
      <td align="center"><a href="index.php?id=<?php echo $row_TDed['VendorID'] ?>"><img src="/images/but_lst.png" width="60" height="20" border="0" /> </a> <a href="edit.php?id=<?php echo $id ?>"><img src="/images/but_edit.png" width="60" height="20" border="0" /></a></td>
    </tr>
  </table>
</form>
</body>
</html>