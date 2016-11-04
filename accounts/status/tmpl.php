<?php
$vmod = "Accounts";
$vkey = "Categories";
$vcat = "AccStatus";
$vCatName = "Status";
$vpth = "../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>
