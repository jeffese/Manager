<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Sessions'));
vetAccess('Academics', 'Sessions', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmsess","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmsess") {
	$schm = GSQLStr(_xpost('scheme'), "int");
	$sql = sprintf("INSERT INTO `%s`.`sch_sessions` (`sess_name`, `scheme`, `start`, `end`, `active`, `Notes`) VALUES (%s, %s, %s, %s, %s, %s)",
					   $_SESSION['DBCoy'],
                       GSQLStr(_xpost('sess_name'), "text"),
                       $schm,
                       GSQLStr(_xpost('start'), "date"),
                       GSQLStr(_xpost('end'), "date"),
                       _xpostchk('active'),
                       GSQLStr(_xpost('Notes'), "text"));
	$insert = runDBQry($dbh, $sql);
	
	if ($insert > 0) {
		$recid = mysqli_insert_id($dbh);
                if (_xpostchk('active') == 1) {
                    $sql = "UPDATE `{$_SESSION['DBCoy']}`.`sch_sessions` SET active=0 WHERE `sess_id`<>$recid";
                    runDBQry($dbh, $sql);
                }
		
		$sql = "SELECT term_no, term_name FROM `{$_SESSION['DBCoy']}`.`sch_schemes` WHERE schm_id=$schm";
		$parts = getDBDataRow($dbh, $sql);
		for ($i=1; $i <= $parts['term_no']; $i++) {
                    switch ($i) {
                        case 1:
                            $pos = "st";
                            break;
                        case 2:
                            $pos = "nd";
                            break;
                        case 3:
                            $pos = "rd";
                            break;
                        default:
                            $pos = "th";
                    }
			$sql = "INSERT INTO `{$_SESSION['DBCoy']}`.`sch_terms`(`term_name`, `session`, `num`, `start`, `end`, `active`, `Notes`) VALUES ('$i{$pos} {$parts['term_name']}', $recid, $i, NOW(), NOW(), 0, '')";
			runDBQry($dbh, $sql);
		}

		$_SESSION['sess_id'] = $recid;
		header("Location: view.php?id=$recid");
		exit;
	}
}

$sql = "SELECT schm_id, schm_name FROM `{$_SESSION['DBCoy']}`.`sch_schemes` ORDER BY `schm_name`";
$TSchm = getDBData($dbh, $sql);

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
["sess_name", "", 
["req", "Enter Session Name"]],
["scheme", "", 
["req", "Select a Scheme"]],
["start", "", 
["req", "Enter Unit Name"]],
["end", "", 
["req", "Enter Unit Symbol"]]
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
        <td width="240" valign="top"><img src="/images/sessions.jpg" alt="" width="240" height="300" /></td>
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="return chkuplow(document.frmsess.start,document.frmsess.end,'Start-End') && validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmsess" id="frmsess">
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
                    <td align="left"><input name="sess_name" type="text" id="sess_name" size="32" /></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Active:</td>
                    <td><input type="checkbox" name="active" id="active" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Scheme:</td>
                    <td><select name="scheme" id="scheme" onchange="catnamesel(this)">
                      <option value="">Select</option>
                      <?php foreach ($TSchm as $row_TSchm) { ?>
                      <option value="<?php echo $row_TSchm['schm_id'] ?>"><?php echo $row_TSchm['schm_name'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Starts:</td>
                    <td><input name="start" type="text" id="start" size="12" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Ends:</td>
                    <td><input name="end" type="text" id="end" size="12" /></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" id="Notes" style="width:300px"></textarea></td>
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
                  </script>
                  </td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_insert" value="frmsess" />
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