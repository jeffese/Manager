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

if (LIST_ENABLE != true) { exit(); }

$searchpage = SEARCH_PAGE;
$listpage = LIST_PAGE;
$newwindow = LIST_NEW_WINDOW;
$showzeros = LIST_SHOW_ZEROS;
$listlimit = LIST_DEFAULT_LIMIT;
$listmeta = LIST_META_TAG;
$copyright = "<a href=\"http://www.phpdig.net/\">".phpdigMsg('powered_by')."</a>";
// $template set in config.php file
// $template_demo set in config.php file

if (!empty($template_demo)) {
	$listplate = "template_demo=$template_demo";
}
else {
	$listplate = "";
}

if (isset($_GET['action'])) {
	$action = $_GET['action'];
}
else {
	$action = "";
}

if (($action != "") && ($action != "total") && ($action != "query") && 
	($action != "mode") && ($action != "links") && ($action != "time")) {
	$action = "";
}

if ($action == "total") {
	$action = "numsearch desc";
	$listing = "action=total";
}
elseif ($action == "query") {
	$action = "inword asc, exword asc";
	$listing = "action=query";
}
elseif ($action == "mode") {
	$action = "nummode desc, wordtime desc";
	$listing = "action=mode";
}
elseif ($action == "links") {
	$action = "numfound desc";
	$listing = "action=links";
}
elseif ($action == "time") {
	$action = "wordtime desc";
	$listing = "action=time";
}
else {
	$action = "wordtime desc";
	$listing = "action=time";
}

if (($listlimit < 10) || (($listlimit % 10) != 0)) {
	$listlimit = 10;
}

$listlimit = intval($listlimit);

if (isset($_GET['page'])) {
	$page = $_GET['page'];
}
else {
	$page = $listlimit;
}

if (($page % $listlimit) != 0) {
	$page = $listlimit;
}

if (($page < $listlimit) || (!is_numeric($page))) {
	$page = $listlimit;
}
else {
	$page = intval($page);
}

$query = "select count(*) as numsearch, l_includes as inword, ".
	"l_excludes as exword, if(l_num > 0,1,0) as numfoo, ".
	"ifnull(sum(l_num)/count(*),0) as numfound, ".
	"l_mode as nummode, max(unix_timestamp(l_ts)) as wordtime ".
	"from ".PHPDIG_DB_PREFIX."logs ".
	"group by inword,exword,nummode,numfoo";

$result = mysql_query($query,$id_connect) or die(mysql_error());
$num_rows = mysql_num_rows($result);
$num_all = 0;
$num_pos = 0;
$num_foo = 0;

while ($data = mysql_fetch_array($result)) {
	$numsearch = $data['numsearch'];
	$numfoo = $data['numfoo'];
	$numfound = round($data['numfound'],0);
	if (($numfound == 0) && ($numfoo == 0)) {
		$num_foo++;
	}
	$num_all+=$numsearch;
	if ($numfound > 0) {
		$num_pos+=$numsearch;
	}
}

$num_neg = $num_all - $num_pos;

if ($showzeros != 1) {
	$num_rows-=$num_foo;
	$where = " where l_num > 0";
}
else {
	$where = "";
}

$pagination = "";
$num_pages = 0;

if ($num_rows > 0) {
	$first_page = $listlimit;
	$num_pages = intval(($num_rows - 1) / $listlimit) + 1;
	$last_page = intval($num_pages * $listlimit);

	if ($page > $last_page) {
		$page = $last_page;
	}

	$stretch = 4 * $listlimit;
	$pagination .= "\n";

	if ($page > $listlimit) {
		$prev_page = max($listlimit,$page - $listlimit);
		$pagination .= " <a href=\"$listpage?$listplate&amp;$listing&amp;page=$first_page\">&lt;&lt;</a> | \n";
		$pagination .= " <a href=\"$listpage?$listplate&amp;$listing&amp;page=$prev_page\">&lt;</a> | \n";
	}

	$i_start = floor(($page - $stretch - 1) / $listlimit) + 2;
	$i_end = floor(($page + $stretch) / $listlimit) + 1;
	$i_start = max($i_start,1);
	$i_end = min($i_end,$num_pages);

	for ($i = $i_start; $i <= $i_end; $i++) {
		$mid_page = max($listlimit,$listlimit * $i);
		if ($mid_page == $page) {
			$pagination .= " <b>$i</b> \n";
		}
		else {
			$pagination .= "<a href=\"$listpage?$listplate&amp;$listing&amp;page=$mid_page\">$i</a> \n";
		}
	}

	if (!(($page / $listlimit) >= $num_pages) && ($num_pages != 1)) {
		$next_page = min($page + $listlimit,$last_page);
		$pagination .= " | <a href=\"$listpage?$listplate&amp;$listing&amp;page=$next_page\">&gt;</a> \n";
		$pagination .= " | <a href=\"$listpage?$listplate&amp;$listing&amp;page=$last_page\">&gt;&gt;</a> \n";
	}
}

