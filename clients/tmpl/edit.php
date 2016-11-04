<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Clients'));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess('Clients', $vkey, 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmclient","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Clients'.DS.$vkey;
$doc_id = $id;

if (_xpost("MM_update") == "frmclient") {
  
	$pix = newpix(ROOT . CLIENTPIX_DIR, '', $id, 20, array(600, 200));
        $logo = newpix(ROOT . CLIENTPIX_DIR, '', $id, 1, array(600, 200, 100, 40), 'logofile');
	
	$sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET ClientType=%s, vendorcode=%s, 
            InUse=%s, ContactTitle=%s, logofile=%s, 
            ContactFirstName=%s, ContactMidName=%s, ContactLastName=%s, BillingAddress=%s, City=%s, StateOrProvince=%s, 
            Country=%s, PostalCode=%s, PhoneNumber=%s, MobilePhone=%s, Extension=%s, EmailAddress=%s, Notes=%s, ReferredBy=%s, 
            ContactsInterests=%s, ChildrenNames=%s, categoryid=%s, DeptID=%s, CompanyName=%s, dateofbirth=%s, religion=%s, 
            sex=%s, marital_status=%s, picturefile=%s, spousename=%s, ability=%s, workphone=%s, nationality=%s, stateorigin=%s, 
            locgovorigin=%s, nativetongue=%s, datehired=%s, datefired=%s, supervisor=%s, homephone=%s, leavstatus=%s, 
            emertype=%s, emername=%s, emerphone=%s, emeraddress=%s, Discount=%s, salary=%s, parentcompany=%s, 
            FaxNumber=%s, credit=%s, CompanyOrDepartment=%s, signfile=%s, passportno=%s, staffrec=%s, cheque=%s, creditlimit=%s
            WHERE VendorID=%s",
                       GSQLStr(_xpost('ClientType'), "int"),
                       GSQLStr(_xpost('vendorcode'), "text"),
                       _xpostchk('InUse'),
                       GSQLStr(_xpost('ContactTitle'), "text"),
                       $logo['pixcode'],
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
                       $pix['pixcode'],
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
                       GSQLStr(_xpost('salary'), "intn"),
                       GSQLStr(_xpost('parentcompany'), "intn"),
                       GSQLStr(_xpost('FaxNumber'), "text"),
                       _xpostchk('credit'),
                       GSQLStr(_xpost('CompanyOrDepartment'), "text"),
                       GSQLStr(_xpost('signfile'), "text"),
                       GSQLStr(_xpost('passportno'), "text"),
                       GSQLStr(_xpost('staffrec'), "text"),
                       GSQLStr(_xpost('cheque'), "int"),
                       GSQLStr(_xpost('creditlimit'), "double"),
                       $id);
	$update = runDBQry($dbh, $sql);
	docs($doc_shelf, $doc_id);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE `VendorID`=$id";
$row_TClients = getDBDataRow($dbh, $sql);

$sql = "SELECT country_id, country FROM `".DB_NAME."`.`country` ORDER BY country";
$Tcountry = getDBData($dbh, $sql);

$sql = "SELECT `state` FROM `".DB_NAME."`.`state` ORDER BY `state`";
$Tstate = getDBData($dbh, $sql);

$sql = "SELECT VendorID, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorType>0 AND VendorType<5 AND VendorID<>{$row_TClients['VendorID']} ORDER BY `VendorName`";
$TClients = getDBData($dbh, $sql);

$TCat = getClassify(6);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Client - Edit <?php echo $row_TClients['CompanyName'] ?></title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/clients/tmpl/script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
var arrFormValidation=[
    ["CompanyName", "if=$('#inf').click() && $('#ClientType_2').is(':checked')",
        ["req", "Enter Company Name"]
    ],
    ["ContactLastName", "if=$('#inf').click() && <?php echo $vtype ?>!=4 && $('#ClientType_1').is(':checked')",
        ["req", "Enter Last Name"]
    ]//,
//    ["vendorcode", "if=$('#inf').click()",
//        ["req", "Enter Client Code"]
//    ]
];

