<?php

$Struct_flds = "`ass_names`, `ass_codes`, `ass_ca`, `ass_state`, `ass_grp`, `percentages`, `max_scores`, 
    `attachments`, `attend_date`, `cls_typ`, `cls_names`, `cls_codes`, `cls_ca`, `cls_state`, `cls_grp_inf`, 
    `cls_percentages`, `cls_max_scores`";

function cummulate($student, $arm, $course, $ass_struct, $glue, $scores, $attend) {
    global $dbh, $Struct_flds;
    $tot = 0;
    $mx = 0;
    $cls_typ = $ass_struct['cls_typ'];
    $ca = explode('|', $ass_struct['ass_ca'] . $glue . $ass_struct['cls_ca']);
    $per = explode('|', $ass_struct['percentages'] . $glue . $ass_struct['cls_percentages']);
    $grp = explode('|', $ass_struct['ass_grp']);
    $max = explode('|', $ass_struct['max_scores'] . $glue . $ass_struct['cls_max_scores']);
    $grp_inf = TabExplode($ass_struct['cls_grp_inf'], '|', ':');
    $score = explode('|', $scores);
    $grps = array();
    $mark = array();
    $marks = array();

    for ($g = 0; $g < count($grp_inf); $g++)
        $grps[$g] = 0;
    for ($c = 0; $c < count($per); $c++) {
        $val = $c < count($score) ? $score[$c] : 0;
        if ($ca[$c] == 0)
            $mx = $max[$c];
        else if ($ca[$c] == -1) {
            $att = explode('|', $attend);
            $val = array_sum($att);
            $mx = count($att) * 3;
        } else {
            $sql = "SELECT `scores`, `attend` 
                FROM `{$_SESSION['DBCoy']}`.`sch_assess`
                WHERE `student`=$student AND `class`=$arm AND `term`={$ca[$c]}";
            $row_TAss = getDBDataRow($dbh, $sql);

            $sql = "SELECT $Struct_flds 
            FROM `{$_SESSION['DBCoy']}`.`sch_assess_struct` 
            WHERE `ass_arm`=$arm AND `ass_term`={$ca[$c]} AND `ass_course`=$course";
            $row_TStruct = getDBDataRow($dbh, $sql);

            $val = cummulate($student, $arm, $course, $row_TStruct, $glue, $row_TAss['scores'], $row_TAss['attend']);
            $mx = 100;
        }
        $gpr = $grp[$c];
        if ($gpr == 0) {
            $ret = aggregate($cls_typ, $tot, $mx, $val, $per[$c]);
            $tot = $ret[0];
            array_push($mark, $ret[1]);
        } else {
            $ret = aggregate($grp_inf[$gpr][4], $grps[$gpr], $mx, $val, $per[$c]);
            $grps[$gpr] = $ret[0];
        }
    }

    for ($i = 1; $i < count($grps); $i++) {
        if ($grp_inf[$grp[$i]][4] == 1) {
            $arr = explode(',', $grps[$i]);
            $grps[$i] = round(array_sum($arr) / count($arr));
        }
        $ret = aggregate($cls_typ, $tot, 100, $grps[$i], $grp_inf[$grp[$i]][3]);
        $tot = $ret[0];
        $grps[$i] = $grps[$i];
        array_push($marks, $ret[1]);
    }
    $marks = array_merge($marks, $mark);

    if ($cls_typ == 1) {
        $arr = explode(',', substr($tot, 2));
        $tot = round(array_sum($arr) / count($arr));
    }

    return array("tot" => $tot, "marks" => $marks);
}

function aggregate($typ, $tot, $max, $val, $per) {
    $total = 0;
    switch ($typ) {
        case 1:
            $total = "$tot,$val";
        case 2:
            $total = $tot > $val ? $tot : $val;
        default:
            $denum = $max;
            $val = $denum > 0 ? round($val * $per / $denum) : 0;
            $total = $tot + $val;
    }
    return array($total, $val);
}

