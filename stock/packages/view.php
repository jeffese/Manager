<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Packages'));
vetAccess('Stock', 'Packages', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 1);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Package]del.php?id=$id","","","find.php","print.php?id=$id","index.php");
$rec_status = 1;

$sql = "SELECT `items`.*, `items_pkgs`.*, classifications.catname AS cat FROM `{$_SESSION['DBCoy']}`.`items`
        INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs` ON items.ItemID=items_pkgs.PackageID
        LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `items`.Classification = classifications.catID 
        WHERE `PackageID`={$id}";
$row_TPacks = getDBDataRow($dbh, $sql);

$sql = "SELECT `items_pkgs_itms`.*, `ProdName`, `UnitPrice` 
    FROM `{$_SESSION['DBCoy']}`.`items_pkgs_itms`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items` ON `items_pkgs_itms`.ProductID=items.ItemID 
    WHERE `PackageID`={$id}";
$TItems = getDBData($dbh, $sql);

$sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
    WHERE `OutletID` IN (0{$row_TPacks['outlets']})";
$T_Outlet = getDBData($dbh, $sql);

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
  <script src="/lib/jquery-ui/js/jquery.js"></script>
  <script src="/lib/jquery-ui/js/jquery-ui.js"></script>
<script type="text/javascript" src="script.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    window.onload = function() {
        calItms();
		setdays();
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
          <td width="240" valign="top"><img src="/images/package.jpg" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblpackage.png); background-repeat:no-repeat">&nbsp;</td>
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
                    <td width="120" class="titles">Package ID:</td>
                    <td class="red-normal"><b><?php echo $row_TPacks['PackageID']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Service Code:</td>
                    <td align="left"><?php echo $row_TPacks['ProdCode'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Name:</td>
                    <td align="left"><?php echo $row_TPacks['ProdName'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td><?php echo $row_TPacks['cat'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Active:</td>
                    <td><input type="checkbox" name="InUse"<?php if ($row_TPacks['InUse'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Items:</td>
                    <td><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td>Value:</td>
                        <td><?php echo $row_TPacks['TotalValue'] ?></td>
                        <td>&nbsp;</td>
                        <td>Discount:</td>
                        <td><?php echo $row_TPacks['TotDisc'] ?></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Discount:</td>
                    <td><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><?php echo $row_TPacks['Dscnt'] ?></td>
                        <td>%</td>
                        <td><strong>=&gt;</strong></td>
                        <td><?php echo $row_TPacks['Discount'] ?></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Total Value:</td>
                    <td><?php echo $row_TPacks['Grandvalue'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td id="ordwords">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Life Time:</td>
                    <td><input type="checkbox" name="LimitedTime"<?php if ($row_TPacks['LimitedTime'] == 1) {
                echo " checked=\"checked\"";
            } ?> onclick="if (this.checked) $('#life').show(); else $('#life').hide()" disabled="disabled" /></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td><table border="0" cellspacing="2" cellpadding="2" id="life"<?php if ($row_TPacks['LimitedTime'] == 0) { ?> style="display:none"<?php } ?>>
                      <tr>
                        <td><table border="0" cellspacing="1" cellpadding="1">
                          <tr>
                            <td>Start Date:</td>
                            <td><?php echo $row_TPacks['StartDate'] ?></td>
                            <td><input name="wkday" type="hidden" id="wkday" value="<?php echo $row_TPacks['wkday'] ?>" /></td>
                            <td>End Date:</td>
                            <td><?php echo $row_TPacks['EndDate'] ?></td>
                            </tr>
                          </table></td>
                        </tr>
                      <tr>
                        <td><table border="0" cellspacing="1" cellpadding="1">
                          <tr>
                            <td><input type="checkbox" id="Sunday" disabled="disabled" /></td>
                            <td>Sunday</td>
                            <td>&nbsp;</td>
                            <td><input type="checkbox" id="Monday" disabled="disabled" /></td>
                            <td>Monday</td>
                            <td>&nbsp;</td>
                            <td><input type="checkbox" id="Tuesday" disabled="disabled" /></td>
                            <td>Tuesday</td>
                            <td>&nbsp;</td>
                            <td><input type="checkbox" id="Wednesday" disabled="disabled" /></td>
                            <td>Wednesday</td>
                            <td>&nbsp;</td>
                            <td><input type="checkbox" id="Thursday" disabled="disabled" /></td>
                            <td>Thursday</td>
                            <td>&nbsp;</td>
                            <td><input type="checkbox" id="Friday" disabled="disabled" /></td>
                            <td>Friday</td>
                            <td>&nbsp;</td>
                            <td><input type="checkbox" id="Saturday" disabled="disabled" /></td>
                            <td>Saturday</td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Outlets:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td nowrap="nowrap" class="h1">Selected Outlets</td>
                      </tr>
                      <tr>
                        <td valign="top"><select name="seloutlets" size="10" id="seloutlets">
                          <?php foreach ($T_Outlet as $row_T_Outlet) { ?>
                          <option value="<?php echo $row_T_Outlet['OutletID'] ?>"><?php echo $row_T_Outlet['OutletName'] ?></option>
                          <?php } ?>
                        </select>
                          <input type="hidden" name="outlets" id="outlets" value="<?php echo $row_TPacks['outlets']; ?>" /></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td valign="top" class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" readonly="readonly" style="width:450px"><?php echo $row_TPacks['Notes'] ?></textarea></td>
                  </tr>
                  <tr>
                    <td valign="top" class="titles">&nbsp;</td>
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
                            <td><?php $doc_shelf = 'Stock'.DS.'Packages';
							$doc_id = $id; ?>
                              <?php include "../../scripts/viewdoc.php" ?></td>
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
                        <td colspan="8" align="center" nowrap="nowrap" bgcolor="#000000" class="h1">Items</td>
                        </tr>
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Unit Price</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">% Dsc</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Discount</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Sales Price</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Total Value</td>
                        </tr>
                      <?php $j = 0;foreach ($TItems as $row_TItems) { ?>
                      <tr id="itm_<?php echo $j ?>">
                        <td><?php echo $row_TItems['PackItemID'] ?></td>
                        <td id="Name_<?php echo $j ?>"><?php echo $row_TItems['ProdName'] ?></td>
                        <td><input type="hidden" name="Quantity_<?php echo $j ?>" id="Quantity_<?php echo $j ?>" value="<?php echo $row_TItems['Quantity'] ?>" onChange="setthous(this, 1); calItm(<?php echo $j ?>)" /><?php echo $row_TItems['Quantity'] ?></td>
                        <td id="unitprice_<?php echo $j ?>"><?php echo $row_TItems['UnitPrice'] ?></td>
                        <td><input type="hidden" name="Discnt_<?php echo $j ?>" id="Discnt_<?php echo $j ?>" value="<?php echo $row_TItems['Discnt'] ?>" onchange="setthous(this, 0); dscItm(<?php echo $j ?>)" /><?php echo $row_TItems['Discnt'] ?></td>
                        <td><input type="hidden" name="Discount_<?php echo $j ?>" id="Discount_<?php echo $j ?>" value="<?php echo $row_TItems['Discount'] ?>" onchange="setthous(this, 0); discItm(<?php echo $j ?>)" /><?php echo $row_TItems['Discount'] ?></td>
                        <td id="salesprice_<?php echo $j ?>">&nbsp;</td>
                        <td id="Total_<?php echo $j ?>">&nbsp;</td>
                        </tr>
                      <?php $j++; } ?>
                      </table>
                      <input name="ItmID" type="hidden" id="ItmID" value="<?php echo $j ?>" />
  <script>var ItmID=<?php echo $j ?> </script></td>
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