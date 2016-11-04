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

// basic cookie logout

$relative_script_path = '..';
$no_connect = 0;
require_once('../../scripts/init.php');
include "$relative_script_path/includes/config.php";
include "$relative_script_path/libs/auth.php";

if (isset($_COOKIE['phpdigadmin'])) {
    setcookie("phpdigadmin", "", time()-3600, "/");
}
$relative_script_path = '.';
header("Location: $relative_script_path/index.php");
exit();

?>