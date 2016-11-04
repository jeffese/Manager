<?php
require_once('../../scripts/init.php');
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Pay Slips'));
vetAccess('Personnel', 'Pay Slips', 'View');

$id = intval(_xget('id'));
$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`paybatch` WHERE paybatchid={$id}";
$row_TPay = getDBDataRow($dbh, $sql);

if (_xpost("MM_update") == "frmpayslip") {
    $upfile = $_FILES['spreadsheet']['tmp_name'];
    $file = ROOT . "/tmp/".$_FILES['spreadsheet']['name'];
    $test = $row_TPay['salary'] + $row_TPay['bonus'];
    
    if ($test == 0)
        array_push($errors, array("Error", "Salary type not specified!"));
//    else if ($test == 2)
//        array_push($errors, array("Error", "You must specify only one Salary type!"));
    else if (!is_uploaded_file($upfile) || !move_uploaded_file($upfile, $file))
        array_push($errors, array("Error", "No File uploaded!"));
    else {
        include 'gensheet.php';
    }
}

if ($row_TPay['posted'] == 1) {
    $sql = "SELECT DISTINCT `DeptID`, `catname` FROM `{$_SESSION['DBCoy']}`.`payslip` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`         ON `payslip`.staffid = `vendors`.VendorID
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `vendors`.DeptID = `classifications`.catID
        WHERE `paybatchid` = " . $id;
} else {
    $sql = "SELECT DISTINCT `DeptID`, `catname` FROM `{$_SESSION['DBCoy']}`.`vendors`
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `vendors`.DeptID = `classifications`.catID
        WHERE `VendorType`=5 AND salary>0";
}
$TSheet = getDBData($dbh, $sql);

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], AccStat($access['Edit'], $row_TPay['posted']), AccStat($access['Del'], $row_TPay['posted']), $access['Print'], 0, 0);

