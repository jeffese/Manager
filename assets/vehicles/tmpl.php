<?php
$vtype = 2;
$vpth = "vehicles";
$vkey = "Vehicles";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("../tmpl/{$match[1]}");
?>
