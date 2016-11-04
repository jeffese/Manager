<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Orders'));
vetAccess('Stock', 'Orders', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmorder","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Stock'.DS.'Orders';
$doc_id = $id;

if (_xpost("MM_update") == "frmorder") {
    try {
        $dbh->autocommit(FALSE);
        
        $OrdDetID = intval(_xpost('OrdDetID'));
        $tot = 0;
        for ($q = 0; $q < $OrdDetID; $q++) {
            if (!isset($_POST["ProductID$q"]))
                continue;
            $orderdetID = intval(_xpost("OrderDetailID$q"));
            $qty = GSQLStr(_xpost("Quantity$q"), "doublev");
            $unitp = GSQLStr(_xpost("UnitPrice$q"), "doublev");
            $stock = GSQLStr(_xpost("QtyinStock$q"), "doublev");
            $sql = "";
            if ($orderdetID == 0) {
                $sql = sprintf("INSERT INTO `%s`.`orderdetails`(`OrderID`, `ProductID`, 
                    `ProductName`, `Quantity`, `unitsinpack`, 
                    `UnitPrice`, `Margin`, `sugsell`, `oldsell`, `currentstock`, `ExpiryDate`, 
                    `Expires`, `Cleared`, `QtyinStock`, `Received`) 
                    VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                           $_SESSION['DBCoy'],
                           $id,
                           GSQLStr(_xpost("ProductID$q"), "int"),
                           GSQLStr(_xpost("ProductName$q"), "text"),
                           "'$qty'",
                           GSQLStr(_xpost("unitsinpack$q"), "double"),
                           "'$unitp'",
                           GSQLStr(_xpost("Margin$q"), "double"),
                           GSQLStr(_xpost("sugsell$q"), "double"),
                           GSQLStr(_xpost("oldsell$q"), "double"),
                           0,
                           GSQLStr(_xpost("ExpiryDate$q"), "date"),
                           _xpostchk("Expires$q"),
                           0,
                           "'$stock'",
                           0);
                runDBQry($dbh, $sql);
                $recid = mysqli_insert_id($dbh);

                $sql = "UPDATE `{$_SESSION['DBCoy']}`.`items_prod` 
                    INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `items_prod`.`ProductID`=orderdetails.ProductID
                    SET `UnitsOnOrder`=`UnitsOnOrder`+'$stock'
                    WHERE `OrderDetailID`=$recid";
                runDBQry($dbh, $sql);
            } else {
                $sql = "UPDATE `{$_SESSION['DBCoy']}`.`items_prod` 
                    INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `items_prod`.`ProductID`=orderdetails.ProductID
                    SET `UnitsOnOrder`=`UnitsOnOrder`-`QtyinStock`+'$stock'
                    WHERE `OrderDetailID`=$orderdetID";
                runDBQry($dbh, $sql);
                $sql = sprintf("UPDATE `%s`.`orderdetails` SET `Quantity`=%s,`unitsinpack`=%s,
                    `UnitPrice`=%s,`Margin`=%s,`sugsell`=%s,`ExpiryDate`=%s,`Expires`=%s,`QtyinStock`=%s
                     WHERE `OrderDetailID`=%s",
                           $_SESSION['DBCoy'],
                           "'$qty'",
                           GSQLStr(_xpost("unitsinpack$q"), "double"),
                           "'$unitp'",
                           GSQLStr(_xpost("Margin$q"), "double"),
                           GSQLStr(_xpost("sugsell$q"), "double"),
                           GSQLStr(_xpost("ExpiryDate$q"), "date"),
                           _xpostchk("Expires$q"),
                           "'$stock'",
                           $orderdetID);
                runDBQry($dbh, $sql);
            }
        }
        $delsub = _xpost('del_ids');
        $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`orderdetails` WHERE OrderDetailID IN ($delsub)";
        runDBQry($dbh, $sql);

        $sql = "SELECT SUM(`Quantity`*`UnitPrice`) AS `tot` 
                    FROM `{$_SESSION['DBCoy']}`.`orderdetails` 
                    WHERE `OrderID`={$id}";
        $tot = getDBDataOne($dbh, $sql, 'tot');
        $exp = GSQLStr(_xpost('expenses'), "doublev");
        $fgt = GSQLStr(_xpost('FreightCharge'), "doublev");
        $expfgt = $exp + $fgt;
        $dsc = GSQLStr(_xpost('Discount'), "doublev");
        $totval = $tot + $expfgt - $dsc;
        $xfrm = GSQLStr(_xpost('ExchangeFrom'), "int");
        $xto = GSQLStr(_xpost('ExchangeTo'), "int");
        $salp = "(UnitPrice/unitsinpack) * (1 + $expfgt/$tot) * $xto / $xfrm";
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`orderdetails` 
                    SET `calcost`=(UnitPrice/unitsinpack) * (1 + $expfgt/$tot) * $xto / $xfrm
                    WHERE `OrderID`={$id}";
        runDBQry($dbh, $sql);

        $sql = sprintf("UPDATE `%s`.`orders` SET `SupplierID`=%s,`EmployeeID`=%s,`OrderDate`=%s,
            `PurchaseOrderNumber`=%s,`RequiredByDate`=%s,`PromisedByDate`=%s,`ShipName`=%s,
            `ShipAddress`=%s,`ShipCity`=%s,`ShipState`=%s,`ShipStateOrProvince`=%s,`ShipPostalCode`=%s,
            `ShipCountry`=%s,`ShipPhoneNumber`=%s,`ShipDate`=%s,`ShippingMethodID`=%s,`ShopCurrency`=%s,
            `Currency`=%s,`ExchangeFrom`=%s,`ExchangeTo`=%s,`FreightCharge`=%s,`SalesTaxRate`=%s,
            `Margin`=%s,`Dscnt`=%s,`Discount`=%s,`expenses`=%s,`OrderTotal`=%s,`TotalValue`=%s,
            `Notes`=%s WHERE `OrderID`=%s AND Posted=0",
                                           $_SESSION['DBCoy'],
                       GSQLStr(_xpost('SupplierID'), "int"),
                       GSQLStr($_SESSION['ids']['VendorID'], "int"),
                       GSQLStr(_xpost('OrderDate'), "date"),
                       GSQLStr(_xpost('PurchaseOrderNumber'), "text"),
                       GSQLStr(_xpost('RequiredByDate'), "date"),
                       GSQLStr(_xpost('PromisedByDate'), "date"),
                       GSQLStr(_xpost('ShipName'), "text"),
                       GSQLStr(_xpost('ShipAddress'), "text"),
                       GSQLStr(_xpost('ShipCity'), "text"),
                       GSQLStr(_xpost('ShipState'), "text"),
                       GSQLStr(_xpost('ShipStateOrProvince'), "text"),
                       GSQLStr(_xpost('ShipPostalCode'), "text"),
                       GSQLStr(_xpost('ShipCountry'), "int"),
                       GSQLStr(_xpost('ShipPhoneNumber'), "text"),
                       GSQLStr(_xpost('ShipDate'), "date"),
                       GSQLStr(_xpost('ShippingMethodID'), "int"),
                       GSQLStr($_SESSION['COY']['currency'], "int"),
                       GSQLStr(_xpost('Currency'), "int"),
                       $xfrm,
                       $xto,
                       "'$fgt'",
                       GSQLStr(_xpost('SalesTaxRate'), "double"),
                       GSQLStr(_xpost('Margin'), "double"),
                       GSQLStr(_xpost('Dscnt'), "double"),
                       "'$dsc'",
                       "'$exp'",
                       "'$tot'",
                       "'$totval'",
                       GSQLStr(_xpost('Notes'), "text"),
                       $id);
        runDBQry($dbh, $sql);

        if (mysqli_error($dbh)) {
            throw new Exception("Error in SQL");
        }

        $dbh->commit();
        $dbh->autocommit(TRUE);
        docs($doc_shelf, $doc_id);
        header("Location: view.php?id=$id");
        exit;
    } catch (Exception $ex) {
        $dbh->rollback();
    }
    $dbh->autocommit(TRUE);
}


