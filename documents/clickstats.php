<?php
/*
----------------------------------------------------------------------------------
PhpDig Version 1.8.x - See the config file for the full version number.
This program is provided WITHOUT warranty under the GNU/GPL license.
See the LICENSE file for more information about the GNU/GPL license.
Contributors are listed in the CREDITS and CHANGELOG files in this package.
Developer from inception to and including PhpDig v.1.6.2: Antoine Bajolet
Developer from PhpDig v.1.6.3 to and including current version: Charter
Copyright (C) 2001 - 2003, Antoine Bajolet, http://www.toiletoine.net/
Copyright (C) 2003 - current, Charter, http://www.phpdig.net/
Contributors hold Copyright (C) to their code submissions.
Do NOT edit or remove this copyright or licence information upon redistribution.
If you modify code and redistribute, you may ADD your copyright to this notice.
----------------------------------------------------------------------------------
*/

require_once('../scripts/init.php');
$relative_script_path = '.';
$no_connect = 0;

if (is_file("$relative_script_path/includes/config.php")) {
    include "$relative_script_path/includes/config.php";
}
else {
    die("Cannot find config.php file.\n");
}

if (LOG_CLICKS != true) { exit(); }

if (isset($_GET['num'])) {
	$num = $_GET['num'];
}
else {
	$num = 0;
}
if (isset($_GET['url'])) {
	$url = $_GET['url'];
}
else {
	$url = "";
}
if (isset($_GET['val'])) {
	$val = $_GET['val'];
}
else {
	$val = "";
}

settype($num, "integer");
settype($url, "string");
settype($val, "string");

phpdigClickLog($id_connect,$num,$url,$val);

function phpdigClickLog($id_connect,$num=0,$url='',$val='') {

  if ($num > 0 && $url != '' && $val != '') {
    $num = (int) $num;
    $url = addslashes(str_replace("\\","",stripslashes(urldecode($url))));
    $val = addslashes(str_replace("\\","",stripslashes(urldecode($val))));

    $query = "INSERT INTO ".PHPDIG_DB_PREFIX."clicks (c_num,c_url,c_val,c_time) VALUES ($num,'".$url."','".$val."',NOW())";
    @mysql_query($query,$id_connect);
  }

}

?>