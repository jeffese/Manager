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

$sql = "UPDATE `{$_SESSION['DBCoy']}`.`items_srv_sched` SET `Status`=32 WHERE SrvSchedID=$id";
runDBQry($dbh, $sql);

$sel = "SELECT SrvSchedID, DATE_FORMAT(startdate, '%e/%c/%Y') AS startdate, DATE_FORMAT(enddate, '%e/%c/%Y') AS enddate, 
    UPPER(DATE_FORMAT(enddate, '%b %Y')) AS endLic, `invoicedetails`.InvoiceID, `invoicedetails`.ProductID, 
    `invoicedetails`.`UnitPrice` AS `LineTotal`, `AssetID`, `items_srv_sched`.`Status`
    FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`";

$sql = "SELECT SrvSchedID, startdate, enddate, endLic, `ProdName`, `sched`.`Status`, 
    `assets`.*, colorname, tags, `auto_categories`.category_name, `license`,
    `category_name` AS `vtype`, `invoices`.InvoiceID, $vendor_sql,
    CONCAT(`BillingAddress`, ' ', `City`, ' ', `StateOrProvince`) AS `addr`, `LineTotal`, 
    DATE_FORMAT(`LedgerDate`, '%e/%c/%Y') AS transdate
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
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`             ON `invoices`.VendorID=`vendors`.VendorID
    INNER JOIN `{$_SESSION['DBCoy']}`.`assets`              ON `sched`.AssetID=`assets`.AssetID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`colors`               ON `assets`.colour=`colors`.colorid
    LEFT JOIN `{$_SESSION['DBCoy']}`.`licenses`             ON `assets`.`desgtype`=`licenses`.`lic_typ`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`auto_categories`      ON `assets`.`SalvageValue`=`auto_categories`.`CatID`";
$row_TLic = getDBDataRow($dbh, $sql);

$sql1 = "FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails`      ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`           ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items`               ON `invoicedetails`.ProductID=`items`.ItemID
    WHERE InvoiceID={$row_TLic['InvoiceID']} AND Classification";
$sql2 = "FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails`      ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms`     ON `items_srv_sched`.PackItemID=`items_pkgs_itms`.`PackItemID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`           ON `items_pkgs_itms`.ProductID=`items_srv`.`ServiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items`               ON `items_pkgs_itms`.ProductID=`items`.ItemID
    WHERE InvoiceID={$row_TLic['InvoiceID']} AND Classification";
$rdw = getDBDatacnt($dbh, "$sql1=16") + getDBDatacnt($dbh, "$sql2=16");
$hck = getDBDatacnt($dbh, "$sql1=18") + getDBDatacnt($dbh, "$sql2=18");
$ksr = getDBDatacnt($dbh, "$sql1=26") + getDBDatacnt($dbh, "$sql2=26");
$dcb = getDBDatacnt($dbh, "$sql1=17") + getDBDatacnt($dbh, "$sql2=17");
$hgp = getDBDatacnt($dbh, "$sql1=27") + getDBDatacnt($dbh, "$sql2=27");
$endprd = explode(' ', $row_TLic['endLic']);
$tag = $row_TLic['tags'];

ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="JavaScript1.2" src="http://<?php echo PRINT_SERVER ?>/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="http://<?php echo PRINT_SERVER ?>/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="http://<?php echo PRINT_SERVER ?>/auto_cats.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="http://<?php echo PRINT_SERVER ?>/autos.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="http://<?php echo PRINT_SERVER ?>/models.js" type="text/javascript"></script>
<style>
body {
    margin: 0px;
}

.lbltab1 {
	position: absolute;
	left:0px;
	top: 12px;
	width: 263px;
}

.lbltab2 {
	position: absolute;
	left:358px;
	top: 50px;
	width: 361px;
}

.cattab {
	position: absolute;
	left:610px;
	top: 51px;
}

.headtab {
	position: absolute;
	left:457px;
	top: 50px;
}

.cat {
	position: absolute;
	left:656px;
	top: 71px;
}

.ticktab {
	position: absolute;
	left:628px;
	top: 231px;
	width: 87px;
	height: 167px;
}

.titles {
    font-family: Chianti Win95BT;
    font-size: 10px;
    font-weight:bold;
    color: #999999;
    text-align:left;
}

.lbl {
    font-family: Verdana, Geneva, sans-serif;
    font-size: 11px;
    color: #999999;
    text-align:left;
}

.info {
    font-family: Arial Black;
    font-size: 12px;
    font-weight:bold;
    color: #999999;
    text-align:left;
}

.typ {
	position: absolute;
	left:650px;
	top: 55px;
}

.typ1 {
    font-family: Comic Sans MS;
    font-size: 24px;
    font-weight:bold;
    color: #999999;
    text-align:left;
}

.typ2 {
    font-family: Arial Black;
    font-size: 14px;
    font-weight:bold;
    color: #999999;
    text-align:left;
}

.hd {
	font-family: Arial Black;
    font-size: 14px;
    font-weight:bolder;
    color: #999999;
    text-align: center;
}

