<?php
require_once('../../scripts/init.php');
require_once('ledger_fncs.php');

$url_var = _xget("id");
$from = _xget("s");
$to = _xget("e");
$url_var = $url_var == "" ? "7" : $url_var;
$cat_id = getCat_id($url_var);

header("Content-type:text/xml");
print('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
$parid = $url_var != "7" ? " parent=\"$url_var\"" : "";
print("<rows$parid>\n");

$sql = "SELECT `catID`, `category_id`, `category_name`
        FROM `{$_SESSION['DBCoy']}`.`classifications` 
        WHERE `parent_id` = '$cat_id' AND `catID` != $url_var
        ORDER BY `category_id`";
$Tcat = getDBData($dbh, $sql);
foreach ($Tcat as $row_Tcat) {
    $sums = getAggr($row_Tcat['catID']);
    print("   <row id=\"{$row_Tcat['catID']}\" xmlkids=\"1\">\n");
    print("       <cell>" . htmlspecialchars($row_Tcat["category_name"]) . "</cell>\n");
    print("       <cell></cell>\n");
    print("       <cell></cell>\n");
    print("       <cell></cell>\n");
    print("       <cell></cell>\n");
    print("       <cell>" . number_format($sums['Debits'], 2, '.', ',') . "</cell>\n");
    print("       <cell>" . number_format($sums['Credits'], 2, '.', ',') . "</cell>\n");
    print("   </row>\n");
}

$Ledger = getLedger($url_var);
foreach ($Ledger as $row_Ledger) {
    print("   <row id=\"{$row_Ledger['LedgerType']}-{$row_Ledger['LedgerID']}\">\n");
    print("       <cell>" . htmlspecialchars($row_Ledger['Title']) . "</cell>\n");
    print("       <cell>{$row_Ledger['VType']}</cell>\n");
    print("       <cell>" . htmlspecialchars($row_Ledger['VendorName']) . "</cell>\n");
    print("       <cell>{$row_Ledger['TransDate']}</cell>\n");
    print("       <cell>{$row_Ledger['LedgerID']}</cell>\n");
    print("       <cell>" . number_format($row_Ledger['Debit'], 2, '.', ',') . "</cell>\n");
    print("       <cell>" . number_format($row_Ledger['Credit'], 2, '.', ',') . "</cell>\n");
    print("   </row>\n");
}
print("</rows>\n");
