<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Clients'));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess('Clients', $vkey, 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmclient","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmclient") {
  
	$sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`vendors` (VendorType, amtbal, ClientType, 
            vendorcode, InUse, ContactTitle, 
            ContactFirstName, ContactMidName, ContactLastName, BillingAddress, City, StateOrProvince, 
            Country, PostalCode, PhoneNumber, MobilePhone, Extension, EmailAddress, Notes, ReferredBy, 
            ContactsInterests, ChildrenNames, categoryid, DeptID, CompanyName, dateofbirth, religion, 
            sex, marital_status, spousename, ability, workphone, nationality, stateorigin, 
            locgovorigin, nativetongue, datehired, datefired, supervisor, homephone, leavstatus, 
            emertype, emername, emerphone, emeraddress, Discount, `currency`, salary, parentcompany, 
            FaxNumber, credit, CompanyOrDepartment, signfile, passportno, staffrec, cheque, creditlimit) 
            VALUES ($vtype, '0', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 
            %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 
            %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GSQLStr(_xpost('ClientType'), "int"),
                       GSQLStr(_xpost('vendorcode'), "text"),
                       _xpostchk('InUse'),
                       GSQLStr(_xpost('ContactTitle'), "text"),
                       GSQLStr(_xpost('ContactFirstName'), "text"),
                       GSQLStr(_xpost('ContactMidName'), "text"),
                       GSQLStr(_xpost($vtype==4?'CompanyName':'ContactLastName'), "text"),
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
                       GSQLStr(_xpost('categoryid'), "intn"),
                       GSQLStr(_xpost('DeptID'), "intn"),
                       GSQLStr(_xpost('CompanyName'), "text"),
                       GSQLStr(_xpost('dateofbirth'), "date"),
                       GSQLStr(_xpost('religion'), "int"),
                       GSQLStr(_xpost('sex'), "int"),
                       GSQLStr(_xpost('marital_status'), "int"),
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
                       GSQLStr(_xpost('currency'), "int"),
                       GSQLStr(_xpost('salary'), "intn"),
                       GSQLStr(_xpost('parentcompany'), "intn"),
                       GSQLStr(_xpost('FaxNumber'), "text"),
                       _xpostchk('credit'),
                       GSQLStr(_xpost('CompanyOrDepartment'), "text"),
                       GSQLStr(_xpost('signfile'), "text"),
                       GSQLStr(_xpost('passportno'), "text"),
                       GSQLStr(_xpost('staffrec'), "text"),
                       GSQLStr(_xpost('cheque'), "int"),
                       GSQLStr(_xpost('creditlimit'), "double"));
	$insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
        $recid = mysqli_insert_id($dbh);
		docs('Clients'.DS.$vkey, $recid);
        $logo = newpix(ROOT . CLIENTPIX_DIR, '', $recid, 1, array(600, 200, 100), 'logofile', 'p', 'logo');
        $pix = newpix(ROOT . CLIENTPIX_DIR, '', $recid, 20, array(600, 200));

        $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET logofile='%s', picturefile='%s' 
                WHERE `VendorID`=%s", $logo['pixcode'], $pix['pixcode'], $recid);
        runDBQry($dbh, $sql);
        header("Location: view.php?id=$recid");
        exit;
    }
}

$sql = "SELECT country_id, country FROM `".DB_NAME."`.`country` ORDER BY country";
$Tcountry = getDBData($dbh, $sql);

$sql = "SELECT `state` FROM `".DB_NAME."`.`state` ORDER BY `state`";
$Tstate = getDBData($dbh, $sql);

$sql = "SELECT cur_id, currencyname FROM `{$_SESSION['DBCoy']}`.`currencies` ORDER BY cur_id";
$Tcurrency = getDBData($dbh, $sql);

$sql = "SELECT VendorID, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorType>0 AND VendorType<5 ORDER BY `VendorName`";
$TClients = getDBData($dbh, $sql);

