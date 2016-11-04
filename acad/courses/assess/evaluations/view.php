<?php
require_once('../../../../scripts/init.php');
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Assessments'));
vetAccess('Academics', 'Assessments', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, $access['Print'], 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "edit.php?id=$id", "", "", "", "", "", "return GB_showCenter('". COURSE ." Assessment', 'print.php?id=$id', 600,600)", "../index.php?ass=cls&eval=1");
$rec_status = 1;

$idStr = "";
$arm = _xvarloopstore('cid', 'assess_arm');
$term = _xvarloopstore('tid', 'assess_term');
$course = _xvarloopstore('crsid', 'assess_crs');
vetClassView($arm);
viewTerm($term);

$sql = "SELECT `cls_ass`, `cls_ass_state`, `cls_sub`, `cls_sub_state`
        FROM `{$_SESSION['DBCoy']}`.`sch_cls_ass_struct`
        INNER JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `sch_cls_ass_struct`.`class`=`sch_arms`.`class`
        WHERE `cls_term`=$term AND `sch_arms`.`arm_id`=$arm";
$row_TAss_struct = getDBDataRow($dbh, $sql);

$insertSQL = "INSERT INTO `{$_SESSION['DBCoy']}`.`sch_evaluate` (`class`, `term`, `student`) 
    SELECT $arm, $term, `VendorID` 
        FROM `{$_SESSION['DBCoy']}`.`vendors`
        WHERE `DeptID`=$arm AND VendorType=7 AND `VendorID` NOT IN (
        SELECT `student` FROM `{$_SESSION['DBCoy']}`.`sch_evaluate`
        WHERE `term`=$term AND `class`=$arm)";
runDBQry($dbh, $insertSQL);

$sql = "SELECT `sch_evaluate`.*, $vendor_sql
        FROM `{$_SESSION['DBCoy']}`.`sch_evaluate`
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_evaluate`.`student`=`vendors`.`VendorID`
        WHERE `sch_evaluate`.`term`=$term AND `class`=$arm ORDER BY `VendorName`";
$TAssess = getDBData($dbh, $sql);

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
<script type="text/javascript" src="assess.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    isEdit = false;
    var stud_cnt = <?php echo count($TAssess) ?>;
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script> 
<script charset="UTF-8" src="../../../programs/classes/menu.js" type="text/javascript"></script> 
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
                <td style="height:30px; min-width:500px; background-image:url(/images/lbl_evaluate.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                    <tr>
                      <td></td>
                      <td align="center"><?php echo catch_error($errors) ?></td>
                      <td rowspan="4">&nbsp;</td>
                      <td width="100%" rowspan="4" style="overflow:scroll"><div id="ass_nam_box" style="padding:4px; height:100px; overflow:scroll"></div></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles"><?php echo LEVEL ?>:</td>
                      <td nowrap="nowrap"><?php $fname = _xses('ass_cls').(strlen(_xses('ass_arm'))>0 && _xget('all')=="" ? ' > '._xses('ass_arm') : '');  echo $fname  ?></td>
                    </tr>
                    <tr>
                      <td class="titles"><?php echo TERM ?>:</td>
                      <td><?php echo _xses('ass_trm') ?></td>
                    </tr>
                    <tr>
                      <td height="100%" valign="top" class="titles">&nbsp;</td>
                      <td valign="top">&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td><div style="border:solid 2px #ff0000; background: #C0DFF1; padding:4px; width:800px; height:500px; overflow:scroll">
                    <table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC" id="ass_tab">
                      <thead>
                        <tr align="center" bgcolor="#045CC8" class="boldwhite1">
                          <td>#</td>
                          <td><a id="col_nam" class="boldwhite1" href="javascript: void(0)" onclick="sort(-1)">Name<img id="img_sort" src="/images/descend.gif" width="10" height="10" border="0" /></a>
                            <input name="assess_cnt" id="assess_cnt" type="hidden" value="<?php echo count($TAssess) ?>" />
                            <input name="cls_ass" id="cls_ass" type="hidden" value="<?php echo $row_TAss_struct['cls_ass'] ?>" />
                            <input name="cls_ass_state" id="cls_ass_state" type="hidden" value="<?php echo $row_TAss_struct['cls_ass_state'] ?>" />
                            <input name="cls_sub" id="cls_sub" type="hidden" value="<?php echo $row_TAss_struct['cls_sub'] ?>" />
                            <input name="cls_sub_state" id="cls_sub_state" type="hidden" value="<?php echo $row_TAss_struct['cls_sub_state'] ?>" /></td>
                          <td nowrap="nowrap"><a id="col_score" class="boldwhite1" href="javascript: void(0)" onclick="sort(0)">Score</a></td>
                          <td nowrap="nowrap">Attachments</td>
                          <td>Comments</td>
                          <td>Notes</td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr id="row_">
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
                            <input name="student_<?php echo $j ?>" id="student_<?php echo $j ?>" type="hidden" value="<?php echo $row_TAssess['student'] ?>" />
                            <input name="scores_<?php echo $j ?>" id="scores_<?php echo $j ?>" type="hidden" value="<?php echo $row_TAssess['scores'] ?>" />
                            <input name="prvw_<?php echo $j ?>" id="prvw_<?php echo $j ?>" type="hidden" value="<?php echo $row_TAssess['attachs'] ?>" />
                            <input name="attach_<?php echo $j ?>" id="attach_<?php echo $j ?>" type="hidden" value="" />
                            <input name="comments_<?php echo $j ?>" id="comments_<?php echo $j ?>" type="hidden" value="<?php echo $row_TAssess['comments'] ?>" />
                            <input name="notes_<?php echo $j ?>" id="notes_<?php echo $j ?>" type="hidden" value="<?php echo $row_TAssess['Notes'] ?>" /></td>
                          <td align="center"><input name="ca_<?php echo $j ?>" type="text" id="ca_<?php echo $j ?>" style="text-align:right" size="3" disabled="disabled" /></td>
                          <td nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><table border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><select name="cmb_atch_<?php echo $j ?>" id="cmb_atch_<?php echo $j ?>">
                                  </select></td>
                                <td><a href="javascript: void(0)" onclick="ass_itms[<?php echo $j ?>].show()"><img src="/images/but_show.png" width="60" height="20" /></a></td>
                              </tr>
                            </table></td>
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><textarea name="com_<?php echo $j ?>" style="width:300px" rows="1" readonly="readonly" id="com_<?php echo $j ?>"></textarea></td>
                          <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><textarea name="not_<?php echo $j ?>" style="width:300px" rows="1" readonly="readonly" id="not_<?php echo $j ?>"></textarea></td>
                        </tr>
                      </tbody>
                      <?php $j++;
                                    } ?>
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