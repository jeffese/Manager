<?php
require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Electives'));
vetAccess('Academics', 'Electives', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, $access['Print'], 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","edit.php?id=$id","","","","","","return GB_showCenter('". COURSE ." Info', 'print.php?id=$id', 600,600)","index.php");
$rec_status = 1;

$idStr = "";
$arm = _xget('cid');
$term = _xget('tid');
$t_num = _xget('tc');
$_SESSION['elect_term'] = $term;
isAdviser($arm);
viewTerm($term);
$fresh = strlen($arm) != 0 && strlen($term) != 0 && strlen($t_num) != 0;

if ($fresh) {
    $idStr = "`elect_arm`=$arm AND `elect_term`=$term";
    $_SESSION['elect_arm'] = $arm;
} else if (isset($_SESSION['elect_id'])) {
    $idStr = "`elect_id`="._xses('elect_id');
}
if (isset($_GET['trm'])) {
	$_SESSION['elect_trm'] = _xget('trm');
}
if (isset($_GET['cls'])) {
	$_SESSION['elect_class'] = _xget('cls');
}

$sql = "SELECT *
FROM `{$_SESSION['DBCoy']}`.`sch_electives`
WHERE $idStr";
$row_TElectives = getDBDataRow($dbh, $sql);
if (count($row_TElectives) == 0) {
    if ($fresh) {
        $sql = "SELECT `sch_electives`.*
        FROM `{$_SESSION['DBCoy']}`.`sch_electives`
        INNER JOIN `{$_SESSION['DBCoy']}`.`sch_terms` ON `sch_electives`.`elect_term`=`sch_terms`.`term_id`
        WHERE `elect_arm`=$arm AND `num`=$t_num ORDER BY `elect_term` DESC";
        $copy = getDBData($dbh, $sql);
        if (count($copy) > 0) {
            $insertSQL = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`sch_electives`
            (`elect_arm`, `elect_term`, `min`, `max`, `min_gp`, `max_gp`, `promote_gp`, 
            `courses`, `gps`, `core_elec`, `grp`, `grp_inf`, `Notes`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            ",
                   $arm,
                   $term,
                   $copy['min'],
                   $copy['max'],
                   $copy['min_gp'],
                   $copy['max_gp'],
                   $copy['promote_gp'],
                   GSQLStr(_xpost('courses'), "text"),
                   GSQLStr(_xpost('gps'), "text"),
                   GSQLStr(_xpost('core_elec'), "text"),
                   GSQLStr(_xpost('grp'), "text"),
                   GSQLStr(_xpost('grp_inf'), "text"),
                   GSQLStr(_xpost('Notes'), "text"));
        } else {
            $insertSQL = "INSERT INTO `{$_SESSION['DBCoy']}`.`sch_electives`(`elect_arm`, `elect_term`, `grp_inf`) 
        VALUES ($arm, $term, '1')";
        }
        runDBQry($dbh, $insertSQL);
        $row_TElectives = getDBDataRow($dbh, $sql);
    } else {
        header("Location: /denied.php?msg=Wrong%20Entry!!");
        exit;
    }
}
$_SESSION['elect_id'] = $row_TElectives['elect_id'];

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
    var lect_abv = "<?php echo LECTURER ?>";
<?php foreach ($TCourses as $row_TCourses) { ?>
    crs_names.push("<?php echo $row_TCourses['course'] ?>");
    crs_ids.push("<?php echo $row_TCourses['course_id'] ?>");
<?php } ?>

<?php foreach ($TLecturers as $row_TLecturers) { ?>
    lec_names.push("<?php echo $row_TLecturers['VendorName'] ?>");
    lec_ids.push("<?php echo $row_TLecturers['VendorID'] ?>");
<?php } ?>

    $(document).ready(function(){
        isEdit = false;
        prepView();
    });
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="../menu.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/course_elect.jpg" alt="" width="240" height="300" />
          <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
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
                        <td><?php echo $row_TElectives['min'] ?></td>
                        <td>&nbsp;</td>
                        <td>Max:</td>
                        <td><?php echo $row_TElectives['max'] ?></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">GP Limits:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td>Min:</td>
                        <td><?php echo $row_TElectives['min_gp'] ?></td>
                        <td>&nbsp;</td>
                        <td>Max:</td>
                        <td><?php echo $row_TElectives['max_gp'] ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Min. GP for Promotion:</td>
                    <td><?php echo $row_TElectives['promote_gp'] ?></td>
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
                    <td><textarea name="Notes" rows="5" readonly="readonly" id="Notes" style="width:300px"><?php echo $row_TElectives['Notes'] ?></textarea></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php'); ?></td>
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