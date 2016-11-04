<?php
$vmod = "Academics";
$vkey = "Courses";
$vcat = "courscat";
$vCatName = "Category";
$vpth = "../../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>