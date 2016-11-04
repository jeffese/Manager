<?php
require_once('../../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Assessment Structure'));
vetAccess('Academics', 'Assessment Structure', 'Edit');
if ($ass == 'ass')
    isTeacher(_xses('ass_struct_cid'), _xses('ass_struct_crs'), _xses('ass_struct_term'), true);
else {    
    isAdviser(_xses('ass_struct_arm'), true);
    viewTerm(_xses('ass_struct_term'), true);
}

$ass = _xget("ass");

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmasses_struct","","view.php?ass=$ass","","","","");
$rec_status = 3;

viewTerm(_xses('ass_struct_term'), true);
$editFormAction = $_SERVER['PHP_SELF'] . set_QS()."?ass=$ass";

if (_xpost("MM_update") == "frmasses_struct") {
    $ass_struct_id = intval(_xpost($ass == "ass" ? 'ass_struct_id' : 'cls_struct_id'));
    if ($ass == "ass") {
        $attach = explode("|", _xpost('attachments'));
        $attachments = "";
        for ($i=0; $i<count($attach); $i++) {
            $atch_keys = explode("#", $attach[$i]);
            $fl = $atch_keys[0];
            $atch = explode(":", $atch_keys[1]);
            $newfile = newfile(ROOT . ASSESS_DIR, "questions".DS.$ass_struct_id, $fl, $atch, "preview_{$fl}_", "pv_{$fl}_", "prvw_{$fl}");
            $attachments .= "~~##~~" . $newfile['prvcode'];
        }
        $attachments = strlen($attachments) > 6 ? substr($attachments, 6) : "";
    }
        
    if ($ass == "ass")
    $sql = sprintf("UPDATE `%s`.`sch_assess_struct` SET `ass_names`=%s, `ass_codes`=%s, `ass_ca`=%s, 
        `ass_state`=%s, `ass_grp`=%s, `percentages`=%s, `max_scores`=%s, `attachments`=%s, `Notes`=%s 
        WHERE `ass_struct_id`=%s",
                   $_SESSION['DBCoy'],
                   GSQLStr(_xpost('ass_names'), "text"),
                   GSQLStr(_xpost('ass_codes'), "text"),
                   GSQLStr(_xpost('ass_ca'), "text"),
                   GSQLStr(_xpost('ass_state'), "text"),
                   GSQLStr(_xpost('ass_grp'), "text"),
                   GSQLStr(_xpost('percentages'), "text"),
                   GSQLStr(_xpost('max_scores'), "text"),
                   GSQLStr($attachments, "text"),
                   GSQLStr(_xpost('Notes'), "text"),
                   $ass_struct_id);
    else
        $sql = sprintf("UPDATE `%s`.`sch_cls_ass_struct` SET `cls_typ`=%s, `cls_names`=%s, `cls_codes`=%s, `cls_ca`=%s, 
        `cls_state`=%s, `cls_grp_inf`=%s, `cls_percentages`=%s, `cls_max_scores`=%s,
        `cls_ass`=%s, `cls_ass_state`=%s, `cls_sub`=%s, `cls_sub_state`=%s, `cls_notes`=%s 
        WHERE `cls_struct_id`=%s",
                   $_SESSION['DBCoy'],
                   GSQLStr(_xpost('cls_typ'), "int"),
                   GSQLStr(_xpost('ass_names'), "text"),
                   GSQLStr(_xpost('ass_codes'), "text"),
                   GSQLStr(_xpost('ass_ca'), "text"),
                   GSQLStr(_xpost('ass_state'), "text"),
                   GSQLStr(_xpost('cls_grp_inf'), "text"),
                   GSQLStr(_xpost('percentages'), "text"),
                   GSQLStr(_xpost('max_scores'), "text"),
                   GSQLStr(_xpost('cls_ass'), "text"),
                   GSQLStr(_xpost('cls_ass_state'), "text"),
                   GSQLStr(_xpost('cls_sub'), "text"),
                   GSQLStr(_xpost('cls_sub_state'), "text"),
                   GSQLStr(_xpost('Notes'), "text"),
                   $ass_struct_id);
    $update = runDBQry($dbh, $sql);
    header("Location: view.php?ass=$ass");
    exit;
}

