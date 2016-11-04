<?php
$vmod = "Personnel";
$vkey = "Employees";
$vcat = "plan";
$vCatName = "Health Plans";
$vpth = "../../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>