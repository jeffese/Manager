<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Clients'));
$access = _xvar_arr_sub($_access, array('Customers'));
vetAccess('Clients', 'Customers', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 1, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmclient","","index.php","","find.php","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmclient") {
  
	$sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`vendors` (VendorType, amtbal, ClientType, 
            vendorcode, InUse, ContactTitle, 
            ContactFirstName, ContactMidName, ContactLastName, BillingAddress, City, StateOrProvince, 
            Country, PostalCode, PhoneNumber, MobilePhone, Extension, EmailAddress, Notes, ReferredBy, LastMeetingDate,
            ContactsInterests, ChildrenNames, categoryid, DeptID, CompanyName, dateofbirth, religion, 
            sex, marital_status, spousename, ability, workphone, nationality, stateorigin, 
            locgovorigin, nativetongue, datehired, datefired, supervisor, homephone, leavstatus, 
            emertype, emername, emerphone, emeraddress, Discount, `currency`, salary, parentcompany, 
            FaxNumber, credit, CompanyOrDepartment, signfile, passportno, staffrec, cheque, creditlimit) 
            VALUES (1, '0', %s, NULL, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 
            %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 
            %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GSQLStr(_xpost('ClientType'), "int"),
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
                       "NOW()",
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
                       GSQLStr($_SESSION['EmployeeID'], "int"),
                       GSQLStr(_xpost('homephone'), "text"),
                       1,
                       GSQLStr(_xpost('emertype'), "int"),
                       GSQLStr(_xpost('emername'), "text"),
                       GSQLStr(_xpost('emerphone'), "text"),
                       GSQLStr(_xpost('emeraddress'), "text"),
                       GSQLStr(_xpost('Discount'), "int"),
                       GSQLStr($_SESSION['COY']['currency'], "int"),
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
        docs('Clients'.DS.'Customers', $recid);
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
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
var arrFormValidation=[
    ["CompanyName", "if=!$('#ClientType_1').is(':checked') && $('#inf').click()",
        ["req", "Enter Company Name"]
    ],
    ["ContactFirstName1", "if=$('#ClientType_1').is(':checked') && $('#inf').click()",
        ["req", "Enter First Name"]
    ],
    ["ContactLastName1", "if=$('#ClientType_1').is(':checked') && $('#inf').click()",
        ["req", "Enter Last Name"]
    ],
    ["nationality", "if=$('#ClientType_1').is(':checked') && $('#perstab').click()",
        ["req", "Select Nationality"]
    ],
    ["religion", "if=$('#ClientType_1').is(':checked') && $('#perstab').click()",
        ["req", "Select ID Type"]
    ],
    ["passportno", "if=$('#ClientType_1').is(':checked') && $('#perstab').click()",
        ["req", "Enter ID No."]
    ],
    ["BillingAddress", "if=$('#contact').click()",
        ["req", "Enter Address"]
    ],
    ["StateOrProvince", "",
        ["req", "Select State"]
    ],
    ["City", "",
        ["req", "Enter City Name"]
    ]
];

</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><span class="titles"><img src="/images/customers.jpg" alt="" width="240" height="300" /></span><span class="titles">        </span></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/custom/images/lblownernew.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td></td>
              </tr>
              <tr>
                <td><div id="Details" class="TabbedPanels">
                  <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0" id="inf">Info</li>
                    <li class="TabbedPanelsTab" tabindex="0" id="perstab">Personal Info</li>
  <li class="TabbedPanelsTab" tabindex="0" id="contact">Contact Details</li>
                    <li class="TabbedPanelsTab" tabindex="0">Notes</li>
                    <li class="TabbedPanelsTab" tabindex="0">Documents</li>
                  </ul>
                  <div class="TabbedPanelsContentGroup">
                        <div class="TabbedPanelsContent" style="display: none"><table border="0" cellpadding="4" cellspacing="4">
                          <tr>
                            <td></td>
                            <td align="center"><?php echo catch_error($errors) ?></td>
                          </tr>
                      <tr>
                            <td width="120" class="titles">Type:</td>
                        <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                          <tr>
                                <td><input name="ClientType" type="radio" id="ClientType_1" onclick="clientType(1, 1)" value="1" size="32" checked="checked" /></td>
                                <td>Individual</td>
                                <td><input name="ClientType" type="radio" id="ClientType_2" value="2" size="32" onclick="clientType(2, 1)" /></td>
                                <td>Corporate</td>
                                <td><input name="ClientType" type="radio" id="ClientType_3" value="3" size="32" onclick="clientType(2, 1)" /></td>
                                <td>Government</td>
                            </tr>
                          </table></td>
                        </tr>
                      <tr>
                        <td colspan="2" class="titles"><table width="100%" border="0" cellpadding="2" cellspacing="2" id="coy">
                          <tr>
                                <td width="120" nowrap="nowrap" class="titles">Name:</td>
                                <td align="left"><input type="text" name="CompanyName" style="width:300px" /></td>
                            </tr>
                          </table></td>
                        </tr>
                      <tr>
                        <td colspan="2" class="titles"><table width="100%" border="0" cellpadding="2" cellspacing="2" id="pers">
                          <tr>
                            <td width="120" class="titles">Title:</td>
                                <td align="left"><select name="ContactTitle1" id="ContactTitle1" onchange="setFldVal(this, 'ContactTitle')">
                                  <option selected="selected">..</option>
                                  <option value="Alh.">Alh.</option>
                                  <option value="Arc.">Arc.</option>
                                  <option value="Bar.">Bar.</option>
                                  <option value="Dr.">Dr.</option>
                                  <option value="Engr.">Engr.</option>
                                  <option value="Mr.">Mr.</option>
                                  <option value="Mrs.">Mrs.</option>
                                  <option value="Prof.">Prof.</option>
                                  </select></td>
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
                      </table>
                      </div>
                        <div class="TabbedPanelsContent" id="persdv" style="display: none">
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
                          <td class="titles">Nationality: </td>
                              <td align="left"><select name="nationality" onchange="if (this.value==154){ this.form.cmbsto.style.display='block'; this.form.stateorigin.style.display='none';} else { this.form.stateorigin.style.display='block'; this.form.cmbsto.style.display='none';};">
                            <option value="">Select Nationality</option>
                            <?php foreach ($Tcountry as $row_Tcountry) { ?>
                            <option value="<?php echo $row_Tcountry['country_id'] ?>" <?php if ($row_Tcountry['country_id']==154) {echo "selected=\"selected\"";} ?>><?php echo $row_Tcountry['country'] ?></option>
                            <?php } ?>
                            </select></td>
                          </tr>
                        <tr>
                              <td class="titles">ID Type: </td>
                              <td align="left"><select name="religion">
                                <option value="" selected="selected">..</option>
                                <option value="1">Passport</option>
                                <option value="2">National ID.</option>
                                <option value="3">Driver's License</option>
                                <?php foreach ($Tcountry as $row_Tcountry) { ?>
                            <?php } ?>
                                </select></td>
                          </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">ID No.:</td>
                              <td align="left"><input type="text" name="passportno" value="" size="32" /></td>
                          </tr>
                        </table>
                      </div>
  <div class="TabbedPanelsContent" style="display: block">
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
                          <td width="120" class="titles">State:</td>
        <td align="left" nowrap="nowrap"><select name="StateOrProvince">
          <option value="" selected="selected">..</option>
                            <?php foreach ($Tstate as $row_Tstate) { ?>
                            <option value="<?php echo $row_Tstate['state']?>"><?php echo $row_Tstate['state']?></option>
                            <?php } ?>
          </select></td>
                        </tr>
                        <tr>
                          <td width="120" class="bluetxt">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Phone I:</td>
        <td align="left"><input type="text" name="PhoneNumber" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Phone II:</td>
        <td align="left"><input type="text" name="FaxNumber" value="" size="32" /></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Email:</td>
                          <td align="left"><input type="text" name="EmailAddress" value="" size="32" /></td>
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
                    <td></tr>

            </table></td>
              </tr>
              <tr>
                <td><input type="hidden" name="MM_insert" value="frmclient" />
<input type="hidden" name="ContactTitle" />
<input type="hidden" name="ContactFirstName" />
<input type="hidden" name="ContactMidName" />
<input type="hidden" name="ContactLastName" /></td>
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