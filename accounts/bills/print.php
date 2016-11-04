<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Journal'));
vetAccess('Accounts', 'Journal', 'Print');

$id = _xget('id');

$creditor_sql = vendorFlds("creditor", "person");
$sql = "SELECT `bills`.*, $vendor_sql, $creditor_sql, vendortypes.VendorType AS vtype, 
    currencies.code, currencyname, unitname, catname, Category 
    FROM `{$_SESSION['DBCoy']}`.`bills`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`          ON `bills`.EmployeeID=`vendors`.VendorID 
    INNER JOIN `" . DB_NAME . "`.`vendortypes`           ON `bills`.VendorType=`vendortypes`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` creditor ON `bills`.VendorID=`creditor`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`currencies`       ON `creditor`.currency=currencies.cur_id 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications`   ON `bills`.BillType=classifications.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`            ON `bills`.Status=status.CategoryID 
    WHERE `BillID`=$id";
$row_TBills = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
<script language="JavaScript1.2" type="text/javascript">
    window.onload = function() {
        isPayable();
    }
</script>
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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblBills.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
            <table border="0" cellpadding="4" cellspacing="4">
              <tr>
                <td class="titles">Payable:</td>
                <td align="left"><input type="checkbox" name="payable" id="payable"<?php if ($row_TBills['payable'] == 1) echo " checked=\"checked\"" ?> disabled="disabled" /></td>
              </tr>
              <tr>
                <td class="titles" id="titleEntry">Invoice #:</td>
                <td align="left"><table id="tabentry">
                  <tr>
                    <td><input type="radio" name="entrytype" value="1" id="entrytype_0"<?php if ($row_TBills['entrytype'] == 1) echo " checked=\"checked\"" ?> disabled="disabled" /></td>
                    <td class="blue-normal"><strong>Debit</strong></td>
                    <td>&nbsp;</td>
                    <td><input type="radio" name="entrytype" value="2" id="entrytype_1"<?php if ($row_TBills['entrytype'] == 2) echo " checked=\"checked\"" ?> disabled="disabled" /></td>
                    <td class="blue-normal"><strong>Credit</strong></td>
                  </tr>
                </table>
                  <span id="InvoiceID"><?php echo $row_TBills['InvoiceID'] ?></span></td>
              </tr>
              <tr>
                <td class="titles">Description:</td>
                <td><?php echo $row_TBills['BillTitle'] ?></td>
              </tr>
              <tr>
                <td class="titles">Client:</td>
                <td align="left"><table width="300" border="0" cellpadding="1" cellspacing="1">
                  <tr>
                    <td nowrap="nowrap" bgcolor="#000000" class="boldwhite1"><strong><?php echo $row_TBills['vtype'] ?>:</strong></td>
                    <td align="left" class="blue-normal">&nbsp;</td>
                    <td width="100%" align="left" bgcolor="#003366" class="boldwhite1"><?php echo $row_TBills['person'] ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" bgcolor="#999999"><?php echo $row_TBills['CustomerName'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td width="120" class="titles">Value:</td>
                <td><table border="0" cellspacing="1" cellpadding="1">
                  <tr>
                    <td><?php echo $row_TBills['code'] ?></td>
                    <td><?php echo $row_TBills['Amount'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Journal Entry Date:</td>
                <td align="left"><?php echo $row_TBills['BillDate'] ?></td>
              </tr>
              <tr>
                <td class="titles">Date Received:</td>
                <td align="left"><?php echo $row_TBills['ReceivedDate'] ?></td>
              </tr>
              <tr>
                <td class="titles">Department:</td>
                <td align="left"><?php echo $row_TBills['Department']; ?></td>
              </tr>
              <tr>
                <td class="titles">Category:</td>
                <td><?php echo $row_TBills['catname'] ?></td>
              </tr>
              <tr>
                <td class="titles">Status: </td>
                <td><?php echo $row_TBills['Category'] ?></td>
              </tr>
              <tr>
                <td class="titles">Posted:</td>
                <td><input type="checkbox" name="Posted"<?php if ($row_TBills['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
              </tr>
              <tr>
                <td class="titles">Staff:</td>
                <td><?php echo $row_TBills['VendorName'] ?></td>
              </tr>
              <tr>
                <td valign="top" class="titles">Notes:</td>
                <td align="left"><textarea name="Notes" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TBills['Notes'] ?></textarea></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              
          </table></td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
    $("#ordwords").html(NumToWords(<?php echo $row_TBills['Amount']; ?>, "<?php echo $row_TBills['currencyname']; ?>", "<?php echo $row_TBills['unitname']; ?>"));
    print();
});
</script>
</body>
</html>
