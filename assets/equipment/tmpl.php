<?php
$vtype = 1;
$vpth = "equipment";
$vkey = "Equipment";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("../tmpl/{$match[1]}");
?>