.typ3 {
    font-family: Berlin Sans FB;
    font-size: 80px;
    font-weight:bold;
    color: #999999;
    text-align:left;
}

.typ4 {
    font-family: Berlin Sans FB;
    font-size: 32px;
    font-weight:bold;
    color: #999999;
    text-align:left;
}

.bcode1 {
	position: absolute;
	left:0px;
	top: 388px;
}

.bcode2 {
	position: absolute;
	left:357px;
	top: 383px;
}

.bcode3 {
	position: absolute;
	left:854px;
	top: 147px;
}

.vert1 {
	position: absolute;
	left:920px;
	top: 136px;
}

.vert2 {
	position: absolute;
	left:930px;
	top: 147px;
}

.expiry {
	position: absolute;
	font-family: Arial Black;
	font-weight:bold;
	color: #999999;
	text-align:left;
	white-space:nowrap;
}

.expiry1 {
	left:22px;
	top: 426px;
	font-size: 36px;
}

.expiry2 {
	left:440px;
	top: 417px;
	font-size: 36px;
}

.expiry3 {
	left:850px;
	top: 91px;
	font-size: 20px;
}

.expiry4 {
	left:850px;
	top: 403px;
	font-size: 35px;
	display:none;
}

.evrc {
	position: absolute;
	left:854px;
	top: 371px;
	font-family: Verdana;
	font-size: 14px;
	font-weight:bold;
	color: #999999;
	text-align:left;
}

</style>
</head>

