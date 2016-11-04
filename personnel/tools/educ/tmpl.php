<?php
$vmod = "Personnel";
$vkey = "Employees";
$vcat = "educ";
$vCatName = "Educational Level";
$vpth = "../../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>