<?php
require_once('../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'EDMS'));
$access = _xvar_arr_sub($_access, array('Documents'));
vetAccess('EDMS', 'Documents', 'View');

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Document]del.php?id=$id","","","","print.php?id=$id","index.php");
$rec_status = 1;

if (_xget("clr") == "1") {
    $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`edms` SET `editedby`=%s, `author`=%s, "
                    . "`approver`=IF(`approver`=`author`, %s, `approver`) WHERE `doc_id`=%s",
                   $_SESSION['EmployeeID'],
                   $_SESSION['EmployeeID'],
                   $_SESSION['EmployeeID'],
                   GSQLStr($id, "int"));
    $update = runDBQry($dbh, $sql);
    header("Location: view.php?id=$id");
    exit;
} elseif (_xget("aprv") == "1") {
    $sql = "SELECT `docname`, `approvals`, `approver`, `created`, $vendor_sql, `category_id` 
            FROM `{$_SESSION['DBCoy']}`.`edms` 
            INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`         ON `edms`.author=`vendors`.VendorID 
            INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `edms`.dept=`classifications`.catID 
            WHERE `doc_id`=$id";
    $row_TDocs = getDBDataRow($dbh, $sql);
    $pts = explode('|', $row_TDocs['approvals']);
    $ids = explode(',', $pts[0]);
    $chks = explode(',', $pts[1]);
    $apr = 0;
    $apv = 0;
    $apd = 0;
    $vw = '';
    while ($row_TDocs['approver'] != $ids[$apr]) {
        $apr++;
    }
    if ($row_TDocs['approver'] == $ids[$apr]) {
        $chks[$apr] = 1;
    }
    if ($apr < count($ids) - 1) {
        $apr++;
        $apv = $ids[$apr];
        $sql = "SELECT $vendor_sql, `EmailAddress`
                FROM `{$_SESSION['DBCoy']}`.`vendors` 
                WHERE `VendorID`=$apv";
        $Aprover = getDBDataRow($dbh, $sql);
        if ($Aprover) {
            alert_mail_doc('doc_approv', 'Re: Document Approval Request');
        }
    } else {
        $apd = 1;
        $sql = "SELECT `VendorID`, $vendor_sql, `EmailAddress`
                    FROM `{$_SESSION['DBCoy']}`.`vendors` 
                    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `vendors`.`DeptID` = `classifications`.`catID` 
                    WHERE `category_id`='{$row_TDocs['category_id']}' OR `category_id` LIKE '{$row_TDocs['category_id']}-%'";
        $Aprovers = getDBDataRow($dbh, $sql);
        $viewers = array();
        foreach ($Aprovers as $Aprover) {
            array_push($viewers, $Aprover['VendorID']);
            alert_mail_doc('doc_new', 'Re: New Document In System');
        }
        $vw = ", `unviewed`='" . implode(',', $viewers) . "'";
    }
    $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`edms` 
                        INNER JOIN `{$_SESSION['DBCoy']}`.`edms_tmpl`               ON `edms`.tmpl_id=`edms_tmpl`.tmpl_id 
                        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` `tmpl`  ON `edms_tmpl`.`category`=`tmpl`.`catID`
                        INNER JOIN `{$_SESSION['DBCoy']}`.`edms_num`                ON `tmpl`.`catID`=`edms_num`.`doc_cat`
                        SET `approvals`=%s, `approver`=%s, `approved`=%s, `approve_tm`=NOW(), 
                        `doc_num`=`autonum`, `autonum`=`autonum`+1 $vw WHERE `doc_id`=%s",//
                   GSQLStr("$pts[0]|" . implode(',', $chks), "text"),//
                   $apv, $apd,//
                   GSQLStr($id, "int"));
    $update = runDBQry($dbh, $sql);
    header("Location: view.php?id=$id");
    exit;
}

