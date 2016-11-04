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

$sel = "SELECT SrvSchedID, DATE_FORMAT(startdate, '%e/%c/%Y') AS startdate, DATE_FORMAT(enddate, '%e/%c/%Y') AS enddate, 
    UPPER(DATE_FORMAT(enddate, '%b %Y')) AS endLic, `invoicedetails`.InvoiceID, `invoicedetails`.ProductID, 
    `invoicedetails`.`UnitPrice` AS `LineTotal`,
    `AssetID`, `items_srv_sched`.`Status` FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`";

$sql = "SELECT SrvSchedID, startdate, enddate, endLic, `ProdName`, `sched`.`Status`, 
    `assets`.*, colorname, tags, `auto_categories`.category_name, `AssStatus`.Category AS assStat, `license`,
    `category_name` AS `vtype`, CONCAT(`AssetName`, ' ', `licenceno`) AS `AssetName`
        FROM (
    $sel
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails`      ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`           ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    WHERE `SrvSchedID`=$id
        UNION
    $sel
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails`      ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms`     ON `items_srv_sched`.PackItemID=`items_pkgs_itms`.`PackItemID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`           ON `items_pkgs_itms`.ProductID=`items_srv`.`ServiceID`
    WHERE `SrvSchedID`=$id
        ) AS `sched` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`items`               ON `sched`.ProductID=`items`.ItemID
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`            ON `sched`.InvoiceID=`invoices`.`InvoiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`             ON `invoices`.VendorID=`vendors`.VendorID
    INNER JOIN `{$_SESSION['DBCoy']}`.`assets`              ON `sched`.AssetID=`assets`.AssetID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status` `AssStatus`   ON `assets`.Status=`AssStatus`.CategoryID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`colors`               ON `assets`.colour=`colors`.colorid
    LEFT JOIN `{$_SESSION['DBCoy']}`.`licenses`             ON `assets`.`desgtype`=`licenses`.`lic_typ`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`auto_categories`      ON `assets`.`SalvageValue`=`auto_categories`.`CatID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`               ON `sched`.Status=status.CategoryID";
$row_TLic = getDBDataRow($dbh, $sql);

$sql = "SELECT `invoicedetails`.*, serialized, `outlet`.`serials` AS allserials, ShopStock
    FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices` ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `invoicedetails`.ProductID=`items_prod`.`ProductID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`outlet` ON 
        (`invoicedetails`.ProductID=`outlet`.ProductID AND `invoices`.OutletID=`outlet`.OutletID)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`items_srv` ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    WHERE `invoices`.`InvoiceID`=$id";
$TItems = getDBData($dbh, $sql);

$sub_sql = "SELECT `ProdName` AS `ProductName`, `UnitPrice`, `items_pkgs_itms`.`Quantity` AS `units`, `Discnt`, `Discount`, 0 AS `TaxRate`, 0 AS `LineTotal`
    FROM `{$_SESSION['DBCoy']}`.`items_pkgs_itms`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items`       ON `items_pkgs_itms`.ProductID=`items`.`ItemID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`items_prod`   ON `items_pkgs_itms`.ProductID=`items_prod`.`ProductID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`items_srv`    ON `items_pkgs_itms`.ProductID=`items_srv`.`ServiceID`
    WHERE `items_pkgs_itms`.`PackageID`=";

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
</style>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/xxpix.jpg" ?>" /></td>
  </tr>
  <tr>
    <td align="center"><span class="coyrcpttxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
                <td class="titles">Owner ID:</td>
                <td align="left"><b class="blacktxt"><?php echo $row_TSales['VendorID']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Owner:</td>
                <td align="left"><?php echo $row_TSales['person'] ?></td>
              </tr>
              <tr>
                <td class="titles">Total Value:</td>
                <td align="left"><strong>
                  <script>document.write(setMoney('<?php echo $row_TSales['Grandvalue'] ?>'))</script>
                </strong></td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">Invoice Date:</td>
                <td align="left"><?php echo $row_TSales['LedgerDate'] ?></td>
              </tr>
              <tr>
                <td class="titles">Status: </td>
                <td align="left"><?php echo $row_TSales['Category'] ?></td>
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
                  <?php 
                        $SubItems = getDBData($dbh, $sub_sql . $row_TItems['ProductID']);
                        foreach ($SubItems as $row_TPackitems) { $j++; ?>
                  <tr bgcolor="#CCCCCC" id="itm_<?php echo $j ?>">
                    <td><input type="hidden" name="UnitPrice_<?php echo $j ?>" value="<?php echo $row_TPackitems['UnitPrice']; ?>" />
                      <input type="hidden" name="units_<?php echo $j ?>" id="units_<?php echo $j ?>" value="<?php echo $row_TPackitems['units'] ?>" />
                      <input type="hidden" name="Discount_<?php echo $j ?>" id="Discount_<?php echo $j ?>" value="<?php echo $row_TPackitems['Discount'] ?>" />
                      <input type="hidden" name="TaxRate_<?php echo $j ?>" id="TaxRate_<?php echo $j ?>" value="<?php echo $row_TPackitems['TaxRate'] ?>" /></td>
                    <td id="Name_<?php echo $j ?>">&nbsp;</td>
                    <input type="hidden" name="LineTotal_<?php echo $j ?>" value="<?php echo $row_TPackitems['LineTotal']; ?>" />
                    <td id="Name_<?php echo $j ?>"><?php echo $row_TPackitems['ProductName'] ?></td>
                    <td align="right" id="units_<?php echo $j ?>"><?php echo $row_TPackitems['units'] ?></td>
                    <td align="right" id="UnitPrice_<?php echo $j ?>"><?php echo $row_TPackitems['UnitPrice'] ?></td>
                    <td align="right" id="LineTotal_<?php echo $j ?>"></td>
                  </tr>
                  <?php }
                        $j++; } ?>
                </table></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="4" cellpadding="4">

          </table></td>
        </tr>
        <tr>
          <td align="right"><strong>
            <script>document.write(setMoney('<?php echo $row_TSales['Grandvalue'] ?>'))</script>
          </strong></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" id="ownerbar">&nbsp;</td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt">
            <input name="ItmID" type="hidden" id="ItmID" value="<?php echo $j ?>" />
            <script>var ItmID=<?php echo $j ?> </script>
            <?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
        </tr>
        <tr>
          <td align="center">..................................................</td>
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
do {
    $f = mt_rand(100, 99999);
    $handle = fopen(ROOT . "/tmp/print-$f.html", "x");
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
