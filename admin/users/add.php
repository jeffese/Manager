<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Administration'));
$access = _xvar_arr_sub($_access, array('Users'));
vetAccess('Administration', 'Users', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmuser","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmuser") {
    $user = GSQLStr(_xpost('username'), "textv");
    $pw = Rand_chr(6);
    $pass = hashPwd($pw, $user);
    $VendorID = intval(_xpost('EmployeeID'));
    $sql = sprintf("INSERT INTO `%s`.`users` (`username`, `userpass`, `usergroup`, `EmployeeID`, `active`) VALUES (%s, %s, %s, %s, 0)",
           $_SESSION['DBCoy'],
           "'$user'", "'$pass'",
           GSQLStr(_xpost('usergroup'), "text"),
           $VendorID);
    $insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
            header("Location: view.php?id=$VendorID&p=".urlencode($pw));
            exit;
    }
}
$sch = $_SESSION['accesskeys']['Academics']['View'] == -1 ? ",6,7" : "";
$sql = "SELECT `VendorID`, `VendorType` FROM `" . DB_NAME . "`.`vendortypes`
WHERE `VendorID` NOT IN (0,4$sch) AND `VendorID` IN (
SELECT DISTINCT `VendorType` FROM `{$_SESSION['DBCoy']}`.`vendors`
)
ORDER BY `VendorType`";
$TVendorTypes = getDBData($dbh, $sql);

$sql = "SELECT usergroup FROM `{$_SESSION['DBCoy']}`.`usergroups` ORDER BY `usergroup`";
$TUsrgrp = getDBData($dbh, $sql);
$vtyp = intval(_xget('t'));
$vtyp = $vtyp == 0 ? -1 : $vtyp;
$sql = "SELECT VendorID, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorType=$vtyp
AND VendorID NOT IN (
    SELECT EmployeeID FROM `{$_SESSION['DBCoy']}`.`users`
)
ORDER BY `VendorName`";
$TPerson = getDBData($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["EmployeeID", "", 
            ["req", "Select Person"]],
        ['username', "", 
            ["req", "Enter User Name"],
            ["minlen=6", "Minimum length for User Name is 6"]],
        ["usergroup", "", 
            ["req", "Select Usergroup"]]
    ]
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
        <td width="240" valign="top"><img src="/images/users.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblusers.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmuser" id="frmuser">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td class="titles">User Type:</td>
                    <td align="left"><select name="usertype" id="usertype" onchange="if (this.value!='') location.href='add.php?t='+this.value">
                      <option value="">Select</option>
                      <?php foreach ($TVendorTypes as $row_TVendorTypes) { ?>
                      <option value="<?php echo $row_TVendorTypes['VendorID'] ?>" <?php if (!(strcmp($vtyp, $row_TVendorTypes['VendorID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TVendorTypes['VendorType'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Person:</td>
                    <td align="left"><select name="EmployeeID" id="EmployeeID">
                      <option value="">Select</option>
                      <?php foreach ($TPerson as $row_TPerson) { ?>
                      <option value="<?php echo $row_TPerson['VendorID'] ?>"><?php echo $row_TPerson['VendorName'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Username:</td>
                    <td align="left"><input name='username' type="text" id='username' value="" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Usergoup:</td>
                    <td><select name="usergroup" id="usergroup">
                      <option value="">Select</option>
                      <?php foreach ($TUsrgrp as $row_TUsrgrp) { ?>
                      <option value="<?php echo $row_TUsrgrp['usergroup'] ?>"><?php echo $row_TUsrgrp['usergroup'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
</tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_insert" value="frmuser" />
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
</body>
</html>