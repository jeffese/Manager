<?php require_once("../../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Schemes'));
vetAccess('Academics', 'Schemes', 'del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_schemes` WHERE schm_id={$_SESSION['schm']}";
runDBQry($dbh, $sql);
header("Location: index.php");

?>