var mCal, mCal2, mCal3;
window.onload = function() {
    clientType(<?php echo $row_TClients['ClientType'], ',', $vtype ?>);
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
        <td width="240" valign="top"><img src="/images/<?php echo $vpth?>.jpg" alt="" width="240" height="300" /></td>
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
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td class="bluetxt">&nbsp;</td>
                    <td align="left"><img src="<?php echo CLIENTPIX_DIR, $id."/xlogo.jpg{$_SESSION['pixrnd']}" ?>" alt="" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Logo:</td>
                    <td align="left"><input type="file" name="logofile" /></td>
                    </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                  <tr>
                    <td colspan="2"><?php $pixcnt = 20; $max = 20000000; ?>
                      <?php 
$pictfld = $row_TClients['picturefile'];
$fpath = $id;
$pixdir = CLIENTPIX_DIR;
$pixi = 'x';
?>
                      &nbsp;</td>
                    </tr>
                  <tr>
                    <td colspan="2"><?php include('../../scripts/editpix.php'); ?></td>
                    </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                    </tr>
                  <tr>
                    <td colspan="2"><div id="Details" class="TabbedPanels">
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
                            <td class="titles">Client ID:</td>
                            <td class="red-normal"><b><?php echo $row_TClients['VendorID']; ?></b></td>
                            </tr>
                          <tr>
                            <td class="titles">Type:</td>
                            <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                              <tr>
                                <td><input type="radio" name="ClientType" id="ClientType_1" value="1" size="32" onclick="clientType(1, <?php echo $vtype ?>)" <?php if (!(strcmp($row_TClients['ClientType'], 1))) { echo "checked=\"checked\""; } ?> /></td>
                                <td><?php echo $vtype == 4 ? 'Company Account' : 'Individual' ?></td>
                                <td><input name="ClientType" type="radio" id="ClientType_2" value="2" size="32" onclick="clientType(2, <?php echo $vtype ?>)" <?php if (!(strcmp($row_TClients['ClientType'], 2))) { echo "checked=\"checked\""; } ?> /></td>
                                <td><?php echo $vtype == 4 ? 'Bank' : 'Company' ?></td>
                                </tr>
                              </table></td>
                            </tr>
                          <tr>
                            <td class="titles">Active:</td>
                            <td align="left"><input type="checkbox" name="InUse" value="1" <?php if (!(strcmp($row_TClients['InUse'], 1))) { echo "checked=\"checked\""; } ?> /></td>
                            </tr>
                          <tr>
                            <td class="titles">Group Account:</td>
                            <td><select name="parentcompany">
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TGrp) { ?>
                              <option value="<?php echo $row_TGrp['VendorID'] ?>" <?php if (!(strcmp($row_TClients['parentcompany'],$row_TGrp['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TGrp['VendorName'] ?></option>
                              <?php } ?>
                              </select></td>
                            </tr>
                          <tr>
                            <td width="120" class="titles">Client Code:</td>
                            <td align="left"><input name="vendorcode" type="text" value="<?php echo $row_TClients['vendorcode'] ?>" size="32" /></td>
                            </tr>
                          <tr>
                            <td colspan="2" class="titles"><table width="100%" border="0" cellpadding="2" cellspacing="2" id="coy">
                              <tr>
                                <td width="120" nowrap="nowrap" class="titles"><?php echo $vtype == 4 ? 'Account' : 'Company' ?> Name:</td>
                                <td align="left"><input name="CompanyName" type="text" value="<?php echo $row_TClients['CompanyName'] ?>" size="32" /></td>
                                </tr>
                              </table></td>
                            </tr>
                          <tr>
                            <td colspan="2" class="titles"><table width="100%" border="0" cellpadding="2" cellspacing="2" id="pers">
                              <tr>
                                <td width="120" class="titles">Title:</td>
                                <td align="left"><input type="text" name="ContactTitle1" value="<?php echo $row_TClients['ContactTitle'] ?>" size="32" onchange="setFldVal(this, 'ContactTitle')" /></td>
                                </tr>
                              <tr>
                                <td width="118" class="titles">First Name:</td>
                                <td align="left"><input type="text" name="ContactFirstName1" value="<?php echo $row_TClients['ContactFirstName'] ?>" size="32" onchange="setFldVal(this, 'ContactFirstName')" /></td>
                                </tr>
                              <tr>
                                <td width="118" class="titles">Middle Name:</td>
                                <td align="left"><input type="text" name="ContactMidName1" value="<?php echo $row_TClients['ContactMidName'] ?>" size="32" onchange="setFldVal(this, 'ContactMidName')" /></td>
                                </tr>
                              <tr>
                                <td width="118" class="titles">Last Name:</td>
                                <td align="left"><input type="text" name="ContactLastName1" value="<?php echo $row_TClients['ContactLastName'] ?>" size="32" onchange="setFldVal(this, 'ContactLastName')" /></td>
                                </tr>
                              </table></td>
                            </tr>
                          <tr>
                            <td class="titles">Category:</td>
                            <td align="left"><select name="categoryid">
                              <option value=""></option>
                              <?php foreach ($TCat as $row_TCat) { ?>
                              <option value="<?php echo $row_TCat['catID'] ?>" <?php if (!(strcmp($row_TClients['categoryid'],$row_TCat['catID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TCat['catname'] ?></option>
                              <?php } ?>
                              </select>
                              <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/clients/cat/index.php', 480,520)" /></td>
                            </tr>
                          <tr>
                            <td class="titles">Referred By:</td>
                            <td align="left"><input type="text" name="ReferredBy" value="<?php echo $row_TClients['ReferredBy'] ?>" size="32" /></td>
                            </tr>
                          </table>
                          </div>
                        <div class="TabbedPanelsContent" id="persdv">
                          <table border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <td width="120" class="titles">Gender:</td>
                              <td width="322"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                                <tr>
                                  <td><input type="radio" name="sex" value="1" size="32" <?php if (!(strcmp($row_TClients['sex'], 1))) { echo "checked=\"checked\""; } ?> /></td>
                                  <td>Male</td>
                                  <td><input type="radio" name="sex" value="2" size="32" <?php if (!(strcmp($row_TClients['sex'], 2))) { echo "checked=\"checked\""; } ?> /></td>
                                  <td>Female</td>
                                  </tr>
                                </table></td>
                              </tr>
                            <tr>
                              <td align="right" class="titles">Disability:</td>
                              <td><textarea name="ability"><?php echo $row_TClients['ability'] ?></textarea></td>
                              </tr>
                            <tr>
                              <td class="titles">Marital Status:</td>
                              <td><select name="marital_status" style="width: 168px;">
                                <option value="0" <?php if (!(strcmp($row_TClients['marital_status'],"0"))) {echo "selected=\"selected\"";} ?>>Select One </option>
                                <option value="1" <?php if (!(strcmp($row_TClients['marital_status'],"1"))) {echo "selected=\"selected\"";} ?>>Single - never married</option>
                                <option value="2" <?php if (!(strcmp($row_TClients['marital_status'],"2"))) {echo "selected=\"selected\"";} ?>>Married</option>
                                <option value="3" <?php if (!(strcmp($row_TClients['marital_status'],"3"))) {echo "selected=\"selected\"";} ?>>Divorced </option>
                                <option value="4" <?php if (!(strcmp($row_TClients['marital_status'],"4"))) {echo "selected=\"selected\"";} ?>>Widowed </option>
                                <option value="5" <?php if (!(strcmp($row_TClients['marital_status'],"5"))) {echo "selected=\"selected\"";} ?>>Separated </option>
                                </select></td>
                              </tr>
                            <tr>
                              <td class="titles">Religion:</td>
                              <td><select name="religion">
                                <option value="0" <?php if (!(strcmp($row_TClients['religion'],"0"))) {echo "selected=\"selected\"";} ?>>Select One </option>
                                <option value="1" <?php if (!(strcmp($row_TClients['religion'],"1"))) {echo "selected=\"selected\"";} ?>>Christian</option>
                                <option value="2" <?php if (!(strcmp($row_TClients['religion'],"2"))) {echo "selected=\"selected\"";} ?>>Muslim</option>
                                <option value="3" <?php if (!(strcmp($row_TClients['religion'],"3"))) {echo "selected=\"selected\"";} ?>>Jewish</option>
                                <option value="4" <?php if (!(strcmp($row_TClients['religion'],"4"))) {echo "selected=\"selected\"";} ?>>Budhist</option>
                                <option value="5" <?php if (!(strcmp($row_TClients['religion'],"5"))) {echo "selected=\"selected\"";} ?>>Atheist</option>
                                <option value="6" <?php if (!(strcmp($row_TClients['religion'],"6"))) {echo "selected=\"selected\"";} ?>>Others</option>
                                </select></td>
                              </tr>
                            <tr>
                              <td class="titles">Date of Birth:</td>
                              <td><input name="dateofbirth" type="text" id="dateofbirth" value="<?php echo $row_TClients['dateofbirth'] ?>" size="12" readonly="readonly" /></td>
                              </tr>
                            <tr>
                              <td class="bluetxt">&nbsp;</td>
                              <td align="left">&nbsp;</td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Passport No.:</td>
                              <td><input type="text" name="passportno" value="<?php echo $row_TClients['passportno'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Nationality: </td>
                              <td><select name="nationality" onchange="if (this.value==154){ this.form.cmbsto.style.display='block'; this.form.stateorigin.style.display='none';} else { this.form.stateorigin.style.display='block'; this.form.cmbsto.style.display='none';};">
                                <option value="">Select Nationality</option>
                                <?php foreach ($Tcountry as $row_Tcountry) { ?>
                                <option value="<?php echo $row_Tcountry['country_id'] ?>" <?php if (!(strcmp($row_TClients['nationality'],$row_Tcountry['country_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tcountry['country'] ?></option>
                                <?php } ?>
                                </select></td>
                              </tr>
                            <tr>
                              <td class="titles">State Of Origin:</td>
                              <td><select name="cmbsto" onchange="this.form.stateorigin.value=this.options[this.selectedIndex].text" >
                                <option value="">Select State</option>
                                <?php foreach ($Tstate as $row_Tstate) { ?>
                                <option value="<?php echo $row_Tstate['state'] ?>" <?php if (!(strcmp($row_TClients['stateorigin'],$row_Tstate['state']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tstate['state'] ?></option>
                                <?php } ?>
                                </select>
                                <input name="stateorigin" type="text" value="<?php echo $row_TClients['stateorigin'] ?>" size="25" style="display: none" /></td>
                              </tr>
                            <tr>
                              <td class="titles">City Of Origin:</td>
                              <td><input type="text" name="locgovorigin" value="<?php echo $row_TClients['locgovorigin'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">&nbsp;</td>
                              <td>&nbsp;</td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Spouse:</td>
                              <td><input type="text" name="spousename" value="<?php echo $row_TClients['spousename'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Children:</td>
                              <td><textarea name="ChildrenNames" style="width:300px" rows="3"><?php echo $row_TClients['ChildrenNames'] ?></textarea></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Interests:</td>
                              <td><textarea name="ContactsInterests" style="width:300px" rows="3"><?php echo $row_TClients['ContactsInterests'] ?></textarea></td>
                              </tr>
                            </table>
                          </div>
                        <div class="TabbedPanelsContent" id="coydv">
                          <table border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <td class="titles">Job Title:</td>
                              <td align="left"><input type="text" name="signfile" value="<?php echo $row_TClients['signfile'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Department:</td>
                              <td><input type="text" name="CompanyOrDepartment" value="<?php echo $row_TClients['CompanyOrDepartment'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">&nbsp;</td>
                              <td>&nbsp;</td>
                              </tr>
                            <tr>
                              <td width="120" class="titles">Title:</td>
                              <td width="322"><input type="text" name="ContactTitle2" value="<?php echo $row_TClients['ContactTitle'] ?>" size="32" onchange="setFldVal(this, 'ContactTitle')" /></td>
                              </tr>
                            <tr>
                              <td class="titles">First Name:</td>
                              <td><input type="text" name="ContactFirstName2" value="<?php echo $row_TClients['ContactFirstName'] ?>" size="32" onchange="setFldVal(this, 'ContactFirstName')" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Middle Name:</td>
                              <td><input type="text" name="ContactMidName2" value="<?php echo $row_TClients['ContactMidName'] ?>" size="32" onchange="setFldVal(this, 'ContactMidName')" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Last Name:</td>
                              <td><input type="text" name="ContactLastName2" value="<?php echo $row_TClients['ContactLastName'] ?>" size="32" onchange="setFldVal(this, 'ContactLastName')" /></td>
                              </tr>
                            <tr>
                              <td class="bluetxt">&nbsp;</td>
                              <td align="left">&nbsp;</td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Mobile No.:</td>
                              <td><input type="text" name="MobilePhone" value="<?php echo $row_TClients['MobilePhone'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Personal No.:</td>
                              <td><input type="text" name="homephone" value="<?php echo $row_TClients['homephone'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Direct Line:</td>
                              <td><input type="text" name="workphone" value="<?php echo $row_TClients['workphone'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Extension:</td>
                              <td><input type="text" name="Extension" value="<?php echo $row_TClients['Extension'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Email:</td>
                              <td align="left"><input type="text" name="emeraddress" value="<?php echo $row_TClients['emeraddress'] ?>" size="40" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Notes:</td>
                              <td><textarea name="staffrec" rows="3" style="width:300px"><?php echo $row_TClients['staffrec'] ?></textarea></td>
                              </tr>
                            </table>
                          </div>
                        <div class="TabbedPanelsContent">
                          <table border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <td width="120" valign="top" class="titles">Address:</td>
                              <td width="322" align="left"><textarea name="BillingAddress" style="width:300px" rows="3"><?php echo $row_TClients['BillingAddress'] ?></textarea></td>
                              </tr>
                            <tr>
                              <td width="120" class="titles">City:</td>
                              <td align="left"><input type="text" name="City" value="<?php echo $row_TClients['City'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td width="120" class="titles">Country:</td>
                              <td align="left"><select name="Country" id="Country" onchange="if (this.value==154){this.form.cmbsta.style.display='block'; this.form.StateOrProvince.style.display='none';} else {this.form.cmbsta.style.display='none'; this.form.StateOrProvince.style.display='block'; }">
                                <option value="">Select Country</option>
                                <?php foreach ($Tcountry as $row_Tcountry) { ?>
                                <option value="<?php echo $row_Tcountry['country_id']?>" <?php if (!(strcmp($row_TClients['Country'],$row_Tcountry['country_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tcountry['country']?></option>
                                <?php } ?>
                                </select></td>
                              </tr>
                            <tr>
                              <td width="120" class="titles">State:</td>
                              <td align="left" nowrap="nowrap"><select name="cmbsta" onchange="this.form.StateOrProvince.value=this.value">
                                <option value="">Select State</option>
                                <?php foreach ($Tstate as $row_Tstate) { ?>
                                <option value="<?php echo $row_Tstate['state']?>" <?php if (!(strcmp($row_TClients['StateOrProvince'],$row_Tstate['state']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tstate['state']?></option>
                                <?php } ?>
                                </select>
                                <input name="StateOrProvince" type="text" size="25" value="<?php echo $row_TClients['StateOrProvince'] ?>" style="display: none" /></td>
                              </tr>
                            <tr>
                              <td width="120" class="bluetxt">&nbsp;</td>
                              <td align="left">&nbsp;</td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Phone I:</td>
                              <td><input type="text" name="PhoneNumber" value="<?php echo $row_TClients['PhoneNumber'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Phone II:</td>
                              <td><input type="text" name="FaxNumber" value="<?php echo $row_TClients['FaxNumber'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Phone III:</td>
                              <td><input type="text" name="emerphone" value="<?php echo $row_TClients['emerphone'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td width="120" class="titles">Email:</td>
                              <td align="left"><input type="text" name="EmailAddress" value="<?php echo $row_TClients['EmailAddress'] ?>" size="32" /></td>
                              </tr>
                            </table>
                          </div>
  <div class="TabbedPanelsContent">
    <table border="0" cellspacing="4" cellpadding="4">
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
        <td align="left"><input name="creditlimit" type="text" value="<?php echo $row_TClients['creditlimit'] ?>" size="32" /></td>
        </tr>
      <tr>
        <td width="120" nowrap="nowrap" class="titles">Discount:</td>
        <td width="322" align="left"><input name="Discount" type="text" value="<?php echo $row_TClients['Discount'] ?>" size="32" /></td>
        </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
      </table>
    </div>
  <div class="TabbedPanelsContent">
    <textarea name="Notes" style="width:450px" rows="10"><?php echo $row_TClients['Notes'] ?></textarea>
  </div>
  <div class="TabbedPanelsContent">
    <?php include "../../scripts/editdoc.php" ?>
  </div>
                        </div>
                      </div></td>
  </tr>
                  <tr>
                    <td colspan="2"><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
                      <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
                      <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
                      <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
                      <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
                      <script type="text/javascript">
    mCal = new dhtmlxCalendarObject('dateofbirth', true, {isYearEditable: true, isMonthEditable: true});
	mCal.setSkin('dhx_black');
    mCal2 = new dhtmlxCalendarObject('datehired', true, {isYearEditable: true, isMonthEditable: true});
	mCal2.setSkin('dhx_black');
    mCal3 = new dhtmlxCalendarObject('datefired', true, {isYearEditable: true, isMonthEditable: true});
	mCal3.setSkin('dhx_black');
}
                  </script></td>
                    </tr>
                  
                  </table></td>
              </tr>
              <tr>
                <td><input type="hidden" name="MM_update" value="frmclient" />
                  <input type="hidden" name="VendorID" value="<?php echo $row_TClients['VendorID']; ?>" />
                  <?php include('../../scripts/buttonset.php')?></td>
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
</html>
