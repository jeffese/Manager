<?php
require_once('../../scripts/init.php');
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Salaries'));
vetAccess('Personnel', 'Salaries', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(1, 1, 1, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","if (confirm('Are you sure you want to delete this Salary?')) document.location='del.php?id=$id'","","","","","index.php");
$rec_status = 1;

$id = _xget('id');

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`salaryscale` WHERE salary_id={$id}";
$row_TSal = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Salaries - <?php echo $row_TSal['salary_name'] ?> Details</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates.js"></script>
<script type="text/javascript">

    $(document).ready(function(){
            isEdit = false;
            prepView();
    });
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td class="h1">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center"><form id="Frm" name="Frm" method="post" action="">
      <input type="hidden" id="parts" name="parts" value="<?php echo $row_TSal['parts'] ?>" />
      <input type="hidden" id="typs" name="typs" value="<?php echo $row_TSal['typs'] ?>" />
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
              <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_sal" onclick="hideshow('sal', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_sal" onclick="hideshow('sal', 0, '')" style="display:none; cursor: pointer" /></div></td>
            </tr>
          </table></td>
          <td rowspan="2" class="bl_rt"></td>
        </tr>
        <tr>
          <td class="bl_center"><table border="0" cellspacing="4" cellpadding="2" id="bx_sal" style="display:none">
            <tr>
              <td width="650"><div id="sal_bx" style="overflow:scroll; padding:2px; max-width:650px; max-height:400px"></div></td>
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
              <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_bonus" onclick="hideshow('bonus', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_bonus" onclick="hideshow('bonus', 0, '')" style="display:none; cursor: pointer" /></div></td>
            </tr>
          </table></td>
          <td rowspan="2" class="bl_rt"></td>
        </tr>
        <tr>
          <td class="bl_center"><table width="100%" border="0" cellspacing="4" cellpadding="2" id="bx_bonus" style="display:none">
            <tr>
              <td width="650"><div id="bonus_bx" style="overflow:scroll; padding:2px; max-width:650px; max-height:400px"></div></td>
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
              <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_tax" onclick="hideshow('tax', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_tax" onclick="hideshow('tax', 0, '')" style="display:none; cursor: pointer" /></div></td>
            </tr>
          </table></td>
          <td rowspan="2" class="bo_rt"></td>
        </tr>
        <tr>
          <td class="bo_center"><table width="100%" border="0" cellspacing="2" cellpadding="2" id="bx_tax" style="display:none">
            <tr>
              <td width="650"><div id="tax_bx" style="overflow:scroll; padding:2px; max-width:650px; max-height:400px"></div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td class="bo_bl"></td>
          <td class="bo_bt"></td>
          <td class="bo_br"></td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr>
    <td align="center" class="h1">&nbsp;</td>
  </tr>
</table>
</body>
</html>