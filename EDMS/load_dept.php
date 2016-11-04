<?php
require_once('../scripts/init.php');
$id = _xget('id');
$sql = "SELECT catname FROM `{$_SESSION['DBCoy']}`.`classifications` WHERE catID=$id";
$dept = getDBDataRow($dbh, $sql);
echo _xvar_arr($dept, 'catname');
