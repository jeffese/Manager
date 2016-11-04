<?php
$vmod = "EDMS";
$vkey = "Documents";
$vnm = "Category";
$vtype = 8;
$vcat = "doccat";
$vpth = "../..";
$_pth= "/EDMS/cat";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/classifications/{$match[1]}");
