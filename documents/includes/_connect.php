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

// Connection configuration
if (!defined('PHPDIG_DB_NAME')) { // do not change this line

    define('PHPDIG_DB_PREFIX','<dbprefix>');
    define('PHPDIG_DB_HOST','<host>');
    define('PHPDIG_DB_USER','<user>');
    define('PHPDIG_DB_PASS','<pass>');
    define('PHPDIG_DB_NAME','<database>');

} // do not change this line

//connection to the MySql server
$id_connect = @mysql_connect(PHPDIG_DB_HOST,PHPDIG_DB_USER,PHPDIG_DB_PASS);
if (!$id_connect) {
    die("Unable to connect to database : Check the connection script.\n");
}

//Select DataBase
$db_select = @mysql_select_db(PHPDIG_DB_NAME,$id_connect);
if (!$db_select) {
    die("Unable to select the database : Check the connection script.\n");
}
?>
