<?php require_once("../../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Certificates'));
vetAccess('Academics', 'Certificates', 'del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_certificates` WHERE cert_id={$_SESSION['cert_id']}";
$del = runDBQry($dbh, $sql);

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_terms` WHERE session={$_SESSION['cert_id']}";
runDBQry($dbh, $sql);

header("Location: index.php");

?>