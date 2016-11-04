<?php 

function findRec() {
    $pst = "";
    $ct = $useval = $chqval = $cdtval = 0;
    $bool = isset($_POST['strict']) ? " AND " : " OR ";
    if (isset($_POST['name'])) {
	$namval = GSQLStr(_xpost('name'), 'textv');
	$gndval = GSQLStr(_xpost('sex'), 'int');
	$relval = GSQLStr(_xpost('religion'), 'int');
	$ag1val = GSQLStr(_xpost('dob1'), 'int');
	$ag2val = GSQLStr(_xpost('dob2'), 'int');
	$natval = GSQLStr(_xpost('nationality'), 'int');
	$orgval = GSQLStr(_xpost('origin'), 'textv');
	$typval = GSQLStr(_xpost('ClientType'), 'int');
	$catval = GSQLStr(_xpost('categoryid'), 'int');
	$cr1val = GSQLStr(_xpost('creditlimit1'), 'textv');
	$cr2val = GSQLStr(_xpost('creditlimit2'), 'textv');
	$ds1val = GSQLStr(_xpost('Discount1'), 'textv');
	$ds2val = GSQLStr(_xpost('Discount2'), 'textv');
	$cdtval = GSQLStr(_xpost('credit'), 'int');
	$chqval = GSQLStr(_xpost('cheque'), 'int');
	$curval = GSQLStr(_xpost('currency'), 'int');
	$parval = GSQLStr(_xpost('parentcompany'), 'int');
	$useval = GSQLStr(_xpost('InUse'), 'int');
	$addval = GSQLStr(_xpost('City'), 'textv');
	$notval = GSQLStr(_xpost('Notes'), 'textv');
    
	$pst = $namval . $orgval . $cr1val . $cr2val . $ds1val . $ds2val . $addval . $notval;
	$ct = $gndval + $relval + $ag1val + $ag2val + $natval + $catval + $curval + $parval + 
                $typval + $cdtval + $chqval + $useval;
    }

    $qryval = '';
    $qrysel = '';
    if ($pst != "" || $ct != 0 || $useval > -1 || $chqval > -1 || $cdtval > -1 ) {
	$qryval .= $gndval == 0 ? "" : " sex={$gndval}";
	$qryval .= ( ($relval == 0 || $qryval == "") ? "" : $bool) . ($relval == 0 ? "" : " religion={$relval}");
	$qryval .= ( ($ag1val == 0 || $qryval == "") ? "" : $bool) . ($ag1val == 0 ? "" : " DATE_SUB(CURDATE(),INTERVAL {$ag1val} YEAR) >= dateofbirth)");
	$qryval .= ( ($ag2val == 0 || $qryval == "") ? "" : $bool) . ($ag2val == 0 ? "" : " DATE_SUB(CURDATE(),INTERVAL {$ag2val} YEAR) <= dateofbirth)");
	$qryval .= ( ($natval == 0 || $qryval == "") ? "" : $bool) . ($natval == 0 ? "" : " nationality={$natval}");
	$qryval .= ( ($typval == 0 || $qryval == "") ? "" : $bool) . ($typval == 0 ? "" : " ClientType={$typval}");
	$qryval .= ( ($catval == 0 || $qryval == "") ? "" : $bool) . ($catval == 0 ? "" : " categoryid={$catval}");
	$qryval .= ( ($ds1val == 0 || $qryval == "") ? "" : $bool) . ($ds1val == 0 ? "" : " Discount>={$ds1val}");
	$qryval .= ( ($ds2val == 0 || $qryval == "") ? "" : $bool) . ($ds2val == 0 ? "" : " Discount<={$ds2val}");
	$qryval .= ( ($cr1val == 0 || $qryval == "") ? "" : $bool) . ($cr1val == 0 ? "" : " creditlimit>={$cr1val}");
	$qryval .= ( ($cr2val == 0 || $qryval == "") ? "" : $bool) . ($cr2val == 0 ? "" : " creditlimit<={$cr2val}");
	$qryval .= ( ($curval == 0 || $qryval == "") ? "" : $bool) . ($curval == 0 ? "" : " currency={$curval}");
	$qryval .= ( ($parval == 0 || $qryval == "") ? "" : $bool) . ($parval == 0 ? "" : " parentcompany={$parval}");
	$qryval .= ( ($cdtval == -1 || $qryval == "") ? "" : $bool) . ($cdtval == -1 ? "" : " credit={$cdtval}");
	$qryval .= ( ($useval == -1 || $qryval == "") ? "" : $bool) . ($useval == -1 ? "" : " InUse={$useval}");
	$qryval .= ( ($chqval == -1 || $qryval == "") ? "" : $bool) . ($chqval == -1 ? "" : " cheque={$chqval}");
	
	$qryval .= ( ($namval == "" || $qryval == "") ? "" : $bool) . ($namval == "" ? "" : " (ContactTitle LIKE '%{$namval}%' OR ContactFirstName LIKE '%{$namval}%' OR ContactMidName LIKE '%{$namval}%' OR ContactLastName LIKE '%{$namval}%')");
	$qryval .= ( ($orgval == "" || $qryval == "") ? "" : $bool) . ($orgval == "" ? "" : " (stateorigin LIKE '%{$orgval}%' OR locgovorigin LIKE '%{$orgval}%' OR nativetongue LIKE '%{$orgval}%' OR PostalCode LIKE '%{$orgval}%' OR ContactsInterests LIKE '%{$orgval}%')");
	$qryval .= ( ($addval == "" || $qryval == "") ? "" : $bool) . ($addval == "" ? "" : " (BillingAddress LIKE '%{$addval}%' OR City LIKE '%{$addval}%' OR StateOrProvince LIKE '%{$addval}%' OR homephone LIKE '%{$addval}%')");
	$qryval .= ( ($notval == "" || $qryval == "") ? "" : $bool) . ($notval == "" ? "" : " Notes LIKE '%{$notval}%'");
    }
    $qryval = $qryval==''? '': ' AND ('.$qryval.')';
    return $qryval;
}

?>
