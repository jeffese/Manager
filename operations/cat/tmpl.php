<?php
$vmod = "Operations";
$vkey = "Services";
$vnm = "Category";
$vtype = 3;
$vcat = "srvcat";
$vpth = "../..";
$_pth= "/operations/cat";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/classifications/{$match[1]}");
?>