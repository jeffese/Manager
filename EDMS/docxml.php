<?php

require_once("../scripts/init.php");

$url_var = _xget("id");
$url_var = $url_var == "" ? 1 : $url_var;

$sql = "SELECT `category_id`, `category_name`, 0 AS `typ` FROM `{$_SESSION['DBCoy']}`.`classifications` 
            WHERE `parent_id` = '$url_var' AND catype=1 AND category_id<>'1' 
            ORDER BY `category_id`";
$Tcat = getDBData($dbh, $sql);

if (count($Tcat) == 0) {
$sql = "SELECT `doc_id` AS `category_id`, `docname` AS `category_name`, `doc_id` AS `typ` 
        FROM `{$_SESSION['DBCoy']}`.`edms` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications`        ON `edms`.dept=`classifications`.catID 
        WHERE `approved`=1 AND `classifications`.`category_id`='$url_var'";
$Tcat = getDBData($dbh, $sql);
}

header("Content-type:text/xml");
print("<?xml version='1.0' encoding='ISO-8859-15'?>");
print("<tree id='$url_var'>");
foreach ($Tcat as $row_Tcat) {
    $catname = htmlspecialchars($row_Tcat["category_name"]);
    print('<item child="1" id="' . $row_Tcat["category_id"] . '" text="' . $catname . '"><userdata name="typ">' . $row_Tcat["typ"] . '</userdata></item>');
}
print("</tree>");
