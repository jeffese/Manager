<?php
require_once("../../../scripts/init.php");

$sql = "SELECT WarantyID, WarantyCode FROM `{$_SESSION['DBCoy']}`.`warranty` ORDER BY WarantyCode";
$TCat = getDBData($dbh, $sql);

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
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="frm_tl"></td>
    <td class="frm_tp"></td>
    <td class="frm_tr"></td>
  </tr>
  <tr>
    <td class="frm_lf"></td>
    <td valign="top" nowrap="nowrap" bgcolor="#CFCFCF"><table border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td align="left" class="h1">Warrantees</td>
        </tr>
      <tr>
        <td><table border="0" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td height="10" bordercolor="#003300" bgcolor="#666666" class="boldwhite1"></td>
            </tr>
          <?php $j=0;
foreach ($TCat as $row_TCat) {
	$j++;
	$k = $j % 2;
	$rowdefcolor = ($k == 1) ? "#E5E5E5" : "#D5D5D5"; ?>
          <tr class="black-normal" 
	  onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor; ?>', '#CCFFCC', '#FFCC99');" 
	  onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
	  onclick="location.href='view.php?id=<?php echo $row_TCat['WarantyID'] ?>'">
            <td bgcolor="<?php echo $rowdefcolor ?>"><?php echo $row_TCat['WarantyCode']; ?></td>
            </tr>
          <?php } ?>
          </table></td>
        </tr>
      <tr>
        <td align="center"><label>
          <input type="button" name="new" id="new" value="Add" onclick="location.href='new.php'" />
          </label></td>
        </tr>
    </table></td>
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