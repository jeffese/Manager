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

//===============================================
// form for the search query.
// $query_string is the previous query if exists
// $option is search option
// $limite is the num results per page
// $result_page is path to the search.php script
// $site is the site to limit the results
// $path as the same purpose
function phpdigMakeForm($query_string="",$option="start",$limite=10,$result_page="search.php",$site="",$path="",$mode='classic',$template_demo="",$num_tot=0,$refine=0)
{
// $result_page is SEARCH_PAGE from config.php file
// $mode is 'template' from search_function.php file
$result = array();

if (!isset($option))
     $option = 'start';
settype($limite,'integer');
if ($limite == 0)
     $limite = 10;

$check_start = array('start' => 'checked="checked"' , 'any' => '', 'exact' => '');
$check_any = array('start' => '' , 'any' => 'checked="checked"', 'exact' => '');
$check_exact = array('start' => '' , 'any' => '', 'exact' => 'checked="checked"');

$limit10 = array(10 => 'selected="selected"', 30=> '', 100=> '');
$limit30 = array(10 => '', 30=> 'selected="selected"', 100=> '');
$limit100 = array(10 => '', 30=> '', 100=> 'selected="selected"');

$limitopt_flag = 0;
if (!in_array(SEARCH_DEFAULT_LIMIT,array(10,30,100))) {
    $limitopt_flag = 1;
    $limit10[SEARCH_DEFAULT_LIMIT] = "";
    $limit30[SEARCH_DEFAULT_LIMIT] = "";
    $limit100[SEARCH_DEFAULT_LIMIT] = "";
    $limitdef = array(10 => '', 30=> '', 100=> '', SEARCH_DEFAULT_LIMIT=> 'selected="selected"');
    $limitoptdef = "<option ".$limitdef[$limite].">".SEARCH_DEFAULT_LIMIT."</option>";
}

$limitopt10 = "<option ".$limit10[$limite].">10</option>";
$limitopt30 = "<option ".$limit30[$limite].">30</option>";
$limitopt100 = "<option ".$limit100[$limite].">100</option>";

$limitselectopts = array(10=>$limitopt10, 30=>$limitopt30, 100=>$limitopt100);

if ($limitopt_flag == 1) {
    $limitselectopts[SEARCH_DEFAULT_LIMIT] = $limitoptdef;
    ksort($limitselectopts);
}

$selectoptlist = "";
foreach($limitselectopts as $selectopt) {
    $selectoptlist .= $selectopt;
}

$query_string2 = urlencode(stripslashes($query_string));

if (defined('LIST_LINKS')) {
$result['templates_links'] = "
<b>".phpdigMsg('choose_temp')."</b> : 
<a href='".LIST_PAGE."?template_demo=grey.html&amp;".LIST_ACTION."&amp;page=".LIST_PAGES."'><u>grey.html</u></a> 
<a href='".LIST_PAGE."?template_demo=yellow.html&amp;".LIST_ACTION."&amp;page=".LIST_PAGES."'><u>yellow.html</u></a> 
<a href='".LIST_PAGE."?template_demo=newspaper.html&amp;".LIST_ACTION."&amp;page=".LIST_PAGES."'><u>newspaper.html</u></a> 
<a href='".LIST_PAGE."?template_demo=corporate.html&amp;".LIST_ACTION."&amp;page=".LIST_PAGES."'><u>corporate.html</u></a> 
";
}
else {
$result['templates_links'] = "
<b>".phpdigMsg('choose_temp')."</b> : 
<a href='".SEARCH_PAGE."?template_demo=phpdig.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>phpdig.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=black.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>black.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=simple.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>simple.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=green.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>green.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=grey.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>grey.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=yellow.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>yellow.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=bluegrey.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>bluegrey.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=terminal.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>terminal.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=linear.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>linear.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=lightgreen.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>lightgreen.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=newspaper.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>newspaper.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=corporate.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>corporate.html</u></a> 
<a href='".SEARCH_PAGE."?template_demo=gaagle.html&amp;result_page=".SEARCH_PAGE."&amp;browse=1&amp;query_string=$query_string2&amp;limite=$limite&amp;option=$option'><u>gaagle.html</u></a> 
";
}

if (DISPLAY_DROPDOWN) {
  $dropdown_flag = 0;
  $relative_script_path = '.';
  
    if (is_file("$relative_script_path/includes/connect.php")) {
        include "$relative_script_path/includes/connect.php";
    }
    else {
        die ("Unable to find connect.php file for dropdown menu.\n");
    }

  if (($num_tot == 0) || (empty($site) && empty($path))) {
     $dropdown_flag = 1;
     $path = "";
  }
  else {
    if (isset($site) && is_numeric($site) && ($site > 0)) {
        $site = (int) $site;

        if (DROPDOWN_URLS) {
            $dd_query = mysql_query('SELECT DISTINCT '.PHPDIG_DB_PREFIX.'sites.site_url AS '.
            'site_url,'.PHPDIG_DB_PREFIX.'spider.path AS path '.
            'FROM '.PHPDIG_DB_PREFIX.'sites,'.PHPDIG_DB_PREFIX.'spider '.
            'WHERE '.PHPDIG_DB_PREFIX.'sites.site_id = '.$site.' '.
            'AND '.PHPDIG_DB_PREFIX.'sites.site_id = '.PHPDIG_DB_PREFIX.'spider.site_id '.
            'AND '.PHPDIG_DB_PREFIX.'spider.path != ""',$id_connect);
        }
        else {
            $dd_query = mysql_query('SELECT DISTINCT path FROM '.PHPDIG_DB_PREFIX.'spider WHERE site_id = '.$site.' AND path != ""',$id_connect);
        }

        if (@mysql_num_rows($dd_query) > 0) {
            $result['form_head'] = "<form action='$result_page' method='post'>
            <input type='hidden' name='site' value='$site'/>
            <input type='hidden' name='refine' value='$refine'/>
            <input type='hidden' name='template_demo' value='$template_demo'/>
            <input type='hidden' name='result_page' value='$result_page'/>";
            $result['form_dropdown'] = phpdigMsg('narrow_path').": <select name='path'>";
            $result['form_dropdown'] .= "<option value='-###-'>".phpdigMsg('searchall')."</option>"; // value needs to be something unique, not blank
            while ($dd_data = mysql_fetch_array($dd_query)) {
                if ($path == $dd_data['path']) {
                    $result['form_dropdown'] .= "<option value='".$dd_data['path']."' selected>".$dd_data['site_url'].$dd_data['path']."</option>";
                }
                else {
                    $result['form_dropdown'] .= "<option value='".$dd_data['path']."'>".$dd_data['site_url'].$dd_data['path']."</option>";
                }
            }
            $result['form_dropdown'] .= "</select> <a href=\"$result_page\">".phpdigMsg('restart')."</a>";
        }
        else {
          $dropdown_flag = 1;
        }
    }
    else {
      $dropdown_flag = 1;
    }
  }

  if ($dropdown_flag == 1) {

      if (DROPDOWN_URLS) {
          $dd_query = mysql_query('SELECT DISTINCT '.PHPDIG_DB_PREFIX.'sites.site_id AS '.
          'site_id,'.PHPDIG_DB_PREFIX.'sites.site_url AS site_url,'.PHPDIG_DB_PREFIX.'spider.path AS path '.
          'FROM '.PHPDIG_DB_PREFIX.'sites,'.PHPDIG_DB_PREFIX.'spider '.
          'WHERE '.PHPDIG_DB_PREFIX.'sites.site_id = '.PHPDIG_DB_PREFIX.'spider.site_id',$id_connect);
      }
      else {
          $dd_query = mysql_query('SELECT site_id,site_url FROM '.PHPDIG_DB_PREFIX.'sites',$id_connect);
      }

      $result['form_head'] = "<form action='$result_page' method='post'>
      <input type='hidden' name='path' value='".htmlspecialchars($path,ENT_QUOTES)."'/>
      <input type='hidden' name='refine' value='$refine'/>
      <input type='hidden' name='template_demo' value='$template_demo'/>
      <input type='hidden' name='result_page' value='$result_page'/>";
      $result['form_dropdown'] = phpdigMsg('select_site').": <select name='site'>";
      $result['form_dropdown'] .= "<option value=''>".phpdigMsg('searchall')."</option>";
      while ($dd_data = mysql_fetch_array($dd_query)) {
          $result['form_dropdown'] .= "<option value='".$dd_data['site_id'].",".$dd_data['path']."'>".$dd_data['site_url'].$dd_data['path']."</option>";
      }
      $result['form_dropdown'] .= "</select> <a href=\"$result_page\">".phpdigMsg('restart')."</a>";
  }
}
else {
  $result['form_dropdown'] = '';
  $result['form_head'] = "<form action='$result_page' method='post'>
  <input type='hidden' name='site' value='$site'/>
  <input type='hidden' name='path' value='".htmlspecialchars($path,ENT_QUOTES)."'/>
  <input type='hidden' name='template_demo' value='$template_demo'/>
  <input type='hidden' name='result_page' value='$result_page'/>
  ";
}

$result['form_foot'] = "</form>";
$result['form_title'] = phpdigMsg('search');
$result['form_field'] = "<input type='text' class='phpdiginputtext' size='".SEARCH_BOX_SIZE."' maxlength='".SEARCH_BOX_MAXLENGTH."' name='query_string' value='".htmlspecialchars(stripslashes($query_string),ENT_QUOTES)."'/>";
$result['form_select'] = phpdigMsg('display')."
  <select name='limite' class='phpdigselect'>
  $selectoptlist
  </select>
  ".phpdigMsg('results')."
 ";
$result['form_button'] = "<input type='submit' class='phpdiginputsubmit' name='search' value='".phpdigMsg('go')."'/>";
$result['form_radio'] = "<input type=\"radio\" class='phpdiginputradio' name=\"option\" value=\"start\" ".$check_start[$option]."/>".phpdigMsg('w_begin')."&nbsp;
 <input type=\"radio\" class='phpdiginputradio' name=\"option\" value=\"exact\" ".$check_exact[$option]."/>".phpdigMsg('w_whole')."&nbsp;
 <input type=\"radio\" class='phpdiginputradio' name=\"option\" value=\"any\" ".$check_any[$option]."/>".phpdigMsg('w_part')."&nbsp;
 ";
if ($mode == 'classic')
{
extract($result);
?>
<?php print $form_head ?>
<table class="borderCollapse">
 <tr>
  <td class="blueForm">
  <?php print $form_title ?>
  </td>
 </tr>
 <tr>
  <td class="greyForm">
  <?php print $form_field ?>
  <?php print $form_button ?>
  <?php print $form_select ?>
  </td>
 </tr>
 <tr>
 <td class="greyForm">
 <?php print $form_radio ?>
 </td>
 </tr>
</table>
</form>
<?php
}
else
return $result;
}

