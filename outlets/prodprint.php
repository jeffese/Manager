<?php
require_once("$vpth/scripts/init.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'Print');

$id = intval(_xget('id'));
preOrd("outl_prod", array('', 'ProdName', 'ShopStock', 'shelf', 'Shoplevel', 'Shopstkcntdate'));

$sql = "SELECT `OutletName` 
    FROM `{$_SESSION['DBCoy']}`.`outlets`
    WHERE `OutletID`=$id";
$row_TOutlets = getDBDataRow($dbh, $sql);

$From = "FROM `{$_SESSION['DBCoy']}`.items 
        INNER JOIN `{$_SESSION['DBCoy']}`.items_prod   ON items.ItemID=items_prod.ProductID
        INNER JOIN `{$_SESSION['DBCoy']}`.outlet       ON items_prod.ProductID=outlet.ProductID
        INNER JOIN `{$_SESSION['DBCoy']}`.outlets      ON outlet.OutletID=outlets.OutletID
        LEFT  JOIN `{$_SESSION['DBCoy']}`.`status`     ON `outlet`.Shopshelf = status.CategoryID 
        WHERE $whr=$id";

$sql = "SELECT outlet.*, ProdName, status.Category AS shelf {$From}{$orderval}";

$TProducts = getDBData($dbh, $sql);
$currentPage = 'prodprint.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
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
                <td><table width="243" border="0" cellpadding="1" cellspacing="1">
                  <tr>
                    <td width="107" style="height:30px; min-width:500px; background-image:url(/images/lbl<?php echo $vcat ?>.png); background-repeat:no-repeat">&nbsp;</td>
                    <td width="129" style="height:30px; min-width:500px; background-image:url(/images/lblproducts.png); background-repeat:no-repeat">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><span class="black-normal"><b><?php echo $vnm ?> : </b></span><span class="blue-normal"><b><?php echo $row_TOutlets['OutletName'] ?></b></span></td>
              </tr>
              <tr>
                <td><table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" class="boldwhite1">
                          <table width="100%" border="0" cellpadding="0" cellspacing="0">
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
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');">
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
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>

            </table></td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br /><?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?>
          </span></td>
        </tr>
      </table></td>
  </tr>
</table><script type="text/javascript">
$(document).ready(function(){
	print();
});
</script>
</body>
</html>