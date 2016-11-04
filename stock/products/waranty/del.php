<?php
require_once("../../../scripts/init.php");

vetAccess('Stock', 'Products', 'Del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`warranty` WHERE WarantyID={$id}";
runDBQry($dbh, $sql);
header("Location: index.php");
?>