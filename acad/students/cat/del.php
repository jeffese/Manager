<?php require_once("../../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Students'));
vetAccess('Academics', 'Students', 'del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`status` WHERE CategoryID={$_SESSION['stud_status']}";
runDBQry($dbh, $sql);
header("Location: index.php");

?>