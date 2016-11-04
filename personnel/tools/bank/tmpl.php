<?php
$vmod = "Personnel";
$vkey = "Employees";
$vcat = "bank";
$vCatName = "Bank";
$vpth = "../../..";
$vCode = "";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>