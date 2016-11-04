<?php
$vmod = "Administration";
$vkey = "Departments";
$vnm = "Department";
$vtype = 1;
$vcat = "dept";
$vcode = "";
$vpth = "../..";
$whr = "`account`=2 AND Dept";
$dr = isset($dr) ? $dr : "classifications";
$_pth= "/admin/departments";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/$dr/{$match[1]}");
?>