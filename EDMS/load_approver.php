<?php
require_once('../scripts/init.php');

$term  = _xget('term');
$vendor_sql = vendorFlds("vendors", "label");

$sql = "SELECT `VendorID` AS `value`, $vendor_sql
        FROM `{$_SESSION['DBCoy']}`.`vendors` "
        . "WHERE `ContactLastName` LIKE '%$term%' "
                . "OR `ContactMidName` LIKE '%$term%' "
                . "OR `ContactFirstName` LIKE '%$term%'";
$supos = getDBData($dbh, $sql);

echo json_encode($supos);