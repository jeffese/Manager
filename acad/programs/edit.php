<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Programs'));
vetAccess('Academics', 'Programs', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmprog","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmprog") {
	$sql = sprintf("UPDATE `%s`.`sch_programs` SET `prog_name`=%s, `prog_code`=%s, `prog_type`=%s, `active`=%s, `department`=%s, `certificate`=%s, `grade`=%s, `class_pfx`=%s, `Notes`=%s WHERE prog_id=%s",
					   $_SESSION['DBCoy'],
                       GSQLStr(_xpost('prog_name'), "text"),
                       GSQLStr(_xpost('prog_code'), "text"),
                       GSQLStr(_xpost('prog_type'), "int"),
                       _xpostchk('active'),
                       GSQLStr(_xpost('department'), "int"),
                       GSQLStr(_xpost('certificate'), "int"),
                       GSQLStr(_xpost('grade'), "int"),
                       GSQLStr(_xpost('class_pfx'), "text"),
                       GSQLStr(_xpost('Notes'), "text"),
                       $_SESSION['prog_id']);
	$update = runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT `sch_programs`.* FROM `{$_SESSION['DBCoy']}`.`sch_programs` WHERE `prog_id`={$_SESSION['prog_id']}";
$row_TProg = getDBDataRow($dbh, $sql);

$TPrgtyp = getCat('progtype');

$TDept = getClassify(1);

$sql = "SELECT cert_id, cert_name FROM `{$_SESSION['DBCoy']}`.`sch_certificates` ORDER BY `cert_name`";
$TCert = getDBData($dbh, $sql);

$sql = "SELECT grade_sys_id, grade_sys FROM `{$_SESSION['DBCoy']}`.`sch_grade_sys` ORDER BY `grade_sys`";
$TGrad = getDBData($dbh, $sql);

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
["prog_name", "", 
["req", "Enter Program Name"]],
["prog_code", "", 
["req", "Enter No. of Levels"]],
["prog_type", "", 
["req", "Select Program Type"]],
["department", "", 
["req", "Select Department"]],
["certificate", "", 
["req", "Select Certificate"]],
["grade", "", 
["req", "Select Grade"]],
["class_pfx", "", 
["req", "Enter Level Prefix"]]
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
        <td width="240" valign="top"><img src="/images/programs.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblprograms.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmprog" id="frmprog">
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
                    <td align="left"><input name="prog_name" type="text" id="prog_name" value="<?php echo $row_TProg['prog_name'] ?>" size="32" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Code:</td>
                    <td align="left"><input name="prog_code" type="text" id="prog_code" value="<?php echo $row_TProg['prog_code'] ?>" size="15" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Type:</td>
                    <td><select name="prog_type" id="prog_type">
                      <option value="">Select</option>
                      <?php foreach ($TPrgtyp as $row_TPrgtyp) { ?>
                      <option value="<?php echo $row_TPrgtyp['CategoryID'] ?>" <?php if (!(strcmp($row_TProg['prog_type'], $row_TPrgtyp['CategoryID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TPrgtyp['Category'] ?></option>
                      <?php } ?>
                      </select>
                      <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/acad/tools/progtype/index.php', 480,520)" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td><select name="department" id="department">
                      <option value="">Select</option>
                      <?php foreach ($TDept as $row_TDept) { ?>
                      <option value="<?php echo $row_TDept['catID'] ?>" <?php if (!(strcmp($row_TProg['department'], $row_TDept['catID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TDept['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Certificate:</td>
                    <td><select name="certificate" id="certificate">
                      <option value="">Select</option>
                      <?php foreach ($TCert as $row_TCert) { ?>
                      <option value="<?php echo $row_TCert['cert_id'] ?>" <?php if (!(strcmp($row_TProg['certificate'], $row_TCert['cert_id']))) { echo "selected=\"selected\""; }?>><?php echo $row_TCert['cert_name'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Grade System:</td>
                    <td><select name="grade" id="grade">
                      <option value="">Select</option>
                      <?php foreach ($TGrad as $row_TGrad) { ?>
                      <option value="<?php echo $row_TGrad['grade_sys_id'] ?>" <?php if (!(strcmp($row_TProg['grade'], $row_TGrad['grade_sys_id']))) { echo "selected=\"selected\""; }?>><?php echo $row_TGrad['grade_sys'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">No. of <?php echo LEVEL ?>s:</td>
                    <td><?php echo $row_TProg['class_no'] ?></td>
                    </tr>
                  <tr>
                    <td class="titles"><?php echo LEVEL ?> Prefix:</td>
                    <td><input name="class_pfx" type="text" id="class_pfx" value="<?php echo $row_TProg['class_pfx'] ?>" size="12" /></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" id="Notes" style="width:300px"><?php echo $row_TProg['Notes'] ?></textarea></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmprog" />
            <input type="hidden" name="prog_id" value="<?php echo $row_TProg['prog_id']; ?>" />
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
