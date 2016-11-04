<?php
$vmod = "Personnel";
$vkey = "Employees";
$vnm = "Category";
$vtype = 5;
$vcat = "empcat";
$vpth = "../../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/classifications/{$match[1]}");
?>