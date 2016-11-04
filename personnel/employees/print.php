<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Employees'));
vetAccess('Personnel', 'Employees', 'Print');

$id = _xget('id');
$vendor_supo = vendorFlds("VendorSup", "supo");
$sql = "SELECT `vendors`.*, salary_name, `parts`, `flds`, country.country AS cntry, 
    nation.country as nation, currencies.code, $vendor_supo, dept.catname AS dept, 
    cat.catname AS cat, proj.Category AS proj, educ.Category AS educate, 
    bank.Category AS bank, plan.Category AS plan, hosp.Category AS hosp 
FROM `{$_SESSION['DBCoy']}`.`vendors` 
LEFT JOIN `{$_SESSION['DBCoy']}`.`salaryscale` ON `vendors`.salary = salaryscale.salary_id 
LEFT JOIN `{$_SESSION['DBCoy']}`.`currencies` ON `vendors`.currency = currencies.cur_id 
LEFT JOIN `".DB_NAME."`.`country` ON `vendors`.country=country.country_id 
LEFT JOIN `".DB_NAME."`.`country` nation ON `vendors`.nationality = nation.country_id 
LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `VendorSup` ON `vendors`.supervisor = VendorSup.VendorID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` dept ON `vendors`.DeptID = dept.catID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` cat ON `vendors`.categoryid = cat.catID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` proj ON `vendors`.Discount = proj.CategoryID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` bank ON `vendors`.bank = bank.CategoryID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` plan ON `vendors`.salary = plan.CategoryID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` hosp ON `vendors`.parentcompany = hosp.CategoryID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` educ ON `vendors`.education = educ.CategoryID 
WHERE `vendors`.`VendorID`={$id}";
$row_TEmployees = getDBDataRow($dbh, $sql);

$pixrnd = _xses('pixrnd');
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
<script type="text/javascript" src="/personnel/payslips/payees/resource.js"></script>
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
                <td class="h1">Info</td>
              </tr>
              <tr>
                <td><?php $pixcnt = 20; $max = 20000000; ?>
                  <?php 
$pictfld = $row_TEmployees['picturefile'];
$fpath = $id;
$pixdir = STAFFPIX_DIR;
$pixi = 'xx';
$xid = '';
$label = '';
?>
                  <?php include('../../scripts/viewpix.php'); ?>
                  &nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                    <tr>
                      <td valign="top" class="titles">Signature:</td>
                      <td align="left"><img src="<?php echo (($row_TEmployees['signfile']=='') ? '/images/noimage.jpg' : STAFFPIX_DIR.$row_TEmployees['VendorID']."/sign/xpix.jpg".$pixrnd); ?>" alt="" name="sign" id="sign" /></td>
                    </tr>
                    <tr>
                      <td class="titles">&nbsp;</td>
                      <td class="red-normal">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class="titles">Staff ID:</td>
                      <td class="red-normal"><b><?php echo $row_TEmployees['VendorID']; ?></b></td>
                    </tr>
                    <tr>
                      <td class="titles">Staff Number:</td>
                      <td align="left"><?php echo $row_TEmployees['vendorcode'] ?></td>
                    </tr>
                    <tr>
                      <td class="titles">&nbsp;</td>
                      <td align="left">&nbsp;</td>
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
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Personal Details</td>
                  </tr>
                  <tr>
                    <td class="titles">Gender:</td>
                    <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input type="radio" name="sex" value="1" size="32" <?php if (!(strcmp($row_TEmployees['sex'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                        <td>Male</td>
                        <td><input type="radio" name="sex" value="2" size="32" <?php if (!(strcmp($row_TEmployees['sex'], 2))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                        <td>Female</td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Disability:</td>
                    <td><label>
                      <input type="checkbox" name="ability" id="ability" <?php if (!(strcmp($row_TEmployees['ability'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" />
                    </label></td>
                  </tr>
                  <tr>
                    <td class="titles">Marital Status </td>
                    <td><span class="orangetext">
                      <script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TEmployees['marital_status']; ?>) {
case 1: document.write("Single - never married"); break;
case 2: document.write("Married"); break;
case 3: document.write("Divorced"); break;
case 4: document.write("Widowed"); break;
case 5: document.write("Separated"); break;
default: document.write("");
}</script>
                    </span></td>
                  </tr>
                  <tr>
                    <td class="titles">Religion:</td>
                    <td><script type="text/javascript">
switch (<?php echo $row_TEmployees['religion']; ?>) {
case 1: document.write("Christian"); break;
case 2: document.write("Muslim"); break;
case 3: document.write("Jewish"); break;
case 4: document.write("Budhist"); break;
case 5: document.write("Atheist"); break;
default: document.write("Others");
}</script></td>
                  </tr>
                  <tr>
                    <td class="titles">Date of Birth:</td>
                    <td><script type="text/javascript">
document.write(formatdate('<?php echo $row_TEmployees['dateofbirth'] ?>'));
	                            </script></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Passport No.:</td>
                    <td><?php echo $row_TEmployees['passportno'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Nationality: </td>
                    <td><?php echo $row_TEmployees['nation'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">State Of Origin:</td>
                    <td><?php echo $row_TEmployees['stateorigin'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Home Town:</td>
                    <td><?php echo $row_TEmployees['locgovorigin'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Native Tongue:</td>
                    <td><?php echo $row_TEmployees['nativetongue'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Languages Spoken: </td>
                    <td><?php echo $row_TEmployees['ContactsInterests']; ?></td>
                  </tr>
                  <tr>
                    <td width="120" valign="top" class="titles">Permanent Home Address:</td>
                    <td width="322" align="left"><?php echo $row_TEmployees['PostalCode'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Contact Details</td>
                  </tr>
                  <tr>
                    <td width="120" valign="top" class="titles">Address:</td>
                    <td width="322" align="left"><?php echo $row_TEmployees['BillingAddress'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">City:</td>
                    <td align="left"><?php echo $row_TEmployees['City'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Country:</td>
                    <td align="left"><?php echo $row_TEmployees['cntry'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">State:</td>
                    <td align="left"><?php echo $row_TEmployees['StateOrProvince'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="bluetxt">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Home Phone:</td>
                    <td><?php echo $row_TEmployees['homephone'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Mobile Phone:</td>
                    <td><?php echo $row_TEmployees['MobilePhone'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Email:</td>
                    <td align="left"><?php echo $row_TEmployees['EmailAddress'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Emergency Contact</td>
                  </tr>
                  <tr>
                    <td width="120" valign="top" class="titles">Name:</td>
                    <td width="322" align="left"><?php echo $row_TEmployees['emername'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Relationship:</td>
                    <td align="left"><script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TEmployees['emertype']; ?>) {
case 1: document.write("Brother"); break;
case 2: document.write("Sister"); break;
case 3: document.write("Husband"); break;
case 4: document.write("Wife"); break;
case 5: document.write("Father"); break;
case 6: document.write("Mother"); break;
case 7: document.write("Child"); break;
case 8: document.write("In-Law"); break;
case 9: document.write("Friend"); break;
case 10: document.write("Cousin"); break;
case 11: document.write("Uncle"); break;
case 12: document.write("Aunty"); break;
case 13: document.write("Others"); break;
default: document.write("");
}</script></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Address:</td>
                    <td align="left"><?php echo $row_TEmployees['emeraddress'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Phone No.:</td>
                    <td align="left" nowrap="nowrap"><?php echo $row_TEmployees['emerphone'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="bluetxt">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Spouse:</td>
                    <td><?php echo $row_TEmployees['spousename'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Children:</td>
                    <td><?php echo $row_TEmployees['ChildrenNames'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Spouse Phone No.:</td>
                    <td align="left"><?php echo $row_TEmployees['PhoneNumber'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Work Info</td>
                    </tr>
                  <tr>
                    <td width="120" valign="top" class="titles">Department:</td>
                    <td width="322" align="left"><?php echo $row_TEmployees['dept'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Category:</td>
                    <td align="left"><?php echo $row_TEmployees['cat'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Educational Level:</td>
                    <td align="left"><?php echo $row_TEmployees['educate'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Work Experience:</td>
                    <td align="left"><?php echo $row_TEmployees['experience'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Job Title:</td>
                    <td align="left"><?php echo $row_TEmployees['ReferredBy'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Project:</td>
                    <td align="left" nowrap="nowrap"><?php echo $row_TEmployees['proj'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Location:</td>
                    <td align="left"><?php echo $row_TEmployees['FaxNumber'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Health Plan:</td>
                    <td align="left"><?php echo $row_TEmployees['plan'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Hospital:</td>
                    <td align="left"><?php echo $row_TEmployees['hosp'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Insured:</td>
                    <td align="left"><input name="credit" type="checkbox" id="credit"  <?php if (!(strcmp($row_TEmployees['credit'],""))) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Direct Line.:</td>
                    <td align="left"><?php echo $row_TEmployees['workphone'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Extension:</td>
                    <td><?php echo $row_TEmployees['Extension'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Supervisor:</td>
                    <td><?php echo $row_TEmployees['supo'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Date Hired:</td>
                    <td><script type="text/javascript">
document.write(formatdate('<?php echo $row_TEmployees['datehired'] ?>'));
	                            </script></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Terminated:</td>
                    <td><table border="0" cellspacing="2" cellpadding="0">
                      <tr>
                        <td><input type="checkbox" name="InUse" value=""  <?php echo $row_TEmployees['InUse']==1? "checked=\"checked\"": ""; ?> disabled="disabled" /></td>
                        <td><table border="0" cellpadding="2" cellspacing="0" id="fired" style="display:<?php echo $row_TEmployees['InUse']==1 ? 'block':'none'; ?>">
                          <tr>
                            <td class="blacktxt">Date:</td>
                            <td><script type="text/javascript">
document.write(formatdate('<?php echo $row_TEmployees['datefired'] ?>'));
	                                        </script></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">On Leave:</td>
                    <td align="left"><input name="leavstatus" type="checkbox" id="leavstatus" value=""  <?php if (!(strcmp($row_TEmployees['leavstatus'],""))) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Salary</td>
                  </tr>
                  <tr>
                    <td class="titles">Account Balance:</td>
                    <td align="left"><?php echo number_format($row_TEmployees['amtbal'], 2) ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Bank:</td>
                    <td width="322" align="left"><?php echo $row_TEmployees['bank'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Account No.:</td>
                    <td align="left"><?php echo $row_TEmployees['logofile'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Salary Package:</td>
                    <td align="left"><?php echo $row_TEmployees['salary_name'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left"><input type="hidden" id="tax" name="tax" value="<?php echo $row_TEmployees['tax'] ?>" />
                      <input type="hidden" id="parts" name="parts" value="<?php echo $row_TEmployees['parts'] ?>" />
                      <input type="hidden" id="flds" name="flds" value="<?php echo $row_TEmployees['flds'] ?>" /></td>
                  </tr>
                  <tr>
                    <td id="reswin" colspan="2">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="2" cellspacing="2">
                  <tr>
                    <td class="h1">Notes</td>
                  </tr>
                  <tr>
                    <td><?php echo $row_TEmployees['Notes']; ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
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
    prepRes(false, '');
	print();
});
</script>
</body>
</html>