<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Administration'));
$access = _xvar_arr_sub($_access, array('Users'));
vetAccess('Administration', 'Users', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmuser","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmuser") {
	$sql = sprintf("UPDATE `%s`.`users` SET `username`=%s, `usergroup`=%s WHERE EmployeeID=%s AND EmployeeID<>1",
					   $_SESSION['DBCoy'],
                       GSQLStr(_xpost('username'), "text"),
                       GSQLStr(_xpost('usergroup'), "text"),
                       $id);
	$update = runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT EmployeeID, username, usergroup, CONCAT(ContactFirstName, ' ', ContactLastName) AS vendor 
FROM `{$_SESSION['DBCoy']}`.`users` 
INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `users`.`EmployeeID`=`vendors`.`VendorID` 
WHERE `EmployeeID`=$id";
$row_TUsers = getDBDataRow($dbh, $sql);

$supUser = in_array('Admin', $_SESSION['usergroup'])  ? "" : "WHERE usergroup<>'Admin' AND usergroup <> 'PowerGroup'";
$sql = "SELECT usergroup FROM `{$_SESSION['DBCoy']}`.`usergroups` $supUser ORDER BY `usergroup`";
$TUsrgrp = getDBData($dbh, $sql);

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
        ['username', "", 
            ["req", "Enter User Name"]],
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
                    <td class="titles">Person:</td>
                    <td align="left"><?php echo $row_TUsers['vendor'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Username:</td>
                    <td align="left"><input name='username' type="text" id='username' value="<?php echo $row_TUsers['username'] ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Usergoup:</td>
                    <td><select name="usergroup" id="usergroup">
                      <option value="">Select</option>
                      <?php foreach ($TUsrgrp as $row_TUsrgrp) { ?>
                      <option value="<?php echo $row_TUsrgrp['usergroup'] ?>" <?php if (!(strcmp($row_TUsrgrp['usergroup'], $row_TUsers['usergroup']))) { echo "selected=\"selected\""; }?>><?php echo $row_TUsrgrp['usergroup'] ?></option>
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
            <input type="hidden" name="MM_update" value="frmuser" />
            <input type="hidden" name="EmployeeID" value="<?php echo $row_TUsers['EmployeeID']; ?>" />
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