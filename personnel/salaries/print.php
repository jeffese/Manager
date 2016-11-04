<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Salaries'));
vetAccess('Personnel', 'Salaries', 'Print');

$id = _xget('id');

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`salaryscale` WHERE salary_id={$id}";
$row_TSal = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates.js"></script>
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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblsalary.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><?php echo $row_TSal['salary_name'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Period:</td>
                    <td><script type="text/javascript">
switch(<?php echo $row_TSal['period'] ?>) {
	case 0:
		document.write('Monthly');
		break;
	case 1:
		document.write('Weekly');
		break;
	case 2:
		document.write('Daily');
		break;
	case 3:
		document.write('Custom');
		break;
}
                </script></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center"><input type="hidden" id="parts" name="parts" value="<?php echo $row_TSal['parts'] ?>" />
                      <input type="hidden" id="typs" name="typs" value="<?php echo $row_TSal['typs'] ?>" />
                      <input type="hidden" id="cmls" name="cmls" value="<?php echo $row_TSal['cmls'] ?>" />
                      <input type="hidden" id="ftyp" name="ftyp" value="<?php echo $row_TSal['ftyp'] ?>" />
                      <input type="hidden" id="oprs" name="oprs" value="<?php echo $row_TSal['oprs'] ?>" />
                      <input type="hidden" id="flds" name="flds" value="<?php echo $row_TSal['flds'] ?>" />
                      <input type="hidden" id="wins" name="wins" value="<?php echo $row_TSal['wins'] ?>" />
                      <input type="hidden" id="fncs" name="fncs" value="<?php echo $row_TSal['fncs'] ?>" />
                      <input type="hidden" id="state" name="state" value="<?php echo $row_TSal['state'] ?>" />
                      <table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
                        <tr>
                          <td class="bl_tl"></td>
                          <td class="bl_tp"></td>
                          <td class="bl_tr"></td>
                        </tr>
                        <tr>
                          <td rowspan="2" class="bl_lf"></td>
                          <td align="left" class="bl_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="60">Salary</td>
                              </tr>
                          </table></td>
                          <td rowspan="2" class="bl_rt"></td>
                        </tr>
                        <tr>
                          <td class="bl_center"><table border="0" cellspacing="4" cellpadding="2" id="bx_sal">
                            <tr>
                              <td><div id="sal_bx" style="overflow:scroll; padding:2px;"></div></td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td class="bl_bl"></td>
                          <td class="bl_bt"></td>
                          <td class="bl_br"></td>
                        </tr>
                      </table>
                      <table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
                        <tr>
                          <td class="bl_tl"></td>
                          <td class="bl_tp"></td>
                          <td class="bl_tr"></td>
                        </tr>
                        <tr>
                          <td rowspan="2" class="bl_lf"></td>
                          <td align="left" class="bl_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="60">Bonus</td>
                              </tr>
                          </table></td>
                          <td rowspan="2" class="bl_rt"></td>
                        </tr>
                        <tr>
                          <td class="bl_center"><table width="100%" border="0" cellspacing="4" cellpadding="2" id="bx_bonus">
                            <tr>
                              <td><div id="bonus_bx" style="overflow:scroll; padding:2px;"></div></td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td class="bl_bl"></td>
                          <td class="bl_bt"></td>
                          <td class="bl_br"></td>
                        </tr>
                    </table>
                      <table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
                        <tr>
                          <td class="bo_tl"></td>
                          <td class="bo_tp"></td>
                          <td class="bo_tr"></td>
                        </tr>
                        <tr>
                          <td rowspan="2" class="bo_lf"></td>
                          <td align="left" class="bo_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="60">Tax</td>
                              </tr>
                          </table></td>
                          <td rowspan="2" class="bo_rt"></td>
                        </tr>
                        <tr>
                          <td class="bo_center"><table width="100%" border="0" cellspacing="2" cellpadding="2" id="bx_tax">
                            <tr>
                              <td><div id="tax_bx" style="overflow:scroll; padding:2px;"></div></td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td class="bo_bl"></td>
                          <td class="bo_bt"></td>
                          <td class="bo_br"></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Description:</td>
                    <td align="left"><textarea name="description" rows="4" readonly="readonly" id="description" style="width:300px"><?php echo $row_TSal['description'] ?></textarea></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>

            </table></td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br /><?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?>
          </span></td>
        </tr>
      </table></td>
  </tr>
</table><script type="text/javascript">
$(document).ready(function(){
        isEdit = false;
        prepView();
        $("#bx_sal").width(screen.availWidth - 200);
        $("#bx_bonus").width(screen.availWidth - 200);
        $("#bx_tax").width(screen.availWidth - 200);
	print();
});
</script>
</body>
</html>