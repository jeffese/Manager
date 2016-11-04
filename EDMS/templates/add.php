<?php require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'EDMS'));
$access = _xvar_arr_sub($_access, array('Templates'));
vetAccess('EDMS', 'Templates', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmtmpl","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmtmpl") {
  $sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`edms_tmpl` (`tmpl_name`, category, `tmpl_det`, `description`, `cmp_idx`) "
                            . "VALUES (%s, %s, %s, %s, %s)",
                       GSQLStr(_xpost('tmpl_name'), "text"),
                       GSQLStr(_xpost('category'), "int"),
                       GSQLStr(_xpost('tmpl_det'), "text"),
                       GSQLStr(_xpost('description'), "text"),
                       GSQLStr(_xpost('cmp_idx'), "int"));
	$ran = runDBQry($dbh, $sql);	
	
    if ($ran>0) {
        $id = mysqli_insert_id($dbh);
        $tmp_dir = _xpost('tmpl_id');
        rename(EDMS_TMPL_DIR . $tmp_dir, EDMS_TMPL_DIR . $id);
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`edms_tmpl` "
        . "SET `tmpl_det`=REPLACE(`tmpl_det`, 'src^~^$tmp_dir/cmp_', 'src^~^$id/cmp_') WHERE `tmpl_id`=$id";
	runDBQry($dbh, $sql);
        header("Location: view.php?id=$id");
        exit;
    }
}

$TCat = getClassify(8);

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
<link rel="stylesheet" type="text/css" href="../../css/canvas.css" />
<link rel="stylesheet" type="text/css" href="../../css/canvas-edit.css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="/lib/jquery/jquery-migrate-1.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/dhtmlx/codebase/dhtmlx.css"/>
<script type="text/javascript" src="/lib/dhtmlx/codebase/dhtmlx.js"></script>
<script type="text/javascript" src='/lib/spectrum/spectrum.js'></script>
<link rel='stylesheet' type="text/css" href='/lib/spectrum/spectrum.css' />
<script type="text/javascript" src="/lib/jQuery-contextMenu/jquery.contextMenu.js"></script>
<script type="text/javascript" src="/lib/jQuery-contextMenu/jquery.ui.position.js"></script>
<link rel='stylesheet' type="text/css" href='/lib/jQuery-contextMenu/jquery.contextMenu.css' />
<link href="/lib/jQuery-Upload-File/uploadfile.css" rel="stylesheet">
<script src="/lib/jQuery-Upload-File/jquery.uploadfile.min.js"></script>
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
var arrFormValidation=
    [
        ["tmpl_name", "", 
            ["req", "Enter Template Name"]],
        ["tmpl_det", "", 
            ["req", "Template is empty!"]]
    ];
var edit = 0, cmp_ = 1, tmp_id = "<?php
    if ($objs = glob(EDMS_TMPL_DIR . $_SESSION['coyid'] . DS . "tmp-*")) {
	foreach ($objs as $obj) {
            if (filemtime($obj) + 60 * 60 < time()) {
                rmdirr($obj);
            }
        }
    }
    $tmp_dir = filenameUsed(EDMS_TMPL_DIR . $_SESSION['coyid'] . DS, "tmp");
    mkdir(EDMS_TMPL_DIR . $_SESSION['coyid'] . DS . $tmp_dir);
    echo $tmp_dir;
?>";
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
        <td width="240" valign="top"><img src="/images/scheme.jpg" alt="" width="240" height="300" />
          <?php include 'menu.htm'; ?></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lbltemplates.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
        </table>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1"><iframe id="propTmpl" src="/EDMS/templates/properties.htm" style="display:none"></iframe></td>
              </tr>
              <tr>
                <td><form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="frmtmpl" id="frmtmpl" onsubmit="saveTmpl(); return validateFormPop(arrFormValidation);">
                  <input type="hidden" name="tmpl_id" value="<?php echo $tmp_dir ?>" />
                  <input type="hidden" name="MM_insert" value="frmtmpl" />
                  <input type="hidden" name="tmpl_det" value="" />
                  <input type="hidden" name="cmp_idx" value="" />
                  <table border="0" cellpadding="4" cellspacing="4">
                    <tr>
                      <td class="titles">Name:</td>
                      <td align="left"><input name="tmpl_name" type="text" id="tmpl_name" style="width:500px"/></td>
                    </tr>
                    <tr>
                      <td class="titles">Category:</td>
                      <td><select name="category" id="category">
                        <option value="">Select</option>
                        <?php foreach ($TCat as $row_TCat) { ?>
                        <option value="<?php echo $row_TCat['catID'] ?>"><?php echo $row_TCat['catname'] ?></option>
                        <?php } ?>
                      </select></td>
                    </tr>
                    <tr>
                    <td width="120" class="titles">Description:</td>
                    <td><textarea name="description" rows="3" id="description" style="width:500px"></textarea></td>
                    </tr>
                </table>
                </form></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
              <td valign="top" id="canvas" ondrop="drop(event)" ondragover="allowDrop(event)"><ul id="cmp_list">
                </ul></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
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
