<?php require_once("../../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'EDMS'));
$access = _xvar_arr_sub($_access, array('Templates'));
vetAccess('EDMS', 'Templates', 'Del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`edms_tmpl` WHERE `tmpl_id`=$id";
if (runDBQry($dbh, $sql) > 0) {
    rmdirr(EDMS_TMPL_DIR . $_SESSION['coyid'] . DS . $id . '/');
}
header("Location: index.php");