$idStr = _xses('ass_struct_id');
$isCopy = "false";
if ($ass == "ass") {
    $sql = "SELECT `sch_assess_struct`.*, `sch_cls_ass_struct`.*, `course_name` 
            FROM `{$_SESSION['DBCoy']}`.`sch_assess_struct`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `sch_assess_struct`.`ass_arm`=`sch_arms`.`arm_id`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_cls_ass_struct` ON 
            (`sch_assess_struct`.`ass_term`=`sch_cls_ass_struct`.`cls_term`
            AND `sch_arms`.`class`=`sch_cls_ass_struct`.`class`)
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_courses` ON `sch_assess_struct`.`ass_course`=`sch_courses`.`course_id`
            WHERE `ass_struct_id`=0$idStr";
    $row_TAss_struct = getDBDataRow($dbh, $sql);
} else {
    $sql = "SELECT `sch_cls_ass_struct`.*, '' AS `course_name`, '' AS `ass_struct_id`, '' AS `ass_arm`, 
        '' AS `ass_course`, `cls_term` AS `ass_term`, '' AS `ass_names`, '' AS `ass_codes`, '' AS `ass_ca`, 
        '' AS `ass_state`, '' AS `ass_grp`, '' AS `percentages`, '' AS `max_scores`, '' AS `attachments`, 
        '' AS `attend_date`, '' AS `Notes`
        FROM `{$_SESSION['DBCoy']}`.`sch_cls_ass_struct`";
    $where = "WHERE `cls_struct_id`=0$idStr";
    $row_TAss_struct = getDBDataRow($dbh, $sql.$where);
    
    if (strlen($row_TAss_struct['cls_grp_inf'])==0) {
        $c_sql = $sql . " INNER JOIN `{$_SESSION['DBCoy']}`.`sch_terms` ON `sch_cls_ass_struct`.`cls_term`=`sch_terms`.`term_id`
                WHERE `class`=0{$_SESSION['ass_struct_cid']} AND `cls_grp_inf`<>'' AND `num`= (
                    SELECT `num` 
                    FROM {$_SESSION['DBCoy']}.`sch_terms` 
                    WHERE `term_id` = 0{$row_TAss_struct['ass_term']}
                )
                ORDER BY `cls_term` DESC";
        $TCopy_struct = getDBData($dbh, $c_sql);
        if (count($TCopy_struct)==0) {
            $c_sql = $sql . " WHERE `class`=0{$_SESSION['ass_struct_cid']} AND `cls_grp_inf`<>''
                ORDER BY `cls_term` DESC";
            $TCopy_struct = getDBData($dbh, $c_sql);
        }
        if (count($TCopy_struct)==0) {
            $c_sql = $sql . 
                " INNER JOIN {$_SESSION['DBCoy']}.`sch_class` ON `sch_cls_ass_struct`.`class`=`sch_class`.`class_id`
                WHERE `program`= (
                    SELECT `program` 
                    FROM {$_SESSION['DBCoy']}.`sch_class` 
                    WHERE `class_id`=0{$_SESSION['ass_struct_cid']}
                )
                AND `cls_grp_inf`<>''
                ORDER BY `cls_term` DESC";
            $TCopy_struct = getDBData($dbh, $c_sql);
        }
        if (count($TCopy_struct)>0) {
            $TCopy_struct[0]['cls_struct_id'] = $row_TAss_struct['cls_struct_id'];
            $TCopy_struct[0]['class'] = $row_TAss_struct['class'];
            $TCopy_struct[0]['cls_term'] = $row_TAss_struct['cls_term'];
            $row_TAss_struct = $TCopy_struct[0];
            $isCopy = "true";
        }
    }
}

