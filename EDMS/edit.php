<?php
require_once('../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'EDMS'));
$access = _xvar_arr_sub($_access, array('Documents'));
vetAccess('EDMS', 'Documents', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmedms","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmedms") {
    if (_xpost('is_approved') == 1) {
	$sql = sprintf("INSERT INTO `%s`.`edms`
            (`tmpl_id`, `docname`, `content`, `author`, `created`, `maindoc`, `editedby`, `dept`, 
            `approvals`, `approved`, `active`, `doc_num`, `retention`, `revision`, `version`, `notes`) 
            SELECT %s, %s, %s, %s, `created`, `maindoc`, %s, %s, %s, 0, `active`, `doc_num`, 
            `retention`, `revision`, `version`+1, %s FROM `%s`.`edms` 
                WHERE `doc_id`=%s",
                        $_SESSION['DBCoy'],
                       GSQLStr(_xpost('tmpl_id'), "int"),
                       GSQLStr(_xpost('docname'), "text"),
                       GSQLStr(_xpost('content'), "text"),
                       $_SESSION['EmployeeID'],
                       $_SESSION['EmployeeID'],
                       GSQLStr(_xpost('dept'), "int"),
                       GSQLStr(_xpost('approvals'), "text"),
                       GSQLStr(_xpost('notes'), "text"),
                        $_SESSION['DBCoy'],
                       GSQLStr(_xpost('doc_id'), "int"));
	$update = runDBQry($dbh, $sql);
        if ($update > 0) {
            $id = mysqli_insert_id($dbh);
            header("Location: view.php?id=$id");
            exit;
        }
    } else {
        $id = _xpost('doc_id');
        $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`edms` SET `tmpl_id`=%s,`docname`=%s,`content`=%s,
            `editedby`=%s,`author`=%s,`edited`=NOW(),`editwhy`=%s,`dept`=%s,`approvals`=%s,`retention`=%s,
            `revision`=%s,`notes`=%s WHERE `doc_id`=%s",
                       GSQLStr(_xpost('tmpl_id'), "int"),
                       GSQLStr(_xpost('docname'), "text"),
                       GSQLStr(_xpost('content'), "text"),
                       $_SESSION['EmployeeID'],
                       $_SESSION['EmployeeID'],
                       GSQLStr(_xpost('editwhy'), "text"),
                       GSQLStr(_xpost('dept'), "int"),
                       GSQLStr(_xpost('approvals'), "text"),
                       GSQLStr(_xpost('retention'), "int"),
                       GSQLStr(_xpost('revision'), "int"),
                       GSQLStr(_xpost('notes'), "text"),
                       GSQLStr($id, "int"));
	$update = runDBQry($dbh, $sql);
        if ($update > 0) {
            header("Location: view.php?id=$id");
            exit;
        }
    }
}

$editor_sql = vendorFlds("editors", "editedby_name");
$sql = "SELECT `edms`.*, $vendor_sql, $editor_sql, `classifications`.catname, tmpl_name, 
    `tmpl`.`category_name` AS `doc_typ`, CONCAT(`prefix`,if(`doc_num`>0,`doc_num`,`autonum`)) AS `docnum`
        FROM `{$_SESSION['DBCoy']}`.`edms`
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`                 ON `edms`.author=`vendors`.VendorID 
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` `editors`       ON `edms`.editedby=`editors`.VendorID
        INNER JOIN `{$_SESSION['DBCoy']}`.`edms_tmpl`               ON `edms`.tmpl_id=`edms_tmpl`.tmpl_id 
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` `tmpl`  ON `edms_tmpl`.`category`=`tmpl`.`catID`
        INNER JOIN `{$_SESSION['DBCoy']}`.`edms_num`                ON `tmpl`.`catID`=`edms_num`.`doc_cat`
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications`         ON `edms`.dept=`classifications`.catID 
        WHERE `doc_id`=$id";
