<?php require_once("../../scripts/init.php");

vetAccess('Administration', 'Currency', 'Del');
$id = intval(_xget('id'));

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`currencies` WHERE cur_id=$id";
runDBQry($dbh, $sql);
header("Location: index.php");

?>