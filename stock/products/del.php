<?php
require_once("../../scripts/init.php");
vetAccess('Stock', 'Products', 'Del');
$id = intval(_xget('id'));

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`items` WHERE ItemID={$id}";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
    delDocs('Stock', 'Products', $id);
    $dirname = ROOT . PRODPIX_DIR . $_SESSION['coyid'] . DS . $id;
    if (file_exists($dirname))
        rmdirr($dirname);
}

header("Location: index.php");
?>
