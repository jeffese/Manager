<?php require_once("../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Students'));
vetAccess('Academics', 'Students', 'del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorID={$_SESSION['stud_id']}";
if (runDBQry($dbh, $sql) > 0) {
    delPixs(STUDPIX_DIR, $_SESSION['stud_id']);
}
$cls = isset($_GET['cls']) ? '?'.$_SERVER['QUERY_STRING'] : '';
header("Location: index.php$cls");
?>