if (count($row_TAss_struct) == 0) {
    header("Location: /denied.php?msg=Wrong%20Entry!!");
    exit;
}

$sql = "SELECT `term_id`, `term_name`
    FROM `{$_SESSION['DBCoy']}`.`sch_terms`
    WHERE `term_id` <> {$row_TAss_struct['ass_term']} AND `session`= (
                SELECT `session` 
                FROM `{$_SESSION['DBCoy']}`.`sch_terms`
                WHERE `term_id` = {$row_TAss_struct['ass_term']}
            )
            AND `num`< (
                SELECT `num` 
                FROM `{$_SESSION['DBCoy']}`.`sch_terms` 
                WHERE `term_id`={$row_TAss_struct['ass_term']}
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
		isEdit = true;
		prepView(<?php echo $isCopy ?>);
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
        <td width="240" valign="top"><img src="/images/assesstruct.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblasstruct.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return vetAss()" method="post" enctype="multipart/form-data" name="frmasses_struct" id="frmasses_struct">
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
                    <td width="120" class="titles"><?php echo COURSE ?>:</td>
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
                                  <td><?php if ($ass == 'cls') { ?>
                                    <select name="cls_typ" id="cls_typ">
                                      <option value="0" <?php if ($row_TAss_struct['cls_typ']==0) echo "selected=\"selected\""; ?>>Sum</option>
                                      <option value="1" <?php if ($row_TAss_struct['cls_typ']==1) echo "selected=\"selected\""; ?>>Average</option>
                                      <option value="2" <?php if ($row_TAss_struct['cls_typ']==2) echo "selected=\"selected\""; ?>>Maximum</option>
                                      </select>
                                    <?php } else { ?><script language="JavaScript" type="text/javascript">
switch (<?php echo $row_TAss_struct['cls_typ']; ?>) {
case 0: document.write("Sum"); break;
case 1: document.write("Average"); break;
case 2: document.write("Maximum"); break;
default: document.write("");
}</script><?php } ?></td>
                                  </tr>
                                </table></td>
                              </tr>
                            <tr>
                              <td class="black-normal" id="bx_assess" >&nbsp;</td>
                              </tr>
                            <?php if ($ass == 'cls') { ?>
                            <tr>
                              <td align="center"><a href="javascript: void(0)" onclick="addItm(0)">
                                <img src="/images/but_add.png" width="50" height="20" /></a>&nbsp;<a href="javascript: void(0)" onclick="addGrp()"><img src="/images/but_add_grp.png" width="80" height="20" /></a></td>
                              </tr>
                            <?php } ?>
                            </table></td>
                          </tr>
                        <tr>
                          <td class="bl_bl"></td>
                          <td class="bl_bt"></td>
                          <td class="bl_br"></td>
                          </tr>
                        </table>
                      <p id="ass_box">&nbsp;</p></td>
                    </tr>
                            <?php if ($ass == 'cls') { ?>
                  <tr>
                    <td colspan="2" align="center"><a href="javascript: void(0)" onclick="addTyp()"><img src="/images/but_assess.png" width="120" height="20" /></a></td>
                    </tr>
                            <?php } ?>
                  <tr>
                    <td class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" id="Notes" style="width:300px"><?php echo $row_TAss_struct[$ass == 'ass' ? 'Notes' : 'cls_notes'] ?></textarea></td>
                    </tr>
                  <?php if ($ass == 'ass') { ?>
                  <tr>
                    <td colspan="2" class="black-normal"><?php echo $row_TAss_struct['cls_notes'] ?></td>
                    </tr>
                  <?php } ?>
                  </table></td>
              </tr>
              <tr>
                <td><?php include('../../../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmasses_struct" />
            <input name="ass_struct_id" type="hidden" id="ass_struct_id" value="<?php echo $row_TAss_struct['ass_struct_id']; ?>" />
            <input name="cls_struct_id" type="hidden" id="cls_struct_id" value="<?php echo $row_TAss_struct['cls_struct_id']; ?>" />
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