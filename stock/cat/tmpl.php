<?php
$vmod = "Stock";
$vkey = "Categories";
$vnm = "Category";
$vtype = 2;
$vcat = "prodcat";
$vpth = "../..";
$_pth= "/stock/cat";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/classifications/{$match[1]}");
?>