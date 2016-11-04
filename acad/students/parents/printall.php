<?php
require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Parents'));
vetAccess('Academics', 'Parents', 'print');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, $access['Print'], 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","","","","","","find.php","","");
$rec_status = 4;

qryfind('parent', array('name'));
preOrd('parent', array('', 'VendorName', 'ReferredBy', 'FaxNumber', 'loc', 'sex', 'age'));

$From = "FROM `{$_SESSION['DBCoy']}`.`vendors` LEFT JOIN `{$_SESSION['DBCoy']}`.`status` cat ON `vendors`.categoryid = cat.CategoryID LEFT JOIN `{$_SESSION['DBCoy']}`.`status` proj ON `vendors`.Discount = proj.CategoryID WHERE VendorType=6 {$qryvals}";

$sql = "SELECT `vendors`.`VendorID`, $vendor_sql, CONCAT(`City`, ' ', `StateOrProvince`) AS loc, `ReferredBy`, `FaxNumber`, `sex`, `datehired`, TIMESTAMPDIFF(YEAR, `dateofbirth`, CURDATE()) AS age, `InUse` {$From}{$orderval}";

$TParent = getDBData($dbh, $sql);
$currentPage = 'printall.php';
doExcel($TParent);

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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblparents.png); background-repeat:no-repeat">&nbsp;</td>
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
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Name', $currentPage, 1, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Job Title', $currentPage, 2, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Company', $currentPage, 3, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('City', $currentPage, 4, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Gender', $currentPage, 5, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Age', $currentPage, 6, $ord, $asc); ?></td>
                                    </tr>
                                  <?php $j=1;
	   foreach ($TParent as $row_TParent) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                                  <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');">
                                    <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TParent['VendorName'] ?></b></td>
                                    <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><?php echo $row_TParent['ReferredBy'] ?></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TParent['FaxNumber'] ?></b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TParent['loc'] ?></b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b>
                                      <script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TParent['sex']; ?>) {
case 1: document.write("Male"); break;
case 2: document.write("Female"); break;
}</script>
                                      </b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TParent['age'] ?></b></td>
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