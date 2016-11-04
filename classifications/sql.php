<?php

function findRec() {
    $pst = "";
    $ct = 0;
    if (isset($_POST['category_name'])) {
        $catval = GSQLStr(_xpost('category_name'), 'textv');
        $namval = GSQLStr(_xpost('catname'), 'textv');
        $codval = GSQLStr(_xpost('code'), 'textv');
        $parval = GSQLStr(_xpost('parent_id'), 'textv');
        $typval = GSQLStr(_xpost('cat_tag'), 'int');
        $bool = " AND ";

        $pst = $catval . $namval . $codval . $parval;
        $ct = $typval;
    }

    $qryval = '';
    $qrysel = '';
    if ($pst != "" || $ct != 0) {
        $qryval .= $typval == 0 ? "" : " `classifications`.cat_tag = $typval";
        $qryval .= ( ($parval == "" || $qryval == "") ? "" : $bool) . ($parval == "" ? "" : " `classifications`.parent_id = '$parval' OR `classifications`.parent_id LIKE '{$parval}-%'");
        $qryval .= ( ($catval == "" || $qryval == "") ? "" : $bool) . ($catval == "" ? "" : " (`classifications`.category_name LIKE '%{$catval}%')");
        $qryval .= ( ($namval == "" || $qryval == "") ? "" : $bool) . ($namval == "" ? "" : " (`classifications`.catname LIKE '%{$namval}%')");
        $qryval .= ( ($codval == "" || $qryval == "") ? "" : $bool) . ($codval == "" ? "" : " (`classifications`.code LIKE '%{$codval}%')");
    }
    $qryval = $qryval == '' ? '' : ' AND (' . $qryval . ')';
    return $qryval;
}

function getCatid($tab, $par = NULL, $oldpar = NULL, $catid = NULL) {
    global $dbh;
    if ($par == NULL) {
        $par = GSQLStr(_xpost('parent_id'), 'textv');
        $oldpar = GSQLStr(_xpost('old_par'), 'textv');
        $catid = GSQLStr(_xpost('category_id'), 'textv');
    }
    if ($par == $oldpar) {
        return $catid;
    }
    $ext = 0;
    do {
        $ext++;
        $catid = "{$par}-$ext";
        $cnt = getDBDatacnt($dbh, "FROM $tab WHERE category_id='$catid'");
    } while ($cnt > 0);
    return $catid;
}

?>