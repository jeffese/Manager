<?php require_once("../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Courses'));
vetAccess('Academics', 'Courses', 'del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`sch_courses` WHERE course_id={$_SESSION['course_id']}";
runDBQry($dbh, $sql);
header("Location: index.php");

?>