//===============================================
//parse a phpdig template
function phpdigParseTemplate($template,$t_strings,$table_results)
{
if (!is_file($template)) {
     print "No template file found !";
     return 0;
}

$in_loop = 0;
$f_handler = fopen($template,'r');
while ($line = fgets($f_handler,4096)) {
       if (eregi('(.*)<phpdig:results>(.*)',$line,$regs)) {
           $i = 0;
           $line .= $regs[1];
           $loop_part[$i++] = $regs[2];
           $in_loop = 1;
           $first_line = 1;
       }
       if ($in_loop == 1) {
           if (eregi('(.*)</phpdig:results>(.*)',$line,$regs)) {
               $loop_part[$i++] = $regs[1];
               $line = $regs[2];
               $in_loop = 0;
               //parse the loop

               if (is_array($table_results) && is_array($loop_part)) {
                   foreach ($table_results as $id => $result) {
                          $result['n'] = $id;
                          foreach ($loop_part as $i => $this_loop) {
                              print phpdigParseTags($this_loop,$result);
                          }
                    }
               }
           }
           else if ($first_line == 1) {
               $first_line = 0;
           }
           else {
               $loop_part[$i++] = $line;
           }
       }

       if ($in_loop == 0) {
           print phpdigParseTags($line,$t_strings);
       }
}
}

//replace <phpdig:/> tags by adequate value in a string
function  phpdigParseTags($line,$t_strings)
{
while(ereg('<phpdig:',$line) && ereg('<phpdig:([a-zA-Z0-9_]+)([[:blank:]]+src=["\']?([a-zA-Z0-9./_-]+)["\']?)?/>',$line,$regs)) {
         if (!isset($t_strings[$regs[1]])) {
            $t_strings[$regs[1]] = '';
         }
         //links with images
         if ($regs[2]) {
             if ($regs[3] && $t_strings[$regs[1]]) {
                 if (ereg('^http',$t_strings[$regs[1]])) {
                     $target = ' target="_blank"';
                 }
                 else {
                     $target = '';
                 }
                 $replacement = '<a href="'.$t_strings[$regs[1]].'"'.$target.'><img src="'.$regs[3].'" border="0" align="bottom" alt="" /></a>';
             }
             else {
                 $replacement = '';
             }
             $line = str_replace($regs[0],$replacement,$line);
         }
         else {
             $line = str_replace($regs[0],$t_strings[$regs[1]],$line);
         }
}
return $line;
}
?>
