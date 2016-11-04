<?php
require_once('../../../scripts/init.php');
require_once('ass_methods.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Assessments'));
vetAccess('Academics', 'Assessments', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, $access['Print'], 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "edit.php?id=$id", "", "", "", "", "", "return GB_showCenter('". COURSE ." Assessment', 'print.php?id=$id', 600,600)", "index.php");
$rec_status = 1;

$idStr = "";
$arm = _xvarloopstore('aid', 'assess_arm');
$cls = _xvarloopstore('cid', 'assess_cls');
$term = _xvarloopstore('tid', 'assess_term');
$course = _xvarloopstore('crsid', 'assess_crs');
viewCourse($arm, $course, $term);
viewTerm($term);

$insertSQL = "INSERT INTO `{$_SESSION['DBCoy']}`.`sch_assess`(`class`, `course`, `term`, `student`) 
    SELECT $cls, $course, $term, `student` 
        FROM `{$_SESSION['DBCoy']}`.`sch_course_offer`
        WHERE `term`=$term AND $course REGEXP CONCAT('^(', `courses`, ')$') AND `student` NOT IN (
        SELECT `student` FROM `{$_SESSION['DBCoy']}`.`sch_assess`
        WHERE `term`=$term AND `course`=$course AND `class`=$cls
        )";
runDBQry($dbh, $insertSQL);
$TAssess=$row_TAss_struct=$glue=$CA=$ca_typ=$Max=$Per=$GRP=$ca_lst=$att_cnt=$grp_inf=$attends=$prev_terms=null;
course_sheet($arm, $cls, $term, $course, _xget('all') == "1");
 
if (isset($_GET['cls'])) {
    $_SESSION['ass_cls'] = _xget('cls');
    $_SESSION['ass_arm'] = _xget('arm');
}
if (isset($_GET['trm'])) {
    $_SESSION['ass_trm'] = _xget('trm');
}
$dcls = _xses('ass_cls');
$darm = _xses('ass_arm'); 
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
<script type="text/javascript" src="assess.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    isEdit = false;
    var stud_cnt = <?php echo count($TAssess) ?>;
    var att_cnt = <?php echo $att_cnt ?>;
    var cls_typ = <?php echo $row_TAss_struct['cls_typ'] ?>;
    
    var str = "<?php echo $row_TAss_struct['ass_names'] . $glue . $row_TAss_struct['cls_names'] ?>";
    var ass_names = str.length == 0 ? new Array() : str.split("|");
    str = "<?php echo $row_TAss_struct['ass_codes'] . $glue . $row_TAss_struct['cls_codes'] ?>";
    var ass_codes = str.length == 0 ? new Array() : str.split("|");
    str = "<?php echo $row_TAss_struct['ass_ca'] . $glue . $row_TAss_struct['cls_ca'] ?>";
    var ass_ca = str.length == 0 ? new Array() : str.split("|");

    str = "<?php echo $row_TAss_struct['ass_state'] . $glue . $row_TAss_struct['cls_state'] ?>";
    var ass_state = str.length == 0 ? new Array() : str.split("|");
    str = "<?php echo $row_TAss_struct['ass_grp'] ?>";
    var ass_grp = str.length == 0 ? new Array() : str.split("|");
    str = "<?php echo $row_TAss_struct['cls_grp_inf'] ?>";
    var cls_grp_inf = str.length == 0 ? new Array() : str.split("|");

    str = "<?php echo $row_TAss_struct['percentages'] . $glue . $row_TAss_struct['cls_percentages'] ?>";
    var percentages = str.length == 0 ? new Array() : str.split("|");
    str = "<?php echo $row_TAss_struct['max_scores'] . $glue . $row_TAss_struct['cls_max_scores'] ?>";
    var max_scores = str.length == 0 ? new Array() : str.split("|");
    
