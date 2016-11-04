<?php
$vmod = "Stock";
$vkey = "Products";
$vcat = "shelf";
$vCatName = "Shelves";
$vpth = "../../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>