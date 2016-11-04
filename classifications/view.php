<?php
require_once("$vpth/scripts/init.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'View');

$_pth = dirname($_SERVER['PHP_SELF']);
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 1);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[$vnm]del.php?id=$id","","","find.php","print.php?id=$id","index.php");
$rec_status = 1;

$id = intval(_xget('id'));
$sql = "SELECT `classifications`.*, `edms_num`.*, `par`.`catname` AS parcat, Category 
FROM `{$_SESSION['DBCoy']}`.`classifications` 
INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` `par` ON `classifications`.`parent_id`=`par`.`category_id`
LEFT JOIN `{$_SESSION['DBCoy']}`.`status`                 ON `classifications`.`cat_tag`=`status`.`CategoryID`  
LEFT JOIN `{$_SESSION['DBCoy']}`.`edms_num`               ON `classifications`.`catID`=`edms_num`.`doc_cat`
WHERE `classifications`.`catID`=$id";
$row_TDept = getDBDataRow($dbh, $sql);

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
<script type="text/javascript">
$(document).ready(function() {
    if (window.innerWidth<720) {
		$('#subpx').hide();
	}
});
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><img src="/images/<?php echo $vcat ?>.jpg" id="subpx" alt="" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lbl<?php echo $vcat ?>.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td><?php include("$vpth/scripts/buttonset.php")?></td>
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
                    <td class="titles">Code:</td>
                    <td align="left"><?php echo $row_TDept['code'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><?php echo $row_TDept['category_name'] ?></td>
                  </tr>
                  <?php if (isset($vcode) && $_SESSION['accesskeys']['Academics']['View'] != -1) { ?>
                  <tr>
                    <td class="titles">Type:</td>
                    <td align="left"><?php echo $row_TDept['Category'] ?></td>
                  </tr>
                  <?php } ?>
                  <tr>
                    <td class="titles">Parent <?php echo $vnm ?>:</td>
                    <td><?php echo $row_TDept['parcat'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Full Name:</td>
                    <td><?php echo $row_TDept['catname'] ?></td>
                  </tr>
			<?php if ($vtype == 8) { ?>
                  <tr>
                    <td class="titles">Prefix:</td>
                    <td><?php echo $row_TDept['prefix'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Auto Number:</td>
                    <td><?php echo $row_TDept['autonum'] ?></td>
                  </tr>
                      <?php } ?>
                  <tr>
                    <td width="120" class="titles">Description:</td>
                    <td><textarea name="description" rows="3" readonly="readonly" id="description" style="width:300px"><?php echo $row_TDept['description'] ?></textarea></td>
                  </tr>
                  <?php if (isset($vframe) && $_SESSION['accesskeys']['Stock']['View'] == 1) { ?>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td><table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
                      <tr>
                        <td class="bl_tl"></td>
                        <td class="bl_tp"></td>
                        <td class="bl_tr"></td>
                      </tr>
                      <tr>
                        <td rowspan="2" class="bl_lf"></td>
                        <td align="left" class="bl_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td nowrap="nowrap">Documents</td>
                            <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_docs" onclick="hideshow('docs', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_docs" onclick="hideshow('docs', 0, '')" style="display:none; cursor: pointer" /></div></td>
                          </tr>
                        </table></td>
                        <td rowspan="2" class="bl_rt"></td>
                      </tr>
                      <tr>
                        <td class="bl_center"><table width="100%" border="0" cellspacing="2" cellpadding="2" id="bx_docs" style="display:none">
                          <tr>
                            <td><?php $doc_shelf = $vmod.DS.$vkey;
							$doc_id = $id; ?><?php include "$vpth/scripts/viewdoc.php" ?></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td class="bl_bl"></td>
                        <td class="bl_bt"></td>
                        <td class="bl_br"></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Item Notes:</td>
                    <td id="prodesc">&nbsp;</td>
                  </tr>
                  <?php } ?>
                </table></td>
              </tr>
                  <?php if (isset($vframe) && $_SESSION['accesskeys']['Stock']['View'] == 1) { ?>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><iframe style=" width:700px; height:400px; border:none" src="<?php echo $vframe ?>"></iframe></td>
              </tr>
                  <?php } ?>
              <tr>
                <td><?php include("$vpth/scripts/buttonset.php"); ?></td>
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