<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Operations'));
$access = _xvar_arr_sub($_access, array('Service Schedule'));
vetAccess('Operations', 'Service Schedule', 'Print');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, $access['Print'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "", "", "", "", "", "", "print.php", "");
$rec_status = 4;

$id = intval(_xget('id'));

if (getDBDatacnt($dbh, "FROM `{$_SESSION['DBCoy']}`.`items_srv_sched` WHERE SrvSchedID=$id AND `Status`=32") == 1) {
    exit();
}

$sql = "update `{$_SESSION['DBCoy']}`.`items_srv_sched` SET `Status`=32 WHERE SrvSchedID=$id";
runDBQry($dbh, $sql);

$sel = "SELECT SrvSchedID, DATE_FORMAT(startdate, '%e/%c/%Y') AS startdate, DATE_FORMAT(enddate, '%e/%c/%Y') AS enddate, 
    UPPER(DATE_FORMAT(enddate, '%b %Y')) AS endLic, `invoicedetails`.InvoiceID, `invoicedetails`.ProductID,
    `AssetID`, `items_srv_sched`.`Status` FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`";

$sql = "SELECT SrvSchedID, startdate, enddate, endLic, `ProdName`, `sched`.`Status`, OutletName, 
    `assets`.*, colorname, tags, `auto_categories`.category_name, `AssStatus`.Category AS assStat, `license`,
    `category_name` AS `vtype`, CONCAT(`AssetName`, ' ', `licenceno`) AS `AssetName`, `invoices`.InvoiceID, $vendor_sql,
    CONCAT(`BillingAddress`, ' ', `City`, ' ', `StateOrProvince`) AS `addr`, IF(`ClientType`=2,'Yes','No') AS `isBiz`
        FROM (
    $sel
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails`      ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`           ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    WHERE `SrvSchedID`=$id
        UNION
    $sel
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails`      ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms`     ON `items_srv_sched`.PackItemID=`items_pkgs_itms`.`PackItemID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`           ON `items_pkgs_itms`.ProductID=`items_srv`.`ServiceID`
    WHERE `SrvSchedID`=$id
        ) AS `sched` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`items`               ON `sched`.ProductID=`items`.ItemID
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`            ON `sched`.InvoiceID=`invoices`.`InvoiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlets`             ON `invoices`.OutletID=`outlets`.OutletID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`             ON `invoices`.VendorID=`vendors`.VendorID
    INNER JOIN `{$_SESSION['DBCoy']}`.`assets`              ON `sched`.AssetID=`assets`.AssetID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status` `AssStatus`   ON `assets`.Status=`AssStatus`.CategoryID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`colors`               ON `assets`.colour=`colors`.colorid
    LEFT JOIN `{$_SESSION['DBCoy']}`.`licenses`             ON `assets`.`desgtype`=`licenses`.`lic_typ`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`auto_categories`      ON `assets`.`SalvageValue`=`auto_categories`.`CatID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`               ON `sched`.Status=status.CategoryID";
$row_TLic = getDBDataRow($dbh, $sql);

$endprd = explode(' ', $row_TLic['endLic']);
$tag = $row_TLic['tags'];

switch($row_TLic['Category']) {
    case 29:
        $class = 'Private';
        break;			
    case 30:
        $class = 'Commercial';
        break;					
    case 31:
        $class = 'Government';
        break;							
}

if (strlen($row_TLic['addr']) > 48) {
    $ch = 48;
    while ($row_TLic['addr'][$ch] != ' ') {
        $ch--;
    }
    $addr1 = substr($row_TLic['addr'], 0, $ch);
    $addr2 = substr($row_TLic['addr'], $ch + 1);
} else {
    $addr1 = $row_TLic['addr'];
    $addr2 = "";
}

ob_start();
?>
<script language="JavaScript1.2" src="http://<?php echo PRINT_SERVER ?>/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="http://<?php echo PRINT_SERVER ?>/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="http://<?php echo PRINT_SERVER ?>/auto_cats.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="http://<?php echo PRINT_SERVER ?>/autos.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="http://<?php echo PRINT_SERVER ?>/models.js" type="text/javascript"></script>
#~#License=eVRC: <?php echo $row_TLic['AssetID'] ?>
#~#Owner_Name_Reg=Kano State
#~#Status=<?php echo $class ?>
#~#Plate Number=<?php echo $row_TLic['licenceno'] ?>
#~#Lic Area=<?php echo $row_TLic['OutletName'] ?>
#~#Date Issue=<?php echo $row_TLic['startdate'] ?>
#~#Owner Name=<?php echo $row_TLic['VendorName'] ?>
#~#Address=<?php echo $addr1 ?>
#~#<?php echo $addr2 ?>
#~#Car Make=<script>document.write(get_brand(<?php echo $row_TLic['SalvageValue'] - 1 ?>, <?php echo intval($row_TLic['Brand']) ?>))</script>
#~#Car Model=<script>document.write(get_model(<?php echo $row_TLic['SalvageValue'] - 1 ?>, <?php echo intval($row_TLic['serialno']) ?>))</script> - <?php echo $row_TLic['Model'] ?>
#~#Vehicle Type=<?php echo $row_TLic['vtype'] ?> - <script>document.write(get_bstyle(<?php echo $row_TLic['SalvageValue'] - 1 ?>, <?php echo intval($row_TLic['DepreciationValue']) ?>))</script>
#~#Car Color=<?php echo $row_TLic['colorname'] ?>
#~#Chassis Number=<?php echo $row_TLic['modelno'] ?>
#~#Engine Number=<?php echo $row_TLic['partno'] ?>
#~#Business Owner=<?php echo $row_TLic['isBiz'] ?>
#~#PrinterOptions=printencode
||~||
<?php 

$dat = ob_get_contents();
ob_end_clean();
$tries = 0;
exec("php /var/www/html/scripts/file_cleaner.php /var/www/html/tmp/ print-*.html 5");

do {
    $f = mt_rand(100, 99999);
    try {
        $handle = fopen(ROOT . "/tmp/print-$f.html", "x");
    } catch (Exception $ex) {
    }
} while (!$handle && $tries < 20);

if ($handle) {
    fwrite($handle, $dat);
    fclose($handle);
?>
<script>
    location.href="<?php echo 'http://', PRINT_SERVER, '/?f=', $f, '&p=3&u=', $_SESSION['userid'] ?>";
</script>
<?php
}
