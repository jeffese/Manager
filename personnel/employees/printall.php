<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Employees'));
vetAccess('Personnel', 'Employees', 'Print');

qryfind('staff', array('name'));
preOrd("staff", array('', 'vendorcode', 'VendorName', 'cat', 'ReferredBy', 'proj', 'FaxNumber', 'sex', 'age', 'datehired', 'InUse'));

$From = "FROM `{$_SESSION['DBCoy']}`.`vendors` 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` cat ON `vendors`.categoryid = cat.catID 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`status` proj ON `vendors`.Discount = proj.CategoryID 
            WHERE VendorType=5 {$qryvals}";

$sql = "SELECT `vendors`.`VendorID`, `vendors`.`vendorcode`, $vendor_sql, `vendors`.`ReferredBy`, 
            `vendors`.`FaxNumber`, `vendors`.`sex`, `vendors`.`datehired`, 
            TIMESTAMPDIFF(YEAR, `vendors`.`dateofbirth`, CURDATE()) AS age, `vendors`.`InUse`, 
            cat.catname AS cat, proj.Category AS proj {$From}{$orderval}";

$TEmployees = getDBData($dbh, $sql);
$currentPage = 'printall.php';
doExcel($TEmployees);

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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblemploy.png); background-repeat:no-repeat">&nbsp;</td>
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
                              <td align="center" valign="top" bgcolor="#FFFBF0">
                                <table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                                  <tr align="center" bgcolor="#666666" class="boldwhite1">
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Staff No.', $currentPage, 1, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Name', $currentPage, 2, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Category', $currentPage, 3, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Job Title', $currentPage, 4, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Project', $currentPage, 5, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Location', $currentPage, 6, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Gender', $currentPage, 7, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Age', $currentPage, 8, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Date Hired', $currentPage, 9, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Active', $currentPage, 10, $ord, $asc); ?></td>
                                    </tr>
                                  <?php $j=1;
	   foreach ($TEmployees as $row_TEmployees) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                                  <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal">
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['vendorcode'] ?></b></td>
                                    <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['VendorName'] ?></b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['cat'] ?></b></td>
                                    <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><?php echo $row_TEmployees['ReferredBy'] ?></td>
                                    <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['proj'] ?></b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['FaxNumber'] ?></b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b>
                                      <script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TEmployees['sex']; ?>) {
case 1: document.write("Male"); break;
case 2: document.write("Female"); break;
}</script>
                                      </b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['age'] ?></b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><script type="text/javascript">
document.write(formatdate('<?php echo $row_TEmployees['datehired'] ?>'));
	                                  </script></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input type="checkbox" name="InUse" value=""  <?php echo $row_TEmployees['InUse']==1? "checked=\"checked\"": ""; ?> disabled="disabled" /></td>
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