//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","if (confirm('Are you sure you want to delete this payslip?')) document.location='del.php?id=$id'","","","","return GB_showCenter('Pay Slip', 'print.php?id=$id","index.php");
$rec_status = 1;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pay Slips - <?php echo $row_TPay['payday'] ?> Details</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
window.onload = function() {
    $("#paylist").width(screen.availWidth-520);
    $("#duplist").width(screen.availWidth-520);
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
          <td width="240" valign="top"><img src="/images/payslip.png" alt="" width="240" height="160" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblpayslip.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
            </table>
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
                  <td class="titles">Batch #:</td>
                  <td align="left"><strong><?php echo $row_TPay['paybatchid'] ?></strong></td>
                </tr>
                <tr>
                  <td class="titles">Title:</td>
                  <td align="left"><input type="text" name="payday" id="payday" style="width:300px" value="<?php echo $row_TPay['payday'] ?>" /></td>
                </tr>
                <tr>
                  <td class="titles">Period:</td>
                  <td><table width="200" border="0" cellpadding="2" cellspacing="2">
                    <tr>
                      <td>Start:</td>
                      <td><input type="text" name="dtfrom" id="dtfrom" value="<?php echo $row_TPay['dtfrom'] ?>" size="12" /></td>
                      <td>&nbsp;</td>
                      <td>End:</td>
                      <td><input type="text" name="dtto" id="dtto" value="<?php echo $row_TPay['dtto'] ?>" size="12" /></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td class="titles">Type:</td>
                  <td><table border="0" cellpadding="2" cellspacing="2" class="blue-normal">
                    <tr>
                      <td><input type="checkbox" name="salary" id="salary"<?php if (!(strcmp($row_TPay['salary'],"1"))) {echo " checked=\"checked\"";} ?> disabled="disabled" /></td>
                      <td><strong>Salary</strong></td>
                      <td>&nbsp;</td>
                      <td><input type="checkbox" name="bonus" id="bonus"<?php if (!(strcmp($row_TPay['bonus'],"1"))) {echo " checked=\"checked\"";} ?> disabled="disabled" /></td>
                      <td><strong>Bonus</strong></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td class="titles">Total Value:</td>
                  <td class="red-normal" id="totvals">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" align="center"><table border="0" cellpadding="2" cellspacing="2" class="titles">
                      <tr>
                          <?php if ($row_TPay['posted'] == 0 && vetAccess('Personnel', 'Pay Slips', 'Post', false)) { ?><td><img src="/images/post.png" width="50" height="20" onclick="if (confirm('Are you sure you want to Post this Pay Slip Batch?')) location.href='post.php?id=<?php echo $id ?>'" style="cursor: pointer" /></td>
                          <td>&nbsp;</td>
                          <td bgcolor="#CCCCCC" class="boldwhite1">Upload:</td>
                        <td bgcolor="#CCCCCC"><input type="file" name="spreadsheet" id="spreadsheet" /></td>
                        <td bgcolor="#CCCCCC"><input type="submit" name="button" id="button" value="Submit" /></td>
                          <?php } ?>
                        <td>&nbsp;</td>
                          <td><iframe id="xwin" style="display:none"></iframe>&nbsp;</td>
                          <td nowrap="nowrap" bgcolor="#99CC00" class="boldwhite1">Time Sheet:</td>
                          <td bgcolor="#99CC00"><select name="tsheet" id="tsheet">
                            <?php foreach ($TSheet as $row_TSheet) { ?>
                            <option value="<?php echo $row_TSheet['DeptID'] ?>"><?php echo $row_TSheet['catname'] ?></option>
                            <?php } ?>
                          </select></td>
                          <td bgcolor="#99CC00"><img src="/images/page_white_acrobat.png" width="16" height="16" onclick="if ($('#tsheet').val().length > 0) $('#xwin').attr('src', 'timepdf.php?id='+$('#tsheet').val())+'&bth=<?php echo $id ?>'" style="cursor: pointer" /></td>
                          <td bgcolor="#99CC00"><img src="/images/page_white_excel.png" width="16" height="16" onclick="if ($('#tsheet').val().length > 0) $('#xwin').attr('src', 'timesheet.php?id='+$('#tsheet').val())+'&bth=<?php echo $id ?>'" style="cursor: pointer" /></td>
                        <td>&nbsp;</td>
                          <td bgcolor="#CC0000" class="boldwhite1">Export:</td>
                          <td bgcolor="#CC0000"><img src="/images/page_white_excel.png" width="16" height="16" onclick="$('#xwin').attr('src', 'payees/export.php?id=<?php echo $id ?>')" style="cursor: pointer" /></td>
                          <?php if ($row_TPay['posted'] == 1 && vetAccess('Personnel', 'Pay Slips', 'Unlock', false)) { ?><td>&nbsp;</td>
                          <td><img src="/images/unlock.png" width="50" height="20" onclick="if (confirm('Are you sure you want to Unlock this Pay Slip Batch?')) location.href='unlock.php?id=<?php echo $id ?>'" style="cursor: pointer" /></td>
                          <td>&nbsp;</td>
                          <td nowrap="nowrap" bgcolor="#0099FF" class="boldwhite1">Email All:</td>
                          <td bgcolor="#0099FF"><img src="/images/email_go.png" width="16" height="16" onclick="/personnel/payslips/payees/mailall.php?id=<?php echo $id ?>" style="cursor: pointer" /></td>
                          <td>&nbsp;</td>
                          <td nowrap="nowrap" bgcolor="#006600" class="boldwhite1">Bank Sheet:</td>
                        <td bgcolor="#006600"><select name="bank" id="bank">
                          <option value="FCMB">FCMB</option>
                          <option value="REMITA">REMITA</option>
                        </select></td>
                          <td bgcolor="#006600"><img src="/images/page_white_excel.png" width="16" height="16" onclick="$('#xwin').attr('src', 'bank.php?id=<?php echo $id ?>&bank='+$('#bank').val())" style="cursor: pointer" /></td>
                          <?php } ?>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="2"><iframe id="paylist" src="payees/view.php?id=<?php echo $id ?>" style="width:100%; height:500px"></iframe></td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" class="h1">Duplicate Slips</td>
                </tr>
                <tr>
                  <td colspan="2"><iframe id="duplist" src="payees/duplicates.php?id=<?php echo $id ?>" style="width:100%; height:200px"></iframe></td>
                </tr>
                <tr>
                  <td class="titles">Description:</td>
                  <td align="left"><textarea name="description" id="description" style="width:500px" rows="4"><?php echo $row_TPay['description'] ?></textarea></td>
                </tr>
                <tr>
                  <td class="titles">Edited by:</td>
                  <td><?php echo $row_TPay['staffid'] ?></td>
                </tr>
              </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php'); ?></td>
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
