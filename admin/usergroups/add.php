<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Administration'));
$access = _xvar_arr_sub($_access, array('Usergroups'));
vetAccess('Administration', 'Usergroups', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmusrgrp","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmusrgrp") {
    $usrgrp = GSQLStr(_xpost('usergroup'), "textv");
    $sql = sprintf("INSERT INTO `%s`.`usergroups` (`usergroup`, `permissions`) VALUES (%s, %s)",
                   $_SESSION['DBCoy'],
                   "'$usrgrp'",
                   GSQLStr(_xpost('permissions'), "text"));
    $insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
            header("Location: view.php?id=$usrgrp");
            exit;
    }
}

$usr_permits = permission_array("0");

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
<script type="text/javascript" src="script.php"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["usergroup", "", 
        ["req", "Enter Usergroup Name"]
    ]
];
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
                    <td align="left"><form action="<?php echo $editFormAction; ?>" onsubmit="collate(); return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmusrgrp" id="frmusrgrp"><input name="usergroup" type="text" id="usergroup" size="32" />
                      <input name="permissions" type="hidden" id="permissions" />
                      <input name="MM_insert" type="hidden" id="MM_insert" value="frmusrgrp" />
                    </form></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                      <tr align="center" bgcolor="#666666" class="boldwhite1">
                    <?php foreach ($usr_permits as $mod => $usr_permit) {
                        if ($usr_permit['View'] != -1) { ?>
                      <td><?php echo $mod ?></td>
                    <?php }} ?>
                      </tr>
                      <tr>
                    <?php foreach ($usr_permits as $mod => $usr_permit) {
                        if ($usr_permit['View'] != -1) { ?>
                      <td align="center"><input type="checkbox" name="<?php echo $mod ?>" /></td>
                    <?php }} ?>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td><table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
                      <tr>
                        <td class="bl_tl"></td>
                        <td class="bl_tp"></td>
                        <td class="bl_tr"></td>
                      </tr>
                      <tr>
                        <td rowspan="2" class="bl_lf"></td>
                        <td align="left" class="bl_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td nowrap="nowrap">Documents</td>
                            <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_docs" onclick="hideshow('docs', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_docs" onclick="hideshow('docs', 0, '')" style="display:none; cursor: pointer" /></div></td>
                          </tr>
                        </table></td>
                        <td rowspan="2" class="bl_rt"></td>
                      </tr>
                      <tr>
                        <td class="bl_center"><table width="100%" border="0" cellspacing="2" cellpadding="2" id="bx_docs" style="display:none">
                          <tr>
                            <td><?php include '../../scripts/newdoc.php' ?></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td class="bl_bl"></td>
                        <td class="bl_bt"></td>
                        <td class="bl_br"></td>
                      </tr>
                    </table></td>
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
                                  <td><input name="<?php echo preg_replace('/[^\w]/', '_', $mod . '_' . $sub . '_' . $can) ?>" type="checkbox" /></td>
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
              <tr>
                <td colspan="2"><?php include('../../scripts/buttonset.php')?></td>
              </tr>
            </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<script type="text/javascript">
var Tabs = new Spry.Widget.TabbedPanels("Tabs");
</script>
</body>
</html>