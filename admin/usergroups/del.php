<?php

require_once("../../scripts/init.php");
vetAccess('Administration', 'Usergroups', 'Del');

$id = _xget('id');
if ($id != "Admin" && $id != "NoAccess" && $id != "PowerGroup") {
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`usergroups` WHERE usergroup=".GSQLStr($id, 'text');
    runDBQry($dbh, $sql);
}
header("Location: index.php");
?>                       