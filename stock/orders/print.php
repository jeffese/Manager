<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Orders'));
vetAccess('Stock', 'Orders', 'Print');

$id = _xget('id');

$vendor_stf = vendorFlds("emp", "staff");
$vendor_sup = vendorFlds("vend", "sup");

$sql = "SELECT `orders`.*, currencyname, unitname, code, country.country, $vendor_stf, $vendor_sup
FROM `{$_SESSION['DBCoy']}`.`orders`
LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` emp  ON `orders`.EmployeeID=`emp`.VendorID
LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` vend ON `orders`.SupplierID=`vend`.VendorID
LEFT JOIN `{$_SESSION['DBCoy']}`.`currencies`   ON `orders`.Currency=currencies.cur_id  
LEFT JOIN `".DB_NAME."`.`country`               ON `orders`.ShipCountry=country.country_id 
WHERE `OrderID`={$id}";
$row_TOrders = getDBDataRow($dbh, $sql);

$sql = "SELECT `orderdetails`.*, serialized 
        FROM `{$_SESSION['DBCoy']}`.`orderdetails`
        INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `orderdetails`.ProductID=items_prod.ProductID  
        WHERE `OrderID`={$id}";
$TOrderDets = getDBData($dbh, $sql);

$sql = "SELECT code FROM `{$_SESSION['DBCoy']}`.`currencies` WHERE cur_id={$_SESSION['COY']['currency']}";
$row_TShopcur = getDBDataRow($dbh, $sql);

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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblorders.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
            <table border="0" cellpadding="4" cellspacing="4">
              <tr>
                <td width="120" class="titles">Order ID:</td>
                <td class="red-normal"><b><?php echo $row_TOrders['OrderID']; ?></b></td>
              </tr>
              <tr>
                <td width="120" class="titles">Supplier:</td>
                <td align="left"><?php echo $row_TOrders['sup'] ?></td>
              </tr>
              <tr>
                <td width="120" class="titles">Invoice #:</td>
                <td align="left"><?php echo $row_TOrders['PurchaseOrderNumber'] ?></td>
              </tr>
              <tr>
                <td width="120" class="titles">Staff:</td>
                <td><?php echo $row_TOrders['staff'] ?></td>
              </tr>
              <tr>
                <td class="titles">Date Ordered:</td>
                <td align="left"><?php echo $row_TOrders['OrderDate'] ?></td>
              </tr>
              <tr>
                <td width="120" class="titles">Posted:</td>
                <td><input type="checkbox" name="Posted"<?php if ($row_TOrders['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
              </tr>
              <tr>
                <td width="120" class="titles">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2" align="left"><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td colspan="5" class="h1">Info</td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Freight Charge:</td>
                    <td><?php echo $row_TOrders['FreightCharge'] ?></td>
                    <td>&nbsp;</td>
                    <td width="120" class="titles">Margin:</td>
                    <td><?php echo $row_TOrders['Margin'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Expenses:</td>
                    <td><?php echo $row_TOrders['expenses'] ?></td>
                    <td>&nbsp;</td>
                    <td width="120" class="titles">Currency:</td>
                    <td><?php echo $row_TOrders['currencyname'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Tax:</td>
                    <td><?php echo $row_TOrders['SalesTaxRate'] ?></td>
                    <td>&nbsp;</td>
                    <td width="120" class="titles">Exchange Rate:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td id="xfrom"><?php echo $row_TOrders['code'] ?></td>
                        <td><?php echo $row_TOrders['ExchangeFrom'] ?></td>
                        <td><strong>=&gt;</strong></td>
                        <td><?php echo $row_TShopcur['code'] ?></td>
                        <td><?php echo $row_TOrders['ExchangeTo'] ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Discount:</td>
                    <td><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><?php echo $row_TOrders['Dscnt'] ?></td>
                        <td>%</td>
                        <td><strong>=&gt;</strong></td>
                        <td><?php echo $row_TOrders['Discount'] ?></td>
                      </tr>
                    </table></td>
                    <td>&nbsp;</td>
                    <td width="120">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="120" class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Order Total:</td>
                    <td><?php echo $row_TOrders['OrderTotal'] ?></td>
                    <td>&nbsp;</td>
                    <td width="120" class="titles">Total Value:</td>
                    <td><?php echo $row_TOrders['TotalValue'] ?></td>
                  </tr>
                  <tr>
                    <td colspan="2" id="ordwords">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2" id="totwords">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="5" class="h1">Shipping Info</td>
                    </tr>
                  <tr>
                    <td width="140" class="titles">Shipper:</td>
                    <td align="left"><?php echo $row_TOrders['ShipName'] ?></td>
                    <td align="left">&nbsp;</td>
                    <td width="120" class="titles">Status:</td>
                    <td align="left"><?php echo $row_TOrders['ShipState'] ?></td>
                  </tr>
                  <tr>
                    <td width="140" class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td width="120" align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="140" class="titles">Shipping Method:</td>
                    <td align="left"><script language="javascript" type="text/javascript">
                                switch (<?php echo $row_TOrders['ShippingMethodID']; ?>) {
                                    case 1: document.write("Air Freight"); break;
                                    case 2: document.write("Sea Freight"); break;
                                    case 3: document.write("Parcel Service"); break;
                                    case 4: document.write("Door Delivery"); break;
                                    case 5: document.write("Others"); break;
                                    default: document.write("");
                                }</script></td>
                    <td align="left">&nbsp;</td>
                    <td width="120" valign="top" class="titles">Shipper Address:</td>
                    <td align="left"><textarea name="ShipAddress" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TOrders['ShipAddress'] ?></textarea></td>
                  </tr>
                  <tr>
                    <td width="140" class="titles">Date Required:</td>
                    <td align="left"><?php echo $row_TOrders['RequiredByDate'] ?></td>
                    <td align="left">&nbsp;</td>
                    <td width="120" class="titles">Shipper City:</td>
                    <td align="left"><?php echo $row_TOrders['ShipCity'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Date of Shipping:</td>
                    <td align="left"><?php echo $row_TOrders['ShipDate'] ?></td>
                    <td align="left">&nbsp;</td>
                    <td width="120" class="titles">Shipper Country:</td>
                    <td align="left"><?php echo $row_TOrders['country'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Expected Date of Arrival:</td>
                    <td align="left"><?php echo $row_TOrders['PromisedByDate'] ?></td>
                    <td align="left">&nbsp;</td>
                    <td width="120" class="titles">Shipper State:</td>
                    <td align="left" nowrap="nowrap"><?php echo $row_TOrders['ShipStateOrProvince'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td width="120" class="titles">Shipper Phone:</td>
                    <td><?php echo $row_TOrders['ShipPhoneNumber'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellpadding="2" cellspacing="2">
                    <tr>
                      <td class="h1">Notes</td>
                    </tr>
                    <tr>
                      <td><textarea name="Notes" rows="10" readonly="readonly" style="width:450px"><?php echo $row_TOrders['Notes'] ?></textarea></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2"></td>
</tr>
              <tr>
                <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="TabOrderdet">
                    <tr class="boldwhite1">
                      <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">#/pack</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Stock Qty</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Recv</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Pack Cost</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Line Total</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Unit Cost</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Margin</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Cal. Cost</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Sug Sellprice</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Old Selprice</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Expires</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Expiry Date</td>
                    </tr>
                    <?php $j = 0;foreach ($TOrderDets as $row_TOrderDets) { ?>
                    <tr id="OrderDet<?php echo $j ?>">
                      <td><?php echo $row_TOrderDets['OrderDetailID'] ?></td>
                      <td id="ProductName<?php echo $j ?>"><strong><?php echo $row_TOrderDets['ProductName'] ?></strong></td>
                      <td><?php echo $row_TOrderDets['Quantity'] ?></td>
                      <td><?php echo $row_TOrderDets['unitsinpack'] ?></td>
                      <td id="QtyinStock<?php echo $j ?>"><?php echo $row_TOrderDets['QtyinStock'] ?></td>
                      <td id="Received<?php echo $j ?>"><?php echo $row_TOrderDets['Received'] ?></td>
                      <td><?php echo $row_TOrderDets['UnitPrice'] ?></td>
                      <td id="linetotal<?php echo $j ?>"><?php echo number_format($row_TOrderDets['UnitPrice'] * $row_TOrderDets['Quantity'], 2) ?></td>
                      <td id="salesprice<?php echo $j ?>"><?php echo number_format($row_TOrderDets['UnitPrice'] / $row_TOrderDets['unitsinpack'], 2) ?></td>
                      <td><?php echo $row_TOrderDets['Margin'] ?></td>
                      <td id="calcost<?php echo $j ?>"><?php echo $row_TOrderDets['calcost'] ?></td>
                      <td><?php echo $row_TOrderDets['sugsell'] ?></td>
                      <td id="oldsell<?php echo $j ?>"><?php echo $row_TOrderDets['oldsell'] ?></td>
                      <td align="center"><input type="checkbox" id="Expires<?php echo $j ?>"<?php if ($row_TOrderDets['Expires'] == 1) {
                echo " checked=\"checked\"";} ?> onclick="setExpires(<?php echo $j ?>)" disabled="disabled" /></td>
                      <td><?php echo $row_TOrderDets['ExpiryDate'] ?></td>
                    </tr>
                    <?php $j++; } ?>
                  </table></td>
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
    $("#ordwords").html(NumToWords(<?php echo $row_TOrders['OrderTotal']; ?>, "<?php echo $row_TOrders['currencyname']; ?>", "<?php echo $row_TOrders['unitname']; ?>"));
    $("#totwords").html(NumToWords(<?php echo $row_TOrders['TotalValue']; ?>, "<?php echo $row_TOrders['currencyname']; ?>", "<?php echo $row_TOrders['unitname']; ?>"));
    print();
});
</script>
</body>
</html>