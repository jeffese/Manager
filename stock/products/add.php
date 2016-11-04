<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Products'));
vetAccess('Stock', 'Products', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmprod","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmprod") {
  
	$sql = sprintf("INSERT INTO `%s`.`items`(`typ`, `ExoodID`, `ProdCode`, `ProdName`, 
            `Description`, `picturefile`, `Classification`, `category`, `status`, 
            `UnitPrice`, `WebPrice`, `itmtax`, `InUse`, `Notes`, `exood`, `exoodsales`, 
            `InfoLoad`, `pixLoad`, `StockLoad`) 
            VALUES (1,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                       $_SESSION['DBCoy'],
                       GSQLStr(_xpost('ExoodID'), "int"),
                       GSQLStr(_xpost('ProdCode'), "text"),
                       GSQLStr(_xpost('ProdName'), "text"),
                       GSQLStr(_xpost('description'), "text"),
                       0,
                       GSQLStr(_xpost('Classification'), "intn"),
                       GSQLStr(_xpost('category'), "int"),
                       GSQLStr(_xpost('status'), "intn"),
                       GSQLStr(_xpost('UnitPrice'), "double"),
                       GSQLStr(_xpost('WebPrice'), "double"),
                       GSQLStr(_xpost('itmtax'), "double"),
                       _xpostchk('InUse'),
                       GSQLStr(_xpost('Notes'), "text"),
                       _xpostchk('exood'),
                       _xpostchk('exoodsales'),
                       _xpostchk('InfoLoad'),
                       _xpostchk('pixLoad'),
                       _xpostchk('StockLoad'));
	$insert = runDBQry($dbh, $sql);
	
	if ($insert > 0) {
            $recid = mysqli_insert_id($dbh);
            docs('Stock'.DS.'Products', $recid);
            $pix = newpix(ROOT . PRODPIX_DIR, '', $recid, 20, array(600, 200));

            if ($pix['pixcode']!='') {
                    $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`items` 
                        SET picturefile = %s
                        WHERE `ItemID` = %s",
                            $pix['pixcode'], $recid);
                    $insert = runDBQry($dbh, $sql);
            }

            $sql = sprintf("INSERT INTO `%s`.`items_prod`(ProductID, `Brand`, `colour`, 
                `weight`, `length`, `width`, `breadth`, `warranty`, `SupplierID`, 
                `xbarcodes`, `unit`, `unitsinpack`, `webstock`, `ReorderLevel`, 
                `UnitsOnOrder`, `IsEquip`, `serialized`, `LeadTime`) 
                VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                           $_SESSION['DBCoy'],
                           $recid,
                           GSQLStr(_xpost('Brand'), "intn"),
                           GSQLStr(_xpost('colour'), "intn"),
                           GSQLStr(_xpost('weight'), "double"),
                           GSQLStr(_xpost('length'), "double"),
                           GSQLStr(_xpost('width'), "double"),
                           GSQLStr(_xpost('breadth'), "double"),
                           GSQLStr(_xpost('warranty'), "intn"),
                           GSQLStr(_xpost('SupplierID'), "intn"),
                           GSQLStr(_xpost('xbarcodes'), "text"),
                           GSQLStr(_xpost('unit'), "intn"),
                           GSQLStr(_xpost('unitsinpack'), "double"),
                           GSQLStr(_xpost('webstock'), "double"),
                           GSQLStr(_xpost('ReorderLevel'), "double"),
                           GSQLStr(_xpost('UnitsOnOrder'), "double"),
                           _xpostchk('IsEquip'),
                           _xpostchk('serialized'),
                           GSQLStr(_xpost('LeadTime'), "text"));
            runDBQry($dbh, $sql);
	
            header("Location: view.php?id=$recid");
            exit;
	}
}

$TSup = getVendor(2);
$TCat = getClassify(2);
$TBrand = getCat('brand');
$TUnit  = getCat('units');
$TShelf = getCat('shelf');

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.colors ORDER BY colorname";
$colors = getDBData($dbh, $sql);

$sql = "SELECT WarantyID, WarantyCode FROM `{$_SESSION['DBCoy']}`.`warranty` ORDER BY WarantyCode";
$TWarant = getDBData($dbh, $sql);

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
<script type="text/javascript" src="/accounts/payslips/payees/resource.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["ProdCode", "", 
            ["req", "Enter Short Name"]
        ],
        ["ProdName", "", 
            ["req", "Enter Full Name"]
        ]
    ]
    
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
        <td width="240" valign="top"><img src="/images/products.jpg" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblproducts.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmprod" id="frmprod">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">Info</td>
              </tr>
              <tr>
                <td><?php $pixcnt = 20; $max = 20000000; ?>
