<?php
require_once('../../scripts/init.php');
require_once('sql.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Assets'));
$access = $_access['Vehicles'];
vetAccess('Assets', 'Vehicles', 'View');

$flow = _xses('flow');
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($flow == 3 ? 0 : $access['Add'], 0, 0, 0, 1, $flow == 3 ? 1 : 0, 0, 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "add.php", "", "", "", $flow == 3 ? "../kiosk.php" : "../owners/view.php", "", "find.php", "", "");
$rec_status = 4;

qryfind('Vehicles', array('lictype'));
preOrd("occupy", array('', 'SalvageValue', 'Brand', 'serialno', 'Model', 'occupy', 'licenceno'));

if (isset($_GET['v'])) {
    $_SESSION['new_veh'] = explode(',', _xget('v'));
}
if (isset($_POST['lictype'])) {
    $lst = $qryvals;
} else {
    $cust = intval(_xses('custid'));
    $vehs = isset($_SESSION['new_veh']) && count($_SESSION['new_veh']) > 0 ? implode(',', $_SESSION['new_veh']) : '';
    $lst = ($cust > 0 ? "AND occupant=$cust" : "") . (strlen($vehs) != 0 ? " AND `assets`.AssetID IN ($vehs)" : "");
}
$flow = _xses('flow');
$join = $flow == 3 ? "INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv_sched`   ON `assets`.AssetID=`items_srv_sched`.AssetID" : "";

$vendor_ocp = vendorFlds("occupys", "occupy");
$From = "FROM `{$_SESSION['DBCoy']}`.`assets`
            $join
            LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `occupys`  ON occupant=occupys.VendorID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`status` `AssStatus` ON `assets`.Status=`AssStatus`.CategoryID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`colors`             ON `assets`.colour=`colors`.colorid
            LEFT JOIN `{$_SESSION['DBCoy']}`.`licenses`           ON `assets`.`desgtype`=`licenses`.`lic_typ`
            LEFT JOIN `{$_SESSION['DBCoy']}`.`auto_categories`    ON `assets`.`SalvageValue`=`auto_categories`.`CatID`
            WHERE AssetType=2 $lst";

$sql = "SELECT DISTINCT `assets`.*, $vendor_ocp, colorname, `AssStatus`.Category AS assStat, 
        `license`, `category_name` AS `vtype` {$From}{$orderval}";

$currentPage = 'index.php';
$maxRows_TVehicle = 30;

$TabArray = 'TVehicle';
require_once (ROOT.'/scripts/fetchdata.php');

if (count($_SESSION['new_veh']) > 0) {
    $xtra_buts_0 = '<td align="center"><a href="../sales/add.php" ><img src="/custom/images/nav_inv.png"  title="Invoice" alt="Invoice" border="0" /></a></td>';
    $xtra_buts_1 = '<td align="center"><a href="../sales/add.php" class="titles">Invoice</a></td>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Clients</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
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
<script type="text/javascript" src="/scripts/js/set.js"></script>
<script type="text/javascript"> 
<!--
window.onload = function() {
    setContent();
}
window.onresize = function() {
    setContent();
}

//--> 
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<div id="content">
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="frametopleft">&nbsp;</td>
      <td class="frametop">&nbsp;</td>
      <td class="frametopright">&nbsp;</td>
    </tr>
    <tr>
      <td class="frameleft">&nbsp;</td>
      <td bgcolor="#FFFFFF">
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/vehicles.jpg" alt="" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>
              <td style="height:30px; min-width:500px; background-image:url(/images/lblvehicles.png); background-repeat:no-repeat">&nbsp;</td>
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
      <td><table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center" class="boldwhite1">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                      <tr align="center" bgcolor="#666666" class="boldwhite1">
                        <td nowrap="nowrap"><?php echo setOrderTitle('Vehicle', $currentPage, 1, $ord, $asc); ?></td>
                        <td nowrap="nowrap"><?php echo setOrderTitle('Owner', $currentPage,2, $ord, $asc); ?></td>
                        <td nowrap="nowrap"><?php echo setOrderTitle('Registration #', $currentPage, 3, $ord, $asc); ?></td>
                        </tr>
                      <?php $j=1;
	   foreach ($TVehicle as $row_TVehicle) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                      <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onclick="location.href='view.php?id=<?php echo $row_TVehicle['AssetID']; ?>'">
                        <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><strong><?php echo $row_TVehicle['vtype'] ?> <script>document.write(get_brand(<?php echo $row_TVehicle['SalvageValue'] - 1 ?>, <?php echo intval($row_TVehicle['Brand']) ?>)+ ' '+get_model(<?php echo $row_TVehicle['SalvageValue'] - 1 ?>, <?php echo intval($row_TVehicle['serialno']) ?>))</script> <?php echo $row_TVehicle['Model'] ?></strong></td>
                        <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TVehicle['occupy'] ?></b></td>
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
</table></td>
      <td class="frameright">&nbsp;</td>
    </tr>
    <tr>
      <td class="framebotleft">&nbsp;</td>
      <td align="center" valign="bottom" class="framebot"><span class="greytxt">Copyright © 2010 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>
</html>
