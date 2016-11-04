<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Administration'));
$access = _xvar_arr_sub($_access, array('Users'));
vetAccess('Administration', 'Users', 'View');
$p = _xget('p');

$id = intval(_xget('id'));
$sql = "SELECT username, usergroup, CONCAT(ContactFirstName, ' ', ContactLastName) AS vendor 
FROM `{$_SESSION['DBCoy']}`.`users` 
INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `users`.`EmployeeID`=`vendors`.`VendorID` 
WHERE `EmployeeID`=$id";
$row_TUsers = getDBDataRow($dbh, $sql);
if (isset($_GET['reset'])) {
    $p = Rand_chr(6);
    $pass = hashPwd($p, $row_TUsers['username']);
    $UpdateSQL = "UPDATE `{$_SESSION['DBCoy']}`.`users` 
                SET `userpass`='$pass', `active`=0 
                WHERE EmployeeID=$id";
    $update = runDBQry($dbh, $UpdateSQL);
    _xmail(_xpost('email'), $_SESSION['COY']['CoyName'], ADMIN_MAIL, $_SESSION['COY']['CoyName'], ADMIN_MAIL, "{$_SESSION['COY']['CoyName']} Password Reset", '', 
            "Your Password has been reset to: $p\nPlease login and change it to a new password.\nThank you.\nAdministrator", '');
    array_push($xMessages, array("Successful", "The Password was successfully Reset!"));
}

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 0);

//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","if (confirm('Are you sure you want to delete this User entry?')) document.location='del.php?id=$id'","","","","print.php?p=$p&id=$id","index.php");
$rec_status = 1;

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
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><?php echo showMsg($xMessages) ?></td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><?php echo $row_TUsers['vendor'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">User Name:</td>
                    <td align="left"><?php echo $row_TUsers['username'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Password:</td>
                    <td><?php if (strlen($p) > 0) { ?><?php echo $p ?><?php } else if ($row_TUsers['username'] != "admin") { ?>
                      <input type="submit" name="button" id="button" value="Reset" onclick="location.href='view.php?id=<?php echo $id ?>&reset=1'" />                      <?php } ?></td>
                  </tr>
                  
                  <tr>
                    <td width="120" class="titles">Usergroup:</td>
                    <td><?php echo $row_TUsers['usergroup'] ?></td>
                  </tr>
                </table></td>
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
</body>
</html>