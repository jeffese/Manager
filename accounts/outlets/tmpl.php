<?php
$vmod = "Accounts";
$vkey = "Outlets";
$vnm = "Outlet";
$vtype = 1;
$vcat = "outlets";
$vpth = "../..";
$_pth= "/accounts/outlets";
$whr = "outlets.OutletID";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/outlets/{$match[1]}");
?>