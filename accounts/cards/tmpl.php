<?php
$vmod = "Accounts";
$vkey = "Expenses";
$vcat = "card";
$vCatName = "POS Cards";
$vCode = "";
$vPar = "Cash Account";
$partab = "vendors";
$parid = "VendorID";
$parname = "CompanyName";
$parWhere = "WHERE `VendorType`=4 AND `ClientType`=2";
$parEdit = 1;
$vpth = "../..";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("$vpth/cats/{$match[1]}");
?>