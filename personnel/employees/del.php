<?php

require_once("../../scripts/init.php");
vetAccess('Personnel', 'Employees', 'Del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorID={$id} AND VendorID<>1";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
    delDocs('Personnel', 'Employees', $id);
    delPixs(STAFFPIX_DIR, $id);
}
header("Location: index.php");
