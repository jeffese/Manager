<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = $_access['Employees'];
vetAccess('Personnel', 'Employees', 'View');

if (getLDAP($_SESSION['coyid'])) {
    $xtra_buts_0 = <<<EOF
<td align="center"><a href="sync.php"><img src="/images/nav_but/sync.png" title="Synchronize Staff" alt="Synchronize" width="33" height="34" border="0" /></a></td>
EOF;
    $xtra_buts_1 = <<<EOF
<td align="center"><a href="sync.php" class="titles">Sync</a></td>
EOF;
}

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, $access['Print'], 1, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","","","","","","find.php","printall.php","");
$rec_status = 4;

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

$currentPage = 'index.php';
$maxRows_TEmployees = 30;

$TabArray = 'TEmployees';
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
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/staff.png" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblemploy.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td>&nbsp;</td>
              </tr>
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
                                  <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onclick="location.href='view.php?id=<?php echo $row_TEmployees['VendorID']; ?>'">
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
              <tr>
                <td></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php'); ?></td>
              </tr>

            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">

          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>