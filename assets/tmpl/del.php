<?php
require_once("../../scripts/init.php");

vetAccess('Assets', $vkey, 'Del');
$id = intval(_xget('id'));

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`assets` WHERE AssetID=$id";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
	delDocs('Assets', $vkey, $id);
    $dirname = ROOT . ASSETPIX_DIR . $_SESSION['coyid'] . DS . $id;
    if (file_exists($dirname))
        rmdirr($dirname);
}
header("Location: index.php");
?>