</script>
        <!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
    </head>
    <body>
        <script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script>
        <script charset="UTF-8" src="../menu.js" type="text/javascript"></script>
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
                                        <td style="height:30px; min-width:500px; background-image:url(/images/lblassess.png); background-repeat:no-repeat">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="h1" height="5px"></td>
                                    </tr>
                                    <tr>
                                        <td><?php include('../../../scripts/buttonset.php') ?></td>
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
                                            <td width="120" class="titles"><?php echo COURSE ?>:</td>
                                            <td align="left"><?php echo $row_TAss_struct['course_name'] ?></td>
                                          </tr>
                                          <tr>
                                            <td class="titles"><?php echo LEVEL ?>:</td>
                                            <td><table border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                  <td nowrap="nowrap"><?php echo $dcls, isset($_GET['all']) ? '' : ' > ' . $darm; ?></td>
                                                  <td>&nbsp;</td>
                                                  <?php if (!isset($_GET['all'])) { ?>
                                                  <td><a href="view.php?all=1"><img src="/images/but_show_all.png" width="70" height="20" /></a></td>
                                                  <?php } else { ?>
                                                  <td>&nbsp;</td>
                                                  <td><a class="blue-normal" href="view.php"><b>Show only "<?php echo $darm ?>"</b></a></td>
                                                  <?php } ?>
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
                                                <table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC" id="ass_tab"><thead>
                                                        <tr align="center" class="boldwhite1">
                                                            <td colspan="2" align="right" bgcolor="#000000">Max. Score:</td>
                                                            <?php $grp_i = $GRP[0];
                                                            for ($c = 0; $c < count($Per); $c++) { ?>
                                                                <td bgcolor="#006600"><?php echo $ca_typ[$c] == 0 ? $Max[$c] : ($ca_typ[$c] == -1 ? $att_cnt : 100);
                                                            if ($ca_typ[$c] == -1) { ?><script type="text/javascript">max_scores[<?php echo $c ?>]=<?php echo $att_cnt ?></script><?php } ?></td>
                                                                <?php
                                                                if ($grp_i != 0 && $grp_i != $GRP[$c + 1]) {
                                                                    $grp_i = $GRP[$c + 1];
                                                                    ?>
                                                                    <td bgcolor="#99CC00">100</td>
                                                                <?php }
                                                            } ?>
                                                            <td colspan="5" rowspan="2" bgcolor="#000000">&nbsp;</td>
                                                        </tr>
                                                        <tr align="center" bgcolor="#045CC8" class="boldwhite1">
                                                            <td colspan="2" align="right" bgcolor="#000000">% of Total:</td>
                                                            <?php $grp_i = $GRP[0];
                                                            for ($c = 0; $c < count($Per); $c++) { ?>
                                                                <td bgcolor="#006600"><?php echo $Per[$c]; ?></td>
                                                                <?php
                                                                if ($grp_i != 0 && $grp_i != $GRP[$c + 1]) {
                                                                    $grp_i = $GRP[$c + 1];
                                                                    ?>
                                                                    <td bgcolor="#99CC00"><?php echo $grp_inf[$GRP[$c]][3]; ?></td>
                                                                <?php }
                                                            } ?>
                                                        </tr>
                                                        <tr align="center" bgcolor="#045CC8" class="boldwhite1">
                                                            <td>#</td>
                                                            <td><a id="col_nam" class="boldwhite1" href="javascript: void(0)" onclick="sort(-2)">Name<img id="img_sort" src="/images/descend.gif" width="10" height="10" border="0" /></a>
                                                                <input name="assess_cnt" id="assess_cnt" type="hidden" value="<?php echo count($TAssess) ?>" /></td>
                                                            <?php $grp_i = $GRP[0];
                                                            for ($c = 0; $c < count($CA); $c++) { ?>
                                                                <td nowrap="nowrap"><a id="col_<?php echo $c ?>" class="boldwhite1" href="javascript: void(0)" onclick="sort(<?php echo $c ?>)"><?php echo $CA[$c]; ?></a></td>
                                                                <?php
                                                            if ($grp_i != 0 && $grp_i != $GRP[$c+1]) {
                                                                $grp_i = $GRP[$c+1];
                                                                ?>
                                                                <td nowrap="nowrap" bgcolor="#00CCFF"><a id="col_grp_<?php echo $GRP[$c] ?>" class="boldwhite1" href="javascript: void(0)" onclick="sort(-3, <?php echo $GRP[$c] ?>)"><?php echo $grp_inf[$GRP[$c]][1]; ?></a></td>
                                                            <?php }} ?>                  
                                                            <td><a id="col_tot" class="boldwhite1" href="javascript: void(0)" onclick="sort(-1)">Total</a></td>
                                                            <td nowrap="nowrap"><a class="boldwhite1" href="javascript: void(0)" onclick="sort(-1)">Pos</a></td>
                                                            <td nowrap="nowrap">Answer Sheets</td>
                                                            <td>Comments</td>
                                                            <td>Notes</td>
                                                        </tr></thead>
                                                    <tbody>                                           <tr id="row_">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <?php
                                                        $j = 0;
                                                        foreach ($TAssess as $row_TAssess) {
                                                            $k = $j % 2;
                                                            $rowdefcolor = ($k == 1) ? "#E5E5E5" : "#D5D5D5";
                                                            ?>
                  
                                                            <tr bgcolor="<?php echo $rowdefcolor ?>" class="ass_row" 
                                                                onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" id="row_<?php echo $j ?>">
                                                                <td align="left" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="red-normal" id="num_<?php echo $j ?>"><strong><?php echo $j + 1 ?></strong></td>
                                                                <td align="left" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b id="stud_name_<?php echo $j ?>"><?php echo $row_TAssess['VendorName'] ?></b>
                                                                    <input name="assess_id_<?php echo $j ?>" id="assess_id_<?php echo $j ?>" type="hidden" value="<?php echo $row_TAssess['assess_id'] ?>" />
                                                                    <input name="scores_<?php echo $j ?>" id="scores_<?php echo $j ?>" type="hidden" value="<?php echo $row_TAssess['scores'] ?>" />
                                                                    <input name="prvw_<?php echo $j ?>" id="prvw_<?php echo $j ?>" type="hidden" value="<?php echo $row_TAssess['attachs'] ?>" />
                                                                    <input name="attach_<?php echo $j ?>" id="attach_<?php echo $j ?>" type="hidden" value="" /></td>
                                                                <?php
                                                                $Score = explode("|", $row_TAssess['scores']);
                                                                $grp_i = $GRP[0];
                                                                for ($m = 0; $m < count($Per); $m++) {
                                                                    switch ($ca_typ[$m]) {
                                                                        case -1:
                                                                            $val = $attends[$j];
                                                                            break;
                                                                        case 0:
                                                                            $val = count($Score) > $m ? $Score[$m] : "";
                                                                            break;
                                                                        default:
                                                                            $val = $prev_terms[$ca_typ[$m]][$row_TAssess['student']];
                                                                            break;
                                                                    }
                                                                    ?>
                                                                    <td><input name="ca_<?php echo $j, '_', $m ?>" type="text" id="ca_<?php echo $j, '_', $m ?>" style="text-align:right" onchange="numme(this); cummulate(<?php echo $j ?>);SetPosition()" value="<?php echo $val; ?>" size="3" disabled="disabled" /></td>
                                                                    <?php
                                                            if ($grp_i != 0 && $grp_i != $GRP[$m+1]) {
                                                                $grp_i = $GRP[$m+1];
                                                                ?>
                                                                <td><input name="grp_<?php echo $j, '_', $GRP[$m] ?>" type="text" id="grp_<?php echo $j, '_', $GRP[$m] ?>" style="text-align:right" size="3" disabled="disabled" /></td>
                                                                <?php }} ?>
                                                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input name="total_<?php echo $j ?>" type="text" id="total_<?php echo $j ?>" size="3" style="text-align:right" disabled="disabled" /></td>
                                                                <td nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input name="pos_<?php echo $j ?>" type="text" id="pos_<?php echo $j ?>" size="3" style="text-align:right" disabled="disabled" /></td>
                                                                <td nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><table border="0" cellspacing="0" cellpadding="0">
                                                                        <tr>
                                                                            <td><select name="cmb_atch_<?php echo $j ?>" id="cmb_atch_<?php echo $j ?>">
                                                                                </select></td>
                                                                            <td><a href="javascript: void(0)" onclick="ass_itms[<?php echo $j ?>].show()"><img src="/images/but_show.png" width="60" height="20" /></a></td>
                                                                        </tr>
                                                                    </table></td>
                                                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><textarea name="comments_<?php echo $j ?>" style="width:300px" rows="1" readonly="readonly" id="comments_<?php echo $j ?>"><?php echo $row_TAssess['comments'] ?></textarea></td>
                                                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><textarea name="notes_<?php echo $j ?>" style="width:300px" rows="1" readonly="readonly" id="notes_<?php echo $j ?>"><?php echo $row_TAssess['Notes'] ?></textarea></td>
                                                            </tr></tbody>
                                        <?php $j++;
                                    } ?>
                                                </table>
                                      </div></td>
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