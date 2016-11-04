<?php
require_once("$vpth/scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'Del');
$id = intval(_xget('id'));
$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`classifications` WHERE catID=$id";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
    delDocs($vmod, $vkey, $id);
    if ($vtype == 8) {
        $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`edms_num` WHERE doc_cat=$id";
        $del = runDBQry($dbh, $sql);
    }
}
header("Location: index.php");
