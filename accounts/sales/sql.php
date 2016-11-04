<?php 

function findRec() {
    $pst = "";
    $ct = 0;
    if (isset($_POST['name'])) {
        $vndval = GSQLStr(_xpost('VendorID'), 'int');
        $invval = GSQLStr(_xpost('InvoiceID'), 'int');
        $stfval = GSQLStr(_xpost('staff'), 'int');
        
	$namval = GSQLStr(_xpost('name'), 'textv');
	$dt1val = GSQLStr(_xpost('StartDate'), 'textv');
	$dt2val = GSQLStr(_xpost('EndDate'), 'textv');
        $outval = GSQLStr(_xpost('outlets'), 'textv');
        $prdval = GSQLStr(_xpost('prods'), 'textv');
	$notval = GSQLStr(_xpost('Notes'), 'textv');
	$pstval = GSQLStr(_xpost('Posted'), 'textv');
	$rfdval = GSQLStr(_xpost('Refunded'), 'textv');
        
        $bool = isset($_POST['strict']) ? " AND " : " OR ";
    
        $pst = $namval . $dt1val . $dt2val . $notval . $outval . $prdval . $pstval . $rfdval;
        $ct = $vndval + $invval + $stfval;
    }

    $qryval = '';
    $qrysel = '';
    if ($pst != "" || $ct != 0) {
        $qryval .= $vndval == 0 ? "" : " `invoices`.VendorID={$vndval}";
	$qryval .= ( ($pstval == "" || $qryval == "") ? "" : $bool) . ($pstval == "" ? "" : " Posted={$pstval}");
	$qryval .= ( ($rfdval == "" || $qryval == "") ? "" : $bool) . ($rfdval == "" ? "" : " Status{$rfdval}");
        $qryval .= ( ($invval == 0  || $qryval == "") ? "" : $bool) . ($invval == 0  ? "" : " InvoiceID={$invval}");
	$qryval .= ( ($stfval == 0  || $qryval == "") ? "" : $bool) . ($stfval == 0  ? "" : " EmployeeID={$stfval}");
    
	$qryval .= ( ($outval == "" || $qryval == "") ? "" : $bool) . ($outval == "" ? "" : " `invoices`.OutletID IN ($outval)");
	$qryval .= ( ($prdval == "" || $qryval == "") ? "" : $bool) . ($prdval == "" ? "" : " `ProductID` IN ($prdval)");
	$qryval .= ( ($dt1val == "" || $qryval == "") ? "" : $bool) . ($dt1val == "" ? "" : " '$dt1val' <= Date(LedgerDate)");
	$qryval .= ( ($dt2val == "" || $qryval == "") ? "" : $bool) . ($dt2val == "" ? "" : " '$dt2val' >= Date(LedgerDate)");
	$qryval .= ( ($namval == "" || $qryval == "") ? "" : $bool) . ($namval == "" ? "" : " (ContactTitle LIKE '%{$namval}%' OR ContactFirstName LIKE '%{$namval}%' OR ContactMidName LIKE '%{$namval}%' OR ContactLastName LIKE '%{$namval}%')");
	$qryval .= ( ($notval == "" || $qryval == "") ? "" : $bool) . ($notval == "" ? "" : " (Notes LIKE '%{$notval}%')");
    }
    $qryval = $qryval == '' ? '' : ' AND (' . $qryval . ')';
    return $qryval;
}
