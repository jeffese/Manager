<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Students'));
vetAccess('Academics', 'Students', 'Edit');

$cls = isset($_GET['cls']) ? '?'.$_SERVER['QUERY_STRING'] : '';

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmstud","","view.php$cls","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS().$cls;

if (_xpost("MM_update") == "frmstud") {
  
	$pix = newpix(ROOT . STUDPIX_DIR, '', $_SESSION['stud_id'], 20, array(600, 200));
	
	$sql = sprintf("UPDATE `%s`.`vendors` SET vendorcode=%s, InUse=%s, ContactTitle=%s, ContactFirstName=%s, ContactMidName=%s, ContactLastName=%s, BillingAddress=%s, City=%s, StateOrProvince=%s, Country=%s, PostalCode=%s, PhoneNumber=%s, MobilePhone=%s, Extension=%s, EmailAddress=%s, Notes=%s, ReferredBy=%s, ContactsInterests=%s, ChildrenNames=%s, categoryid=%s, DeptID=%s, passportno=%s, dateofbirth=%s, religion=%s, sex=%s, marital_status=%s, picturefile=%s, spousename=%s, ability=%s, workphone=%s, nationality=%s, stateorigin=%s, locgovorigin=%s, nativetongue=%s, datehired=%s, datefired=%s, supervisor=%s, homephone=%s, leavstatus=%s, emertype=%s, emername=%s, emerphone=%s, emeraddress=%s, Discount=%s, `currency`=%s, salary=%s, parentcompany=%s, FaxNumber=%s, logofile=%s, credit=%s WHERE VendorID=%s",
					   $_SESSION['DBCoy'],
                       GSQLStr(_xpost('vendorcode'), "text"),
                       _xpostchk('InUse'),
                       GSQLStr(_xpost('ContactTitle'), "text"),
                       GSQLStr(_xpost('ContactFirstName'), "text"),
                       GSQLStr(_xpost('ContactMidName'), "text"),
                       GSQLStr(_xpost('ContactLastName'), "text"),
                       GSQLStr(_xpost('BillingAddress'), "text"),
                       GSQLStr(_xpost('City'), "text"),
                       GSQLStr(_xpost('StateOrProvince'), "text"),
                       GSQLStr(_xpost('Country'), "int"),
                       GSQLStr(_xpost('PostalCode'), "text"),
                       GSQLStr(_xpost('PhoneNumber'), "text"),
                       GSQLStr(_xpost('MobilePhone'), "text"),
                       GSQLStr(_xpost('Extension'), "text"),
                       GSQLStr(_xpost('EmailAddress'), "text"),
                       GSQLStr(_xpost('Notes'), "text"),
                       GSQLStr(_xpost('ReferredBy'), "text"),
                       GSQLStr(_xpost('ContactsInterests'), "text"),
                       GSQLStr(_xpost('ChildrenNames'), "text"),
                       GSQLStr(_xpost('categoryid'), "int"),
                       GSQLStr(_xpost('DeptID'), "int"),
                       GSQLStr(_xpost('passportno'), "text"),
                       GSQLStr(_xpost('dateofbirth'), "date"),
                       GSQLStr(_xpost('religion'), "int"),
                       GSQLStr(_xpost('sex'), "int"),
                       GSQLStr(_xpost('marital_status'), "int"),
                       $pix['pixcode'],
                       GSQLStr(_xpost('spousename'), "text"),
                       _xpostchk('ability'),
                       GSQLStr(_xpost('workphone'), "text"),
                       GSQLStr(_xpost('nationality'), "int"),
                       GSQLStr(_xpost('stateorigin'), "text"),
                       GSQLStr(_xpost('locgovorigin'), "text"),
                       GSQLStr(_xpost('nativetongue'), "text"),
                       GSQLStr(_xpost('datehired'), "date"),
                       GSQLStr(_xpost('datefired'), "date"),
                       GSQLStr(_xpost('supervisor'), "int"),
                       GSQLStr(_xpost('homephone'), "text"),
                       _xpostchk('leavstatus'),
                       GSQLStr(_xpost('emertype'), "int"),
                       GSQLStr(_xpost('emername'), "text"),
                       GSQLStr(_xpost('emerphone'), "text"),
                       GSQLStr(_xpost('emeraddress'), "text"),
                       GSQLStr(_xpost('Discount'), "int"),
                       GSQLStr(_xpost('currency'), "int"),
                       GSQLStr(_xpost('salary'), "int"),
                       GSQLStr(_xpost('parentcompany'), "int"),
                       GSQLStr(_xpost('FaxNumber'), "text"),
                       GSQLStr(_xpost('logofile'), "text"),
                       _xpostchk('credit'),
                       $_SESSION['stud_id']);
	$update = runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT `vendors`.*, CONCAT(catname, ' > ', `prog_name`) AS prog,
    CONCAT(`class_name`, IF(arm_code='', '', ' > '), arm_code) AS stud_class
    FROM `{$_SESSION['DBCoy']}`.`vendors` 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `vendors`.`DeptID`=`sch_arms`.`arm_id`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`sch_class` ON `sch_arms`.`class`=`sch_class`.`class_id`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`sch_programs` ON `sch_class`.`program`=`sch_programs`.`prog_id`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` dept ON `sch_programs`.`department`=dept.`catID`
    WHERE `VendorID`={$_SESSION['stud_id']}";
$row_TStudents = getDBDataRow($dbh, $sql);

$sql = "SELECT country_id, country FROM `".DB_NAME."`.`country` ORDER BY country";
$Tcountry = getDBData($dbh, $sql);

$sql = "SELECT `state` FROM `".DB_NAME."`.`state` ORDER BY `state`";
$Tstate = getDBData($dbh, $sql);

$TCat = getCat('stud_status');

$sql = "SELECT `VendorID`, $vendor_sql
FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorType=6 ORDER BY VendorName";
$TGuard = getDBData($dbh, $sql);

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
<script language="JavaScript1.2" type="text/javascript">
var arrFormValidation=
[
["ContactFirstName", "", 
["req", "Enter First Name"]],
["ContactLastName", "", 
["req", "Enter ContactLastName Name"]]
]
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="240" valign="top"><img src="/images/students.jpg" width="240" height="300" /></td>
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmstud" id="frmstud">
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
?>
  <?php include('../../scripts/editpix.php'); ?>&nbsp;</td>
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
                    <td align="left"><input type="text" name="vendorcode" value="<?php echo $row_TStudents['vendorcode'] ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Title:</td>
                    <td><input type="text" name="ContactTitle" value="<?php echo $row_TStudents['ContactTitle'] ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">First Name:</td>
                    <td><input type="text" name="ContactFirstName" value="<?php echo $row_TStudents['ContactFirstName'] ?>" size="32" /></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Middle Name:</td>
                    <td><input type="text" name="ContactMidName" value="<?php echo $row_TStudents['ContactMidName'] ?>" size="32" /></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Last Name:</td>
                    <td><input type="text" name="ContactLastName" value="<?php echo $row_TStudents['ContactLastName'] ?>" size="32" /></td>
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
                              <td><input type="radio" name="sex" value="1" size="32" <?php if (!(strcmp($row_TStudents['sex'], 1))) { echo "checked=\"checked\""; } ?> /></td>
                              <td>Male</td>
                              <td><input type="radio" name="sex" value="2" size="32" <?php if (!(strcmp($row_TStudents['sex'], 2))) { echo "checked=\"checked\""; } ?> /></td>
                              <td>Female</td>
                              </tr>
                            </table></td>
                          </tr>
                        <tr>
                          <td class="titles">Disability:</td>
                          <td><input type="checkbox" name="ability" id="ability" <?php if (!(strcmp($row_TStudents['ability'], 1))) { echo "checked=\"checked\""; } ?> /></td>
                          </tr>
                        <tr>
                          <td class="titles">Marital Status </td>
                          <td><span class="orangetext">
                            <select name="marital_status" width="175" style="width: 168px;">
                              <option value="0" selected="selected" <?php
                                                                        if (!(strcmp(0, $row_TStudents['marital_status']))) {
                                                                            echo "selected=\"selected\"";
                                                                        } ?>>Select One </option>
                              <option value="1" <?php
                                                                                if (!(strcmp(1, $row_TStudents['marital_status']))) {
                                                                                    echo "selected=\"selected\"";
                                                                                } ?>>Single - never married</option>
                              <option value="2" <?php
                                                                                        if (!(strcmp(2, $row_TStudents['marital_status']))) {
                                                                                            echo "selected=\"selected\"";
                                                                                        }
                                                                        ?>>Married</option>
                              <option value="3" <?php
                                                                                        if (!(strcmp(3, $row_TStudents['marital_status']))) {
                                                                                            echo "selected=\"selected\"";
                                                                                        }
                                                                        ?>>Divorced </option>
                              <option value="4" <?php
                                                                                        if (!(strcmp(4, $row_TStudents['marital_status']))) {
                                                                                            echo "selected=\"selected\"";
                                                                                        }
                                                                        ?>>Widowed </option>
                              <option value="5" <?php
                                                                                        if (!(strcmp(5, $row_TStudents['marital_status']))) {
                                                                                            echo "selected=\"selected\"";
                                                                                        }
                                                                        ?>>Separated </option>
                              </select>
                            </span></td>
                          </tr>
                        <tr>
                          <td class="titles">Religion:</td>
                          <td><select name="religion">
                            <option value="0"<?php echo $row_TStudents['religion']==0 ? ' selected=\"selected\"' : ''; ?>>Select One </option>
                            <option value="1"<?php echo $row_TStudents['religion']==1 ? ' selected=\"selected\"' : ''; ?>>Christian</option>
                            <option value="2"<?php echo $row_TStudents['religion']==2 ? ' selected=\"selected\"' : ''; ?>>Muslim</option>
                            <option value="3"<?php echo $row_TStudents['religion']==3 ? ' selected=\"selected\"' : ''; ?>>Jewish</option>
                            <option value="4"<?php echo $row_TStudents['religion']==4 ? ' selected=\"selected\"' : ''; ?>>Budhist</option>
                            <option value="5"<?php echo $row_TStudents['religion']==5 ? ' selected=\"selected\"' : ''; ?>>Atheist</option>
                            <option value="6"<?php echo $row_TStudents['religion']==6 ? ' selected=\"selected\"' : ''; ?>>Others</option>
                            </select></td>
                          </tr>
                        <tr>
                          <td class="titles">Date of Birth:</td>
                          <td><input name="dateofbirth" type="text" id="dateofbirth" value="<?php echo $row_TStudents['dateofbirth'] ?>" size="12" readonly="readonly" /></td>
                          </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td>&nbsp;</td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Passport No.:</td>
                          <td><input type="text" name="passportno" value="<?php echo $row_TStudents['passportno'] ?>" size="32" /></td>
                          </tr>
                        <tr>
                          <td class="titles">Nationality: </td>
                          <td><select name="nationality" onchange="if (this.value==154){ this.form.cmbsto.style.display='block'; this.form.stateorigin.style.display='none';} else { this.form.stateorigin.style.display='block'; this.form.cmbsto.style.display='none';};">
                            <option value="">Select Nationality</option>
                            <?php foreach ($Tcountry as $row_Tcountry) { ?>
                            <option value="<?php echo $row_Tcountry['country_id'] ?>" <?php if (!(strcmp($row_TStudents['nationality'], $row_Tcountry['country_id']))) { echo "selected=\"selected\""; }?>><?php echo $row_Tcountry['country'] ?></option>
                            <?php } ?>
                            </select></td>
                          </tr>
                        <tr>
                          <td class="titles">State Of Origin:</td>
                          <td><select name="cmbsto" onchange="this.form.stateorigin.value=this.options[this.selectedIndex].text" style="display: <?php echo ($row_TStudents['nationality'] == 154) ? 'block' : 'none' ?>">
                            <option value="">Select State</option>
                            <?php foreach ($Tstate as $row_Tstate) { ?>
                            <option value="<?php echo $row_Tstate['state'] ?>" <?php
                                                                                            if (!(strcmp($row_TStudents['stateorigin'], $row_Tstate['state']))) {
                                                                                                echo "selected=\"selected\"";
                                                                                            }
?>><?php echo $row_Tstate['state'] ?></option>
                            <?php } ?>
                            </select>
                            <input name="stateorigin" type="text" value="<?php echo $row_TStudents['stateorigin'] ?>" size="25" style="display: <?php echo ($row_TStudents['nationality'] != 154) ? 'block' : 'none' ?>" /></td>
                          </tr>
                        <tr>
                          <td class="titles">Home Town:</td>
                          <td><input type="text" name="locgovorigin" value="<?php echo $row_TStudents['locgovorigin'] ?>" size="32" /></td>
                          </tr>
                        <tr>
                          <td class="titles">Native Tongue:</td>
                          <td><input name="nativetongue" type="text" value="<?php echo $row_TStudents['nativetongue'] ?>" size="32" /></td>
                          </tr>
                        <tr>
                          <td class="titles">Languages Spoken: </td>
                          <td><textarea name="ContactsInterests"><?php echo $row_TStudents['ContactsInterests']; ?></textarea></td>
                          </tr>
                        <tr>
                          <td width="120" valign="top" class="titles">Permanent Home Address:</td>
                          <td width="322" align="left"><textarea name="PostalCode" style="width:300px" rows="3"><?php echo $row_TStudents['PostalCode'] ?></textarea></td>
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
                          <td width="322" align="left"><textarea name="BillingAddress" style="width:300px" rows="3"><?php echo $row_TStudents['BillingAddress'] ?></textarea></td>
                          </tr>
                        <tr>
                          <td width="120" class="titles">City:</td>
                          <td align="left"><input type="text" name="City" value="<?php echo $row_TStudents['City'] ?>" size="32" /></td>
                          </tr>
                        <tr>
                          <td width="120" class="titles">Country:</td>
                          <td align="left"><select name="Country" id="Country" onchange="if (this.value==154){this.form.cmbsta.style.display='block'; this.form.StateOrProvince.style.display='none';} else {this.form.cmbsta.style.display='none'; this.form.StateOrProvince.style.display='block'; }">
                            <option value="">Select Country</option>
                            <?php foreach ($Tcountry as $row_Tcountry) { ?>
                            <option value="<?php echo $row_Tcountry['country_id']?>" <?php if (!(strcmp($row_TStudents['Country'],$row_Tcountry['country_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tcountry['country']?></option>
                            <?php } ?>
                            </select></td>
                          </tr>
                        <tr>
                          <td width="120" class="titles">State:</td>
                          <td align="left" nowrap="nowrap"><select name="cmbsta" onchange="this.form.StateOrProvince.value=this.value" style="display: <?php echo ($row_TStudents['Country']==154)? 'block': 'none' ?>">
                            <option value="">Select State</option>
                            <?php foreach ($Tstate as $row_Tstate) { ?>
                            <option value="<?php echo $row_Tstate['state']?>"<?php if (!(strcmp($row_TStudents['StateOrProvince'],$row_Tstate['state']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tstate['state']?></option>
                            <?php } ?>
                            </select>
                            <input name="StateOrProvince" type="text" size="25" value="<?php echo $row_TStudents['StateOrProvince'] ?>" style="display: <?php echo ($row_TStudents['Country']!=154)? 'block': 'none' ?>" /></td>
                          </tr>
                        <tr>
                          <td width="120" class="bluetxt">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Home Phone:</td>
                          <td><input type="text" name="homephone" value="<?php echo $row_TStudents['homephone'] ?>" size="32" /></td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Mobile Phone:</td>
                          <td><input type="text" name="MobilePhone" value="<?php echo $row_TStudents['MobilePhone'] ?>" size="32" /></td>
                          </tr>
                        <tr>
                          <td width="120" class="titles">Email:</td>
                          <td align="left"><input type="text" name="EmailAddress" value="<?php echo $row_TStudents['EmailAddress'] ?>" size="32" /></td>
                          </tr>
                        </table>
                      </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td class="titles">Parent:</td>
                          <td align="left"><select name="parentcompany" id="parentcompany">
                            <option value="0">Select One </option>
                            <?php foreach ($TGuard as $row_TGuard) { ?>
                            <option value="<?php echo $row_TGuard['VendorID'] ?>" <?php if (!(strcmp($row_TStudents['parentcompany'], $row_TGuard['VendorID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TGuard['VendorName'] ?></option>
                            <?php } ?>
                            </select></td>
                          </tr>
                        <tr>
                          <td width="120" class="titles">Name:</td>
                          <td width="322" align="left"><input type="text" name="emername" value="<?php echo $row_TStudents['emername'] ?>" size="32" /></td>
                          </tr>
                        <tr>
                          <td width="120" class="titles">Relationship:</td>
                          <td align="left"><select name="emertype">
                            <option value="0"></option>
                            <option value="1">Brother</option>
                            <option value="2">Sister</option>
                            <option value="3">Husband</option>
                            <option value="4">Wife</option>
                            <option value="5">Father</option>
                            <option value="6">Mother</option>
                            <option value="7">Child</option>
                            <option value="8">In-Law</option>
                            <option value="9">Friend</option>
                            <option value="10">Cousin</option>
                            <option value="11">Uncle</option>
                            <option value="12">Aunty</option>
                            <option value="13">Others</option>
                            <?php echo $row_TStudents['emertype'] ?>
                            </select></td>
                          </tr>
                        <tr>
                          <td width="120" class="titles">Address:</td>
                          <td align="left"><textarea name="emeraddress" style="width:300px" rows="3"><?php echo $row_TStudents['emeraddress'] ?></textarea></td>
                          </tr>
                        <tr>
                          <td width="120" class="titles">Phone No.:</td>
                          <td align="left" nowrap="nowrap"><input type="text" name="emerphone" value="<?php echo $row_TStudents['emerphone'] ?>" size="32" /></td>
                          </tr>
                        <tr>
                          <td width="120" class="bluetxt">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Spouse:</td>
                          <td><input type="text" name="spousename" value="<?php echo $row_TStudents['spousename'] ?>" size="32" /></td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Children:</td>
                          <td><textarea name="ChildrenNames" style="width:300px" rows="3"><?php echo $row_TStudents['ChildrenNames'] ?></textarea></td>
                          </tr>
                        <tr>
                          <td width="120" class="titles">Spouse Phone No.:</td>
                          <td align="left"><input type="text" name="PhoneNumber" value="<?php echo $row_TStudents['PhoneNumber'] ?>" size="32" /></td>
                          </tr>
                        </table>
                      </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" class="titles">Program:
                            <input name="DeptID" type="hidden" id="DeptID" value="<?php echo $row_TStudents['DeptID']; ?>" /></td>
                          <td width="322" align="left" id="prog_txt"><?php echo $row_TStudents['prog'] ?></td>
                          </tr>
                        <tr>
                          <td class="titles">Class: </td>
                          <td align="left" id="class_txt"><?php echo $row_TStudents['stud_class'] ?></td>
                          </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="center"><input type="button" name="btprog" id="btprog" value="Select" onclick="return GB_showCenter('Program &amp; Class', '/acad/students/progpick.php', 480,520)" />
                            <input type="button" name="bt_prog" id="bt_prog" value="Clear" onclick="				this.form.DeptID.value= '';				document.getElementById('prog_txt').innerHTML='';				document.getElementById('class_txt').innerHTML=''" /></td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">&nbsp;</td>
                          <td>&nbsp;</td>
                          </tr>
                        <tr>
                          <td class="titles">In Hostel:</td>
                          <td align="left"><input name="credit" type="checkbox" id="credit"  <?php if (!(strcmp($row_TStudents['credit'],""))) {echo "checked=\"checked\"";} ?> /></td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Date Admitted:</td>
                          <td><input type="text" name="datehired" id="datehired" value="<?php echo $row_TStudents['datehired'] ?>" size="12" /></td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Concluded Program:</td>
                          <td><table border="0" cellspacing="2" cellpadding="0">
                            <tr>
                              <td><input type="checkbox" name="InUse" value=""  <?php if (!(strcmp($row_TStudents['InUse'],""))) {echo "checked=\"checked\"";} ?> onclick="if (this.checked) {document.getElementById('fired').style.display='block';} else {document.getElementById('fired').style.display='none';}" /></td>
                              <td><table border="0" cellpadding="2" cellspacing="0" id="fired" style="display:<?php echo $row_TStudents['InUse']==1 ? 'block':'none'; ?>">
                                <tr>
                                  <td class="blacktxt">Date:</td>
                                  <td><input type="text" name="datefired" id="datefired" value="<?php echo $row_TStudents['datefired'] ?>" size="12" /></td>
                                  </tr>
                                </table></td>
                              </tr>
                            </table></td>
                          </tr>
                        <tr>
                          <td class="titles">Status:</td>
                          <td align="left"><select name="categoryid">
                            <?php foreach ($TCat as $row_TCat) { ?>
                            <option value="<?php echo $row_TCat['CategoryID'] ?>" <?php if (!(strcmp($row_TStudents['categoryid'], $row_TCat['CategoryID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TCat['Category'] ?></option>
                            <?php } ?>
                            </select>
                            <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/acad/students/cat/index.php', 480,520)" /></td>
                          </tr>
                        <tr>
                          <td class="titles">Suspended:</td>
                          <td align="left"><input name="leavstatus" type="checkbox" id="leavstatus" value=""  <?php if (!(strcmp($row_TStudents['leavstatus'],""))) {echo "checked=\"checked\"";} ?> /></td>
                          </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                          </tr>
                        </table>
                      </div>
  <div class="TabbedPanelsContent">
    <textarea name="Notes" style="width:450px" rows="10"><?php echo $row_TStudents['Notes'] ?></textarea>
    </div>
                    </div>
                  </div></td>
</tr>
              <tr>
                <td>
            <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
            <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
            <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
            <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
            <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
            <script type="text/javascript">
var mCal;
window.onload = function() {
    mCal = new dhtmlxCalendarObject('dateofbirth', true, {isYearEditable: true, isMonthEditable: true});
	mCal.setSkin('dhx_black');
    mCal2 = new dhtmlxCalendarObject('datehired', true, {isYearEditable: true, isMonthEditable: true});
	mCal2.setSkin('dhx_black');
    mCal3 = new dhtmlxCalendarObject('datefired', true, {isYearEditable: true, isMonthEditable: true});
	mCal3.setSkin('dhx_black');
}
      </script></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmstud" />
            <input type="hidden" name="VendorID" value="<?php echo $row_TStudents['VendorID']; ?>" />
          </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>            </tr>
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