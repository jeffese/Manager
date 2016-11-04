<?php
require_once('init.php');
$params = explode('?', _xget('url'));
$url = $params[0] . set_QS("xcel=1", count($params) == 2 ? $params[1] : '0');

header("Location: $url");

