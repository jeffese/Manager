<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Administration'));
$access = _xvar_arr_sub($_access, array('Usergroups'));
vetAccess('Administration', 'Usergroups', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 0);

$id = GSQLStr(_xget('id'), 'text');
$_id = urlencode(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$_id","","[Usergroup]del.php?id=$_id","","","find.php","print.php?id=$_id","index.php");
$rec_status = 1;

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
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="script.php"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/usergroups.jpg" alt="" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblusrgrp.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><?php echo $row_TUsrgrp['usergroup'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td><table cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
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
                    <td colspan="2" class="titles"><div id="Tabs" class="TabbedPanels">
                    <ul class="TabbedPanelsTabGroup">
                    <?php foreach ($usr_permits as $mod => $usr_permit) {
                        if ($usr_permit['View'] != -1) { ?>
                      <li class="TabbedPanelsTab" tabindex="0"><?php echo $mod ?></li>
                    <?php }} ?>
                    </ul>
                    <div class="TabbedPanelsContentGroup">
                    <?php foreach ($usr_permits as $mod => $usr_permit) {
                        if ($usr_permit['View'] != -1) {
                                $val = array_shift($usr_permit); ?>
                      <div class="TabbedPanelsContent" align="left">
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
                      </div>
                    <?php }} ?>
                    </div>
                  </div></td>
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
<script type="text/javascript">
var Tabs = new Spry.Widget.TabbedPanels("Tabs");
</script>
</body>
</html>