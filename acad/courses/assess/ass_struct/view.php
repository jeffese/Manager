<?php
require_once('../../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Assessment Structure'));
vetAccess('Academics', 'Assessment Structure', 'View');

$ass = _xget("ass");
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, $access['Print'], 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "edit.php?ass=$ass", "", "", "", "", "", "return GB_showCenter('". COURSE ." Assessment Config', 'print.php?id=$id', 600,600)", "index.php?ass=$ass");
$rec_status = 1;

$idStr = "";
$class = _xvarloopstore('cid', 'ass_struct_cid');
$term = _xvarloopstore('tid', 'ass_struct_term');
$course = _xvarloopstore('crsid', 'ass_struct_crs');

if ($ass == "ass")
    viewCourse($class, $course, $term);
else
    vetClassView($class);
viewTerm($term);

$fresh = isset($_GET['cid']) && isset($_GET['tid']) && isset($_GET['crsid']) && strlen($class) != 0 && strlen($term) != 0 && strlen($course) != 0;

if ($fresh) {
    $idStr = $ass == "ass" ? "`ass_arm`=0$class AND `ass_term`=0$term AND `ass_course`=0$course" :
        "`class`=0$class AND `cls_term`=0$term";
} else if (isset($_SESSION['ass_struct_id'])) {
    $idStr = ($ass == "ass" ? "`ass_struct_id`=0" : "`cls_struct_id`=") . _xses('ass_struct_id');
}
if (isset($_GET['cls'])) {
    $_SESSION['ass_struct_arm'] = _xget('cls');
}
if (isset($_GET['trm'])) {
    $_SESSION['ass_struct_trm'] = _xget('trm');
}

if ($ass == "ass") {
    $sql = "SELECT `sch_assess_struct`.*, `sch_cls_ass_struct`.*, `course_name` 
            FROM `{$_SESSION['DBCoy']}`.`sch_assess_struct`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `sch_assess_struct`.`ass_arm`=`sch_arms`.`arm_id`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_cls_ass_struct` ON 
            (`sch_assess_struct`.`ass_term`=`sch_cls_ass_struct`.`cls_term`
            AND `sch_arms`.`class`=`sch_cls_ass_struct`.`class`)
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_courses` ON `sch_assess_struct`.`ass_course`=`sch_courses`.`course_id`
            WHERE $idStr";
} else {
    $sql = "SELECT `sch_cls_ass_struct`.*, '' AS `course_name`, '' AS `ass_struct_id`, '' AS `ass_arm`, 
        '' AS `ass_course`, `cls_term` AS `ass_term`, '' AS `ass_names`, '' AS `ass_codes`, '' AS `ass_ca`, 
        '' AS `ass_state`, '' AS `ass_grp`, '' AS `percentages`, '' AS `max_scores`, '' AS `attachments`, 
        '' AS `attend_date`, '' AS `Notes`
        FROM `{$_SESSION['DBCoy']}`.`sch_cls_ass_struct`
        WHERE $idStr";
}
$row_TAss_struct = getDBDataRow($dbh, $sql);
if (count($row_TAss_struct) == 0) {
    if ($fresh) {
        if ($ass == "ass")
        $insertSQL = "INSERT INTO `{$_SESSION['DBCoy']}`.`sch_assess_struct` (`ass_arm`, `ass_course`, `ass_term`) 
        VALUES ($class, $course, $term)";
        else
            $insertSQL = "INSERT INTO `{$_SESSION['DBCoy']}`.`sch_cls_ass_struct` (`class`, `cls_term`) 
        VALUES ($class, $term)";
        runDBQry($dbh, $insertSQL);
        $row_TAss_struct = getDBDataRow($dbh, $sql);
    } else {
        header("Location: /denied.php?msg=Wrong%20Entry!!");
        exit;
    }
}
$_SESSION['ass_struct_id'] = $row_TAss_struct[$ass == "ass" ? 'ass_struct_id' : 'cls_struct_id'];

$sql = "SELECT `term_id`, `term_name`
    FROM `{$_SESSION['DBCoy']}`.`sch_terms`
    WHERE `term_id` <> {$row_TAss_struct['ass_term']} AND `session`= (
            SELECT `session` 
            FROM `{$_SESSION['DBCoy']}`.`sch_terms`
            WHERE `term_id` = 0{$row_TAss_struct['ass_term']}
            )";
$Terms = getDBData($dbh, $sql);

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
<script type="text/javascript" src="assessment.js"></script>
<script language="JavaScript1.2" type="text/javascript">
	var arrFormValidation=[];
        isClass = <?php echo $ass == 'ass' ? "false" : "true" ?>;
	var ass_struct_id = "<?php echo $row_TAss_struct['ass_struct_id']; ?>";

	var ca_ids = new Array(0, -1<?php foreach ($Terms as $row_Terms) {
		echo ", ", $row_Terms['term_id'];
	} ?>);
	var ca_str = new Array('Assessment', 'Attendance'<?php foreach ($Terms as $row_Terms) {
		echo ", '", mysql_escape_string($row_Terms['term_name']), "'";
	} ?>);
	
	$(document).ready(function(){
		isEdit = false;
		prepView(false);
	});
