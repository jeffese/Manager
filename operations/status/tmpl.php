<?php
$vmod = "Operations";
$vkey = "Service Schedule";
$vcat = "srv_schd_status";
$vCatName = "Status";
$vpth = "../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>