$sql = "SELECT `orders`.*, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`orders` 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `orders`.EmployeeID=`vendors`.VendorID
    WHERE `OrderID`={$id} AND Posted=0";
$row_TOrders = getDBDataRow($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`orderdetails` WHERE `OrderID`={$id}";
$TOrderDets = getDBData($dbh, $sql);

$sql = "SELECT country_id, country FROM `".DB_NAME."`.`country` ORDER BY country";
$Tcountry = getDBData($dbh, $sql);

$sql = "SELECT `state` FROM `".DB_NAME."`.`state` ORDER BY `state`";
$Tstate = getDBData($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`currencies` ORDER BY cur_id";
$TCurrency = getDBData($dbh, $sql);

$TSup = getVendor(2);

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
<script type="text/javascript" src="script.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["SupplierID", "", 
            ["req", "Select Supplier"]
        ],
        ["OrderDate", "", 
            ["req", "Select Order Date"]
        ],
		["Currency", "if=$('#inf').click()", 
            ["req", "Select Currency"]
        ]
    ]
    
    var curArray=[
            []<?php foreach ($TCurrency as $row_TCurrency) { ?>,
            [<?php echo '"'.implode('", "', $row_TCurrency).'"' ?>]
            <?php }?>
    ];

    var  mCal, mCal2, mCal3, mCal4;
    window.onload = function() {
        mCal = new dhtmlxCalendarObject('OrderDate', true, {isYearEditable: true, isMonthEditable: true});
	mCal.setSkin('dhx_black');
        mCal2 = new dhtmlxCalendarObject('RequiredByDate', true, {isYearEditable: true, isMonthEditable: true});
	mCal2.setSkin('dhx_black');
        mCal3 = new dhtmlxCalendarObject('ShipDate', true, {isYearEditable: true, isMonthEditable: true});
	mCal3.setSkin('dhx_black');
        mCal4 = new dhtmlxCalendarObject('PromisedByDate', true, {isYearEditable: true, isMonthEditable: true});
	mCal4.setSkin('dhx_black');
        setExchange();
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="return vetProds() && validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmorder" id="frmorder">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td width="120" class="titles">Order ID:</td>
                    <td class="red-normal"><b><?php echo $row_TOrders['OrderID']; ?></b></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Supplier:</td>
                    <td align="left"><select name="SupplierID">
                      <option value="">Select Supplier</option>
                      <?php foreach ($TSup as $row_TSup) { ?>
                      <option value="<?php echo $row_TSup['VendorID'] ?>" <?php if (!(strcmp($row_TOrders['SupplierID'], $row_TSup['VendorID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TSup['VendorName'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Invoice #:</td>
                    <td align="left"><input type="text" name="PurchaseOrderNumber" value="<?php echo $row_TOrders['PurchaseOrderNumber'] ?>" size="32" /></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Staff:</td>
                    <td><?php echo $row_TOrders['VendorName'] ?></td>
                    </tr>
                  <tr>
                    <td class="titles">Date Ordered:</td>
                    <td align="left"><input name="OrderDate" type="text" id="OrderDate" value="<?php echo $row_TOrders['OrderDate'] ?>" size="16" /></td>
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
                              <td><input type="text" name="FreightCharge" value="<?php echo $row_TOrders['FreightCharge'] ?>" onchange="calCost()" size="12" /></td>
                              <td>&nbsp;</td>
                              <td width="120" class="titles">Margin:</td>
                              <td><input name="Margin" type="text" id="Margin" value="<?php echo $row_TOrders['Margin'] ?>" size="12" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Expenses:</td>
                              <td><input type="text" name="expenses" value="<?php echo $row_TOrders['expenses'] ?>" onchange="calCost()" size="12" /></td>
                              <td>&nbsp;</td>
                              <td width="120" class="titles">Currency:</td>
                              <td><select name="Currency" id="Currency" onchange="setExRate()">
                                <option value=""></option>
                                <?php
                                $coyCur = "";
                                 foreach ($TCurrency as $row_TCurrency) {
                                    if ($_SESSION['COY']['currency']==$row_TCurrency['cur_id']) 
                                        $coyCur = $row_TCurrency['code'];
                                ?>
                                <option value="<?php echo $row_TCurrency['cur_id'] ?>" <?php if (!(strcmp($row_TOrders['Currency'],$row_TCurrency['cur_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TCurrency['currencyname'] ?></option>
                                <?php } ?>
                                </select></td>
                              </tr>
                            <tr>
                              <td class="titles">Tax:</td>
                              <td><input type="text" name="SalesTaxRate" value="<?php echo $row_TOrders['SalesTaxRate'] ?>" style="width:30px" /></td>
                              <td>&nbsp;</td>
                              <td width="120" class="titles">Exchange Rate:</td>
                              <td><table border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                  <td id="xfrom">&nbsp;</td>
                                  <td><input name="ExchangeFrom" type="text" id="ExchangeFrom" value="<?php echo $row_TOrders['ExchangeFrom'] ?>" style="width:30px" /></td>
                                  <td><strong>=&gt;</strong></td>
                                  <td><?php echo $coyCur ?></td>
                                  <td><input name="ExchangeTo" type="text" id="ExchangeTo" value="<?php echo $row_TOrders['ExchangeTo'] ?>" style="width:30px" /></td>
                                </tr>
                              </table></td>
                              </tr>
                            <tr>
                              <td class="titles">Discount:</td>
                              <td><table border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td><input type="text" name="Dscnt" value="<?php echo $row_TOrders['Dscnt'] ?>" onchange="calDsc()" style="width:30px" /></td>
                                  <td>%</td>
                                  <td><strong>=&gt;</strong></td>
                                  <td><input type="text" name="Discount" value="<?php echo $row_TOrders['Discount'] ?>" onchange="calCost()" size="12" /></td>
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
                              <td><input name="OrderTotal" type="text" style="width:120px" value="<?php echo $row_TOrders['OrderTotal'] ?>" readonly="readonly" /></td>
                              <td>&nbsp;</td>
                              <td width="120" class="titles">Total Value:</td>
                              <td><input name="TotalValue" type="text" style="width:120px" value="<?php echo $row_TOrders['TotalValue'] ?>" readonly="readonly" /></td>
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
                              <td align="left"><input name="ShipName" type="text" value="<?php echo $row_TOrders['ShipName'] ?>" size="32" /></td>
                              <td align="left">&nbsp;</td>
                              <td width="120" class="titles">Status:</td>
                              <td align="left"><input name="ShipState" type="text" value="<?php echo $row_TOrders['ShipState'] ?>" size="32" /></td>
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
                              <td align="left"><select name="ShippingMethodID">
                                <option value="0" <?php if (!(strcmp($row_TOrders['ShippingMethodID'],"0"))) {echo "selected=\"selected\"";} ?>>Select One </option>
                                <option value="1" <?php if (!(strcmp($row_TOrders['ShippingMethodID'],"1"))) {echo "selected=\"selected\"";} ?>>Air Freight</option>
                                <option value="2" <?php if (!(strcmp($row_TOrders['ShippingMethodID'],"2"))) {echo "selected=\"selected\"";} ?>>Sea Freight</option>
                                <option value="3" <?php if (!(strcmp($row_TOrders['ShippingMethodID'],"3"))) {echo "selected=\"selected\"";} ?>>Parcel Service</option>
                                <option value="4" <?php if (!(strcmp($row_TOrders['ShippingMethodID'],"4"))) {echo "selected=\"selected\"";} ?>>Door Delivery</option>
                                <option value="5" <?php if (!(strcmp($row_TOrders['ShippingMethodID'],"5"))) {echo "selected=\"selected\"";} ?>>Others</option>
                                </select></td>
                              <td align="left">&nbsp;</td>
                              <td width="120" valign="top" class="titles">Shipper Address:</td>
                              <td align="left"><textarea name="ShipAddress" style="width:300px" rows="3"><?php echo $row_TOrders['ShipAddress'] ?></textarea></td>
                              </tr>
                            <tr>
                              <td width="140" class="titles">Date Required:</td>
                              <td align="left"><input name="RequiredByDate" type="text" id="RequiredByDate" value="<?php echo $row_TOrders['RequiredByDate'] ?>" size="16" /></td>
                              <td align="left">&nbsp;</td>
                              <td width="120" class="titles">Shipper City:</td>
                              <td align="left"><input type="text" name="ShipCity" value="<?php echo $row_TOrders['ShipCity'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Date of Shipping:</td>
                              <td align="left"><input name="ShipDate" type="text" id="ShipDate" value="<?php echo $row_TOrders['ShipDate'] ?>" size="16" /></td>
                              <td align="left">&nbsp;</td>
                              <td width="120" class="titles">Shipper Country:</td>
                              <td align="left"><select name="ShipCountry" id="ShipCountry" onchange="if (this.value==154){this.form.cmbsta.style.display='block'; this.form.ShipStateOrProvince.style.display='none';} else {this.form.cmbsta.style.display='none'; this.form.ShipStateOrProvince.style.display='block'; }">
                                <option value="">Select Country</option>
                                <?php foreach ($Tcountry as $row_Tcountry) { ?>
                                <option value="<?php echo $row_Tcountry['country_id']?>" <?php if (!(strcmp($row_TOrders['ShipCountry'],$row_Tcountry['country_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tcountry['country']?></option>
                                <?php } ?>
                                </select></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Expected Date of Arrival:</td>
                              <td align="left"><input name="PromisedByDate" type="text" id="PromisedByDate" value="<?php echo $row_TOrders['PromisedByDate'] ?>" size="16" /></td>
                              <td align="left">&nbsp;</td>
                              <td width="120" class="titles">Shipper State:</td>
                              <td align="left" nowrap="nowrap"><select name="cmbsta" onchange="this.form.ShipStateOrProvince.value=this.value">
                                <option value="">Select State</option>
                                <?php foreach ($Tstate as $row_Tstate) { ?>
                                <option value="<?php echo $row_Tstate['state']?>" <?php if (!(strcmp($row_TOrders['ShipStateOrProvince'],$row_Tstate['state']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tstate['state']?></option>
                                <?php } ?>
                                </select>
                                <input name="ShipStateOrProvince" type="text" size="25" value="<?php echo $row_TOrders['ShipStateOrProvince'] ?>" style="display: none" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">&nbsp;</td>
                              <td align="left">&nbsp;</td>
                              <td align="left">&nbsp;</td>
                              <td width="120" class="titles">Shipper Phone:</td>
                              <td><input type="text" name="ShipPhoneNumber" value="<?php echo $row_TOrders['ShipPhoneNumber'] ?>" size="32" /></td>
                              </tr>
                            </table>
                          </div>
                        <div class="TabbedPanelsContent">
                          <textarea name="Notes" style="width:450px" rows="10"><?php echo $row_TOrders['Notes'] ?></textarea>
                        </div>
                        <div class="TabbedPanelsContent">
                          <?php include "../../scripts/editdoc.php" ?>
                        </div>
                      </div>
                      </div></td>
                  </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="TabOrderdet">
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000"><input type="hidden" name="del_ids" value="0" /></td>
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
                        <td><a href="javascript: void(0)" onclick="removeProd(<?php echo $j ?>)"><img src="/images/delete.png" width="16" height="16" /></a>
                          <input type="hidden" name="OrderDetailID<?php echo $j ?>" id="OrderDetailID<?php echo $j ?>" value="<?php echo $row_TOrderDets['OrderDetailID']; ?>" />
                          <input type="hidden" name="ProductID<?php echo $j ?>" id="ProductID<?php echo $j ?>" value="<?php echo $row_TOrderDets['ProductID']; ?>" />
                          <input type="hidden" name="calcost<?php echo $j ?>" value="<?php echo $row_TOrderDets['calcost']; ?>" />
                          <input type="hidden" name="ProductName<?php echo $j ?>" value="<?php echo $row_TOrderDets['ProductName']; ?>" />
                          <input type="hidden" name="QtyinStock<?php echo $j ?>" value="<?php echo $row_TOrderDets['QtyinStock']; ?>" /></td>
                        <td><?php echo $row_TOrderDets['OrderDetailID'] ?></td>
                        <td id="ProductName<?php echo $j ?>"><strong><?php echo $row_TOrderDets['ProductName'] ?></strong></td>
                        <td><input type="text" name="Quantity<?php echo $j ?>" id="Quantity<?php echo $j ?>" value="<?php echo $row_TOrderDets['Quantity'] ?>" onChange="setthous(this, 1); calProd(<?php echo $j ?>)" style="width:40px" /></td>
                        <td><input type="text" name="unitsinpack<?php echo $j ?>" id="unitsinpack<?php echo $j ?>" value="<?php echo $row_TOrderDets['unitsinpack'] ?>" onChange="setthous(this, 1); calProd(<?php echo $j ?>)" style="width:40px" /></td>
                        <td id="QtyinStock<?php echo $j ?>"><?php echo $row_TOrderDets['QtyinStock'] ?></td>
                        <td id="Received<?php echo $j ?>"><?php echo $row_TOrderDets['Received'] ?></td>
                        <td><input type="text" name="UnitPrice<?php echo $j ?>" id="UnitPrice<?php echo $j ?>" value="<?php echo $row_TOrderDets['UnitPrice'] ?>" onChange="numme(this, 0); calProd(<?php echo $j ?>)" style="width:100px" /></td>
                        <td id="linetotal<?php echo $j ?>"><?php echo $row_TOrderDets['UnitPrice'] * $row_TOrderDets['Quantity'] ?></td>
                        <td id="salesprice<?php echo $j ?>"><?php echo $row_TOrderDets['UnitPrice'] / $row_TOrderDets['unitsinpack'] ?></td>
                        <td><input type="text" name="Margin<?php echo $j ?>" id="Margin<?php echo $j ?>" value="<?php echo $row_TOrderDets['Margin'] ?>" onChange="setthous(this, 0); calProd(<?php echo $j ?>)" style="width:30px" /></td>
                        <td id="calcost<?php echo $j ?>"><?php echo $row_TOrderDets['calcost'] ?></td>
                        <td><input type="text" name="sugsell<?php echo $j ?>" id="sugsell<?php echo $j ?>" value="<?php echo $row_TOrderDets['sugsell'] ?>" style="width:100px" /></td>
                        <td id="oldsell<?php echo $j ?>"><?php echo $row_TOrderDets['oldsell'] ?></td>
                        <td align="center"><input type="checkbox" name="Expires<?php echo $j ?>" id="Expires<?php echo $j ?>"<?php if ($row_TOrderDets['Expires'] == 1) {
                echo " checked=\"checked\"";} ?> onclick="setExpires(<?php echo $j ?>)" /></td>
                        <td><input type="text" name="ExpiryDate<?php echo $j ?>" id="ExpiryDate<?php echo $j ?>" style="width:80px<?php if ($row_TOrderDets['Expires'] == 0) { ?>; display:none<?php } ?>" value="<?php echo $row_TOrderDets['ExpiryDate'] ?>" /></td>
                        </tr>
                        <?php $j++; } ?>
                      </table>
                      <input name="OrdDetID" type="hidden" id="OrdDetID" value="<?php echo $j ?>" />
                      <script>var OrdDetID=<?php echo $j ?> </script></td>
                  </tr>
                  <tr>
                    <td height="28" colspan="2" align="center"><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td><a href="javascript: void(0)" onclick="GB_showCenter('Product List', '/stock/orders/pick.php', 600,600)"><img src="/images/but_add.png" width="50" height="20" /></a></td>
                        <td>&nbsp;</td>
                        <td><a id="sug" href="javascript: void(0)" onclick="suggest()"><img src="/images/but_sug_price.png" width="60" height="20" /></a></td>
                      </tr>
                    </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
                  <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
                  <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
                  <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
                  <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmorder" />
            <input type="hidden" name="OrderID" value="<?php echo $row_TOrders['OrderID']; ?>" />
          </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>            </tr>
          </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
<script type="text/javascript">
var Tabs = new Spry.Widget.TabbedPanels("Details");
</script>
</body>
</html>
