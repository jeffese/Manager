<?php
require_once("$vpth/scripts/init.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'View');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, $access['Print'], 1, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "prodedit.php?id=$id", "", "", "", "", "", "$_pth/prodprint.php?id=$id", "");
$rec_status = 4;

preOrd("outl_prod", array('', 'ProdName', 'ShopStock', 'shelf', 'Shoplevel', 'Shopstkcntdate'));

$From = "FROM `{$_SESSION['DBCoy']}`.items 
        INNER JOIN `{$_SESSION['DBCoy']}`.items_prod   ON items.ItemID=items_prod.ProductID
        INNER JOIN `{$_SESSION['DBCoy']}`.outlet       ON items_prod.ProductID=outlet.ProductID
        INNER JOIN `{$_SESSION['DBCoy']}`.outlets      ON outlet.OutletID=outlets.OutletID
        LEFT  JOIN `{$_SESSION['DBCoy']}`.`status`     ON `outlet`.Shopshelf = status.CategoryID 
        WHERE $whr=$id";

$sql = "SELECT outlet.*, ProdName, status.Category AS shelf {$From}{$orderval}";

$currentPage = 'prodview.php';
$maxRows_TProducts = 30;

$TabArray = 'TProducts';
require_once (ROOT . '/scripts/fetchdata.php');
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
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="boldwhite1"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
              <tr align="center" bgcolor="#666666" class="boldwhite1">
                <td nowrap="nowrap"><?php echo setOrderTitle('Item', $currentPage, 1, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Stock', $currentPage, 2, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Shelf', $currentPage, 3, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Re-Order Level', $currentPage, 4, $ord, $asc); ?></td>
                <td nowrap="nowrap"><?php echo setOrderTitle('Last Stock Date', $currentPage, 5, $ord, $asc); ?></td>
              </tr>
              <?php $j=1;
	   foreach ($TProducts as $row_TProducts) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
              <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onclick="$(window.parent.document).find('#prodesc').html('<?php echo $row_TProducts['ShopNotes'] ?>')">
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['ProdName'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['ShopStock'] ?></b></td>
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['shelf'] ?></b></td>
                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['Shoplevel'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['Shopstkcntdate'] ?></b></td>
              </tr>
              <?php $j++;} ?>
            </table></td>
          </tr>

        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php include("$vpth/scripts/buttonset.php")?>
</body>
</html>
