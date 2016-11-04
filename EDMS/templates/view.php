<?php require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'EDMS'));
$access = _xvar_arr_sub($_access, array('Templates'));
vetAccess('EDMS', 'Templates', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], 0, 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Template]del.php?id=$id","","","","print.php?id=$id","index.php");
$rec_status = 1;

$sql = "SELECT `edms_tmpl`.*, `catname` "
        . "FROM `{$_SESSION['DBCoy']}`.`edms_tmpl` "
        . "INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `edms_tmpl`.`category`=`classifications`.`catID`"
        . "WHERE `tmpl_id`=$id";
$row_TEdms_tmpl = getDBDataRow($dbh, $sql);

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
<link rel="stylesheet" type="text/css" href="../../css/canvas.css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="/lib/jquery/jquery-migrate-1.2.1.min.js"></script>
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
    var edit = -1;

function loadTmpl() {
<?php
$lis = explode(PHP_EOL, $row_TEdms_tmpl['tmpl_det']);
foreach ($lis as $li) { ?>
    showCmp("<?= addcslashes($li, '"') ?>");
<?php } ?>
    }

</script>
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
          <td width="240" valign="top"><img src="/images/scheme.jpg" alt="" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lbltemplates.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td><table border="0" cellspacing="4" cellpadding="4">
            <tr>
              <td class="titles">Name:</td>
                    <td align="left"><?php echo $row_TEdms_tmpl['tmpl_name'] ?></td>
            </tr>
            <tr>
              <td class="titles">Category:</td>
              <td align="left"><?php echo $row_TEdms_tmpl['catname'] ?></td>
            </tr>
            <tr>
              <td class="titles">Description:</td>
              <td align="left"><textarea name="Notes" rows="3" readonly="readonly" id="Notes" style="width:500px"><?php echo $row_TEdms_tmpl['description'] ?></textarea></td>
            </tr>
            </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
              <td valign="top" id="canvas"><ul id="cmp_list">
                </ul></td>
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