function course_sheet($arm, $term, $course, $all) {
    global $dbh, $vendor_sql, $TAssess, $row_TAss_struct, $glue,
    $CA, $ca_typ, $Max, $Per, $GRP, $ca_lst, $att_cnt, $grp_inf,
    $attends, $prev_terms, $Struct_flds;

    $idStr = !$all ? "`sch_assess`.`class`=$arm" :
            "`sch_arms`.`class` = (SELECT `class` FROM `{$_SESSION['DBCoy']}`.`sch_arms` WHERE `arm_id`=$arm)";
    $sql = "SELECT `assess_id`, `sch_assess`.`student`, `course`, `scores`, `attachs`, `attend`,
            `sch_assess`.`comments`, `sch_assess`.`Notes`, $vendor_sql
            FROM `{$_SESSION['DBCoy']}`.`sch_assess`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_course_offer` ON 
            (`sch_assess`.`student`=`sch_course_offer`.`student` AND `sch_assess`.`term`=`sch_course_offer`.`term` AND 
            `sch_assess`.`course` REGEXP CONCAT('^(', `sch_course_offer`.`courses`, ')$'))
            INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_assess`.`student`=`vendors`.`VendorID`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `sch_assess`.`class`=`sch_arms`.`arm_id`
            WHERE $idStr AND `sch_assess`.`term`=$term AND `course`=$course ORDER BY `VendorName`";
    $TAssess = getDBData($dbh, $sql);

    $sql = "SELECT $Struct_flds, `course_name`
            FROM `{$_SESSION['DBCoy']}`.`sch_assess_struct`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `sch_assess_struct`.`ass_arm`=`sch_arms`.`arm_id`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_cls_ass_struct` ON 
            (`sch_assess_struct`.`ass_term`=`sch_cls_ass_struct`.`cls_term`
            AND `sch_arms`.`class`=`sch_cls_ass_struct`.`class`)
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_courses` ON `sch_assess_struct`.`ass_course`=`sch_courses`.`course_id`
            WHERE `ass_arm`=$arm AND `ass_term`=$term AND `ass_course`=$course";
    $row_TAss_struct = getDBDataRow($dbh, $sql);

    $glue = strlen($row_TAss_struct['ass_codes']) > 0 && strlen($row_TAss_struct['cls_codes']) > 0 ? '|' : '';
    $CA = explode("|", $row_TAss_struct['ass_codes'] . $glue . $row_TAss_struct['cls_codes']);
    $cas = $row_TAss_struct['ass_ca'] . $glue . $row_TAss_struct['cls_ca'];
    $ca_typ = explode("|", $cas);
    $Max = explode("|", $row_TAss_struct['max_scores'] . $glue . $row_TAss_struct['cls_max_scores']);
    $Per = explode("|", $row_TAss_struct['percentages'] . $glue . $row_TAss_struct['cls_percentages']);
    $GRP = explode("|", $row_TAss_struct['ass_grp'] . $glue . '');
    $ca_lst = str_replace("|", ",", $cas);
    $att_cnt = count(explode('|', $row_TAss_struct['attend_date'])) * 3;
    $grp_inf = TabExplode($row_TAss_struct['cls_grp_inf'], '|', ':');

    $attends = array();
    foreach ($TAssess as $row_TAssess) {
        $attend = explode("|", $row_TAssess['attend']);
        array_push($attends, array_sum($attend));
    }

    $sql = "SELECT $Struct_flds 
        FROM `{$_SESSION['DBCoy']}`.`sch_assess_struct`
        INNER JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `sch_assess_struct`.`ass_arm`=`sch_arms`.`arm_id`
        INNER JOIN `{$_SESSION['DBCoy']}`.`sch_cls_ass_struct` ON 
        (`sch_assess_struct`.`ass_term`=`sch_cls_ass_struct`.`cls_term`
        AND `sch_arms`.`class`=`sch_cls_ass_struct`.`class`)
        WHERE `ass_arm`=$arm AND `ass_course`=$course AND `ass_term` IN ($ca_lst)";

    $TStruct = strlen($ca_lst) > 0 ? getDBData($dbh, $sql) : array();

    $prev_terms = array();
    foreach ($TStruct as $row_TStruct) {
        $sql = "SELECT `student`, `scores`, `attend` 
            FROM `{$_SESSION['DBCoy']}`.`sch_assess`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_course_offer` ON 
            (`sch_assess`.`student`=`sch_course_offer`.`student` AND `sch_assess`.`term`=`sch_course_offer`.`term` AND 
            `sch_assess`.`course` REGEXP CONCAT('^(', `sch_course_offer`.`courses`, ')$'))
            INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `sch_assess`.`student`=`vendors`.`VendorID`
            WHERE $idStr AND `course`=$course AND `term`={$row_TStruct['ass_term']}";
        $TAss = getDBData($dbh, $sql);

        foreach ($TAss as $row_TAss) {
            $tot = cummulate($row_TAss['student'], $arm, $course, $row_TStruct, $glue, $row_TAss['scores'], $row_TAss['attend']);
            $prev_terms[$row_TStruct['ass_term']][$row_TAss['student']] = $tot["tot"];
        }
    }
}

function term_report($arm, $term) {
    global $dbh, $vendor_sql, $stud_data, $Term_data, $high_av, $cls_av, $Struct_flds;

    $Term_data = array();

    $sql = "SELECT `courses` 
            FROM `{$_SESSION['DBCoy']}`.`sch_electives`
            WHERE `elect_arm`=$arm AND `elect_term`=$term";
    $row_crs = getDBDataRow($dbh, $sql);
    $crslst = str_replace("|", ",", $row_crs['courses']);

    $sql = "SELECT `course_id`, `department` 
        FROM `{$_SESSION['DBCoy']}`.`sch_courses`
        WHERE `course_id` IN ($crslst) ORDER BY `department`";
    $TCourse = getDBData($dbh, $sql);

    foreach ($TCourse as $row_TCourse) {
        $course = $row_TCourse['course_id'];
        $sql = "SELECT `assess_id`, `sch_assess`.`student`, `course`, `scores`, `attachs`, `attend`,
            `sch_assess`.`comments`
            FROM `{$_SESSION['DBCoy']}`.`sch_assess`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_course_offer` ON 
            (`sch_assess`.`student`=`sch_course_offer`.`student` AND `sch_assess`.`term`=`sch_course_offer`.`term` AND 
            `sch_assess`.`course` REGEXP CONCAT('^(', `sch_course_offer`.`courses`, ')$'))
            WHERE `sch_assess`.`class`=$arm AND `sch_assess`.`term`=$term AND `course`=$course";
        $TAss = getDBData($dbh, $sql);

        if (count($TAss) == 0)
            continue;

        $sql = "SELECT $Struct_flds, `course_name`, `department` 
            FROM `{$_SESSION['DBCoy']}`.`sch_assess_struct`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_arms` ON `sch_assess_struct`.`ass_arm`=`sch_arms`.`arm_id`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_cls_ass_struct` ON 
            (`sch_assess_struct`.`ass_term`=`sch_cls_ass_struct`.`cls_term`
            AND `sch_arms`.`class`=`sch_cls_ass_struct`.`class`)
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_courses` ON `sch_assess_struct`.`ass_course`=`sch_courses`.`course_id`
            WHERE `ass_arm`=$arm AND `ass_term`=$term AND `ass_course`=$course";
        $row_TStruct = getDBDataRow($dbh, $sql);

        if (count($row_TStruct) == 0)
            break;

        $students = array();
        $stud = array();
        $tots = array();
        foreach ($TAss as $row_TAss) {
            $glue = strlen($row_TStruct['ass_codes']) > 0 && strlen($row_TStruct['cls_codes']) > 0 ? '|' : '';
            $tot = cummulate($row_TAss['student'], $arm, $course, $row_TStruct, $glue, $row_TAss['scores'], $row_TAss['attend']);
            $students[$row_TAss['student']]['tot'] = $tot['tot'];
            $students[$row_TAss['student']]['marks'] = $tot['marks'];
            $students[$row_TAss['student']]['comments'] = $row_TAss['comments'];
            array_push($stud, $row_TAss['student']);
            array_push($tots, $tot["tot"]);
        }
        // Sort
        array_multisort($tots, SORT_DESC, SORT_NUMERIC, $stud);
        for ($i = 0; $i < count($stud); $i++) {
            $pos = $i > 0 && $tots[$i] == $tots[$i - 1] ? $students[$stud[$i - 1]]['pos'] : $i + 1;
            $students[$stud[$i]]['pos'] = $pos;
        }
        $Term_data[$course] = array("config" => $row_TStruct, "students" => $students, "highest" => $tots[0]);
    }

    // Average
    $sql = "SELECT `VendorID`, $vendor_sql, `attend`, `sch_stud_attend`.`comments`
            FROM `{$_SESSION['DBCoy']}`.`vendors`
            INNER JOIN `{$_SESSION['DBCoy']}`.`sch_stud_attend` ON `vendors`.`VendorID`=`sch_stud_attend`.`student`
            WHERE `term`=$term AND `DeptID`=$arm ORDER BY `VendorName`";
    $TStuds = getDBData($dbh, $sql);

    $stud_data = array(); //
    $stud = array();
    $tots = array();
    $cls_cnt = 0;
    $cls_tot = 0;
    foreach ($TStuds as $TStud) {
        $id = $TStud["VendorID"];
        $students = array();
        $sum = 0;
        $cnt = 0;
        foreach ($Term_data as $subject) {
            if (isset($subject["students"][$id])) {
                $sum += $subject["students"][$id]["tot"];
                $cnt++;
            }
        }
        if ($cnt == 0)
            break;
        $av = round($sum / $cnt, 1);
        $att = explode('|', $TStud["attend"]);
        $att_cnt = array_count_values($att);

        $stud_data[$id]["present"] = (isset($att_cnt[1]) ? $att_cnt[1] : 0) +
                (isset($att_cnt[2]) ? $att_cnt[2] : 0) + (isset($att_cnt[3]) ? $att_cnt[3] : 0);
        $stud_data[$id]["absent"] = isset($att_cnt[0]) ? $att_cnt[0] : 0;
        $stud_data[$id]["late"] = isset($att_cnt[1]) ? $att_cnt[1] : 0;
        $stud_data[$id]["name"] = $TStud["VendorName"];
        $stud_data[$id]["comments"] = $TStud["comments"];
        $stud_data[$id]["sum"] = $sum;
        $stud_data[$id]["average"] = $av;
        array_push($stud, $id);
        array_push($tots, $av);
        $cls_tot += $av;
        $cls_cnt++;
    }
    $cls_av = $cls_cnt > 0 ? round($cls_tot / $cls_cnt, 1) : 0;

    // Sort
    array_multisort($tots, SORT_DESC, SORT_NUMERIC, $stud);
    for ($i = 0; $i < count($stud); $i++) {
        $pos = $i > 0 && $tots[$i] == $tots[$i - 1] ? $stud_data[$stud[$i - 1]]['pos'] : $i + 1;
        $stud_data[$stud[$i]]['pos'] = $pos;
    }
    $high_av = $cls_cnt > 0 ? $tots[0] : 0;
}

?>
