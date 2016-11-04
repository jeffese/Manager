<?php
require_once('../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Administration'));
$access = _xvar_arr_sub($_access, array('Company Info'));
vetAccess('Administration', 'Company Info', 'Account View');

$id = intval(_xget('id'));

$acc = intval(_xget('acc'));
$seek = $acc == 0 ? "VendorID" : "AccountID";
preOrd("ledger", array('', 'LedgerType', 'LType', 'VType', 'VendorName', 'LedgerID', 'TransactionMethod', 
        'TransDate', 'Debit', 'Credit', 'AccountBalance', 'RecAccountBalance'));

$From = "FROM (
            SELECT 1 AS LedgerType, AccountID, VendorType, VendorID, ExpenseID AS LedgerID, 
            Recipient AS VendorName, ExpenseType AS Type, Amount AS Debit, 0 AS Credit, 
            PaymentMethodID, AccountBalance, RecAccountBalance, ExpenseDate AS TransDate 
            FROM `{$_SESSION['DBCoy']}`.expenses 
            WHERE Posted=1 
            UNION 
            SELECT 2 AS LedgerType, AccountID, VendorType, VendorID, PaymentID AS LedgerID, 
            Payer AS VendorName, PaymentType AS Type, 0 AS Debit, Amount AS Credit, 
            PaymentMethodID,  AccountBalance, RecAccountBalance, PaymentDate AS TransDate 
            FROM `{$_SESSION['DBCoy']}`.payments 
            WHERE Posted=1 
            UNION 
            SELECT 2 AS LedgerType, AccountID, VendorType, VendorID, PaymentID AS LedgerID, 
            Payer AS VendorName, PaymentType AS Type, Amount AS Debit, 0 AS Credit, 
            PaymentMethodID,  AccountBalance, RecAccountBalance, PaymentDate AS TransDate 
            FROM `{$_SESSION['DBCoy']}`.payments 
            WHERE VendorType=4 AND Posted=1 
            UNION 
            SELECT 3 AS LedgerType, 0 AS AccountID, VendorType, VendorID, BillID AS LedgerID, 
            CustomerName AS VendorName, BillType AS Type, 0 AS Debit, Amount AS Credit, 
            0 AS PaymentMethodID, 0 AS AccountBalance, RecAccountBalance, BillDate AS TransDate 
            FROM `{$_SESSION['DBCoy']}`.bills 
            WHERE Posted=1 
            UNION 
            SELECT 4 AS LedgerType, 0 AS AccountID, VendorType, VendorID, InvoiceID AS LedgerID, 
            CustomerName AS VendorName, InvoiceType AS Type, Grandvalue AS Debit, 0 AS Credit, 
            0 AS PaymentMethodID, 0 AS AccountBalance, RecAccountBalance, LedgerDate AS TransDate 
            FROM `{$_SESSION['DBCoy']}`.invoices 
            WHERE Posted=1 
            UNION 
            SELECT 5 AS LedgerType, 0 AS AccountID, VendorType, SupplierID AS VendorID, 
            OrderID AS LedgerID, $vendor_sql, 0 AS Type, 0 AS Debit, TotalValue AS Credit, 
            0 AS PaymentMethodID, 0 AS AccountBalance, RecAccountBalance, OrderDate AS TransDate 
            FROM `{$_SESSION['DBCoy']}`.orders 
            INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON orders.SupplierID=`vendors`.VendorID 
            WHERE Posted=1
            UNION 
            SELECT 6 AS LedgerType, 0 AS AccountID, VendorType, SupplierID AS VendorID, 
            OrderRetID AS LedgerID, $vendor_sql, 0 AS Type, orderreturns.TotalValue AS Debit, 0 AS Credit, 
            0 AS PaymentMethodID, 0 AS AccountBalance, orderreturns.RecAccountBalance, ReturnDate AS TransDate 
            FROM `{$_SESSION['DBCoy']}`.orderreturns 
            INNER JOIN `{$_SESSION['DBCoy']}`.orders    ON orderreturns.OrderID=orders.OrderID
            INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON orders.SupplierID=`vendors`.VendorID 
            WHERE orderreturns.Posted=1
            UNION 
            SELECT 7 AS LedgerType, 0 AS AccountID, VendorType, deductions.VendorID, 
            ded_id AS LedgerID, $vendor_sql, 0 AS Type, deductions.deduct AS Debit, 0 AS Credit, 
            0 AS PaymentMethodID, 0 AS AccountBalance, `accbal` AS RecAccountBalance, `dt` AS TransDate 
            FROM `{$_SESSION['DBCoy']}`.deductions 
            INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON deductions.VendorID=`vendors`.VendorID 
        ) AS Ledger 
    INNER JOIN `" . DB_NAME . "`.`vendortypes`         ON `Ledger`.VendorType=`vendortypes`.VendorID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `Ledger`.Type=classifications.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`          ON `Ledger`.PaymentMethodID=status.CategoryID
    WHERE `Ledger`.$seek=$id";
    
$sql = "SELECT LedgerType, AccountID, `Ledger`.VendorID, LedgerID, `vendortypes`.VendorType AS VType, VendorName, 
        catname AS LType, Debit, Credit, Category AS TransactionMethod, AccountBalance, RecAccountBalance, TransDate 
        {$From}{$orderval}";
            
$TLedger = getDBData($dbh, $sql);
$currentPage = 'printall.php';
doExcel($TLedger);

$client = getDBDataRow($dbh, "SELECT $vendor_sql 
    FROM `{$_SESSION['DBCoy']}`.`vendors` 
    WHERE `VendorID`=" . intval(_xget('vid')));
    
$LedgTyps = array('', 'Expense', 'Payment', 'Bill', 'Invoice', 'Purchase Order', 'Order Return', 'Deductions');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
    
    function showTrans(typ, id) {
        var lnks = [[],
            <?php $mnu_lnks = Array_Lnks();
                echo "['", $mnu_lnks['Accounts']['Expenses'][0], "', '", $mnu_lnks['Accounts']['Expenses'][1], "'],\n";
                echo "['", $mnu_lnks['Accounts']['Payments'][0], "', '", $mnu_lnks['Accounts']['Payments'][1], "'],\n";
                echo "['", $mnu_lnks['Accounts']['Bills'][0],    "', '", $mnu_lnks['Accounts']['Bills'][1],    "'],\n";
                echo "['", $mnu_lnks['Accounts']['Sales'][0],    "', '", $mnu_lnks['Accounts']['Sales'][1],    "'],\n";
                echo "['", $mnu_lnks['Stock']['Orders'][0],      "', '", $mnu_lnks['Stock']['Orders'][1],      "'],\n";
                echo "['", $mnu_lnks['Stock']['Returns'][0],     "', '", $mnu_lnks['Stock']['Returns'][1],     "']";
            ?>];
        top.leftFrame.showMod(lnks[typ][0], lnks[typ][1]+'view.php?id='+id);
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
                <td style="height:30px; min-width:500px; background-image:url(/images/lblledger.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"><?php echo $client['VendorName'] ?></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="4" cellpadding="4">
                <tr>
                  <td><table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="center" class="boldwhite1"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td align="center" valign="top" bgcolor="#FFFBF0"><table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                                <tr align="center" bgcolor="#666666" class="boldwhite1">
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Type', $currentPage, 1, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Category', $currentPage, 2, $ord, $asc); ?></td>
                                  <?php if ($acc==1) { ?>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Client Type', $currentPage, 3, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Client', $currentPage, 4, $ord, $asc); ?></td>
                                  <?php } ?>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Trans. Mthd.', $currentPage, 5, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Date', $currentPage, 6, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Trans. #', $currentPage, 7, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Debit', $currentPage, 8, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Credit', $currentPage, 9, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Acc. Bal.', $currentPage, 10, $ord, $asc); ?></td>
                                </tr>
                                <?php $j=1;
	   foreach ($TLedger as $row_TLedger) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                                <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" 
onclick="showTrans(<?php echo $row_TLedger['LedgerType'], ", ", $row_TLedger['LedgerID']; ?>)">
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $LedgTyps[$row_TLedger['LedgerType']] ?></b></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TLedger['LType'] ?></b></td>
                                  <?php if ($acc==1) { ?>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TLedger['VType'] ?></b></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TLedger['VendorName'] ?></b></td>
                                  <?php } ?>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TLedger['TransactionMethod'] ?></b></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TLedger['TransDate'] ?></b></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TLedger['LedgerID'] ?></b></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TLedger['Debit'] ?></b></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TLedger['Credit'] ?></b></td>
                                  <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TLedger[$acc==1?'AccountBalance':'RecAccountBalance'] ?></b></td>
                                </tr>
                                <?php $j++;} ?>
                              </table></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
              <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
          </tr>
        </table></td>
      </tr>
    </table>
    </body>
</html>