<?php require_once("../../scripts/init.php");

vetAccess('Accounts', 'Journal', 'Del');
$id = intval(_xget('id'));

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`bills` 
    WHERE BillID=$id AND Posted=0";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
    delDocs('Accounts', 'Journal', $id);
}
header("Location: index.php");

?>