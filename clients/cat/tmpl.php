<?php
$vmod = "Clients";
$vkey = "Categories";
$vnm = "Category";
$vtype = 6;
$vcat = "clientcat";
$vpth = "../..";
$_pth= "/clients/cat";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/classifications/{$match[1]}");
?>