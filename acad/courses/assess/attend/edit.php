<?php
require_once('../../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Assessments'));
vetAccess('Academics', 'Assessments', 'Edit');

$ass = _xget("ass");

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmattend","","view.php?ass=$ass","","","","");
$rec_status = 3;

viewTerm(_xses('assess_term'), true);
$editFormAction = $_SERVER['PHP_SELF'] . set_QS()."?ass=$ass";

if (_xpost("MM_update") == "frmattend") {
    $dtTab = $ass == 'att' ? "sch_assess_struct" : "sch_cls_attend";
    $assTab = $ass == 'att' ? "sch_assess" : "sch_stud_attend";
    $sql = sprintf("UPDATE `%s`.`$dtTab` SET `attend_date`=%s WHERE `ass_struct_id`=%s",
                   $_SESSION['DBCoy'],
                   GSQLStr(_xpost("attend_date"), "text"),
                   GSQLStr(_xpost("ass_struct_id"), "int"));
    $update = runDBQry($dbh, $sql);
    
    $qry_cnt = intval(_xpost('assess_cnt'));
    for ($q=0; $q<$qry_cnt; $q++) {
        $assess_id = intval(_xpost("assess_id_$q"));
        $sql = sprintf("UPDATE `%s`.`$assTab` SET `attend`=%s, `comments`=%s, `Notes`=%s WHERE `assess_id`=%s",
                       $_SESSION['DBCoy'],
                       GSQLStr(_xpost("attend_$q"), "text"),
                       GSQLStr(_xpost("comments_$q"), "text"),
                       GSQLStr(_xpost("notes_$q"), "text"),
                       $assess_id);
        $update = runDBQry($dbh, $sql);
    }
    header("Location: view.php?ass=$ass");
    exit;
}

$class = _xvarloopstore('cid', 'assess_arm');
$term = _xvarloopstore('tid', 'assess_term');

if ($ass == 'att') {
	$course = _xvarloopstore('crsid', 'assess_crs');
        isTeacher($class, $course, $term, true);
	$sql = "SELECT `assess_id`, `attend`, `sch_assess`.`comments`, `sch_assess`.`Notes`, $vendor_sql 
	FROM `{$_SESSION['DBCoy']}`.`sch_assess` 
	INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_assess`.`student`=`vendors`.`VendorID`
	WHERE `DeptID`=$class AND `term`=$term AND `course`=$course ORDER BY `VendorName`";
	$TAssess = getDBData($dbh, $sql);
	
	$sql = "SELECT `ass_struct_id`, `attend_date`, `course_name`
	FROM `{$_SESSION['DBCoy']}`.`sch_assess_struct` 
	INNER JOIN `{$_SESSION['DBCoy']}`.`sch_courses` ON `sch_assess_struct`.`ass_course`=`sch_courses`.`course_id`
	WHERE `ass_arm`=$class AND `ass_term`=$term AND `ass_course`=$course";
	$row_TAss_struct = getDBDataRow($dbh, $sql);
} else {
    isAdviser($class, true);
    $sql = "SELECT `assess_id`, `attend`, `sch_stud_attend`.`comments`, `sch_stud_attend`.`Notes`, $vendor_sql 
    FROM `{$_SESSION['DBCoy']}`.`sch_stud_attend` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_stud_attend`.`student`=`vendors`.`VendorID`
    WHERE `DeptID`=$class AND `term`=$term ORDER BY `VendorName`";
    $TAssess = getDBData($dbh, $sql);

    $sql = "SELECT `ass_struct_id`, `attend_date` FROM `{$_SESSION['DBCoy']}`.`sch_cls_attend`
    WHERE `class`=$class AND `term`=$term";
    $row_TAss_struct = getDBDataRow($dbh, $sql);
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
    var isEdit = true;
    var stud_cnt = <?php echo count($TAssess) ?>;
    
    str = "<?php echo $row_TAss_struct['attend_date'] ?>";
    var att_dates = str.length == 0 ? new Array() : str.split("|");
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
        <td width="240" valign="top"><img src="/images/assess.jpg" alt="" width="240" height="300" />
          <div id="calwin"></div></td>
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
            <td><?php include('../../../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="frmattend" id="frmattend">
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
                    <td><?php echo _xses('ass_cls').(strlen(_xses('ass_arm'))>0 ? ' > '._xses('ass_arm') : '') ?></td>
                  </tr>
                  <tr>
                    <td class="titles"><?php echo TERM ?>:</td>
                    <td><?php echo _xses('ass_trm') ?></td>
                  </tr>
                </table><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
            <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
            <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
            <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
            <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
            <script type="text/javascript">
window.onload = function() {
    mCal = new dhtmlxCalendarObject('newdt', true, {
        isYearEditable: true, 
        isMonthEditable: true
    });
    mCal.attachEvent("onClick",function(date){   
        add_date(date);
    })
    mCal.setSkin('dhx_black');
}
            </script></td>
              </tr>
              <tr>
                <td><div style="border:solid 2px #ff0000; background: #C0DFF1; padding:4px; width:800px; height:500px; overflow:scroll">
                  <table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC" id="ass_tab"><thead>

                    <tr id="hd_blk" align="center" bgcolor="#045CC8" class="boldwhite1">
                      <td align="right" bgcolor="#000000">&nbsp;</td>
                      <td align="right" bgcolor="#000000">&nbsp;</td>
                      <td id="blk_add" colspan="2" bgcolor="#000000"><input name="newdt" type="text" id="newdt" style="width:70px; display:none" value="<?php echo date("Y-m-d") ?>" readonly="readonly" />
                        <a href="javascript: void(0)" onclick="$('#newdt').show()"><img src="/images/but_add.png" width="50" height="20" /></a></td>
                      <td colspan="2" bgcolor="#000000">&nbsp;</td>
                    </tr>
                    <tr id="hd_bl" align="center" bgcolor="#045CC8" class="boldwhite1">
                      <td>#</td>
                      <td><a id="col_nam" class="boldwhite1" href="javascript: void(0)" onclick="sort(-2)">Name<img id="img_sort" src="/images/descend.gif" width="10" height="10" border="0"></a>                        <input name="assess_cnt" id="assess_cnt" type="hidden" value="<?php echo count($TAssess) ?>" />
                        <span class="black-normal">
                        <input name="attend_date" id="attend_date" type="hidden" value="<?php echo $row_TAss_struct['attend_date'] ?>" />
                        </span></td>
                      <td id="bl_tot"><a id="col_tot" class="boldwhite1" href="javascript: void(0)" onclick="sort(-1)">Total</a></td>
                      <td nowrap="nowrap"><a class="boldwhite1" href="javascript: void(0)" onclick="sort(-1)">Position</a></td>
                      <td>Comments</td>
                      <td>Notes</td>
                    </tr></thead>
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
                    </tr></tbody>

                    <?php $j++;} ?>
                  </table>
                </div></td>
              </tr>
              <tr>
                <td><?php include('../../../../scripts/buttonset.php')?></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="frmattend" />
            <input type="hidden" name="ass_struct_id" id="ass_struct_id" value="<?php echo $row_TAss_struct['ass_struct_id'] ?>" />
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