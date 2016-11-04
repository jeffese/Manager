<?php
require_once('../../scripts/init.php');

if (!vetAccess('Personnel', 'Pay Slips', 'Print', false)) {
?>
        <script>alert('Access Denied!')</script>
<?php
exit;
}
$po = intval(_xget('id'));
$bth = intval(_xget('bth'));

$sql = "SELECT `dtfrom`, `dtto`, DATEDIFF(`dtto`, `dtfrom`)+1 AS `dys`, posted,
    DATE_FORMAT(`dtfrom`,'%b %Y') AS `mth`, DATE_FORMAT(`dtfrom`,'%b-%Y') AS `_mth` 
    FROM `{$_SESSION['DBCoy']}`.`paybatch` WHERE paybatchid=$bth";
$row_TPay = getDBDataRow($dbh, $sql);

if (count($row_TPay) == 0)
    exit;

$vendor_supo = vendorFlds("VendorSup", "supo");
//$fld = $row_TPay['posted'] == 0 ? '`vendors`.`contract`' : '`ref_no`';
$join = $row_TPay['posted'] == 0 ? '' : "INNER JOIN `{$_SESSION['DBCoy']}`.`payslip` ON `vendors`.`VendorID`=`payslip`.`staffid`";
$sql = "SELECT $vendor_sql, `vendors`.`MobilePhone`, `salary_name`, 
            `loc`.`Category` AS `loc`, `vendors`.Discount, $vendor_supo
            FROM `{$_SESSION['DBCoy']}`.`vendors` 
            $join
            LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `VendorSup` ON `vendors`.supervisor = VendorSup.VendorID 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`salaryscale` ON `vendors`.salary = salaryscale.salary_id 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`status` loc ON `vendors`.FaxNumber = loc.CategoryID 
            WHERE `vendors`.DeptID=$po AND `vendors`.`VendorType`=5 AND `vendors`.`InUse`=1";
$TEmployees = getDBData($dbh, $sql);

$sql = "SELECT catname
        FROM `{$_SESSION['DBCoy']}`.`classifications`
        WHERE `catID`=$po";
$TProj = getDBDatarow($dbh, $sql);
$proj = $TProj['catname'];

if (count($TEmployees) == 0)
    exit;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
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
                <td class="h1">MONTHLY DEPARTMENT HOUR TIMESHEET</td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table border="2" cellpadding="10" cellspacing="0" style="margin:10px">
                  <tr>
                    <td class="titles">Department</td>
                    <td class="titles">Head Of Department</td>
                    <td width="200" class="titles">Signature</td>
                    </tr>
                  <tr>
                    <td><strong><?php echo $proj ?></strong></td>
                    <td>&nbsp;</td>
                    <td height="50" align="left">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="2" cellspacing="0" cellpadding="5">
                  <tr class="boldwhite1">
                    <td width="120" valign="top" bgcolor="#333333" class="boldwhite1">#</td>
                    <td width="322" align="left" bgcolor="#333333">Name</td>
                    <td width="322" align="left" bgcolor="#333333">Salary Package</td>
                    <td width="322" align="left" bgcolor="#333333">Location</td>
                    <td width="322" align="left" bgcolor="#333333">Start Date</td>
                    <td width="322" align="left" bgcolor="#333333">End Date</td>
                    <td width="322" align="left" bgcolor="#333333">Work Days</td>
                    <td width="322" align="left" bgcolor="#333333">Verify</td>
                    <td width="322" align="left" bgcolor="#333333">Project Manager</td>
                    <td width="322" align="left" bgcolor="#333333">Signature</td>
                  </tr>
                <?php
                    $i = 1;
                    foreach ($TEmployees as $row_TEmployees) {
                ?>
                  <tr class="black-normal">
                    <td><?php echo $i ?></td>
                    <td align="left"><strong><?php echo $row_TEmployees['VendorName'] ?></strong><br />
                    <?php echo $row_TEmployees['MobilePhone'] ?></td>
                    <td align="left"><?php echo $row_TEmployees['salary_name'] ?></td>
                    <td align="left"><?php echo $row_TEmployees['loc'] ?></td>
                    <td align="left"><?php echo $row_TPay['dtfrom'] ?></td>
                    <td align="left"><?php echo $row_TPay['dtto'] ?></td>
                    <td align="left"><?php echo $row_TPay['dys'] ?></td>
                    <td align="left">&nbsp;</td>
                    <td align="left"><?php echo $row_TEmployees['supo'] ?></td>
                    <td align="left">&nbsp;</td>
                  </tr>
                <?php $i++; } ?>
                </table></td>
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
<script type="text/javascript">
$(document).ready(function(){
	print();
});
</script>
</body>
</html>
