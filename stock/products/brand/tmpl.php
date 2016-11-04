<?php
$vmod = "Stock";
$vkey = "Products";
$vcat = "brand";
$vCatName = "Brand";
$vpth = "../../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>