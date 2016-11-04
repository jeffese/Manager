<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Sales'));
vetAccess('Accounts', 'Sales', 'Print');

$id = _xget('id');

$outid = _xses('OutletID');
$Payer_sql = vendorFlds("Cust", "person");
$acc_sql = vendorFlds("acc", "acc_person");
$sql = "SELECT `invoices`.*, OutletName, $vendor_sql, $Payer_sql, $acc_sql, 
    vendortypes.VendorType AS vtype, currencies.code, currencyname, catname, cur_id, status.Category
    FROM `{$_SESSION['DBCoy']}`.`invoices`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors`         ON `invoices`.EmployeeID=`vendors`.VendorID
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlets`        ON `invoices`.OutletID=`outlets`.OutletID 
    INNER JOIN `" . DB_NAME . "`.`vendortypes`         ON `invoices`.VendorType=`vendortypes`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` Cust   ON `invoices`.VendorID=`Cust`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` acc    ON `invoices`.AccountID=`acc`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`currencies`     ON `Cust`.currency=currencies.cur_id 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `invoices`.InvoiceType=classifications.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`          ON `invoices`.Status=status.CategoryID 
    WHERE `InvoiceID`=$id AND `invoices`.OutletID IN ($outid)";
$row_TSales = getDBDataRow($dbh, $sql);

$sql = "SELECT `invoicedetails`.*, serialized, `outlet`.`serials` AS allserials, ShopStock
    FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices` ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `invoicedetails`.ProductID=`items_prod`.`ProductID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`outlet` ON 
        (`invoicedetails`.ProductID=`outlet`.ProductID AND `invoices`.OutletID=`outlet`.OutletID)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`items_srv` ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    WHERE `invoices`.`InvoiceID`=$id";
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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblsales.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
            <table border="0" cellpadding="4" cellspacing="4">
              <tr>
                <td width="120" class="titles">Invoice ID:</td>
                <td class="red-normal"><b><?php echo $row_TSales['InvoiceID']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Outlet:</td>
                <td><b><?php echo $row_TSales['OutletName']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Client:</td>
                <td align="left"><table width="300" border="0" cellpadding="1" cellspacing="1">
                  <tr class="boldwhite1">
                    <td bgcolor="#000000"><?php echo $row_TSales['vtype'] ?>: </td>
                    <td align="left">&nbsp;</td>
                    <td width="100%" align="left" bgcolor="#003366"><?php echo $row_TSales['person'] ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" bgcolor="#999999"><?php echo $row_TSales['CustomerName'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Account:</td>
                <td><?php echo $row_TSales['acc_person'] ?></td>
              </tr>
              <tr>
                <td class="titles">Items:</td>
                <td><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td>Value:</td>
                    <td><?php echo $row_TSales['TotalValue'] ?></td>
                    <td>&nbsp;</td>
                    <td>Discount:</td>
                    <td><?php echo $row_TSales['TotDisc'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Discount:</td>
                <td><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><?php echo $row_TSales['Dscnt'] ?></td>
                    <td>%</td>
                    <td><strong>=&gt;</strong></td>
                    <td><?php echo $row_TSales['Discount'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Tax:</td>
                <td><table border="0" cellspacing="1" cellpadding="1">
                  <tr>
                    <td><?php echo $row_TSales['TaxRate'] ?></td>
                    <td>%</td>
                    <td>&nbsp;</td>
                    <td>Total:</td>
                    <td><?php echo $row_TSales['TotTax'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Total Value:</td>
                <td><?php echo $row_TSales['Grandvalue'] ?></td>
              </tr>
              <tr>
                <td class="titles">Currency:</td>
                <td><table border="0" cellspacing="1" cellpadding="1">
                  <tr>
                    <td><?php echo $row_TSales['currencyname'] ?></td>
                    <td><table border="0" cellspacing="2" cellpadding="2" id="xbox"<?php if ($_SESSION['COY']['currency']==$row_TSales['cur_id']) { ?> style="display:none"<?php } ?>>
                      <tr>
                        <td id="xfrom"><?php echo $row_TSales['code'] ?></td>
                        <td><?php echo $row_TSales['ExchangeFrom'] ?></td>
                        <td><strong>=&gt;</strong></td>
                        <td><?php echo $row_TShopcur['code'] ?></td>
                        <td><?php echo $row_TSales['ExchangeTo'] ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Invoice Date:</td>
                <td align="left"><?php echo $row_TSales['InvoiceDate'] ?></td>
              </tr>
              <tr>
                <td class="titles">Category:</td>
                <td><?php echo $row_TSales['catname'] ?></td>
              </tr>
              <tr>
                <td class="titles">Status: </td>
                <td><?php echo $row_TSales['Category'] ?></td>
              </tr>
              <tr>
                <td class="titles">Posted:</td>
                <td><input type="checkbox" name="Posted"<?php if ($row_TSales['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
              </tr>
              <tr>
                <td class="titles">Staff:</td>
                <td><?php echo $row_TSales['VendorName'] ?></td>
              </tr>
              <tr>
                <td width="120" class="titles">Notes:</td>
                <td><textarea name="Notes" style="width:450px" rows="3"><?php echo $row_TSales['Notes'] ?></textarea></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="Tabdet">
                  <tr class="boldwhite1">
                    <td colspan="9" align="center" nowrap="nowrap" bgcolor="#000000" class="h1">Items</td>
                  </tr>
                  <tr class="boldwhite1">
                    <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Unit Price</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">% Dsc</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Discount</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Tax</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Sales Price</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Total Value</td>
                  </tr>
                  <?php $j = 0;foreach ($TItems as $row_TItems) { ?>
                  <tr id="itm_<?php echo $j ?>">
                    <td><?php echo $row_TItems['InvoiceDetailID'] ?>
                      <input type="hidden" name="UnitPrice_<?php echo $j ?>" value="<?php echo $row_TItems['UnitPrice']; ?>" />
                      <input type="hidden" name="units_<?php echo $j ?>" id="units_<?php echo $j ?>" value="<?php echo $row_TItems['units'] ?>" />
                      <input type="hidden" name="Discount_<?php echo $j ?>" id="Discount_<?php echo $j ?>" value="<?php echo $row_TItems['Discount'] ?>" />
                      <input type="hidden" name="TaxRate_<?php echo $j ?>" id="TaxRate_<?php echo $j ?>" value="<?php echo $row_TItems['TaxRate'] ?>" /></td>
                      <input type="hidden" name="LineTotal_<?php echo $j ?>" value="<?php echo $row_TItems['LineTotal']; ?>" />
                    <td id="Name_<?php echo $j ?>"><?php echo $row_TItems['ProductName'] ?></td>
                    <td><?php echo $row_TItems['units'] ?></td>
                    <td id="UnitPrice_<?php echo $j ?>"><?php echo $row_TItems['UnitPrice'] ?></td>
                    <td><?php echo $row_TItems['Discnt'] ?></td>
                    <td><?php echo $row_TItems['Discount'] ?></td>
                    <td><?php echo $row_TItems['TaxRate'] ?></td>
                    <td id="SalePrice_<?php echo $j ?>"></td>
                    <td id="LineTotal_<?php echo $j ?>"></td>
                  </tr>
                  <?php $j++; } ?>
                </table>
                  <input name="ItmID" type="hidden" id="ItmID" value="<?php echo $j ?>" />
                  <script>var ItmID=<?php echo $j ?> </script></td>
              </tr>
              <tr>
                <td height="28" colspan="2" align="center">&nbsp;</td>
              </tr>
              <tr>
                <td height="28" colspan="2" align="center">&nbsp;</td>
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
    calItms();
    print();
});
</script>
</body>
</html>