$TCat = getClassify(6);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New clientee</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/clients/tmpl/script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
var arrFormValidation=[
    ["CompanyName", "if=$('#inf').click() && $('#ClientType_2').is(':checked')||<?php echo $vtype ?>==4",
        ["req", "Enter <?php echo $vtype == 4 ? 'Account' : 'Company' ?> Name"]
    ],
    ["ContactLastName", "if=$('#inf').click() && <?php echo $vtype ?>!=4 && $('#ClientType_1').is(':checked')",
        ["req", "Enter Last Name"]
    ],
    ["vendorcode", "if=$('#inf').click()",
        ["req", "Enter Client Code"]
    ],
    ["vetvendorcode", "if=$('#inf').click()",
        ["chk=vendorcode", "Please wait while we check the availability of this Code. . . ."]
    ],
    ["currency", "if=$('#acc').click()",
        ["req", "Select Currency"]
    ]
];

var mCal;
window.onload = function() {
    mCal = new dhtmlxCalendarObject('dateofbirth', true, {isYearEditable: true, isMonthEditable: true});
	mCal.setSkin('dhx_black');
    clientType(2, <?php echo $vtype ?>);
}
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
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
        <td width="240" valign="top"><span class="TabbedPanelsTabGroup"><span class="titles"><img src="/images/<?php echo $vpth?>.jpg" width="240" height="300" /></span></span></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lbl<?php echo $vpth?>.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmclient" id="frmclient">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td></td>
                        <td align="center"><?php echo catch_error($errors) ?></td>
                      </tr>
                      <tr>
                        <td class="titles">Logo:</td>
                        <td><input type="file" name="logofile" /></td>
                        </tr>
                      <tr>
                        <td class="titles">&nbsp;</td>
                        <td>&nbsp;</td>
                        </tr>
                      <tr>
                        <td colspan="2" class="titles"><?php $pixcnt = 20; $max = 20000000; ?>
                          <?php include('../../scripts/newpix.php'); ?></td>
                        </tr>
                      <tr>
                        <td colspan="2" class="titles">&nbsp;</td>
                        </tr>
                      <tr>
                        <td colspan="2" class="titles"><table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
                          <tr>
                            <td class="bl_tl"></td>
                            <td class="bl_tp"></td>
                            <td class="bl_tr"></td>
                            </tr>
                          <tr>
                            <td rowspan="2" class="bl_lf"></td>
                            <td align="left" class="bl_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td nowrap="nowrap">Documents</td>
                                <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_docs" onclick="hideshow('docs', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_docs" onclick="hideshow('docs', 0, '')" style="display:none; cursor: pointer" /></div></td>
                                </tr>
                              </table></td>
                            <td rowspan="2" class="bl_rt"></td>
                            </tr>
                          <tr>
                            <td class="bl_center"><table width="100%" border="0" cellspacing="2" cellpadding="2" id="bx_docs" style="display:none">
                              <tr>
                                <td><?php include '../../scripts/newdoc.php' ?></td>
                                </tr>
                              </table></td>
                            </tr>
                          <tr>
                            <td class="bl_bl"></td>
                            <td class="bl_bt"></td>
                            <td class="bl_br"></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td></td>
                    </tr>
                  <tr>
                    <td><div id="Details" class="TabbedPanels">
                      <ul class="TabbedPanelsTabGroup">
                        <li class="TabbedPanelsTab" tabindex="0" id="inf">Info</li>
                        <li class="TabbedPanelsTab" tabindex="0" id="perstab">Personal Info</li>
                        <li class="TabbedPanelsTab" tabindex="0" id="coytab">Contact Personnel</li>
                        <li class="TabbedPanelsTab" tabindex="0">Contact Details</li>
                        <li class="TabbedPanelsTab" tabindex="0" id="acc">Account</li>
                        <li class="TabbedPanelsTab" tabindex="0">Notes</li>
                        <li class="TabbedPanelsTab" tabindex="0">Documents</li>
                        </ul>
                      <div class="TabbedPanelsContentGroup">
                        <div class="TabbedPanelsContent"><table border="0" cellpadding="4" cellspacing="4">
                          <tr>
                            <td class="titles">Type:</td>
                            <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                              <tr>
                                <td><input type="radio" name="ClientType" id="ClientType_1" value="1" size="32" onclick="clientType(1, <?php echo $vtype ?>)" /></td>
                                <td><?php echo $vtype == 4 ? 'Company Account' : 'Individual' ?></td>
                                <td><input name="ClientType" type="radio" id="ClientType_2" value="2" size="32" onclick="clientType(2, <?php echo $vtype ?>)" checked="checked" /></td>
                                <td><?php echo $vtype == 4 ? 'Bank' : 'Company' ?></td>
                                </tr>
                              </table></td>
                            </tr>
                          <tr>
                            <td class="titles">Active:</td>
                            <td align="left"><input type="checkbox" name="InUse" value="1" /></td>
                            </tr>
                          <tr>
                            <td class="titles">Group Account:</td>
                            <td><select name="parentcompany">
                              <option value="" selected="selected"></option>
                              <?php foreach ($TClients as $row_TClients) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                              <?php } ?>
                              </select></td>
                            </tr>
                          <tr>
                            <td width="120" class="titles">Client Code:<iframe style="display:none" id="vendorcodewin"></iframe></td>
                            <td align="left"><input type="text" name="vendorcode" size="32" onchange="if (validateDataPop(arrFormValidation[2][0][0], this, arrFormValidation[2][0][1])) checkup(this, '../tmpl/checkcode.php?code='+this.value)" />
                              <input name="vetvendorcode" type="hidden" id="vetvendorcode" value="0" /><span id="vendorcodeprogress"></span></td>
                            </tr>
                          <tr>
                            <td colspan="2" class="titles"><table width="100%" border="0" cellpadding="2" cellspacing="2" id="coy">
                              <tr>
                                <td width="120" nowrap="nowrap" class="titles"><?php echo $vtype == 4 ? 'Account' : 'Company' ?> Name:</td>
                                <td align="left"><input type="text" name="CompanyName" size="32" /></td>
                                </tr>
                              </table></td>
                            </tr>
                          <tr>
                            <td colspan="2" class="titles"><table width="100%" border="0" cellpadding="2" cellspacing="2" id="pers">
                              <tr>
                                <td width="120" class="titles">Title:</td>
                                <td align="left"><input type="text" name="ContactTitle1" size="32" onchange="setFldVal(this, 'ContactTitle')" /></td>
                                </tr>
                              <tr>
                                <td width="118" class="titles">First Name:</td>
                                <td align="left"><input type="text" name="ContactFirstName1" size="32" onchange="setFldVal(this, 'ContactFirstName')" /></td>
                                </tr>
                              <tr>
                                <td width="118" class="titles">Middle Name:</td>
                                <td align="left"><input type="text" name="ContactMidName1" size="32" onchange="setFldVal(this, 'ContactMidName')" /></td>
                                </tr>
                              <tr>
                                <td width="118" class="titles">Last Name:</td>
                                <td align="left"><input type="text" name="ContactLastName1" size="32" onchange="setFldVal(this, 'ContactLastName')" /></td>
                                </tr>
                              </table></td>
                            </tr>
                          <tr>
                            <td class="titles">Category:</td>
                            <td align="left"><select name="categoryid">
                              <option value="" selected="selected"></option>
                              <?php foreach ($TCat as $row_TCat) { ?>
                              <option value="<?php echo $row_TCat['catID'] ?>"><?php echo $row_TCat['catname'] ?></option>
                              <?php } ?>
                              </select>
                              <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/clients/cat/index.php', 480,520)" /></td>
                            </tr>
                          <tr>
                            <td class="titles">Referred By:</td>
                            <td align="left"><input type="text" name="ReferredBy" size="32" /></td>
                            </tr>
                          </table>
                          </div>
                        <div class="TabbedPanelsContent" id="persdv">
                          <table border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <td width="120" class="titles">Gender:</td>
                              <td width="322"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
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
                              <td><textarea name="ability"></textarea></td>
                              </tr>
                            <tr>
                              <td class="titles">Marital Status:</td>
                              <td><select name="marital_status" style="width: 168px;">
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
                              <td class="bluetxt">&nbsp;</td>
                              <td align="left">&nbsp;</td>
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
                              <td class="titles">City Of Origin:</td>
                              <td><input type="text" name="locgovorigin" value="" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">&nbsp;</td>
                              <td>&nbsp;</td>
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
                              <td nowrap="nowrap" class="titles">Interests:</td>
                              <td><textarea name="ContactsInterests" style="width:300px" rows="3"></textarea></td>
                              </tr>
                            </table>
                          </div>
                        <div class="TabbedPanelsContent" id="coydv">
                          <table border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <td class="titles">Job Title:</td>
                              <td align="left"><input type="text" name="signfile" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Department:</td>
                              <td><input type="text" name="CompanyOrDepartment" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">&nbsp;</td>
                              <td>&nbsp;</td>
                              </tr>
                            <tr>
                              <td width="120" class="titles">Title:</td>
                              <td width="322"><input type="text" name="ContactTitle2" size="32" onchange="setFldVal(this, 'ContactTitle')" /></td>
                              </tr>
                            <tr>
                              <td class="titles">First Name:</td>
                              <td><input type="text" name="ContactFirstName2" size="32" onchange="setFldVal(this, 'ContactFirstName')" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Middle Name:</td>
                              <td><input type="text" name="ContactMidName2" size="32" onchange="setFldVal(this, 'ContactMidName')" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Last Name:</td>
                              <td><input type="text" name="ContactLastName2" size="32" onchange="setFldVal(this, 'ContactLastName')" /></td>
                              </tr>
                            <tr>
                              <td class="bluetxt">&nbsp;</td>
                              <td align="left">&nbsp;</td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Mobile No.:</td>
                              <td><input type="text" name="MobilePhone" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Personal No.:</td>
                              <td><input type="text" name="homephone" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Direct Line:</td>
                              <td><input type="text" name="workphone" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Extension:</td>
                              <td><input type="text" name="Extension" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Email:</td>
                              <td align="left"><input type="text" name="emeraddress" size="40" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Notes:</td>
                              <td><textarea name="staffrec" rows="3" style="width:300px"></textarea></td>
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
                              <td nowrap="nowrap" class="titles">Phone I:</td>
                              <td><input type="text" name="PhoneNumber" value="" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Phone II:</td>
                              <td><input type="text" name="FaxNumber" value="" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Phone III:</td>
                              <td><input type="text" name="emerphone" value="" size="32" /></td>
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
        <td nowrap="nowrap" class="titles">Currency:<img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
        <td align="left"><select name="currency">
          <option value=""></option>
          <?php for ($i=0; $i<count($Tcurrency); $i++) { ?>
          <option value="<?php echo $Tcurrency[$i]['cur_id']?>"><?php echo $Tcurrency[$i]['currencyname']?></option>
          <?php } ?>
          </select></td>
        </tr>
      <tr>
        <td class="titles">Cheque:</td>
        <td align="left"><input type="checkbox" name="cheque" value="1" /></td>
        </tr>
      <tr>
        <td class="titles">Credit:</td>
        <td align="left"><input type="checkbox" name="credit" value="1" /></td>
        </tr>
      <tr>
        <td nowrap="nowrap" class="titles">Credit Limit:</td>
        <td align="left"><input name="creditlimit" type="text" value="" size="32" /></td>
        </tr>
      <tr>
        <td width="120" nowrap="nowrap" class="titles">Discount:</td>
        <td width="322" align="left"><input name="Discount" type="text" value="" size="32" /></td>
        </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
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
                      </tr>
                  
                  </table></td>
              </tr>
              <tr>
                <td><input type="hidden" name="MM_insert" value="frmclient" />
<input type="hidden" name="ContactTitle" />
<input type="hidden" name="ContactFirstName" />
<input type="hidden" name="ContactMidName" />
<input type="hidden" name="ContactLastName" /></td>
</tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
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
var Tabs = new Spry.Widget.TabbedPanels("Details");
</script>
</body>
</html></html>
