<?php
$vmod = "Personnel";
$vkey = "Employees";
$vcat = "hosp";
$vCatName = "Hospitals";
$vpth = "../../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>