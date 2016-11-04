<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Pay Slips'));
vetAccess('Personnel', 'Pay Slips', 'Print');

$id = _xget('id');
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

if ($row_TPay['posted'] == 1)
    $sql = "SELECT DISTINCT `ref_no` FROM `{$_SESSION['DBCoy']}`.`payslip` WHERE `paybatchid` = " . $id;
else
    $sql = "SELECT DISTINCT `contract` AS `ref_no` FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE `VendorType`=5";
$TSheet = getDBData($dbh, $sql);

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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblpayslip.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
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
                  <td colspan="2" align="center"></td>
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
	print();
});
</script>
</body>
</html>
