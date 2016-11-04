<?php
require_once('../../scripts/init.php');

$from = _xget("s");
$to = _xget("e");

$where = "Posted=1 AND LedgerDate>='$from' AND LedgerDate<='$to'";
$From = "FROM (
        SELECT ExpenseType AS Type, Amount AS Debit, 0 AS Credit
        FROM `{$_SESSION['DBCoy']}`.expenses 
        WHERE $where
        UNION 
        SELECT PaymentType AS Type, 0 AS Debit, Amount AS Credit
        FROM `{$_SESSION['DBCoy']}`.payments 
        WHERE $where
        UNION 
        SELECT PaymentType AS Type, Amount AS Debit, 0 AS Credit
        FROM `{$_SESSION['DBCoy']}`.payments 
        WHERE VendorType=4 AND $where
        UNION 
        SELECT BillType AS Type, 0 AS Debit, Amount AS Credit
        FROM `{$_SESSION['DBCoy']}`.bills 
        WHERE $where
        UNION 
        SELECT InvoiceType AS Type, Grandvalue AS Debit, 0 AS Credit
        FROM `{$_SESSION['DBCoy']}`.invoices 
        WHERE $where
    ) AS Ledger 
    INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `Ledger`.Type=classifications.catID 
    WHERE `Ledger`.Type=7 OR `category_id` LIKE '7-%'";

$sql = "SELECT SUM(Debit) AS Debits, SUM(Credit) AS Credits {$From}";
$Bal = getDBDataRow($dbh, $sql);
?>
<script>
    $('#debit').val('<?php echo number_format($Bal['Debits'], 2, '.', ',') ?>');
    $('#credit').val('<?php echo number_format($Bal['Credits'], 2, '.', ',') ?>');
</script>
