<?php

require_once("../scripts/init.php");
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'EDMS'));
$access = _xvar_arr_sub($_access, array('Documents'));
vetAccess('EDMS', 'Documents', 'del');
$id = intval(_xget('id'));

$sql = "SELECT `doc_id` FROM `{$_SESSION['DBCoy']}`.`edms` WHERE `maindoc`=$id";
$Dels = getDBData($dbh, $sql);

foreach ($Dels as $dl) {
    delDoc($dl['doc_id']);
}
delDoc($id);

header("Location: index.php");

function delDoc($id) {
    global $dbh;
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`edms` WHERE `doc_id`=$id";
    if (runDBQry($dbh, $sql) > 0) {
        rmdirr(EDMS_DIR . $_SESSION['coyid'] . DS . $id . DS);
    }
}
