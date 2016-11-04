<?php
require_once('../../scripts/init.php');
require_once('sql.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Assets'));
$access = $_access['Vehicles'];
vetAccess('Assets', 'Vehicles', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 1, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "", "", "", "", "", "", "", "");
$rec_status = 4;

preOrd("occupy", array('', 'SalvageValue', 'Brand', 'serialno', 'Model', 'occupy', 'licenceno'));

$id = intval(_xget('id'));
$vendor_ocp = vendorFlds("occupys", "occupy");
$From = "FROM `{$_SESSION['DBCoy']}`.`assets`
            LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `occupys`  ON occupant=occupys.VendorID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`status` `AssStatus` ON `assets`.Status=`AssStatus`.CategoryID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`colors`             ON `assets`.colour=`colors`.colorid
            LEFT JOIN `{$_SESSION['DBCoy']}`.`licenses`           ON `assets`.`desgtype`=`licenses`.`lic_typ`
            LEFT JOIN `{$_SESSION['DBCoy']}`.`auto_categories`    ON `assets`.`SalvageValue`=`auto_categories`.`CatID`
            WHERE `occupant`=$id";

$sql = "SELECT `assets`.*, $vendor_ocp, colorname, `AssStatus`.Category AS assStat, 
        `license`, `category_name` AS `vtype` {$From}{$orderval}";

$currentPage = 'index.php';
$maxRows_TVehicle = 30;

$TabArray = 'TVehicle';
require_once (ROOT.'/scripts/fetchdata.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Clients</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="auto_cats.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="autos.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="models.jgz" type="text/javascript"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
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
                <td><table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" class="boldwhite1">
                          <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                                <tr align="center" bgcolor="#666666" class="boldwhite1">
                                  <td nowrap="nowrap">&nbsp;</td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Vehicle', $currentPage, 1, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Registration #', $currentPage, 3, $ord, $asc); ?></td>
                                  </tr>
                                <?php $j=1;
	   foreach ($TVehicle as $row_TVehicle) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                                <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
onclick="top.leftFrame.showMod('Vehicles', '/assets/vehicles/view.php?id=<?php echo $row_TVehicle['AssetID']; ?>')">
                                  <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><?php echo $row_TVehicle['AssetID'] ?></td>
                                  <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><strong><?php echo $row_TVehicle['vtype'] ?> <script>document.write(get_brand(<?php echo $row_TVehicle['SalvageValue'] - 1 ?>, <?php echo intval($row_TVehicle['Brand']) ?>)+ ' '+get_model(<?php echo $row_TVehicle['SalvageValue'] - 1 ?>, <?php echo intval($row_TVehicle['serialno']) ?>))</script> <?php echo $row_TVehicle['Model'] ?></strong></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>"><?php echo $row_TVehicle['licenceno'] ?></td>
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
              </table>
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              
            </table></td>
        </tr>
        </table></td>
  </tr>
</table>
</body>
</html>
