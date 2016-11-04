<?php require_once("../../scripts/init.php");

vetAccess('Accounts', 'Income', 'Del');
$id = intval(_xget('id'));

$outid = _xses('OutletID');
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`payments` 
    WHERE PaymentID=$id AND Posted=0 AND OutletID IN ($outid)";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
    delDocs('Accounts', 'Income', $id);
}
header("Location: index.php");

?>