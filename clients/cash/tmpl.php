<?php
$vtype = 4;
$vpth = "cashacc";
$vkey = "Cash Accounts";
preg_match('/(\w+\.php$)/', $_SERVER['PHP_SELF'], $match);
require_once("../tmpl/{$match[1]}");
?>
