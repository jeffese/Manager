<?php 

function findRec() {
    $pst = "";
    $ct = 0; $temval = -1; $levval = -1; $insval = -1;
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
	$catval = GSQLStr(_xpost('categoryid'), 'int');
	$jobval = GSQLStr(_xpost('ReferredBy'), 'textv');
	$prjval = GSQLStr(_xpost('Discount'), 'int');
	$locval = GSQLStr(_xpost('FaxNumber'), 'textv');
	$bnkval = GSQLStr(_xpost('bank'), 'int');
	$accval = GSQLStr(_xpost('logofile'), 'textv');
	$plnval = GSQLStr(_xpost('salary'), 'int');
	$hspval = GSQLStr(_xpost('parentcompany'), 'int');
	$supvel = GSQLStr(_xpost('supervisor'), 'int');
	$insval = GSQLStr(_xpost('credit'), 'int');
	$temval = GSQLStr(_xpost('InUse'), 'int');
	$levval = GSQLStr(_xpost('leavstatus'), 'int');
	$addval = GSQLStr(_xpost('City'), 'textv');
	$notval = GSQLStr(_xpost('Notes'), 'textv');
    $bool = isset($_POST['strict']) ? " AND " : " OR ";
    
	$pst = $namval . $orgval . $jobval . $locval . $accval . $addval . $notval;
	$ct = $gndval + $marval + $relval + $ag1val + $ag2val + $natval + $dptval + 
        $catval + $prjval + $bnkval + $plnval + $hspval + $supvel;
    }

    $qryval = '';
    $qrysel = '';
    if ($pst != "" || $ct != 0 || $temval > -1 || $levval > -1 || $insval > -1) {
	$qryval .= $gndval == 0 ? "" : " sex={$gndval}";
	$qryval .= ( ($marval == 0 || $qryval == "") ? "" : $bool) . ($marval == 0 ? "" : " marital_status={$marval}");
	$qryval .= ( ($relval == 0 || $qryval == "") ? "" : $bool) . ($relval == 0 ? "" : " religion={$relval}");
	$qryval .= ( ($ag1val == 0 || $qryval == "") ? "" : $bool) . ($ag1val == 0 ? "" : " DATE_SUB(CURDATE(),INTERVAL {$ag1val} YEAR) >= dateofbirth)");
	$qryval .= ( ($ag2val == 0 || $qryval == "") ? "" : $bool) . ($ag2val == 0 ? "" : " DATE_SUB(CURDATE(),INTERVAL {$ag2val} YEAR) <= dateofbirth)");
	$qryval .= ( ($natval == 0 || $qryval == "") ? "" : $bool) . ($natval == 0 ? "" : " nationality={$natval}");
	$qryval .= ( ($dptval == 0 || $qryval == "") ? "" : $bool) . ($dptval == 0 ? "" : " DeptID={$dptval}");
	$qryval .= ( ($catval == 0 || $qryval == "") ? "" : $bool) . ($catval == 0 ? "" : " categoryid={$catval}");
	$qryval .= ( ($prjval == 0 || $qryval == "") ? "" : $bool) . ($prjval == 0 ? "" : " Discount={$prjval}");
	$qryval .= ( ($bnkval == 0 || $qryval == "") ? "" : $bool) . ($bnkval == 0 ? "" : " bank={$bnkval}");
	$qryval .= ( ($plnval == 0 || $qryval == "") ? "" : $bool) . ($plnval == 0 ? "" : " salary={$plnval}");
	$qryval .= ( ($hspval == 0 || $qryval == "") ? "" : $bool) . ($hspval == 0 ? "" : " parentcompany={$hspval}");
	$qryval .= ( ($insval == -1 || $qryval == "") ? "" : $bool) . ($insval == -1 ? "" : " credit={$insval}");
	$qryval .= ( ($supvel == 0 || $qryval == "") ? "" : $bool) . ($supvel == 0 ? "" : " supervisor={$supvel}");
	$qryval .= ( ($temval == -1 || $qryval == "") ? "" : $bool) . ($temval == -1 ? "" : " InUse={$temval}");
	$qryval .= ( ($levval == -1 || $qryval == "") ? "" : $bool) . ($levval == -1 ? "" : " leavstatus={$levval}");
    
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
