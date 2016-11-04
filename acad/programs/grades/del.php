<?php require_once("../../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Grades'));
vetAccess('Academics', 'Grades', 'del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_grade_sys` WHERE grade_sys_id={$_SESSION['grade_sys_id']}";
$del = runDBQry($dbh, $sql);

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_grades` WHERE grd_sys={$_SESSION['grade_sys_id']}";
runDBQry($dbh, $sql);

header("Location: index.php");

?>