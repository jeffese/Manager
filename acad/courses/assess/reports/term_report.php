<?php
require_once('../../../../scripts/init.php');
require_once('../ass_methods.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Assessments'));
vetAccess('Academics', 'Assessments', 'print');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "", "", "", "", "", "", "", "", "index.php");
$rec_status = 0;

$idStr = "";
$arm = _xvarloopstore('cid', 'assess_arm');
$term = _xvarloopstore('tid', 'assess_term');
isAdviser($arm);
viewTerm($term);

term_report($arm, $term);

if (isset($_GET['cls'])) {
    $_SESSION['ass_cls'] = _xget('cls');
}
if (isset($_GET['trm'])) {
    $_SESSION['ass_trm'] = _xget('trm');
}

$sql = "SELECT `grade_code`, `max`
    FROM `{$_SESSION['DBCoy']}`.`sch_grades`
    INNER JOIN `{$_SESSION['DBCoy']}`.`sch_programs` ON `sch_grades`.`grd_sys`=`sch_programs`.`grade`
    INNER JOIN `{$_SESSION['DBCoy']}`.`sch_class` ON `sch_programs`.`prog_id`=`sch_class`.`program`
    INNER JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `sch_class`.`class_id`=`sch_arms`.`class`
    WHERE `arm_id`=$arm ORDER BY `max`";
$TGrades = getDBData($dbh, $sql);
//var_dump($Term_data);
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
        <script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
        <script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="report.js"></script>
        <script type="text/javascript">
            var high_av = <?php echo $high_av ?>;
            var cls_av = <?php echo $cls_av ?>;
            var stud_str = "<?php echo TabKeyImplode($stud_data) ?>";
            var course_arr = new Array(""<?php foreach ($Term_data as $data) {
                                echo ', "' . $data['highest'] . '~~&&~~' . 
                                        RowImplode($data['config']) . '~~&&~~' . 
                                        TabKeyImplode($data['students']) . '"';
                           } ?>);
            var grades = new Array(''<?php foreach ($TGrades as $row_TGrades) echo ", '{$row_TGrades['grade_code']}'" ?>);
            var range = new Array(0<?php foreach ($TGrades as $row_TGrades) echo ", ", $row_TGrades['max'] ?>);
        </script>
                <!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
    </head>
    <body>
        <script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script>
        <script charset="UTF-8" src="../../../programs/classes/menu.js" type="text/javascript"></script>
        <script type="text/javascript">awmBuildMenu();</script>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="240" valign="top"><img src="/images/reports.jpg" alt="" width="240" height="300" />
                        <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
                            <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                                    <tr>
                                        <td style="height:30px; min-width:500px; background-image:url(/images/lbl_reports.png); background-repeat:no-repeat">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="h1" height="5px"></td>
                                    </tr>
                                    <tr>
                                        <td><?php include('../../../../scripts/buttonset.php')?></td>
                                    </tr>
                                    </table>
                                <table width="100%" border="0" cellspacing="4" cellpadding="4">
                                  <tr>
                                      <td><table border="0" cellpadding="4" cellspacing="4">
                                        <tr>
                                          <td width="120" class="titles"><?php echo LEVEL ?>:</td>
                                          <td><?php echo _xses('ass_cls').(strlen(_xses('ass_arm'))>0 && _xget('all')=="" ? ' > '._xses('ass_arm') : '') ?></td>
                                        </tr>
                                        <tr>
                                          <td class="titles"><?php echo TERM ?>:</td>
                                          <td><?php echo _xses('ass_trm') ?></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                        <td><div style="border:solid 2px #ff0000; background: #C0DFF1; padding:4px;  height:500px; overflow:scroll">
                                          <table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC" id="ass_tab">
                                            <thead>
                                              <tr align="center" bgcolor="#045CC8" class="boldwhite1">
                                                <td>#</td>
                                                <td>Name</td>
                                                <td>Average</td>
                                                <td nowrap="nowrap">Pos</td>
                                                <td>&nbsp;</td>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr id="row_">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                              </tr>
                                              <?php
                                                        $j = 0;
                                                        foreach ($stud_data as $studid => $row_data) {
                                                            $k = $j % 2;
                                                            $rowdefcolor = ($k == 1) ? "#E5E5E5" : "#D5D5D5";
                                                            ?>
                                              <tr bgcolor="<?php echo $rowdefcolor ?>" class="ass_row" 
                                                                onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" id="row_<?php echo $j ?>">
                                                <td align="left" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="red-normal" id="num_<?php echo $j ?>"><strong><?php echo $j + 1 ?></strong></td>
                                                <td align="left" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b id="stud_name_<?php echo $j ?>"><?php echo $row_data['name'] ?></b></td>
                                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input name="total_<?php echo $j ?>" type="text" id="total_<?php echo $j ?>" value="<?php echo $row_data['average'] ?>" size="3" style="text-align:center" disabled="disabled" /></td>
                                                <td nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input name="pos_<?php echo $j ?>" type="text" id="pos_<?php echo $j ?>" value="<?php echo $row_data['pos'] ?>" size="3" style="text-align:center" disabled="disabled" /></td>
                                                <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><table border="0" cellspacing="2" cellpadding="2">
                                                  <tr>
                                                    <td><a class="darkgrey boldme" onclick="return GB_showCenter('Term Report', '/acad/courses/assess/reports/print001.php?id=<?php echo $j ?>', 600,800)" href="javascript: void(0)"><img src="/images/icons/printer.png" alt="" width="16" height="16" />Print</a></td>
                                                    <td>&nbsp;</td>
                                                    <td><a class="darkgrey boldme" onclick="genpdf(0)" href="javascript: void(0)"><img src="/images/icons/page_white_acrobat.png" width="16" height="16" />PDF</a></td>
                                                  </tr>
                                                </table></td>
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
                                        <td><?php include('../../../../scripts/buttonset.php')?></td>
                                    </tr>

                                </table></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>&nbsp;</td>
            </tr>
        </table>
    </body>
</html>