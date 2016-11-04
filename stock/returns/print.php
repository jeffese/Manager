<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Returns'));
vetAccess('Stock', 'Returns', 'Print');

$id = _xget('id');

$vendor_stf = vendorFlds("emp", "staff");
$vendor_sup = vendorFlds("vend", "sup");

$vendor_supo = vendorFlds("VendorSup", "sup");
$sql = "SELECT `orderreturns`.*, ExchangeFrom, ExchangeTo, `currencies`.code AS cur, 
    `currencies`.currencyname, `currencies`.unitname, coycur.code AS coycod, $vendor_sql, $vendor_supo 
    FROM `{$_SESSION['DBCoy']}`.`orderreturns` 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `orderreturns`.EmployeeID=`vendors`.VendorID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`orders` ON `orderreturns`.OrderID = orders.OrderID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `VendorSup` ON `orders`.SupplierID = VendorSup.VendorID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`currencies`   ON `orders`.Currency=currencies.cur_id
    LEFT JOIN `{$_SESSION['DBCoy']}`.`currencies` coycur  ON `orders`.ShopCurrency=coycur.cur_id
    WHERE `OrderRetID`={$id}";
$row_TReturns = getDBDataRow($dbh, $sql);
$_SESSION['rets_ordid'] = $row_TReturns['OrderID'];

$sql = "SELECT `orderreturndet`.*, `orderdetails`.ProductName, 
    `orderdetails`.UnitPrice/`Quantity` AS `SalePrice`, `units`/`orderdetails`.`unitsinpack` AS `qty` 
    FROM `{$_SESSION['DBCoy']}`.`orderreturndet` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `orderreturndet`.OrderDetailID=orderdetails.OrderDetailID
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `orderdetails`.ProductID=`items_prod`.`ProductID` 
    WHERE `OrderRetID`={$id}";
$TOrderRetDets = getDBData($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script type="text/javascript" src="script.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/xxpix.jpg" ?>" /></td>
        <td><span class="coytxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblreturns.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
            <table border="0" cellpadding="4" cellspacing="4">
              <tr>
                <td width="120" class="titles">Return ID:</td>
                <td class="red-normal"><b><?php echo $row_TReturns['OrderRetID']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Order ID:</td>
                <td class="red-normal"><b><?php echo $row_TReturns['OrderID']; ?></b></td>
              </tr>
              <tr>
                <td width="120" class="titles">Supplier:</td>
                <td align="left"><?php echo $row_TReturns['sup'] ?></td>
              </tr>
              <tr>
                <td class="titles">Staff:</td>
                <td><?php echo $row_TReturns['VendorName'] ?></td>
              </tr>
              <tr>
                <td class="titles">Return Date:</td>
                <td align="left"><?php echo $row_TReturns['ReturnDate'] ?></td>
              </tr>
              <tr>
                <td class="titles">Exchange Rate:</td>
                <td><table border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td id="xfrom"><?php echo $row_TReturns['cur'] ?></td>
                    <td><?php echo $row_TReturns['ExchangeFrom'] ?></td>
                    <td><strong>=&gt;</strong></td>
                    <td><?php echo $row_TReturns['coycod'] ?></td>
                    <td><?php echo $row_TReturns['ExchangeTo'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Total Value:</td>
                <td align="left"><table border="0" cellspacing="1" cellpadding="1">
                  <tr>
                    <td><?php echo $row_TReturns['cur'] ?></td>
                    <td><?php echo $row_TReturns['TotalValue'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td width="120" class="titles">Posted:</td>
                <td><input type="checkbox" name="Posted"<?php if ($row_TReturns['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
              </tr>
              <tr>
                <td width="120" class="titles">&nbsp;</td>
                <td><table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
                  <tr>
                    <td class="bo_tl"></td>
                    <td class="bo_tp"></td>
                    <td class="bo_tr"></td>
                  </tr>
                  <tr>
                    <td rowspan="2" class="bo_lf"></td>
                    <td align="left" class="bo_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td nowrap="nowrap">Shipping Info</td>
                        <td><div style="float:right"></div></td>
                      </tr>
                    </table></td>
                    <td rowspan="2" class="bo_rt"></td>
                  </tr>
                  <tr>
                    <td class="bo_center"><table width="100%" border="0" cellspacing="2" cellpadding="2" id="bx_tax">
                      <tr>
                        <td><table border="0" cellspacing="4" cellpadding="4">
                          <tr>
                            <td width="140" class="titles">Shipper:</td>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="titles">Shipping Method:</td>
                            <td align="left"><script language="javascript" type="text/javascript">
                                switch (<?php echo $row_TReturns['ShippingMethodID']; ?>) {
                                    case 1: document.write("Air Freight"); break;
                                    case 2: document.write("Sea Freight"); break;
                                    case 3: document.write("Parcel Service"); break;
                                    case 4: document.write("Door Delivery"); break;
                                    case 5: document.write("Others"); break;
                                    default: document.write("");
                                }</script></td>
                          </tr>
                          <tr>
                            <td class="titles">Date of Shipping:</td>
                            <td align="left"><?php echo $row_TReturns['ShipDate'] ?></td>
                          </tr>
                          <tr>
                            <td valign="top" class="titles">Shipper Address:</td>
                            <td align="left"><?php echo $row_TReturns['ShipAddress'] ?></td>
                          </tr>
                          <tr>
                            <td class="titles">&nbsp;</td>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="titles">Freight Charge:</td>
                            <td><?php echo $row_TReturns['FreightCharge'] ?></td>
                          </tr>
                          <tr>
                            <td class="titles">Expenses:</td>
                            <td><?php echo $row_TReturns['Expenses'] ?></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="bo_bl"></td>
                    <td class="bo_bt"></td>
                    <td class="bo_br"></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Notes:</td>
                <td><?php echo $row_TReturns['Notes'] ?></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="TabOrderdet">
                  <tr class="boldwhite1">
                    <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Units</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Sales Price</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Line Total</td>
                  </tr>
                  <?php $j = 0;foreach ($TOrderRetDets as $row_TOrderRetDets) { ?>
                  <tr id="RetDet<?php echo $j ?>">
                    <td><?php echo $row_TOrderRetDets['OrderDetailID'] ?></td>
                    <td id="ProductName<?php echo $j ?>"><strong><?php echo $row_TOrderRetDets['ProductName'] ?></strong></td>
                    <td id="Qty"><?php echo $row_TOrderRetDets['qty'] ?></td>
                    <td><?php echo $row_TOrderRetDets['units'] ?></td>
                    <td id="SalePrice<?php echo $j ?>"><?php echo $row_TOrderRetDets['SalePrice'] ?></td>
                    <td id="linetotal<?php echo $j ?>"><?php echo $row_TOrderRetDets['SalePrice']*$row_TOrderRetDets['units'] ?></td>
                  </tr>
                  <?php $j++; } ?>
                </table>
                  <script>var OrdDetID=<?php echo $j ?> </script></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="4" cellpadding="4">

          </table></td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
    $("#ordwords").html(NumToWords(<?php echo $row_TReturns['OrderTotal']; ?>, "<?php echo $row_TReturns['currencyname']; ?>", "<?php echo $row_TReturns['unitname']; ?>"));
    $("#totwords").html(NumToWords(<?php echo $row_TReturns['TotalValue']; ?>, "<?php echo $row_TReturns['currencyname']; ?>", "<?php echo $row_TReturns['unitname']; ?>"));
    print();
});
</script>
</body>
</html>