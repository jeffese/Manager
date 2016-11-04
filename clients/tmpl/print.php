<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Clients'));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess('Clients', $vkey, 'Print');

$id = intval(_xget('id'));
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
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
                <td style="height:30px; min-width:460px; background-image:url(/images/lbl<?php echo $vpth?>.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">Info</td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="2" cellpadding="2">
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
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Info</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Client ID:</td>
                    <td width="322" class="red-normal"><b><?php echo $row_TClients['VendorID']; ?></b></td>
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
                    <td class="titles">Client Code:</td>
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
                        <td><?php echo $row_TClients['ContactTitle'] ?></td>
                      </tr>
                      <tr>
                        <td width="118" class="titles">First Name:</td>
                        <td><?php echo $row_TClients['ContactFirstName'] ?></td>
                      </tr>
                      <tr>
                        <td width="118" class="titles">Middle Name:</td>
                        <td><?php echo $row_TClients['ContactMidName'] ?></td>
                      </tr>
                      <tr>
                        <td width="118" class="titles">Last Name:</td>
                        <td><?php echo $row_TClients['ContactLastName'] ?></td>
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
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4" id="perstab">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Personal Info</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Gender:</td>
                    <td width="322"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input type="radio" name="sex" value="1" size="32" onclick="clientType(0, <?php echo $vtype ?>)" <?php if (!(strcmp($row_TClients['sex'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                        <td>Male</td>
                        <td><input type="radio" name="sex" value="2" size="32" onclick="clientType(1, <?php echo $vtype ?>)" <?php if (!(strcmp($row_TClients['sex'], 2))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                        <td>Fema
                          
                          le</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td align="right" class="titles">Disability:</td>
                    <td><?php echo $row_TClients['ability'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Marital Status:</td>
                    <td><script language="javascript" type="text/javascript">
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
                    <td><?php echo $row_TClients['ChildrenNames'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Interests:</td>
                    <td><?php echo $row_TClients['ContactsInterests'] ?></td>
                  </tr>
                </table>
                  <table width="100%" border="0" cellpadding="4" cellspacing="4" id="coytab">
                    <tr>
                      <td colspan="2" valign="top" class="h1">Contact Personnel</td>
                    </tr>
                    <tr>
                      <td width="120" class="titles">Job Title:</td>
                      <td width="322" align="left"><?php echo $row_TClients['signfile'] ?></td>
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
                      <td class="titles">Title:</td>
                      <td><?php echo $row_TClients['ContactTitle'] ?></td>
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
                      <td><?php echo $row_TClients['staffrec'] ?></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Contact Details</td>
                  </tr>
                  <tr>
                    <td width="120" valign="top" class="titles">Address:</td>
                    <td align="left"><?php echo $row_TClients['BillingAddress'] ?></td>
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
                    <td width="120" nowrap="nowrap" class="titles">Phone I:</td>
                    <td><?php echo $row_TClients['PhoneNumber'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">Phone II:</td>
                    <td><?php echo $row_TClients['FaxNumber'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">Phone III:</td>
                    <td><?php echo $row_TClients['emerphone'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Email:</td>
                    <td align="left"><?php echo $row_TClients['EmailAddress'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Account</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Currency:</td>
                    <td width="322" align="left"><?php echo $row_TClients['currencyname'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Cheque:</td>
                    <td align="left"><input type="checkbox" name="cheque" value="1" disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Credit:</td>
                    <td align="left"><input type="checkbox" name="credit" value="1" disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">Account Balance:</td>
                    <td align="left"><?php echo $row_TClients['amtbal'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">Credit Limit:</td>
                    <td align="left"><?php echo $row_TClients['creditlimit'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">Discount:</td>
                    <td align="left"><?php echo $row_TClients['Discount'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="2" cellspacing="2">
                  <tr>
                    <td class="h1">Notes</td>
                  </tr>
                  <tr>
                    <td><?php echo $row_TClients['Notes']; ?></td>
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
<script language="JavaScript1.2" src="/clients/tmpl/script.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    clientType(<?php echo $row_TClients['ClientType'], ',', $vtype; ?>);
	print();
});
</script>
</body>
</html>