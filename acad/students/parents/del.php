<?php require_once("../../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Academics'));
$access = _xvar_arr_sub($_access, array('Parents'));
vetAccess('Academics', 'Parents', 'del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorID={$_SESSION['parent_id']}";
if (runDBQry($dbh, $sql) > 0) {
	$dirname = ROOT . PARENT_PIX_DIR . $_SESSION['coyid'] . DS . $_SESSION['parent_id'];
    if (file_exists($dirname)) {
        rmdirr($dirname);
    }
}
header("Location: index.php");
?>
