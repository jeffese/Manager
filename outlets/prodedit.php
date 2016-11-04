<?php
require_once("$vpth/scripts/init.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'Edit');
//isOutlet($_SESSION['outlets_dept']);

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "", "frmprodlist", "", "prodview.php?id=$id", "", "", "", "");
$rec_status = 3;

if (_xpost("MM_update") == "frmprodlist") {
    $cnt = intval(_xpost('cnt'));
    for ($q = 1; $q < $cnt; $q++) {
        if (!isset($_POST["ProductID$q"]))
            continue;
        $sql = sprintf("UPDATE `%s`.`outlet` SET `Shopshelf`=%s,`Shoplevel`=%s, 
            `Shopstkcntdate`=%s,`ShopNotes`=%s
             WHERE `ProductID`=%s AND `OutletID`=%s",
                   $_SESSION['DBCoy'],
                   GSQLStr(_xpost("Shopshelf$q"), "int"),
                   GSQLStr(_xpost("Shoplevel$q"), "int"),
                   GSQLStr(_xpost("Shopstkcntdate$q"), "date"),
                   GSQLStr(_xpost("ShopNotes$q"), "text"),
                   GSQLStr(_xpost("ProductID$q"), "int"),
                   GSQLStr(_xpost("OutletID$q"), "int"));
        runDBQry($dbh, $sql);
    }
    header("Location: prodview.php");
    exit;
}

preOrd("outl_prod", array('', 'ProdName', 'ShopStock', 'shelf', 'Shoplevel', 'Shopstkcntdate'));

$From = "FROM `{$_SESSION['DBCoy']}`.items 
        INNER JOIN `{$_SESSION['DBCoy']}`.items_prod   ON items.ItemID=items_prod.ProductID
        INNER JOIN `{$_SESSION['DBCoy']}`.outlet       ON items_prod.ProductID=outlet.ProductID
        INNER JOIN `{$_SESSION['DBCoy']}`.outlets      ON outlet.OutletID=outlets.OutletID
        LEFT  JOIN `{$_SESSION['DBCoy']}`.`status`     ON `outlet`.Shopshelf = status.CategoryID 
        WHERE $whr=$id";

$sql = "SELECT outlet.*, ProdName, status.Category AS shelf {$From}{$orderval}";

$currentPage = 'prodedit.php';
$maxRows_TProducts = 30;

$TabArray = 'TProducts';
require_once (ROOT . '/scripts/fetchdata.php');
$TShelf = getCat('shelf');
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
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
var cals=0, mCal = new Array();
window.onload = function() {
    for (var i=1; i<cals; i++) {
        mCal = new dhtmlxCalendarObject('Shopstkcntdate'+i, true, {isYearEditable: true, isMonthEditable: true});
	mCal.setSkin('dhx_black');
    }
}
      </script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td bgcolor="#FFFBF0"><form action="" method="post" id="frmprodlist" name="frmprodlist"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
              <tr align="center" bgcolor="#666666" class="boldwhite1">
                <td nowrap="nowrap">Item</td>
                <td nowrap="nowrap">Stock</td>
                <td nowrap="nowrap">Shelf</td>
                <td nowrap="nowrap">Re-Order Level</td>
                <td nowrap="nowrap">Last Stock Date</td>
              </tr>
              <?php $j=1;
	   foreach ($TProducts as $row_TProducts) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
              <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onclick="$(window.parent.document).find('#prodesc').html('<?php echo $row_TProducts['ShopNotes'] ?>')">
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['ProdName'] ?></b><input type="hidden" name="ProductID<?php echo $j ?>" value="<?php echo $row_TProducts['ProductID'] ?>" /><input type="hidden" name="OutletID<?php echo $j ?>" value="<?php echo $row_TProducts['OutletID'] ?>" /></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['ShopStock'] ?></b></td>
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><select name="Shopshelf<?php echo $j ?>">
                  <option value=""></option>
                  <?php foreach ($TShelf as $row_TShelf) { ?>
                  <option value="<?php echo $row_TShelf['CategoryID'] ?>" <?php if (!(strcmp($row_TProducts['Shopshelf'], $row_TShelf['CategoryID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TShelf['Category'] ?></option>
                  <?php } ?>
                </select></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input type="text" name="Shoplevel<?php echo $j ?>" value="<?php echo $row_TProducts['Shoplevel'] ?>" onchange="numme(this)" style="width:30px" /></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input name="Shopstkcntdate<?php echo $j ?>" type="text" id="Shopstkcntdate<?php echo $j ?>" style="width:70px" value="<?php echo $row_TProducts['Shopstkcntdate'] ?>" /><input type="hidden" name="ShopNotes<?php echo $j ?>" value="<?php echo $row_TProducts['ShopNotes'] ?>" /></td>
              </tr>
              <?php $j++;} ?>
            <script type="text/javascript"> cals = <?php echo $j ?> </script>
            </table>
                <input type="hidden" name="MM_update" value="frmprodlist" /><input type="hidden" name="cnt" value="<?php echo $j ?>" />
            </form>
            <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
            <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
            <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
            <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
            <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php include("$vpth/scripts/buttonset.php")?>
</body>
</html>
