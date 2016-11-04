<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Sales'));
vetAccess('Accounts', 'Sales', 'View');

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Sale]del.php?id=$id","","","find.php","print.php?id=$id","index.php");
$rec_status = 1;

$outid = _xses('OutletID');
if (_xpost("MM_Post") == "frmpost") {
    try {
        $dbh->autocommit(FALSE);
    
        $sql = "FROM `{$_SESSION['DBCoy']}`.`invoices` 
                INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `invoices`.VendorID=`vendors`.VendorID 
                WHERE `InvoiceID`=$id AND `posted`=0 AND amtbal<Grandvalue AND (credit=0 OR credit=1 AND creditlimit<amtbal-Grandvalue)";
        $errCnt = getDBDatacnt($dbh, $sql);

        if ($errCnt > 0) {
//            throw new Exception("Insufficient Funds!");
        }
        
        $sql = "INSERT INTO `{$_SESSION['DBCoy']}`.`outlet`(`ProductID`, `OutletID`, `serials`, 
                `Shopshelf`, `ShopStock`, `Shopactlstock`, `Shoplevel`, `ShopNotes`) 
                SELECT `invoicedetails`.`ProductID`, $outid, '', 1, 0, 0, 0, ''
                FROM `{$_SESSION['DBCoy']}`.invoicedetails 
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `invoicedetails`.ProductID=`items_prod`.`ProductID`
                WHERE invoicedetails.InvoiceID=$id AND `invoicedetails`.`ProductID` NOT IN
                    (SELECT `ProductID` FROM `{$_SESSION['DBCoy']}`.`outlet` 
                    WHERE `OutletID`=$outid)";
        runDBQry($dbh, $sql);

        runDBQry($dbh, "SELECT * FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`  ON `invoicedetails`.ProductID=`items_prod`.`ProductID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`    ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`     ON `invoices`.VendorID=`vendors`.VendorID 
                INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`      ON `invoices`.`OutletID`=`outlet`.`OutletID` AND `invoicedetails`.ProductID=outlet.ProductID
                WHERE `invoices`.`InvoiceID`=$id AND `posted`=0 LOCK IN SHARE MODE");
        
        runDBQry($dbh, "SELECT * FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
                INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`        ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms` ON `invoicedetails`.ProductID=`items_pkgs_itms`.`PackageID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`      ON `items_pkgs_itms`.ProductID=`items_prod`.`ProductID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`          ON `invoices`.`OutletID`=`outlet`.`OutletID` AND `items_pkgs_itms`.`PackageID`=outlet.ProductID
                WHERE `invoices`.`InvoiceID`=$id AND `posted`=0 LOCK IN SHARE MODE");
        
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`invoicedetails`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`  ON `invoicedetails`.ProductID=`items_prod`.`ProductID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`    ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`      ON `invoices`.`OutletID`=`outlet`.`OutletID` AND `invoicedetails`.ProductID=outlet.ProductID
                SET `ShopStock`=`ShopStock`-`units`
                WHERE `invoices`.`InvoiceID`=$id AND `posted`=0";
        runDBQry($dbh, $sql);

        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`invoicedetails`
                INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`        ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms` ON `invoicedetails`.ProductID=`items_pkgs_itms`.`PackageID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`      ON `items_pkgs_itms`.ProductID=`items_prod`.`ProductID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`          ON `invoices`.`OutletID`=`outlet`.`OutletID` AND `items_pkgs_itms`.`PackageID`=outlet.ProductID
                SET `ShopStock`=`ShopStock`-`units`
                WHERE `invoices`.`InvoiceID`=$id AND `posted`=0";
        runDBQry($dbh, $sql);

        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`invoices` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `invoices`.VendorID=`vendors`.VendorID 
        SET `posted`=1, LedgerDate=NOW(), RecAccountBalance=amtbal-Grandvalue, amtbal=amtbal-Grandvalue
        WHERE `InvoiceID`=$id AND `posted`=0";
        $update = runDBQry($dbh, $sql);
            
        if ($update != 2) {
            throw new Exception("Not updated");
        }
        $dbh->commit();
    } catch (Exception $ex) {
        $dbh->rollback();
        array_push($errors, array("Error", $ex->getMessage()));
    }
    $dbh->autocommit(TRUE);
} else if (_xpost("MM_Refund") == "frmRefund") {
    try {
        $dbh->autocommit(FALSE);
    
        runDBQry($dbh, "SELECT * FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`  ON `invoicedetails`.ProductID=`items_prod`.`ProductID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`    ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`     ON `invoices`.VendorID=`vendors`.VendorID 
                INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`      ON `invoices`.`OutletID`=`outlet`.`OutletID` AND `invoicedetails`.ProductID=outlet.ProductID
                WHERE `invoices`.`InvoiceID`=$id AND `posted`=1 LOCK IN SHARE MODE");
        
        runDBQry($dbh, "SELECT * FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
                INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`        ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms` ON `invoicedetails`.ProductID=`items_pkgs_itms`.`PackageID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`      ON `items_pkgs_itms`.ProductID=`items_prod`.`ProductID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`          ON `invoices`.`OutletID`=`outlet`.`OutletID` AND `items_pkgs_itms`.`PackageID`=outlet.ProductID
                WHERE `invoices`.`InvoiceID`=$id AND `posted`=1 LOCK IN SHARE MODE");
        
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`invoicedetails`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`  ON `invoicedetails`.ProductID=`items_prod`.`ProductID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`    ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`      ON `invoices`.`OutletID`=`outlet`.`OutletID` AND `invoicedetails`.ProductID=outlet.ProductID
                SET `ShopStock`=`ShopStock`+`units`
                WHERE `invoices`.`InvoiceID`=$id AND `posted`=1";
        runDBQry($dbh, $sql);

        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`invoicedetails`
                INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`        ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms` ON `invoicedetails`.ProductID=`items_pkgs_itms`.`PackageID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`      ON `items_pkgs_itms`.ProductID=`items_prod`.`ProductID`
                INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`          ON `invoices`.`OutletID`=`outlet`.`OutletID` AND `items_pkgs_itms`.`PackageID`=outlet.ProductID
                SET `ShopStock`=`ShopStock`+`units`
                WHERE `invoices`.`InvoiceID`=$id AND `posted`=1";
        runDBQry($dbh, $sql);

        $sql = sprintf("INSERT INTO `%s`.`expenses`(`AccountID`, `EmployeeID`, `InvoiceID`, 
            `ExpenseTitle`, `VendorType`, `VendorID`, `Recipient`, `ExpenseType`, `Amount`, 
            `ExpenseDate`, `DateSubmitted`, `Posted`, `Status`, `PaymentMethodID`, `CreditCardType`, `Notes`) 
            SELECT `AccountID`, %s, `InvoiceID`, 'Refund', `VendorType`, `VendorID`, `CustomerName`, 
            20, `Grandvalue`, NOW(), NOW(), 0, 0, NULL, NULL, '' FROM `%s`.`invoices`
            WHERE `InvoiceID`=$id",
                        $_SESSION['DBCoy'],
                        GSQLStr($_SESSION['ids']['VendorID'], "int"),
                        $_SESSION['DBCoy']);
        $insert = runDBQry($dbh, $sql);
        $recid = mysqli_insert_id($dbh);

        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`expenses` 
            INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`     ON `expenses`.VendorID=`vendors`.VendorID 
            SET `posted`=1, LedgerDate=NOW(), 
            `vendors`.amtbal=`vendors`.amtbal-Amount, RecAccountBalance=`vendors`.amtbal-Amount
            WHERE `ExpenseID`=$recid";
        $update = runDBQry($dbh, $sql);

        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`invoices` SET `status`=20 WHERE `InvoiceID`=$id AND `posted`=1";
        $update += runDBQry($dbh, $sql);
        
        if ($update != 3) {
            throw new Exception("Not updated");
        }
        $dbh->commit();
    } catch (Exception $ex) {
        $dbh->rollback();
        array_push($errors, array("Error", $ex->getMessage()));
    }
    $dbh->autocommit(TRUE);
}

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

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], AccStat($access['Edit'], $row_TSales['Posted']), AccStat($access['Del'], $row_TSales['Posted']), $access['Print'], 0, 0);
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

