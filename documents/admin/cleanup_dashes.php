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

$count = 0;
$relative_script_path = '..';
$no_connect = 0;

require_once('../../scripts/init.php');
include "$relative_script_path/includes/config.php";
include "$relative_script_path/libs/auth.php";
include "$relative_script_path/admin/robot_functions.php";
?>
<?php include $relative_script_path.'/libs/htmlheader.php' ?>
<head>
<title>PhpDig : Cleanup dashes</title>
<?php include $relative_script_path.'/libs/htmlmetas.php' ?>
</head>
<body bgcolor="white">
<h2><?php phpdigPrnMsg('Cleanup dashes'); ?></h2>
<?php
$locks = phpdigMySelect($id_connect,'SELECT locked FROM '.PHPDIG_DB_PREFIX.'sites WHERE locked = 1');
if (is_array($locks)) {
    phpdigPrnMsg('onelock');
}
else {
mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=1',$id_connect);
$query = mysql_query("SELECT spider_id FROM ".PHPDIG_DB_PREFIX."spider WHERE file = '';");
while ($row = mysql_fetch_array($query)) {
  mysql_query("DELETE FROM ".PHPDIG_DB_PREFIX."engine WHERE spider_id=".$row['spider_id'].";");
  mysql_query("DELETE FROM ".PHPDIG_DB_PREFIX."spider WHERE spider_id=".$row['spider_id'].";");
  phpdigDelText($relative_script_path,$row['spider_id']);
  $count++;
  echo $count . " ";
}
echo phpdigMsg('done');
mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=0',$id_connect);
}
?>
<br /><br />
<a href="index.php" target="arch_win">[<?php phpdigPrnMsg('back'); ?>]</a> <?php phpdigPrnMsg('to_admin'); ?>.
<br /><br />
<a href='http://www.phpdig.net/' target='_blank'><img src='../phpdig_powered_2.png' width='88' height='28' border='0' alt='Powered by PhpDig' /></a>
</body>
</html>