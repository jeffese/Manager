<?php
require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Electives'));
vetAccess('Academics', 'Electives', 'print');

$sql = "SELECT *
FROM `{$_SESSION['DBCoy']}`.`sch_electives`
WHERE `elect_id`={$_SESSION['elect_id']}";
$row_TElectives = getDBDataRow($dbh, $sql);

$crslst = str_replace("|", ",", $row_TElectives['courses']);
$crslst = strlen($crslst) == 0 ? '0' : $crslst;

$sql = "SELECT course_id, lecturer, CONCAT(course_name, ' {', course_code, '}') as `course`, $vendor_sql
FROM `{$_SESSION['DBCoy']}`.`sch_courses`
INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_courses`.`lecturer`=`vendors`.`VendorID`
WHERE course_id IN ($crslst)";
$TCourses = getDBData($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../course.js"></script>
<script language="JavaScript1.2" type="text/javascript">
	var lect_abv = "<?php echo LECTURER ?>";
<?php foreach ($TCourses as $row_TCourses) { ?>
	crs_names.push("<?php echo $row_TCourses['course'] ?>");
	crs_ids.push("<?php echo $row_TCourses['course_id'] ?>");
	lec_names.push("<?php echo $row_TCourses['VendorName'] ?>");
	lec_ids.push("<?php echo $row_TCourses['lecturer'] ?>");
<?php } ?>

	$(document).ready(function(){
		isEdit = false;
		prepView();
	});
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/xxpix.jpg" ?>" /></td>
        <td><span class="coytxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/<?php echo COURSE=='Subject' ? 'lblsubjelectives' : 'lblelectives' ?>.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td class="titles">Class Name:</td>
                    <td align="left"><?php echo _xses('elect_class') ?></td>
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
                    <td colspan="2"><input type="hidden" name="courses" id="courses" value="<?php echo $row_TElectives['courses'] ?>" />
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
                    <td><?php echo $row_TElectives['Notes'] ?></textarea></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td></td>
              </tr>

            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">

            </table></td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
	print();
});
</script>
</body>
</html>