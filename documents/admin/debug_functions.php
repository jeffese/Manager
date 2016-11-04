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

function phpdigDspTable($datas)
{
if(!is_array($datas))
    $datas[0] = 1;
else
    {
    list($id_one) = each($datas);
    reset($datas);
    }
if(!is_array($datas[$id_one]))
    {
    $id = 0;
    while (list($index,$value) = each($datas))
           {
           $datacopy[$id]['index'] = $index;
           $datacopy[$id]['value'] = $value;
           $id++;
           }
    $datas = $datacopy;
    }

    $rows = count($datas);
    $columns = count($datas[$id_one]);
    print "$rows rows & $columns columns<br />";
    print "<table border='1' cellspacing='0' cellpadding='3'>\n";
    print "\t<tr>\n";
    while(list($index) = each($datas[$id_one]))
          {
          print "\t\t<td style='font-weight:bold; background-color:#CCCCCC'>$index</td>\n";
          }
    print "\t</tr>\n";
    reset($datas);
    while(list($index) = each($datas))
           {
           print "\t<tr>\n";
           reset($datas[$index]);
           while(list($useless,$value) = each($datas[$index]))
                 {
                 print "\t\t<td>$value</td>\n";
                 }
           print "\t<tr>\n";
           }
    print "</table>\n";

}
?>