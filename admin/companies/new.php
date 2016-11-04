<?php
require_once('../../scripts/init.php');

if ($_SERVER['REMOTE_ADDR'] = '127.0.0.1') {
    $_SESSION['newcompany'] = '1';
} else {
    header("Location: ../../relogin.htm");
    exit;
}

$FormAction = getformaction();

$error = '';
if (_xpost('MM_newcoy')=='company') {
    $sql = sprintf("INSERT INTO `" . DB_NAME . "`.`coyinfo` ( `CoyName`, `Address`, `City`, `State`, `Country`, 
        `Tel`, `Web`, `Email`, `Slogan`, `ReceiptComment`, `AutoSell`, `AutoReceipt`, `UseStore`, `UsePOSPrinter`, 
        `currency`, `CashAccount`, `officesign`, `negstock`, `negcash`, `securetransfer`, `Tax`, 
        `ExoodID`, `Exoodpass`, `ExoodCoyID`, `ExoodAddrID`, `AutoExoodUpdate`, `LinkRefresh`, `UpdateTime`, `admin_mail`, 
        `email_pass`, `smtp`, `smtp_port`, `smtp_auth`, `ad_auth`, `ad_host`, `ad_user`, `ad_pass`, `isschool`, `gateway`, `kiosk`) 
        VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 
        %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
               GSQLStr(_xpost('company'), "text"),
               GSQLStr(_xpost('address'), "text"),
               GSQLStr(_xpost('city'), "text"),
               GSQLStr(_xpost('state'), "text"),
               GSQLStr(_xpost('country'), "int"),
               GSQLStr(_xpost('tel'), "text"),
               GSQLStr(_xpost('web'), "text"),
               GSQLStr(_xpost('email'), "text"),
               GSQLStr(_xpost('slogan'), "text"),
               GSQLStr(_xpost('comment'), "text"),
               _xpostchk('autosale'),
               _xpostchk('autoprint'),
               _xpostchk('store'),
               _xpostchk('pos'),
               GSQLStr(_xpost('currency'), "int"),
		GSQLStr(_xpost('cash'), "int"),
                "''",
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
                _xpostchk('kiosk'));
    runDBQry($dbh, $sql);
    $recid = mysqli_insert_id($dbh);
    $_SESSION['coyid'] = $recid;

    $logo = newpix(ROOT . COYPIX_DIR, '', 'logo', 1, array(600, 200, 100, 60));
    $sign = newpix(ROOT . COYPIX_DIR, '', 'signature', 1, array(200, 100, 40), 'officesign');

    if ($logo['pixcode'] != '' || $sign['pixcode'] != '') {
        $sql = sprintf("UPDATE `" . DB_NAME . "`.`coyinfo` SET `logo` = %s, `officesign`=%s 
            WHERE `CoyID` = %s", $logo['pixcode'], $sign['pixcode'], $recid);
        runDBQry($dbh, $sql);
    }

    $_SESSION['tmpcur'] = GSQLStr(_xpost('currency'), "int");
    $_SESSION['COY']['CoyName'] = _xpost('company');
    $_SESSION['scriptfile'] = '/admin/companies/newcoydb.php';
    $_SESSION['msg'] = 'Creating Database for ' . $_SESSION['COY']['CoyName'] . ' ..';
    header("Location: progress.php");
    exit;
}

$sql = "SELECT country_id, country FROM `" . DB_NAME . "`.`country` ORDER BY country";
$row_Tcountry = getDBData($dbh, $sql);

$sql = "SELECT `state` FROM `" . DB_NAME . "`.`state` ORDER BY `state`";
$row_Tstate = getDBData($dbh, $sql);

$sql = "SELECT cur_id, currencyname FROM `" . DB_NAME . "`.`currencies` ORDER BY cur_id";
$row_Tcurrency = getDBData($dbh, $sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
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
<script type="text/javascript" src="/scripts/js/set.js"></script>
<script type="text/javascript"> 
<!--
window.onload = function() {
	setContent();
}
window.onresize = function() {
	setContent();
}

//--> 
</script>
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
            ["req", "Enter the state"]],
        ["currency", "if=$('#acc').click()", 
            ["req", "Select <?php echo COY ?> Default Currency"]]
