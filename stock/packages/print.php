<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Packages'));
vetAccess('Stock', 'Packages', 'Print');

$id = _xget('id');

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
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script type="text/javascript" src="script.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/xxpix.jpg" ?>" /></td>
        <td><span class="coytxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblpackage.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
            <table border="0" cellpadding="4" cellspacing="4">
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
                        <td>&nbsp;</td>
                        <td>End Date:</td>
                        <td><?php echo $row_TPacks['EndDate'] ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><table border="0" cellspacing="1" cellpadding="1">
                      <tr>
                        <td><input type="checkbox" name="Sunday"<?php if ($row_TPacks['Sunday'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                        <td>Sunday</td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" name="Monday"<?php if ($row_TPacks['Monday'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                        <td>Monday</td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" name="Tuesday"<?php if ($row_TPacks['Tuesday'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                        <td>Tuesday</td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" name="Wednesday"<?php if ($row_TPacks['Wednesday'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                        <td>Wednesday</td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" name="Thursday"<?php if ($row_TPacks['Thursday'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                        <td>Thursday</td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" name="Friday"<?php if ($row_TPacks['Friday'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                        <td>Friday</td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" name="Saturday"<?php if ($row_TPacks['Saturday'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                        <td>Saturday</td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td valign="top" class="titles">Notes:</td>
                <td><textarea name="Notes" rows="5" readonly="readonly" style="width:450px"><?php echo $row_TPacks['Notes'] ?></textarea></td>
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
                    <td><input type="hidden" name="Quantity_<?php echo $j ?>" id="Quantity_<?php echo $j ?>" value="<?php echo $row_TItems['Quantity'] ?>" onchange="setthous(this, 1); calItm(<?php echo $j ?>)" />
                      <?php echo $row_TItems['Quantity'] ?></td>
                    <td id="unitprice_<?php echo $j ?>"><?php echo $row_TItems['UnitPrice'] ?></td>
                    <td><input type="hidden" name="Discnt_<?php echo $j ?>" id="Discnt_<?php echo $j ?>" value="<?php echo $row_TItems['Discnt'] ?>" onchange="setthous(this, 0); dscItm(<?php echo $j ?>)" />
                      <?php echo $row_TItems['Discnt'] ?></td>
                    <td><input type="hidden" name="Discount_<?php echo $j ?>" id="Discount_<?php echo $j ?>" value="<?php echo $row_TItems['Discount'] ?>" onchange="setthous(this, 0); discItm(<?php echo $j ?>)" />
                      <?php echo $row_TItems['Discount'] ?></td>
                    <td id="salesprice_<?php echo $j ?>">&nbsp;</td>
                    <td id="Total_<?php echo $j ?>">&nbsp;</td>
                  </tr>
                  <?php $j++; } ?>
                </table>
                  <input name="ItmID" type="hidden" id="ItmID" value="<?php echo $j ?>" />
                  <script>var ItmID=<?php echo $j ?> </script></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="4" cellpadding="4">

          </table></td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
    calItms();
    print();
});
</script>
</body>
</html>