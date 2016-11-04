<?php
require_once("../../scripts/init.php");

vetAccess('Clients', 'Customers', 'Del');
$id = intval(_xget('id'));

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`vendors` 
    WHERE VendorID=$id AND VendorID>6 AND `VendorType`=1";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
    delDocs('Clients', 'Customers', $id);
    $dirname = ROOT . CLIENTPIX_DIR . $_SESSION['coyid'] . DS . $id;
    if (file_exists($dirname))
        rmdirr($dirname);
}
header("Location: index.php");
?>
