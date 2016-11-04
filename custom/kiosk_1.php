<?php
require_once('../scripts/init.php');
    
if (!isset($_SESSION['OutletID']) && $_SESSION['accesskeys']['Accounts']['View'] == 1) {
    $sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` outcat ON `outlets`.Dept = outcat.catID 
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` vencat ON 
            (`outcat`.category_id LIKE CONCAT(`vencat`.category_id, '-%')
            OR  `outcat`.category_id = `vencat`.category_id)
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`                ON `vencat`.catID = `vendors`.DeptID 
        WHERE `account`=1 AND `VendorID`={$_SESSION['ids']['VendorID']}
        UNION
        SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
        WHERE `account`=1 AND {$_SESSION['ids']['VendorID']} REGEXP CONCAT('^(0', REPLACE(`guests`,',','|'), ')$')
        ";
    $TOutlets = getDBData($dbh, $sql);
    if (count($TOutlets) == 0) {
	header("Location: /denied.php");
        exit;
    } else {
        $_SESSION['OutletID'] = $TOutlets[0]['OutletID'];
    }
//    $all_outs = "0";
//    foreach ($TOutlets as $row_TOutlets) {
//        $all_outs .= "," .$row_TOutlets['OutletID'];
//    }
//    array_unshift($TOutlets, array('OutletID'=>$all_outs, 'OutletName'=>"All"));
//    if (isset($_POST['OutletID'])) {
//        $_SESSION['OutletID'] = GSQLStr(_xpost('OutletID'), "textv");
//    } elseif (strlen(_xses('OutletID')) == 0) {
//        $_SESSION['OutletID'] = $all_outs;
//    }
}

if (($id=intval(_xses('custid'))) > 0) {
    $sql = "SELECT `VendorID`, `ClientType`, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE `VendorID`=$id";
    $row_TOwner = getDBDataRow($dbh, $sql);
    
    $sql = "SELECT `AssetID`
            FROM `{$_SESSION['DBCoy']}`.`assets` 
            WHERE `occupant`=$id";
    $TVehicles = getDBData($dbh, $sql);
}
if (isset($_GET['v'])) {
    $_SESSION['vehid'] = intval(_xget('v'));
}
if (($id=intval(_xses('vehid'))) > 0) {
    $sql = "SELECT `assets`.*, colorname, `AssStatus`.Category AS assStat, `license`, `category_name` AS `vtype`
            FROM `{$_SESSION['DBCoy']}`.`assets` 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`status` `AssStatus` ON `assets`.Status=`AssStatus`.CategoryID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`colors`             ON `assets`.colour=`colors`.colorid
            LEFT JOIN `{$_SESSION['DBCoy']}`.`licenses`           ON `assets`.`desgtype`=`licenses`.`lic_typ`
            LEFT JOIN `{$_SESSION['DBCoy']}`.`auto_categories`    ON `assets`.`SalvageValue`=`auto_categories`.`CatID`
            WHERE `assets`.`AssetID`=$id";
    $row_TVehicle = getDBDataRow($dbh, $sql);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/scripts/js/set.js"></script>
<script language="JavaScript1.2" src="vehicles/auto_cats.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="vehicles/autos.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="vehicles/models.jgz" type="text/javascript"></script>
<script type="text/javascript"> 

window.onload = function() {
    setContent();
}
window.onresize = function() {
    setContent();
}

</script>
<style>
.screen {
    border-radius: 10px;
    border:3px inset #333;
    background-color: #333;
}
.pixlnk {
    cursor: pointer;
}

</style>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<div id="content">
  <table border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
      <td><img src="/custom/images/owner.png" width="120" height="120" onclick="location.href='/custom/owners/add.php'" class="pixlnk" /></td>
      <td><img src="/custom/images/vehicle.png" width="120" height="120"<?php if (isset($row_TOwner)) { ?> onclick="location.href='/custom/vehicles/add.php'" class="pixlnk"<?php } ?> /></td>
      <td><a href=""></a><img src="/custom/images/owner_find.png" width="120" height="120" onclick="location.href='/custom/owners/find.php'" class="pixlnk" /></td>
      <td><img src="/custom/images/vehicle_find.png" width="120" height="120" onclick="location.href='/custom/vehicles/find.php'" class="pixlnk" /></td>
      <td><img src="/custom/images/license.png" width="120" height="120"<?php if (isset($row_TVehicle)) { ?>  onclick="location.href='/custom/sales/add.php'" class="pixlnk"<?php } ?> /></td>
      <td><img src="/custom/images/print.png" width="120" height="120"<?php if (isset($row_TVehicle)) { ?>  onclick="location.href='/custom/reprint.php'" class="pixlnk"<?php } ?> /></td>
      <td><img src="/custom/images/logout.png" width="120" height="120" onclick="location.href='/logout.php'" class="pixlnk" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="7"><table width="100%" border="0" cellpadding="4" cellspacing="4">
          <tr>
            <td width="8%"><img src="/custom/images/owner_current.png" width="60" height="60"<?php if (isset($row_TOwner)) { ?> onclick="location.href='/custom/owners/view.php?id=<?php echo $row_TOwner['VendorID'] ?>'" class="pixlnk"<?php } ?> /></td>
            <td width="39%" class="screen"><?php if (isset($row_TOwner)) { ?><table border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td align="right" class="boldwhite1">Name:</td>
                <td align="left" class="yellowtxt"><?php echo $row_TOwner['VendorName'] ?></td>
                </tr>
              <tr>
                <td align="right" class="boldwhite1">ID:</td>
                <td align="left" class="yellowtxt"><?php echo $row_TOwner['VendorID'] ?></td>
                </tr>
              <tr>
                <td align="right" class="boldwhite1">Type:</td>
                <td align="left" class="yellowtxt"><?php 
				switch($row_TOwner['ClientType']) {
					case 1:
						echo 'Individual';
						break;			
					case 2:
						echo 'Corporate';
						break;					
					case 3:
						echo 'Government';
						break;							
				}?></td>
              </tr>
              </table>
                  <table width="100%" border="0" cellpadding="2" cellspacing="2">
                    <tr>
                      <td align="center"><a href="/custom/sales/index.php"><img src="/images/but_invoices.png" width="120" height="30" /></a></td>
                      <td align="center">&nbsp;</td>
                      <td align="center"><a href="/custom/payments/index.php"><img src="/images/but_payments.png" width="120" height="30" /></a></td>
                    </tr>
                  </table>
            <?php } ?></td>
            <td width="3%">&nbsp;</td>
            <td width="11%"><img src="/custom/images/vehicle_current.png" width="85" height="70"<?php if (isset($row_TVehicle)) { ?> onclick="location.href='/custom/vehicles/view.php?id=<?php echo $row_TVehicle['AssetID'] ?>'" class="pixlnk"<?php } ?> /></td>
            <td width="39%" class="screen"><?php if (isset($row_TOwner)) { ?><table border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td align="right" class="boldwhite1">Vehicle ID:</td>
                <td align="left" class="yellowtxt"><select name="lictype" id="lictype" style="padding: 5px;" onchange="location.href='kiosk.php?v=' + this.value">
                  <option value="">..</option>
                  <?php foreach ($TVehicles as $row_TVehicles) { ?>
                  <option value="<?php echo $row_TVehicles['AssetID'] ?>" <?php if (!(strcmp($id, $row_TVehicles['AssetID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TVehicles['AssetID'] ?></option>
                  <?php } ?>
                </select></td>
                <td align="left" class="yellowtxt">&nbsp;</td>
                <td align="left" class="yellowtxt">&nbsp;</td>
                </tr><?php if (isset($row_TVehicle)) { ?>
              <tr>
                <td colspan="4" align="center" class="yellowtxt"><strong><?php echo $row_TVehicle['vtype'] ?>  - 
                    <script>document.write(get_brand(<?php echo $row_TVehicle['SalvageValue'] - 1 ?>, <?php echo intval($row_TVehicle['Brand']) ?>)+ ' '+get_model(<?php echo $row_TVehicle['SalvageValue'] - 1 ?>, <?php echo intval($row_TVehicle['serialno']) ?>))</script>
- <?php echo $row_TVehicle['Model'] ?></strong></td>
                </tr>
              <tr>
                <td align="center" class="boldwhite1">Registration:</td>
                <td align="center" class="yellowtxt"><?php echo $row_TVehicle['licenceno'] ?></td>
                <td align="center" class="yellowtxt">&nbsp;</td>
                <td align="center" class="yellowtxt">(<?php 
				switch($row_TVehicle['Category']) {
					case 29:
						echo 'Private';
						break;			
					case 30:
						echo 'Commercial';
						break;	
					case 31:
						echo 'Government';
						break;									
				}?>)</td>
                </tr>
              <tr>
                <td align="center" class="boldwhite1">License Type:</td>
                <td align="center" class="yellowtxt"><?php echo $row_TVehicle['license']; ?></td>
                <td align="center" class="yellowtxt">&nbsp;</td>
                <td align="center" class="yellowtxt">&nbsp;</td>
              </tr><?php } else unset($_SESSION['vehid']) ?>
              </table><?php } ?></td>
          </tr>
      </table></td>
    </tr>
  </table>
</div>
</body>
</html>
