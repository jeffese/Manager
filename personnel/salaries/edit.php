<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Salaries'));
vetAccess('Personnel', 'Salaries', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmSal","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "Frm") {
  $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`salaryscale` SET `salary_name`=%s, 
                    `period`=%s, `parts`=%s, `typs`=%s, `cmls`=%s, `ftyp`=%s, `oprs`=%s,
                    `fncs`=%s, `flds`=%s, `wins`=%s, `state`=%s, `description`=%s 
                    WHERE salary_id=%s",
                       GSQLStr(_xpost('salary_name'), "text"),
                       GSQLStr(_xpost('period'), "int"),
                       GSQLStr(_xpost('parts'), "text"),
                       GSQLStr(_xpost('typs'), "text"),
                       GSQLStr(_xpost('cmls'), "text"),
                       GSQLStr(_xpost('ftyp'), "text"),
                       GSQLStr(_xpost('oprs'), "text"),
                       GSQLStr(_xpost('fncs'), "text"),
                       GSQLStr(_xpost('flds'), "text"),
                       GSQLStr(_xpost('wins'), "text"),
                       GSQLStr(_xpost('state'), "text"),
                       GSQLStr(_xpost('description'), "text"),
                        $id);
	runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`salaryscale` WHERE salary_id={$id}";
$row_TSal = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Salaries - Edit <?php echo $row_TSal['salary_name'] ?></title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["salary_name", '',
                ["req", "Enter Name for Salary"]
        ],
        ["parts", '',
                ["req", "You need to specify at least one Salary Part!"]
        ]
    ];

    window.onload = function() {
        isEdit = true;
        prepView();
        $("#bx_sal").width(screen.availWidth - 520);
        $("#bx_bonus").width(screen.availWidth - 520);
        $("#bx_tax").width(screen.availWidth - 520);
    };
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
        <td width="240" valign="top"><img src="/images/salary.jpg" alt="" width="240" height="160" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblsalary.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form id="frmSal" name="frmSal" onsubmit="return vetTmpl()" method="post" action="<?php echo $editFormAction; ?>">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td colspan="2" align="center">&nbsp;<?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><input type="text" name="salary_name" id="salary_name" style="width:300px" value="<?php echo $row_TSal['salary_name'] ?>" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Period:</td>
                    <td><select name="period" id="period">
                      <option value="0"<?php echo $row_TSal['period']==0? ' selected="selected"' : '' ?>>Monthly</option>
                      <option value="1"<?php echo $row_TSal['period']==1? ' selected="selected"' : '' ?>>Weekly</option>
                      <option value="2"<?php echo $row_TSal['period']==2? ' selected="selected"' : '' ?>>Daily</option>
                      <option value="3"<?php echo $row_TSal['period']==3? ' selected="selected"' : '' ?>>Custom</option>
                      </select></td>
                    </tr>
                  <tr>
                    <td colspan="2" align="center" style="overflow:scroll; max-height:500px">
                      <input type="hidden" id="parts" name="parts" value="<?php echo $row_TSal['parts'] ?>" />
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
                              <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_sal" onclick="hideshow('sal', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_sal" onclick="hideshow('sal', 0, '')" style="display:none; cursor: pointer" /></div></td>
                              </tr>
                            </table></td>
                          <td rowspan="2" class="bl_rt"></td>
                          </tr>
                        <tr>
                          <td class="bl_center"><table border="0" cellspacing="4" cellpadding="2" id="bx_sal" style="display:none">
                            <tr>
                              <td><div id="sal_bx" style="overflow:scroll; padding:2px;"></div></td>
                              </tr>
                            <tr>
                              <td align="center"><img src="/images/but_add.png" width="50" height="20" onclick="addPart(1)" style="cursor:pointer" /></td>
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
                              <td><div id="bonus_bx" style="overflow:scroll; padding:2px;"></div></td>
                              </tr>
                            <tr>
                              <td align="center"><img src="/images/but_add.png" width="50" height="20" onclick="addPart(2)" style="cursor:pointer" /></td>
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
                              <td><div id="tax_bx" style="overflow:scroll; padding:2px;"></div></td>
                              </tr>
                            <tr>
                              <td align="center"><img src="/images/but_add.png" width="50" height="20" onclick="addPart(0)" style="cursor:pointer" /></td>
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
                    <td align="left"><textarea name="description" id="description" style="width:300px" rows="4"><?php echo $row_TSal['description'] ?></textarea></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
</tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?><span class="titles">
                  <input type="hidden" name="MM_update" value="Frm" />
                </span>
                  </td>
              </tr>

            </table>
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