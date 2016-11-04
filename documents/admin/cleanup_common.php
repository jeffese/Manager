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
?>
<?php include $relative_script_path.'/libs/htmlheader.php' ?>
<head>
<title>PhpDig : Cleanup common words</title>
<?php include $relative_script_path.'/libs/htmlmetas.php' ?>
</head>
<body bgcolor="white">
<h2><?php phpdigPrnMsg('cleanupcommon'); ?></h2>
<?php
$locks = phpdigMySelect($id_connect,'SELECT locked FROM '.PHPDIG_DB_PREFIX.'sites WHERE locked = 1');
if (is_array($locks)) {
    phpdigPrnMsg('onelock');
}
else {
mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=1',$id_connect);
//set the max time to an hour
set_time_limit(3600);
$numtot = 0;
$common_words = phpdigComWords("$relative_script_path/includes/common_words.txt");
while (list($common) = each($common_words))
       {
       //list of common words in the keywords table
       $common = addslashes(str_replace('\\','',$common));
       $query = "select key_id from ".PHPDIG_DB_PREFIX."keywords where keyword like '$common'";
       $res = mysql_query($query,$id_connect);
       if ($res)
       {
       while (list($key_id) = mysql_fetch_row($res))
              {
              //delete references to this keyword in the engine table
              $query = "DELETE FROM ".PHPDIG_DB_PREFIX."engine WHERE key_id=$key_id";
              mysql_query($query,$id_connect);
              $numdel = mysql_affected_rows($id_connect);
              print "$numdel".phpdigMsg('deletedfor')." ".stripslashes($common)." ($key_id)<br />";
              $numtot += $numdel;
              }
       //delete this common word from the keywords table
       $query = "DELETE from ".PHPDIG_DB_PREFIX."keywords where keyword like '$common'";
       }
       mysql_query($query,$id_connect);
       }
print "<h3>".phpdigMsg('cleanuptotal')."$numtot".phpdigMsg('cleaned')."</h3>";
mysql_query('UPDATE '.PHPDIG_DB_PREFIX.'sites SET locked=0',$id_connect);
}
?>
<br /><br />
<a href="index.php" target="arch_win">[<?php phpdigPrnMsg('back'); ?>]</a> <?php phpdigPrnMsg('to_admin'); ?>.
<br /><br />
<a href='http://www.phpdig.net/' target='_blank'><img src='../phpdig_powered_2.png' width='88' height='28' border='0' alt='Powered by PhpDig' /></a>
</body>
</html>