<body>
<img src="http://<?php echo PRINT_SERVER ?>/barcode.php?a=0&c=<?php echo $row_TLic['AssetID'] ?>" class="bcode1" />
<img src="http://<?php echo PRINT_SERVER ?>/barcode.php?a=0&c=<?php echo $row_TLic['AssetID'] ?>" class="bcode2" />
<img src="http://<?php echo PRINT_SERVER ?>/barcode.php?a=90&c=<?php echo $row_TLic['AssetID'] ?>" class="bcode3" />
<img src="http://<?php echo PRINT_SERVER ?>/vertext.php?sz=24&txt=<?php echo $row_TLic['licenceno'] ?>" class="vert1" /><span class="expiry expiry1"><?php echo $row_TLic['endLic'] ?></span>
<span class="expiry expiry2"><?php echo $row_TLic['endLic'] ?></span>
<span class="expiry expiry3"><?php echo $row_TLic['endLic'] ?></span>
<span class="expiry expiry4"><?php echo $endprd[1] ?></span>
<span class="evrc"><?php echo $row_TLic['AssetID'] ?></span>
<span class="typ">
<span class="typ3"><?php //echo $tag[0] ?></span>
<span class="typ4"><?php //echo strlen($tag) == 1 ? '' : $tag[1] ?></span>
</span>
  <table border="0" cellpadding="0" cellspacing="0" class="lbltab1">
    <tr>
      <td nowrap="nowrap" class="titles" width="91">&nbsp;</td>
      <td class="typ2" width="176"><?php 
				switch($row_TLic['Category']) {
					case 29:
						echo 'Private';
						break;			
					case 30:
						echo 'Commercial';
						break;					
					case 31:
						echo 'Government';
						break;							
				}?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="19"></td>
      <td></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles">OWNERS NAME:</td>
      <td rowspan="2" valign="top" class="info"><?php echo $row_TLic['VendorName'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="20"></td>
      </tr>
    <tr>
      <td nowrap="nowrap" class="titles">ADDRESS:</td>
      <td rowspan="2" valign="top" class="info"><?php echo $row_TLic['addr'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="20"></td>
      </tr>
    <tr>
      <td nowrap="nowrap" class="titles">eVRC NUMBER:</td>
      <td class="info"><?php echo $row_TLic['AssetID'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="8"></td>
      <td class="info"></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles">VEHICLE REG. No.:</td>
      <td class="info"><?php echo $row_TLic['licenceno'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="8"></td>
      <td class="info"></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles">ENGINE NUMBER:</td>
      <td class="info"><?php echo $row_TLic['partno'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="8"></td>
      <td class="info"></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles">CHASSIS NUMBER:</td>
      <td class="info"><?php echo $row_TLic['modelno'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="8"></td>
      <td class="info"></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles">VEHICLE MAKE:</td>
      <td rowspan="2" valign="top" class="info"><script>document.write(get_brand(<?php echo $row_TLic['SalvageValue'] - 1 ?>, <?php echo intval($row_TLic['Brand']) ?>)+ ' '+get_model(<?php echo $row_TLic['SalvageValue'] - 1 ?>, <?php echo intval($row_TLic['serialno']) ?>))</script> <?php echo $row_TLic['Model'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="20"></td>
      </tr>
    <tr>
      <td nowrap="nowrap" class="titles">VEHICLE TYPE:</td>
      <td class="info"><?php echo $row_TLic['vtype'] ?> <script>document.write(get_bstyle(<?php echo $row_TLic['SalvageValue'] - 1 ?>, <?php echo intval($row_TLic['DepreciationValue']) ?>))</script></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="8"></td>
      <td class="info"></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles">COLOUR:</td>
      <td class="info"><?php echo $row_TLic['colorname'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="8"></td>
      <td class="info"></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles">ENGINE CAPACITY:</td>
      <td class="info"><?php echo $row_TLic['Capacity'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="8"></td>
      <td class="info"></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles">DATE ISSUED:</td>
      <td class="info"><?php echo $row_TLic['startdate'] ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles" height="8"></td>
      <td class="info"></td>
    </tr>
    <tr>
      <td nowrap="nowrap" class="titles">LICENSE FEE:</td>
      <td class="info">N <?php echo number_format($row_TLic['LineTotal']) ?></td>
    </tr>
  </table>
</div>
<table border="0" cellspacing="0" cellpadding="0" class="lbltab2">
  <tr>
    <td width="104">&nbsp;</td>
    <td width="247">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles" height="10"></td>
    <td></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles">VEHICLE REG. No.:</td>
    <td class="info"><?php echo $row_TLic['licenceno'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles" height="10"></td>
    <td class="info"></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles">ENGINE NUMBER:</td>
    <td class="info"><?php echo $row_TLic['partno'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles" height="10"></td>
    <td class="info"></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles">CHASSIS NUMBER</td>
    <td class="info"><?php echo $row_TLic['modelno'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles" height="10"></td>
    <td class="info"></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles">VEHICLE MAKE:</td>
    <td rowspan="2" valign="top" class="info"><script>document.write(get_brand(<?php echo $row_TLic['SalvageValue'] - 1 ?>, <?php echo intval($row_TLic['Brand']) ?>)+ ' '+get_model(<?php echo $row_TLic['SalvageValue'] - 1 ?>, <?php echo intval($row_TLic['serialno']) ?>))</script>
      - <?php echo $row_TLic['Model'] ?></td>
  </tr>
  <tr>
    <td height="20"></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles">VEHICLE TYPE:</td>
    <td class="info"><?php echo $row_TLic['vtype'] ?> -
    <script>document.write(get_bstyle(<?php echo $row_TLic['SalvageValue'] - 1 ?>, <?php echo intval($row_TLic['DepreciationValue']) ?>))</script></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles" height="10"></td>
    <td class="info"></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles">COLOUR:</td>
    <td class="info"><?php echo $row_TLic['colorname'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles" height="10"></td>
    <td class="info"></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles">ENGINE CAPACITY:</td>
    <td class="info"><?php echo $row_TLic['Capacity'] ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles" height="10"></td>
    <td></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles">TRANSACTION DATE:</td>
    <td><span class="info"><?php echo $row_TLic['transdate'] ?></span></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles" height="10"></td>
    <td></td>
  </tr>
  <tr>
    <td class="titles">DATE ISSUED:</td>
    <td><span class="info"><?php echo $row_TLic['startdate'] ?></span></td>
  </tr>
  <tr>
    <td nowrap="nowrap" class="titles" height="10"></td>
    <td></td>
  </tr>
  <tr>
    <td class="titles">EXPIRY DATE:</td>
    <td><span class="info"><?php echo $row_TLic['enddate'] ?></span></td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" class="headtab">
  <tr>
    <td nowrap="nowrap" class="hd">VEHICLE LICENSE</td>
  </tr>
  <tr>
    <td class="hd">KANO STATE</td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" class="cattab">
  <tr>
    <td class="typ2"><?php 
				switch($row_TLic['Category']) {
					case 29:
						echo 'Private';
						break;			
					case 30:
						echo 'Commercial';
						break;					
					case 31:
						echo 'Government';
						break;							
				}?>&nbsp;</td>
  </tr>
  <tr>
    <td height="65"></td>
  </tr>
  <tr>
    <td class="typ2"><?php //echo $row_TLic['category_name'] ?></td>
  </tr>
</table>
<table border="1" cellspacing="0" cellpadding="4" class="ticktab" bordercolor="#999999">
  <tr>
    <td class="titles">RDW</td>
    <td><?php if ($rdw > 0) { ?><img src="/images/jjdot.png" /><?php } ?></td>
  </tr>
  <tr>
    <td class="titles">HACK</td>
    <td><?php if ($hck > 0) { ?><img src="/images/jjdot.png" /><?php } ?></td>
  </tr>
  <tr>
    <td class="titles">KSTR</td>
    <td><?php if ($ksr > 0) { ?><img src="/images/jjdot.png" /><?php } ?></td>
  </tr>
  <tr>
    <td class="titles">DCB</td>
    <td><?php if ($dcb > 0) { ?><img src="/images/jjdot.png" /><?php } ?></td>
  </tr>
  <tr>
    <td class="titles">HGP</td>
    <td><?php if ($hgp > 0) { ?><img src="/images/jjdot.png" /><?php } ?></td>
  </tr>
</table>
</body>
</html>
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
    location.href="<?php echo 'http://', PRINT_SERVER, '/?f=', $f, '&p=2&u=', $_SESSION['userid'] ?>";
</script>
<?php
}
