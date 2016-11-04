<?php
$vmod = "Accounts";
$vkey = "Categories";
$vnm = "Category";
$vtype = 7;
$vcat = "acccat";
$vpth = "../..";
$_pth= "/accounts/cat";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/classifications/{$match[1]}");
