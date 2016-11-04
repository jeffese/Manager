<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Administration'));
$access = _xvar_arr_sub($_access, array('Company Info'));
vetAccess('Administration', 'Company Info', 'Edit');

$id = $_SESSION['coyid'];
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmcoy","","view.php","","","","");
$rec_status = 3;

$FormAction = getformaction();

$doc_shelf = 'Company';
$doc_id = $id;

$error = '';
if (_xpost('MM_coy')=='company') {

	$logo = newpix(ROOT . COYPIX_DIR, '', 'logo', 1, array(600, 200, 100, 60));
	$sign = newpix(ROOT . COYPIX_DIR, '', 'signature', 1, array(200, 100, 40), 'officesign');
	
	$sql = sprintf("UPDATE `%s`.`coyinfo` SET `CoyName`=%s, `Address`=%s, `City`=%s, `State`=%s, 
            `Country`=%s, `Tel`=%s, `Web`=%s, `Email`=%s, `Slogan`=%s, `logo`='%s', `ReceiptComment`=%s, 
            `AutoSell`=%s, `AutoReceipt`=%s, `UseStore`=%s, `UsePOSPrinter`=%s, `currency`=%s, 
            `CashAccount`=%s, `officesign`='%s', `negstock`=%s, `negcash`=%s, `securetransfer`=%s, 
            `Tax`=%s, `ExoodID`=%s, `Exoodpass`=%s, `ExoodCoyID`=%s, `ExoodAddrID`=%s, 
            `AutoExoodUpdate`=%s, `LinkRefresh`=%s, `UpdateTime`=%s, `admin_mail`=%s, `email_pass`=%s, 
            `smtp`=%s, `smtp_port`=%s, `smtp_auth`=%s, `ad_auth`=%s, `ad_host`=%s, `ad_user`=%s, `ad_pass`=%s, 
            `isschool`=%s, `gateway`=%s, `kiosk`=%s WHERE `CoyID`=%s",
		DB_NAME,
		GSQLStr(_xpost('company'), "text"),
		GSQLStr(_xpost('address'), "text"),
		GSQLStr(_xpost('city'), "text"),
		GSQLStr(_xpost('state'), "text"),
		GSQLStr(_xpost('country'), "int"),
		GSQLStr(_xpost('tel'), "text"),
		GSQLStr(_xpost('web'), "text"),
		GSQLStr(_xpost('email'), "text"),
		GSQLStr(_xpost('slogan'), "text"),
		$logo['pixcode'],
		GSQLStr(_xpost('comment'), "text"),
		_xpostchk('autosale'),
		_xpostchk('autoprint'),
		_xpostchk('store'),
		_xpostchk('pos'),
		GSQLStr(_xpost('currency'), "int"),
		GSQLStr(_xpost('cash'), "int"),
		$sign['pixcode'],
		_xpostchk('negstock'),
		_xpostchk('negcash'),
		_xpostchk('securetransfer'),
		GSQLStr(_xpost('tax'), "double"),
		GSQLStr(_xpost('username'), "text"),
		GSQLStr(_xpost('password'), "text"),
		GSQLStr(_xpost('coyid'), "int"),
		GSQLStr(_xpost('shopid'), "int"),
		_xpostchk('update'),
		GSQLStr(_xpost('linkdb'), "int"),
		GSQLStr(_xpost('internetdb'), "int"),
		GSQLStr(_xpost('admin_mail'), "text"),
		GSQLStr(_xpost('email_pass'), "text"),
		GSQLStr(_xpost('smtp'), "text"),
		GSQLStr(_xpost('smtp_port'), "int"),
		_xpostchk('smtp_auth'),
                _xpostchk('ad_auth'),
		GSQLStr(_xpost('ad_host'), "text"),
		GSQLStr(_xpost('ad_user'), "text"),
		GSQLStr(_xpost('ad_pass'), "text"),
                _xpostchk('isschool'),
                GSQLStr(_xpost('gateway'), "text"),
                _xpostchk('kiosk'),
		$_SESSION['coyid']
		);
	$update = runDBQry($dbh, $sql);
	docs($doc_shelf, $doc_id);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT country_id, country FROM `" . DB_NAME . "`.`country` ORDER BY country";
$row_Tcountry = getDBData($dbh, $sql);

$sql = "SELECT `state` FROM `" . DB_NAME . "`.`state` ORDER BY `state`";
$row_Tstate = getDBData($dbh, $sql);

$sql = "SELECT cur_id, currencyname FROM `{$_SESSION['DBCoy']}`.`currencies` ORDER BY cur_id";
$row_Tcurrency = getDBData($dbh, $sql);

$row_Tcash = getVendor(4);

$sql = "SELECT `coyinfo`.* FROM `" . DB_NAME . "`.`coyinfo` WHERE `coyinfo`.`CoyID`={$_SESSION['coyid']}";
$row_Tcoy = getDBDataRow($dbh, $sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["company", "", 
            ["req", "Enter your <?php echo COY ?> Name"]],
        ["address", "if=$('#addr').click()", 
            ["req", "Enter your company address"]],
        ["city", "", 
            ["req", "Enter the city"]],
        ["country", "", 
            ["req", "Enter the country"]],
        ["state", "", 
            ["req", "Enter the state"]]
    <?php if ($_SESSION['license']['Accounts']['View'] == 1) { ?>,
        ["currency", "if=$('#acc').click()", 
                ["req", "Select <?php echo COY ?> Default Currency"]]
    <?php } ?>
    <?php if (X_LOCAL_HOST == 1) { ?>,
        ["coyid", "if=$('#exood').click()", 
            ["num", "This is a Numeric Field"]],
        ["shopid", "", 
            ["num", "This is a Numeric Field"]],
        ["internetdb", "", 
            ["num", "This is a Numeric Field"]],
        ["linkdb", "", 
            ["num", "This is a Numeric Field"]]
    <?php } ?>
    ]
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script><script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
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
            <td align="right"><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table><form action="<?php echo $FormAction ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmcoy" id="frmcoy">
              <table width="100%" border="0" cellspacing="4" cellpadding="4">
                <tr>
                  <td class="h1"><?php echo COY ?></td>
                </tr>
                <tr>
                  <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                    <tr>
                      <td></td>
                      <td align="center"><?php echo catch_error($errors) ?></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles"><?php echo COY ?> Name:</td>
                        <td align="left"><input type="text" name="company" id="company" style="width:200px" value="<?php echo $row_Tcoy['CoyName'] ?>" />
                        <img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
                      </tr>
                    <tr>
                      <td width="120" class="titles">Slogan:</td>
                        <td align="left"><input type="text" name="slogan" id="slogan" style="width:300px" value="<?php echo $row_Tcoy['Slogan'] ?>" /></td>
                      </tr>
                    <tr>
                      <td class="bluetxt">&nbsp;</td>
                        <td align="left"><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/logo/xxpix.jpg{$_SESSION['pixrnd']}" ?>" /></td>
                      </tr>
                    <tr>
                      <td width="120" class="titles">Logo:</td>
                      <td align="left"><input type="file" name="picture" id="picture" /></td>
                    </tr>
                    <tr>
                      <td class="bluetxt">&nbsp;</td>
                      <td align="left"><img src="<?php echo (($row_Tcoy['officesign']=='') ? '/images/noimage.jpg' : COYPIX_DIR . $_SESSION['coyid']."/signature/xpix.jpg{$_SESSION['pixrnd']}"); ?>" alt="" name="signpix" id="signpix" /></td>
                    </tr>
                    <tr>
                      <td class="titles">Official Signature:</td>
                      <td align="left"><input type="file" name="officesign" id="officesign" /></td>
                    </tr>
                    <tr>
                      <td class="titles">&nbsp;</td>
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
                            <td width="322" align="left"><textarea name="address" rows="3" id="address" style="width:200px"><?php echo $row_Tcoy['Address'] ?></textarea>
                              <img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">City:</td>
                            <td align="left"><input type="text" name="city" id="city" style="width:200px" value="<?php echo $row_Tcoy['City'] ?>" />
                              <img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">Country:</td>
                            <td align="left" valign="baseline"><select name="country" id="country" onchange="if (this.value==154){cmb='block'; txt='none';} else {txt='block'; cmb='none';}{this.form.cmbsta.style.display=cmb; this.form.state.style.display=txt;}">
                              <?php for ($i=0; $i<count($row_Tcountry); $i++) { ?>
                              <option value="<?php echo $row_Tcountry[$i]['country_id']?>" <?php if (!(strcmp($row_Tcoy['Country'],$row_Tcountry[$i]['country_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tcountry[$i]['country']?></option>
                              <?php } ?>
                            </select>
                              <img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">State:</td>
                            <td align="left" valign="baseline" nowrap="nowrap"><select name="cmbsta" id="cmbsta" onchange="this.form.state.value=this.value" style="display: <?php echo ($row_Tcoy['Country']==154)? 'block': 'none' ?>">
                              <?php for ($i=0; $i<count($row_Tstate); $i++) { ?>
                              <option value="<?php echo $row_Tstate[$i]['state']?>" <?php if (!(strcmp($row_Tcoy['State'],$row_Tcountry[$i]['country_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tstate[$i]['state']?></option>
                              <?php } ?>
                            </select>
                              <input name="state" type="text" size="25" value="<?php echo $row_Tcoy['State'] ?>" style="display: <?php echo ($row_Tcoy['Country']==154)? 'none': 'block' ?>" />
                              <img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
                          </tr>
                          <tr>
                            <td width="120" class="bluetxt">&nbsp;</td>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">Telephone:</td>
                            <td align="left"><input type="text" name="tel" id="tel" style="width:200px" value="<?php echo $row_Tcoy['Tel'] ?>" /></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">Email:</td>
                            <td align="left"><input type="text" name="email" id="email" style="width:200px" value="<?php echo $row_Tcoy['Email'] ?>" /></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">Website:</td>
                            <td align="left"><input type="text" name="web" id="web" style="width:200px" value="<?php echo $row_Tcoy['Web'] ?>" /></td>
                          </tr>
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <table width="100%" border="0" cellspacing="4" cellpadding="4">
                          <tr>
                            <td bgcolor="#0099FF" class="titles">Email Account:</td>
                            <td align="left"><input name="admin_mail" type="text" id="admin_mail" style="width:250px" value="<?php echo $row_Tcoy['admin_mail'] ?>" /></td>
                          </tr>
                          <tr>
                            <td width="120" bgcolor="#0099FF" class="titles">Password:</td>
                            <td align="left"><input name="email_pass" type="password" id="email_pass" value="<?php echo $row_Tcoy['email_pass'] ?>" size="25" /></td>
                          </tr>
                          <tr>
                            <td width="120" bgcolor="#0099FF" class="titles">SMTP Server:</td>
                            <td align="left"><input name="smtp" type="text" id="smtp" style="width:200px" value="<?php echo $row_Tcoy['smtp'] ?>" /></td>
                          </tr>
                          <tr>
                            <td width="120" bgcolor="#0099FF" class="titles">SMTP Port:</td>
                            <td align="left"><input name="smtp_port" type="text" id="smtp_port" style="width:70px" value="<?php echo $row_Tcoy['smtp_port'] ?>" /></td>
                          </tr>
                          <tr>
                            <td width="120" bgcolor="#0099FF" class="titles">Authentication:</td>
                            <td align="left"><input type="checkbox" name="smtp_auth" id="smtp_auth" <?php if ($row_Tcoy['smtp_auth']==1) {echo "checked=\"checked\"";} ?> /></td>
                          </tr>
                          <tr>
                            <td bgcolor="#339900" class="titles">Active Directory:</td>
                            <td align="left"><input type="checkbox" name="ad_auth" id="ad_auth" <?php if ($row_Tcoy['ad_auth']==1) {echo "checked=\"checked\"";} ?> /></td>
                          </tr>
                          <tr>
                            <td bgcolor="#339900" class="titles">Domain Controller:</td>
                            <td align="left"><span class="blacktxt">
                              <input type="text" name="ad_host" id="ad_host" style="width:200px" value="<?php echo $row_Tcoy['ad_host'] ?>" />
                            </span></td>
                          </tr>
                          <tr>
                            <td bgcolor="#339900" class="titles">LDAP User Account:</td>
                            <td align="left"><span class="blacktxt">
                              <input type="text" name="ad_user" id="ad_user" style="width:200px" value="<?php echo $row_Tcoy['ad_user'] ?>" />
                            </span></td>
                          </tr>
                          <tr>
                            <td bgcolor="#339900" class="titles">Password:</td>
                            <td align="left"><span class="blacktxt">
                              <input name="ad_pass" type="password" style="width:200px" value="<?php echo $row_Tcoy['ad_pass'] ?>" />
                            </span></td>
                          </tr>
                          <?php if ($_SESSION['accesskeys']['Academics']['View'] == 1) { ?>
                          <tr>
                            <td width="120" class="titles">Is School:</td>
                            <td align="left"><input type="checkbox" name="isschool" id="isschool" <?php if ($row_Tcoy['isschool']==1) {echo "checked=\"checked\"";} ?> disabled="disabled" /></td>
                          </tr>
                          <?php } ?>
                          <tr>
                            <td class="titles">Kiosk Mode:</td>
                            <td align="left"><input type="checkbox" name="kiosk" id="kiosk" <?php if ($row_Tcoy['kiosk']==1) {echo "checked=\"checked\"";} ?> /></td>
                          </tr>
                          <?php if ($_SESSION['accesskeys']['Administration']['Traffic Control']['View'] == 1) { ?>
                          <tr>
                            <td class="titles">Gateway Server:</td>
                            <td align="left"><input name="gateway" type="text" id="gateway" style="width:150px" value="<?php echo $row_Tcoy['gateway'] ?>" /></td>
                          </tr>
                          <?php } ?>
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <table width="100%" border="0" cellspacing="4" cellpadding="4">
                          <tr>
                            <td class="titles"><?php echo COY ?> Currency:</td>
                            <td align="left"><select name="currency" id="currency">
                              <?php for ($i=0; $i<count($row_Tcurrency); $i++) { ?>
                              <option value="<?php echo $row_Tcurrency[$i]['cur_id']?>" <?php if (!(strcmp($row_Tcoy['currency'],$row_Tcurrency[$i]['cur_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tcurrency[$i]['currencyname']?></option>
                              <?php } ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td class="titles">Default Account:</td>
                            <td align="left"><select name="cash" id="cash">
                              <?php for ($i=0; $i<count($row_Tcash); $i++) { ?>
                              <option value="<?php echo $row_Tcash[$i]['VendorID']?>" <?php if (!(strcmp($row_Tcoy['CashAccount'],$row_Tcash[$i]['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tcash[$i]['VendorName']?></option>
                              <?php } ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td class="titles">Default Tax Rate:</td>
                            <td align="left"><input type="text" name="tax" id="tax" style="width:50px" value="<?php echo $row_Tcoy['Tax'] ?>" onchange="this.value=setnum(this.value);" /></td>
                          </tr>
                          <tr>
                            <td width="120">&nbsp;</td>
                            <td align="left" class="bluetxt"><input type="checkbox" name="store" id="store" <?php if ($row_Tcoy['UseStore']==1) {echo "checked=\"checked\"";} ?> />
                              Use Store Facilities</td>
                          </tr>
                          <tr>
                            <td width="120">&nbsp;</td>
                            <td align="left" class="bluetxt"><input type="checkbox" name="pos" id="pos" <?php if ($row_Tcoy['UsePOSPrinter']==1) {echo "checked=\"checked\"";} ?> />
                              Use POS Printer</td>
                          </tr>
                          <tr>
                            <td width="120">&nbsp;</td>
                            <td align="left" class="bluetxt"><input type="checkbox" name="autosale" id="autosale" <?php if ($row_Tcoy['AutoSell']==1) {echo "checked=\"checked\"";} ?> />
                              Automatic New Sale</td>
                          </tr>
                          <tr>
                            <td width="120">&nbsp;</td>
                            <td align="left" class="bluetxt"><input type="checkbox" name="autoprint" id="autoprint"<?php if ($row_Tcoy['AutoReceipt']==1) {echo "checked=\"checked\"";} ?> />
                              Automatic Receipt Printout after Sale</td>
                          </tr>
                          <tr>
                            <td valign="top" class="bluetxt">&nbsp;</td>
                            <td align="left" class="bluetxt"><input type="checkbox" name="negstock" id="negstock" <?php if ($row_Tcoy['negstock']==1) {echo "checked=\"checked\"";} ?> />
                              Allow Negative Stock</td>
                          </tr>
                          <tr>
                            <td valign="top" class="bluetxt">&nbsp;</td>
                            <td align="left" class="bluetxt"><input type="checkbox" name="negcash" id="negcash" <?php if ($row_Tcoy['negcash']==1) {echo "checked=\"checked\"";} ?> />
                              Allow Negative Cash Transactions</td>
                          </tr>
                          <tr>
                            <td valign="top" class="bluetxt">&nbsp;</td>
                            <td align="left" class="bluetxt"><input type="checkbox" name="securetransfer" id="securetransfer" <?php if ($row_Tcoy['securetransfer']==1) {echo "checked=\"checked\"";} ?> />
                              Require Permission for Stock Transfer</td>
                          </tr>
                          <tr>
                            <td width="120" valign="top" class="titles">Receipt Comment:</td>
                            <td align="left"><textarea name="comment" rows="3" id="comment" style="width:200px"><?php echo $row_Tcoy['ReceiptComment'] ?></textarea></td>
                          </tr>
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <table width="100%" border="0" cellspacing="4" cellpadding="4">
                          <tr>
                            <td width="120" class="titles"><?php echo COY ?> ID:</td>
                            <td align="left"><input type="text" name="coyid" id="coyid" style="width:200px" value="<?php echo $row_Tcoy['ExoodCoyID'] ?>" /></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">Office/Shop ID:</td>
                            <td align="left"><input type="text" name="shopid" id="shopid" style="width:200px" value="<?php echo $row_Tcoy['ExoodAddrID'] ?>" /></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">Username:</td>
                            <td align="left"><input type="text" name="username" id="username" style="width:200px" value="<?php echo $row_Tcoy['ExoodID'] ?>" /></td>
                          </tr>
                          <tr>
                            <td width="120" class="titles">Password:</td>
                            <td align="left"><input type="password" name="password" id="password" style="width:200px" value="<?php echo $row_Tcoy['Exoodpass'] ?>" /></td>
                          </tr>
                          <tr>
                            <td width="120">&nbsp;</td>
                            <td align="left" class="blacktxt"><input type="checkbox" name="update" id="update" value="" <?php if ($row_Tcoy['AutoExoodUpdate']==1) {echo "checked=\"checked\"";} ?> />
                              Automatic Online Products &amp; Services Updates</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td align="left" class="blacktxt"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                              <tr>
                                <td colspan="2" align="left">Update Intervals (Minutes)</td>
                              </tr>
                              <tr>
                                <td width="41%" height="32" align="right">Internet Update:</td>
                                <td width="59%"><input type="text" name="internetdb" id="internetdb" style="width:50px" value="<?php echo $row_Tcoy['UpdateTime'] ?>" /></td>
                              </tr>
                              <tr>
                                <td align="right">Linked Database:</td>
                                <td><input type="text" name="linkdb" id="linkdb" style="width:50px" value="<?php echo $row_Tcoy['LinkRefresh'] ?>" /></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table>
                      </div>
                      <div class="TabbedPanelsContent">
                        <?php include '../../scripts/editdoc.php' ?>
                      </div>
                    </div>
                  </div></td>
                </tr>
              </table>
              
              <table width="100%" border="0" cellspacing="4" cellpadding="4">
                <tr>
                  <td><?php include('../../scripts/buttonset.php')?>
                  <input name="MM_coy" type="hidden" id="MM_coy" value="company" /><input name="key" type="hidden" id="key" value="<?php echo $row_Tcoy['CoyID'] ?>" /></td>
                </tr>
              </table>
        </form></td>
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
