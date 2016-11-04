<?php require_once("../../scripts/init.php");
vetAccess('Personnel', 'Pay Slips', 'Del');
$id = intval(_xget('id'));

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`paybatch` WHERE paybatchid={$id} AND posted=0";
runDBQry($dbh, $sql);
header("Location: index.php");

?>