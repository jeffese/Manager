<?php require_once("../../scripts/init.php");
vetAccess('Administration', 'Users', 'Del');
$id = intval(_xget('id'));

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`users` WHERE EmployeeID=$id AND EmployeeID<>1";
runDBQry($dbh, $sql);
header("Location: index.php");

?>