if ($num_rows == 0) {
	$first = 0;
}
else {
	$first = $page - $listlimit + 1;
}

if (!(($page / $listlimit) >= $num_pages) && ($num_pages != 1)) {
	$last = $page;
}
else {
	$last = $num_rows;
}

if ($num_pages > 0) {
	$current = intval(($page - $listlimit) / $listlimit) + 1;
}
else {
	$current = 0;
}

$location = phpdigMsg('listing')." <b>$first</b> ".phpdigMsg('to')." <b>$last</b> ".phpdigMsg('of')." <b>$num_rows</b>. ".phpdigMsg('page')." <b>$current</b> ".phpdigMsg('of')." <b>$num_pages</b>.";

$list_action = $listing;
$list_pages = $page;
$page-=$listlimit;
if ($page < 0) {
	$page = 0;
}

$query = "select count(*) as numsearch, l_includes as inword, ".
	"l_excludes as exword, if(l_num > 0,1,0) as numfoo, ".
	"ifnull(sum(l_num)/count(*),0) as numfound, ".
	"l_mode as nummode, max(unix_timestamp(l_ts)) as wordtime ".
	"from ".PHPDIG_DB_PREFIX."logs$where ".
	"group by inword,exword,nummode,numfoo order by $action limit $page,$listlimit";

$result = mysql_query($query,$id_connect);
$content = "";

while ($data = mysql_fetch_array($result)) {
	$numsearch = $data['numsearch'];
	$inword = trim($data['inword']);
	$exword = str_replace(" "," -",trim($data['exword']));
	$nummode = $data['nummode'];
	$wordtime = date("Y-m-d H:i:s",$data['wordtime'] - date("Z"));

	if (strlen($exword) > 0) {
		$allword = $inword." -".$exword;
	}
	else {
		$allword = $inword;
	}

	$query_string = str_replace("\\","",str_replace("\%","",$allword));
	$allword = htmlspecialchars($query_string,ENT_QUOTES);
	$allword = str_replace("&amp;","&",$allword);
	$query_string = urlencode($query_string);
	$numfound = round($data['numfound'],0);

	if ($nummode == "s") {
		$nummode = "and";
		$options = "?query_string=$query_string&amp;option=start";
	}
	elseif ($nummode == "e") {
		$nummode = "exact";
		$options = "?query_string=$query_string&amp;option=exact";
	}
	elseif ($nummode == "a") {
		$nummode = "or";
		$options = "?query_string=$query_string&amp;option=any";
	}
	else {
		$nummode = " ";
		$options = "";
	}

	if ($newwindow == "1") {
		$target = " target=\"_blank\"";
	}
	else {
		$target = "";
	}

	if (strlen(trim($allword)) == 0) {
		$allword = " ";
		$options = "";
	}

	if (($showzeros == 1) || ($numfound > 0)) {
		$content .= "<tr>\n\n";
		$content .= "<td class=\"color_table_cells\">$numsearch</td>\n";
		$content .= "<td class=\"color_table_cells\"><a href=\"$searchpage$options&amp;$listplate\"$target>$allword</a></td>\n";
		$content .= "<td class=\"color_table_cells\">$nummode</td>\n";
		$content .= "<td class=\"color_table_cells\">$numfound</td>\n";
		$content .= "<td class=\"color_table_cells\">$wordtime</td>\n";
		$content .= "\n</tr>";
	}
}

