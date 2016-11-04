<?php
require_once("$vpth/scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'Del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`outlets` WHERE OutletID>2 AND OutletID=$id";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
    delDocs($vmod, $vkey, $id);
}
header("Location: index.php");

?>