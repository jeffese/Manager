<?php
require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Parents'));
vetAccess('Academics', 'Parents', 'print');

$id = _xget('id');
$sql = "SELECT `vendors`.*, country.country AS cntry, nation.country as nation
FROM `{$_SESSION['DBCoy']}`.`vendors`
LEFT JOIN `".DB_NAME."`.`country` ON `vendors`.country=country.country_id 
LEFT JOIN `".DB_NAME."`.`country` nation ON `vendors`.nationality = nation.country_id
WHERE `vendors`.`VendorID`={$_SESSION['parent_id']}";
$row_TParent = getDBDataRow($dbh, $sql);

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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblparents.png); background-repeat:no-repeat">&nbsp;</td>
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
$pictfld = $row_TParent['picturefile'];
$fpath = $_SESSION['parent_id'];
$pixdir = PARENT_PIX_DIR;
$pixi = 'x';
$xid = '';
$label = '';
?>
                  <?php include('../../../scripts/viewpix.php'); ?>
                  &nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                    <tr>
                      <td class="titles">ID:</td>
                      <td class="red-normal"><b><?php echo $row_TParent['VendorID']; ?></b></td>
                    </tr>
                    <tr>
                      <td class="titles">Title:</td>
                      <td><?php echo $row_TParent['ContactTitle'] ?></td>
                    </tr>
                    <tr>
                      <td class="titles">First Name:</td>
                      <td><?php echo $row_TParent['ContactFirstName'] ?></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles">Middle Name:</td>
                      <td><?php echo $row_TParent['ContactMidName'] ?></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles">Last Name:</td>
                      <td><?php echo $row_TParent['ContactLastName'] ?></td>
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
                        <td><input type="radio" name="sex" value="1" size="32" <?php if (!(strcmp($row_TParent['sex'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                        <td>Male</td>
                        <td><input type="radio" name="sex" value="2" size="32" <?php if (!(strcmp($row_TParent['sex'], 2))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                        <td>Female</td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Disability:</td>
                    <td><label>
                      <input type="checkbox" name="ability" id="ability" <?php if (!(strcmp($row_TParent['ability'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" />
                    </label></td>
                  </tr>
                  <tr>
                    <td class="titles">Marital Status </td>
                    <td><span class="orangetext">
                      <script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TParent['marital_status']; ?>) {
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
switch (<?php echo $row_TParent['religion']; ?>) {
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
document.write(formatdate('<?php echo $row_TParent['dateofbirth'] ?>'));
	                            </script></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Nationality: </td>
                    <td><?php echo $row_TParent['nation'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">State Of Origin:</td>
                    <td><?php echo $row_TParent['stateorigin'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Home Town:</td>
                    <td><?php echo $row_TParent['locgovorigin'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Native Tongue:</td>
                    <td><?php echo $row_TParent['nativetongue'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Languages Spoken: </td>
                    <td><?php echo $row_TParent['ContactsInterests']; ?></td>
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
                    <td width="322" align="left"><?php echo $row_TParent['BillingAddress'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">City:</td>
                    <td align="left"><?php echo $row_TParent['City'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Country:</td>
                    <td align="left"><?php echo $row_TParent['cntry'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">State:</td>
                    <td align="left"><?php echo $row_TParent['StateOrProvince'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="bluetxt">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Home Phone:</td>
                    <td><?php echo $row_TParent['homephone'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Mobile Phone:</td>
                    <td><?php echo $row_TParent['MobilePhone'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Email:</td>
                    <td align="left"><?php echo $row_TParent['EmailAddress'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Emergency Contact</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Name:</td>
                    <td width="322" align="left"><?php echo $row_TParent['emername'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Relationship:</td>
                    <td align="left"><script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TParent['emertype']; ?>) {
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
                    <td align="left"><?php echo $row_TParent['emeraddress'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Phone No.:</td>
                    <td align="left" nowrap="nowrap"><?php echo $row_TParent['emerphone'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="bluetxt">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Spouse:</td>
                    <td><?php echo $row_TParent['spousename'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Children:</td>
                    <td><?php echo $row_TParent['ChildrenNames'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Spouse Phone No.:</td>
                    <td align="left"><?php echo $row_TParent['PhoneNumber'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Work Info</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Job Title:</td>
                    <td width="322" align="left"><?php echo $row_TParent['ReferredBy'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Location:</td>
                    <td align="left"><?php echo $row_TParent['FaxNumber'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Address:</td>
                    <td align="left"><?php echo $row_TParent['fingerprint'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Direct Line.:</td>
                    <td align="left"><?php echo $row_TParent['workphone'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Extension:</td>
                    <td><?php echo $row_TParent['Extension'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="h1">Students</td>
              </tr>
              <tr>
                <td><table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" class="boldwhite1"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                              <tr align="center" bgcolor="#666666" class="boldwhite1">
                                <td nowrap="nowrap">Student No.</td>
                                <td nowrap="nowrap">Name</td>
                                <td nowrap="nowrap">Program</td>
                                <td nowrap="nowrap">Location</td>
                                <td nowrap="nowrap">Gender</td>
                                <td nowrap="nowrap">Age</td>
                                <td nowrap="nowrap">Date Admitted</td>
                                <td nowrap="nowrap">Concluded</td>
                              </tr>
                              <?php $j=1;
	   foreach ($TStudents as $row_TStudents) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                              <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onclick="top.leftFrame.showMod('Term Info', '/acad/sessions/terms/view.php?id=<?php echo $row_TStudents['VendorID']; ?>')">
                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TStudents['vendorcode'] ?></b></td>
                                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TStudents['VendorName'] ?></b></td>
                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TStudents['prog_name'] ?></b></td>
                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TStudents['loc'] ?></b></td>
                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b>
                                  <script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TStudents['sex']; ?>) {
case 1: document.write("Male"); break;
case 2: document.write("Female"); break;
}</script>
                                </b></td>
                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TStudents['age'] ?></b></td>
                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><script type="text/javascript">
document.write(formatdate('<?php echo $row_TStudents['datehired'] ?>'));
	                                  </script></td>
                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input type="checkbox" name="InUse" value=""  <?php echo $row_TStudents['InUse']==1? "checked=\"checked\"": ""; ?> disabled="disabled" /></td>
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
                <td><table width="100%" border="0" cellpadding="2" cellspacing="2">
                  <tr>
                    <td class="h1">Notes</td>
                  </tr>
                  <tr>
                    <td><?php echo $row_TParent['Notes']; ?></td>
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