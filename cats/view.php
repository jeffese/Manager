<?php
require_once("$vpth/scripts/init.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], 0, 0, 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[$vnm]del.php?id=$id","","","","","index.php");
$rec_status = 1;

$id = intval(_xget('id'));
$fld = isset($vPar) ? ", `tab`.$parname AS parname" : "";
$join = isset($vPar) ? "LEFT JOIN `{$_SESSION['DBCoy']}`.`$partab` tab ON `status`.`par`=`tab`.`$parid`" : "";
$sql = "SELECT `status`.* $fld
        FROM `{$_SESSION['DBCoy']}`.`status` 
        $join 
        WHERE `status`.CategoryID=$id";
$row_TCat = getDBDataRow($dbh, $sql);

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
    <td valign="top">
        <form id="Frm" name="Frm" method="post" action="">
        <table border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td></td>
            <td align="center"><?php echo catch_error($errors) ?></td>
          </tr>
      <tr>
        <td class="titles">Name:</td>
        <td align="left"><?php echo $row_TCat['Category'] ?></td>
      </tr>
      <?php if (isset($vCode)) { ?>
      <tr>
        <td class="titles">Code:</td>
        <td align="left"><?php echo $row_TCat['code'] ?></td>
      </tr>
      <?php } ?>
      <?php if (isset($vPar)) { ?>
      <tr>
        <td class="titles"><?php echo $vPar ?>:</td>
        <td><?php echo $row_TCat['parname'] ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td class="titles">In Use</td>
        <td align="left"><input type="checkbox" name="InUse"<?php if ($row_TCat['InUse'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
      </tr>
      <tr>
        <td class="titles">Notes:</td>
        <td align="left"><textarea name="Description" rows="4" readonly="readonly" id="Description" style="width:300px"><?php echo $row_TCat['Description'] ?></textarea></td>
      </tr>
      <tr>
        <td colspan="2" class="titles"><?php include("$vpth/scripts/buttonset.php"); ?></td>
        </tr>
    </table>
   	</form></td>
  </tr>
</table>
</body>
</html>