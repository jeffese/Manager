<?php
require_once('../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'EDMS'));
$access = _xvar_arr_sub($_access, array('Documents'));
vetAccess('EDMS', 'Documents', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmedms","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmedms") {
    $sql = sprintf("INSERT INTO `%s`.`edms`
        (`tmpl_id`, `docname`, `content`, `author`, `created`, `maindoc`, `editedby`, 
        `dept`, `approvals`, `approver`, `approved`, `active`, `doc_num`, `retention`, 
        `revision`, `version`, `notes`) 
        VALUES (%s, %s, %s, %s, NOW(), 0, %s, %s, %s, %s, 0, 1, 0, %s, %s, 1, %s)",
                    $_SESSION['DBCoy'],
                   GSQLStr(_xpost('tmpl_id'), "int"),
                   GSQLStr(_xpost('docname'), "text"),
                   GSQLStr(_xpost('content'), "text"),
                   $_SESSION['EmployeeID'],
                   $_SESSION['EmployeeID'],
                   GSQLStr(_xpost('dept'), "int"),
                   GSQLStr(_xpost('approvals'), "text"),
                   $_SESSION['EmployeeID'],
                   GSQLStr(_xpost('retention'), "int"),
                   GSQLStr(_xpost('revision'), "int"),
                   GSQLStr(_xpost('notes'), "text"));
    $insert = runDBQry($dbh, $sql);
    
    $EDMS_DIR = EDMS_DIR . $_SESSION['coyid'] . DS;
    if ($insert > 0) {
        $id = mysqli_insert_id($dbh);
        $tmp_dir = _xpost('tmp_id');
        rename($EDMS_DIR . $tmp_dir, $EDMS_DIR . $id);
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`edms` "
        . "SET `content`=REPLACE(`content`, 'src^~^$tmp_dir/cmp_', 'src^~^$id/cmp_') WHERE `doc_id`=$id";
        runDBQry($dbh, $sql);
        header("Location: view.php?id=$id");
        exit;
    }
}

$TCat = getClassify(1);

$sql = "SELECT `tmpl_id`, `tmpl_name` FROM `{$_SESSION['DBCoy']}`.`edms_tmpl` ORDER BY `tmpl_name`";
$TTmpl = getDBData($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/jquery/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="/lib/jquery/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="/lib/jquery-ui/js/jquery-ui.min.js"></script>
<link href="/lib/jquery-ui/css/smoothness/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/dhtmlx/codebase/dhtmlx.css"/>
<script type="text/javascript" src="/lib/dhtmlx/codebase/dhtmlx.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<link rel="stylesheet" type="text/css" href="../css/canvas.css" />
<link rel="stylesheet" type="text/css" href="../css/doc-edit.css" />
<link rel="stylesheet" type="text/css" href="/lib/jquery-ui/jQuery-TE/jquery-te-1.4.0.css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<link href="/lib/jQuery-Upload-File/uploadfile.css" rel="stylesheet">
<script src="/lib/jQuery-Upload-File/jquery.uploadfile.min.js"></script>
<script src="/lib/jquery-ui/jQuery-TE/jquery-te-1.4.0.min.js"></script>
<script language="JavaScript1.2" src="templates/script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
    <?php include 'load_dbf.php'; ?>
var edit = -2, tmp_id = "<?php
    if ($objs = glob($EDMS_DIR . $_SESSION['coyid'] . DS . "tmp-*")) {
	foreach ($objs as $obj) {
            if (filemtime($obj) + 60 * 60 < time()) {
                rmdirr($obj);
            }
        }
    }
    $tmp_dir = filenameUsed($EDMS_DIR, "tmp");
    mkdir($EDMS_DIR . $tmp_dir, 0777, true);
    echo $tmp_dir;
?>";
var arrFormValidation=
    [
        ["docname", "", 
            ["req", "Enter Dcoument Name"]],
        ["tmpl_id", "", 
            ["req", "Select Template!"]],
        ["dept", "", 
            ["req", "Select Department"]]
    ];

function loadTmpl() {
    
}
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
        <td width="240" valign="top"><img src="/images/edms.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblEDMS.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../scripts/buttonset.php')?></td>
          </tr>
        </table>
        <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1"><iframe id="propTmpl" src="properties.htm" style="display:none"></iframe></td>
              </tr>
              <tr>
                <td><form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation) && saveDoc();" method="post" enctype="multipart/form-data" name="frmedms" id="frmedms">
                  <input type="hidden" name="tmp_id" value="<?php echo $tmp_dir ?>" />
                  <input type="hidden" name="MM_insert" value="frmedms" />
                  <input type="hidden" name="content" value="" />
                  <input type="hidden" name="approvals" id="approvals" value="" />
<table border="0" cellpadding="4" cellspacing="4">
  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><input name="docname" type="text" id="docname" style="width:500px" /></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Template:</td>
                    <td><select name="tmpl_id" id="tmpl_id" onchange="load_Tmpl($('#tmpl_id').val())">
                      <option value="">Select</option>
                      <?php foreach ($TTmpl as $row_TTmpl) { ?>
                      <option value="<?php echo $row_TTmpl['tmpl_id'] ?>"><?php echo $row_TTmpl['tmpl_name'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td><select name="dept" id="dept">
                      <option value="">Select</option>
                      <?php foreach ($TCat as $row_TCat) { ?>
                      <option value="<?php echo $row_TCat['catID'] ?>"><?php echo $row_TCat['catname'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Retention Period:</td>
                    <td><input name="retention" type="text" id="retention" value="<?php echo $row_TDocs['retention'] ?>" style="width:30px" />
                    days</td>
                  </tr>
                  <tr>
                    <td class="titles">Revision Period:</td>
                    <td><input name="revision" type="text" id="revision" value="<?php echo $row_TDocs['revision'] ?>" style="width:30px" />
                      days</td>
                  </tr>
                  <tr>
                    <td class="titles">Approvers:</td>
                    <td bgcolor="#333333"><div><div><input type="text" id="seek_apprv" />
                      <div class="ajax-file-upload-red load_apprv">Default</div></div>
                      <div class="approvals"></div>
                      </div></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="notes" rows="5" id="notes" style="width:500px"></textarea></td>
                  </tr>
              </table>
                </form></td>
              </tr>
              <tr>
                <td id="tmp_loader">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top" id="canvas"><ul id="cmp_list">
                </ul></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><?php include('../scripts/buttonset.php')?></td>
              </tr>

            </table>
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
