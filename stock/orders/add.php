<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Orders'));
vetAccess('Stock', 'Orders', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmorder","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmorder") {
  
    $exp = GSQLStr(_xpost('expenses'), "doublev");
    $fgt = GSQLStr(_xpost('FreightCharge'), "doublev");
    $expfgt = $exp + $fgt;
    $dsc = GSQLStr(_xpost('Discount'), "doublev");
    $xfrm = GSQLStr(_xpost('ExchangeFrom'), "int");
    $xto = GSQLStr(_xpost('ExchangeTo'), "int");
    $sql = sprintf("INSERT INTO `%s`.`orders`(`SupplierID`, `EmployeeID`, `OrderDate`, 
        `PurchaseOrderNumber`, `RequiredByDate`, `PromisedByDate`, `ShipName`, `ShipAddress`, 
        `ShipCity`, `ShipState`, `ShipStateOrProvince`, `ShipPostalCode`, `ShipCountry`, 
        `ShipPhoneNumber`, `ShipDate`, `ShippingMethodID`, `ShopCurrency`, `Currency`, 
        `ExchangeFrom`, `ExchangeTo`, `FreightCharge`, `SalesTaxRate`, `Margin`, `Dscnt`, 
        `Discount`, `expenses`, `OrderTotal`, `TotalValue`, `Posted`, `Notes`) 
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,
        %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
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
               "'0'",
               "'0'",
               _xpostchk('Posted'),
               GSQLStr(_xpost('Notes'), "text"));
    $insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
        $id = mysqli_insert_id($dbh);
        docs('Stock'.DS.'Orders', $id);
        
        $OrdDetID = intval(_xpost('OrdDetID'));
        $tot = 0;
        for ($q = 0; $q < $OrdDetID; $q++) {
            if (!isset($_POST["ProductID$q"]))
                continue;
            $qty = GSQLStr(_xpost("Quantity$q"), "doublev");
            $unitp = GSQLStr(_xpost("UnitPrice$q"), "doublev");
            $stock = GSQLStr(_xpost("QtyinStock$q"), "doublev");
            
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
                       GSQLStr(_xpost("Received$q"), "double"));
            runDBQry($dbh, $sql);
            $detid = mysqli_insert_id($dbh);

            $sql = "UPDATE `{$_SESSION['DBCoy']}`.`items_prod` 
                INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `items_prod`.`ProductID`=orderdetails.ProductID
                SET `UnitsOnOrder`=`UnitsOnOrder`+'$stock'
                WHERE `OrderDetailID`=$detid";
            runDBQry($dbh, $sql);
        }
        $sql = "SELECT SUM(`Quantity`*`UnitPrice`) AS `tot` 
                    FROM `{$_SESSION['DBCoy']}`.`orderdetails` 
                    WHERE `OrderID`=$id";
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
        
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`orders` 
                    SET `OrderTotal`='$tot',`TotalValue`='$totval'
                    WHERE `OrderID`=$id";
        runDBQry($dbh, $sql);
        
        header("Location: view.php?id=$id");
        exit;
    }
}

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
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
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
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td width="120" class="titles">Supplier:</td>
                    <td align="left"><select name="SupplierID">
                      <option value="">Select Supplier</option>
                      <?php foreach ($TSup as $row_TSup) { ?>
                      <option value="<?php echo $row_TSup['VendorID'] ?>"><?php echo $row_TSup['VendorName'] ?></option>
                      <?php } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Invoice #:</td>
                    <td align="left"><input type="text" name="PurchaseOrderNumber" size="32" /></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Staff:</td>
                    <td><?php echo $_SESSION['ids']['VendorName'] ?></td>
                    </tr>
                  <tr>
                    <td class="titles">Date Ordered:</td>
                    <td align="left"><input name="OrderDate" type="text" id="OrderDate" size="16" /></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Posted:</td>
                    <td><input type="checkbox" name="Posted" disabled="disabled" /></td>
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
                              <td><input type="text" name="FreightCharge" onchange="calCost()" size="12" /></td>
                              <td>&nbsp;</td>
                              <td width="120" class="titles">Margin:</td>
                              <td><input name="Margin" type="text" id="Margin" size="12" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Expenses:</td>
                              <td><input type="text" name="expenses" onchange="calCost()" size="12" /></td>
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
                                <option value="<?php echo $row_TCurrency['cur_id'] ?>"><?php echo $row_TCurrency['currencyname'] ?></option>
                                <?php } ?>
                                </select></td>
                              </tr>
                            <tr>
                              <td class="titles">Tax:</td>
                              <td><input type="text" name="SalesTaxRate" style="width:30px" /></td>
                              <td>&nbsp;</td>
                              <td width="120" class="titles">Exchange Rate:</td>
                              <td><table border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                  <td id="xfrom">&nbsp;</td>
                                  <td><input name="ExchangeFrom" type="text" id="ExchangeFrom" style="width:30px" /></td>
                                  <td><strong>=&gt;</strong></td>
                                  <td><?php echo $coyCur ?></td>
                                  <td><input name="ExchangeTo" type="text" id="ExchangeTo" style="width:30px" /></td>
                                  </tr>
                                </table></td>
                              </tr>
                            <tr>
                              <td class="titles">Discount:</td>
                              <td><table border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td><input type="text" name="Dscnt" onchange="calDsc()" style="width:30px" /></td>
                                  <td>%</td>
                                  <td><strong>=&gt;</strong></td>
                                  <td><input type="text" name="Discount" onchange="calCost()" size="12" /></td>
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
                              <td><input name="OrderTotal" type="text" style="width:120px" readonly="readonly" /></td>
                              <td>&nbsp;</td>
                              <td width="120" class="titles">Total Value:</td>
                              <td><input name="TotalValue" type="text" style="width:120px" readonly="readonly" /></td>
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
                              <td align="left"><input name="ShipName" type="text" size="32" /></td>
                              <td align="left">&nbsp;</td>
                              <td width="120" class="titles">Status:</td>
                              <td align="left"><input name="ShipState" type="text" size="32" /></td>
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
                                <option value="0">Select One </option>
                                <option value="1">Air Freight</option>
                                <option value="2">Sea Freight</option>
                                <option value="3">Parcel Service</option>
                                <option value="4">Door Delivery</option>
                                <option value="5">Others</option>
                                </select></td>
                              <td align="left">&nbsp;</td>
                              <td width="120" valign="top" class="titles">Shipper Address:</td>
                              <td align="left"><textarea name="ShipAddress" style="width:300px" rows="3"></textarea></td>
                              </tr>
                            <tr>
                              <td width="140" class="titles">Date Required:</td>
                              <td align="left"><input name="RequiredByDate" type="text" id="RequiredByDate" size="16" /></td>
                              <td align="left">&nbsp;</td>
                              <td width="120" class="titles">Shipper City:</td>
                              <td align="left"><input type="text" name="ShipCity" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Date of Shipping:</td>
                              <td align="left"><input name="ShipDate" type="text" id="ShipDate" size="16" /></td>
                              <td align="left">&nbsp;</td>
                              <td width="120" class="titles">Shipper Country:</td>
                              <td align="left"><select name="ShipCountry" id="ShipCountry" onchange="if (this.value==154){this.form.cmbsta.style.display='block'; this.form.ShipStateOrProvince.style.display='none';} else {this.form.cmbsta.style.display='none'; this.form.ShipStateOrProvince.style.display='block'; }">
                                <option value="">Select Country</option>
                                <?php foreach ($Tcountry as $row_Tcountry) { ?>
                                <option value="<?php echo $row_Tcountry['country_id']?>"><?php echo $row_Tcountry['country']?></option>
                                <?php } ?>
                                </select></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Expected Date of Arrival:</td>
                              <td align="left"><input name="PromisedByDate" type="text" id="PromisedByDate" size="16" /></td>
                              <td align="left">&nbsp;</td>
                              <td width="120" class="titles">Shipper State:</td>
                              <td align="left" nowrap="nowrap"><select name="cmbsta" onchange="this.form.ShipStateOrProvince.value=this.value">
                                <option value="">Select State</option>
                                <?php foreach ($Tstate as $row_Tstate) { ?>
                                <option value="<?php echo $row_Tstate['state']?>"><?php echo $row_Tstate['state']?></option>
                                <?php } ?>
                                </select>
                                <input name="ShipStateOrProvince" type="text" size="25" style="display: none" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">&nbsp;</td>
                              <td align="left">&nbsp;</td>
                              <td align="left">&nbsp;</td>
                              <td width="120" class="titles">Shipper Phone:</td>
                              <td><input type="text" name="ShipPhoneNumber" size="32" /></td>
                              </tr>
                            </table>
                          </div>
                        <div class="TabbedPanelsContent">
                          <textarea name="Notes" style="width:450px" rows="10"></textarea>
                        </div>
                        <div class="TabbedPanelsContent">
                          <?php include "../../scripts/newdoc.php" ?>
                        </div>
                      </div>
                      </div></td>
                    </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="TabOrderdet">
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000"></td>
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
                      </table>
                      <input name="OrdDetID" type="hidden" id="OrdDetID" value="0" />
                      <script>var OrdDetID=0</script></td>
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
            <input type="hidden" name="MM_insert" value="frmorder" />
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
