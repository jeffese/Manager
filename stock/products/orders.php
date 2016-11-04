<?php
require_once('../../scripts/init.php');

$id = intval(_xget('id'));
preOrd("prodOrd", array('', 'OrderID', 'OrderDate', 'sup', 'Quantity', 'unitsinpack', 'QtyinStock', 'UnitPrice', 'ExpiryDate', 'currentstock', 'Cleared'));

$vendor_sup = vendorFlds("vendors", "sup");

$sql = "SELECT `orderdetails`.*, OrderDate, $vendor_sup FROM `{$_SESSION['DBCoy']}`.`orderdetails`
INNER JOIN `{$_SESSION['DBCoy']}`.`orders`  ON `orderdetails`.OrderID=orders.OrderID 
INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `orders`.SupplierID=`vendors`.VendorID
WHERE ProductID={$id} AND Posted=1 {$orderval}";
$TOrderDets = getDBData($dbh, $sql);

$currentPage = 'orders.php';

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
                <td nowrap="nowrap"><?php echo setOrderTitle('Order ID', $currentPage, 1, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Date', $currentPage, 2, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Supplier', $currentPage, 3, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Qty', $currentPage, 4, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('#/Pack', $currentPage, 5, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Qty in Stock', $currentPage, 6, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('UnitPrice', $currentPage, 7, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Expiry Date', $currentPage, 8, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Current', $currentPage, 9, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Cleared', $currentPage, 10, $ord, $asc); ?></td>
              </tr>
              <?php $j=1;
	   foreach ($TOrderDets as $row_TOrderDets) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
              <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');">
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TOrderDets['OrderID'] ?></b></td>
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TOrderDets['OrderDate'] ?></b></td>
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TOrderDets['sup'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TOrderDets['Quantity'] ?></b></td>
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TOrderDets['unitsinpack'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TOrderDets['QtyinStock'] ?></b></td>
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TOrderDets['UnitPrice'] ?></b></td>
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TOrderDets['ExpiryDate'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input type="checkbox" <?php echo $row_TOrderDets['currentstock']==1? "checked=\"checked\"": ""; ?> disabled="disabled" /></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input type="checkbox" <?php echo $row_TOrderDets['Cleared']==1? "checked=\"checked\"": ""; ?> disabled="disabled" /></td>
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
