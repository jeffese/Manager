<?php
require_once('../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Course Selection'));
vetAccess('Academics', 'Course Selection', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmcrselect","","view.php?id=$id","","","","");
$rec_status = 3;

isAdviser(_xses('offer_arm'), true);
viewTerm(_xses('offer_term'), true);
$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmcrselect") {
    $sql = sprintf("UPDATE `%s`.`sch_course_offer` SET `courses`=%s, `gp`=%s, `Notes`=%s 
    WHERE `offer_id`=0%s",
               $_SESSION['DBCoy'],
               GSQLStr(_xpost('courses'), "text"),
               GSQLStr(_xpost('gp'), "int"),
               GSQLStr(_xpost('Notes'), "text"),
               GSQLStr(_xpost('offer_id'), "int"));
    $update = runDBQry($dbh, $sql);
    header("Location: view.php?id=$id");
    exit;
}
    
$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_electives` WHERE `elect_id`=0" . $_SESSION['offer_elect_id'];
$row_TElectives = getDBDataRow($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`sch_course_offer` WHERE `offer_id`=0" . _xses('offer_id');
$row_TOffers = getDBDatarow($dbh, $sql);

$crslst = str_replace("|", ",", _xvar_arr($row_TElectives, 'courses') .
        (strlen(_xvar_arr($row_TElectives, 'courses')) > 0 && strlen(_xvar_arr($row_TOffers, 'courses')) > 0 ? '|' : '') .
        _xvar_arr($row_TOffers, 'courses'));
$crslst = strlen($crslst) == 0 ? '0' : $crslst;

$sql = "SELECT course_id, CONCAT(course_name, ' {', course_code, '}') as `course`
FROM `{$_SESSION['DBCoy']}`.`sch_courses`
WHERE course_id IN ($crslst)";
$TCourses = getDBData($dbh, $sql);
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
<script type="text/javascript" src="course.js"></script>
<script language="JavaScript1.2" type="text/javascript">
	var arrFormValidation=[];
	
<?php foreach ($TCourses as $row_TCourses) { ?>
	crs_names.push("<?php echo $row_TCourses['course'] ?>");
	crs_ids.push("<?php echo $row_TCourses['course_id'] ?>");
<?php } ?>
    
    var min = <?php echo _xvar_arr($row_TElectives, min) ?>;
    var max = <?php echo _xvar_arr($row_TElectives, max) ?>;
    var min_gp = <?php echo _xvar_arr($row_TElectives, min_gp) ?>;
    var max_gp = <?php echo _xvar_arr($row_TElectives, max_gp) ?>;
    
    var str = "<?php echo _xvar_arr($row_TElectives, courses) ?>";
    var crs_lst = str.length == 0 ? new Array() : str.split("|");
    str = "<?php echo _xvar_arr($row_TElectives, gps) ?>";
    var gps_lst = str.length == 0 ? new Array() : str.split("|");
    str = "<?php echo _xvar_arr($row_TElectives, core_elec) ?>";
    var cor_lst = str.length == 0 ? new Array() : str.split("|");
    str = "<?php echo _xvar_arr($row_TElectives, grp) ?>";
    var grp_lst = str.length == 0 ? new Array() : str.split("|");
    str = "<?php echo _xvar_arr($row_TElectives, grp_inf) ?>";
    var inf_lst = str.length == 0 ? new Array() : str.split("|");
    
    str = "<?php echo $row_TOffers['courses'] ?>";
    var sel_lst = str.length == 0 ? new Array() : str.split("|");
    
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
        <td width="240" valign="top"><img src="/images/course_select.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/<?php echo COURSE=='Subject' ? 'lblsubjsel' : 'lblselect.png)' ?>.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return vetGPs()" method="post" enctype="multipart/form-data" name="frmcrselect" id="frmcrselect">
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
                    <td class="titles">Student:</td>
                    <td align="left"><?php echo _xses('offer_stud') ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Class Name:</td>
                    <td align="left"><?php echo _xses('offer_class') ?></td>
                    </tr>
                  <tr>
                    <td class="titles"><?php echo TERM ?>:</td>
                    <td><?php echo _xses('offer_trm') ?></td>
                    </tr>
                  <tr>
                    <td class="titles">No. Limits:</td>
                    <td><table border="1" cellspacing="0" cellpadding="2">
                      <tr>
                        <td bgcolor="#333333" class="tinywhite">Min:</td>
                        <td><?php echo _xvar_arr($row_TElectives, min) ?></td>
                        <td bgcolor="#333333" class="tinywhite">Max:</td>
                        <td><?php echo _xvar_arr($row_TElectives, max) ?></td>
                        <td bgcolor="#333333" class="tinywhite">Total:</td>
                        <td><input name="tot" type="text" id="tot" size="4" readonly="readonly" /></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">GP Limits:</td>
                    <td><table border="1" cellspacing="0" cellpadding="4">
                      <tr>
                        <td bgcolor="#333333" class="tinywhite">Min:</td>
                        <td><?php echo _xvar_arr($row_TElectives, min_gp) ?></td>
                        <td bgcolor="#333333" class="tinywhite">Max:</td>
                        <td><?php echo _xvar_arr($row_TElectives, max_gp) ?></td>
                        <td bgcolor="#333333" class="tinywhite">Total:</td>
                        <td><input name="totgp" type="text" id="totgp" size="4" readonly="readonly" /></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Min. GP for Promotion:</td>
                    <td><?php echo _xvar_arr($row_TElectives, promote_gp) ?></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                  <tr>
                    <td colspan="2" align="center"><input type="hidden" name="courses" id="courses" value="<?php echo $row_TOffers['courses'] ?>" />
                      <input type="hidden" name="gps" id="gps" value="<?php echo $row_TOffers['gp'] ?>" />
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
                        </table>
                      <table border="0" cellpadding="0" cellspacing="0" style="margin:10px">
                        <tr>
                          <td class="bx_tl"></td>
                          <td class="bx_tp"></td>
                          <td class="bx_tr"></td>
                          </tr>
                        <tr>
                          <td rowspan="2" class="bx_lf"></td>
                          <td class="bx_title">Carry-Over <?php echo COURSE ?>s</td>
                          <td rowspan="2" class="bx_rt"></td>
                          </tr>
                        <tr>
                          <td class="bx_center"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <td class="black-normal" id="bx_over">&nbsp;</td>
                              </tr>
                            </table></td>
                          </tr>
                        <tr>
                          <td class="bx_bl"></td>
                          <td class="bx_bt"></td>
                          <td class="bx_br"></td>
                          </tr>
                        </table>
                      <div><span class="red-normal"><b>Info</b></span><b>:</b><?php echo _xvar_arr($row_TElectives, Notes) ?></div></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="5" id="Notes" style="width:300px"><?php echo $row_TOffers['Notes'] ?></textarea></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><?php include('../../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmcrselect" />
            <input name="offer_id" type="hidden" id="offer_id" value="<?php echo $row_TOffers['offer_id']; ?>" />
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