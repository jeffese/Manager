<?php
require_once('../../scripts/init.php');

$id = intval(_xget('id'));
$vendor_req = vendorFlds("ReqBy", "req_by");
$vendor_giv = vendorFlds("GivBy", "giv_by");
$sql = "SELECT `requisitions`.`RequisitID`, `RequestDate`, units, $vendor_req, $vendor_giv, 
        IF(`transfertype` IN (11,14,17),`deptin`.`catname`,`outin`.`OutletName`) AS OutletIn,
        IF(`transfertype`>16,`deptout`.`catname`,`outout`.`OutletName`) AS OutletOut
    FROM `{$_SESSION['DBCoy']}`.`requisitions` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`req_items`       ON `requisitions`.`RequisitID`=`req_items`.`RequisitID`
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
    WHERE `requisitions`.Transfered=1
";

$TTranfers = getDBData($dbh, $sql);

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
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="boldwhite1"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
              <tr align="center" bgcolor="#666666" class="boldwhite1">
                <td nowrap="nowrap">Transfer ID</td>
                <td nowrap="nowrap">From</td>
                <td nowrap="nowrap">To</td>
                <td nowrap="nowrap">Requested By</td>
                <td nowrap="nowrap">Request Date</td>
                <td nowrap="nowrap">Units</td>
              </tr>
              <?php $j=1;
	   foreach ($TTranfers as $row_TTranfers) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
              <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
onclick="top.leftFrame.showMod('Item Transfers', '/stock/transfers/view.php?id=<?php echo $row_TTranfers['RequisitID']; ?>')">
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TTranfers['RequisitID'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TTranfers['OutletOut'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TTranfers['OutletIn'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TTranfers['req_by'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TTranfers['RequestDate'] ?></b></td>
                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TTranfers['units'] ?></b></td>
              </tr>
              <?php $j++;} ?>
            </table></td>
          </tr>

        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
