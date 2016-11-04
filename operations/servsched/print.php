<?php
require_once('../../scripts/init.php');

vetAccess('Operations', 'Service Schedule', 'Print');

$id = _xget('id');

$sql = "SELECT `items_srv_sched`.*, ProductName, $vendor_sql, AssetName, status.Category, `useasset`, 
    `assetcat`, `items_srv_sched`.InvoiceDetailID, `invoicedetails`.InvoiceID 
    FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails` ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`      ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`       ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`        ON `invoices`.VendorID=`vendors`.VendorID
    INNER JOIN `{$_SESSION['DBCoy']}`.`status`         ON `items_srv_sched`.Status=status.CategoryID
    LEFT  JOIN `{$_SESSION['DBCoy']}`.`assets`         ON `items_srv_sched`.AssetID=`assets`.AssetID
    WHERE `SrvSchedID`=$id";
$row_TSched = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblsrvsched.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
            <table border="0" cellpadding="4" cellspacing="4">
              <tr>
                <td class="titles">Service:</td>
                <td align="left"><?php echo $row_TSched['ProductName'] ?></td>
              </tr>
              <tr>
                <td width="120" class="titles">Client:</td>
                <td><?php echo $row_TSched['VendorName'] ?></td>
              </tr>
              <tr>
                <td class="titles">Invoice #:</td>
                <td><?php echo $row_TSched['InvoiceID'], ' : ', $row_TSched['InvoiceDetailID'] ?></td>
              </tr>
              <tr>
                <td class="titles">Asset:</td>
                <td><?php echo $row_TSched['AssetName'] ?></td>
              </tr>
              <tr>
                <td class="titles">Schedule:</td>
                <td><table border="0" cellpadding="2" cellspacing="2" id="timeframe">
                  <tr>
                    <td><?php echo $row_TSched['startdate'] ?></td>
                    <td class="black-normal">to</td>
                    <td><?php echo $row_TSched['enddate'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Auto Renew:</td>
                <td><input type="checkbox" name="renew" disabled="disabled"<?php if ($row_TSched['renew'] == 1) {
                echo " checked=\"checked\"";
            } ?> /></td>
              </tr>
              <tr>
                <td class="titles">Status: </td>
                <td><?php echo $row_TSched['Category'] ?></td>
              </tr>
              <tr>
                <td class="titles">Notes:</td>
                <td><?php echo $row_TSched['Notes'] ?></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              
          </table></td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
	print();
});
</script>
</body>
</html>