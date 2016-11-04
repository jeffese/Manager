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

require_once('../../scripts/init.php');
// prevent caching code from php.net
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

$relative_script_path = '..';
$no_connect = 0;
include "$relative_script_path/includes/config.php";
include "$relative_script_path/libs/auth.php";

// extract vars
extract( phpdigHttpVars(
     array('message'=>'string')
     ),EXTR_SKIP);

?>
<?php include $relative_script_path.'/libs/htmlheader.php' ?>
<head>
<title>PhpDig : <?php phpdigPrnMsg('admin') ?></title>
<?php include $relative_script_path.'/libs/htmlmetas.php' ?>
</head>
<body bgcolor="white">
<div align='center'>
<table border="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align='center'>
      <?php
$phpdig_tables = array('sites'=>'Hosts','spider'=>'Pages','engine'=>'Index','keywords'=>'Keywords','tempspider'=>'Temporary table');
print "<table class=\"borderCollapse\">\n";
print "<tr><td class=\"greyFormDark\" colspan='2' align='center'><b>".phpdigMsg('databasestatus')."</b></td></tr>\n";
while (list($table,$name) = each($phpdig_tables))
       {
       $result = mysql_fetch_array(mysql_query("SELECT count(*) as num FROM ".PHPDIG_DB_PREFIX."$table"),MYSQL_ASSOC);
       print "<tr>\n\t<td class=\"greyFormLight\">\n$name : </td>\n\t<td class=\"greyForm\">\n<b>".$result['num']."</b>".phpdigMsg('entries')."</td>\n</tr>\n";
       }
print "</table>\n";
?>
    </div></td>
    </tr>
  <tr>
<td><div align='center'>
  <form action="update_frame.php" method="post">
    <select class="phpdigSelect" name="site_ids[]" style="width:200px">
      <?php
//list of sites in the database
$query = "SELECT site_id,site_url,port,locked FROM ".PHPDIG_DB_PREFIX."sites ORDER BY site_url";
$result_id = mysql_query($query,$id_connect);
while (list($id,$url,$port,$locked) = mysql_fetch_row($result_id))
    {
    if ($port)
        $url .= " (port #$port)";
    if ($locked) {
        $url = '*'.phpdigMsg('locked').'* '.$url;
    }
    print "\t<option value='$id'>$url</option>\n";
    }
?>
    </select>
    <br/>
    <input type="submit" name="update" value="<?php phpdigPrnMsg('updateform'); ?>" />
  </form>
</div></td>
  </tr>
<tr><td valign="top"><form class="grey" action="spider.php" method="post">
  <div align="center">
  <input name="url" type="hidden" value="/documents/archive/">
  <br/>
    Use values from Update sites table if present and   use<BR>
    default values if values absent from table?<br/>
    <input type="radio" name="usetable" value="yes"> 
    <?php phpdigPrnMsg('yes'); ?> 
   <input type="radio" name="usetable" value="no" checked> 
    <?php phpdigPrnMsg('no'); ?>
   <input name="limit" type="hidden" value="1">
    <input name="linksper" type="hidden" value="0">
    <br/>
   <input type="submit" name="spider" value="Index Archive" />
  </div>
</form>
    <p align="left" class='grey'><a href="cleanup_engine.php"><?php print phpdigMsg('clean')." ".phpdigMsg('t_index'); ?></a><br/>
        <a href="cleanup_keywords.php"><?php print phpdigMsg('clean')." ".phpdigMsg('t_dic'); ?></a><br/>
        <a href="cleanup_common.php"><?php print phpdigMsg('clean')." ".phpdigMsg('t_stopw'); ?></a><br/>
        <a href="cleanup_dashes.php"><?php print phpdigMsg('clean')." ".phpdigMsg('t_dash'); ?></a><br/>
        <a href="statistics.php"><?php print phpdigMsg('statistics') ?></a><br/>
        <a href="stop_spider.php?stop=1"><?php print phpdigMsg('StopSpider') ?></a><br/>
    </p></td>
</tr>
</table>
</div>
</body>
</html>