<?php
require_once("../../scripts/init.php");

$id = intval(_xget("id"));
$id = $id == 0 ? $vtype : $id;
if ($id < 10) {
    $url_var = "`AssetType`=$id AND (`parent`=0 OR `parent` IS NULL)";
} else {
    $url_var = "`parent`=$id";
}

$sql = "SELECT `AssetID`, `AssetName` FROM `{$_SESSION['DBCoy']}`.`assets` 
            WHERE $url_var
            ORDER BY `AssetName`";
$TAsset = getDBData($dbh, $sql);

header("Content-type:text/xml");
print("<?xml version='1.0' encoding='ISO-8859-15'?>");
print("<tree id='$id'>");
foreach ($TAsset as $row_TAsset) {
    $catname = htmlspecialchars($row_TAsset["AssetName"]);
    print('<item child="1" id="' . $row_TAsset["AssetID"] . '" text="' . $catname . '"></item>');
}
print("</tree>");
?>