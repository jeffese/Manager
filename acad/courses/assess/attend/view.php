<?php
require_once('../../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Assessments'));
vetAccess('Academics', 'Assessments', 'View');

$ass = _xget("ass");
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, $access['Print'], 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "edit.php?ass=$ass", "", "", "", "", "", "return GB_showCenter('". COURSE ." Assessment', 'print.php?id=$id', 600,600)", "../index.php?ass=$ass");
$rec_status = 1;

$arm = _xvarloopstore('cid', 'assess_arm');
$term = _xvarloopstore('tid', 'assess_term');
viewTerm($term);

$idStr = _xget('all') == "1" ? "" : "`DeptID`=$arm AND ";
if ($ass == 'att') {
    $course = _xvarloopstore('crsid', 'assess_crs');
    isTeacher($arm, $course, $term);

    $insertSQL = "INSERT INTO `{$_SESSION['DBCoy']}`.`sch_assess`(`class`, `course`, `term`, `student`) 
        SELECT $arm, $course, $term, `student` 
            FROM `{$_SESSION['DBCoy']}`.`sch_course_offer`
            WHERE `term`=$term AND $course REGEXP CONCAT('^(', `courses`, ')$') AND `student` NOT IN (
            SELECT `student` FROM `{$_SESSION['DBCoy']}`.`sch_assess`
            WHERE `term`=$term AND `course`=$course
            )";
    runDBQry($dbh, $insertSQL);

    $sql = "SELECT `assess_id`, `attend`, `sch_assess`.`comments`, `sch_assess`.`Notes`, $vendor_sql 
    FROM `{$_SESSION['DBCoy']}`.`sch_assess` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_assess`.`student`=`vendors`.`VendorID`
    WHERE $idStr`term`=$term AND `course`=$course ORDER BY `VendorName`";
    $TAssess = getDBData($dbh, $sql);

    $sql = "SELECT `ass_struct_id`, `attend_date`, `course_name`
    FROM `{$_SESSION['DBCoy']}`.`sch_assess_struct` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`sch_courses` ON `sch_assess_struct`.`ass_course`=`sch_courses`.`course_id`
    WHERE `ass_arm`=$arm AND `ass_term`=$term AND `ass_course`=$course";
    $row_TAss_struct = getDBDataRow($dbh, $sql);
} else {
    isAdviser($arm);
    $sql = "SELECT `ass_struct_id` AS `ass_struct_id`, `attend_date` FROM `{$_SESSION['DBCoy']}`.`sch_cls_attend`
    WHERE `class`=$arm AND `term`=$term";
    $row_TAss_struct = getDBDataRow($dbh, $sql);
    if (count($row_TAss_struct) == 0) {
        $insertSQL = "INSERT INTO `{$_SESSION['DBCoy']}`.`sch_cls_attend`(`class`, `term`) 
            VALUES ($arm, $term)";
        runDBQry($dbh, $insertSQL);
        
        $row_TAss_struct = getDBDataRow($dbh, $sql);
    }
    
    $insertSQL = "INSERT INTO `{$_SESSION['DBCoy']}`.`sch_stud_attend`(`term`, `student`) 
        SELECT $term, `VendorID` 
            FROM `{$_SESSION['DBCoy']}`.`vendors`
            WHERE `VendorType`=7 AND InUse=0 AND `VendorID` NOT IN (
            SELECT `student` FROM `{$_SESSION['DBCoy']}`.`sch_stud_attend`
            WHERE `term`=$term
            )";
    runDBQry($dbh, $insertSQL);

    $sql = "SELECT `assess_id`, `attend`, `sch_stud_attend`.`comments`, `sch_stud_attend`.`Notes`, $vendor_sql 
    FROM `{$_SESSION['DBCoy']}`.`sch_stud_attend` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_stud_attend`.`student`=`vendors`.`VendorID`
    WHERE $idStr`term`=$term ORDER BY `VendorName`";
    $TAssess = getDBData($dbh, $sql);
}
if (isset($_GET['cls'])) {
    $_SESSION['ass_cls'] = _xget('cls');
    $_SESSION['ass_arm'] = _xget('arm');
}
if (isset($_GET['trm'])) {
    $_SESSION['ass_trm'] = _xget('trm');
}

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
<script type="text/javascript" src="attend.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    isEdit = false;
    var stud_cnt = <?php echo count($TAssess) ?>;
    
    str = "<?php echo $row_TAss_struct['attend_date'] ?>";
    var att_dates = str.length == 0 ? new Array() : str.split("|");