<?php if (X_LOCAL_HOST == 1) { ?>,
        ["coyid", "if=$('#exood').click()", 
            ["num", "This is a Numeric Field"]],
        ["shopid", "", 
            ["num", "This is a Numeric Field"]]
    <?php } ?>
    ];
    
    function vetFrm() {
        if (vetform(1,0,1,'Select your company logo') && validateFormPop(arrFormValidation))
            document.frmnewcoy.submit();
    }
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
      <td bgcolor="#FFFFFF"><table width="720" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="headerlogo"></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="240" valign="top"><img src="/images/newcoy.png" width="240" height="300" /></td>
              <td valign="top"><form action="<?php echo $FormAction ?>" method="post" enctype="multipart/form-data" name="frmnewcoy" id="frmnewcoy">
                <table width="100%" border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td style="height:30px; background-image:url(/images/newcoylabel.png); background-repeat:no-repeat">&nbsp;</td>
                    </tr>
                  <tr>
                    <td class="h1" height="5px"></td>
                    </tr>
                  </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
  
  <tr>
    <td><table border="0" cellpadding="4" cellspacing="4">
      <tr>
        <td></td>
        <td align="center"><?php echo catch_error($errors) ?></td>
      </tr>
      <tr>
        <td width="120" align="right" class="bluetxt"><?php echo COY ?> Name:<img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
        <td width="285" align="left"><input type="text" name="company" id="company" style="width:200px" /></td>
        </tr>
      <tr>
        <td width="120" align="right" class="bluetxt">Slogan:</td>
        <td align="left"><input type="text" name="slogan" id="slogan" style="width:300px" /></td>
        </tr>
      <tr>
        <td width="120" align="right" class="bluetxt">Logo:<img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
        <td align="left" nowrap="nowrap"><input type="file" name="picture" id="picture" width="20" />
          </td>
        </tr>
      <tr>
        <td align="right" class="bluetxt">Official Signature:</td>
        <td align="left"><input type="file" name="officesign" id="officesign" /></td>
      </tr>
      </table></td>
    </tr>
  <tr>
    <td><div id="Tabs" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0" id="addr">Contact Details</li>
        <li class="TabbedPanelsTab" tabindex="1">Program Settings</li>
        <li class="TabbedPanelsTab" tabindex="2" id="acc">Accounts Settings</li>
        <li class="TabbedPanelsTab" tabindex="3" id="exood"<?php if (X_LOCAL_HOST==1)  {?> style="display:none"<?php } ?>>Exood Account Settings</li>
        </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent"><table border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td width="120" align="right" valign="top" class="bluetxt">Address:<img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
            <td width="322" align="left"><textarea name="address" rows="3" id="address" style="width:200px"></textarea></td>
            </tr>
          <tr>
            <td width="120" align="right" class="bluetxt">City:<img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
            <td align="left"><input type="text" name="city" id="city" style="width:200px" /></td>
            </tr>
          <tr>
            <td width="120" align="right" class="bluetxt">Country:<img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
            <td align="left" valign="baseline"><select name="country" id="country" onchange="if (this.value==154){cmb='block'; txt='none';} else {txt='block'; cmb='none';}{this.form.cmbsta.style.display=cmb; this.form.state.style.display=txt;}">
              <option value=""></option>
              <?php for ($i=0; $i<count($row_Tcountry); $i++) { ?>
              <option value="<?php echo $row_Tcountry[$i]['country_id']?>"><?php echo $row_Tcountry[$i]['country']?></option>
              <?php } ?>
              </select></td>
            </tr>
          <tr>
            <td width="120" align="right" class="bluetxt">State:<img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
            <td align="left" valign="baseline" nowrap="nowrap"><select name="cmbsta" id="cmbsta" onchange="this.form.state.value=this.value" style="display: none">
              <option value=""></option>
              <?php for ($i=0; $i<count($row_Tstate); $i++) { ?>
              <option value="<?php echo $row_Tstate[$i]['state']?>"><?php echo $row_Tstate[$i]['state']?></option>
              <?php } ?>
              </select>
              <input name="state" type="text" size="25" /></td>
            </tr>
          <tr>
            <td width="120" align="right" class="bluetxt">&nbsp;</td>
            <td align="left">&nbsp;</td>
            </tr>
          <tr>
            <td width="120" align="right" class="bluetxt">Telephone:</td>
            <td align="left"><input type="text" name="tel" id="tel" style="width:200px" /></td>
            </tr>
          <tr>
            <td width="120" align="right" class="bluetxt">Email:</td>
            <td align="left"><input type="text" name="email" id="email" style="width:200px" /></td>
            </tr>
          <tr>
            <td width="120" align="right" class="bluetxt">Website:</td>
            <td align="left"><input type="text" name="web" id="web" style="width:200px" /></td>
            </tr>
          
          </table></div>
        <div class="TabbedPanelsContent">
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>
              <td bgcolor="#0099FF" class="titles">Email Account:</td>
              <td align="left"><span class="blacktxt">
                <input type="text" name="admin_mail" id="admin_mail" style="width:250px" />
                </span></td>
              </tr>
            <tr>
              <td width="120" bgcolor="#0099FF" class="titles">Password:</td>
              <td align="left" class="blacktxt"><input name="email_pass" type="password" size="25" id="email_pass" /></td>
              </tr>
            <tr>
              <td width="120" bgcolor="#0099FF" class="titles">SMTP Server:</td>
              <td align="left" class="blacktxt"><input type="text" name="smtp" id="smtp" style="width:200px" /></td>
              </tr>
            <tr>
              <td width="120" bgcolor="#0099FF" class="titles">SMTP Port:</td>
              <td align="left" class="blacktxt"><input name="smtp_port" type="text" id="smtp_port" style="width:70px" /></td>
              </tr>
            <tr>
              <td width="120" bgcolor="#0099FF" class="titles">Authentication:</td>
              <td align="left" class="blacktxt"><input type="checkbox" name="smtp_auth" id="smtp_auth" /></td>
              </tr>
            <tr>
              <td bgcolor="#339900" class="titles">Active Directory:</td>
              <td align="left"><input type="checkbox" name="ad_auth" id="ad_auth" /></td>
            </tr>
            <tr>
              <td bgcolor="#339900" class="titles">Domain Controller:</td>
              <td align="left"><span class="blacktxt">
                <input type="text" name="ad_host" id="ad_host" style="width:200px" />
              </span></td>
            </tr>
            <tr>
              <td bgcolor="#339900" class="titles">LDAP User Account:</td>
              <td align="left"><span class="blacktxt">
                <input type="text" name="ad_user" id="ad_user" style="width:200px" />
              </span></td>
            </tr>
            <tr>
              <td bgcolor="#339900" class="titles">Password:</td>
              <td align="left"><span class="blacktxt">
                <input name="ad_pass" type="password" style="width:200px" />
              </span></td>
            </tr>
            <tr>
              <td width="120" align="right" class="titles">Is School:</td>
              <td align="left"><span class="blacktxt">
                <input type="checkbox" name="isschool" id="isschool" />
                </span></td>
              </tr>
            <tr>
              <td align="right" class="titles">Kiosk Mode:</td>
              <td align="left"><span class="blacktxt">
                <input type="checkbox" name="kiosk" id="kiosk" />
              </span></td>
            </tr>
            <tr>
              <td align="right" class="titles">Gateway Server:</td>
              <td align="left"><span class="blacktxt">
                <input type="text" name="gateway" id="gateway" style="width:150px" />
                </span></td>
              </tr>
            </table>
          </div>
        <div class="TabbedPanelsContent">
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>
              <td align="right" class="blue-normal"><?php echo COY ?> Currency:<img src="/images/pc_icon_required.gif" width="9" height="9" /></td>
              <td align="left"><select name="currency" id="currency">
                <option value=""></option>
                <?php for ($i=0; $i<count($row_Tcurrency); $i++) { ?>
                <option value="<?php echo $row_Tcurrency[$i]['cur_id']?>"><?php echo $row_Tcurrency[$i]['currencyname']?></option>
                <?php } ?>
                </select></td>
              </tr>
            <tr>
              <td width="120">&nbsp;</td>
              <td align="left" class="blacktxt"><input type="checkbox" name="store" id="store" />
                Use Store Facilities</td>
              </tr>
            <tr>
              <td width="120">&nbsp;</td>
              <td align="left" class="blacktxt"><input type="checkbox" name="pos" id="pos" />
                Use POS Printer</td>
              </tr>
            <tr>
              <td width="120">&nbsp;</td>
              <td align="left" class="blacktxt"><input type="checkbox" name="autosale" id="autosale" />
                Automatic New Sale</td>
              </tr>
            <tr>
              <td width="120">&nbsp;</td>
              <td align="left" class="blacktxt"><input type="checkbox" name="autoprint" id="autoprint" />
                Automatic Receipt Printout after Sale</td>
              </tr>
            <tr>
              <td valign="top" class="bluetxt">&nbsp;</td>
              <td align="left" class="blacktxt"><input type="checkbox" name="negstock" id="negstock" />
                Allow Negative Stock</td>
              </tr>
            <tr>
              <td valign="top" class="bluetxt">&nbsp;</td>
              <td align="left" class="blacktxt"><input type="checkbox" name="negcash" id="negcash" />
                Allow Negative Cash Transactions</td>
              </tr>
            <tr>
              <td valign="top" class="bluetxt">&nbsp;</td>
              <td align="left" class="blacktxt"><input type="checkbox" name="securetransfer" id="securetransfer" />
                Require Permission for Stock Transfer</td>
              </tr>
            <tr>
              <td width="120" align="right" valign="top" class="blue-normal">Receipt Comment:</td>
              <td align="left"><textarea name="comment" rows="3" id="comment" style="width:200px"></textarea></td>
              </tr>
            </table>
          </div>
        <div class="TabbedPanelsContent">
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>
              <td width="120" align="right" class="blue-normal"><?php echo COY ?> ID:</td>
              <td align="left"><input type="text" name="coyid" id="coyid" style="width:200px" /></td>
              </tr>
            <tr>
              <td width="120" align="right"><span class="blue-normal">Office/Shop ID:</span></td>
              <td align="left"><input type="text" name="shopid" id="shopid" style="width:200px" /></td>
              </tr>
            <tr>
              <td width="120" align="right" class="blue-normal">Username:</td>
              <td align="left"><input type="text" name="username" id="username" style="width:200px" /></td>
              </tr>
            <tr>
              <td width="120" align="right" class="blue-normal">Password:</td>
              <td align="left"><input type="password" name="password" id="password" style="width:200px" /></td>
              </tr>
            <tr>
              <td width="120" align="right">&nbsp;</td>
              <td align="left" class="blacktxt"><input type="checkbox" name="update" id="update" />
                Automatic Online Products &amp; Services Updates</td>
              </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td align="left" class="blacktxt"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                <tr>
                  <td colspan="2" align="left">Update Intervals (Minutes)</td>
                  </tr>
                <tr>
                  <td width="41%" height="32" align="right">Internet Update:</td>
                  <td width="59%"><input type="text" name="internetdb" id="internetdb" style="width:50px" /></td>
                  </tr>
                <tr>
                  <td align="right">Linked Database:</td>
                  <td><input type="text" name="linkdb" id="linkdb" style="width:50px" /></td>
                  </tr>
                </table></td>
              </tr>
            </table>
          </div>
        </div>
      </div></td>
    </tr>
</table>
                  
                <table width="100%" border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td align="center"><a href="Javascript: void(0)" onclick="vetFrm()"><img src="/images/save.png" width="80" height="30" /></a>
                      <input name="MM_newcoy" type="hidden" id="MM_newcoy" value="company" />
                      <a href="/index.php"><img src="/images/back.png" width="80" height="30" /></a></td>
                    </tr>
                  </table>
                </form></td>
              </tr> 
            </table></td>
        </tr>
      </table></td>
      <td class="frameright">&nbsp;</td>
    </tr>
    <tr>
      <td class="framebotleft">&nbsp;</td>
      <td align="center" valign="bottom" class="framebot"><span class="greytxt">Copyright © 2010 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
</div>
<script type="text/javascript">
var Tabs = new Spry.Widget.TabbedPanels("Tabs");
</script>
</body>
</html>
