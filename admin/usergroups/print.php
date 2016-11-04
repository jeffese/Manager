<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Administration'));
$access = _xvar_arr_sub($_access, array('Usergroups'));
vetAccess('Administration', 'Usergroups', 'Print');

$id = GSQLStr(_xget('id'), 'text');
$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`usergroups` WHERE `usergroup`=$id";
$row_TUsrgrp = getDBDataRow($dbh, $sql);
$usr_permits = permission_array($row_TUsrgrp['permissions']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="script.php"></script>
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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblusrgrp.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td width="120" class="titles">Name:</td>
                    <td align="left"><?php echo $row_TUsrgrp['usergroup'] ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><table cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                      <tr align="center" bgcolor="#666666" class="boldwhite1">
                        <?php foreach ($usr_permits as $mod => $usr_permit) {
                        if ($usr_permit['View'] != -1) { ?>
                        <td><?php echo $mod ?></td>
                        <?php }} ?>
                        </tr>
                      <tr>
                        <?php foreach ($usr_permits as $mod => $usr_permit) {
                        if ($usr_permit['View'] != -1) { ?>
                        <td align="center"><input type="checkbox" name="<?php echo $mod ?>" <?php if ($usr_permit['View'] == 1) echo 'checked="checked"' ?> disabled="disabled" /></td>
                        <?php }} ?>
                        </tr>
                    </table></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2"><table cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                        <?php foreach ($usr_permits as $mod => $usr_permit) {
                        if ($usr_permit['View'] != -1) {
                                $val = array_shift($usr_permit); ?>
                      <tr class="h1">
                        <td><?php echo $mod ?></td>
                      </tr>
                      <tr>
                        <td>
                          <table border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <?php foreach ($usr_permit as $sub => $permits) {
                            if ($permits['View'] != -1) { ?>
                              <td valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2" style="background-color:#ccc">
                                <tr align="center" bgcolor="#666666" class="boldwhite1">
                                  <td colspan="2"><?php echo $sub ?></td>
                                  </tr>
                                <?php foreach ($permits as $can => $permit) { ?>
                                <tr>
                                  <td><input name="<?php echo preg_replace('/\s/', '_', $mod . '_' . $sub . '_' . $can) ?>" type="checkbox" <?php if ($permit == 1) echo 'checked="checked"' ?> disabled="disabled" /></td>
                                  <td><?php echo $can ?></td>
                                  </tr>
                                <?php } ?>
                                </table></td>
                              <?php }} ?>
                              </tr>
                            </table>
                        </td>
                      </tr>
                        <?php }} ?>
                    </table></td>
</tr>
                </table></td>
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
<script type="text/javascript">
$(document).ready(function(){
	print();
});
</script>
</body>
</html>