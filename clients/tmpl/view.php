<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Clients'));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess('Clients', $vkey, 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Client]del.php?id=$id","","","find.php","return GB_showCenter('clientee File', '/clients/{$vpth}/print.php?id=$id', 600,600)","index.php");
$rec_status = 1;

$vendor_supo = vendorFlds("VendorGrp", "grp");
$sql = "SELECT `vendors`.*, country.country AS cntry, nation.country as nation, 
$vendor_supo, catname AS cat, currencyname
            FROM `{$_SESSION['DBCoy']}`.`vendors` 
            LEFT JOIN `".DB_NAME."`.`country` ON `vendors`.country=country.country_id
            LEFT JOIN `".DB_NAME."`.`country` nation ON `vendors`.nationality = nation.country_id 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `vendors`.categoryid=`classifications`.catID 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `VendorGrp` ON `vendors`.parentcompany=VendorGrp.VendorID 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`currencies` ON `vendors`.currency=`currencies`.cur_id
            WHERE `vendors`.`VendorID`=$id";
$row_TClients = getDBDataRow($dbh, $sql);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APN - <?php echo $row_TClients['ContactLastName'], ' ', $row_TClients['ContactFirstName'] ?> Details</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/clients/tmpl/script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
window.onload = function() {
    clientType(<?php echo $row_TClients['ClientType'], ',', $vtype; ?>);
}
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
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
                    <td class="titles">Logo:</td>
                    <td align="left"><img src="<?php echo CLIENTPIX_DIR, $id."/xlogo.jpg{$_SESSION['pixrnd']}" ?>" alt="" /></td>
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
$xid = '';
$label = '';
?>
                  <?php include('../../scripts/viewpix.php'); ?>
                  &nbsp;</td>
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
                    <li class="TabbedPanelsTab" tabindex="0" id="acc">Ledger</li>
                    <li class="TabbedPanelsTab" tabindex="0"<?php if ($row_TClients['VendorType'] == 4) { ?> style="display:none"<?php } ?>>Services</li>
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
                            <td><input type="radio" name="ClientType" id="ClientType_1" value="1" size="32" <?php if (!(strcmp($row_TClients['ClientType'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                            <td><?php echo $vtype == 4 ? 'Company Account' : 'Individual' ?></td>
                            <td><input name="ClientType" type="radio" id="ClientType_2" value="2" size="32" <?php if (!(strcmp($row_TClients['ClientType'], 2))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                            <td><?php echo $vtype == 4 ? 'Bank' : 'Company' ?></td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td class="titles">Active:</td>
                        <td align="left"><input type="checkbox" name="InUse" value="1" <?php if (!(strcmp($row_TClients['InUse'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                        </tr>
                      <tr>
                        <td class="titles">Group Account:</td>
                        <td><?php echo $row_TClients['grp'] ?></td>
                        </tr>
                      <tr>
                        <td width="120" class="titles">Client Code:</td>
                        <td align="left"><?php echo $row_TClients['vendorcode'] ?></td>
                        </tr>
                      <tr>
                        <td colspan="2"><table width="100%" border="0" cellpadding="2" cellspacing="2" id="coy">
                          <tr>
                            <td width="120" nowrap="nowrap" class="titles"><?php echo $vtype == 4 ? 'Account' : 'Company' ?> Name:</td>
                            <td align="left" class="blacktxt"><?php echo $row_TClients['CompanyName'] ?></td>
                            </tr>
                          </table></td>
                        </tr>
                      <tr>
                        <td colspan="2"><table width="100%" border="0" cellpadding="2" cellspacing="2" id="pers">
                          <tr>
                            <td width="120" class="titles">Title:</td>
                            <td align="left"><?php echo $row_TClients['ContactTitle'] ?></td>
                            </tr>
                          <tr>
                            <td width="118" class="titles">First Name:</td>
                            <td align="left"><?php echo $row_TClients['ContactFirstName'] ?></td>
                            </tr>
                          <tr>
                            <td width="118" class="titles">Middle Name:</td>
                            <td align="left"><?php echo $row_TClients['ContactMidName'] ?></td>
                            </tr>
                          <tr>
                            <td width="118" class="titles">Last Name:</td>
                            <td align="left"><?php echo $row_TClients['ContactLastName'] ?></td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td class="titles">Category:</td>
                        <td align="left"><?php echo $row_TClients['cat'] ?></td>
                      </tr>
                      <tr>
                        <td class="titles">Referred By:</td>
                        <td align="left"><?php echo $row_TClients['ReferredBy'] ?></td>
                        </tr>
                      </table>
                      </div>
                    <div class="TabbedPanelsContent" id="persdv">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" class="titles">Gender:</td>
                          <td width="322"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                            <tr>
                              <td><input type="radio" name="sex" value="1" size="32" <?php if (!(strcmp($row_TClients['sex'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                              <td>Male</td>
                              <td><input type="radio" name="sex" value="2" size="32" <?php if (!(strcmp($row_TClients['sex'], 2))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                              <td>Female</td>
                              </tr>
                            </table></td>
                          </tr>
                        <tr>
                          <td align="right" class="titles">Disability:</td>
                          <td><textarea name="ability" readonly="readonly"><?php echo $row_TClients['ability'] ?></textarea></td>
                          </tr>
                        <tr>
                          <td class="titles">Marital Status:</td>
                          <td><script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TClients['marital_status']; ?>) {
case 1: document.write("Single - never married"); break;
case 2: document.write("Married"); break;
case 3: document.write("Divorced"); break;
case 4: document.write("Widowed"); break;
case 5: document.write("Separated"); break;
default: document.write("");
}</script></td>
                          </tr>
                        <tr>
                          <td class="titles">Religion:</td>
                          <td><script type="text/javascript">
switch (<?php echo $row_TClients['religion']; ?>) {
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
                          <td><?php echo $row_TClients['dateofbirth'] ?></td>
                          </tr>
                        <tr>
                          <td class="bluetxt">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Passport No.:</td>
                          <td><?php echo $row_TClients['passportno'] ?></td>
                          </tr>
                        <tr>
                          <td class="titles">Nationality: </td>
                          <td><?php echo $row_TClients['nation'] ?></td>
                          </tr>
                        <tr>
                          <td class="titles">State Of Origin:</td>
                          <td><?php echo $row_TClients['stateorigin'] ?></td>
                          </tr>
                        <tr>
                          <td class="titles">City Of Origin:</td>
                          <td><?php echo $row_TClients['locgovorigin'] ?></td>
                          </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td>&nbsp;</td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Spouse:</td>
                          <td><?php echo $row_TClients['spousename'] ?></td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Children:</td>
                          <td><textarea name="ChildrenNames" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TClients['ChildrenNames'] ?></textarea></td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Interests:</td>
                          <td><textarea name="ContactsInterests" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TClients['ContactsInterests'] ?></textarea></td>
                          </tr>
                        </table>
                      </div>
                    <div class="TabbedPanelsContent" id="coydv">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td class="titles">Job Title:</td>
                          <td align="left"><?php echo $row_TClients['signfile'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Department:</td>
                          <td><?php echo $row_TClients['CompanyOrDepartment'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Title:</td>
                          <td width="322"><?php echo $row_TClients['ContactTitle'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">First Name:</td>
                          <td><?php echo $row_TClients['ContactFirstName'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Middle Name:</td>
                          <td><?php echo $row_TClients['ContactMidName'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Last Name:</td>
                          <td><?php echo $row_TClients['ContactLastName'] ?></td>
                        </tr>
                        <tr>
                          <td class="bluetxt">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Mobile No.:</td>
                          <td><?php echo $row_TClients['MobilePhone'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Personal No.:</td>
                          <td><?php echo $row_TClients['homephone'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Direct Line:</td>
                          <td><?php echo $row_TClients['workphone'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Extension:</td>
                          <td><?php echo $row_TClients['Extension'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Email:</td>
                          <td align="left"><?php echo $row_TClients['emeraddress'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Notes:</td>
                          <td><textarea name="staffrec" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TClients['staffrec'] ?></textarea></td>
                        </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" valign="top" class="titles">Address:</td>
                          <td width="322" align="left"><textarea name="BillingAddress" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TClients['BillingAddress'] ?></textarea></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">City:</td>
                          <td align="left"><?php echo $row_TClients['City'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Country:</td>
                          <td align="left"><?php echo $row_TClients['cntry'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">State:</td>
                          <td align="left" nowrap="nowrap"><?php echo $row_TClients['StateOrProvince'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="bluetxt">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Phone I:</td>
                          <td><?php echo $row_TClients['PhoneNumber'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Phone II:</td>
                          <td><?php echo $row_TClients['FaxNumber'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Phone III:</td>
                          <td><?php echo $row_TClients['emerphone'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Email:</td>
                          <td align="left"><?php echo $row_TClients['EmailAddress'] ?></td>
                        </tr>
                      </table>
                    </div>
<div class="TabbedPanelsContent">
  <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td class="titles">Currency:</td>
                          <td align="left"><?php echo $row_TClients['currencyname'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Cheque:</td>
                          <td align="left"><input type="checkbox" name="cheque" value="1" disabled="disabled" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Credit:</td>
                          <td align="left"><input type="checkbox" name="credit" value="1" disabled="disabled" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Account Balance:</td>
                          <td align="left"><?php echo $row_TClients['amtbal'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Credit Limit:</td>
                          <td align="left"><?php echo $row_TClients['creditlimit'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" nowrap="nowrap" class="titles">Discount:</td>
                          <td width="322" align="left"><?php echo $row_TClients['Discount'] ?></td>
                        </tr>
                        <tr>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                      </table>
                  </div>
<div class="TabbedPanelsContent">
  <iframe width="700" height="400" src="/accounts/ledger.php?id=<?php echo $row_TClients['VendorID']; ?><?php echo $vtype == 4 ? '&acc=1' : ''; ?>"></iframe>
</div>
<div class="TabbedPanelsContent">
  <iframe width="700" height="400" src="/operations/servsched/scheds.php?id=<?php echo $row_TClients['VendorID']; ?><?php echo $vtype == 4 ? '&acc=1' : ''; ?>"></iframe>
</div>
<div class="TabbedPanelsContent">
  <textarea name="Notes" style="width:450px" rows="10"><?php echo $row_TClients['Notes'] ?></textarea>
                </div>
                      <div class="TabbedPanelsContent">
                        <?php $doc_shelf = 'Clients'.DS.$vkey;
							$doc_id = $id; ?>
                        <?php include "../../scripts/viewdoc.php" ?>
                      </div>
                  </div>
                  </div></td>
</tr>
              <tr>
                <td colspan="2"></td>
              </tr>


            </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
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
var Tabs = new Spry.Widget.TabbedPanels("Details");
</script>
</body>
</html>
