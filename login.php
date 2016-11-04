<?php
if (false && file_exists($cust = "custom/login.php")) {
    header("Location: $cust");
    exit;
}
require_once('scripts/init.php');
vetlogout();

$dbmain = getDBDataOne($dbh, "SELECT IF(EXISTS(SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'), 'Yes','No') AS db", 'db');
if ($dbmain=='No' || getDBDatacnt($dbh, "FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "'") == 0) {
	$_SESSION['setup'] = '1';
	$_SESSION['scriptfile'] = '/admin/companies/maindb.php';
	$_SESSION['msg'] = 'Preparing Main Database for first use.';
	header("Location: /admin/companies/progress.php");
	exit;
}
$chk_bot = intval(_xses('X_BAD_CREDENTIALS')) > BOT_CNT;
$FormAction = getformaction();
$custom_pg = '';//&& (!$chk_bot || vetcaptcha())
if (_xpost('MM_login') == 'login'  && login('company', 'username', 'password')) {
    $goto = (isset($_GET['PrevUrl'])) ? $_GET['PrevUrl'] : 'index.php';
    header('location:' . $goto);
    exit;
}
$chk_bot = intval(_xses('X_BAD_CREDENTIALS')) >= BOT_CNT;

$cy = isset($_GET['coy']) ? "WHERE LCASE(`CoyDir`)='" . strtolower(_xget('coy')) . "'" : "";
$sql = "SELECT `CoyID`, `CoyName`, `CoyDir` FROM `" . DB_NAME . "`.`coyinfo` $cy";
$row_Tmycoy = getDBData($dbh, $sql);

if (!isset($_GET['coy']) && count($row_Tmycoy)==0) {
    $_SESSION['newcompany'] = '1';
    header("Location: /admin/companies/add.php");
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manager 1.0</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<link href="/css/login-box.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script><script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script><script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script><script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script><link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
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
            ["req", "Select <?php echo COY ?>"]],
        ["username", "",
            ["req", "Enter Username"]],
        ["password", "",
            ["req", "Enter Password"]]<?php if ($chk_bot) { ?>,
        ["captcha", "",
            ["req", "Enter Security Phrase"]]<?php } ?>
]
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script><script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<div id="content"><form id="frmlogin" name="frmlogin" method="post" action="<?php echo $FormAction ?>" autocomplete="off" onsubmit="return validateFormPop(arrFormValidation);">
    <table width="485" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td style="background: url(/images/login-box-backg.png) no-repeat left top; padding: 58px 60px 0 60px;"><img src="/images/Exood-Manager.png" width="350" height="100" /></td>
      </tr>
      <tr>
        <td style="background: url(/images/login-box-backg_error.png) left; padding: 0 76px 0 76px;"><span style="color: orange; font-weight: bold;"><?php echo catch_error($errors) ?></span>
          <input type="hidden" name="action" value="login" /></td>
      </tr>
      <tr>
        <td style="background: url(/images/login-box-backg_error.png) left; padding: 0 76px 0 76px;">
          <div id="login-box-name" style="margin-top:20px;" class="boldwhite1"><?php echo COY ?>:</div>
            <?php if (isset($_GET['coy'])) { ?>
          <div id="login-box-name" style="margin-top:20px; padding-left:0; width:230px; text-align: left" class="yellowtxt"><?php
                    if (!$row_Tmycoy) {
                        echo "Unknown Company Code: " . _xget('coy');
                    } else {
                        echo $row_Tmycoy[0]['CoyName'];
            ?><input name="company" type="hidden" id="company" value="<?php echo $row_Tmycoy[0]['CoyID'] ?>" />
              <input name="coyname" type="hidden" id="coyname" value="<?php echo $row_Tmycoy[0]['CoyName'] ?>" />
              <?php } ?>
          </div>
              <?php } else {  ?>
          <div id="login-box-field" style="margin-top:20px;">
              <select class="form-login" id="company" name="company" onchange="this.form.coyname.value=this.options[this.selectedIndex].text">
                <?php for ($i=0; $i<count($row_Tmycoy); $i++) { ?>
                <option value="<?php echo $row_Tmycoy[$i]['CoyID']?>"><?php echo $row_Tmycoy[$i]['CoyName']?></option>
                <?php } ?>
              </select>
              <input name="coyname" type="hidden" id="coyname" value="<?php echo $row_Tmycoy[0]['CoyName'] ?>" />
          </div>
              <?php } ?>
          </td>
      </tr>
      <tr>
        <td style="background: url(/images/login-box-backg_cmp.png) no-repeat left bottom; padding: 0 76px 0 76px; height:130px"><div id="login-box-name" style="margin-top:20px;"><span class="boldwhite1">Username:</span></div>
          <div id="login-box-field" style="margin-top:20px;">
            <input name="username" id="username" class="form-login" title='username' size="30" maxlength="64" />
          </div>
          <div class="boldwhite1" id="login-box-name">Password:</div>
          <div id="login-box-field">
            <input name="password" id="password" type="password" class="form-login" title="Password" size="30" maxlength="64" />
          </div></td>
      </tr>
      <tr>
        <td style="background: url(/images/login-box-backg_capt.png) left; padding: 0 76px 0 76px;"><div id="login-box-captcha">
            <?php if ($chk_bot) { ?>
            <?php include('scripts/functions/bot_chk.php'); ?>
            <br />
            <?php } ?>
            <span class="greytxt">
            <input type="submit" name="bt" id="bt" value="" style="display:none" />
            </span>
            <input type="image" src="/images/login-btn.png" width="103" height="42" style="margin-left:110px;" border="0" alt="Login" />
            <span class="greytxt">
            <input name="MM_login" type="hidden" id="MM_login" value="login" />
            </span>          </div></td>
      </tr>
      <tr>
        <td style="background: url(/images/login-box-backg.png) no-repeat left bottom; padding: 10px 50px 40px 50px;"><span class="framebot"><span class="greytxt">Copyright &copy; 2012 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></span></td>
      </tr>
    </table>
  </form>
  </div>
</body>
</html>
