<?php require_once('../../scripts/init.php');
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
vetAccess('Personnel', 'Deductions', 'View');

$id = intval(_xget('id'));
$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`deductions` WHERE `VendorID`=$id";
$TDed = getDBData($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td><table border="0" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF">
      <tr>
        <td align="center" bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Batch ID</td>
        <td align="center" bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Title</td>
        <td height="10" align="center" bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Amount</td>
        <td align="center" bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Deduction</td>
        <td align="center" bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Date Time</td>
      </tr>
      <?php $j=0;
foreach ($TDed as $row_TDed) {
	$j++;
	$k = $j % 2;
	$rowdefcolor = ($k == 1) ? "#E5E5E5" : "#D5D5D5"; ?>
      <tr class="black-normal" 
	  onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor; ?>', '#CCFFCC', '#FFCC99');" 
	  onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
	  onclick="location.href='view.php?id=<?php echo $row_TDed['ded_id'] ?>'">
        <td bgcolor="<?php echo $rowdefcolor ?>"><?php echo $row_TDed['paybatchid']; ?></td>
        <td bgcolor="<?php echo $rowdefcolor ?>"><?php echo $row_TDed['Title']; ?></td>
        <td bgcolor="<?php echo $rowdefcolor ?>"><?php echo number_format($row_TDed['deduct'], 2); ?></td>
        <td bgcolor="<?php echo $rowdefcolor ?>"><?php echo number_format($row_TDed['ded'], 2); ?></td>
        <td bgcolor="<?php echo $rowdefcolor ?>"><?php echo $row_TDed['dt']; ?></td>
      </tr>
      <?php } ?>
    </table></td>
  </tr>
  <tr>
    <td align="center"><label>
      <input type="button" name="new" id="new" value="Add" onclick="location.href='new.php?id=<?php echo $id ?>'" />
    </label></td>
  </tr>
</table>
</body>
</html>