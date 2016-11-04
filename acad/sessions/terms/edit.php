<?php require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Terms'));
vetAccess('Academics', 'Terms', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmterm","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmterm") {
  $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`sch_terms` SET term_name=%s, start=%s, end=%s, active=%s, Notes=%s WHERE term_id=%s",
                       GSQLStr(_xpost('term_name'), "text"),
                       GSQLStr(_xpost('start'), "date"),
                       GSQLStr(_xpost('end'), "date"),
                       _xpostchk('active'),
                       GSQLStr(_xpost('Notes'), "text"),
                       $_SESSION['term']);
	runDBQry($dbh, $sql);
        if (_xpostchk('active') == 1) {
            $sql = "UPDATE `{$_SESSION['DBCoy']}`.`sch_terms` SET active=0 WHERE `term_id`<>{$_SESSION['term']}";
            runDBQry($dbh, $sql);
        }
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_terms` WHERE term_id={$_SESSION['term']}";
$row_TTerm = getDBDataRow($dbh, $sql);

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
	var arrFormValidation=
	[
	["term_name", "", 
["req", "Enter <?php echo TERM ?> Name"]],
	["start", "", 
["req", "Select start date"]],
	["end", "", 
["req", "Select end date"]]
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
        <td width="240" valign="top"><img src="/images/term.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/<?php echo TERM=='Term' ? 'lblterm' : 'lblsemester' ?>.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmterm" id="frmterm">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
      <tr>
        <td class="titles">Name:</td>
        <td align="left"><input type="text" name="term_name" id="term_name" style="width:300px" value="<?php echo $row_TTerm['term_name'] ?>" /></td>
      </tr>
      <tr>
        <td class="titles">Active:</td>
        <td><input type="checkbox" name="active" id="active" <?php if (!(strcmp($row_TTerm['active'], 1))) { echo "checked=\"checked\""; } ?> /></td>
      </tr>
      <tr>
        <td class="titles">Starts:</td>
        <td align="left"><input type="text" name="start" id="start" style="width:100px" value="<?php echo $row_TTerm['start'] ?>" readonly="readonly" /></td>
      </tr>
      <tr>
        <td class="titles">Ends:</td>
        <td align="left"><input type="text" name="end" id="end" style="width:100px" value="<?php echo $row_TTerm['end'] ?>" readonly="readonly" /></td>
      </tr>
      <tr>
        <td class="titles">Notes:</td>
        <td align="left"><textarea name="Notes" id="Notes" style="width:300px" rows="4"><?php echo $row_TTerm['Notes'] ?></textarea></td>
      </tr>
      </table><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
            <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
			<script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
                  <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
                  <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
                  <script type="text/javascript">
var mCal;
window.onload = function() {
    mCal = new dhtmlxCalendarObject('start', true, {isYearEditable: true, isMonthEditable: true});
	mCal.setSkin('dhx_black');
    mCal2 = new dhtmlxCalendarObject('end', true, {isYearEditable: true, isMonthEditable: true});
	mCal2.setSkin('dhx_black');
}
                  </script></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php')?></td>
              </tr>

            </table>
<input type="hidden" name="term_id" value="<?php echo $row_TTerm['term_id'] ?>" />
            <input type="hidden" name="MM_update" value="frmterm" />
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