<?php include('../../scripts/newpix.php'); ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td width="120" class="titles">Short Name:</td>
                    <td align="left"><input type="text" name="ProdCode" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Full Name:</td>
                    <td><input type="text" name="ProdName" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td class="titles">Equipment:</td>
                        <td><input name="IsEquip" type="checkbox" value="1" disabled="disabled" /></td>
                        <td>&nbsp;</td>
                        <td class="titles">Serialized:</td>
                        <td><input name="serialized" type="checkbox" value="1" /></td>
                        <td>&nbsp;</td>
                        <td class="titles">Active:</td>
                        <td><input name="InUse" type="checkbox" value="1" checked="checked" /></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><div id="Details" class="TabbedPanels">
                  <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0">Info</li>
                    <li class="TabbedPanelsTab" tabindex="0">Barcodes</li>
                    <li class="TabbedPanelsTab" tabindex="0">Supply</li>
                    <li class="TabbedPanelsTab" tabindex="0">Notes</li>
                    <li class="TabbedPanelsTab" tabindex="0">Documents</li>
                  </ul>
                  <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td class="titles">Status:</td>
                          <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                            <tr>
                              <td><input type="radio" name="status" value="1" size="32" /></td>
                              <td>New</td>
                              <td><input type="radio" name="status" value="2" size="32" /></td>
                              <td>Used</td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td class="titles">Category:</td>
                          <td><select name="Classification">
                            <option value=""></option>
                            <?php foreach ($TCat as $row_TCat) { ?>
                            <option value="<?php echo $row_TCat['catID'] ?>"><?php echo $row_TCat['catname'] ?></option>
                            <?php } ?>
                          </select>
                            <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/stock/cat/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Brand:</td>
                          <td><select name="Brand">
                            <?php foreach ($TBrand as $row_TBrand) { ?>
                            <option value="<?php echo $row_TBrand['CategoryID'] ?>"><?php echo $row_TBrand['Category'] ?></option>
                            <?php } ?>
                          </select>
                            <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/stock/products/brand/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Colour:</td>
                          <td><select name="colour">
                            <option value="" selected="selected">Select Color</option>
                            <?php foreach ($colors as $row_colors) { ?>
                            <option value="<?php echo $row_colors['colorid'] ?>" style="background-color: <?php echo $row_colors['colorcode'] ?>;color: Black;"><?php echo $row_colors['colorname'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td class="titles">Unit Type: </td>
                          <td><select name="unit">
                            <option value=""></option>
                            <?php foreach ($TUnit as $row_TUnit) { ?>
                            <option value="<?php echo $row_TUnit['CategoryID'] ?>"><?php echo $row_TUnit['Category'] ?></option>
                            <?php } ?>
                          </select>
                            <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/stock/products/unit/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Units in Pack:</td>
                          <td><input type="text" name="unitsinpack" size="10" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Unit Price:</td>
                          <td><input type="text" name="UnitPrice" size="12"<?php if ($access['Edit Prices']==0) { ?> readonly="readonly"<?php } ?> /></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="2"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr>
                              <td class="h1">Weight</td>
                              <td>&nbsp;</td>
                              <td class="h1">Length</td>
                              <td>&nbsp;</td>
                              <td class="h1">Width</td>
                              <td>&nbsp;</td>
                              <td class="h1">Breadth</td>
                              </tr>
                            <tr>
                              <td><input type="text" name="weight" size="10" /></td>
                              <td>&nbsp;</td>
                              <td><input type="text" name="length" size="10" /></td>
                              <td>&nbsp;</td>
                              <td><input type="text" name="width" size="10" /></td>
                              <td>&nbsp;</td>
                              <td><input type="text" name="breadth" size="10" /></td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td class="titles">Warrantee:</td>
                          <td align="left"><select name="warranty">
                            <option value=""></option>
                            <?php foreach ($TWarant as $row_TWarant) { ?>
                            <option value="<?php echo $row_TWarant['WarantyID'] ?>"><?php echo $row_TWarant['WarantyCode'] ?></option>
                            <?php } ?>
                          </select>
                            <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/stock/products/waranty/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td width="120" valign="top" class="titles">Description:</td>
                          <td align="left"><textarea name="Description" style="width:300px" rows="3"></textarea></td>
                        </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table width="100%" border="0" cellpadding="4" cellspacing="4">
                        <tr>
                          <td class="h1">Barcodes
                            <input type="hidden" name="xbarcodes" /></td>
                          </tr>
                        <tr>
                          <td><select name="barcodes" size="10">
                          </select></td>
                          </tr>
                        <tr>
                          <td>&nbsp;</td>
                          </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" class="titles">Supplier:</td>
                          <td width="322" align="left"><select name="SupplierID">
                            <option value=""></option>
                            <?php foreach ($TSup as $row_TSup) { ?>
                            <option value="<?php echo $row_TSup['VendorID'] ?>"><?php echo $row_TSup['VendorName'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Lead Time:</td>
                          <td align="left" nowrap="nowrap"><input type="text" name="LeadTime" size="32" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Re-Order Level:</td>
                          <td align="left"><input type="text" name="ReorderLevel" size="10" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Units in Stock:</td>
                          <td><input type="text" name="UnitsInStock" size="10" /></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Units on Order:</td>
                          <td align="left"><input type="text" name="UnitsOnOrder" size="10" /></td>
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
                <td><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
            <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
            <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
            <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
            <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
            <script type="text/javascript">
var mCal;
window.onload = function() {
    mCal = new dhtmlxCalendarObject('dateofbirth', true, {isYearEditable: true, isMonthEditable: true});
	mCal.setSkin('dhx_black');
    mCal2 = new dhtmlxCalendarObject('datehired', true, {isYearEditable: true, isMonthEditable: true});
	mCal2.setSkin('dhx_black');
    mCal3 = new dhtmlxCalendarObject('datefired', true, {isYearEditable: true, isMonthEditable: true});
	mCal3.setSkin('dhx_black');
}
      </script></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

          </table>
            <input type="hidden" name="MM_insert" value="frmprod" />
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
