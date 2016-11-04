<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Operations'));
$access = _xvar_arr_sub($_access, array('Service Schedule'));
vetAccess('Operations', 'Service Schedule', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, $access['Print'], 1, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "", "", "", "", "", "", "print.php", "");
$rec_status = 4;

$id = intval(_xget('id'));
preOrd('srvshed', array('', 'ProdName', 'VendorName', 'startdate', 'enddate', 'Category'));

$sel = "SELECT SrvSchedID, DATE_FORMAT(startdate, '%e/%c/%Y') AS startdate, DATE_FORMAT(enddate, '%e/%c/%Y') AS enddate, `ProdName`, 
    `AssetID`, `items_srv_sched`.`Status`, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`";

$From = "FROM (
    $sel
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails`      ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`           ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items`               ON `invoicedetails`.ProductID=`items`.ItemID
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`            ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`             ON `invoices`.VendorID=`vendors`.VendorID
    WHERE `items_srv_sched`.AssetID=$id
        UNION
    $sel
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails`      ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms`     ON `items_srv_sched`.PackItemID=`items_pkgs_itms`.`PackItemID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`           ON `items_pkgs_itms`.ProductID=`items_srv`.`ServiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items`               ON `items_pkgs_itms`.ProductID=`items`.ItemID
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`            ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`             ON `invoices`.VendorID=`vendors`.VendorID
    WHERE `items_srv_sched`.AssetID=$id
        ) AS `sched` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`assets`               ON `sched`.AssetID=`assets`.AssetID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`               ON `sched`.Status=status.CategoryID";

$sql = "SELECT SrvSchedID, startdate, enddate, `ProdName`, `sched`.`Status`, status.Category, VendorName {$From}{$orderval}";

$currentPage = 'assets.php';
$maxRows_TSrvsched = 30;

$TabArray = 'TSrvsched';
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td><?php include('../../scripts/buttonset.php')?></td>
      </tr>
    </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center" class="boldwhite1"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                        <tr align="center" bgcolor="#666666" class="boldwhite1">
                          <td nowrap="nowrap"><?php echo setOrderTitle('Service', $currentPage, 1, $ord, $asc); ?></td>
                          <td nowrap="nowrap"><?php echo setOrderTitle('Client', $currentPage, 2, $ord, $asc); ?></td>
                          <td nowrap="nowrap"><?php echo setOrderTitle('From', $currentPage, 3, $ord, $asc); ?></td>
                          <td nowrap="nowrap"><?php echo setOrderTitle('To', $currentPage, 4, $ord, $asc); ?></td>
                          <td nowrap="nowrap"><?php echo setOrderTitle('Status', $currentPage, 5, $ord, $asc); ?></td>
                        </tr>
                        <?php $j=1;
	   foreach ($TSrvsched as $row_TSrvsched) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                        <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onclick="location.href='view.php?id=<?php echo $row_TSrvsched['SrvSchedID']; ?>'">
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSrvsched['ProdName'] ?></b></td>
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSrvsched['VendorName'] ?></b></td>
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSrvsched['startdate'] ?></b></td>
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSrvsched['enddate'] ?></b></td>
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSrvsched['Category'] ?></b></td>
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
          <td></td>
        </tr>
        <tr>
          <td><?php include('../../scripts/buttonset.php'); ?></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="4" cellpadding="4">
    </table></td>
  </tr>
</table>
</body>
</html>