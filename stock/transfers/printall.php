<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Transfers'));
vetAccess('Stock', 'Transfers', 'Print');

qryfind("transfers");
preOrd("transfers", array('', 'RequisitID', 'Category', 'OutletOut', 'OutletIn', 'req_by', 'giv_by', 'RequestDate', 'Transfered'));

$vendor_req = vendorFlds("ReqBy", "req_by");
$vendor_giv = vendorFlds("GivBy", "giv_by");
$From = "FROM `{$_SESSION['DBCoy']}`.`requisitions` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`status`          ON `requisitions`.`transfertype`=`status`.`CategoryID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`outlets` `outin`  ON `requisitions`.`Outletin`=`outin`.`OutletID` 
            AND `requisitions`.`transfertype` IN (12,13,15,16,18,19)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`outlets` `outout` ON `requisitions`.`Outletout`=`outout`.`OutletID` 
            AND `requisitions`.`transfertype` IN (11,12,13,14,15,16)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `deptin` ON `requisitions`.`Outletin`=`deptin`.`catID` 
            AND `requisitions`.`transfertype` IN (11,14,17)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `deptout` ON `requisitions`.`Outletout`=`deptout`.`catID` 
            AND `requisitions`.`transfertype` IN (17,18,19)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `ReqBy` ON `requisitions`.`RequestedBy`=`ReqBy`.VendorID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `GivBy` ON `requisitions`.`GivenBy`=`GivBy`.VendorID
    {$qryvals}
";

$sql = "SELECT `RequisitID`, Category, `RequestDate`, Transfered, $vendor_req, $vendor_giv, 
        IF(`transfertype` IN (11,14,17),`deptin`.`catname`,`outin`.`OutletName`) AS OutletIn,
        IF(`transfertype`>16,`deptout`.`catname`,`outout`.`OutletName`) AS OutletOut
    {$From}{$orderval}";

$TTranfers = getDBData($dbh, $sql);
$currentPage = 'printall.php';
doExcel($TTranfers);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/xxpix.jpg" ?>" /></td>
          <td><span class="coytxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lbltransfers.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
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
                              <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                                <tr align="center" bgcolor="#666666" class="boldwhite1">
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Order ID', $currentPage, 1, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Total Value', $currentPage, 2, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Supplier', $currentPage, 3, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Order Date', $currentPage, 4, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Posted', $currentPage, 5, $ord, $asc); ?></td>
                                </tr>
                                <?php $j=1;
	   foreach ($TTranfers as $row_TTranfers) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                                <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onclick="location.href='view.php?id=<?php echo $row_TTranfers['RequisitID']; ?>'">
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TTranfers['RequisitID'] ?></b></td>
                                  <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TTranfers['TotalValue'] ?></b></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TTranfers['VendorName'] ?></b></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TTranfers['ReturnDate'] ?></b></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input type="checkbox" name="Transfered" value=""  <?php echo $row_TTranfers['Transfered']==1? "checked=\"checked\"": ""; ?> disabled="disabled" /></td>
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
                <td>&nbsp;</td>
              </tr>

            </table></td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br /><?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?>
          </span></td>
        </tr>
      </table></td>
  </tr>
</table><script type="text/javascript">
$(document).ready(function(){
	print();
});
</script>
</body>
</html>