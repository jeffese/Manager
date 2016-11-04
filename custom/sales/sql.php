<?php

function findRec() {
    $pst = "";
    $ct = 0;
    if (isset($_POST['name'])) {
        $vndval = GSQLStr(_xpost('VendorID'), 'int');
        $namval = GSQLStr(_xpost('name'), 'textv');
        $invval = GSQLStr(_xpost('InvoiceID'), 'int');
        $bool = " AND ";

        $pst = $namval;
        $ct = $vndval + $invval;
    }

    $qryval = '';
    $qrysel = '';
    if ($pst != "" || $ct != 0) {
        $qryval .= $vndval == 0 ? "" : " `invoices`.VendorID={$vndval}";
        $qryval .= ( ($invval == 0 || $qryval == "") ? "" : $bool) . ($invval == 0 ? "" : " InvoiceID={$invval}");

	$qryval .= ( ($namval == "" || $qryval == "") ? "" : $bool) . ($namval == "" ? "" : " (ContactTitle LIKE '%{$namval}%' OR ContactFirstName LIKE '%{$namval}%' OR ContactMidName LIKE '%{$namval}%' OR ContactLastName LIKE '%{$namval}%')");
    }
    $qryval = $qryval == '' ? '' : ' AND (' . $qryval . ')';
    return $qryval;
}
