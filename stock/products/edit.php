<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Products'));
vetAccess('Stock', 'Products', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmprod","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Stock'.DS.'Products';
$doc_id = $id;

if (_xpost("MM_update") == "frmprod") {
  
	$pix = newpix(ROOT . PRODPIX_DIR, '', $id, 20, array(600, 200));
	
	$sql = sprintf("UPDATE `%s`.`items` SET `ExoodID`=%s,`ProdCode`=%s,`ProdName`=%s,`Description`=%s,
            `picturefile`=%s,`Classification`=%s,`category`=%s,`status`=%s,`UnitPrice`=%s,`WebPrice`=%s,
            `InUse`=%s,`Notes`=%s,`exood`=%s,`exoodsales`=%s,`InfoLoad`=%s,`pixLoad`=%s,`StockLoad`=%s 
            WHERE ItemID=%s",
                       $_SESSION['DBCoy'],
                       GSQLStr(_xpost('ExoodID'), "int"),
                       GSQLStr(_xpost('ProdCode'), "text"),
                       GSQLStr(_xpost('ProdName'), "text"),
                       GSQLStr(_xpost('Description'), "text"),
                       $pix['pixcode'],
                       GSQLStr(_xpost('Classification'), "intn"),
                       GSQLStr(_xpost('category'), "intn"),
                       GSQLStr(_xpost('status'), "intn"),
                       GSQLStr(_xpost('UnitPrice'), "double"),
                       GSQLStr(_xpost('WebPrice'), "double"),
                       _xpostchk('InUse'),
                       GSQLStr(_xpost('Notes'), "text"),
                       _xpostchk('exood'),
                       _xpostchk('exoodsales'),
                       _xpostchk('InfoLoad'),
                       _xpostchk('pixLoad'),
                       _xpostchk('StockLoad'),
                       $id);
	$update = runDBQry($dbh, $sql);
	$sql = sprintf("UPDATE `%s`.`items_prod` SET `Brand`=%s,`colour`=%s,`weight`=%s,`length`=%s,
            `width`=%s,`breadth`=%s,`warranty`=%s,`SupplierID`=%s,`xbarcodes`=%s,
            `unit`=%s,`unitsinpack`=%s,`webstock`=%s,`ReorderLevel`=%s,`UnitsOnOrder`=%s,
            `IsEquip`=%s,`serialized`=%s,`LeadTime`=%s WHERE ProductID=%s",
                       $_SESSION['DBCoy'],
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
                       GSQLStr(_xpost('LeadTime'), "text"),
                       $id);
	$update = runDBQry($dbh, $sql);
        docs($doc_shelf, $doc_id);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT `items`.*, `items_prod`.*, stock 
        FROM `{$_SESSION['DBCoy']}`.`items` 
        INNER JOIN `{$_SESSION['DBCoy']}`.items_prod   ON items.ItemID=items_prod.ProductID 
        LEFT JOIN (
                SELECT `ProductID`, SUM(`ShopStock`) AS stock 
                    FROM  `{$_SESSION['DBCoy']}`.`outlet` 
                    GROUP BY  `ProductID`
            ) shop ON `items_prod`.ProductID = shop.ProductID
            WHERE `items_prod`.`ProductID`={$id}";
$row_TProducts = getDBDataRow($dbh, $sql);

$TSup = getVendor(2);
$TCat = getClassify(2);
$TBrand = getCat('brand');
$TUnit  = getCat('units');

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
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
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
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
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
<?php 
$pictfld = $row_TProducts['picturefile'];
$fpath = $id;
$pixdir = PRODPIX_DIR;
$pixi = 'x';
?>
<?php include('../../scripts/editpix.php'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td width="120" class="titles">Product ID:</td>
                    <td class="red-normal"><b><?php echo $row_TProducts['ProductID']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Short Name:</td>
                    <td align="left"><input type="text" name="ProdCode" value="<?php echo $row_TProducts['ProdCode'] ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Full Name:</td>
                    <td><input type="text" name="ProdName" value="<?php echo $row_TProducts['ProdName'] ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td class="titles">Equipment:</td>
                        <td><input name="IsEquip" type="checkbox" value="1"  <?php if (!(strcmp($row_TProducts['IsEquip'], 1))) {echo "checked=\"checked\"";} ?> /></td>
                        <td>&nbsp;</td>
                        <td class="titles">Serialized:</td>
                        <td><input name="serialized" type="checkbox" value="1"  <?php if (!(strcmp($row_TProducts['serialized'], 1))) {echo "checked=\"checked\"";} ?> /></td>
                        <td>&nbsp;</td>
                        <td class="titles">Active:</td>
                        <td><input name="InUse" type="checkbox" value="1"  <?php if (!(strcmp($row_TProducts['InUse'], 1))) {echo "checked=\"checked\"";} ?> /></td>
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
                              <td><input type="radio" name="status" value="1" size="32" <?php if (!(strcmp($row_TProducts['status'], 1))) { echo "checked=\"checked\""; } ?> /></td>
                              <td>New</td>
                              <td><input type="radio" name="status" value="2" size="32" <?php if (!(strcmp($row_TProducts['status'], 2))) { echo "checked=\"checked\""; } ?> /></td>
                              <td>Used</td>
                              </tr>
                            </table></td>
                        </tr>
                        <tr>
                          <td class="titles">Category:</td>
                          <td><select name="Classification">
                            <option value=""></option>
                            <?php foreach ($TCat as $row_TCat) { ?>
                            <option value="<?php echo $row_TCat['catID'] ?>" <?php if (!(strcmp($row_TProducts['Classification'], $row_TCat['catID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TCat['catname'] ?></option>
                            <?php } ?>
                          </select>
                            <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/stock/cat/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Brand:</td>
                          <td><select name="Brand">
                            <?php foreach ($TBrand as $row_TBrand) { ?>
                            <option value="<?php echo $row_TBrand['CategoryID'] ?>" <?php if (!(strcmp($row_TProducts['Brand'], $row_TBrand['CategoryID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TBrand['Category'] ?></option>
                            <?php } ?>
                          </select>                            <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/stock/products/brand/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Colour:</td>
                          <td><select name="colour">
                            <option value="" selected="selected">Select Color</option>
                            <?php foreach ($colors as $row_colors) { ?>
                            <option value="<?php echo $row_colors['colorid'] ?>" style="background-color: <?php echo $row_colors['colorcode'] ?>;color: Black;" <?php if (!(strcmp($row_colors['colorid'], $row_TProducts['colour']))) {echo "selected=\"selected\"";} ?>><?php echo $row_colors['colorname'] ?></option>
                            <?php } ?>
                            </select></td>
                        </tr>
                        <tr>
                          <td class="titles">Unit Type: </td>
                          <td><select name="unit">
                            <option value=""></option>
                            <?php foreach ($TUnit as $row_TUnit) { ?>
                            <option value="<?php echo $row_TUnit['CategoryID'] ?>" <?php if (!(strcmp($row_TProducts['unit'], $row_TUnit['CategoryID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TUnit['Category'] ?></option>
                            <?php } ?>
                            </select>
                            <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/stock/products/unit/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Units in Pack:</td>
                          <td><input type="text" name="unitsinpack" value="<?php echo $row_TProducts['unitsinpack'] ?>" size="10" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Unit Price:</td>
                          <td><input type="text" name="UnitPrice" value="<?php echo $row_TProducts['UnitPrice'] ?>" size="12"<?php if ($access['Edit Prices']==0) { ?> readonly="readonly"<?php } ?> /></td>
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
                              <td><input type="text" name="weight" value="<?php echo $row_TProducts['weight'] ?>" size="10" /></td>
                              <td>&nbsp;</td>
                              <td><input type="text" name="length" value="<?php echo $row_TProducts['length'] ?>" size="10" /></td>
                              <td>&nbsp;</td>
                              <td><input type="text" name="width" value="<?php echo $row_TProducts['width'] ?>" size="10" /></td>
                              <td>&nbsp;</td>
                              <td><input type="text" name="breadth" value="<?php echo $row_TProducts['breadth'] ?>" size="10" /></td>
                              </tr>
                            </table></td>
                        </tr>
                        <tr>
                          <td class="titles">Warrantee:</td>
                          <td align="left"><select name="warranty">
                            <option value=""></option>
                            <?php foreach ($TWarant as $row_TWarant) { ?>
                            <option value="<?php echo $row_TWarant['WarantyID'] ?>" <?php if (!(strcmp($row_TProducts['warranty'], $row_TWarant['WarantyID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TWarant['WarantyCode'] ?></option>
                            <?php } ?>
                            </select>
                            <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/stock/products/waranty/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td width="120" valign="top" class="titles">Description:</td>
                          <td align="left"><textarea name="Description" style="width:300px" rows="3"><?php echo $row_TProducts['Description'] ?></textarea></td>
                        </tr>
                        </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table width="100%" border="0" cellpadding="4" cellspacing="4">
                        <tr>
                          <td class="h1">Barcodes
                            <input type="hidden" name="xbarcodes" value="<?php echo $row_TProducts['xbarcodes']; ?>" /></td>
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
                            <option value="<?php echo $row_TSup['VendorID'] ?>" <?php if (!(strcmp($row_TProducts['SupplierID'],$row_TSup['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TSup['VendorName'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Lead Time:</td>
                          <td align="left" nowrap="nowrap"><input type="text" name="LeadTime" value="<?php echo $row_TProducts['LeadTime'] ?>" size="32" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Re-Order Level:</td>
                          <td align="left"><input type="text" name="ReorderLevel" value="<?php echo $row_TProducts['ReorderLevel'] ?>" size="10" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Units in Stock:</td>
                          <td><?php echo $row_TProducts['stock'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Units on Order:</td>
                          <td align="left"><?php echo $row_TProducts['UnitsOnOrder'] ?></td>
                        </tr>
                        </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <textarea name="Notes" style="width:450px" rows="10"><?php echo $row_TProducts['Notes'] ?></textarea>
                    </div>
                    <div class="TabbedPanelsContent">
                      <?php include "../../scripts/editdoc.php" ?>
                    </div>
                  </div>
                </div></td>
</tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmprod" />
            <input type="hidden" name="ProductID" value="<?php echo $row_TProducts['ProductID']; ?>" />
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
<!--
var Tabs = new Spry.Widget.TabbedPanels("Details");
//-->
</script>
</body>
</html>
