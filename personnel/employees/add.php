<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Employees'));
vetAccess('Personnel', 'Employees', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmstaff","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmstaff") {
    $sql = sprintf("INSERT INTO `%s`.`vendors` (VendorType, ClientType, vendorcode, InUse, ContactTitle, 
        ContactFirstName, ContactMidName, ContactLastName, BillingAddress, City, StateOrProvince, Country, 
        PostalCode, PhoneNumber, MobilePhone, Extension, EmailAddress, Notes, ReferredBy, ContactsInterests, 
        ChildrenNames, categoryid, DeptID, passportno, dateofbirth, religion, sex, marital_status, picturefile, 
        spousename, ability, workphone, nationality, stateorigin, locgovorigin, nativetongue, datehired, 
        datefired, supervisor, homephone, leavstatus, emertype, emername, emerphone, emeraddress, Discount, 
        `bank`, currency, cheque, parentcompany, FaxNumber, logofile, credit, salary, tax, education,
        experience) 
        VALUES (5, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 
        %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 
        %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                                       $_SESSION['DBCoy'],
                   GSQLStr(_xpost('ClientType'), "int"),
                   GSQLStr(_xpost('vendorcode'), "textn"),
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
                   0,
                   GSQLStr(_xpost('spousename'), "text"),
                   GSQLStr(_xpost('ability'), "text"),
                   GSQLStr(_xpost('workphone'), "text"),
                   GSQLStr(_xpost('nationality'), "int"),
                   GSQLStr(_xpost('stateorigin'), "text"),
                   GSQLStr(_xpost('locgovorigin'), "text"),
                   GSQLStr(_xpost('nativetongue'), "text"),
                   GSQLStr(_xpost('datehired'), "date"),
                   GSQLStr(_xpost('datefired'), "date"),
                   GSQLStr(_xpost('supervisor'), "intn"),
                   GSQLStr(_xpost('homephone'), "text"),
                   _xpostchk('leavstatus'),
                   GSQLStr(_xpost('emertype'), "int"),
                   GSQLStr(_xpost('emername'), "text"),
                   GSQLStr(_xpost('emerphone'), "text"),
                   GSQLStr(_xpost('emeraddress'), "text"),
                   GSQLStr(_xpost('Discount'), "int"),
                   GSQLStr(_xpost('bank'), "int"),
                   GSQLStr(_xpost('currency'), "int"),
                   GSQLStr(_xpost('cheque'), "int"),
                   GSQLStr(_xpost('parentcompany'), "intn"),
                   GSQLStr(_xpost('FaxNumber'), "text"),
                   GSQLStr(_xpost('logofile'), "text"),
                   _xpostchk('credit'),
                   GSQLStr(_xpost('salary'), "intn"),
                   GSQLStr(_xpost('tax'), "text"),
                   GSQLStr(_xpost('education'), "int"),
                   GSQLStr(_xpost('experience'), "int"));
    $insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
        $recid = mysqli_insert_id($dbh);
		docs('personnel'.DS.'employees', $recid);
        $pix = newpix(ROOT . STAFFPIX_DIR, '', $recid, 20, array(600, 200, 100));
    	$sign = newpix(ROOT . STAFFPIX_DIR, $recid, 'sign', 1, array(200, 100, 40), 'sign');

        if ($pix['pixcode']!='' || $sign['pixcode']!='') {
            $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET picturefile=%s, signfile=%s WHERE `VendorID` = %s",
                    $pix['pixcode'], 
                    $sign['pixcode'], 
					$recid);
            $insert = runDBQry($dbh, $sql);
        }

        header("Location: view.php?id=$recid");
        exit;
    }
}

$sql = "SELECT country_id, country FROM `".DB_NAME."`.`country` ORDER BY country";
$Tcountry = getDBData($dbh, $sql);

$sql = "SELECT `state` FROM `".DB_NAME."`.`state` ORDER BY `state`";
$Tstate = getDBData($dbh, $sql);

$TSup = getVendor(5);

$TDept = getClassify(1);
$TCat = getClassify(5);

$TProj = getCat('proj');
$TBank = getCat('bank');
$TPlan = getCat('plan');
$THosp = getCat('hosp');
$TEduc = getCat('educ');

$sql = "SELECT `salary_id`, `salary_name` FROM `{$_SESSION['DBCoy']}`.`salaryscale` ORDER BY `salary_name`";
$TSalary = getDBData($dbh, $sql);

