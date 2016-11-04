<?php require_once("../../scripts/init.php");
vetAccess('Operations', 'Services', 'Del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`items` WHERE ItemID=$id";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
    delDocs('Operations', 'Services', $id);
}
header("Location: index.php");
?>