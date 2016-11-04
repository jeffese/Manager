<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Clients'));
$access = _xvar_arr_sub($_access, array('Customers'));
vetAccess('Clients', 'Customers', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmclient","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Clients'.DS.'Customers';
$doc_id = $id;

if (_xpost("MM_update") == "frmclient") {
  
	$pix = newpix(ROOT . CLIENTPIX_DIR, '', $id, 20, array(600, 200));
        $logo = newpix(ROOT . CLIENTPIX_DIR, '', $id, 1, array(600, 200, 100, 40), 'logofile');
	
	$sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET ClientType=%s, 
            ContactTitle=%s, ContactFirstName=%s, ContactMidName=%s, ContactLastName=%s, 
            BillingAddress=%s, City=%s, StateOrProvince=%s, PhoneNumber=%s, MobilePhone=%s, 
            EmailAddress=%s, Notes=%s, CompanyName=%s, religion=%s, 
            sex=%s, workphone=%s, nationality=%s, FaxNumber=%s, passportno=%s
            WHERE VendorID=%s",
                       GSQLStr(_xpost('ClientType'), "int"),
                       GSQLStr(_xpost('ContactTitle'), "text"),
                       GSQLStr(_xpost('ContactFirstName'), "text"),
                       GSQLStr(_xpost('ContactMidName'), "text"),
                       GSQLStr(_xpost('ContactLastName'), "text"),
                       GSQLStr(_xpost('BillingAddress'), "text"),
                       GSQLStr(_xpost('City'), "text"),
                       GSQLStr(_xpost('StateOrProvince'), "text"),
                       GSQLStr(_xpost('PhoneNumber'), "text"),
                       GSQLStr(_xpost('MobilePhone'), "text"),
                       GSQLStr(_xpost('EmailAddress'), "text"),
                       GSQLStr(_xpost('Notes'), "text"),
                       GSQLStr(_xpost('CompanyName'), "text"),
                       GSQLStr(_xpost('religion'), "int"),
                       GSQLStr(_xpost('sex'), "int"),
                       GSQLStr(_xpost('workphone'), "text"),
                       GSQLStr(_xpost('nationality'), "int"),
                       GSQLStr(_xpost('FaxNumber'), "text"),
                       GSQLStr(_xpost('passportno'), "text"),
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
<link href="/css/main.css" rel="stylesheet" type="text/css" />
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
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<script type="text/javascript" src="/scripts/js/set.js"></script>
<script type="text/javascript"> 
window.onload = function() {
    setContent();
    clientType(<?php echo $row_TClients['ClientType'], ',', 1 ?>);
}
window.onresize = function() {
    setContent();
}

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
<div id="content">
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="frametopleft">&nbsp;</td>
      <td class="frametop">&nbsp;</td>
      <td class="frametopright">&nbsp;</td>
    </tr>
    <tr>
      <td class="frameleft">&nbsp;</td>
      <td bgcolor="#FFFFFF">
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><span class="titles"><img src="/images/customers.jpg" alt="" width="240" height="300" /></span></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(../images/lblowners.png); background-repeat:no-repeat">&nbsp;</td>
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
                    <td><div id="Details" class="TabbedPanels">
                      <ul class="TabbedPanelsTabGroup">
                        <li class="TabbedPanelsTab" tabindex="0" id="inf">Info</li>
                        <li class="TabbedPanelsTab" tabindex="0" id="perstab">Personal Info</li>
  <li class="TabbedPanelsTab" tabindex="0">Contact Details</li>
<li class="TabbedPanelsTab" tabindex="0">Notes</li>
                        <li class="TabbedPanelsTab" tabindex="0">Documents</li>
                        </ul>
                      <div class="TabbedPanelsContentGroup">
                        <div class="TabbedPanelsContent"><table border="0" cellpadding="4" cellspacing="4">
                          <tr>
                            <td></td>
                            <td align="center"><?php echo catch_error($errors) ?></td>
                          </tr>
                          <tr>
                            <td width="119" class="titles">Client ID:</td>
                            <td width="195" align="left" class="red-normal"><b><?php echo $row_TClients['VendorID']; ?></b></td>
                            </tr>
                          <tr>
                            <td class="titles">Type:</td>
                            <td align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                              <tr>
                                <td><input type="radio" name="ClientType" id="ClientType_1" onclick="clientType(1, 1)" value="1" size="32" <?php if (!(strcmp($row_TClients['ClientType'], 1))) { echo "checked=\"checked\""; } ?> /></td>
                                <td>Individual</td>
                                <td><input name="ClientType" type="radio" id="ClientType_2" onclick="clientType(2, 1)" value="2" size="32" <?php if (!(strcmp($row_TClients['ClientType'], 2))) { echo "checked=\"checked\""; } ?> /></td>
                                <td>Corporate</td>
                                <td><input name="ClientType" type="radio" id="ClientType_3" onclick="clientType(2, 1)" value="3" size="32" <?php if (!(strcmp($row_TClients['ClientType'], 3))) { echo "checked=\"checked\""; } ?> /></td>
                                <td>Government</td>
                              </tr>
                            </table></td>
                            </tr>
                          <tr>
                            <td colspan="2" class="titles"><table width="100%" border="0" cellpadding="2" cellspacing="2" id="coy">
                              <tr>
                                <td width="120" nowrap="nowrap" class="titles">Company Name:</td>
                                <td align="left"><input name="CompanyName" type="text" value="<?php echo $row_TClients['CompanyName'] ?>" size="32" /></td>
                                </tr>
                              </table></td>
                            </tr>
                          <tr>
                            <td colspan="2" class="titles"><table width="100%" border="0" cellpadding="2" cellspacing="2" id="pers">
                              <tr>
                                <td width="120" class="titles">Title:</td>
                                <td align="left"><select name="ContactTitle1" id="ContactTitle1" onchange="setFldVal(this, 'ContactTitle')">
                                  <option selected="selected">..</option>
                                  <option value="Alh." <?php if (!(strcmp($row_TClients['ContactTitle'], "Alh."))) {echo "selected=\"selected\"";} ?>>Alh.</option>
                                  <option value="Arc." <?php if (!(strcmp($row_TClients['ContactTitle'], "Arc."))) {echo "selected=\"selected\"";} ?>>Arc.</option>
                                  <option value="Bar." <?php if (!(strcmp($row_TClients['ContactTitle'], "Bar."))) {echo "selected=\"selected\"";} ?>>Bar.</option>
                                  <option value="Dr." <?php if (!(strcmp($row_TClients['ContactTitle'], "Dr."))) {echo "selected=\"selected\"";} ?>>Dr.</option>
                                  <option value="Engr." <?php if (!(strcmp($row_TClients['ContactTitle'], "Engr."))) {echo "selected=\"selected\"";} ?>>Engr.</option>
                                  <option value="Mr." <?php if (!(strcmp($row_TClients['ContactTitle'], "Mr."))) {echo "selected=\"selected\"";} ?>>Mr.</option>
                                  <option value="Mrs." <?php if (!(strcmp($row_TClients['ContactTitle'], "Mrs."))) {echo "selected=\"selected\"";} ?>>Mrs.</option>
                                  <option value="Prof." <?php if (!(strcmp($row_TClients['ContactTitle'], "Prof."))) {echo "selected=\"selected\"";} ?>>Prof.</option>
                                </select></td>
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
                          </table>
                          </div>
                        <div class="TabbedPanelsContent" id="persdv">
                          <table border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <td width="120" class="titles">Gender:</td>
                              <td width="322" align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                                <tr>
                                  <td><input type="radio" name="sex" value="1" size="32" <?php if (!(strcmp($row_TClients['sex'], 1))) { echo "checked=\"checked\""; } ?> /></td>
                                  <td>Male</td>
                                  <td><input type="radio" name="sex" value="2" size="32" <?php if (!(strcmp($row_TClients['sex'], 2))) { echo "checked=\"checked\""; } ?> /></td>
                                  <td>Female</td>
                                  </tr>
                                </table></td>
                              </tr>
                            <tr>
                              <td class="titles">Religion:</td>
                              <td align="left"><select name="religion">
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
                              <td class="bluetxt">&nbsp;</td>
                              <td align="left">&nbsp;</td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Passport No.:</td>
                              <td align="left"><input type="text" name="passportno" value="<?php echo $row_TClients['passportno'] ?>" size="32" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Nationality: </td>
                              <td align="left"><select name="nationality" onchange="if (this.value==154){ this.form.cmbsto.style.display='block'; this.form.stateorigin.style.display='none';} else { this.form.stateorigin.style.display='block'; this.form.cmbsto.style.display='none';};">
                                <option value="">Select Nationality</option>
                                <?php foreach ($Tcountry as $row_Tcountry) { ?>
                                <option value="<?php echo $row_Tcountry['country_id'] ?>" <?php if (!(strcmp($row_TClients['nationality'],$row_Tcountry['country_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tcountry['country'] ?></option>
                                <?php } ?>
                                </select></td>
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
        <td width="120" class="titles">State:</td>
        <td align="left" nowrap="nowrap"><select name="StateOrProvince">
          <option value="" selected="selected">Select State</option>
          <?php foreach ($Tstate as $row_Tstate) { ?>
          <option value="<?php echo $row_Tstate['state']?>" <?php if (!(strcmp($row_TClients['StateOrProvince'],$row_Tstate['state']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tstate['state']?></option>
          <?php } ?>
          </select></td>
        </tr>
      <tr>
        <td width="120" class="bluetxt">&nbsp;</td>
        <td align="left">&nbsp;</td>
        </tr>
      <tr>
        <td nowrap="nowrap" class="titles">Phone I:</td>
        <td align="left"><input type="text" name="PhoneNumber" value="<?php echo $row_TClients['PhoneNumber'] ?>" size="32" /></td>
        </tr>
      <tr>
        <td nowrap="nowrap" class="titles">Phone II:</td>
        <td align="left"><input type="text" name="FaxNumber" value="<?php echo $row_TClients['FaxNumber'] ?>" size="32" /></td>
        </tr>
      <tr>
        <td width="120" class="titles">Email:</td>
        <td align="left"><input type="text" name="EmailAddress" value="<?php echo $row_TClients['EmailAddress'] ?>" size="32" /></td>
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
                  
                  </table></td>
              </tr>
              <tr>
                <td><input type="hidden" name="MM_update" value="frmclient" />
                  <input type="hidden" name="VendorID" value="<?php echo $row_TClients['VendorID']; ?>" />
                  <input type="hidden" name="MM_insert" value="frmclient" />
                  <input type="hidden" name="ContactTitle" value="<?php echo $row_TClients['ContactTitle'] ?>" />
                  <input type="hidden" name="ContactFirstName" value="<?php echo $row_TClients['ContactFirstName'] ?>" />
                  <input type="hidden" name="ContactMidName" value="<?php echo $row_TClients['ContactMidName'] ?>" />
                  <input type="hidden" name="ContactLastName" value="<?php echo $row_TClients['ContactLastName'] ?>" /></td>
                </tr>
              
              </table>
            </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>            </tr>
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<script type="text/javascript">
var Tabs = new Spry.Widget.TabbedPanels("Details");
</script></td>
      <td class="frameright">&nbsp;</td>
    </tr>
    <tr>
      <td class="framebotleft">&nbsp;</td>
      <td valign="bottom" class="framebot"><span class="greytxt">Copyright © 2010 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>
</html>
