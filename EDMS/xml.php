<?php

require_once("../scripts/init.php");

$url_var = _xget("id");

header("Content-type:text/xml");
print("<?xml version='1.0' encoding='ISO-8859-15'?>");
print("<tree id='$url_var'>");

if (($ldap_cred = getLDAP($_SESSION['coyid'])) && ($grps = getOUs($ldap_cred))) {
    $lst = array();
    if ($url_var == '1') {
        foreach ($grps as $OU) {
            $arr = array($OU[0], $OU[0]);
            if (count($OU) == 1 && !in_array($arr, $lst)) {
                array_push($lst, $arr);
            }
        }
    } else {
        foreach ($grps as $OU) {
            $o = count($OU) - 1;
            $cat = $OU[$o];
            while (--$o >= 0 && "$url_var > " != substr($cat, 0, strlen($url_var) + 3)) {
                $cat .= ' > ' . $OU[$o];
            }
            if ("$url_var > " == substr($cat, 0, strlen($url_var) + 3)) {
                $arr = array($cat, $OU[$o + 1]);
                if (!in_array($arr, $lst)) {
                    array_push($lst, $arr);
                }
            }
        }
    }
    foreach ($lst as $OU) {
        $catid = htmlspecialchars($OU[0]);
        $catname = htmlspecialchars($OU[1]);
        print('<item child="1" id="' . $catid . '" text="' . $catname . '"></item>');
    }
}
print("</tree>");
