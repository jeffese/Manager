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
if ((isset($_SERVER['SCRIPT_FILENAME'])) && (eregi("custom_search.php",$_SERVER['SCRIPT_FILENAME']))) {
  exit();
}
if ((isset($_SERVER['SCRIPT_URI'])) && (eregi("custom_search.php",$_SERVER['SCRIPT_URI']))) {
  exit();
}
if ((isset($_SERVER['SCRIPT_URL'])) && (eregi("custom_search.php",$_SERVER['SCRIPT_URL']))) {
  exit();
}
if ((isset($_SERVER['REQUEST_URI'])) && (eregi("custom_search.php",$_SERVER['REQUEST_URI']))) {
  exit();
}
if ((isset($_SERVER['SCRIPT_NAME'])) && (eregi("custom_search.php",$_SERVER['SCRIPT_NAME']))) {
  exit();
}
if ((isset($_SERVER['PATH_TRANSLATED'])) && (eregi("custom_search.php",$_SERVER['PATH_TRANSLATED']))) {
  exit();
}
if ((isset($_SERVER['PHP_SELF'])) && (eregi("custom_search.php",$_SERVER['PHP_SELF']))) {
  exit();
}

if (!defined('CONFIG_CHECK')) {
  exit();
}

echo "<html><body>";

echo "<b>YOU MUST EDIT custom_search.php TO GET THE OUTPUT INTO YOUR DESIRED FORMAT.</b><br>";

echo $arrayout['js_for_clicks']."<br>".
     $arrayout['ignore_message']."<br>".
     $arrayout['ignore_commess']."<br>".
     $arrayout['result_message']."<br>".
     $arrayout['powered_by_link']."<br>".
     $arrayout['title_message']."<br>".
     $arrayout['phpdig_version']."<br>".
     $arrayout['nav_bar']."<br>".
     $arrayout['pages_bar']."<br>".
     $arrayout['next_link']."<br>".
     $arrayout['form_head']." ".
     $arrayout['form_title']." ".
     $arrayout['form_field']." ".
     $arrayout['form_select']." ".
     $arrayout['form_button']."<br>".
     $arrayout['form_radio']."<br>".
     $arrayout['form_dropdown']." ".
     $arrayout['form_foot']."<br><br>";

if (!empty($arrayout['results'])) {
    $num_out = count($arrayout['results']);
}
else {
    $num_out = 0;
}

$lim_start = (int) $lim_start;
$num_start = $lim_start + 1;
$num_end = $lim_start + $num_out;

for ($i=$num_start; $i<=$num_end; $i++) {
     echo $i.". ";
     $arrayout2 = $arrayout['results'][$i];
     echo $arrayout2['weight']." ".
          $arrayout2['img_tag']." ".
          $arrayout2['page_link']."<br>".
          $arrayout2['limit_links']."<br>".
          $arrayout2['filesize']." ".
          $arrayout2['update_date']." ".
          $arrayout2['complete_path']."<br>".
          $arrayout2['link_title']."<br>".
          $arrayout2['text']."<br><br>";
}

echo "</body></html>";

?>
