<?php 

function findRec() {
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
	$dptval = GSQLStr(_xpost('DeptID'), 'int');
	$typval = GSQLStr(_xpost('ClientType'), 'int');
	$catval = GSQLStr(_xpost('categoryid'), 'int');
	$jobval = GSQLStr(_xpost('ReferredBy'), 'textv');
	$prjval = GSQLStr(_xpost('Discount'), 'int');
	$locval = GSQLStr(_xpost('FaxNumber'), 'textv');
	$bnkval = GSQLStr(_xpost('currency'), 'int');
	$accval = GSQLStr(_xpost('logofile'), 'textv');
	$plnval = GSQLStr(_xpost('salary'), 'int');
	$hspval = GSQLStr(_xpost('parentcompany'), 'int');
	$insval = _xpost('credit');
	$supvel = GSQLStr(_xpost('supervisor'), 'int');
	$temval = _xpost('InUse');
	$levval = _xpost('leavstatus');
	$addval = GSQLStr(_xpost('City'), 'textv');
	$notval = GSQLStr(_xpost('Notes'), 'textv');
    $bool = isset($_POST['strict']) ? " AND " : " OR ";
    
	$pst = $namval . $orgval . $jobval . $locval . $accval . $addval . $notval;
	$ct = $gndval + $marval + $relval + $ag1val + $ag2val + $natval + $dptval + $catval + $prjval + $bnkval + $plnval + $hspval + $insval + $supvel + $temval + $levval;
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
	$qryval .= ( ($typval == 0 || $qryval == "") ? "" : $bool) . ($typval == 0 ? "" : " ClientType={$typval}");
	$qryval .= ( ($dptval == 0 || $qryval == "") ? "" : $bool) . ($dptval == 0 ? "" : " DeptID={$dptval}");
	$qryval .= ( ($catval == 0 || $qryval == "") ? "" : $bool) . ($catval == 0 ? "" : " categoryid={$catval}");
	$qryval .= ( ($prjval == 0 || $qryval == "") ? "" : $bool) . ($prjval == 0 ? "" : " Discount={$prjval}");
	$qryval .= ( ($bnkval == 0 || $qryval == "") ? "" : $bool) . ($bnkval == 0 ? "" : " currency={$bnkval}");
	$qryval .= ( ($plnval == 0 || $qryval == "") ? "" : $bool) . ($plnval == 0 ? "" : " salary={$plnval}");
	$qryval .= ( ($hspval == 0 || $qryval == "") ? "" : $bool) . ($hspval == 0 ? "" : " parentcompany={$hspval}");
	$qryval .= ( ($insval == 0 || $qryval == "") ? "" : $bool) . ($insval == 0 ? "" : " credit={$insval}");
	$qryval .= ( ($supvel == 0 || $qryval == "") ? "" : $bool) . ($supvel == 0 ? "" : " supervisor={$supvel}");
	$qryval .= ( ($temval == 0 || $qryval == "") ? "" : $bool) . ($temval == 0 ? "" : " InUse={$temval}");
	$qryval .= ( ($levval == 0 || $qryval == "") ? "" : $bool) . ($levval == 0 ? "" : " leavstatus={$levval}");
    
	$qryval .= ( ($namval == "" || $qryval == "") ? "" : $bool) . ($namval == "" ? "" : " (ContactTitle LIKE '%{$namval}%' OR ContactFirstName LIKE '%{$namval}%' OR ContactMidName LIKE '%{$namval}%' OR ContactLastName LIKE '%{$namval}%')");
	$qryval .= ( ($orgval == "" || $qryval == "") ? "" : $bool) . ($orgval == "" ? "" : " (stateorigin LIKE '%{$orgval}%' OR locgovorigin LIKE '%{$orgval}%' OR nativetongue LIKE '%{$orgval}%' OR PostalCode LIKE '%{$orgval}%' OR ContactsInterests LIKE '%{$orgval}%')");
	$qryval .= ( ($addval == "" || $qryval == "") ? "" : $bool) . ($addval == "" ? "" : " (BillingAddress LIKE '%{$addval}%' OR City LIKE '%{$addval}%' OR StateOrProvince LIKE '%{$addval}%' OR homephone LIKE '%{$addval}%')");
	$qryval .= ( ($jobval == "" || $qryval == "") ? "" : $bool) . ($jobval == "" ? "" : " ReferredBy LIKE '%{$jobval}%'");
	$qryval .= ( ($locval == "" || $qryval == "") ? "" : $bool) . ($locval == "" ? "" : " FaxNumber LIKE '%{$locval}%'");
	$qryval .= ( ($accval == "" || $qryval == "") ? "" : $bool) . ($accval == "" ? "" : " logofile LIKE '%{$accval}%'");
	$qryval .= ( ($notval == "" || $qryval == "") ? "" : $bool) . ($notval == "" ? "" : " Notes LIKE '%{$notval}%'");
    }
    $qryval = $qryval==''? '': ' AND ('.$qryval.')';
    return $qryval;
}

?>