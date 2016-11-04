<?php

function findRec() {
    $pst = "";
    $ct = 0;
    if (isset($_POST['name'])) {
        $vndval = GSQLStr(_xpost('VendorID'), 'int');
        $namval = GSQLStr(_xpost('name'), 'textv');
        $gndval = GSQLStr(_xpost('sex'), 'int');
        $natval = GSQLStr(_xpost('nationality'), 'int');
        $relval = GSQLStr(_xpost('religion'), 'int');
        $pstval = GSQLStr(_xpost('passportno'), 'textv');
        $typval = GSQLStr(_xpost('ClientType'), 'int');
        $addval = GSQLStr(_xpost('City'), 'textv');
        $notval = GSQLStr(_xpost('Notes'), 'textv');
        $bool = " AND ";

        $pst = $namval . $pstval . $addval . $notval;
        $ct = $vndval + $gndval + $relval + $natval + $typval;
    }

    $qryval = '';
    $qrysel = '';
    if ($pst != "" || $ct != 0) {
        $qryval .= $gndval == 0 ? "" : " sex={$gndval}";
        $qryval .= ( ($vndval == 0 || $qryval == "") ? "" : $bool) . ($vndval == 0 ? "" : " VendorID={$vndval}");
        $qryval .= ( ($relval == 0 || $qryval == "") ? "" : $bool) . ($relval == 0 ? "" : " religion={$relval}");
        $qryval .= ( ($natval == 0 || $qryval == "") ? "" : $bool) . ($natval == 0 ? "" : " nationality={$natval}");
        $qryval .= ( ($typval == 0 || $qryval == "") ? "" : $bool) . ($typval == 0 ? "" : " ClientType={$typval}");
        
        $qryval .= ( ($pstval == "" || $qryval == "") ? "" : $bool) . ($pstval == "" ? "" : " (passportno LIKE '%{$pstval}%')");
        $qryval .= ( ($namval == "" || $qryval == "") ? "" : $bool) . ($namval == "" ? "" : " (CompanyName LIKE '%{$namval}%' OR ContactTitle LIKE '%{$namval}%' OR ContactFirstName LIKE '%{$namval}%' OR ContactMidName LIKE '%{$namval}%' OR ContactLastName LIKE '%{$namval}%')");
        $qryval .= ( ($addval == "" || $qryval == "") ? "" : $bool) . ($addval == "" ? "" : " (BillingAddress LIKE '%{$addval}%' OR City LIKE '%{$addval}%' OR StateOrProvince LIKE '%{$addval}%' OR homephone LIKE '%{$addval}%')");
        $qryval .= ( ($notval == "" || $qryval == "") ? "" : $bool) . ($notval == "" ? "" : " Notes LIKE '%{$notval}%'");
    }
    $qryval = $qryval == '' ? '' : ' AND (' . $qryval . ')';
    return $qryval;
}