function alert_mail_doc($tmpl, $subj) {
    global $Aprover, $row_TDocs;
    $message = sprintf(file_get_contents(ROOT . "/mail_tmpl/$tmpl"), //
                    $Aprover['VendorName'],//
                    $row_TDocs['docname'],//
                    $row_TDocs['VendorName'],//
                    $row_TDocs['created']);
    _xmail($Aprover['EmailAddress'], 'EDMS System', $_SESSION['COY']['admin_mail'], 'EDMS System', $_SESSION['COY']['admin_mail'], 
            $subj, null, $message, $message, 2, 4, 'text/plain');
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
$isAuthor = $row_TDocs['approved'] == 1 || $_SESSION['EmployeeID'] == $row_TDocs['author'] || $row_TDocs['author'] == 1 ? $access['Edit'] : 0;
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $isAuthor, $isAuthor, $access['Print'], 0, 0);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<link rel="stylesheet" type="text/css" href="../css/canvas.css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="/lib/jquery/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="/lib/jquery-ui/js/jquery-ui.min.js"></script>
<link href="/lib/jquery-ui/css/smoothness/jquery-ui.min.css" rel="stylesheet">
<link href="/lib/jQuery-Upload-File/uploadfile.css" rel="stylesheet">
<script language="JavaScript1.2" src="templates/script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
    <?php include 'load_dbf.php'; ?>
    var edit = -4, tmp_id = <?php echo $id ?>, net_approver = <?php echo $_SESSION['EmployeeID'] == $row_TDocs['approver'] ? $row_TDocs['approver'] : 0 ?>;

    function loadTmpl() {
        load_Tmpl(<?php echo $row_TDocs['tmpl_id'] ?>);
    }

    function show_Doc() {
        showDoc("<?php echo addcslashes($row_TDocs['content'], '\"'); ?>");
    }
    
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
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
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                    <tr>
                      <td class="titles">Name:</td>
                      <td align="left"><?php echo $row_TDocs['docname'] ?></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles">Template:</td>
                      <td><?php echo $row_TDocs['tmpl_name'] ?></td>
                    </tr>
                    <tr>
                      <td class="titles">Department:</td>
                      <td><?php echo $row_TDocs['catname'] ?></td>
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
                      <td><?php echo $row_TDocs['retention'] ?> days</td>
                    </tr>
                    <tr>
                      <td class="titles">Revision Period:</td>
                      <td><?php echo $row_TDocs['revision'] ?> days</td>
                    </tr>
                    <tr>
                      <td class="titles">Revision No.:</td>
                      <td><?php echo $row_TDocs['version'] ?></td>
                    </tr>
                    <tr>
                      <td class="titles">Author:</td>
                      <td><table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><?php echo $row_TDocs['VendorName'] ?></td>
                            <td>&nbsp;</td>
                            <td><?php 
                      if ($row_TDocs['approved'] == 0 && isAdminPowerGrp() && $row_TDocs['author'] != 1) { ?>
                              <a href="javascript: void(0)" onclick="if (confirm('Are you sure you want to clear the current Editor?')) document.location='view.php?id=<?php echo $id ?>&clr=1'"><img src="/images/but_clear.png" width="60" height="20" /></a>
                            <?php } ?></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td class="titles">Created:</td>
                      <td><?php echo $row_TDocs['created'] ?></td>
                    </tr>
                    <tr>
                      <td class="titles">Edited By:</td>
                      <td><?php echo $row_TDocs['editedby_name'] ?></td>
                    </tr>
                    <tr>
                      <td class="titles">Edited:</td>
                      <td><?php echo $row_TDocs['edited'] ?></td>
                    </tr>
                    <tr>
                      <td class="titles">Approvers:</td>
                      <td bgcolor="#333333"><div>
                        <div class="approvals"></div>
                      </div></td>
                    </tr>
                    <tr>
                      <td class="titles"><input type="hidden" name="approvals" id="approvals" value="<?php echo $row_TDocs['approvals'] ?>" />
                      Approved:</td>
                      <td><input type="checkbox" name="approved" id="approved" <?php if (!(strcmp($row_TDocs['approved'], 1))) { echo "checked=\"checked\""; } ?>  disabled="disabled"/></td>
                    </tr>
                    <tr>
                      <td width="120" class="titles">Notes:</td>
                      <td><textarea name="notes" rows="5" readonly="readonly" id="notes" style="width:500px"><?php echo $row_TDocs['notes'] ?></textarea></td>
                    </tr>
                  </table></td>
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
                <td></td>
              </tr>
              <tr>
                <td><?php include('../scripts/buttonset.php'); ?></td>
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