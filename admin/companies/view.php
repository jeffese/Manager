<?php 
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Administration'));
$access = _xvar_arr_sub($_access, array('Company Info'));
vetAccess('Administration', 'Company Info', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);

$id = $_SESSION['coyid'];
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","edit.php?id=$id","","","","","","","");
$rec_status = 1;

$FormAction = getformaction();
$pixrnd = _xses('pixrnd');

$sql = sprintf("SELECT `coyinfo`.*, `country`.`country` AS cntry, `currencyname`, `CompanyName` AS cash 
    FROM `%s`.`coyinfo` 
    LEFT JOIN `%s`.`country` ON `coyinfo`.`country`=`country`.`country_id` 
    LEFT JOIN `%s`.`currencies` ON `coyinfo`.`currency`=`currencies`.`cur_id` 
    LEFT JOIN `%s`.`vendors` ON `coyinfo`.`CashAccount`=`vendors`.`VendorID` 
    WHERE `coyinfo`.`CoyID`=%s", 
        DB_NAME, DB_NAME, 
        $_SESSION['DBCoy'], 
        $_SESSION['DBCoy'], 
        $_SESSION['coyid']);
$row_Tcoy = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="240" valign="top"><img src="/images/newcoy.png" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; background-image:url(/images/<?php echo COY=="School" ? "schinfo" : "coyinfo" ?>.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1"><?php echo COY ?></td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                    <tr>
                      <td width="120" class="titles"><?php echo COY ?> Name:</td>
                      <td width="285" align="left"><?php echo $row_Tcoy['CoyName'] ?></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles">Slogan:</td>
                      <td align="left"><?php echo $row_Tcoy['Slogan'] ?></td>
                    </tr>
                    
                    <tr>
                      <td width="120" valign="top" class="titles">Logo:</td>
                      <td align="left"><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/logo/xxpix.jpg".$pixrnd ?>" /></td>
                    </tr>
                    <tr>
                      <td valign="top" class="titles">Official Signature:</td>
                      <td align="left"><img src="<?php echo (($row_Tcoy['officesign']=='') ? '/images/noimage.jpg' : COYPIX_DIR.$_SESSION['coyid']."/signature/xpix.jpg".$pixrnd); ?>" alt="" name="signpix" id="signpix" /></td>
                    </tr>
                    <tr>
                      <td valign="top" class="titles">&nbsp;</td>
                      <td align="left">&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td><div id="Tabs" class="TabbedPanels">
                  <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0" id="addr">Contact Details</li>
                    <li class="TabbedPanelsTab" tabindex="1">Program Settings</li>
                    <li class="TabbedPanelsTab" tabindex="2" id="acc"<?php if ($_SESSION['license']['Accounts']['View']!=1)  {?> style="display:none"<?php } ?>>Accounts Settings</li>
                    <li class="TabbedPanelsTab" tabindex="3" id="exood"<?php if (X_LOCAL_HOST==1)  {?> style="display:none"<?php } ?>>Exood Account Settings</li>
                    <li class="TabbedPanelsTab" tabindex="0">Documents</li>
</ul>
                  <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" valign="top" class="titles">Address:</td>
                          <td width="322" align="left"><textarea name="viewaddress" rows="3" id="viewaddress" style="width:200px" disabled="disabled"><?php echo $row_Tcoy['Address'] ?></textarea></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">City:</td>
                          <td align="left"><?php echo $row_Tcoy['City'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Country:</td>
                          <td align="left" valign="baseline"><?php echo $row_Tcoy['cntry'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">State:</td>
                          <td align="left" valign="baseline" nowrap="nowrap"><?php echo $row_Tcoy['State'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="bluetxt">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Telephone:</td>
                          <td align="left"><?php echo $row_Tcoy['Tel'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Email:</td>
                          <td align="left"><?php echo $row_Tcoy['Email'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Website:</td>
                          <td align="left"><?php echo $row_Tcoy['Web'] ?></td>
                        </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table width="100%" border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td bgcolor="#0099FF" class="titles">Email Account:</td>
                          <td align="left"><?php echo $row_Tcoy['admin_mail'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" bgcolor="#0099FF" class="titles">Password:</td>
                          <td align="left">********** </td>
                        </tr>
                        <tr>
                          <td width="120" bgcolor="#0099FF" class="titles">SMTP Server:</td>
                          <td align="left"><?php echo $row_Tcoy['smtp'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" bgcolor="#0099FF" class="titles">SMTP Port:</td>
                          <td align="left"><?php echo $row_Tcoy['smtp_port'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" bgcolor="#0099FF" class="titles">Authentication:</td>
                          <td align="left"><input type="checkbox" name="smtp_auth" id="smtp_auth" <?php if ($row_Tcoy['smtp_auth']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                        </tr>
                        <?php if ($_SESSION['accesskeys']['Academics']['View'] == 1) { ?>
                        <tr>
                          <td bgcolor="#339900" class="titles">Active Directory:</td>
                          <td align="left"><input type="checkbox" name="ad_auth" id="ad_auth" <?php if ($row_Tcoy['ad_auth']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                        </tr>
                        <tr>
                          <td bgcolor="#339900" class="titles">Domain Controller:</td>
                          <td align="left"><?php echo $row_Tcoy['ad_host'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" bgcolor="#339900" class="titles">LDAP User Account:</td>
                          <td align="left"><?php echo $row_Tcoy['ad_user'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" bgcolor="#339900" class="titles">Password:</td>
                          <td align="left">********** </td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Is School:</td>
                          <td align="left"><input type="checkbox" name="isschool" id="isschool" <?php if ($row_Tcoy['isschool']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                        </tr>
                        <?php } ?>
                        <tr>
                          <td class="titles">Kiosk Mode:</td>
                          <td align="left"><input type="checkbox" name="kiosk" id="kiosk" <?php if ($row_Tcoy['kiosk']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                        </tr>
                        <?php if ($_SESSION['accesskeys']['Administration']['Traffic Control']['View'] == 1) { ?>
                        <tr>
                          <td class="titles">Gateway Server:</td>
                          <td align="left"><?php echo $row_Tcoy['gateway'] ?></td>
                        </tr>
                        <?php } ?>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table width="100%" border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td class="titles"><?php echo COY ?> Currency:</td>
                          <td align="left"><?php echo $row_Tcoy['currencyname'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Default Account:</td>
                          <td align="left" class="blacktxt"><?php echo $row_Tcoy['cash'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Default Tax Rate:</td>
                          <td align="left" class="blacktxt"><?php echo $row_Tcoy['Tax'] ?></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td align="left" class="blacktxt"><input type="checkbox" name="viewstore" id="viewstore" <?php if ($row_Tcoy['UseStore']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" />
                            Use Store Facilities</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td align="left" class="blacktxt"><input type="checkbox" name="viewpos" id="viewpos" <?php if ($row_Tcoy['UsePOSPrinter']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" />
                            Use POS Printer</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td align="left" class="blacktxt"><input type="checkbox" name="viewautosale" id="viewautosale" <?php if ($row_Tcoy['AutoSell']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" />
                            Automatic New Sale</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td align="left" class="blacktxt"><input type="checkbox" name="viewautoprint" id="viewautoprint" <?php if ($row_Tcoy['AutoReceipt']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" />
                            Automatic Receipt Printout after Sale</td>
                        </tr>
                        <tr>
                          <td valign="top" class="bluetxt">&nbsp;</td>
                          <td align="left"><span class="blacktxt">
                            <input type="checkbox" name="viewnegstock" value="" <?php if ($row_Tcoy['negstock']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" />
                            Allow Negative Stock</span></td>
                        </tr>
                        <tr>
                          <td valign="top" class="bluetxt">&nbsp;</td>
                          <td align="left"><span class="blacktxt">
                            <input type="checkbox" name="viewnegcash" value="" <?php if ($row_Tcoy['negcash']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" />
                            Allow Negative Cash Transactions</span></td>
                        </tr>
                        <tr>
                          <td valign="top" class="bluetxt">&nbsp;</td>
                          <td align="left"><span class="blacktxt">
                            <input type="checkbox" name="viewtrans" id="viewtrans" value="" <?php if ($row_Tcoy['securetransfer']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" />
                            Require Permission for Stock Transfer</span></td>
                        </tr>
                        <tr>
                          <td width="120" valign="top" class="titles">Receipt Comment:</td>
                          <td align="left"><textarea name="viewcomment" rows="3" id="viewcomment" style="width:200px" disabled="disabled"><?php echo $row_Tcoy['ReceiptComment'] ?></textarea></td>
                        </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table width="100%" border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" class="titles"><?php echo COY ?> ID:</td>
                          <td align="left"><?php echo $row_Tcoy['ExoodCoyID'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Office/Shop ID:</td>
                          <td align="left"><?php echo $row_Tcoy['ExoodAddrID'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Username:</td>
                          <td align="left"><?php echo $row_Tcoy['ExoodID'] ?></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Password:</td>
                          <td align="left">**********</td>
                        </tr>
                        <tr>
                          <td width="120" align="right">&nbsp;</td>
                          <td align="left" class="blacktxt"><input type="checkbox" name="viewupdate" id="viewupdate" <?php if ($row_Tcoy['AutoExoodUpdate']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" />
                            Automatic Online Products &amp; Services Updates</td>
                        </tr>
                        <tr>
                          <td align="right">&nbsp;</td>
                          <td align="left" class="blacktxt"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <td colspan="2" align="left" class="h1">Update Intervals (Minutes)</td>
                            </tr>
                            <tr>
                              <td width="41%" height="32" class="titles">Internet Update:</td>
                              <td width="59%"><?php echo $row_Tcoy['UpdateTime'] ?></td>
                            </tr>
                            <tr>
                              <td class="titles">Linked Database:</td>
                              <td><?php echo $row_Tcoy['LinkRefresh'] ?></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <?php $doc_shelf = 'Company';
							$doc_id = $id; ?>
                      <?php include '../../scripts/viewdoc.php' ?>
                    </div>
</div>
                </div></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
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
var Tabs = new Spry.Widget.TabbedPanels("Tabs");
</script>
</body>
</html>
