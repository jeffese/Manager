<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Operations'));
$access = _xvar_arr_sub($_access, array('Service Schedule'));
vetAccess('Operations', 'Service Schedule', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","","","index.php","","","","");
$rec_status = 0;

//qryfind("prod", array('name'));
preOrd("prod", array('', 'VendorName', 'ProductName', 'InvoiceID'));

$outid = _xses('OutletID');
$From = "FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv` ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`  ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`   ON `invoices`.VendorID=`vendors`.VendorID
    LEFT  JOIN `{$_SESSION['DBCoy']}`.`payments`  ON `invoices`.InvoiceID=`payments`.`InvoiceID`
    WHERE `invoices`.Posted=1 AND `serials`='' AND 
        (`event_length`>8 OR `useasset`=1) AND `invoices`.`OutletID` IN (0$outid) AND
        (amtbal>=creditlimit OR `payments`.`Posted`=1 AND `payments`.`Amount`>=`LineTotal`)";

$sql = "SELECT `InvoiceDetailID`, `invoices`.`InvoiceID`, $vendor_sql, `ProductName`
    {$From}{$orderval}";

$currentPage = 'pick.php';
$maxRows_TSched = 100;

$TabArray = 'TSched';
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
<table width="100" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblsrvsched.png); background-repeat:no-repeat">&nbsp;</td>
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
                                <td nowrap="nowrap"><?php echo setOrderTitle('Client', $currentPage, 1, $ord, $asc); ?></td>
                                <td nowrap="nowrap"><?php echo setOrderTitle('Service', $currentPage, 2, $ord, $asc); ?></td>
                                <td nowrap="nowrap"><?php echo setOrderTitle('Invoice #', $currentPage, 3, $ord, $asc); ?></td>
                                </tr>
                              <?php $j=1;
   foreach ($TSched as $row_TSched) {
  $k=$j%2;
  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
  ?>
                              <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
onclick="location.href='add.php?inv=<?php echo $row_TSched['InvoiceDetailID'] ?>'">
                                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSched['VendorName'] ?></b></td>
                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSched['ProductName'] ?></b></td>
                                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TSched['InvoiceID'] ?></b></td>
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