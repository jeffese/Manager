<?php require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Schemes'));
vetAccess('Academics', 'Schemes', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmscheme","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmscheme") {
  $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`sch_schemes` SET schm_name=%s, term_name=%s, class_name=%s, arm_nm=%s, crs_nm=%s, lect_nm=%s, Notes=%s WHERE schm_id=%s",
                       GSQLStr(_xpost('schm_name'), "text"),
                       GSQLStr(_xpost('term_name'), "text"),
                       GSQLStr(_xpost('class_name'), "text"),
                       GSQLStr(_xpost('arm_nm'), "text"),
                       GSQLStr(_xpost('crs_nm'), "text"),
                       GSQLStr(_xpost('lect_nm'), "text"),
                       GSQLStr(_xpost('Notes'), "text"),
                       $_SESSION['schm']);
	runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_schemes` WHERE schm_id={$_SESSION['schm']}";
$row_TSchem = getDBDataRow($dbh, $sql);

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
	["schm_name", "", 
["req", "Enter Session Name"]],
	["term_name", "", 
["req", "Enter Session Part Name"]],
	["class_name", "", 
["req", "Enter Class Name"]],
	["arm_nm", "", 
["req", "Enter Arm Name"]],
	["crs_nm", "", 
["req", "Enter Course Name"]],
	["lect_nm", "", 
["req", "Enter Teacher Name"]]
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
        <td width="240" valign="top"><img src="/images/scheme.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblscheme.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmscheme" id="frmscheme">
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
                    <td align="left"><input type="text" name="schm_name" id="schm_name" style="width:300px" value="<?php echo $row_TSchem['schm_name'] ?>" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Part's Name:</td>
                    <td align="left"><input type="text" name="term_name" id="term_name" style="width:300px" value="<?php echo $row_TSchem['term_name'] ?>" /></td>
                    </tr>
                  <tr>
                    <td class="titles">No. of Parts:</td>
                    <td align="left"><?php echo $row_TSchem['term_no'] ?></td>
                    </tr>
                  <tr>
                    <td class="titles">Class Name:</td>
                    <td align="left"><input type="text" name="class_name" id="class_name" style="width:300px" value="<?php echo $row_TSchem['class_name'] ?>" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Arm Name:</td>
                    <td align="left"><input type="text" name="arm_nm" id="arm_nm" style="width:300px" value="<?php echo $row_TSchem['arm_nm'] ?>" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Course Name:</td>
                    <td align="left"><input type="text" name="crs_nm" id="crs_nm" style="width:300px" value="<?php echo $row_TSchem['crs_nm'] ?>" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Teacher Name:</td>
                    <td align="left"><input type="text" name="lect_nm" id="lect_nm" style="width:300px" value="<?php echo $row_TSchem['lect_nm'] ?>" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Description:</td>
                    <td align="left"><textarea name="Notes" id="Notes" style="width:300px" rows="4"><?php echo $row_TSchem['Notes'] ?></textarea></td>
                  </tr>
                  </table></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <span class="titles">
            <input type="hidden" name="schm_id" value="<?php echo $row_TSchem['schm_id'] ?>" />
            <input type="hidden" name="MM_update" value="frmscheme" />
            </span>
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