$row_TDocs = getDBDataRow($dbh, $sql);

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
    var edit = -3, tmp_id = <?php echo $id ?>;
    var arrFormValidation=
        [
            ["docname", "", 
                ["req", "Enter Dcoument Name"]],
            ["tmpl_id", "", 
                ["req", "Select Template!"]],
            ["dept", "", 
                ["req", "Select Department"]]
                      <?php if ($row_TDocs['approved'] == 1) { ?>,
            ["editwhy", "", 
                ["req", "Enter 'Reason for Change'!"]]<?php } ?>
        ];

    function loadTmpl() {
        load_Tmpl(<?php echo $row_TDocs['tmpl_id'] ?>);
    }

    function show_Doc() {
        showDoc("<?php echo addcslashes($row_TDocs['content'], '\"'); ?>");
        richup();
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
                  <input type="hidden" name="MM_update" value="frmedms" />
                  <input type="hidden" name="doc_id" value="<?php echo $row_TDocs['doc_id']; ?>" />
                  <input type="hidden" name="content" value="" />
                  <input type="hidden" name="approvals" id="approvals" value="<?php echo $row_TDocs['approvals'] ?>" />
                  <table border="0" cellpadding="4" cellspacing="4">
                    <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><input name="docname" type="text" id="docname" value="<?php echo $row_TDocs['docname'] ?>" style="width:500px" /></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Template:</td>
                    <td><select name="tmpl_id" id="tmpl_id">
                      <option value="">Select</option>
                      <?php foreach ($TTmpl as $row_TTmpl) { ?>
                      <option value="<?php echo $row_TTmpl['tmpl_id'] ?>" <?php if (!(strcmp($row_TDocs['tmpl_id'], $row_TTmpl['tmpl_id']))) { echo "selected=\"selected\""; }?>><?php echo $row_TTmpl['tmpl_name'] ?></option>
                      <?php } ?>
                    </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td><select name="dept" id="dept">
                      <option value="">Select</option>
                      <?php foreach ($TCat as $row_TCat) { ?>
                      <option value="<?php echo $row_TCat['catID'] ?>" <?php if (!(strcmp($row_TDocs['dept'], $row_TCat['catID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TCat['catname'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Document No.:</td>
                    <td><?php echo $row_TDocs['docnum'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Implementation Date:</td>
                    <td><?php echo $row_TDocs['approve_tm'] ?></td>
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
                    <td class="titles">Revision No.:</td>
                    <td><?php echo $row_TDocs['version'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Author:</td>
                    <td><?php echo $row_TDocs['VendorName'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Created:</td>
                    <td><?php echo $row_TDocs['created'] ?></td>
                  </tr>
                  <?php if ($row_TDocs['approved'] == 1) { ?>
                  <tr>
                    <td class="titles">Edited By:</td>
                    <td><?php echo $row_TDocs['editedby_name'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Edited:</td>
                    <td><?php echo $row_TDocs['edited'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Reason for Change:</td>
                    <td><textarea name="editwhy" rows="5" id="editwhy" style="width:500px"></textarea></td>
                  </tr>
                  <?php } ?>
                  <tr>
                    <td class="titles">Approvers:</td>
                    <td bgcolor="#333333"><div>
                      <div>
                        <input type="text" id="seek_apprv" />
                        <div class="ajax-file-upload-red load_apprv">Default</div>
                      </div>
                      <div class="approvals"></div>
                    </div></td>
                  </tr>
                  <tr>
                    <td class="titles">Approved:</td>
                    <td><input type="checkbox" name="approved" id="approved" <?php if (!(strcmp($row_TDocs['approved'], 1))) { echo "checked=\"checked\""; } ?>  disabled="disabled"/>
                      <input type="hidden" name="is_approved" id="is_approved" value="<?php echo $row_TDocs['approved'] ?>" /></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="notes" rows="5" id="notes" style="width:500px"><?php echo $row_TDocs['notes'] ?></textarea></td>
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
