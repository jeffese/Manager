<?php
require_once('../../../scripts/init.php');
vetAccess('Personnel', 'Pay Slips', 'Add');

require_once("paygen.php");
if (_xpost("MM_insert") == "fmlst") {
    $x = 1;
    $ran = 0;
    $sql = "UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET InUse=1 WHERE `VendorType`=5";
    runDBQry($dbh, $sql);
    while (isset($_POST["VendorID$x"])) {
	$sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET InUse=%s, tax=%s, `contract`=%s, `worked`=%s, `deduct`=%s WHERE VendorID=%s",
                       _xpostchk("InUse$x"),
                       GSQLStr(_xpost("tax$x"), "text"),
                       GSQLStr(_xpost("ref_no$x"), "text"),
                       GSQLStr(_xpost("worked$x"), "int"),
                       GSQLStr(_xpost("deduct$x"), "text"),
                       GSQLStr(_xpost("VendorID$x"), "int"));
        runDBQry($dbh, $sql);
        $x++;
    } ?>
    <script type="text/javascript">
    parent.post();
    </script>
    <?php
    exit;
}
$currentPage = 'add.php';

preOrd("paylst", array('InUse', 'vendorcode', 'VendorName', 'category_name', 'InUse', 'worked', 'ref_no', 'salary_name'));

$sql = "SELECT `VendorID`, `vendorcode`, $vendor_sql, category_name, `worked`,
        `amtbal`, `InUse`, `tax`, `worked`, `contract` AS `ref_no`, `salaryscale`.* 
        FROM `{$_SESSION['DBCoy']}`.`vendors` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`salaryscale`    ON `vendors`.`salary` = `salaryscale`.`salary_id`
        LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `vendors`.categoryid = classifications.catID 
        WHERE `VendorType`=5 $orderval";
$TEmployees = getDBData($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="resource.js"></script>
<script type="text/javascript">
    var called = false;
    function post() {
        called = true;
        $('#fmlst').submit();
    }
    
    function termAll(chk) {
        $("[name*=InUse]").attr("checked", "");
    }
    
    function setdays(days) {
        $('input[name^="worked"]').val(days);
        $('#totalworked').val(days);
    }
    
    function vetdays(row) {
		var tot = $('#totalworked').val();
        if ($("#worked"+row).val() > tot) {
			$("#worked"+row).val(tot);
			$("#worked"+row).focus();
			alert('Maximum days based on time period is '+tot);
		}
    }
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<form id="fmlst" action="" method="post" onSubmit="return called">
  <table border="0" cellpadding="4" cellspacing="4">
    <tr>
      <td></td>
      <td align="center"><?php echo catch_error($errors) ?></td>
    </tr>
  </table>
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center" class="boldwhite1"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                <tr align="center" bgcolor="#666666" class="boldwhite1">
                  <td>#</td>
                  <td><input type="hidden" name="MM_insert" value="fmlst" /><?php echo setOrderTitle('Staff No.', $currentPage, 1, $ord, $asc); ?></td>
                  <td><?php echo setOrderTitle('Name', $currentPage, 2, $ord, $asc); ?></td>
                  <td><?php echo setOrderTitle('Category', $currentPage, 3, $ord, $asc); ?></td>
                  <td nowrap="nowrap"><?php echo setOrderTitle('Pay', $currentPage, 4, $ord, $asc); ?>
                    <input name="chkall" type="checkbox" id="chkall" onClick="termAll(this)" checked="checked" /></td>
                  <td><?php echo setOrderTitle('Worked', $currentPage, 5, $ord, $asc); ?>                    <input type="hidden" name="totalworked" id="totalworked" value="0" /></td>
                  <td><?php echo setOrderTitle('Salary', $currentPage, 7, $ord, $asc); ?></td>
                  <td>Resources</td>
                  <td>Deductions</td>
                  <td>Salary</td>
                  <td>Bonus</td>
                  <td>Deductions</td>
                  <td>Tax</td>
                  </tr>
                <?php $j=1;
	   foreach ($TEmployees as $row_TEmployees) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
          $prep = prepView($row_TEmployees, null);
	  ?>
                <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
onMouseOut="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');">
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><?php echo $j ?></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['vendorcode'] ?></b></td>
                  <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['VendorName'] ?></b></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['category_name']; ?></b></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input type="checkbox" name="InUse<?php echo $j ?>" value=""  <?php echo $row_TEmployees['InUse']==1? "checked=\"checked\"": ""; ?> />
                    <input type="hidden" name="VendorID<?php echo $j ?>" value="<?php echo $row_TEmployees['VendorID']; ?>" />
                    <input type="hidden" id="tax<?php echo $j ?>" name="tax<?php echo $j ?>" value="<?php echo $row_TEmployees['tax']; ?>" />
                    <input type="hidden" id="parts<?php echo $j ?>" name="parts<?php echo $j ?>" value="<?php echo $row_TEmployees['parts'] ?>" />
                    <input type="hidden" id="typs<?php echo $j ?>" name="typs<?php echo $j ?>" value="<?php echo $row_TEmployees['typs'] ?>" />
                    <input type="hidden" id="cmls<?php echo $j ?>" name="cmls<?php echo $j ?>" value="<?php echo $row_TEmployees['cmls'] ?>" />
                    <input type="hidden" id="ftyp<?php echo $j ?>" name="ftyp<?php echo $j ?>" value="<?php echo $row_TEmployees['ftyp'] ?>" />
                    <input type="hidden" id="oprs<?php echo $j ?>" name="oprs<?php echo $j ?>" value="<?php echo $row_TEmployees['oprs'] ?>" />
                    <input type="hidden" id="flds<?php echo $j ?>" name="flds<?php echo $j ?>" value="<?php echo $row_TEmployees['flds'] ?>" />
                    <input type="hidden" id="wins<?php echo $j ?>" name="wins<?php echo $j ?>" value="<?php echo $row_TEmployees['wins'] ?>" />
                    <input type="hidden" id="fncs<?php echo $j ?>" name="fncs<?php echo $j ?>" value="<?php echo $row_TEmployees['fncs'] ?>" />
                    <input type="hidden" id="state<?php echo $j ?>" name="state<?php echo $j ?>" value="<?php echo $row_TEmployees['state'] ?>" />
                    <input type="hidden" id="deduct<?php echo $j ?>" name="deduct<?php echo $j ?>" value="<?php echo $prep['Deductions'] ?>" /></td>
                  <td><input type="text" name="worked<?php echo $j ?>" id="worked<?php echo $j ?>" style="width:40px" onChange="vetdays(<?php echo $j ?>)" /></td>
                  <td align="center"><?php echo strbrief($row_TEmployees['salary_name'], 10) ?></td>
                  <td align="center" id="reswin<?php echo $j ?>">&nbsp;</td>
                  <td align="center" id="dedwin<?php echo $j ?>"><?php echo $prep['DedHTML'] ?></td>
                  <td align="center" nowrap="nowrap"><?php echo $prep['sal']['Total']; ?></td>
                  <td align="center" nowrap="nowrap"><?php echo $prep['bon']['Total']; ?></td>
                  <td align="center" nowrap="nowrap"><?php echo $prep['ded']['Total']; ?></td>
                  <td align="center" nowrap="nowrap"><?php echo $prep['tax']['Total']; ?></td>
                  </tr>
                <?php $j++;} ?>
              </table>
                <script type="text/javascript">
                            for (var s=1; s<<?php echo $j ?>; s++)
                                prepRes(true, s);
                        </script></td>
            </tr>

          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
