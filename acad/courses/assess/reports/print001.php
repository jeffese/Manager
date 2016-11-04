<?php
require_once('../../../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Assessments'));
vetAccess('Academics', 'Assessments', 'print');

$id = _xget('id');
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
<script type="text/javascript" src="print.js"></script>
<script language="JavaScript1.2" type="text/javascript">
	var px_dir = "<?php echo STUDPIX_DIR ?>";
	$(document).ready(function(){
		prepView(<?php echo $id ?>);
	});
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" style="margin:10px">
  <tr>
    <td class="rp_tl"></td>
    <td class="rp_tp"></td>
    <td class="rp_tr"></td>
  </tr>
  <tr>
    <td class="rp_rt"></td>
    <td><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td align="center"><table border="0" cellpadding="2" cellspacing="2">
          <tr>
            <td><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/xxpix.jpg" ?>" /></td>
            <td align="center"><span class="coytxt"><?php echo $_SESSION['COY']['CoyName'] ?></span><br />
              <span class="titles"><?php echo $_SESSION['COY']['Slogan'] ?></span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="1">
              <tr>
                <td align="center"><strong style="font-family: 'GoudyHandtooled BT'; font-size: 36px;">COGNITIVE REPORT</strong></td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="4" cellpadding="1">
                <tr>
                  <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td align="center"><table width="100%" border="0" cellpadding="1" cellspacing="1">
                        <tr>
                          <td class="titles">Name:</td>
                          <td colspan="7" id="stud_name">&nbsp;</td>
                          <td rowspan="3" align="center"><table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td class="picture_frame"><img src="" name="pix" id="pix" /></td>
                              </tr>
                            <tr>
                              <td><img src="/images/image-shadow.png" width="100%" height="12" /></td>
                              </tr>
                            </table></td>
                          </tr>
                        <tr>
                          <td class="titles"><?php echo LEVEL ?>:</td>
                          <td id="cls_name"><?php echo str_replace(' > ', ' ', _xses('ass_cls')) ?></td>
                          <td>&nbsp;</td>
                          <td class="titles"><?php echo TERM ?>:</td>
                          <td id="term"><?php echo _xses('ass_trm') ?></td>
                          <td>&nbsp;</td>
                          <td class="titles">Session:</td>
                          <td id="sess"><?php echo _xses('rep_sess') ?></td>
                          </tr>
                        <tr>
                          <td colspan="8"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td valign="top"><table border="0" cellspacing="4" cellpadding="4" style="background-color:#E2FAE3">
                                <tr>
                                  <td colspan="4" align="center" nowrap="nowrap" bgcolor="#006600" class="boldwhite1">Daily Attendance</td>
                                  </tr>
                                <tr>
                                  <td class="titles">Present:</td>
                                  <td class="boldwhite1 darkgreen" id="present">&nbsp;</td>
                                  <td class="titles">-</td>
                                  <td class="boldwhite1 darkgreen" id="per_pres">&nbsp;</td>
                                  </tr>
                                <tr>
                                  <td class="titles">Late:</td>
                                  <td class="boldwhite1 darkgreen" id="late">&nbsp;</td>
                                  <td class="titles">-</td>
                                  <td class="boldwhite1 darkgreen" id="per_late">&nbsp;</td>
                                  </tr>
                                <tr>
                                  <td class="titles">Absent:</td>
                                  <td class="boldwhite1 darkgreen" id="absent">&nbsp;</td>
                                  <td class="titles">-</td>
                                  <td class="boldwhite1 darkgreen" id="per_abs">&nbsp;</td>
                                  </tr>
                                </table></td>
                              <td>&nbsp;</td>
                              <td valign="top"><table border="0" cellspacing="4" cellpadding="4" style="background-color:#9ECEF1">
                                <tr>
                                  <td colspan="2" align="center" nowrap="nowrap" bgcolor="#003366" class="boldwhite1">Performance</td>
                                  </tr>
                                <tr>
                                  <td class="titles">Position</td>
                                  <td class="boldwhite1 blue-normal" id="position">&nbsp;</td>
                                  </tr>
                                <tr>
                                  <td class="titles">Average:</td>
                                  <td class="boldwhite1 blue-normal" id="average">&nbsp;</td>
                                  </tr>
                                </table></td>
                              <td valign="top">&nbsp;</td>
                              <td valign="top"><table border="0" cellspacing="4" cellpadding="4" style="background-color:#CCC">
                                <tr>
                                  <td colspan="2" align="center" nowrap="nowrap" bgcolor="#0" class="boldwhite1">Summary</td>
                                  </tr>
                                <tr>
                                  <td class="titles"><span class="titles">No. in Class</span></td>
                                  <td class="black-normal" id="classnum">&nbsp;</td>
                                  </tr>
                                <tr>
                                  <td class="titles"><span class="titles">Class Highest:</span></td>
                                  <td class="black-normal" id="classhigh">&nbsp;</td>
                                  </tr>
                                <tr>
                                  <td class="titles"><span class="titles">Class Average :</span></td>
                                  <td class="black-normal" id="classav">&nbsp;</td>
                                  </tr>
                                <tr>
                                  <td class="titles"><span class="titles">Total Obtainable:</span></td>
                                  <td class="black-normal" id="totobtain">&nbsp;</td>
                                  </tr>
                                <tr>
                                  <td class="titles"><span class="titles">Total Obtained:</span></td>
                                  <td class="black-normal" id="obtain">&nbsp;</td>
                                  </tr>
                                </table></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table></td>
                      </tr>
                    <tr>
                      <td align="center"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                        <thead>
                          <tr align="center" class="boldwhite1">
                            <td colspan="2">&nbsp;</td>
                            <td id="head_per" bgcolor="#999999">100</td>
                            <td colspan="4">&nbsp;</td>
                            </tr>
                          <tr align="center" bgcolor="#045CC8" class="boldwhite1">
                            <td bgcolor="#333366">D</td>
                            <td><?php echo COURSE ?></td>
                            <td id="head_tot">Total</td>
                            <td>Cls-Hi</td>
                            <td>Grd</td>
                            <td>Pos</td>
                            <td><?php echo LECTURER ?>'s Comments</td>
                            </tr>
                          </thead>
                        <tbody id="course_lst">
                          </tbody>
                        </table></td>
                      </tr>
                    <tr>
                      <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                        <tr>
                          <td><strong><?php echo LECTURER=="Teacher" ? "Class Teacher":"<?php echo COURSE ?> Adviser" ?>'s Comments:</strong></td>
                          </tr>
                        <tr>
                          <td id="comment" style="border: thin dashed #036; padding:10px">&nbsp;</td>
                          </tr>
                        <tr>
                          <td style="padding-top: 30px;"><strong>Signature &amp; Date:</strong> _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _</td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
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
              <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?></span></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td class="rp_lf"></td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td class="rp_bl"></td>
    <td class="rp_bt"></td>
    <td class="rp_br"></td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
	print();
});
  </script>
</body>
</html>