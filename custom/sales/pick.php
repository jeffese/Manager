<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Transfers'));
//vetAccess('Stock', 'Transfers', 'Edit');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 1, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","","","","","","","");
$rec_status = 4;

qryfind("prod", array('name'));
preOrd("prod", array('', 'ProductName', 'UnitPrice', 'catname', 'ShopStock'));

$outid = intval(_xget("outid"));
/*
LEFT JOIN (
            SELECT `PackageID`, GROUP_CONCAT(CONCAT_WS('~#~', `ProductID`, `ProdName`, `Quantity`, 
                    `Discount`, `UnitPrice`, `ShopStock`, `serials`, `serialized`) 
                    SEPARATOR '~~##~~') AS `itms` 
                    FROM `{$_SESSION['DBCoy']}`.`items_pkgs_itms` 
                    INNER JOIN `{$_SESSION['DBCoy']}`.items ON `items`.`ItemID`=items_pkgs.PackageID
                        GROUP BY `parent_id`
                    ) AS itms ON 
 */
$From = "FROM `{$_SESSION['DBCoy']}`.`items` 
LEFT JOIN `{$_SESSION['DBCoy']}`.items_prod        ON `items`.`ItemID`=items_prod.ProductID
LEFT JOIN `{$_SESSION['DBCoy']}`.items_srv         ON `items`.`ItemID`=items_srv.ServiceID
LEFT JOIN `{$_SESSION['DBCoy']}`.items_pkgs        ON `items`.`ItemID`=items_pkgs.PackageID
LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `items`.Classification = classifications.catID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`outlet`          ON `items_prod`.ProductID=outlet.ProductID
WHERE `InUse`=1 AND 
    (`LimitedTime`=0 OR `LimitedTime` IS NULL OR (`LimitedTime`=1 AND StartDate<=NOW() AND EndDate>=NOW() 
            AND DAYOFWEEK(NOW()) REGEXP CONCAT('^(', `wkday`, ')$'))) AND 
    (OutletID=$outid OR $outid REGEXP CONCAT('^(', REPLACE(items_srv.`outlets`,',','|'), ')$')
                     OR $outid REGEXP CONCAT('^(', REPLACE(items_pkgs.`outlets`,',','|'), ')$'))";

$sql = "SELECT `ItemID`, `ProdName`, catname, ShopStock, `serials`, `serialized`, `UnitPrice`, `itmtax`
        {$From}{$orderval}";

$currentPage = 'pick.php';
$maxRows_TProducts = 100;

$TabArray = 'TProducts';
require_once (ROOT.'/scripts/fetchdata.php');

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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
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
            <td><table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center" class="boldwhite1">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="center" valign="top" bgcolor="#FFFBF0">
                            <table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                              <tr align="center" bgcolor="#666666" class="boldwhite1">
                                <td nowrap="nowrap"><?php echo setOrderTitle('Name', $currentPage, 1, $ord, $asc); ?></td>
                                <td nowrap="nowrap"><?php echo setOrderTitle('Price', $currentPage, 2, $ord, $asc); ?></td>
                                <td nowrap="nowrap"><?php echo setOrderTitle('Category', $currentPage, 3, $ord, $asc); ?></td>
                                <td nowrap="nowrap"><?php echo setOrderTitle('Qty', $currentPage, 4, $ord, $asc); ?></td>
                                </tr>
                              <?php $j=1;
   foreach ($TProducts as $row_TProducts) {
  $k=$j%2;
  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
  ?>
                              <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
onclick="parent.parent.appendItm(<?php echo "'", $row_TProducts['ItemID'], "','", str_replace(array('"', "'"), array('”', "’"), $row_TProducts['ProdName']), "','", $row_TProducts['UnitPrice'], "','", $row_TProducts['ShopStock'], "','", $row_TProducts['serials'], "','", $row_TProducts['serialized'], "','", $row_TProducts['itmtax'], "'" ?>)">
                                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['ProdName'] ?></b></td>
                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['UnitPrice'] ?></b></td>
                                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['catname'] ?></b></td>
                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TProducts['ShopStock'] ?></b></td>
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
        </table></td>
    </tr>
  </table>
</body>
</html>