<?php require_once("../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Salaries'));
vetAccess('Personnel', 'Salaries', 'Del');
$id = intval(_xget('id'));

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`salaryscale` WHERE salary_id={$id}";
runDBQry($dbh, $sql);
header("Location: index.php");

?>