<?php require_once('../../scripts/init.php');
vetAccess('Personnel', 'Deductions', 'Add');

$id = intval(_xget('id'));
if (_xpost("MM_insert") == "Frm") {
    $id = intval(_xpost('staffid'));
    $val = GSQLStr(_xpost('deduct'), "doublev");
    $bal = floatval(getDBDataFldkey($dbh, 'vendors', 'VendorID', 'amtbal', $id)) - $val;
    $amt = "$val";
    $sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`deductions` (`Title`, `VendorID`, `deduct`, `ded`, `bal`, `accbal`, `description`) 
                    VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GSQLStr(_xpost('Title'), "text"),
                       $id,
                       $amt,
                       GSQLStr(_xpost('ded'), "double"),
                       $amt,
                       $bal,
                       GSQLStr(_xpost('description'), "text"));
    $ran = runDBQry($dbh, $sql);	
	
    if ($ran > 0) {
        $recid = mysqli_insert_id($dbh);
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`deductions` SET `par_id`=$recid WHERE `ded_id`=$recid";
	runDBQry($dbh, $sql);
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET `amtbal`=$amt WHERE `VendorID`=$id";
        runDBqry($dbh, $sql);
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
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
</head>

<body>
<form id="Frm" name="Frm" method="post" action="">
  <table border="0" align="center" cellpadding="4" cellspacing="4">
    <tr>
      <td></td>
      <td align="center"><?php echo catch_error($errors) ?></td>
    </tr>
    <tr>
      <td class="titles">Title:</td>
      <td align="left"><input type="text" name="Title" id="Title" style="width:300px" value="" /></td>
    </tr>
    <tr>
      <td class="titles">Amount:</td>
      <td align="left"><input type="text" name="deduct" id="deduct" style="width:100px" value="" onchange="numme(this, 0)" /></td>
    </tr>
    <tr>
      <td class="titles">Deduction:</td>
      <td align="left"><input type="text" name="ded" id="ded" style="width:100px" value="" onchange="numme(this, 0)" /></td>
    </tr>
    <tr>
      <td class="titles">Description:</td>
      <td align="left"><textarea name="description" id="description" style="width:300px" rows="4"></textarea></td>
    </tr>
    <tr>
      <td class="titles"><input type="hidden" name="MM_insert" value="Frm" /><input type="hidden" name="staffid" value="<?php echo $id ?>" /></td>
      <td align="center"><input type="submit" name="button" id="button" value="Submit" />
.
  <input type="button" name="but" id="but" value="Cancel" onclick="location.href='index.php'" /></td>
    </tr>
  </table>
</form>
</body>
</html>