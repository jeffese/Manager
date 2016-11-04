<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Students'));
vetAccess('Academics', 'Students', 'print');

$id = _xget('id');
$sql = "SELECT `vendors`.*, country.country AS cntry, nation.country as nation, dept.catname AS dept, cat.Category AS cat, 
    CONCAT(`guard`.`ContactLastName`, ' ', `guard`.`ContactFirstName`, ' ', `guard`.`ContactMidName`, ' ', 
    `guard`.`ContactTitle`, ' {', `guard`.`VendorID`, '}') AS parent, CONCAT(catname, ' > ', `prog_name`) AS prog,
    CONCAT(`class_name`, IF(arm_code='', '', ' > '), arm_code) AS stud_class
    FROM `{$_SESSION['DBCoy']}`.`vendors` 
    LEFT JOIN `" . DB_NAME . "`.`country` ON `vendors`.country=country.country_id 
    LEFT JOIN `" . DB_NAME . "`.`country` nation ON `vendors`.nationality=nation.country_id 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `guard` ON `guard`.VendorID=vendors.parentcompany 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status` cat ON `vendors`.categoryid=cat.CategoryID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `vendors`.`DeptID`=`sch_arms`.`arm_id`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`sch_class` ON `sch_arms`.`class`=`sch_class`.`class_id`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`sch_programs` ON `sch_class`.`program`=`sch_programs`.`prog_id`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` dept ON `sch_programs`.`department`=dept.`catID`
    WHERE `vendors`.`VendorID`=" . $_SESSION['stud_id'];
$row_TStudents = getDBDataRow($dbh, $sql);

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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblstudents.png); background-repeat:no-repeat">&nbsp;</td>
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
$pictfld = $row_TStudents['picturefile'];
$fpath = $_SESSION['stud_id'];
$pixdir = STUDPIX_DIR;
$pixi = 'x';
$xid = '';
$label = '';
?>
                  <?php include('../../scripts/viewpix.php'); ?>
                  &nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                    <tr>
                      <td class="titles">Student ID:</td>
                      <td class="red-normal"><b><?php echo $row_TStudents['VendorID']; ?></b></td>
                    </tr>
                    <tr>
                      <td class="titles">Student Number:</td>
                      <td align="left"><?php echo $row_TStudents['vendorcode'] ?></td>
                    </tr>
                    <tr>
                      <td class="titles">&nbsp;</td>
                      <td align="left">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class="titles">Title:</td>
                      <td><?php echo $row_TStudents['ContactTitle'] ?></td>
                    </tr>
                    <tr>
                      <td class="titles">First Name:</td>
                      <td><?php echo $row_TStudents['ContactFirstName'] ?></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles">Middle Name:</td>
                      <td><?php echo $row_TStudents['ContactMidName'] ?></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles">Last Name:</td>
                      <td><?php echo $row_TStudents['ContactLastName'] ?></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Personal Details</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Gender:</td>
                    <td width="322"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input type="radio" name="sex" value="1" size="32" <?php if (!(strcmp($row_TStudents['sex'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                        <td>Male</td>
                        <td><input type="radio" name="sex" value="2" size="32" <?php if (!(strcmp($row_TStudents['sex'], 2))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                        <td>Female</td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Disability:</td>
                    <td><label>
                      <input type="checkbox" name="ability" id="ability" <?php if (!(strcmp($row_TStudents['ability'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" />
                    </label></td>
                  </tr>
                  <tr>
                    <td class="titles">Marital Status </td>
                    <td><span class="orangetext">
                      <script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TStudents['marital_status']; ?>) {
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
switch (<?php echo $row_TStudents['religion']; ?>) {
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
document.write(formatdate('<?php echo $row_TStudents['dateofbirth'] ?>'));
	                            </script></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Passport No.:</td>
                    <td><?php echo $row_TStudents['passportno'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Nationality: </td>
                    <td><?php echo $row_TStudents['nation'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">State Of Origin:</td>
                    <td><?php echo $row_TStudents['stateorigin'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Home Town:</td>
                    <td><?php echo $row_TStudents['locgovorigin'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Native Tongue:</td>
                    <td><?php echo $row_TStudents['nativetongue'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Languages Spoken: </td>
                    <td><?php echo $row_TStudents['ContactsInterests']; ?></td>
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
                    <td width="322" align="left"><?php echo $row_TStudents['BillingAddress'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">City:</td>
                    <td align="left"><?php echo $row_TStudents['City'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Country:</td>
                    <td align="left"><?php echo $row_TStudents['cntry'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">State:</td>
                    <td align="left"><?php echo $row_TStudents['StateOrProvince'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="bluetxt">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Home Phone:</td>
                    <td><?php echo $row_TStudents['homephone'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Mobile Phone:</td>
                    <td><?php echo $row_TStudents['MobilePhone'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Email:</td>
                    <td align="left"><?php echo $row_TStudents['EmailAddress'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Emergency Contact</td>
                  </tr>
                  <tr>
                    <td class="titles">Parent:</td>
                    <td align="left"><?php echo $row_TStudents['parent'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Name:</td>
                    <td width="322" align="left"><?php echo $row_TStudents['emername'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Relationship:</td>
                    <td align="left"><script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TStudents['emertype']; ?>) {
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
                    <td align="left"><?php echo $row_TStudents['emeraddress'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Phone No.:</td>
                    <td align="left" nowrap="nowrap"><?php echo $row_TStudents['emerphone'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="bluetxt">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Spouse:</td>
                    <td><?php echo $row_TStudents['spousename'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Children:</td>
                    <td><?php echo $row_TStudents['ChildrenNames'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Spouse Phone No.:</td>
                    <td align="left"><?php echo $row_TStudents['PhoneNumber'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1"><span class="TabbedPanelsTab">Academic</span> Info</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Program: </td>
                    <td width="322" align="left"><?php echo $row_TStudents['prog'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Class: </td>
                    <td align="left"><?php echo $row_TStudents['stud_class'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">In Hostel:</td>
                    <td align="left"><input name="credit" type="checkbox" id="credit"  <?php if (!(strcmp($row_TStudents['credit'],""))) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Date Admitted:</td>
                    <td><script type="text/javascript">
document.write(formatdate('<?php echo $row_TStudents['datehired'] ?>'));
	                            </script></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Concluded Program:</td>
                    <td><table border="0" cellspacing="2" cellpadding="0">
                      <tr>
                        <td><input name="InUse" type="checkbox" disabled="disabled" id="InUse" value=""  <?php echo $row_TStudents['InUse']==1? "checked=\"checked\"": ""; ?> /></td>
                        <td><table border="0" cellpadding="2" cellspacing="0" id="fired" style="display:<?php echo $row_TStudents['InUse']==1 ? 'block':'none'; ?>">
                          <tr>
                            <td class="blacktxt">Date:</td>
                            <td><script type="text/javascript">
document.write(formatdate('<?php echo $row_TStudents['datefired'] ?>'));
	                                        </script></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Status: </td>
                    <td align="left"><?php echo $row_TStudents['cat'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Suspended:</td>
                    <td align="left"><input name="leavstatus" type="checkbox" id="leavstatus" value=""  <?php if (!(strcmp($row_TStudents['leavstatus'],""))) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="2" cellspacing="2">
                  <tr>
                    <td class="h1">Notes</td>
                  </tr>
                  <tr>
                    <td><?php echo $row_TStudents['Notes']; ?></td>
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
	print();
});
</script>
</body>
</html>