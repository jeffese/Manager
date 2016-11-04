<?php

function findRec() {
    $pst = "";
    $ct = 0;
    if (isset($_POST['lictype'])) {
        $idnval = GSQLStr(_xpost('AssetID'), 'int');
        $catval = GSQLStr(_xpost('Category'), 'int');
        $licval = GSQLStr(_xpost('lictype'), 'int');
        $vtpval = GSQLStr(_xpost('vtype'), 'int');
        $bstval = GSQLStr(_xpost('bstyle'), 'int');
        $yr1val = GSQLStr(_xpost('year_prod1'), 'int');
        $yr2val = GSQLStr(_xpost('year_prod2'), 'int');
        $clrval = GSQLStr(_xpost('colour'), 'int');
        
        $brdval = GSQLStr(_xpost('brandid'), 'textv');
        $serval = GSQLStr(_xpost('serieid'), 'textv');
        $modval = GSQLStr(_xpost('Model'), 'textv');
        $lnoval = GSQLStr(_xpost('licenceno'), 'textv');
        $chsval = GSQLStr(_xpost('modelno'), 'textv');
        $prtval = GSQLStr(_xpost('partno'), 'textv');
        $insval = GSQLStr(_xpost('insuranceno'), 'textv');
        $notval = GSQLStr(_xpost('description'), 'textv');
        $bool = " AND ";

        $pst = $modval . $lnoval . $chsval . $prtval . $insval . $notval;
        $ct = $idnval + $catval + $licval + $vtpval + $bstval + $brdval + $serval + $yr1val + $yr2val + $clrval;
    }

    $qryval = '';
    $qrysel = '';
    if ($pst != "" || $ct != 0) {
        $qryval .= $catval == 0 ? "" : " `assets`.Category={$catval}";
        $qryval .= ( ($idnval == 0 || $qryval == "") ? "" : $bool) . ($idnval == 0 ? "" : " AssetID={$idnval}");
        $qryval .= ( ($licval == 0 || $qryval == "") ? "" : $bool) . ($licval == 0 ? "" : " desgtype={$licval}");
        $qryval .= ( ($vtpval == 0 || $qryval == "") ? "" : $bool) . ($vtpval == 0 ? "" : " SalvageValue={$vtpval}");
        $qryval .= ( ($bstval == 0 || $qryval == "") ? "" : $bool) . ($bstval == 0 ? "" : " DepreciationValue={$bstval}");
        $qryval .= ( ($yr1val == 0 || $qryval == "") ? "" : $bool) . ($yr1val == 0 ? "" : " '$yr1val'<=BarcodeNumber");
        $qryval .= ( ($yr2val == 0 || $qryval == "") ? "" : $bool) . ($yr2val == 0 ? "" : " '$yr2val'>=BarcodeNumber");
        $qryval .= ( ($clrval == 0 || $qryval == "") ? "" : $bool) . ($clrval == 0 ? "" : " colour={$clrval}");
        
        $qryval .= ( ($brdval == 0 || $qryval == "") ? "" : $bool) . ($brdval == 0 ? "" : " Brand='{$brdval}'");
        $qryval .= ( ($serval == 0 || $qryval == "") ? "" : $bool) . ($serval == 0 ? "" : " serialno='{$serval}'");
        $qryval .= ( ($modval == "" || $qryval == "") ? "" : $bool) . ($modval == "" ? "" : " Model LIKE '%{$modval}%'");
        $qryval .= ( ($lnoval == "" || $qryval == "") ? "" : $bool) . ($lnoval == "" ? "" : " licenceno LIKE '%{$lnoval}%'");
        $qryval .= ( ($chsval == "" || $qryval == "") ? "" : $bool) . ($chsval == "" ? "" : " modelno LIKE '%{$chsval}%'");
        $qryval .= ( ($prtval == "" || $qryval == "") ? "" : $bool) . ($prtval == "" ? "" : " partno LIKE '%{$prtval}%'");
        $qryval .= ( ($insval == "" || $qryval == "") ? "" : $bool) . ($insval == "" ? "" : " insuranceno LIKE '%{$insval}%'");
        $qryval .= ( ($notval == "" || $qryval == "") ? "" : $bool) . ($notval == "" ? "" : " (MATCH (`assets`.`description`,`assets`.`Notes`) AGAINST ('$notval' IN BOOLEAN MODE))");
    }
    $qryval = $qryval == '' ? '' : ' AND (' . $qryval . ')';
    return $qryval;
}
