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
//executes a select and returns a whole resultset
function phpdigMySelect($id_connect,$query_select)
{
if (!eregi('^[^a-z]*select',$query_select))
     return -1;
$res_id = mysql_query($query_select,$id_connect);
if (!$res_id) {
     // print mysql_error();
     return 0;
}
if (mysql_num_rows($res_id) > 0)
    {
    $result = array();
    while ($res_datas = mysql_fetch_array($res_id,MYSQL_ASSOC))
           {
           array_push($result,$res_datas);
           }
    return $result;
    }
else
    return 0;
}

//===============================================
// verify phpdig_tables
function phpdigCheckTables($id_connect,$tables=array()) {
     $res_id = mysql_query('SHOW TABLES',$id_connect);
     if (!$res_id) {
        die('Unable to check table. Check connection parameters'."\n");
     }
     $num_to_reach = count($tables);
     $num_find = 0;
     foreach ($tables as $id => $table) {
         $tabname[PHPDIG_DB_PREFIX.$table] = 0;
     }
     while ($row = mysql_fetch_row($res_id)) {
         if (isset($tabname[$row[0]])) {
             $tabname[$row[0]] = 1;
             $num_find ++;
         }
     }
     if ($num_find != $num_to_reach) {
         foreach ($tabname as $tablename => $exists) {
             if (!$exists) {
                  print "Table $tablename missing.\n";
             }
         }
         die("\n");
     }
}
?>