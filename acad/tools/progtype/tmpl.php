<?php
$vmod = "Academics";
$vkey = "Programs";
$vcat = "prgtyp";
$vCatName = "Program Type";
$vpth = "../../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>