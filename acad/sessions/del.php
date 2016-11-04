<?php require_once("../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Sessions'));
vetAccess('Academics', 'Sessions', 'del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_sessions` WHERE sess_id={$_SESSION['sess_id']}";
$del = runDBQry($dbh, $sql);

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_terms` WHERE session={$_SESSION['sess_id']}";
runDBQry($dbh, $sql);

header("Location: index.php");

?>