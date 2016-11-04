<?php
$vmod = "Assets";
$vkey = "Categories";
$vnm = "Category";
$vtype = 4;
$vcat = "asscat";
$vpth = "../..";
$_pth= "/assets/cat";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/classifications/{$match[1]}");
?>