<?php require_once("../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Programs'));
vetAccess('Academics', 'Programs', 'del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_arms` WHERE class IN (SELECT class_id FROM `{$_SESSION['DBCoy']}`.`sch_class` WHERE program={$_SESSION['prog_id']})";
runDBQry($dbh, $sql);

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_class` WHERE program={$_SESSION['prog_id']}";
runDBQry($dbh, $sql);

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_programs` WHERE prog_id={$_SESSION['prog_id']}";
$del = runDBQry($dbh, $sql);

header("Location: index.php");

?>