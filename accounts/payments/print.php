<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Income'));
vetAccess('Accounts', 'Income', 'Print');

$id = _xget('id');

$outid = _xses('OutletID');
$Payer_sql = vendorFlds("Payer", "person");
$acc_sql = vendorFlds("acc", "acc_person");
$sql = "SELECT `payments`.*, OutletName, $vendor_sql, $Payer_sql, $acc_sql, vendortypes.VendorType AS vtype, 
    currencies.code, catname, status.Category, paytp.Category AS paytype, cards.Category AS card
    FROM `{$_SESSION['DBCoy']}`.`payments`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`        ON `payments`.EmployeeID=`vendors`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlets`        ON `payments`.OutletID=`outlets`.OutletID 
    INNER JOIN `" . DB_NAME . "`.`vendortypes`         ON `payments`.VendorType=`vendortypes`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` Payer  ON `payments`.VendorID=`Payer`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` acc    ON `payments`.AccountID=`acc`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`currencies`     ON `Payer`.currency=currencies.cur_id 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `payments`.PaymentType=classifications.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`          ON `payments`.Status=status.CategoryID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`  paytp   ON `payments`.PaymentMethodID=paytp.CategoryID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`  cards   ON `payments`.CreditCardType=cards.CategoryID 
    WHERE `PaymentID`=$id AND `payments`.OutletID IN ($outid)";
$row_TPayments = getDBDataRow($dbh, $sql);

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
        paytype(<?php echo $row_TPayments['PaymentMethodID']; ?>);
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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblpayments.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
            <table border="0" cellpadding="4" cellspacing="4">
              <tr>
                <td width="120" class="titles">Income ID:</td>
                <td class="red-normal"><b><?php echo $row_TPayments['PaymentID']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Outlet:</td>
                <td><b><?php echo $row_TPayments['OutletName']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Invoice #:</td>
                <td align="left"><?php echo $row_TPayments['InvoiceID'] ?></td>
              </tr>
              <tr>
                <td class="titles">Description:</td>
                <td><?php echo $row_TPayments['PaymentTitle'] ?></td>
              </tr>
              <tr>
                <td class="titles">Payer:</td>
                <td align="left"><table width="300" border="0" cellpadding="1" cellspacing="1">
                  <tr class="boldwhite1">
                    <td bgcolor="#000000"><?php echo $row_TPayments['vtype'] ?>: </td>
                    <td align="left">&nbsp;</td>
                    <td width="100%" align="left" bgcolor="#003366"><?php echo $row_TPayments['person'] ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" bgcolor="#999999"><?php echo $row_TPayments['Payer'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Value:</td>
                <td><table border="0" cellspacing="1" cellpadding="1">
                  <tr>
                    <td><?php echo $row_TPayments['code'] ?></td>
                    <td><?php echo $row_TPayments['Amount'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Account:</td>
                <td><?php echo $row_TPayments['acc_person'] ?></td>
              </tr>
              <tr>
                <td class="titles">Income:</td>
                <td bgcolor="#999999"><table width="300" border="0" cellpadding="1" cellspacing="1">
                  <tr>
                    <td bgcolor="#000000" class="boldwhite1"><?php echo $row_TPayments['paytype'] ?></td>
                  </tr>
                  <tr>
                    <td><table border="0" cellspacing="1" cellpadding="1" id="pay21" style="display:none">
                      <tr>
                        <td align="right" nowrap="nowrap" class="boldwhite1">Card Type:</td>
                        <td><?php echo $row_TPayments['card'] ?></td>
                      </tr>
                      <tr>
                        <td align="right" nowrap="nowrap" class="boldwhite1">Card Holder:</td>
                        <td><?php echo $row_TPayments['AccountName'] ?></td>
                      </tr>
                      <tr>
                        <td align="right" nowrap="nowrap" class="boldwhite1">Card #:</td>
                        <td><?php echo $row_TPayments['AccountNumber'] ?></td>
                      </tr>
                    </table>
                      <table border="0" cellspacing="1" cellpadding="1" id="pay23" style="display:none">
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Bank:</td>
                          <td><?php echo $row_TPayments['PaymentMethod'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Account Name:</td>
                          <td><?php echo $row_TPayments['AccountName'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Account #:</td>
                          <td><?php echo $row_TPayments['AccountNumber'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Cheque #:</td>
                          <td><?php echo $row_TPayments['CheckNumber'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Cheque Date:</td>
                          <td><?php echo $row_TPayments['CheckDate'] ?></td>
                        </tr>
                      </table>
                      <table border="0" cellspacing="1" cellpadding="1" id="pay25" style="display:none">
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Institution/Method:</td>
                          <td><?php echo $row_TPayments['PaymentMethod'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Payer Name:</td>
                          <td><?php echo $row_TPayments['AccountName'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Tracking #:</td>
                          <td><?php echo $row_TPayments['CheckNumber'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Other Info:</td>
                          <td><?php echo $row_TPayments['AccountNumber'] ?></td>
                        </tr>
                        <tr>
                          <td align="right" nowrap="nowrap" class="boldwhite1">Date:</td>
                          <td><?php echo $row_TPayments['CheckDate'] ?></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Income Date:</td>
                <td align="left"><?php echo $row_TPayments['PaymentDate'] ?></td>
              </tr>
              <tr>
                <td class="titles">Category:</td>
                <td><?php echo $row_TPayments['catname'] ?></td>
              </tr>
              <tr>
                <td class="titles">Status: </td>
                <td><?php echo $row_TPayments['Category'] ?></td>
              </tr>
              <tr>
                <td class="titles">Posted:</td>
                <td><input type="checkbox" name="Posted"<?php if ($row_TPayments['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
              </tr>
              <tr>
                <td class="titles">Staff:</td>
                <td><?php echo $row_TPayments['VendorName'] ?></td>
              </tr>
              <tr>
                <td valign="top" class="titles">Notes:</td>
                <td align="left"><textarea name="Notes" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TPayments['Notes'] ?></textarea></td>
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
    $("#ordwords").html(NumToWords(<?php echo $row_TPayments['Amount']; ?>, "<?php echo $row_TPayments['currencyname']; ?>", "<?php echo $row_TPayments['unitname']; ?>"));
    print();
});
</script>
</body>
</html>