<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Sessions'));
vetAccess('Academics', 'Sessions', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Session]del.php?id=$id","","","","print.php?id=$id","index.php");
$rec_status = 1;

$sql = "SELECT `sch_sessions`.*, `schm_name`, `term_name` FROM `{$_SESSION['DBCoy']}`.`sch_sessions` INNER JOIN `{$_SESSION['DBCoy']}`.`sch_schemes` ON `sch_sessions`.`scheme`=`sch_schemes`.`schm_id` WHERE `sess_id`={$_SESSION['sess_id']}";
$row_TSess = getDBDataRow($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_terms` WHERE `session`={$_SESSION['sess_id']}";
$TTerm = getDBData($dbh, $sql);

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
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="menu.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/sessions.jpg" alt="" width="240" height="300" />
          <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblsessions.png); background-repeat:no-repeat">&nbsp;</td>
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
                    <td align="left"><?php echo $row_TSess['sess_name'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Active:</td>
                    <td><input type="checkbox" name="active" id="active" <?php if (!(strcmp($row_TSess['active'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Scheme:</td>
                    <td><?php echo $row_TSess['schm_name'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Starts:</td>
                    <td><?php echo $row_TSess['start'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Ends:</td>
                    <td><?php echo $row_TSess['end'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" readonly="readonly" id="Notes" style="width:300px"><?php echo $row_TSess['Notes'] ?></textarea></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table border="0" align="center" cellpadding="8" cellspacing="0" bordercolor="#FFFFFF">
                  <tr>
                    <td height="10" colspan="4" align="center" bordercolor="#003300" bgcolor="#666666" class="yellowtxt"><strong><?php echo $row_TSess['term_name'] ?>s</strong></td>
                    </tr>
                  <tr>
                    <td height="10" bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Name</td>
                    <td bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Start</td>
                    <td bordercolor="#003300" bgcolor="#666666" class="boldwhite1">End</td>
                    <td bordercolor="#003300" bgcolor="#666666" class="boldwhite1">Active</td>
                  </tr>
                  <?php $j=0;
foreach ($TTerm as $row_TTerm) {
	$j++;
	$k = $j % 2;
	$rowdefcolor = ($k == 1) ? "#E5E5E5" : "#D5D5D5"; ?>
                  <tr class="black-normal" 
	  onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor; ?>', '#CCFFCC', '#FFCC99');" 
	  onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
	  onclick="top.leftFrame.showMod('Term Info', '/acad/sessions/terms/view.php?id=<?php echo $row_TTerm['term_id'] ?>')">
                    <td bgcolor="<?php echo $rowdefcolor ?>"><strong><?php echo $row_TTerm['term_name']; ?></strong></td>
                    <td bgcolor="<?php echo $rowdefcolor ?>"><?php echo $row_TTerm['start']; ?></td>
                    <td bgcolor="<?php echo $rowdefcolor ?>"><?php echo $row_TTerm['end']; ?></td>
                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>"><input type="checkbox" name="trm_active" id="trm_active" <?php if (!(strcmp($row_TTerm['active'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                  </tr>
                  <?php } ?>
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