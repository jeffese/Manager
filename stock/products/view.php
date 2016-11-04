<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Products'));
vetAccess('Stock', 'Products', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 1);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Product]del.php?id=$id","","","find.php","print.php?id=$id","index.php");
$rec_status = 1;

$vendor_supo = vendorFlds("VendorSup", "supo");
$sql = "SELECT `items`.*, `items_prod`.*, stock, $vendor_supo, classifications.catname AS cat, 
    brands.Category AS brand, units.Category AS unit, colorname, WarantyCode 
FROM `{$_SESSION['DBCoy']}`.`items` 
INNER JOIN `{$_SESSION['DBCoy']}`.items_prod   ON items.ItemID=items_prod.ProductID 
LEFT JOIN (
        SELECT `ProductID`, SUM(`ShopStock`) AS stock 
            FROM  `{$_SESSION['DBCoy']}`.`outlet` 
            GROUP BY  `ProductID`
    ) shop ON `items_prod`.ProductID = shop.ProductID
LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `VendorSup` ON `items_prod`.SupplierID = VendorSup.VendorID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `items`.Classification = classifications.catID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` brands ON `items_prod`.Brand = brands.CategoryID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` units ON `items_prod`.unit = units.CategoryID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`colors` ON `items_prod`.colour = colors.colorid 
LEFT JOIN `{$_SESSION['DBCoy']}`.`warranty` ON `items_prod`.warranty = warranty.WarantyID 
WHERE `items_prod`.`ProductID`={$id}";
$row_TProducts = getDBDataRow($dbh, $sql);

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
<script type="text/javascript" src="/personnel/payslips/payees/resource.js"></script>
<script language="JavaScript1.2" type="text/javascript">

</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->


</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                  <tr></tr>
                </table>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
$xid = '';
$label = '';
?>
                              <?php include('../../scripts/viewpix.php'); ?>
                              &nbsp;</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td><table border="0" cellpadding="4" cellspacing="4">
                              <tr>
                                <td width="120" class="titles">Product ID:</td>
                                <td class="red-normal"><b><?php echo $id; ?></b></td>
                              </tr>
                              <tr>
                                <td class="titles">Short Name:</td>
                                <td align="left"><?php echo $row_TProducts['ProdCode'] ?></td>
                              </tr>
                              <tr>
                                <td class="titles">Full Name:</td>
                                <td><?php echo $row_TProducts['ProdName'] ?></td>
                              </tr>
                              <tr>
                                <td class="titles">&nbsp;</td>
                                <td align="left">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="2"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                                  <tr>
                                    <td class="titles">Equipment:</td>
                                    <td><input name="IsEquip" type="checkbox" value="1"  <?php if (!(strcmp($row_TProducts['IsEquip'], 1))) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                                    <td>&nbsp;</td>
                                    <td class="titles">Serialized:</td>
                                    <td><input name="serialized" type="checkbox" value="1"  <?php if (!(strcmp($row_TProducts['serialized'], 1))) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                                    <td>&nbsp;</td>
                                    <td class="titles">Active:</td>
                                    <td><input name="InUse" type="checkbox" value="1"  <?php if (!(strcmp($row_TProducts['InUse'], 1))) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
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
                                <li class="TabbedPanelsTab" tabindex="0">Storage</li>
                                <li class="TabbedPanelsTab" tabindex="0">Orders</li>
                                <li class="TabbedPanelsTab" tabindex="0">Transfers</li>
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
                                          <td><input type="radio" name="status" value="1" size="32" <?php if (!(strcmp($row_TProducts['status'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                                          <td>New</td>
                                          <td><input type="radio" name="status" value="2" size="32" <?php if (!(strcmp($row_TProducts['status'], 2))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                                          <td>Used</td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td class="titles">Category:</td>
                                      <td><?php echo $row_TProducts['cat'] ?></td>
                                    </tr>
                                    <tr>
                                      <td class="titles">Brand:</td>
                                      <td><?php echo $row_TProducts['brand'] ?></td>
                                    </tr>
                                    <tr>
                                      <td class="titles">Colour:</td>
                                      <td><?php echo $row_TProducts['colorname'] ?></td>
                                    </tr>
                                    <tr>
                                      <td class="titles">Unit Type:</td>
                                      <td><?php echo $row_TProducts['unit'] ?></td>
                                    </tr>
                                    <tr>
                                      <td nowrap="nowrap" class="titles">Units in Pack:</td>
                                      <td><?php echo $row_TProducts['unitsinpack'] ?></td>
                                    </tr>
                                    <tr>
                                      <td class="titles">Unit Price:</td>
                                      <td><?php echo $row_TProducts['UnitPrice'] ?></td>
                                    </tr>
                                    <tr>
                                      <td class="titles">&nbsp;</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="center"><table border="0" cellspacing="2" cellpadding="2">
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
                                          <td><?php echo $row_TProducts['weight'] ?></td>
                                          <td>&nbsp;</td>
                                          <td><?php echo $row_TProducts['length'] ?></td>
                                          <td>&nbsp;</td>
                                          <td><?php echo $row_TProducts['width'] ?></td>
                                          <td>&nbsp;</td>
                                          <td><?php echo $row_TProducts['breadth'] ?></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td class="titles">Warrantee:</td>
                                      <td align="left"><?php echo $row_TProducts['WarantyCode'] ?></td>
                                    </tr>
                                    <tr>
                                      <td width="120" valign="top" class="titles">Description:</td>
                                      <td align="left"><textarea name="Description" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TProducts['Description'] ?></textarea></td>
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
                                      <td width="322" align="left"><?php echo $row_TProducts['supo'] ?></td>
                                    </tr>
                                    <tr>
                                      <td width="120" class="titles">Lead Time:</td>
                                      <td align="left" nowrap="nowrap"><?php echo $row_TProducts['LeadTime'] ?></td>
                                    </tr>
                                    <tr>
                                      <td class="titles">Re-Order Level:</td>
                                      <td align="left"><?php echo $row_TProducts['ReorderLevel'] ?></td>
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
                                  <iframe width="700" height="400" src="outlets.php?id=<?php echo $id; ?>"></iframe>
                                </div>
                                <div class="TabbedPanelsContent">
                                  <iframe width="700" height="400" src="orders.php?id=<?php echo $id; ?>"></iframe>
                                </div>
                                <div class="TabbedPanelsContent">
                                  <iframe width="700" height="400" src="transfers.php?id=<?php echo $id; ?>"></iframe>
                                </div>
                                <div class="TabbedPanelsContent">
                                  <textarea name="Notes" rows="10" readonly="readonly" style="width:450px"><?php echo $row_TProducts['Notes'] ?></textarea>
                                </div>
                                <div class="TabbedPanelsContent">
                                  <?php $doc_shelf = 'Stock'.DS.'Products';
							$doc_id = $id; ?>
                                  <?php include "../../scripts/viewdoc.php" ?>
                                </div>
                              </div>
                            </div></td>
                          </tr>
                          <tr>
                            <td></td>
                          </tr>
                          <tr>
                            <td><?php include('../../scripts/buttonset.php'); ?></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="4" cellpadding="4">
                        </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                  <table width="100%" border="0" cellspacing="4" cellpadding="4">
                  </table></td>
              </tr>
            </table></td>
          </tr>
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
