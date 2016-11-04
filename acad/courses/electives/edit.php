<?php
require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Electives'));
vetAccess('Academics', 'Electives', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmelectives","","view.php?id=$id","","","","");
$rec_status = 3;

isAdviser(_xses('elect_arm'), true);
viewTerm(_xses('elect_term'), true);
$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmelectives") {
    $sql = sprintf("UPDATE `%s`.`sch_electives` SET `min`=%s, `max`=%s, `min_gp`=%s, `max_gp`=%s, `promote_gp`=%s, `courses`=%s, `lecturers`=%s, `gps`=%s, `core_elec`=%s, `grp`=%s, `grp_inf`=%s, `Notes`=%s 
    WHERE `elect_id`=%s",
                   $_SESSION['DBCoy'],
                   GSQLStr(_xpost('min'), "int"),
                   GSQLStr(_xpost('max'), "int"),
                   GSQLStr(_xpost('min_gp'), "int"),
                   GSQLStr(_xpost('max_gp'), "int"),
                   GSQLStr(_xpost('promote_gp'), "int"),
                   GSQLStr(_xpost('courses'), "text"),
                   GSQLStr(_xpost('lecturers'), "text"),
                   GSQLStr(_xpost('gps'), "text"),
                   GSQLStr(_xpost('core_elec'), "text"),
                   GSQLStr(_xpost('grp'), "text"),
                   GSQLStr(_xpost('grp_inf'), "text"),
                   GSQLStr(_xpost('Notes'), "text"),
                   GSQLStr(_xpost('elect_id'), "int"));
    $update = runDBQry($dbh, $sql);
    header("Location: view.php?id=$id");
    exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_electives` WHERE `elect_id`={$_SESSION['elect_id']}";
$row_TElectives = getDBDataRow($dbh, $sql);

$crslst = str_replace("|", ",", $row_TElectives['courses']);
$crslst = strlen($crslst) == 0 ? '0' : $crslst;

$sql = "SELECT course_id, lecturer, CONCAT(course_name, ' {', course_code, '}') as `course`
FROM `{$_SESSION['DBCoy']}`.`sch_courses`
WHERE course_id IN ($crslst)";
$TCourses = getDBData($dbh, $sql);

$leclst = str_replace("|", ",", $row_TElectives['lecturers']);
$leclst = strlen($leclst) == 0 ? '0' : $leclst;

$sql = "SELECT VendorID, $vendor_sql
FROM `{$_SESSION['DBCoy']}`.`vendors`
WHERE VendorID IN ($leclst)";
$TLecturers = getDBData($dbh, $sql);

$TLecturer = getVendor(5, 1);

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
<script type="text/javascript" src="../course.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[];
    var lect_abv = "<?php echo LECTURER ?>";
    var cmb_lecturer ='<select name="_cmb_" id="_cmb_" onchange="course_itms[_id_].onChangeVal(2, this.value)"><option value="">Select</option><?php foreach ($TLecturer as $row_TLecturer) { ?><option value="<?php echo $row_TLecturer['VendorID'] ?>"><?php echo $row_TLecturer['VendorName'] ?></option><?php } ?></select>';
<?php foreach ($TCourses as $row_TCourses) { ?>
    crs_names.push("<?php echo $row_TCourses['course'] ?>");
    crs_ids.push("<?php echo $row_TCourses['course_id'] ?>");
<?php } ?>

<?php foreach ($TLecturers as $row_TLecturers) { ?>
    lec_names.push("<?php echo $row_TLecturers['VendorName'] ?>");
    lec_ids.push("<?php echo $row_TLecturers['VendorID'] ?>");
<?php } ?>

    $(document).ready(function(){
        isEdit = true;
        prepView();
    });
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
        <td width="240" valign="top"><img src="/images/course_elect.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/<?php echo COURSE=='Subject' ? 'lblsubjelectives' : 'lblelectives' ?>.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmelectives" id="frmelectives">
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
                    <td class="titles">Class Name:</td>
                    <td align="left"><?php echo _xses('elect_class') ?></td>
                  </tr>
                  <tr>
                    <td class="titles"><?php echo TERM ?>:</td>
                    <td><?php echo _xses('elect_trm') ?></td>
                    </tr>
                  <tr>
                    <td class="titles">Selection Limits:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td>Min:</td>
                        <td><input name="min" type="text" id="min" value="<?php echo $row_TElectives['min'] ?>" size="5" /></td>
                        <td>&nbsp;</td>
                        <td>Max:</td>
                        <td><input name="max" type="text" id="max" value="<?php echo $row_TElectives['max'] ?>" size="5" /></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">GP Limits:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td>Min:</td>
                        <td><input name="min_gp" type="text" id="min_gp" value="<?php echo $row_TElectives['min_gp'] ?>" size="5" /></td>
                        <td>&nbsp;</td>
                        <td>Max:</td>
                        <td><input name="max_gp" type="text" id="max_gp" value="<?php echo $row_TElectives['max_gp'] ?>" size="5" /></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Min. GP for Promotion:</td>
                    <td><input name="promote_gp" type="text" id="promote_gp" value="<?php echo $row_TElectives['promote_gp'] ?>" size="5" /></td>
                    </tr>
                  <tr>
                    <td colspan="2" align="center"><input type="hidden" name="courses" id="courses" value="<?php echo $row_TElectives['courses'] ?>" />
                      <input type="hidden" name="lecturers" id="lecturers" value="<?php echo $row_TElectives['lecturers'] ?>" />
                      <input type="hidden" name="gps" id="gps" value="<?php echo $row_TElectives['gps'] ?>" />
                      <input type="hidden" name="core_elec" id="core_elec" value="<?php echo $row_TElectives['core_elec'] ?>" />
                      <input type="hidden" name="grp" id="grp" value="<?php echo $row_TElectives['grp'] ?>" />
                      <input type="hidden" name="grp_inf" id="grp_inf" value="<?php echo $row_TElectives['grp_inf'] ?>" />
                      <table border="0" cellpadding="0" cellspacing="0" style="margin:10px">
                        <tr>
                          <td class="bl_tl"></td>
                          <td class="bl_tp"></td>
                          <td class="bl_tr"></td>
                          </tr>
                        <tr>
                          <td rowspan="2" class="bl_lf"></td>
                          <td class="bl_title">Core <?php echo COURSE ?>s</td>
                          <td rowspan="2" class="bl_rt"></td>
                          </tr>
                        <tr>
                          <td class="bl_center"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <td class="black-normal" id="bx_core">&nbsp;</td>
                              </tr>
                            <tr>
                              <td align="center"><a href="javascript: void(0)" onclick="return GB_showCenter('Select <?php echo COURSE ?>s', '/acad/courses/electives/coursepick.php?typ=1&crs=' + $('#courses').val(), 480,520)"><img src="/images/but_add.png" width="50" height="20" /></a></td>
                              </tr>
                            </table></td>
                          </tr>
                        <tr>
                          <td class="bl_bl"></td>
                          <td class="bl_bt"></td>
                          <td class="bl_br"></td>
                          </tr>
                        </table>
                      <table border="0" cellpadding="0" cellspacing="0" style="margin:10px">
                        <tr>
                          <td class="bl_tl"></td>
                          <td class="bl_tp"></td>
                          <td class="bl_tr"></td>
                          </tr>
                        <tr>
                          <td rowspan="2" class="bl_lf"></td>
                          <td class="bl_title">Elective <?php echo COURSE ?>s</td>
                          <td rowspan="2" class="bl_rt"></td>
                          </tr>
                        <tr>
                          <td class="bl_center"><table width="100%" border="0" cellpadding="4" cellspacing="4">
                            <tr>
                              <td class="black-normal" id="bx_elect">&nbsp;</td>
                              </tr>
                            <tr>
                              <td align="center"><a href="javascript: void(0)" onclick="return GB_showCenter('Select <?php echo COURSE ?>s', '/acad/courses/electives/coursepick.php?typ=2&crs=' + $('#courses').val(), 480,520)"><img src="/images/but_add.png" width="50" height="20" /></a></td>
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
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" id="Notes" style="width:300px"><?php echo $row_TElectives['Notes'] ?></textarea></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmelectives" />
            <input name="elect_id" type="hidden" id="elect_id" value="<?php echo $row_TElectives['elect_id']; ?>" />
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