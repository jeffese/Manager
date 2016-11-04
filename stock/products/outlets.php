<?php
require_once('../../scripts/init.php');

$id = intval(_xget('id'));
preOrd("outl", array('', 'OutletName', 'ShopStock', 'shelf', 'Shoplevel', 'Shopstkcntdate'));

$sql = "SELECT outlet.*, OutletName, status.Category AS shelf
        FROM `{$_SESSION['DBCoy']}`.items_prod 
        INNER JOIN `{$_SESSION['DBCoy']}`.outlet           ON items_prod.ProductID=outlet.ProductID
        LEFT JOIN `{$_SESSION['DBCoy']}`.outlets           ON outlet.OutletID=outlets.OutletID
        LEFT  JOIN `{$_SESSION['DBCoy']}`.`status`         ON `outlet`.Shopshelf = status.CategoryID 
        WHERE items_prod.ProductID={$id} {$orderval}";

$TProducts = getDBData($dbh, $sql);
$currentPage = 'outlets.php';

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
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="boldwhite1"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
              <tr align="center" bgcolor="#666666" class="boldwhite1">
                <td nowrap="nowrap"><?php echo setOrderTitle('Outlet', $currentPage, 1, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Stock', $currentPage, 2, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Shelf', $currentPage, 3, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Re-Order Level', $currentPage, 4, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Last Stock Date', $currentPage, 5, $ord, $asc); ?></td>
              </tr>
              <?php $j=1;
	   foreach ($TProducts as $row_TProducts) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
              <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');">
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['OutletName'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['ShopStock'] ?></b></td>
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['shelf'] ?></b></td>
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['Shoplevel'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['Shopstkcntdate'] ?></b></td>
              </tr>
              <?php $j++;} ?>
            </table></td>
          </tr>

        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
