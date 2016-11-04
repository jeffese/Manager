<?php 

function getParent() {
    $pst = "";
    $ct = 0;
    if (isset($_POST['name'])) {
	$namval = GSQLStr(_xpost('name'), 'textv');
	$gndval = GSQLStr(_xpost('sex'), 'int');
	$marval = GSQLStr(_xpost('marital_status'), 'int');
	$relval = GSQLStr(_xpost('religion'), 'int');
	$ag1val = GSQLStr(_xpost('dob1'), 'int');
	$ag2val = GSQLStr(_xpost('dob2'), 'int');
	$natval = GSQLStr(_xpost('nationality'), 'int');
	$orgval = GSQLStr(_xpost('origin'), 'textv');
	$jobval = GSQLStr(_xpost('ReferredBy'), 'textv');
	$locval = GSQLStr(_xpost('FaxNumber'), 'textv');
	$addval = GSQLStr(_xpost('City'), 'textv');
	$notval = GSQLStr(_xpost('Notes'), 'textv');
    $bool = isset($_POST['strict']) ? " AND " : " OR ";
    
	$pst = $namval . $orgval . $jobval . $locval . $addval . $notval;
	$ct = $gndval + $marval + $relval + $ag1val + $ag2val + $natval;
    }

    $qryval = '';
    $qrysel = '';
    if ($pst != "" || $ct != 0) {
	$qryval .= $gndval == 0 ? "" : " sex={$gndval}";
	$qryval .= ( ($marval == 0 || $qryval == "") ? "" : $bool) . ($marval == 0 ? "" : " marital_status={$marval}");
	$qryval .= ( ($relval == 0 || $qryval == "") ? "" : $bool) . ($relval == 0 ? "" : " religion={$relval}");
	$qryval .= ( ($ag1val == 0 || $qryval == "") ? "" : $bool) . ($ag1val == 0 ? "" : " DATE_SUB(CURDATE(),INTERVAL {$ag1val} YEAR) >= dateofbirth)");
	$qryval .= ( ($ag2val == 0 || $qryval == "") ? "" : $bool) . ($ag2val == 0 ? "" : " DATE_SUB(CURDATE(),INTERVAL {$ag2val} YEAR) <= dateofbirth)");
	$qryval .= ( ($natval == 0 || $qryval == "") ? "" : $bool) . ($natval == 0 ? "" : " nationality={$natval}");
    
	$qryval .= ( ($namval == "" || $qryval == "") ? "" : $bool) . ($namval == "" ? "" : " (ContactTitle LIKE '%{$namval}%' OR ContactFirstName LIKE '%{$namval}%' OR ContactMidName LIKE '%{$namval}%' OR ContactLastName LIKE '%{$namval}%')");
	$qryval .= ( ($orgval == "" || $qryval == "") ? "" : $bool) . ($orgval == "" ? "" : " (stateorigin LIKE '%{$orgval}%' OR locgovorigin LIKE '%{$orgval}%' OR nativetongue LIKE '%{$orgval}%' OR PostalCode LIKE '%{$orgval}%' OR ContactsInterests LIKE '%{$orgval}%')");
	$qryval .= ( ($addval == "" || $qryval == "") ? "" : $bool) . ($addval == "" ? "" : " (BillingAddress LIKE '%{$addval}%' OR City LIKE '%{$addval}%' OR StateOrProvince LIKE '%{$addval}%' OR homephone LIKE '%{$addval}%')");
	$qryval .= ( ($jobval == "" || $qryval == "") ? "" : $bool) . ($jobval == "" ? "" : " ReferredBy LIKE '%{$jobval}%'");
	$qryval .= ( ($locval == "" || $qryval == "") ? "" : $bool) . ($locval == "" ? "" : " FaxNumber LIKE '%{$locval}%'");
	$qryval .= ( ($notval == "" || $qryval == "") ? "" : $bool) . ($notval == "" ? "" : " Notes LIKE '%{$notval}%'");
    }
    $qryval = $qryval==''? '': ' AND ('.$qryval.')';
    return $qryval;
}

?>