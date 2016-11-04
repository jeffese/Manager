<?php
require_once('scripts/init.php');

$FormAction = getformaction();
$username = isset($_SESSION['userid']) ? $_SESSION['userid'] : (isset($_SESSION['X_RESET']['username']) ? $_SESSION['X_RESET']['username'] : '');

if (strlen($username) > 0 && _xpost('MM_reset') == 'reset') {
    $coy = $_SESSION['COY']['CoyID'];
    $pass = hashPwd(GSQLStr(_xpost('password'), "textv"), $username);
    $sql = sprintf("SELECT * FROM %s.users WHERE username = '$username' AND userpass = '%s'", 
            DB_COY . $coy, $pass
    );
    $login = getDBDataRow($dbh, $sql);
    $newpass = _xpost('new_password');

    if (count($login) == 0) {
        array_push($errors, array("Error", "Current password is wrong for this Username ($username)!"));
    } else if ($newpass == _xpost('confirm_password') && (X_SECURE_PASS == 0 || (X_SECURE_PASS == 1 &&
            preg_match("/[A-Z]/", $newpass) && preg_match("/[a-z]/", $newpass) &&
            preg_match("/[0-9]/", $newpass) && preg_match("/\W/", $newpass) && !preg_match("/\s/", $newpass)))) {
        $pass = hashPwd($newpass, $username);
        $uid = isset($_SESSION['EmployeeID']) ? $_SESSION['EmployeeID'] : $_SESSION['X_RESET']['EmployeeID'];
        $sql = sprintf("UPDATE `%s`.`users` SET `userpass`='%s', `active`=1 WHERE EmployeeID=%s", 
                DB_COY . $coy, $pass, $uid
        );
        $update = runDBQry($dbh, $sql);
        logout();
        header("Location: login.php");
        exit;
    } else {
        array_push($errors, array("Error", "New Password either contains wrong characters or is missing required character types!"));
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
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
        ["password", "", 
            ["req", "Please enter your current password."]
        ],
        ["new_password", "", 
            ["minlen=6", "Minimum length for password is 6 characters."],
            ["maxlen=20", "Maximum length for password is 20 characters."]<?php if (X_SECURE_PASS == 1) { ?>,
            ["regexp=[A-Z]", "Password must contain at least one uppercase letter {A-Z}."],
            ["regexp=[a-z]", "Password must contain at least one lowercase letter {a-z}."],
            ["regexp=[0-9]", "Password must contain at least one number {0-9}."],
            ["regexp=\\W", "Password must contain at least one special character {!@#$%^&...}."],
            ["notregexp=\\s", "Password cannot contain a space character."]<?php } ?>
        ],
        ["confirm_password", "", 
            ["vet=new_password", "New Password and Confirm New Password do not match."]
        ]
    ]
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script><script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<div id="content">
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="frametopleft">&nbsp;</td>
      <td class="frametop">&nbsp;</td>
      <td class="frametopright">&nbsp;</td>
    </tr>
    <tr>
      <td class="frameleft">&nbsp;</td>
      <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="headerlogo"></td>
        </tr>
        <tr>
          <td height="10" class="h1">Change Password</td>
        </tr>
        <tr>
          <td><form id="frmlogin" name="frmlogin" method="post" action="<?php echo $FormAction ?>" autocomplete="off" onsubmit="return validateFormPop(arrFormValidation);">
              <table width="100%" border="0" cellpadding="2" cellspacing="2" class="bluetxt" style="border: solid 1px #666666">
                <tr>
                  <td colspan="2" align="left" class="bluetxt">Enter your new password and confirm it.
                    <br />
                    <span class="red-normal"><strong>Notes:</strong></span> Password must contain all of these<br />
                    <ul>
                      <li><em>An uppercase letter [A_Z]</em></li>
                      <li><em>A lowercase letter [a-z]</em></li>
                      <li><em>A number [0-9]</em></li>
                      <li><em>A special character [!@#$%^&amp;...]</em><br />
                      </li>
                    </ul></td>
                </tr>
                <tr>
                  <td colspan="2" align="center"><b><?php echo catch_error($errors) ?></b></td>
                </tr>
                <tr>
                  <td class="titles"><strong>Username:</strong></td>
                  <td align="left"><strong><?php echo $username ?></strong></td>
                </tr>
                <tr>
                  <td class="titles"><strong>Current Password:</strong></td>
                  <td align="left"><input type="password" name="password" id="password" style="width:200px" /></td>
                </tr>
                <tr>
                  <td class="titles"><strong>New Password:</strong></td>
                  <td align="left"><input type="password" name="new_password" id="new_password" style="width:200px" /></td>
                </tr>
                <tr>
                  <td class="titles">Confirm New Password</td>
                  <td align="left"><input type="password" name="confirm_password" id="confirm_password" style="width:200px" /></td>
                </tr>
                <tr>
                  <td colspan="2" align="center" class="greytxt"><input type="submit" name="bt" id="bt" value="" style="display:none" />
                    <a href="javascript: void(0)" onclick="if (document.frmlogin.onsubmit()) document.frmlogin.submit()"><img src="/images/save.png" width="80" height="30" border="0" /></a>
                    <input name="MM_reset" type="hidden" id="MM_reset" value="reset" /><?php if (isset($_SESSION['userid'])) { ?>
                        <a href="/index.php"><img src="/images/back.png" width="80" height="30" border="0" /></a>
                        <?php } ?></td>
                </tr>
                <tr>
                  <td colspan="2" align="center" class="greytxt">*Username and password are case sensitive</td>
                </tr>
              </table>
          </form></td>
        </tr>
      </table></td>
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
