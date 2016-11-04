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
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`         ON `invoices`.EmployeeID=`vendors`.VendorID
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlets`        ON `invoices`.OutletID=`outlets`.OutletID 
    INNER JOIN `" . DB_NAME . "`.`vendortypes`         ON `invoices`.VendorType=`vendortypes`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` Cust   ON `invoices`.VendorID=`Cust`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` acc    ON `invoices`.AccountID=`acc`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`currencies`     ON `Cust`.currency=currencies.cur_id 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `invoices`.InvoiceType=classifications.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`          ON `invoices`.Status=status.CategoryID 
    WHERE `InvoiceID`=$id AND `invoices`.OutletID IN ($outid)";
$row_TSales = getDBDataRow($dbh, $sql);

$sql = "SELECT DISTINCT `serials` FROM `{$_SESSION['DBCoy']}`.`invoicedetails` WHERE `InvoiceID`=$id";
$TVehs = getDBData($dbh, $sql);

$sub_sql = "SELECT AssetID, `license`, licenceno, Brand, modelno, partno, SalvageValue, serialno, Model, DepreciationValue, 
        `auto_categories`.category_name, `category_name` AS `vtype`
    FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
    INNER JOIN `{$_SESSION['DBCoy']}`.`assets`              ON `invoicedetails`.serials=`assets`.AssetID
    INNER JOIN `{$_SESSION['DBCoy']}`.`licenses`            ON `assets`.`desgtype`=`licenses`.`lic_typ`
    INNER JOIN `{$_SESSION['DBCoy']}`.`auto_categories`     ON `assets`.`SalvageValue`=`auto_categories`.`CatID`
    WHERE `InvoiceID`=$id AND AssetID=";

$sql = "SELECT `invoicedetails`.*, serialized, `outlet`.`serials` AS allserials, ShopStock
    FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`    ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`items_prod`   ON `invoicedetails`.ProductID=`items_prod`.`ProductID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`outlet`       ON 
            (`invoicedetails`.ProductID=`outlet`.ProductID AND `invoices`.OutletID=`outlet`.OutletID)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`items_srv`    ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    WHERE `invoices`.`InvoiceID`=$id AND `invoicedetails`.`serials`=";

ob_start();
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
<script type="text/javascript" src="/lib/jquery-barcode/jquery-barcode.js"></script>  
<script type="text/javascript" src="/custom/sales/script.js"></script>
<script type="text/javascript">
window.onload = function() {
    calItms(true);
    $("#ownerbar").barcode("<?php echo $row_TSales['VendorID']; ?>", "code128",{barWidth:3, barHeight:50});
};
</script>
<style>
    body {
        margin: 0px;
    }
.info {    font-family: Arial Black;
    font-size: 12px;
    font-weight:bold;
    color: #999999;
    text-align:left;
}
</style>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <?php 
  foreach ($TVehs as $row_TVehs) { 
    $TItems = getDBData($dbh, $sql . $row_TVehs['serials']);
    $row_TSubs = getDBDataRow($dbh, $sub_sql . $row_TVehs['serials']);
  ?>
  <tr>
    <td align="center"><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/xxpix.jpg" ?>" /></td>
  </tr>
  <tr>
    <td align="center"><span class="coyrcpttxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="/images/lblInvoice.png" width="150" height="30" /></td>
              </tr>
              <tr>
                <td><table width="100%" border="1" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="2" bordercolor="#CCC" bgcolor="#CCC"></td>
                  </tr>
                </table></td>
              </tr>
              </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="4">
              <tr>
                <td class="titles">Invoice ID:</td>
                <td align="left" class="red-normal"><b><?php echo $row_TSales['InvoiceID']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Station:</td>
                <td align="left"><b><?php echo $row_TSales['OutletName']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">Owner ID:</td>
                <td align="left"><strong><?php echo $row_TSales['VendorID']; ?></strong></td>
              </tr>
              <tr>
                <td class="titles">Owner Name:</td>
                <td align="left"><strong><?php echo $row_TSales['person'] ?></strong></td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">eVRC NUMBER:</td>
                <td align="left"><b><?php echo $row_TSubs['AssetID']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Vehicle Reg. No.:</td>
                <td align="left"><b><?php echo $row_TSubs['licenceno']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Chassis No.:</td>
                <td align="left"><b><?php echo $row_TSubs['modelno']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Engine No.:</td>
                <td align="left"><b><?php echo $row_TSubs['partno']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Vehicle Make:</td>
                <td align="left"><strong>
                  <script>document.write(get_brand(<?php echo $row_TSubs['SalvageValue'] - 1 ?>, <?php echo intval($row_TSubs['Brand']) ?>)+ ' '+get_model(<?php echo $row_TSubs['SalvageValue'] - 1 ?>, <?php echo intval($row_TSubs['serialno']) ?>))</script>
                <?php echo $row_TSubs['Model'] ?></strong></td>
              </tr>
              <tr>
                <td class="titles">Vehicle Type:</td>
                <td align="left"><strong><?php echo $row_TSubs['vtype'] ?>
                  <script>document.write(get_bstyle(<?php echo $row_TSubs['SalvageValue'] - 1 ?>, <?php echo intval($row_TSubs['DepreciationValue']) ?>))</script>
                </strong></td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">License Type:</td>
                <td align="left"><b><?php echo $row_TSubs['license']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">Invoice Date:</td>
                <td align="left"><strong><?php echo $row_TSales['LedgerDate'] ?></strong></td>
              </tr>
              <tr>
                <td class="titles">Posted:</td>
                <td align="left"><input type="checkbox" name="Posted2"<?php if ($row_TSales['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="1" cellpadding="2" cellspacing="0" id="Tabdet">
                  <tr class="boldwhite1">
                    <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Vehicle</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Price</td>
                    <td align="center" nowrap="nowrap" bgcolor="#000000">Total</td>
                  </tr>
                  <?php $j = 0;foreach ($TItems as $row_TItems) { ?>
                  <tr id="itm_<?php echo $j ?>">
                    <td><?php echo $row_TItems['InvoiceDetailID'] ?>
                      <input type="hidden" name="UnitPrice_<?php echo $j ?>" value="<?php echo $row_TItems['UnitPrice']; ?>" />
                      <input type="hidden" name="units_<?php echo $j ?>" id="units_<?php echo $j ?>" value="<?php echo $row_TItems['units'] ?>" />
                      <input type="hidden" name="Discount_<?php echo $j ?>" id="Discount_<?php echo $j ?>" value="<?php echo $row_TItems['Discount'] ?>" />
                      <input type="hidden" name="TaxRate_<?php echo $j ?>" id="TaxRate_<?php echo $j ?>" value="<?php echo $row_TItems['TaxRate'] ?>" /></td>
                    <td id="Name_<?php echo $j ?>"><?php echo $row_TItems['serials'] ?></td>
                    <input type="hidden" name="LineTotal_<?php echo $j ?>" value="<?php echo $row_TItems['LineTotal']; ?>" />
                    <td id="Name_<?php echo $j ?>"><?php echo $row_TItems['ProductName'] ?></td>
                    <td align="right"><?php echo $row_TItems['units'] ?></td>
                    <td align="right" id="UnitPrice_<?php echo $j ?>"><?php echo $row_TItems['UnitPrice'] ?></td>
                    <td align="right" id="LineTotal_<?php echo $j ?>"></td>
                  </tr>
                  <?php $j++; } ?>
                </table></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="4" cellpadding="4">

          </table></td>
        </tr>
        <tr>
          <td colspan="2" align="right"><strong>
            <script>document.write(setMoney('<?php echo $row_TSales['Grandvalue'] ?>'))</script>
          </strong></td>
        </tr>
        <tr>
          <td colspan="2" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="right">&nbsp;</td>
        </tr>
        <?php } ?>
        <tr>
          <td colspan="2" align="right" bgcolor="#333333">&nbsp;</td>
        </tr>
        <tr>
          <td class="titles">Grand Total:</td>
          <td align="left"><strong>
            <script>document.write(setMoney('<?php echo $row_TSales['Grandvalue'] ?>'))</script>
          </strong></td>
        </tr>
        <tr>
          <td colspan="2" align="right" bgcolor="#333333">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center" id="ownerbar">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center"><span class="blacktxt">
            <input name="ItmID" type="hidden" id="ItmID" value="<?php echo $j ?>" />
            <script>var ItmID=<?php echo $j ?> </script>
            <?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
        </tr>
        <tr>
          <td colspan="2" align="center">..................................................</td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
<?php 
$dat = ob_get_contents();
ob_end_clean();
$tries = 0;
exec("php /var/www/html/scripts/file_cleaner.php /var/www/html/tmp/ print-*.html 5");

do {
    $f = mt_rand(100, 99999);
    try {
        $handle = fopen(ROOT . "/tmp/print-$f.html", "x");
    } catch (Exception $ex) {
    }
} while (!$handle && $tries < 20);

if ($handle) {
    fwrite($handle, $dat);
    fclose($handle);
?>
<script>
    location.href="<?php echo 'http://', PRINT_SERVER, '/?f=', $f, '&p=1&u=', $_SESSION['userid'] ?>";
</script>
<?php
}
