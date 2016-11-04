<?php
require_once('../scripts/init.php');

$id  = _xget('id');

$sql = "SELECT $vendor_sql
        FROM `{$_SESSION['DBCoy']}`.`vendors` 
        WHERE `VendorID`=$id";
$supos = getDBDataRow($dbh, $sql);
echo $supos ? $supos['VendorName'] : 'UnKnown';