<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
  <link rel="stylesheet" href="/lib/jquery-ui/css/smoothness/jquery-ui.css">
  <script src="/lib/jquery-ui/js/jquery.js"></script>
  <script src="/lib/jquery-ui/js/jquery-ui.js"></script>
<script type="text/javascript" src="script.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    window.onload = function() {
        calItms(true);
    }
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/sales.jpg" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblsales.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
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
                    <td><textarea name="Notes" style="width:450px" rows="3"><?php echo $row_TSales['Notes'] ?></textarea>                    </td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td><table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
                      <tr>
                        <td class="bl_tl"></td>
                        <td class="bl_tp"></td>
                        <td class="bl_tr"></td>
                      </tr>
                      <tr>
                        <td rowspan="2" class="bl_lf"></td>
                        <td align="left" class="bl_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td nowrap="nowrap">Documents</td>
                            <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_docs" onclick="hideshow('docs', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_docs" onclick="hideshow('docs', 0, '')" style="display:none; cursor: pointer" /></div></td>
                          </tr>
                        </table></td>
                        <td rowspan="2" class="bl_rt"></td>
                      </tr>
                      <tr>
                        <td class="bl_center"><table width="100%" border="0" cellspacing="2" cellpadding="2" id="bx_docs" style="display:none">
                          <tr>
                            <td><?php $doc_shelf = 'Accounts'.DS.'Sales';
							$doc_id = $id; ?>
                              <?php include '../../scripts/viewdoc.php' ?></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td class="bl_bl"></td>
                        <td class="bl_bt"></td>
                        <td class="bl_br"></td>
                      </tr>
                    </table></td>
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
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Tenure/Qty</td>
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
                    <td height="28" colspan="2" align="center"><?php if ($access['Post'] == 1 && $row_TSales['Posted'] == 0) { ?>
                      <table border="0" cellspacing="1" cellpadding="1">
                        <tr>
                          <td><img src="/images/post.png" width="50" height="20" id="post" onclick="Post()" style="cursor: pointer" /></td>
                          <td><form id="frmpost" name="frmpost" method="post" action="">
                            <input type="hidden" name="MM_Post" value="frmpost" />
                          </form></td>
                        </tr>
                      </table>
                      <?php } elseif ($access['Refund'] == 1 && $row_TSales['Posted'] == 1 && $row_TSales['Status'] != 20) { ?>
                      <table border="0" cellspacing="1" cellpadding="1">
                        <tr>
                          <td><img src="/images/but_refund.png" name="refund" width="60" height="20" id="refund" style="cursor: pointer" onclick="Refund()" /></td>
                          <td><form id="frmRefund" name="frmRefund" method="post" action="">
                            <input type="hidden" name="MM_Refund" value="frmRefund" />
                          </form></td>
                        </tr>
                      </table>
                      <?php } ?>
                    </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php'); ?></td>
              </tr>

            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>

</body>
</html>
