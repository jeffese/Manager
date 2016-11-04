<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Expenses'));
vetAccess('Accounts', 'Expenses', 'Print');

$id = _xget('id');

$payee_sql = vendorFlds("payee", "person");
$acc_sql = vendorFlds("acc", "acc_person");
$sql = "SELECT `expenses`.*, $vendor_sql, $payee_sql, $acc_sql, vendortypes.VendorType AS vtype, 
    currencies.code, `typ`.catname, `dept`.catname AS catn, status.Category, paytp.Category AS paytype, cards.Category AS card
    FROM `{$_SESSION['DBCoy']}`.`expenses`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`        ON `expenses`.EmployeeID=`vendors`.VendorID 
    INNER JOIN `" . DB_NAME . "`.`vendortypes`         ON `expenses`.VendorType=`vendortypes`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` payee  ON `expenses`.VendorID=`payee`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` acc    ON `expenses`.AccountID=`acc`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`currencies`     ON `payee`.currency=currencies.cur_id 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `typ` ON `expenses`.ExpenseType=typ.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `dept` ON `expenses`.Dept=dept.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`          ON `expenses`.Status=status.CategoryID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`  paytp   ON `expenses`.PaymentMethodID=paytp.CategoryID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`  cards   ON `expenses`.CreditCardType=cards.CategoryID 
    WHERE `ExpenseID`=$id";
$row_TExpenses = getDBDataRow($dbh, $sql);

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
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
    window.onload = function() {
        paytype(<?php echo $row_TExpenses['PaymentMethodID']; ?>);
    }
</script>
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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblexpenses.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
            <table border="0" cellpadding="4" cellspacing="4">
              <tr>
                <td width="120" class="titles">Expense ID:</td>
                <td class="red-normal"><b><?php echo $row_TExpenses['ExpenseID']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Invoice #:</td>
                <td align="left"><?php echo $row_TExpenses['InvoiceID'] ?></td>
              </tr>
              <tr>
                <td class="titles">Description:</td>
                <td><?php echo $row_TExpenses['ExpenseTitle'] ?></td>
              </tr>
              <tr>
                <td class="titles">Payee:</td>
                <td align="left"><table width="300" border="0" cellpadding="1" cellspacing="1">
                  <tr class="boldwhite1">
                    <td nowrap="nowrap" bgcolor="#000000"><strong><?php echo $row_TExpenses['vtype'] ?>:</strong></td>
                    <td align="left">&nbsp;</td>
                    <td width="100%" align="left" bgcolor="#003366"><?php echo $row_TExpenses['person'] ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" bgcolor="#999999"><?php echo $row_TExpenses['Recipient'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Value:</td>
                <td><table border="0" cellspacing="1" cellpadding="1">
                  <tr>
                    <td><?php echo $row_TExpenses['code'] ?></td>
                    <td><?php echo $row_TExpenses['Amount'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Account:</td>
                <td><?php echo $row_TExpenses['acc_person'] ?></td>
              </tr>
              <tr>
                <td class="titles">Payment:</td>
                <td bgcolor="#999999"><table width="300" border="0" cellpadding="1" cellspacing="1">
                  <tr>
                    <td bgcolor="#000000" class="boldwhite1"><?php echo $row_TExpenses['paytype'] ?></td>
                  </tr>
                  <tr>
                    <td><table border="0" cellspacing="1" cellpadding="1" id="pay21" style="display:none">
                      <tr>
                        <td align="right" nowrap="nowrap" class="boldwhite1">Card Type:</td>
                        <td><?php echo $row_TExpenses['card'] ?></td>
                      </tr>
                      <tr>
                        <td align="right" nowrap="nowrap" class="boldwhite1">Card Holder:</td>
                        <td><?php echo $row_TExpenses['AccountName'] ?></td>
                      </tr>
                      <tr>
                        <td align="right" nowrap="nowrap" class="boldwhite1">Card #:</td>
                        <td><?php echo $row_TExpenses['AccountNumber'] ?></td>
                      </tr>
                    </table>
                      <table border="0" cellspacing="1" cellpadding="1" id="pay23" style="display:none">
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Bank:</td>
                          <td><?php echo $row_TExpenses['PaymentMethod'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Account Name:</td>
                          <td><?php echo $row_TExpenses['AccountName'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Account #:</td>
                          <td><?php echo $row_TExpenses['AccountNumber'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Cheque #:</td>
                          <td><?php echo $row_TExpenses['CheckNumber'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Cheque Date:</td>
                          <td><?php echo $row_TExpenses['CheckDate'] ?></td>
                        </tr>
                      </table>
                      <table border="0" cellspacing="1" cellpadding="1" id="pay25" style="display:none">
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Institution/Method:</td>
                          <td><?php echo $row_TExpenses['PaymentMethod'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Payer Name:</td>
                          <td><?php echo $row_TExpenses['AccountName'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Tracking #:</td>
                          <td><?php echo $row_TExpenses['CheckNumber'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Other Info:</td>
                          <td><?php echo $row_TExpenses['AccountNumber'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Date:</td>
                          <td><?php echo $row_TExpenses['CheckDate'] ?></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Expense Date:</td>
                <td align="left"><?php echo $row_TExpenses['ExpenseDate'] ?></td>
              </tr>
              <tr>
                <td class="titles">Date Received:</td>
                <td align="left"><?php echo $row_TExpenses['DateSubmitted'] ?></td>
              </tr>
              <tr>
                <td class="titles">Department:</td>
                <td><?php echo $row_TExpenses['catn'] ?></td>
              </tr>
              <tr>
                <td class="titles">Category:</td>
                <td><?php echo $row_TExpenses['catname'] ?></td>
              </tr>
              <tr>
                <td class="titles">Status: </td>
                <td><?php echo $row_TExpenses['Category'] ?></td>
              </tr>
              <tr>
                <td class="titles">Posted:</td>
                <td><input type="checkbox" name="Posted"<?php if ($row_TExpenses['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
              </tr>
              <tr>
                <td class="titles">Staff:</td>
                <td><?php echo $row_TExpenses['VendorName'] ?></td>
              </tr>
              <tr>
                <td valign="top" class="titles">Notes:</td>
                <td align="left"><textarea name="Notes" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TExpenses['Notes'] ?></textarea></td>
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
    $("#ordwords").html(NumToWords(<?php echo $row_TExpenses['Amount']; ?>, "<?php echo $row_TExpenses['currencyname']; ?>", "<?php echo $row_TExpenses['unitname']; ?>"));
    print();
});
</script>
</body>
</html>