</script>
        <!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
    </head>
    <body>
        <script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script>
        <script charset="UTF-8" src="../../menu.js" type="text/javascript"></script>
        <script type="text/javascript">awmBuildMenu();</script>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="240" valign="top"><img src="/images/assesstruct.jpg" alt="" width="240" height="300" />
                                <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
                            <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                                    <tr>
                                        <td style="height:30px; min-width:500px; background-image:url(/images/lblasstruct.png); background-repeat:no-repeat">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="h1" height="5px"></td>
                                    </tr>
                                    <tr>
                                        <td><?php include('../../../../scripts/buttonset.php') ?></td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" cellspacing="4" cellpadding="4">
                                    <tr>
                                        <td class="h1">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><table border="0" cellpadding="4" cellspacing="4">
										<?php if ($ass == 'ass') { ?>
                                          <tr>
                                            <td></td>
                                            <td align="center"><?php echo catch_error($errors) ?></td>
                                          </tr>
                                          <tr>
                                            <td class="titles"><?php echo COURSE ?>:</td>
                                            <td align="left"><?php echo $row_TAss_struct['course_name'] ?></td>
                                          </tr>
										<?php } ?>
                                          <tr>
                                            <td class="titles">Class:</td>
                                            <td><?php echo _xses('ass_struct_arm') ?></td>
                                          </tr>
                                          <tr>
                                            <td class="titles"><?php echo TERM ?>:</td>
                                            <td><?php echo _xses('ass_struct_trm') ?></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2" align="center" id="ass_box"><input name="ass_names" type="hidden" id="ass_names" value="<?php echo $row_TAss_struct['ass_names']; ?>" />
                                              <input name="ass_codes" type="hidden" id="ass_codes" value="<?php echo $row_TAss_struct['ass_codes']; ?>" />
                                              <input name="ass_ca" type="hidden" id="ass_ca" value="<?php echo $row_TAss_struct['ass_ca']; ?>" />
                                              <input name="ass_state" type="hidden" id="ass_state" value="<?php echo $row_TAss_struct['ass_state']; ?>" />
                                              <input name="ass_grp" type="hidden" id="ass_grp" value="<?php echo $row_TAss_struct['ass_grp']; ?>" />
                                              <input name="cls_grp_inf" type="hidden" id="cls_grp_inf" value="<?php echo $row_TAss_struct['cls_grp_inf']; ?>" />
                                              <input name="percentages" type="hidden" id="percentages" value="<?php echo $row_TAss_struct['percentages']; ?>" />
                                              <input name="max_scores" type="hidden" id="max_scores" value="<?php echo $row_TAss_struct['max_scores']; ?>" />
                                              <input name="attachments" type="hidden" id="attachments" value="<?php echo $row_TAss_struct['attachments']; ?>" />
                                              <input name="cls_names" type="hidden" id="cls_names" value="<?php echo $row_TAss_struct['cls_names']; ?>" />
                                              <input name="cls_codes" type="hidden" id="cls_codes" value="<?php echo $row_TAss_struct['cls_codes']; ?>" />
                                              <input name="cls_ca" type="hidden" id="cls_ca" value="<?php echo $row_TAss_struct['cls_ca']; ?>" />
                                              <input name="cls_state" type="hidden" id="cls_state" value="<?php echo $row_TAss_struct['cls_state']; ?>" />
                                              <input name="cls_percentages" type="hidden" id="cls_percentages" value="<?php echo $row_TAss_struct['cls_percentages']; ?>" />
                                              <input name="cls_max_scores" type="hidden" id="cls_max_scores" value="<?php echo $row_TAss_struct['cls_max_scores']; ?>" />
                                              <input name="cls_ass" type="hidden" id="cls_ass" value="<?php echo $row_TAss_struct['cls_ass']; ?>" />
                                              <input name="cls_ass_state" type="hidden" id="cls_ass_state" value="<?php echo $row_TAss_struct['cls_ass_state']; ?>" />
                                              <input name="cls_sub" type="hidden" id="cls_sub" value="<?php echo $row_TAss_struct['cls_sub']; ?>" />
                                              <input name="cls_sub_state" type="hidden" id="cls_sub_state" value="<?php echo $row_TAss_struct['cls_sub_state']; ?>" />
                                              <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
                                              <table border="0" cellpadding="0" cellspacing="0" style="margin:10px">
                                                <tr>
                                                  <td class="bl_tl"></td>
                                                  <td class="bl_tp"></td>
                                                  <td class="bl_tr"></td>
                                                </tr>
                                                <tr>
                                                  <td rowspan="2" class="bl_lf"></td>
                                                  <td class="bl_title">Cognitive Assessments</td>
                                                  <td rowspan="2" class="bl_rt"></td>
                                                </tr>
                                                <tr>
                                                  <td class="bl_center"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                                                    <tr>
                                                      <td class="black-normal" id="bx_assess2"><table border="0" cellspacing="2" cellpadding="2">
                                                        <tr>
                                                          <td><strong>Collation:</strong></td>
                                                          <td><script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TAss_struct['cls_typ']; ?>) {
case 0: document.write("Sum"); break;
case 1: document.write("Average"); break;
case 2: document.write("Maximum"); break;
default: document.write("");
}</script></td>
                                                        </tr>
                                                      </table></td>
                                                    </tr>
                                                    <tr>
                                                      <td class="black-normal" id="bx_assess">&nbsp;</td>
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
                                            <td><textarea name="Notes" rows="5" readonly="readonly" id="Notes" style="width:300px"><?php echo $row_TAss_struct[$ass == 'ass' ? 'Notes' : 'cls_notes'] ?></textarea></td>
                                          </tr>
                                          <?php if ($ass == 'ass') { ?>
                                          <tr>
                                            <td colspan="2" class="black-normal"><?php echo $row_TAss_struct['cls_notes'] ?></td>
                                          </tr>
                                          <?php } ?>
                                        </table></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php include('../../../../scripts/buttonset.php'); ?></td>
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