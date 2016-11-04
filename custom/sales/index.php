<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = $_access['Sales'];
vetAccess('Accounts', 'Sales', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 1, 1, 0, 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","","","../kiosk.php","","find.php","","");
$rec_status = 4;

qryfind("sale", array("name"));
preOrd('sale', array('', 'InvoiceID', 'VendorName', 'Grandvalue', 'LedgerDate', 'Posted'));

$outid = _xses('OutletID');
$From = "FROM `{$_SESSION['DBCoy']}`.`invoices` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlets`                ON `invoices`.OutletID = outlets.OutletID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` outcat ON `outlets`.Dept = outcat.catID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`                 ON `invoices`.VendorID = vendors.VendorID
    WHERE `invoices`.OutletID IN ($outid) {$qryvals}";

$sql = "SELECT `InvoiceID`, `LedgerDate`, `Grandvalue`, Posted, $vendor_sql {$From}{$orderval}";

$currentPage = 'index.php';
$maxRows_TSales = 30;

$TabArray = 'TSales';
require_once (ROOT.'/scripts/fetchdata.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
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
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><img src="/images/sales.jpg" alt="" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>
              <td colspan="2" style="height:30px; min-width:500px; background-image:url(/images/lblsales.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
            <tr>
              <td height="5px" colspan="2" class="h1"></td>
              </tr>
            <tr>
              <td><?php include('../../scripts/buttonset.php')?></td>
              <td><iframe src="http://<?php echo PRINT_SERVER ?>/printer_list.php?u=<?php echo $_SESSION['userid'] ?>" style="border:none; width:300px; height:90px"></iframe></td>
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
                          <td nowrap="nowrap"><?php echo setOrderTitle('Invoice ID', $currentPage, 1, $ord, $asc); ?></td>
                          <td nowrap="nowrap"><?php echo setOrderTitle('Customer', $currentPage, 2, $ord, $asc); ?></td>
                          <td nowrap="nowrap"><?php echo setOrderTitle('Total Value', $currentPage, 3, $ord, $asc); ?></td>
                          <td nowrap="nowrap"><?php echo setOrderTitle('Sale Date', $currentPage, 4, $ord, $asc); ?></td>
                          <td nowrap="nowrap"><?php echo setOrderTitle('Posted', $currentPage, 5, $ord, $asc); ?></td>
                          </tr>
                        <?php $j=1;
	   foreach ($TSales as $row_TSales) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                        <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onclick="location.href='view.php?id=<?php echo $row_TSales['InvoiceID']; ?>'">
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSales['InvoiceID'] ?></b></td>
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSales['VendorName'] ?></b></td>
                          <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSales['Grandvalue'] ?></b></td>
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSales['LedgerDate'] ?></b></td>
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input type="checkbox" name="Posted" value=""  <?php echo $row_TSales['Posted']==1? "checked=\"checked\"": ""; ?> disabled="disabled" /></td>
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
      <td valign="bottom" class="framebot"><span class="greytxt">Copyright © 2010 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>
</html>
