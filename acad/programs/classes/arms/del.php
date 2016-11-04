<?php require_once("../../../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Classes'));
vetAccess('Academics', 'Classes', 'del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_arms` WHERE arm_id={$_SESSION['arm_id']}";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
	$_SESSION['arms']--;
}
header("Location: index.php");

?>