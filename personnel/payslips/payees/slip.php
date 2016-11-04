<?php
require_once('../../../scripts/init.php');
require_once("paygen.php");

if (!vetAccess('Personnel', 'Pay Slips', 'Dispatch', false)) {
?><script>alert('Access Denied!')</script><?php
    exit;
}
$id = intval(_xget('id'));
$bth = intval(_xget('bth'));

$sql = "SELECT *, DATEDIFF(`dtto`, `dtfrom`)+1 AS `dys`, `posted` 
    FROM `{$_SESSION['DBCoy']}`.`paybatch` WHERE paybatchid=$bth";
$row_TPay = getDBDataRow($dbh, $sql);

$tax = $row_TPay['posted'] == 0 ? '`contract` AS `ref_no`, `salaryscale`.*, 
    `vendors`.`worked`, `VendorID` AS payslip_id,' :
        '`ref_no`, `code`, `payslip`.*, `details` AS';

$ijn = $row_TPay['posted'] == 0 ? 
    "INNER JOIN `{$_SESSION['DBCoy']}`.`salaryscale` ON `vendors`.`salary`=`salaryscale`.`salary_id`
    WHERE `VendorID`" : 
    "INNER JOIN `{$_SESSION['DBCoy']}`.`payslip` ON `vendors`.`VendorID`=`payslip`.`staffid`
    INNER JOIN `{$_SESSION['DBCoy']}`.`paybatch` ON `payslip`.`paybatchid`=`paybatch`.`paybatchid`
    WHERE `payslip_id`";

$sql = "SELECT `VendorID`, `vendorcode`, `ContactTitle`, `ContactFirstName`, `ContactMidName`, 
            `amtbal`, `ContactLastName`, `InUse`, $tax `tax`
            FROM `{$_SESSION['DBCoy']}`.`vendors` 
            $ijn=$id";
$row_TEmployees = getDBDataRow($dbh, $sql);

if ($row_TPay['posted'] == 1) {
    $codes = explode('@@@', $row_TEmployees['code']);
    $row_TEmployees['parts'] = $codes[1];
    $row_TEmployees['typs'] = $codes[2];
    $row_TEmployees['cmls'] = $codes[3];
    $row_TEmployees['ftyp'] = $codes[4];
    $row_TEmployees['oprs'] = $codes[5];
    $row_TEmployees['fncs'] = $codes[6];
    $row_TEmployees['flds'] = $codes[7];
    $row_TEmployees['wins'] = $codes[8];
    $row_TEmployees['state'] = $codes[9];
    $row_TEmployees['deduct'] = $codes[10];
    $row_TEmployees['InUse'] = 1;
}

$prep = prepView($row_TEmployees, $row_TPay);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Payslip</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<?php if (isset($_GET['p'])) { ?>
<script type="text/javascript">
$(document).ready(function(){
	print();
});
</script>
<?php } ?>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td><img src="<?php echo COYPIX_DIR, $_SESSION['COY']['CoyID']."/xxpix.jpg" ?>" /></td>
        <td><span class="coytxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:460px; background-image:url(/images/lblpay_slip.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table border="0" cellpadding="2" cellspacing="4">
                    <tr>
                      <td class="titles">Staff ID:</td>
                      <td class="red-normal"><b><?php echo $row_TEmployees['VendorID']; ?></b></td>
                    </tr>
                    <tr>
                      <td class="titles">Staff Number:</td>
                      <td align="left"><?php echo $row_TEmployees['vendorcode'] ?></td>
                    </tr>
                    <tr>
                      <td height="4"></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td class="titles">Title:</td>
                      <td><?php echo $row_TEmployees['ContactTitle'] ?></td>
                    </tr>
                    <tr>
                      <td class="titles">First Name:</td>
                      <td><?php echo $row_TEmployees['ContactFirstName'] ?></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles">Middle Name:</td>
                      <td><?php echo $row_TEmployees['ContactMidName'] ?></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles">Last Name:</td>
                      <td><?php echo $row_TEmployees['ContactLastName'] ?></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td height="4"></td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="2" cellspacing="4">
                  <tr>
                    <td width="120" class="titles">Batch ID:</td>
                    <td class="blue-normal"><b><?php echo $row_TPay['paybatchid']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Payslip ID:</td>
                    <td align="left"><?php echo $row_TEmployees['payslip_id'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Ref. No.:</td>
                    <td align="left"><?php echo $row_TEmployees['ref_no'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Title:</td>
                    <td align="left"><?php echo $row_TPay['payday'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Period:</td>
                    <td align="left"><?php echo $row_TPay['dtfrom'] ?> -&gt; <?php echo $row_TPay['dtto'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Generated on:</td>
                    <td align="left"><?php echo $row_TPay['dategen'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Net Salary:</td>
                    <td align="left" class="blue-normal"><strong><?php echo $_SESSION['COY']['code'], ' ', number_format($prep['Total'], 2), ' ', $_SESSION['COY']['unitcode'] ?></strong></td>
                  </tr>
                  <tr>
                    <td height="10"></td>
                    <td></td>
                  </tr>
                </table></td>
              </tr>
              <?php if ($row_TPay['salary'] == 1) { 
                  array_pop($prep['sal']); ?>
              <tr>
                <td><table border="0" cellspacing="2" cellpadding="2" width="100%">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Salary</td>
                  </tr>
                <?php foreach ($prep['sal'] as $fld=>$val) { ?>
                  <tr>
                    <td width="120" class="titles"><?php echo $fld ?>:</td>
                    <td><?php echo $val ?></td>
                  </tr>
                <?php } ?>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                  </tr>
                </table></td>
              </tr>
              <?php } ?>
              <?php if ($row_TPay['bonus'] == 1) {  
                  array_pop($prep['bon']); ?>
              <tr>
                <td><table border="0" cellspacing="2" cellpadding="2" width="100%">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Bonus</td>
                  </tr>
                <?php foreach ($prep['bon'] as $fld=>$val) { ?>
                  <tr>
                    <td width="120" class="titles"><?php echo $fld ?>:</td>
                    <td><?php echo $val ?></td>
                  </tr>
                <?php } ?>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                  </tr>
                </table></td>
              </tr>
              <?php } ?>
              <?php if ($row_TPay['salary'] == 1) {  
                  array_pop($prep['ded']); ?>
              <tr>
                <td><table border="0" cellspacing="2" cellpadding="2" width="100%">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Deductions</td>
                  </tr>
                <?php foreach ($prep['ded'] as $fld=>$val) {
                    if ($fld=="vals") continue;?>
                  <tr>
                    <td width="120" class="titles"><?php echo $fld ?>:</td>
                    <td><?php echo $val ?></td>
                  </tr>
                <?php } ?>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                  </tr>
                </table></td>
              </tr>
              <?php } ?>
              <tr>
                <td><table border="0" cellspacing="2" cellpadding="2" width="100%">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Tax</td>
                  </tr>
                <?php array_pop($prep['tax']); 
                   foreach ($prep['tax'] as $fld=>$val) { ?>
                  <tr>
                    <td width="120" class="titles"><?php echo $fld ?>:</td>
                    <td><?php echo $val ?></td>
                  </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                  </tr>
                <?php } ?>
                </table></td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="2" cellpadding="2" width="100%">
                  <tr>
                    <td class="h1">Notes</td>
                  </tr>
                  <tr>
                    <td><?php echo $row_TPay['description']; ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td></td>
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
</body>
</html>
