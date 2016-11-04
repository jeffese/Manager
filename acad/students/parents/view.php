<?php
require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Parents'));
vetAccess('Academics', 'Parents', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 1);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Student]del.php?id=$id","","","find.php","print.php?id=$id","index.php");
$rec_status = 1;

$sql = "SELECT `vendors`.*, country.country AS cntry, nation.country as nation
FROM `{$_SESSION['DBCoy']}`.`vendors`
LEFT JOIN `".DB_NAME."`.`country` ON `vendors`.country=country.country_id 
LEFT JOIN `".DB_NAME."`.`country` nation ON `vendors`.nationality = nation.country_id 
WHERE `vendors`.`VendorID`={$_SESSION['parent_id']}";
$row_TParent = getDBDataRow($dbh, $sql);

$sql = "SELECT vendorcode, `VendorID`, $vendor_sql, 
    CONCAT(`City`, ' ', `StateOrProvince`) AS loc, `sex`, `datehired`, 
    TIMESTAMPDIFF(YEAR, `dateofbirth`, CURDATE()) AS age, `InUse`, category_name AS dept, cat.Category AS cat, 
    `prog_name`,
    CONCAT(`class_name`, IF(arm_code='', '', ' > '), arm_code) AS stud_class
FROM `{$_SESSION['DBCoy']}`.`vendors` 
    LEFT JOIN `" . DB_NAME . "`.`country` ON `vendors`.country=country.country_id 
    LEFT JOIN `" . DB_NAME . "`.`country` nation ON `vendors`.nationality=nation.country_id  
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status` cat ON `vendors`.categoryid=cat.CategoryID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `vendors`.`DeptID`=`sch_arms`.`arm_id`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`sch_class` ON `sch_arms`.`class`=`sch_class`.`class_id`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`sch_programs` ON `sch_class`.`program`=`sch_programs`.`prog_id`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` dept ON `sch_programs`.`department`=dept.`catID`
    WHERE parentcompany={$_SESSION['parent_id']}
ORDER BY `datehired`";
$TStudents = getDBData($dbh, $sql);

$currentPage = 'index.php';
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
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="../menu.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/parents.jpg" width="240" height="300" />
          <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblparents.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php')?></td>
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
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
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
                <td><div id="Details" class="TabbedPanels">
                    <ul class="TabbedPanelsTabGroup">
                      <li class="TabbedPanelsTab" tabindex="0">Personal Info</li>
                      <li class="TabbedPanelsTab" tabindex="0">Contact Details</li>
                      <li class="TabbedPanelsTab" tabindex="0">Emergency Contact</li>
                      <li class="TabbedPanelsTab" tabindex="0">Work Info</li>
                      <li class="TabbedPanelsTab" tabindex="0">Students</li>
                      <li class="TabbedPanelsTab" tabindex="0">Notes</li>
</ul>
                    <div class="TabbedPanelsContentGroup">
                      <div class="TabbedPanelsContent">
                        <table border="0" cellspacing="4" cellpadding="4">
                          <tr>
                            <td class="titles">Gender:</td>
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
                            <td><textarea name="ContactsInterests" readonly="readonly"><?php echo $row_TParent['ContactsInterests']; ?></textarea></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">&nbsp;</td>
                            <td align="left">&nbsp;</td>
                          </tr>
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <table border="0" cellspacing="4" cellpadding="4">
                          <tr>
                            <td width="120" valign="top" class="titles">Address:</td>
                            <td width="322" align="left"><textarea name="BillingAddress" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TParent['BillingAddress'] ?></textarea></td>
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
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <table border="0" cellspacing="4" cellpadding="4">
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
                            <td align="left"><textarea name="emeraddress" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TParent['emeraddress'] ?></textarea></td>
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
                            <td><textarea name="ChildrenNames" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TParent['ChildrenNames'] ?></textarea></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">Spouse Phone No.:</td>
                            <td align="left"><?php echo $row_TParent['PhoneNumber'] ?></td>
                          </tr>
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <table border="0" cellspacing="4" cellpadding="4">
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
                            <td align="left"><textarea name="fingerprint" rows="3" readonly="readonly" id="fingerprint" style="width:300px"><?php echo $row_TParent['fingerprint'] ?></textarea></td>
                          </tr>
                          <tr>
                            <td class="titles">&nbsp;</td>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">Direct Line.:</td>
                            <td align="left"><?php echo $row_TParent['workphone'] ?></td>
                          </tr>
                          <tr>
                            <td nowrap="nowrap" class="titles">Extension:</td>
                            <td><?php echo $row_TParent['Extension'] ?></td>
                          </tr>
                          <tr>
                            <td nowrap="nowrap" class="titles">&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <table width="100%" cellpadding="0" cellspacing="0">
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
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onclick="top.leftFrame.showMod('Students', '/acad/students/view.php?id=<?php echo $row_TStudents['VendorID']; ?>')">
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
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <textarea name="Notes" rows="10" readonly="readonly" style="width:450px"><?php echo $row_TParent['Notes']; ?></textarea>
                      </div>
</div>
                  </div></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php'); ?></td>
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
<script type="text/javascript">
<!--
var Tabs = new Spry.Widget.TabbedPanels("Details");
//-->
</script>
</body>
</html>