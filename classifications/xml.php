<?php

require_once("$vpth/scripts/init.php");

$url_var = _xget("id");
$url_var = $url_var == "" ? $vtype : $url_var;

$sql = "SELECT `catID`, `category_id`, `category_name` FROM `{$_SESSION['DBCoy']}`.`classifications` 
            WHERE `parent_id` = '$url_var' AND catype=$vtype AND category_id<>'$vtype' 
            ORDER BY `category_id`";
$Tcat = getDBData($dbh, $sql);

header("Content-type:text/xml");
print("<?xml version='1.0' encoding='ISO-8859-15'?>");
print("<tree id='$url_var'>");
foreach ($Tcat as $row_Tcat) {
    $catname = htmlspecialchars($row_Tcat["category_name"]);
    print('<item child="1" id="' . $row_Tcat["category_id"] . '" text="' . $catname . '"><userdata name="catid">' . $row_Tcat["catID"] . '</userdata></item>');
}
print("</tree>");
