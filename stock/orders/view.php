<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Orders'));
vetAccess('Stock', 'Orders', 'View');

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Order]del.php?id=$id","","","find.php","print.php?id=$id","index.php");
$rec_status = 1;

if (_xpost("MM_Recv") == "frmreceive") {
    $OrdDetID = intval(_xpost('OrdDetID'));

    try {
        $dbh->autocommit(FALSE);
        runDBQry($dbh, "SELECT * FROM `{$_SESSION['DBCoy']}`.`orderdetails`
                WHERE `OrderID`=$id LOCK IN SHARE MODE");
        
        for ($q = 0; $q < $OrdDetID; $q++) {
            if (!isset($_POST["ProductID$q"]))
                continue;
            $orderdetID = intval(_xpost("OrderDetailID$q"));

            $sql = sprintf("UPDATE `%s`.`orderdetails` SET `QtyinStock`=%s, `Received`=%s, `serials`=%s
                 WHERE `OrderDetailID`=%s AND `OrderID`=$id",
                       $_SESSION['DBCoy'],
                       GSQLStr(_xpost("QtyinStock$q"), "double"),
                       GSQLStr(_xpost("Received$q"), "double"),
                       GSQLStr(_xpost("serials$q"), "text"),
                       $orderdetID);
            runDBQry($dbh, $sql);
        }
        $dbh->commit();
    } catch (Exception $ex) {
        $conn->rollback();
    }
    $dbh->autocommit(TRUE);
} else if (_xpost("MM_Post") == "frmpost") {
    
    $sql = "FROM `{$_SESSION['DBCoy']}`.`orderdetails`
            WHERE `OrderID`={$id} AND `QtyinStock`<>`Received`";
    $errCnt = getDBDatacnt($dbh, $sql);

    if ($errCnt == 0){
        try {
            $dbh->autocommit(FALSE);
            $outid = GSQLStr(_xpost("outlet"), "int");
            $sql = "INSERT INTO `{$_SESSION['DBCoy']}`.`outlet`(`ProductID`, `OutletID`, `serials`, 
                    `Shopshelf`, `ShopStock`, `Shopactlstock`, `Shoplevel`, `ShopNotes`) 
                    SELECT `ProductID`, $outid, '', NULL, 0, 0, 0, ''
                    FROM `{$_SESSION['DBCoy']}`.orderdetails 
                    WHERE orderdetails.OrderID={$id} AND `ProductID` NOT IN
                        (SELECT `ProductID` FROM `{$_SESSION['DBCoy']}`.`outlet` 
                        WHERE `OutletID`=$outid)";
            runDBQry($dbh, $sql);
            
            runDBQry($dbh, "SELECT * FROM `{$_SESSION['DBCoy']}`.`orderdetails`
                    INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`  ON `orderdetails`.ProductID=items_prod.ProductID
                    INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`      ON `orderdetails`.ProductID=outlet.ProductID
                    INNER JOIN `{$_SESSION['DBCoy']}`.`orders`      ON `orderdetails`.OrderID=`orders`.`OrderID`
                    WHERE `orderdetails`.`OrderID`={$id} AND `posted`=0 AND `OutletID`=$outid LOCK IN SHARE MODE");

            $sql = "UPDATE `{$_SESSION['DBCoy']}`.`orderdetails`
                    INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `orderdetails`.ProductID=items_prod.ProductID
                    INNER JOIN `{$_SESSION['DBCoy']}`.`outlet` ON `orderdetails`.ProductID=outlet.ProductID
                    SET `outlet`.`serials` = IF(`items_prod`.`serialized`=0 
                    OR (`outlet`.`serials`='' AND `orderdetails`.`serials`=''),'',
                    CONCAT(`outlet`.`serials`, 
                    IF(`outlet`.`serials`<>'' AND `orderdetails`.`serials`<>'', ',', ''), 
                    `orderdetails`.`serials`)
                    ), `ShopStock`=`ShopStock`+`QtyinStock`, `UnitsOnOrder`=`UnitsOnOrder`-`QtyinStock`
                    WHERE `OrderID`={$id} AND `OutletID`=$outid";
            runDBQry($dbh, $sql);
            
            $sql = "UPDATE `{$_SESSION['DBCoy']}`.`orders` 
                    SET `posted`=1, `outlet`=$outid, LedgerDate=NOW()
                    WHERE `OrderID`={$id} AND `posted`=0";
            $update = runDBQry($dbh, $sql);
            
            if ($update != 1 || mysqli_error($dbh)) {
                throw new Exception("Error in SQL");
            }
            $dbh->commit();
        } catch (Exception $ex) {
            $conn->rollback();
        }
        $dbh->autocommit(TRUE);
    }
}
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
        INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `orderdetails`.ProductID=`items_prod`.ProductID  
        WHERE `OrderID`={$id}";
$TOrderDets = getDBData($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`currencies` WHERE cur_id={$_SESSION['COY']['currency']}";
$row_TShopcur = getDBDataRow($dbh, $sql);

$sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets` ORDER BY OutletName";
$TOutlets = getDBData($dbh, $sql);

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], AccStat($access['Edit'], $row_TOrders['Posted']), AccStat($access['Del'], $row_TOrders['Posted']), $access['Print'], 0, 1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
  <link rel="stylesheet" href="/lib/jquery-ui/css/smoothness/jquery-ui.css">
  <script src="/lib/jquery-ui/js/jquery.js"></script>
  <script src="/lib/jquery-ui/js/jquery-ui.js"></script>
<script type="text/javascript" src="script.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    window.onload = function() {
        $("#ordwords").html(NumToWords(<?php echo $row_TOrders['OrderTotal']; ?>, "<?php echo $row_TOrders['currencyname']; ?>", "<?php echo $row_TOrders['unitname']; ?>"));
        $("#totwords").html(NumToWords(<?php echo $row_TOrders['TotalValue']; ?>, "<?php echo $row_TOrders['currencyname']; ?>", "<?php echo $row_TOrders['unitname']; ?>"));
        $("#exwords").html(NumToWords(<?php echo ($row_TOrders['TotalValue'] * $row_TOrders['ExchangeTo'] / $row_TOrders['ExchangeFrom']) ?>, "<?php echo $row_TShopcur['currencyname']; ?>", "<?php echo $row_TShopcur['unitname']; ?>"));
    }
    
    $(function() {
        $("#dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 350,
            modal: true,
            buttons: {
                "Ok": function() {
                    var itms = "";
                    $("#serials > option").each(function() {
                        itms += "," + this.text;
                    });
                    $("[name=serials"+itm+"]").val(itms.substr(2));
                    $("[name=Received"+itm+"]").val($("#serials > option").length);
                    $(this).dialog("close");
                },
                Cancel: function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
            }
        });
    });
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
          <td width="240" valign="top"><img src="/images/orders.jpg" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblorders.png); background-repeat:no-repeat">&nbsp;</td>
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
                    <td colspan="2"><div id="Details" class="TabbedPanels">
                      <ul class="TabbedPanelsTabGroup">
                        <li class="TabbedPanelsTab" tabindex="0" id="inf">Info</li>
                        <li class="TabbedPanelsTab" tabindex="0">Shipping</li>
                        <li class="TabbedPanelsTab" tabindex="0">Notes</li>
                        <li class="TabbedPanelsTab" tabindex="0">Documents</li>
                      </ul>
                      <div class="TabbedPanelsContentGroup">
                        <div class="TabbedPanelsContent">
                          <table border="0" cellpadding="4" cellspacing="4">
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
                              <td width="120" class="titles">Exchange Value:</td>
                              <td><?php echo $row_TShopcur['code'] ?><?php echo ($row_TOrders['TotalValue'] * $row_TOrders['ExchangeTo'] / $row_TOrders['ExchangeFrom']) ?></td>
                            </tr>
                            <tr>
                              <td class="titles">&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td colspan="2" id="exwords">&nbsp;</td>
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
                          </table>
                        </div>
                        <div class="TabbedPanelsContent">
                          <table border="0" cellspacing="4" cellpadding="4">
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
                          </table>
                        </div>
                        <div class="TabbedPanelsContent">
                          <textarea name="Notes" rows="10" readonly="readonly" style="width:450px"><?php echo $row_TOrders['Notes'] ?></textarea>
                        </div>
                        <div class="TabbedPanelsContent">
                          <?php $doc_shelf = 'Stock'.DS.'Orders';
							$doc_id = $id; ?>
                          <?php include "../../scripts/viewdoc.php" ?>
                        </div>
                      </div>
                    </div></td>
                  </tr>
                  <tr>
                    <td colspan="2">
          <form method="post" name="frmreceive" id="frmreceive">
          <table width="100%" border="1" cellspacing="0" cellpadding="2" id="TabOrderdet">
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#/pack</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Stock Qty</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Recv</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000" style="padding:0px"></td>
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
                        <td><?php echo $row_TOrderDets['OrderDetailID'] ?>
                          <input type="hidden" name="OrderDetailID<?php echo $j ?>" id="OrderDetailID<?php echo $j ?>" value="<?php echo $row_TOrderDets['OrderDetailID']; ?>" />
                          <input type="hidden" name="ProductID<?php echo $j ?>" id="ProductID<?php echo $j ?>" value="<?php echo $row_TOrderDets['ProductID']; ?>" />
                          <input type="hidden" name="serials<?php echo $j ?>" id="serials<?php echo $j ?>" value="<?php echo $row_TOrderDets['serials']; ?>" />
                          <input type="hidden" name="serialized<?php echo $j ?>" value="<?php echo $row_TOrderDets['serialized']; ?>" />
                          <input type="hidden" name="QtyinStock<?php echo $j ?>" value="<?php echo $row_TOrderDets['QtyinStock']; ?>" /></td>
                        <td id="ProductName<?php echo $j ?>"><strong><?php echo $row_TOrderDets['ProductName'] ?></strong></td>
                        <td><?php echo $row_TOrderDets['Quantity'] ?></td>
                        <td><?php echo $row_TOrderDets['unitsinpack'] ?></td>
                        <td id="QtyinStock<?php echo $j ?>"><?php echo $row_TOrderDets['QtyinStock'] ?></td>
                        <td id="Received<?php echo $j ?>"><?php echo $row_TOrderDets['Received'] ?></td>
                        <td style="padding:0px"><input name="Received<?php echo $j ?>" type="text" style="width:30px; display:none" value="<?php echo $row_TOrderDets['Received'] ?>" <?php if ($row_TOrderDets['serialized'] == 1) { ?>readonly="readonly" onkeydown="serials(<?php echo $j ?>)" onclick="serials(<?php echo $j ?>)" <?php } ?>/></td>
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
                      </table>
                      <input name="OrdDetID" type="hidden" id="OrdDetID" value="<?php echo $j ?>" />
                      <input type="hidden" name="MM_Recv" value="frmreceive" />
                      <input type="hidden" name="OrderID" value="<?php echo $row_TOrders['OrderID']; ?>" />
          </form>
                      <script>var OrdDetID=<?php echo $j ?> </script></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center">
                      <table border="0" cellspacing="2" cellpadding="2">
                        <tr>
                      <?php if ($access['Receive'] == 1 && $row_TOrders['Posted'] == 0) { ?>
                          <td><a id="recv" href="javascript: void(0)" onclick="Recv()"><img src="/images/but_recv.png" width="80" height="20" /></a></td>
                          <td>&nbsp;</td>
                          <td><a id="recvall" href="javascript: void(0)" onclick="rcvAll()" style="display:none"><img src="/images/but_recvall.png" width="80" height="20" /></a></td>
                          <td>&nbsp;</td>
                          <td><a id="accept" href="javascript: void(0)" onclick="rcvAccept()" style="display:none"><img src="/images/but_accept.png" alt="" width="60" height="20" /></a></td>
                          <td>&nbsp;</td>
                          <td><a id="cncl" href="javascript: void(0)" onclick="cancelRcv()" style="display:none"><img src="/images/cancel.png" width="60" height="20" /></a></td>
                      <?php } ?>
                      <?php if ($access['Post'] == 1 && $row_TOrders['Posted'] == 0) { ?>
                          <td>&nbsp;</td>
                          <td><a id="post" href="javascript: void(0)" onclick="ordPost()"><img src="/images/post.png" width="50" height="20" /></a></td>
                          <td class="titles">to=&gt;</td>
                          <td><form id="frmpost" name="frmpost" method="post" action="">
                            <input type="hidden" name="MM_Post" value="frmpost" />
                            <input name="ordid" type="hidden" id="ordid" value="<?php echo $id ?>" />
                            <select name="outlet">
                              <?php foreach ($TOutlets as $row_TOutlets) { ?>
                              <option value="<?php echo $row_TOutlets['OutletID'] ?>" <?php if (!(strcmp(1, $row_TOutlets['OutletID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TOutlets['OutletName'] ?></option>
                              <?php } ?>
                            </select>
                          </form></td>
                      <?php } elseif ($_access['Returns']['Add'] == 1 && $row_TOrders['OrderID'] != 1000 && $row_TOrders['Posted'] == 1) { ?>
                          <td><a href="javascript: void(0)" onclick="ordRet(<?php echo $row_TOrders['OrderID'] ?>)"><img src="/images/but_return.png" width="60" height="20" /></a></td>
                      <?php } ?>
                        </tr>
                      </table>
                    <div id="dialog-form" title="Edit Product Serial No.s">
                      <table border="0" cellspacing="2" cellpadding="2">
                        <tr>
                          <td><select name="serials" size="12" id="serials">
                          </select></td>
                          <td valign="top"><table border="0" cellspacing="2" cellpadding="2">
                            <tr>
                              <td align="center"><input id="serialno" name="serialno" type="text" style="width:100px" /></td>
                            </tr>
                            <tr>
                              <td align="center"><a id="addserial" href="javascript: void(0)" onclick="addSerial()"><img src="/images/but_add.png" width="50" height="20" /></a></td>
                            </tr>
                            <tr>
                              <td align="center"><a id="delserial" href="javascript: void(0)" onclick="delSerial()"><img src="/images/but_mini_del.png" width="34" height="20" /></a></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table>
                    </div>
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
<script type="text/javascript">
var Tabs = new Spry.Widget.TabbedPanels("Details");
</script>
</body>
</html>
