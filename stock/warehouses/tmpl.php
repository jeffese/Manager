<?php
$vmod = "Stock";
$vkey = "Warehouses";
$vnm = "Warehouse";
$vtype = 0;
$vcat = "warehouses";
$vpth = "../..";
$_pth= "/stock/warehouses";
$whr = "outlets.OutletID";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/outlets/{$match[1]}");
?>