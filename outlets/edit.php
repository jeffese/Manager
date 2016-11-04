<?php
require_once("$vpth/scripts/init.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmoutlet","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = $vmod.DS.$vkey;
$doc_id = $id;

if (_xpost("MM_update") == "frmoutlet") {
	$sql = sprintf("UPDATE `%s`.`outlets` SET OutletCode=%s, OutletName=%s, Dept=%s, guests=%s, 
            description=%s WHERE OutletID=%s",
                       $_SESSION['DBCoy'],
                       GSQLStr(_xpost('OutletCode'), "text"),
                       GSQLStr(_xpost('OutletName'), "text"),
                       GSQLStr(_xpost('Dept'), "int"),
                       GSQLStr(_xpost('guests'), "text"),
                       GSQLStr(_xpost('description'), "text"),
                       $id);
	$update = runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`outlets` WHERE `OutletID`=$id";
$row_TOutlets = getDBDataRow($dbh, $sql);

$TDept = getClassify(1);

$TStaff = getVendor(5);
$TStaffLst = getVendor(5, 0, "AND VendorID IN (0{$row_TOutlets['guests']})");
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
<script type="text/javascript" src="/outlets/script.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["OutletCode", "", 
            ["req", "Enter $vnm Code"]],
        ["OutletName", "", 
            ["req", "Enter $vnm Name"]],
        ["Dept", "", 
            ["req", "Select Department"]]
    ];
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmoutlet" id="frmoutlet">
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
                    <td align="left"><input name="OutletCode" type="text" id="OutletCode" value="<?php echo $row_TOutlets['OutletCode'] ?>" size="20" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><input name="OutletName" type="text" id="OutletName" value="<?php echo $row_TOutlets['OutletName'] ?>" size="32" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td><select name="Dept" id="Dept">
                      <option value="">Select</option>
                      <?php foreach ($TDept as $row_TDept) { ?>
                      <option value="<?php echo $row_TDept['catID'] ?>" <?php if (!(strcmp($row_TOutlets['Dept'], $row_TDept['catID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TDept['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Non-Department Staff:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td><select name="lst" size="10" id="lst">
                        <?php foreach ($TStaffLst as $row_TStaffLst) { ?>
                        <option value="<?php echo $row_TStaffLst['VendorID'] ?>"><?php echo $row_TStaffLst['VendorName'] ?></option>
                        <?php } ?>
                        </select></td>
                        <td valign="top"><table border="0" cellspacing="2" cellpadding="2">
                          <tr>
                            <td align="center"><select name="staff" id="staff">
                              <?php foreach ($TStaff as $row_TStaff) { ?>
                              <option value="<?php echo $row_TStaff['VendorID'] ?>"><?php echo $row_TStaff['VendorName'] ?></option>
                              <?php } ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td align="center"><a id="addserial" href="javascript: void(0)" onclick="addSerial()"><img src="/images/but_add.png" width="50" height="20" /></a></td>
                          </tr>
                          <tr>
                            <td align="center"><a id="delserial" href="javascript: void(0)" onclick="delSerial()"><img src="/images/but_mini_del.png" width="34" height="20" /></a></td>
                          </tr>
                        </table>
                          <input type="hidden" name="guests" id="guests" value="<?php echo $row_TOutlets['guests']; ?>" /></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Description:</td>
                    <td><textarea name="description" rows="3" id="description" style="width:300px"><?php echo $row_TOutlets['description'] ?></textarea></td>
                    </tr>
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
                            <td><?php include "$vpth/scripts/editdoc.php" ?></td>
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
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                </tr>
              <tr>
                <td><?php include("$vpth/scripts/buttonset.php")?></td>
                </tr>

              </table>
            <input type="hidden" name="MM_update" value="frmoutlet" />
            <input type="hidden" name="OutletID" value="<?php echo $row_TOutlets['OutletID']; ?>" />
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
