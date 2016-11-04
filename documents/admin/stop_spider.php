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

$relative_script_path = '..';
$no_connect = 0;
require_once('../../scripts/init.php');
include "$relative_script_path/includes/config.php";
include "$relative_script_path/libs/auth.php";
include "$relative_script_path/admin/robot_functions.php";
?>
<?php include $relative_script_path.'/libs/htmlheader.php' ?>
<head>
<title>PhpDig : Stop Spider</title>
<?php include $relative_script_path.'/libs/htmlmetas.php' ?>
</head>
<body bgcolor="white">
<h2><?php phpdigPrnMsg('StopSpider'); ?></h2>
<?php

// extract vars
extract( phpdigHttpVars(
     array('stop'=>'string')
     ),EXTR_SKIP);

if ($stop) {
       $query = "DELETE FROM ".PHPDIG_DB_PREFIX."tempspider";
       $result_id = mysql_query($query,$id_connect);
       $query = "UPDATE ".PHPDIG_DB_PREFIX."sites SET stopped=1";
       $result_id = mysql_query($query,$id_connect);
       echo phpdigMsg('wait');

       for ($i=0; $i<4; $i++) {
           sleep(5); // do not remove me as i MAY be needed to make a nice stop
           echo phpdigMsg('wait');
           flush();
           @ob_flush();
       }

       $query = "SELECT * FROM ".PHPDIG_DB_PREFIX."tempspider";
       $result_id = mysql_query($query,$id_connect);

       for ($i=0; $i<2; $i++) {
           while (mysql_num_rows($result_id) > 0) {
               $query = "DELETE FROM ".PHPDIG_DB_PREFIX."tempspider";
               $result_id = mysql_query($query,$id_connect);
               sleep(5); // do not remove me as i MAY be needed to make a nice stop
               echo phpdigMsg('wait');
               flush();
               @ob_flush();
               $query = "SELECT * FROM ".PHPDIG_DB_PREFIX."tempspider";
               $result_id = mysql_query($query,$id_connect);
           }
       }

       mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=0 WHERE locked=1',$id_connect);
       $query = "UPDATE ".PHPDIG_DB_PREFIX."sites SET stopped=0";
       $result_id = mysql_query($query,$id_connect);
       echo "<strong>".phpdigMsg('done')."</strong>";
}

?>
<br /><br />
<a href="index.php" target="arch_win">[<?php phpdigPrnMsg('back'); ?>]</a> <?php phpdigPrnMsg('to_admin'); ?>.
<br /><br />
<a href='http://www.phpdig.net/' target='_blank'><img src='../phpdig_powered_2.png' width='88' height='28' border='0' alt='Powered by PhpDig' /></a>
</body>
</html>