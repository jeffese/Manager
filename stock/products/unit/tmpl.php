<?php
$vmod = "Stock";
$vkey = "Products";
$vcat = "units";
$vCatName = "Unit Types";
$vpth = "../../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>