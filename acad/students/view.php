<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Students'));
vetAccess('Academics', 'Students', 'View');

$cls = isset($_GET['cls']) ? '?'.$_SERVER['QUERY_STRING'] : '';
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 1);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "add.php$cls", "edit.php$cls", "", "[Student]del.php$cls", "", "", "find.php$cls", "return GB_showCenter('Student\'s File', 'print.php?id=$id', 600,600)", "index.php$cls");
$rec_status = 1;

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
<script charset="UTF-8" src="menu.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/students.jpg" width="240" height="300" />
          <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblstudents.png); background-repeat:no-repeat">&nbsp;</td>
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
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
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
                <td><div id="Details" class="TabbedPanels">
                    <ul class="TabbedPanelsTabGroup">
                      <li class="TabbedPanelsTab" tabindex="0">Personal Info</li>
                      <li class="TabbedPanelsTab" tabindex="0">Contact Details</li>
                      <li class="TabbedPanelsTab" tabindex="0">Emergency Contact</li>
                      <li class="TabbedPanelsTab" tabindex="0">Academic Info</li>
                      <li class="TabbedPanelsTab" tabindex="0">Notes</li>
                    </ul>
                    <div class="TabbedPanelsContentGroup">
                      <div class="TabbedPanelsContent">
                        <table border="0" cellspacing="4" cellpadding="4">
                          <tr>
                            <td class="titles">Gender:</td>
                            <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
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
                            <td><textarea name="ContactsInterests" readonly="readonly"><?php echo $row_TStudents['ContactsInterests']; ?></textarea></td>
                          </tr>
                          <tr>
                            <td width="120" valign="top" class="titles">Permanent Home Address:</td>
                            <td width="322" align="left"><textarea name="PostalCode" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TStudents['PostalCode'] ?></textarea></td>
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
                            <td width="322" align="left"><textarea name="BillingAddress" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TStudents['BillingAddress'] ?></textarea></td>
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
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <table border="0" cellspacing="4" cellpadding="4">
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
                            <td align="left"><textarea name="emeraddress" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TStudents['emeraddress'] ?></textarea></td>
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
                            <td><textarea name="ChildrenNames" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TStudents['ChildrenNames'] ?></textarea></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">Spouse Phone No.:</td>
                            <td align="left"><?php echo $row_TStudents['PhoneNumber'] ?></td>
                          </tr>
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <table border="0" cellspacing="4" cellpadding="4">
                          <tr>
                            <td width="120" class="titles">Program:                              </td>
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
                                  <td><input type="checkbox" name="InUse" value=""  <?php echo $row_TStudents['InUse']==1? "checked=\"checked\"": ""; ?> disabled="disabled" /></td>
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
                          <tr>
                            <td class="titles">&nbsp;</td>
                            <td align="left">&nbsp;</td>
                          </tr>
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <textarea name="Notes" rows="10" readonly="readonly" style="width:450px"><?php echo $row_TStudents['Notes']; ?></textarea>
                      </div>
                    </div>
                  </div></td>
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
<script type="text/javascript">
<!--
var Tabs = new Spry.Widget.TabbedPanels("Details");
//-->
</script>
</body>
</html>