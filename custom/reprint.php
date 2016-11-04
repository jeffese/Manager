<?php
require_once('../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Clients'));
$access = _xvar_arr_sub($_access, array('Customers'));
vetAccess('Clients', 'Customers', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 1, 0, 0, 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","","","kiosk.php","","","","");
$rec_status = 4;

preOrd('', array('', 'category_name', 'startdate', 'enddate', 'Category', 'Status'));

$id = intval(_xget('id'));
if (isset($_GET['schd'])) {
    $st = isset($_GET['clr']) ? '32' : 'NULL';
    $sql = "UPDATE `{$_SESSION['DBCoy']}`.`items_srv_sched` 
        SET `Status`=$st WHERE `SrvSchedID`=" . intval(_xget('schd'));
    runDBQry($dbh, $sql);
    header("Location: reprint.php?id=$id");
    exit();
}

$sel = "SELECT SrvSchedID, DATE_FORMAT(startdate, '%e/%c/%Y') AS startdate, DATE_FORMAT(enddate, '%e/%c/%Y') AS enddate, 
    `items_srv_sched`.Status, `items`.Classification FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails`      ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`           ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items`               ON `invoicedetails`.ProductID=`items`.ItemID";
$where = "WHERE `InvoiceID`=$id AND (Classification=20 OR Classification=34)";
$From = "FROM (
    $sel $where UNION $sel
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms`     ON `items_srv_sched`.PackItemID=`items_pkgs_itms`.`PackItemID`
    $where
        ) AS `sched`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications`      ON `sched`.Classification=classifications.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`               ON `sched`.Status=status.CategoryID";
    
$sql = "SELECT `sched`.*, Category, category_name
        $From
        ORDER BY enddate $orderval";

$currentPage = 'reprint.php';
$maxRows_TSrvsched = 30;

$TabArray = 'TSrvsched';
require_once (ROOT . '/scripts/fetchdata.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New clientee</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
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

<body><div id="content">
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="frametopleft">&nbsp;</td>
      <td class="frametop">&nbsp;</td>
      <td class="frametopright">&nbsp;</td>
    </tr>
    <tr>
      <td class="frameleft">&nbsp;</td>
      <td bgcolor="#FFFFFF"><table border="0" cellpadding="0" cellspacing="0">
        <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(images/lblprint.png); background-repeat:no-repeat">&nbsp;</td>
            </tr>
          <tr>
            <td class="h1" height="5px"></td>
            </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><?php include('../scripts/buttonset.php')?></td>
                <td align="right"><iframe src="http://<?php echo PRINT_SERVER ?>/printer_list.php?u=<?php echo $_SESSION['userid'] ?>" style="border:none; width:300px; height:90px"></iframe>&nbsp;</td>
              </tr>
            </table></td>
            </tr>
          </table>
  <table width="100%" border="0" cellspacing="4" cellpadding="4">
    <tr>
      <td><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
        <tr align="center" bgcolor="#666666" class="boldwhite1">
          <td width="17%" nowrap="nowrap"><?php echo setOrderTitle('Document', $currentPage, 1, $ord, $asc); ?></td>
          <td width="18%" nowrap="nowrap"><?php echo setOrderTitle('From', $currentPage, 2, $ord, $asc); ?></td>
          <td width="17%" nowrap="nowrap"><?php echo setOrderTitle('To', $currentPage, 3, $ord, $asc); ?></td>
          <td width="15%" nowrap="nowrap"><?php echo setOrderTitle('Status', $currentPage, 4, $ord, $asc); ?></td>
          <td width="14%" nowrap="nowrap"><?php echo setOrderTitle('Print', $currentPage, 5, $ord, $asc); ?></td>
          </tr>
        <?php $j=1;
	   foreach ($TSrvsched as $row_TSrvsched) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
        <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');">
          <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSrvsched['category_name'] ?></b></td>
          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSrvsched['startdate'] ?></b></td>
          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSrvsched['enddate'] ?></b></td>
          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSrvsched['Category'] ?></b></td>
          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" nowrap>
            <?php if ($_SESSION['accesskeys']['Assets']['Vehicles']['Allocate'] == 1) { ?>
            <?php if ($row_TSrvsched['Status'] != 32) { ?>
            <img src="/images/but_print.png" width="60" height="20" onclick="$(this).hide(); printSys('|print/<?php echo $row_TSrvsched['Classification']==20 ? 'sticker' : 'card' ?>-template.php?id=<?php echo $row_TSrvsched['SrvSchedID']; ?>')" />
            <img src="/images/but_clear.png" width="60" height="20" onclick="location.href='reprint.php?clr=1&schd=<?php echo $row_TSrvsched['SrvSchedID'], '&id=', $id; ?>'" style="cursor: pointer" />
            <?php } else { ?>
            <img src="/custom/images/reprint.png" width="70" height="20" onclick="location.href='reprint.php?schd=<?php echo $row_TSrvsched['SrvSchedID'], '&id=', $id; ?>'" style="cursor: pointer" />
            <img src="/custom/images/cust-reprint.png" width="70" height="20" onclick="location.href='sales/add.php?schd=<?php echo $row_TSrvsched['SrvSchedID']; ?>'" style="cursor: pointer" />
            <?php }} ?></td>
          </tr>
        <?php $j++; } ?>
        </table></td>
      </tr>
  </table>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>            </tr>
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