$list_output = "<center>\n<table border=\"0\" cellpadding=\"5\" cellspacing=\"1\" class=\"color_table_outline\">";
$list_output .= "<tr>\n\n<td colspan=\"5\" align=\"left\" class=\"color_sub_top_bottom\">
<b>$num_all</b> ".phpdigMsg('searches').": <b>$num_pos</b> ".phpdigMsg('with_results').", <b>$num_neg</b> ".phpdigMsg('with_no_results').".
</td>\n\n</tr>";
$list_output .= "<tr>\n\n<td nowrap colspan=\"5\" align=\"left\" class=\"color_top_bottom\">".phpdigMsg('list_meanings')."</td>\n\n</tr>";
$list_output .= "<tr>\n\n<td colspan=\"5\" align=\"left\" class=\"color_sub_top_bottom\">$location</td>\n\n</tr>";
$list_output .= "<tr>\n\n<td class=\"color_table_cells\"><b><a href=\"$listpage?$listplate&amp;action=total&amp;page=$listlimit\">".ucfirst(phpdigMsg('total'))."</a></b></td>\n";
$list_output .= "<td class=\"color_table_cells\"><b><a href=\"$listpage?$listplate&amp;action=query&amp;page=$listlimit\">".ucfirst(phpdigMsg('query'))."</a></b></td>\n";
$list_output .= "<td class=\"color_table_cells\"><b><a href=\"$listpage?$listplate&amp;action=mode&amp;page=$listlimit\">".ucfirst(phpdigMsg('mode'))."</a></b></td>\n";
$list_output .= "<td class=\"color_table_cells\"><b><a href=\"$listpage?$listplate&amp;action=links&amp;page=$listlimit\">".ucfirst(phpdigMsg('Links'))."</a></b></td>\n";
$list_output .= "<td class=\"color_table_cells\"><b><a href=\"$listpage?$listplate&amp;action=time&amp;page=$listlimit\">".ucfirst(phpdigMsg('time'))."</a></b></td>\n";
$list_output .= "\n</tr>";
$list_output .= $content;
$list_output .= "<tr>\n\n<td colspan=\"5\" align=\"left\" class=\"color_sub_top_bottom\">$pagination</td>\n\n</tr>";
$list_output .= "<tr>\n\n<td colspan=\"5\" align=\"center\" class=\"color_top_bottom\">$copyright</td>\n\n</tr>";
$list_output .= "</table>\n</center>\n";

if (is_file($template)) {
    define('LIST_LINKS',true);
    define('LIST_ACTION',$list_action);
    define('LIST_PAGES',$list_pages);
    $list_meta = array('listmetatag' => $listmeta);
    $list_title = array('title_message' => 'PhpDig '.PHPDIG_VERSION);
    $list_form = phpdigMakeForm('',SEARCH_DEFAULT_MODE,SEARCH_DEFAULT_LIMIT,SEARCH_PAGE,'','','template',$template_demo,0,0);
    $list_result = array('result_message' => phpdigMsg('no_query').'.');
    $list_table = array('listing' => $list_output);
    $powered_by_link = "<font size=\"1\" face=\"verdana,arial,sans-serif\">";
    $powered_by_link .= "<a href=\"http://www.phpdig.net/\">".phpdigMsg('powered_by')."</a><br></font>";
    $list_power = array('powered_by_link' => $powered_by_link);
    $list_strings = array_merge($list_meta,$list_title,$list_form,$list_result,$list_table,$list_power);
    phpdigParseTemplate($template,$list_strings,'');
}
else {
?>
<?php include $relative_script_path.'/libs/htmlheader.php' ?>
<head>
<title>PhpDig <?php print PHPDIG_VERSION ?></title>
<?php print $listmeta."\n"; ?>
<?php include $relative_script_path.'/libs/htmlmetas.php' ?>
<style type="text/css">
<!--
.color_table_outline {
	background-color: #ccddff;
	color: #000000;
	border: 1px solid #7688a7;
}
.color_top_bottom {
	background-color: #eeeeee;
	color: #000000;
}
.color_sub_top_bottom {
	background-color: #dddddd;
	color: #000000;
}
.color_table_cells {
	background-color: #ffffff;
	color: #000000;
}
-->
</style>
</head>
<body bgcolor="white">
<div align="center">
<img src="phpdig_logo_2.png" width="200" height="114" alt="PhpDig <?php print PHPDIG_VERSION ?>" border="0" />
<br />
<?php
phpdigMakeForm('',SEARCH_DEFAULT_MODE,SEARCH_DEFAULT_LIMIT,SEARCH_PAGE,'','','classic','',0,0);
?>
<h3><span class="phpdigMsg"><?php print phpdigMsg('no_query').'.'; ?></span>
<br />
<br />
</h3>
</div>
<p class="blue"></p>
<?php
echo $list_output;
?>
<br />
<div align='center'>
<a href='http://www.phpdig.net/' target='_blank'><img src='phpdig_powered_2.png' width='88' height='28' border='0' alt='Powered by PhpDig' /></a> &nbsp;
</div>
</body>
</html>
<?php
}

?>