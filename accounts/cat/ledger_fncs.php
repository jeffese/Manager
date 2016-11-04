<?php

$LedgTyps = array('', 'Expense', 'Income', 'Bill', 'Invoice', 'Purchase Order', 'Order Return', 'Deductions');

function getLedger($id) {
    global $dbh, $from, $to;
    $where = "Posted=1 AND LedgerDate>='$from' AND LedgerDate<='$to'";
    $sql = "SELECT LedgerType, `Ledger`.VendorID, LedgerID, `vendortypes`.VendorType AS VType, 
        VendorName, Title, Debit, Credit, TransDate 
        FROM (
            SELECT 1 AS LedgerType, VendorType, VendorID, ExpenseID AS LedgerID, 
            `ExpenseTitle` AS `Title`, Recipient AS VendorName, ExpenseType AS Type, Amount AS Debit, 0 AS Credit, 
            PaymentMethodID, AccountBalance, RecAccountBalance, ExpenseDate AS TransDate, LedgerDate 
            FROM `{$_SESSION['DBCoy']}`.expenses 
            WHERE ExpenseType=$id AND $where
            UNION 
            SELECT 2 AS LedgerType, VendorType, VendorID, PaymentID AS LedgerID, 
            `PaymentTitle` AS `Title`, Payer AS VendorName, PaymentType AS Type, 0 AS Debit, Amount AS Credit, 
            PaymentMethodID,  AccountBalance, RecAccountBalance, PaymentDate AS TransDate, LedgerDate 
            FROM `{$_SESSION['DBCoy']}`.payments 
            WHERE PaymentType=$id AND $where
            UNION 
            SELECT 2 AS LedgerType, VendorType, VendorID, PaymentID AS LedgerID, 
            `PaymentTitle` AS `Title`, Payer AS VendorName, PaymentType AS Type, Amount AS Debit, 0 AS Credit, 
            PaymentMethodID,  AccountBalance, RecAccountBalance, LedgerDate AS TransDate, LedgerDate 
            FROM `{$_SESSION['DBCoy']}`.payments 
            WHERE VendorType=4 AND PaymentType=$id AND $where
            UNION 
            SELECT 3 AS LedgerType, VendorType, VendorID, BillID AS LedgerID, 
            `BillTitle` AS `Title`, CustomerName AS VendorName, BillType AS Type, 0 AS Debit, Amount AS Credit, 
            0 AS PaymentMethodID, 0 AS AccountBalance, RecAccountBalance, BillDate AS TransDate, LedgerDate 
            FROM `{$_SESSION['DBCoy']}`.bills 
            WHERE BillType=$id AND $where
            UNION 
            SELECT 4 AS LedgerType, VendorType, VendorID, InvoiceID AS LedgerID, 
            '' AS `Title`, CustomerName AS VendorName, InvoiceType AS Type, Grandvalue AS Debit, 0 AS Credit, 
            0 AS PaymentMethodID, 0 AS AccountBalance, RecAccountBalance, InvoiceDate AS TransDate, LedgerDate 
            FROM `{$_SESSION['DBCoy']}`.invoices 
            WHERE InvoiceType=$id AND $where
        ) AS Ledger
        INNER JOIN `" . DB_NAME . "`.`vendortypes`         ON `Ledger`.VendorType=`vendortypes`.VendorID 
        ORDER BY LedgerDate";
    return getDBData($dbh, $sql);
}

function getAggr($id) {
    global $dbh, $from, $to;
    $where = "Posted=1 AND LedgerDate>='$from' AND LedgerDate<='$to'";
    $cat_id = getCat_id($id);
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
    WHERE `Ledger`.Type=$id OR `category_id` LIKE '{$cat_id}-%'";

    $sql = "SELECT SUM(Debit) AS Debits, SUM(Credit) AS Credits
            {$From}";
    return getDBDataRow($dbh, $sql);
}

function getCat_id($id) {
    global $dbh;
    return getDBDataFldkey($dbh, "`{$_SESSION['DBCoy']}`.`classifications`", '`catID`', 'category_id', $id);
}

function gapTitle($grp, $gap, $title, $space = "&nbsp;") {
    $pre = $gap > 1 ? str_repeat($space, ($gap - 1) * 4) : "";
    $pre .= $grp ? "+ " : "";
    return "$pre$title";
}

function genCat($id, $cat, $gap) {
    global $dbh;
    $gap++;
    $sql = "SELECT `catID`, `category_id`, `category_name`
            FROM `{$_SESSION['DBCoy']}`.`classifications` 
            WHERE `parent_id` = '$cat' AND `catID` != '$id'
            ORDER BY `category_id`";
    $Tcat = getDBData($dbh, $sql);

    $Ledger = getLedger($id);
    foreach ($Ledger as $row) {
        printRow($row, $gap, FALSE);
    }

    foreach ($Tcat as $row_Tcat) {
        $sums = getAggr($row_Tcat['catID']);
        $row_Tcat['Debit'] = $sums['Debits'];
        $row_Tcat['Credit'] = $sums['Credits'];
        $row_Tcat['Title'] = htmlspecialchars($row_Tcat["category_name"]);
        $row_Tcat['LedgerID'] = "";
        $row_Tcat['TransDate'] = "";
        $row_Tcat['VendorName'] = "";
        $row_Tcat['VType'] = "";
        printRow($row_Tcat, $gap, TRUE);
        genCat($row_Tcat['catID'], $row_Tcat['category_id'], $gap);
    }
}
