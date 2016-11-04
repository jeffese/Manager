<?php
$vmod = "Assets";
$vkey = "Equipment";
$vcat = "AssStatus";
$vCatName = "Status";
$vpth = "../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>