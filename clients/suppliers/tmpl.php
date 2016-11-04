<?php
$vtype = 2;
$vpth = "suppliers";
$vkey = "Suppliers";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("../tmpl/{$match[1]}");
?>