$sql = "SELECT cur_id, currencyname FROM `{$_SESSION['DBCoy']}`.`currencies` ORDER BY cur_id";
$Tcurrency = getDBData($dbh, $sql);

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
<script type="text/javascript" src="/accounts/payslips/payees/resource.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["ContactFirstName", "if=$('#inf').click()", 
            ["req", "Enter First Name"]
        ],
        ["ContactLastName", "if=$('#inf').click()", 
            ["req", "Enter ContactLastName Name"]
        ],
        ["currency", "if=$('#acc').click()",
            ["req", "Select Currency"]
        ]
    ]

$(document).ready(function() {
    prepRes(true, '');
});
	
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmstaff" id="frmstaff">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">Info</td>
              </tr>
              <tr>
                <td><?php $pixcnt = 20; $max = 20000000; ?>
                  <?php include('../../scripts/newpix.php'); ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><div id="Details" class="TabbedPanels">
                  <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0" id="inf">Info</li>
                    <li class="TabbedPanelsTab" tabindex="0">Personal Info</li>
<li class="TabbedPanelsTab" tabindex="0">Contact Details</li>
                    <li class="TabbedPanelsTab" tabindex="0">Emergency Contact</li>
                    <li class="TabbedPanelsTab" tabindex="0">Work Info</li>
                    <li class="TabbedPanelsTab" tabindex="0" id="acc">Salary</li>
                    <li class="TabbedPanelsTab" tabindex="0">Notes</li>
                    <li class="TabbedPanelsTab" tabindex="0">Documents</li>
                  </ul>
                  <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent">
                      <table border="0" cellpadding="4" cellspacing="4">
                        <tr>
                          <td></td>
                          <td align="center"><?php echo catch_error($errors) ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Signature:</td>
                          <td align="left"><input type="file" name="sign" id="sign" /></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="titles">Staff Number:</td>
                          <td align="left"><input type="text" name="vendorcode" size="32" /></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="titles">Title:</td>
                          <td><input type="text" name="ContactTitle" size="32" /></td>
                        </tr>
                        <tr>
                          <td class="titles">First Name:</td>
                          <td><input type="text" name="ContactFirstName" size="32" /></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Middle Name:</td>
                          <td><input type="text" name="ContactMidName" size="32" /></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Last Name:</td>
                          <td><input type="text" name="ContactLastName" size="32" /></td>
                        </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td class="titles">Gender:</td>
                          <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                            <tr>
                              <td><input type="radio" name="sex" value="1" size="32" /></td>
                              <td>Male</td>
                              <td><input type="radio" name="sex" value="2" size="32" /></td>
                              <td>Female</td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td align="right" class="titles">Disability:</td>
                          <td><textarea name="ability" id="ability"></textarea></td>
                        </tr>
                        <tr>
                          <td class="titles">Marital Status </td>
                          <td><select name="marital_status" width="175" style="width: 168px;">
                            <option value="0" selected="selected">Select One </option>
                            <option value="1">Single - never married</option>
                            <option value="2">Married</option>
                            <option value="3">Divorced </option>
                            <option value="4">Widowed </option>
                            <option value="5">Separated </option>
                          </select></td>
                        </tr>
                        <tr>
                          <td class="titles">Religion:</td>
                          <td><select name="religion">
                            <option value="0">Select One </option>
                            <option value="1">Christian</option>
                            <option value="2">Muslim</option>
                            <option value="3">Jewish</option>
                            <option value="4">Budhist</option>
                            <option value="5">Atheist</option>
                            <option value="6">Others</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td class="titles">Date of Birth:</td>
                          <td><input name="dateofbirth" type="text" id="dateofbirth" value="" size="12" readonly="readonly" /></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Passport No.:</td>
                          <td><input type="text" name="passportno" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Nationality: </td>
                          <td><select name="nationality" onchange="if (this.value==154){ this.form.cmbsto.style.display='block'; this.form.stateorigin.style.display='none';} else { this.form.stateorigin.style.display='block'; this.form.cmbsto.style.display='none';};">
                            <option value="">Select Nationality</option>
                            <?php foreach ($Tcountry as $row_Tcountry) { ?>
                            <option value="<?php echo $row_Tcountry['country_id'] ?>" <?php if ($row_Tcountry['country_id']==154) {echo "selected=\"selected\"";} ?>><?php echo $row_Tcountry['country'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td class="titles">State Of Origin:</td>
                          <td><select name="cmbsto" onchange="this.form.stateorigin.value=this.options[this.selectedIndex].text" >
                            <option value="">Select State</option>
                            <?php foreach ($Tstate as $row_Tstate) { ?>
                            <option value="<?php echo $row_Tstate['state'] ?>"><?php echo $row_Tstate['state'] ?></option>
                            <?php } ?>
                          </select>
                            <input name="stateorigin" type="text" value="" size="25" style="display: none" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Home Town:</td>
                          <td><input type="text" name="locgovorigin" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Native Tongue:</td>
                          <td><input name="nativetongue" type="text" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Languages Spoken: </td>
                          <td><textarea name="ContactsInterests"></textarea></td>
                        </tr>
                        <tr>
                          <td width="120" valign="top" class="titles">Permanent Home Address:</td>
                          <td width="322" align="left"><textarea name="PostalCode" style="width:300px" rows="3"></textarea></td>
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
                          <td width="322" align="left"><textarea name="BillingAddress" style="width:300px" rows="3"></textarea></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">City:</td>
                          <td align="left"><input type="text" name="City" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Country:</td>
                          <td align="left"><select name="Country" id="Country" onchange="if (this.value==154){this.form.cmbsta.style.display='block'; this.form.StateOrProvince.style.display='none';} else {this.form.cmbsta.style.display='none'; this.form.StateOrProvince.style.display='block'; }">
                              <option value="">Select Country</option>
                            <?php foreach ($Tcountry as $row_Tcountry) { ?>
                              <option value="<?php echo $row_Tcountry['country_id']?>"><?php echo $row_Tcountry['country']?></option>
                              <?php } ?>
                            </select></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">State:</td>
                          <td align="left" nowrap="nowrap"><select name="cmbsta" onchange="this.form.StateOrProvince.value=this.value">
                              <option value="">Select State</option>
                            <?php foreach ($Tstate as $row_Tstate) { ?>
                              <option value="<?php echo $row_Tstate['state']?>"><?php echo $row_Tstate['state']?></option>
                              <?php } ?>
                            </select>
                            <input name="StateOrProvince" type="text" size="25" value="" style="display: none" /></td>
                        </tr>
                        <tr>
                          <td width="120" class="bluetxt">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Home Phone:</td>
                          <td><input type="text" name="homephone" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Mobile Phone:</td>
                          <td><input type="text" name="MobilePhone" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Email:</td>
                          <td align="left"><input type="text" name="EmailAddress" value="" size="32" /></td>
                        </tr>
                        </table>
                </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" valign="top" class="titles">Name:</td>
                          <td width="322" align="left"><input type="text" name="emername" value="" size="32" /></td>
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
                          </select></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Address:</td>
                          <td align="left"><textarea name="emeraddress" style="width:300px" rows="3"></textarea></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Phone No.:</td>
                          <td align="left" nowrap="nowrap"><input type="text" name="emerphone" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td width="120" class="bluetxt">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Spouse:</td>
                          <td><input type="text" name="spousename" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Children:</td>
                          <td><textarea name="ChildrenNames" style="width:300px" rows="3"></textarea></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Spouse Phone No.:</td>
                          <td align="left"><input type="text" name="PhoneNumber" value="" size="32" /></td>
                        </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" valign="top" class="titles">Department:</td>
                          <td width="322" align="left"><select name="DeptID">
                            <option value="" selected="selected"></option>
                            <?php foreach ($TDept as $row_TDept) { ?>
                            <option value="<?php echo $row_TDept['catID'] ?>"><?php echo $row_TDept['catname'] ?></option>
                            <?php } ?>
                            </select></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Category:</td>
                          <td align="left"><select name="categoryid">
                            <option value="" selected="selected"></option>
                            <?php foreach ($TCat as $row_TCat) { ?>
                            <option value="<?php echo $row_TCat['catID'] ?>"><?php echo $row_TCat['catname'] ?></option>
                            <?php } ?>
                          </select>
                            <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/personnel/tools/cat/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="titles">Educational Level:</td>
                          <td align="left"><select name="education">
                            <option value=""></option>
                            <?php foreach ($TEduc as $row_TEduc) { ?>
                            <option value="<?php echo $row_TEduc['CategoryID'] ?>"><?php echo $row_TEduc['Category'] ?></option>
                            <?php } ?>
                          </select>
                            <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/personnel/tools/educ/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Work Experience:</td>
                          <td align="left"><input type="text" name="experience" size="8" /></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Job Title:</td>
                          <td align="left"><input type="text" name="ReferredBy" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Project:</td>
                          <td align="left" nowrap="nowrap"><select name="Discount" id="Discount">
                            <?php foreach ($TProj as $row_TProj) { ?>
                            <option value="<?php echo $row_TProj['CategoryID'] ?>"><?php echo $row_TProj['Category'] ?></option>
                            <?php } ?>
                          </select>
                            <input type="button" name="btproj" id="btproj" value="edit" onclick="return GB_showCenter('Categories', '/personnel/tools/proj/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Location:</td>
                          <td align="left"><input name="FaxNumber" type="text" id="FaxNumber" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="titles">Health Plan:</td>
                          <td align="left"><select name="cheque" id="cheque">
                            <option value="" selected="selected"></option>
                            <?php foreach ($TPlan as $row_TPlan) { ?>
                            <option value="<?php echo $row_TPlan['CategoryID'] ?>"><?php echo $row_TPlan['Category'] ?></option>
                            <?php } ?>
                            </select>
                            <input type="button" name="btplan" id="btplan" value="edit" onclick="return GB_showCenter('Categories', '/personnel/tools/plan/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Hospital:</td>
                          <td align="left"><select name="parentcompany" id="parentcompany">
                            <option value="" selected="selected"></option>
                            <?php foreach ($THosp as $row_THosp) { ?>
                            <option value="<?php echo $row_THosp['CategoryID'] ?>"><?php echo $row_THosp['Category'] ?></option>
                            <?php } ?>
                          </select>
                            <input type="button" name="bthosp" id="bthosp" value="edit" onclick="return GB_showCenter('Categories', '/personnel/tools/hosp/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Insured:</td>
                          <td align="left"><input name="credit" type="checkbox" id="credit" /></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Direct Line.:</td>
                          <td align="left"><input type="text" name="workphone" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Extension:</td>
                          <td><input type="text" name="Extension" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Supervisor:</td>
                          <td><select name="supervisor">
                            <option value="" selected="selected"></option>
                            <?php foreach ($TSup as $row_TSup) { ?>
                            <option value="<?php echo $row_TSup['VendorID'] ?>"><?php echo $row_TSup['VendorName'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Date Hired:</td>
                          <td><input type="text" name="datehired" id="datehired" value="" size="12" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Terminated:</td>
                          <td><table border="0" cellspacing="2" cellpadding="0">
                            <tr>
                              <td><input type="checkbox" name="InUse" value="" onclick="if (this.checked) {document.getElementById('fired').style.display='block';} else {document.getElementById('fired').style.display='none';}" /></td>
                              <td><table border="0" cellpadding="2" cellspacing="0" id="fired" style="display:none">
                                <tr>
                                  <td class="blacktxt">Date:</td>
                                  <td><input type="text" name="datefired" id="datefired" value="" size="12" /></td>
                                </tr>
                              </table></td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td class="titles">On Leave:</td>
                          <td align="left"><input name="leavstatus" type="checkbox" id="leavstatus" value=""  /></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td nowrap="nowrap" class="titles">Currency:<img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
                          <td align="left"><select name="currency">
                            <option value=""></option>
                            <?php for ($i=0; $i<count($Tcurrency); $i++) { ?>
                            <option value="<?php echo $Tcurrency[$i]['cur_id']?>" <?php if (!(strcmp($_SESSION['COY']['currency'],$Tcurrency[$i]['cur_id']))) {echo "selected=\"selected\"";} ?>><?php echo $Tcurrency[$i]['currencyname']?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Bank:</td>
                          <td width="322" align="left"><select name="bank" id="bank">
                            <option value="" selected="selected"></option>
                            <?php foreach ($TBank as $row_TBank) { ?>
                            <option value="<?php echo $row_TBank['CategoryID'] ?>"><?php echo $row_TBank['Category'] ?></option>
                            <?php } ?>
                            </select>
                            <input type="button" name="btbank" id="btbank" value="edit" onclick="return GB_showCenter('Categories', '/personnel/tools/bank/index.php', 480,520)" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Account No.:</td>
                          <td align="left"><input name="logofile" type="text" id="logofile" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Salary Package:</td>
                          <td align="left"><select name="salary" id="salary" onchange="$('#reswin').load('salary.php?id='+this.value, function (){prepRes(true)})">
                            <option value="" selected="selected"></option>
                            <?php foreach ($TSalary as $row_TSalary) { ?>
                            <option value="<?php echo $row_TSalary['salary_id'] ?>"><?php echo $row_TSalary['salary_name'] ?></option>
                            <?php } ?>
                            </select></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="left"><input type="hidden" id="tax" name="tax" />
                                            <input type="hidden" id="parts" name="parts" />
                                            <input type="hidden" id="flds" name="flds" /></td>
                        </tr>
                        <tr>
                          <td id="reswin" colspan="2">&nbsp;</td>
                        </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <textarea name="Notes" style="width:450px" rows="10"></textarea>
                    </div>
                    <div class="TabbedPanelsContent">
                      <?php include "../../scripts/newdoc.php" ?>
                    </div>
                  </div>
                </div></td>
</tr>
              <tr>
                <td><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
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
            <input type="hidden" name="MM_insert" value="frmstaff" />
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
