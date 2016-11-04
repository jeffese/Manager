<?php

function findRec() {
    $pst = "";
    $ct = 0;
    if (isset($_POST['course_name'])) {
        $namval = GSQLStr(_xpost('course_name'), 'textv');
        $codval = GSQLStr(_xpost('course_code'), 'textv');
        $typval = GSQLStr(_xpost('course_type'), "int");
		$dept = GSQLStr(_xpost('department'), "int");
        $lect = GSQLStr(_xpost('lecturer'), "int");
	    $desc = GSQLStr(_xpost('description'), "textv");
	    $note = GSQLStr(_xpost('Notes'), "textv");
        $bool = " AND ";

        $pst = $namval . $codval . $desc . $note;
        $ct = $typval + $dept + $lect;
    }

    $qryval = '';
    $qrysel = '';
    if ($pst != "" || $ct != 0) {
        $qryval .= $typval == 0 ? "" : " course_type=$typval";
        $qryval .= ( ($dept == 0 || $qryval == "") ? "" : $bool) . ($dept == 0 ? "" : " department=$dept");
        $qryval .= ( ($lect == 0 || $qryval == "") ? "" : $bool) . ($lect == 0 ? "" : " lecturer=$lect");
        $qryval .= ( ($namval == "" || $qryval == "") ? "" : $bool) . ($namval == "" ? "" : " (course_name LIKE '%{$namval}%')");
        $qryval .= ( ($codval == "" || $qryval == "") ? "" : $bool) . ($codval == "" ? "" : " (code LIKE '%{$course_code}%')");
        $qryval .= ( ($desc == "" || $qryval == "") ? "" : $bool) . ($desc == "" ? "" : " (description LIKE '%{$catval}%')");
        $qryval .= ( ($note == "" || $qryval == "") ? "" : $bool) . ($note == "" ? "" : " (Notes LIKE '%{$namval}%')");
    }
    $qryval = $qryval == '' ? '' : ' AND (' . $qryval . ')';
    return $qryval;
}

?>