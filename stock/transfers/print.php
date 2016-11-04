<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Transfers'));
vetAccess('Stock', 'Transfers', 'Print');

$id = _xget('id');

$vendor_stf = vendorFlds("emp", "staff");
$vendor_sup = vendorFlds("vend", "sup");

$sql = "SELECT `requisitions`.*, currencyname, unitname, code, country.country, $vendor_stf, $vendor_sup
FROM `{$_SESSION['DBCoy']}`.`requisitions`
LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` emp  ON `requisitions`.EmployeeID=`emp`.VendorID
LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` vend ON `requisitions`.SupplierID=`vend`.VendorID
LEFT JOIN `{$_SESSION['DBCoy']}`.`currencies`   ON `requisitions`.Currency=currencies.cur_id  
LEFT JOIN `".DB_NAME."`.`country`               ON `requisitions`.ShipCountry=country.country_id 
WHERE `RequisitID`={$id}";
$row_TTranfers = getDBDataRow($dbh, $sql);

$sql = "SELECT `req_items`.*, serialized 
        FROM `{$_SESSION['DBCoy']}`.`req_items`
        INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `req_items`.ProductID=`items_prod`.`ProductID`  
        WHERE `RequisitID`={$id}";
$TItems = getDBData($dbh, $sql);

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
                <td style="height:30px; min-width:500px; background-image:url(/images/lbltransfers.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
            <table border="0" cellpadding="4" cellspacing="4">
              <tr>
                <td width="120" class="titles">Order ID:</td>
                <td class="red-normal"><b><?php echo $row_TTranfers['RequisitID']; ?></b></td>
              </tr>
              <tr>
                <td width="120" class="titles">Supplier:</td>
                <td align="left"><?php echo $row_TTranfers['sup'] ?></td>
              </tr>
              <tr>
                <td width="120" class="titles">Invoice #:</td>
                <td align="left"><?php echo $row_TTranfers['PurchaseOrderNumber'] ?></td>
              </tr>
              <tr>
                <td width="120" class="titles">Staff:</td>
                <td><?php echo $row_TTranfers['staff'] ?></td>
              </tr>
              <tr>
                <td class="titles">Date Ordered:</td>
                <td align="left"><?php echo $row_TTranfers['ReturnDate'] ?></td>
              </tr>
              <tr>
                <td width="120" class="titles">Posted:</td>
                <td><input type="checkbox" name="Transfered"<?php if ($row_TTranfers['Transfered'] == 1) {
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
                    <td><?php echo $row_TTranfers['FreightCharge'] ?></td>
                    <td>&nbsp;</td>
                    <td width="120" class="titles">Margin:</td>
                    <td><?php echo $row_TTranfers['Margin'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Expenses:</td>
                    <td><?php echo $row_TTranfers['expenses'] ?></td>
                    <td>&nbsp;</td>
                    <td width="120" class="titles">Currency:</td>
                    <td><?php echo $row_TTranfers['currencyname'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Tax:</td>
                    <td><?php echo $row_TTranfers['SalesTaxRate'] ?></td>
                    <td>&nbsp;</td>
                    <td width="120" class="titles">Exchange Rate:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td id="xfrom"><?php echo $row_TTranfers['code'] ?></td>
                        <td><?php echo $row_TTranfers['ExchangeFrom'] ?></td>
                        <td><strong>=&gt;</strong></td>
                        <td><?php echo $row_TShopcur['code'] ?></td>
                        <td><?php echo $row_TTranfers['ExchangeTo'] ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Discount:</td>
                    <td><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><?php echo $row_TTranfers['Dscnt'] ?></td>
                        <td>%</td>
                        <td><strong>=&gt;</strong></td>
                        <td><?php echo $row_TTranfers['Discount'] ?></td>
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
                    <td><?php echo $row_TTranfers['OrderTotal'] ?></td>
                    <td>&nbsp;</td>
                    <td width="120" class="titles">Total Value:</td>
                    <td><?php echo $row_TTranfers['TotalValue'] ?></td>
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
                    <td align="left"><?php echo $row_TTranfers['ShipName'] ?></td>
                    <td align="left">&nbsp;</td>
                    <td width="120" class="titles">Status:</td>
                    <td align="left"><?php echo $row_TTranfers['ShipState'] ?></td>
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
                                switch (<?php echo $row_TTranfers['ShippingMethodID']; ?>) {
                                    case 1: document.write("Air Freight"); break;
                                    case 2: document.write("Sea Freight"); break;
                                    case 3: document.write("Parcel Service"); break;
                                    case 4: document.write("Door Delivery"); break;
                                    case 5: document.write("Others"); break;
                                    default: document.write("");
                                }</script></td>
                    <td align="left">&nbsp;</td>
                    <td width="120" valign="top" class="titles">Shipper Address:</td>
                    <td align="left"><textarea name="ShipAddress" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TTranfers['ShipAddress'] ?></textarea></td>
                  </tr>
                  <tr>
                    <td width="140" class="titles">Date Required:</td>
                    <td align="left"><?php echo $row_TTranfers['RequiredByDate'] ?></td>
                    <td align="left">&nbsp;</td>
                    <td width="120" class="titles">Shipper City:</td>
                    <td align="left"><?php echo $row_TTranfers['ShipCity'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Date of Shipping:</td>
                    <td align="left"><?php echo $row_TTranfers['ShipDate'] ?></td>
                    <td align="left">&nbsp;</td>
                    <td width="120" class="titles">Shipper Country:</td>
                    <td align="left"><?php echo $row_TTranfers['country'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Expected Date of Arrival:</td>
                    <td align="left"><?php echo $row_TTranfers['PromisedByDate'] ?></td>
                    <td align="left">&nbsp;</td>
                    <td width="120" class="titles">Shipper State:</td>
                    <td align="left" nowrap="nowrap"><?php echo $row_TTranfers['ShipStateOrProvince'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    <td width="120" class="titles">Shipper Phone:</td>
                    <td><?php echo $row_TTranfers['ShipPhoneNumber'] ?></td>
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
                      <td><textarea name="Notes" rows="10" readonly="readonly" style="width:450px"><?php echo $row_TTranfers['Notes'] ?></textarea></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2"></td>
</tr>
              <tr>
                <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="TabTransDet">
                    <tr class="boldwhite1">
                      <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">#/pack</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Stock Qty</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Recv</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Unit Price</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Line Total</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Sales Price</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Margin</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Cal. Cost</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Sug Sellprice</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Old Selprice</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Expires</td>
                      <td align="center" nowrap="nowrap" bgcolor="#000000">Expiry Date</td>
                    </tr>
                    <?php $j = 0;foreach ($TItems as $row_TItems) { ?>
                    <tr id="TransDet<?php echo $j ?>">
                      <td><?php echo $row_TItems['ProductID'] ?></td>
                      <td id="ProductName<?php echo $j ?>"><strong><?php echo $row_TItems['ProductName'] ?></strong></td>
                      <td><?php echo $row_TItems['Quantity'] ?></td>
                      <td><?php echo $row_TItems['unitsinpack'] ?></td>
                      <td id="ShopStock<?php echo $j ?>"><?php echo $row_TItems['ShopStock'] ?></td>
                      <td id="Received<?php echo $j ?>"><?php echo $row_TItems['Received'] ?></td>
                      <td><?php echo $row_TItems['UnitPrice'] ?></td>
                      <td id="linetotal<?php echo $j ?>"><?php echo number_format($row_TItems['UnitPrice'] * $row_TItems['Quantity'], 2) ?></td>
                      <td id="salesprice<?php echo $j ?>"><?php echo number_format($row_TItems['UnitPrice'] / $row_TItems['unitsinpack'], 2) ?></td>
                      <td><?php echo $row_TItems['Margin'] ?></td>
                      <td id="calcost<?php echo $j ?>"><?php echo $row_TItems['calcost'] ?></td>
                      <td><?php echo $row_TItems['sugsell'] ?></td>
                      <td id="oldsell<?php echo $j ?>"><?php echo $row_TItems['oldsell'] ?></td>
                      <td align="center"><input type="checkbox" id="Expires<?php echo $j ?>"<?php if ($row_TItems['Expires'] == 1) {
                echo " checked=\"checked\"";} ?> onclick="setExpires(<?php echo $j ?>)" disabled="disabled" /></td>
                      <td><?php echo $row_TItems['ExpiryDate'] ?></td>
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
    $("#ordwords").html(NumToWords(<?php echo $row_TTranfers['OrderTotal']; ?>, "<?php echo $row_TTranfers['currencyname']; ?>", "<?php echo $row_TTranfers['unitname']; ?>"));
    $("#totwords").html(NumToWords(<?php echo $row_TTranfers['TotalValue']; ?>, "<?php echo $row_TTranfers['currencyname']; ?>", "<?php echo $row_TTranfers['unitname']; ?>"));
    print();
});
</script>
</body>
</html>