</script>
        <!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
    </head>
    <body>
        <script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script>
        <script charset="UTF-8" src="<?php echo $ass == 'att' ? "../../" : "../../../programs/classes/" ?>menu.js" type="text/javascript"></script>
        <script type="text/javascript">awmBuildMenu();</script>
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
                            <td width="240" valign="top"><img src="/images/assess.jpg" alt="" width="240" height="300" />
                                <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
                            <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                                    <tr>
                                        <td style="height:30px; min-width:500px; background-image:url(/images/<?php
				if($ass == 'cls') {
                                    echo "lbldayattend";
                                } elseif ($ass == 'att') {
                                    if (COURSE=='Subject')
                                        echo "lblsubjattend";
                                    else
                                        echo "lblclassattend";
                                } else {
                                    echo "lblassess";
                                } ?>.png); background-repeat:no-repeat">&nbsp;</td>
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
                                        <?php if ($ass == 'att') { ?>
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
                                            <td class="titles"><?php echo LEVEL ?>:</td>
                                            <td><table border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td nowrap="nowrap"><?php echo _xses('ass_cls').(strlen(_xses('ass_arm'))>0 && _xget('all')=="" ? ' > '._xses('ass_arm') : '') ?></td>
                                                <td>&nbsp;</td>
                                                <td><?php if (strlen(_xses('ass_arm'))>0 && _xget('all')=="") { ?>
                                                  <a href="view.php?ass=<?php echo $ass ?>&all=1"><img src="/images/but_show_all.png" width="70" height="20" /></a>
                                                  <?php } else if (strlen(_xses('ass_arm'))>0) { ?>
                                                  <a href="view.php?ass=<?php echo $ass ?>"><strong>Show only <?php echo _xses('ass_arm') ?></strong></a>
                                                  <?php } ?></td>
                                              </tr>
                                            </table></td>
                                          </tr>
                                          <tr>
                                            <td class="titles"><?php echo TERM ?>:</td>
                                            <td><?php echo _xses('ass_trm') ?></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                        <td><div style="border:solid 2px #ff0000; background: #C0DFF1; padding:4px; width:800px; height:500px; overflow:scroll">
                                          <table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC" id="ass_tab">
                                            <thead>
                                              <tr id="hd_bl" align="center" bgcolor="#045CC8" class="boldwhite1">
                                                <td>#</td>
                                                <td><a id="col_nam" class="boldwhite1" href="javascript: void(0)" onclick="sort(-2)">Name<img id="img_sort" src="/images/descend.gif" width="10" height="10" border="0" /></a>
                                                  <input name="assess_cnt" id="assess_cnt" type="hidden" value="<?php echo count($TAssess) ?>" />
                                                  <span class="black-normal">
                                                    <input name="attend_date" id="attend_date" type="hidden" value="<?php echo $row_TAss_struct['attend_date'] ?>" />
                                                  </span></td>
                                                <td id="bl_tot"><a id="col_tot" class="boldwhite1" href="javascript: void(0)" onclick="sort(-1)">Total</a></td>
                                                <td nowrap="nowrap"><a class="boldwhite1" href="javascript: void(0)" onclick="sort(-1)">Position</a></td>
                                                <td>Comments</td>
                                                <td>Notes</td>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <?php $j=0;
	   foreach ($TAssess as $row_TAssess) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                                              <tr bgcolor="<?php echo $rowdefcolor ?>" class="ass_row" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" id="row_<?php echo $j ?>">
                                                <td align="left" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="red-normal" id="num_<?php echo $j ?>"><strong><?php echo $j+1 ?></strong></td>
                                                <td align="left" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b id="stud_name_<?php echo $j ?>"><?php echo $row_TAssess['VendorName'] ?></b>
                                                  <input name="assess_id_<?php echo $j ?>" id="assess_id_<?php echo $j ?>" type="hidden" value="<?php echo $row_TAssess['assess_id'] ?>" />
                                                  <input name="attend_<?php echo $j ?>" id="attend_<?php echo $j ?>" type="hidden" value="<?php echo $row_TAssess['attend'] ?>" /></td>
                                                <td id="tot_cell_<?php echo $j ?>" align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input name="total_<?php echo $j ?>" type="text" id="total_<?php echo $j ?>" size="3" style="text-align:right" readonly="readonly" /></td>
                                                <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input name="pos_<?php echo $j ?>" type="text" id="pos_<?php echo $j ?>" size="3" style="text-align:right" readonly="readonly" /></td>
                                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><textarea name="comments_<?php echo $j ?>" style="width:300px" rows="1" id="comments_<?php echo $j ?>"><?php echo $row_TAssess['comments'] ?></textarea></td>
                                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><textarea name="notes_<?php echo $j ?>" style="width:300px" rows="1" id="notes_<?php echo $j ?>"><?php echo $row_TAssess['Notes'] ?></textarea></td>
                                              </tr>
                                            </tbody>
                                            <?php $j++;} ?>
                                          </table>
                                      </div></td>
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