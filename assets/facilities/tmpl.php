<?php
$vtype = 3;
$vpth = "facilities";
$vkey = "Facilities";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("../tmpl/{$match[1]}");
?>
