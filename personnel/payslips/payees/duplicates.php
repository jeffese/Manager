<?php
require_once('../../../scripts/init.php');
require_once("paygen.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Pay Slips'));
vetAccess('Personnel', 'Pay Slips', 'View');

preOrd("paylst", array('InUse', 'vendorcode', 'VendorName', 'category_name', 
    'InUse', 'worked', 'ref_no', 'salary_name'));

$id = intval(_xget('id'));
$sql = "SELECT *, DATEDIFF(`dtto`, `dtfrom`)+1 AS `dys` 
    FROM `{$_SESSION['DBCoy']}`.`paybatch` 
        WHERE paybatchid=$id";
$row_TPay = getDBDataRow($dbh, $sql);
$tax = $row_TPay['posted'] == 0 ? "0 AS paybatchid, `contract` AS `ref_no`, `salaryscale`.*, 
        `vendors`.`worked`, `VendorID` AS payslip_id," :
        "`ref_no`, `payslip`.`code`, `paybatchid`, `payslip_id`, `payslip`.`worked`, `details` AS";
$ijn = $row_TPay['posted'] == 0 ? 
        "INNER JOIN `{$_SESSION['DBCoy']}`.`salaryscale` ON `vendors`.`salary`=`salaryscale`.`salary_id`" :
        "INNER JOIN `{$_SESSION['DBCoy']}`.`payslip` ON `vendors`.`VendorID`=`payslip`.`staffid`";
$sql = "SELECT `VendorID`, `vendorcode`, $vendor_sql, category_name, `amtbal`, `InUse`, $tax `tax`
        FROM `{$_SESSION['DBCoy']}`.`vendors` 
        LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `vendors`.categoryid = classifications.catID 
        $ijn
        WHERE ". ($row_TPay['posted'] == 0 ? "`VendorType`=5 AND `InUse`=1" :
        "`paybatchid` IN 
            (SELECT `him`.`paybatchid` FROM `{$_SESSION['DBCoy']}`.`paybatch` `me`
                INNER JOIN `{$_SESSION['DBCoy']}`.`paybatch` `him` ON `me`.`dtfrom`=`him`.`dtfrom` 
                    AND `me`.`dtto`=`him`.`dtto`
                WHERE `me`.`paybatchid`=$id
                    AND `him`.`paybatchid`<>`me`.`paybatchid`
                    AND (`me`.`salary`=1 AND `him`.`salary`=1 
                        OR `me`.`bonus`=1 AND `him`.`bonus`=1)
             ) $orderval");
$TEmployees = getDBData($dbh, $sql);
$currentPage = 'duplicates.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="resource.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<form action="" method="post">
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center" class="boldwhite1"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                <tr align="center" bgcolor="#666666" class="boldwhite1">
                  <td>#</td>
                  <td>Batch #</td>
                  <td><?php echo setOrderTitle('Staff No.', $currentPage, 1, $ord, $asc); ?></td>
                  <td><?php echo setOrderTitle('Name', $currentPage, 2, $ord, $asc); ?></td>
                  <td><?php echo setOrderTitle('Category', $currentPage, 3, $ord, $asc); ?></td>
                  <td><?php echo setOrderTitle('Terminated', $currentPage, 4, $ord, $asc); ?></td>
                  <td><?php echo setOrderTitle('Worked', $currentPage, 5, $ord, $asc); ?></td>
                  <td>Resources</td>
                  <td>Salary</td>
                  <td>Bonus</td>
                  <td>Tax</td>
                  <td><iframe id="actwin" style="display:none"></iframe></td>
                  <td>&nbsp;</td>
                  <?php if ($row_TPay['posted'] == 1) { ?>
                  <?php } ?>
                </tr>
                <?php $j=1;
	   foreach ($TEmployees as $row_TEmployees) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5";
          if ($row_TPay['posted'] == 1) {
              $codes = explode('@@@', $row_TEmployees['code']);
              $row_TEmployees['parts'] = $codes[1];
              $row_TEmployees['typs'] = $codes[2];
              $row_TEmployees['cmls'] = $codes[3];
              $row_TEmployees['ftyp'] = $codes[4];
              $row_TEmployees['oprs'] = $codes[5];
              $row_TEmployees['fncs'] = $codes[6];
              $row_TEmployees['flds'] = $codes[7];
              $row_TEmployees['wins'] = $codes[8];
              $row_TEmployees['state'] = $codes[9];
              $row_TEmployees['deduct'] = $codes[10];
              $row_TEmployees['InUse'] = 1;
          }
          $prep = prepView($row_TEmployees, $row_TPay);
	  ?>
                <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');">
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><?php echo $j ?></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['paybatchid'] ?></b></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['vendorcode'] ?></b></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['VendorName'] ?></b></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['category_name']; ?></b></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input type="checkbox" name="InUse<?php echo $j ?>" value=""  <?php echo $row_TEmployees['InUse']==1? "checked=\"checked\"": ""; ?> disabled="disabled" />
                    <input type="hidden" name="VendorID<?php echo $j ?>" value="<?php echo $row_TEmployees['VendorID']; ?>" />
                    <input type="hidden" id="tax<?php echo $j ?>" name="tax<?php echo $j ?>" value="<?php echo $row_TEmployees['tax']; ?>" />
                    <input type="hidden" id="parts<?php echo $j ?>" name="parts<?php echo $j ?>" value="<?php echo $row_TEmployees['parts'] ?>" />
                    <input type="hidden" id="typs<?php echo $j ?>" name="typs<?php echo $j ?>" value="<?php echo $row_TEmployees['typs'] ?>" />
                    <input type="hidden" id="cmls<?php echo $j ?>" name="cmls<?php echo $j ?>" value="<?php echo $row_TEmployees['cmls'] ?>" />
                    <input type="hidden" id="oprs<?php echo $j ?>" name="oprs<?php echo $j ?>" value="<?php echo $row_TEmployees['oprs'] ?>" />
                    <input type="hidden" id="flds<?php echo $j ?>" name="flds<?php echo $j ?>" value="<?php echo $row_TEmployees['flds'] ?>" />
                    <input type="hidden" id="wins<?php echo $j ?>" name="wins<?php echo $j ?>" value="<?php echo $row_TEmployees['wins'] ?>" />
                    <input type="hidden" id="fncs<?php echo $j ?>" name="fncs<?php echo $j ?>" value="<?php echo $row_TEmployees['fncs'] ?>" />
                    <input type="hidden" id="state<?php echo $j ?>" name="state<?php echo $j ?>" value="<?php echo $row_TEmployees['state'] ?>" /></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TEmployees['worked'] ?></b></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" id="reswin<?php echo $j ?>">&nbsp;</td>
                  <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><?php echo $prep['sal']['Total']; ?></td>
                  <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><?php echo $prep['bon']['Total']; ?></td>
                  <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><?php echo $prep['tax']['Total']; ?></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><img src="/images/printer.png" width="16" height="16" onclick="$('#actwin').attr('src', 'slip.php?id=<?php echo $row_TEmployees['payslip_id'] ?>&bth=<?php echo $id ?>&p=1')" /></td>
                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><img src="/images/page_white_acrobat.png" width="16" height="16" onclick="$('#actwin').attr('src', 'pdf.php?id=<?php echo $row_TEmployees['payslip_id'] ?>&bth=<?php echo $id ?>')" /></td>
                  <?php if ($row_TPay['posted'] == 1) { ?>
                  <?php } ?>
                </tr>
                <?php $j++;} ?>
              </table>                <script type="text/javascript">
                            for (var s=1; s<<?php echo $j ?>; s++)
                                prepRes(false, s);
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
