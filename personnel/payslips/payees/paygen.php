<?php

$sal_parts = array(null);
$cum_str = array("", "+", "-");
$oper_str = array("+", "-", "*", "/");
$func_str = array("", "%", "grd", "ded", "sum", "avg", "max", "min");
$work_str = array("WORKED", "TOTAL PERIOD");
define('OPR', '0');
define('VAL', '1');
define('PRD', '2');
define('BRC', '3');
define('IFF', '4');

class SalPart {

    var $id;
    var $name;
    var $ftyp;
    var $oprs;
    var $fncs;
    var $flds;
    var $wins;
    var $state;
    var $itms = array();

    function sal_part($name, $ftyp, $oprs, $fncs, $flds, $wins, $state, $id) {
        $this->id = $id;
        $this->name = $name;
        $this->ftyp = explode('#', $ftyp);
        $this->oprs = explode('#', $oprs);
        $this->fncs = explode('#', $fncs);
        $this->flds = explode('#', $flds);
        $this->wins = explode('#', $wins);
        $this->state = intval($state);
        $this->itms = array();

        for ($p = 0; $p < count($this->flds); $p++) {
            $this->itms[$p] = new Itms();
            $this->itms[$p]->itm($this->ftyp[$p], $this->oprs[$p], $this->fncs[$p], $this->flds[$p], $this->id, $this->wins[$p], $p);
        }
        return $this->doprint();
    }

    function doprint() {
        $str = "";
        $idx = 0;
        $prv = -1;
        if ($this->state == 1)
            for ($t = 0; $t < count($this->itms); $t++) {
                if ($this->itms[$t] != null && $this->itms[$t]->win == -1) {
                    $str .= " " . $this->itms[$t]->doprint($idx++, $prv);
                    $prv = $t;
                }
            }
        return $str;
    }

}

class Itms {

    var $id;
    var $par;
    var $win;
    var $typ;
    var $oprs;
    var $opr;
    var $fncs;
    var $fnc;
    var $flds;

    function itm($typ, $oprs, $fncs, $flds, $par, $wins, $cnt) {
        $this->id = $cnt;
        $this->par = $par;
        $this->win = $wins;
        $this->typ = $typ;
        $this->oprs = explode('~', $oprs);
        $this->opr = intval($this->oprs[0]);
        $this->fncs = explode('~', $fncs);
        $this->fnc = intval($this->fncs[0]);
        $this->flds = Tab_Explode($flds, '~', ':');
    }

    function printGrad() {
        $str = '';
        for ($i = 0; $i < count($this->flds) - 1; $i++)
            $str .= ", '{$this->flds[$i][1]}'=>{$this->flds[$i][0]}";
        return 'array(' . substr($str, 2) . ')' . (strlen($str) > 0 ? ', ' : '') . "{$this->flds[$i]}";
    }

    function getFlds() {
        global $names;
        $lst = '';
        for ($l = 0; $l < count($this->flds); $l++) {
            $val = $names[$this->flds[$l]];
            $lst .= ', ' . ($this->flds[$l] == 0 ? $val : "[$val]");
        }
        $val = substr($lst, 2);
        return count($this->flds) > 1 ? "array($val)" : $val;
    }

    function doprint($idx, $prv) {
        global $sal_parts, $func_str, $oper_str, $work_str;
        $isPer = $prv > -1 && ($sal_parts[$this->par]->itms[$prv]->fnc == 1 ||
                $sal_parts[$this->par]->itms[$prv]->fnc == 2);
        $opr = $this->opr != 1 && ($idx == 0 || $isPer) ? '' : $oper_str[$this->opr] . ($isPer ? '' : ' ');
        $fnc = $func_str[$this->fnc];
        $val = "";
        switch ($this->typ) {
            case OPR:
                $val = $this->fnc == 2 ? $this->printGrad() : $this->getFlds();
                break;
            case VAL:
                $val = $this->flds[0];
                break;
            case PRD:
                $val = $work_str[$this->flds[0]];
                break;
            case BRC:
                $_idx = 0;
                $_prv = -1;
                for ($t = 0; $t < count($sal_parts[$this->par]->itms); $t++)
                    if ($sal_parts[$this->par]->itms[$t] != null && $sal_parts[$this->par]->itms[$t]->win == $this->id) {
                        $val .= " " . $sal_parts[$this->par]->itms[$t]->doprint($_idx++, $_prv);
                        $_prv = $t;
                    }
                $val = '( ' . substr($val, substr($val, 2, 1) == ' ' ? 3 : 1) . ' )';
                break;
        }
        
        if ($prv > -1 && $sal_parts[$this->par]->itms[$prv]->fnc == 2)
            $val = ", $val)";

        switch ($this->fnc) {
            case 0:
                return "$opr$val";
            case 1:
                return "$opr$val / 100 *";
            case 2:
                return "$opr$fnc($val";
            case 3:
                return "$opr$fnc('{$sal_parts[$this->par]->name}', $val)";
            default:
                return "$opr$fnc($val)";
        }
    }

}

function prepView($tab, $bth) {
    global $sal_parts, $names, $cum_str, $row, $work_str, $iou;
    $row = $tab;
    $prep = array('Total' => 0, 'Deductions' => "", 'DedHTML' => "", 
        'sal' => array('Total' => 0), 'bon' => array('Total' => 0), 
        'tax' => array('Total' => 0), 'ded' => array('Total' => 0));
    if ($tab['InUse'] == 0 || $bth == null)
        return $prep;
    $names = explode('|', $tab['parts']);
    $typs = explode('|', $tab['typs']);
    $cmls = explode('|', $tab['cmls']);
    $ftyp = explode('|', $tab['ftyp']);
    $oprs = explode('|', $tab['oprs']);
    $fncs = explode('|', $tab['fncs']);
    $flds = explode('|', $tab['flds']);
    $wins = explode('|', $tab['wins']);
    $state = explode('|', $tab['state']);
    $vals = explode('|', $tab['tax']);
    $work_str = array($tab['worked'], $bth['dys']);

    $formulas = array('');
    for ($i = 1; $i < count($names); $i++) {
        $names[0] = floatval(_xvar_arr($vals, $i));
        $sal_parts[$i] = new SalPart();
        $formulas[$i] = $sal_parts[$i]->sal_part($names[$i], $ftyp[$i], //
                $oprs[$i], $fncs[$i], $flds[$i], $wins[$i], $state[$i], $i);
    }

    if (isset($tab['deduct']))
        $iou = Taboom($tab['deduct'], array('#~#', '$~$', '&~&'), false);
    else {
        $iou = array();
        IOU($bth['paybatchid']);
    }
    $head = "";
    $conts = "";
    $calc = array(0);
    $cols = array('tax', 'sal', 'bon', 'ded');
    for ($i = 1; $i < count($formulas); $i++) {
        for ($j = 1; $j < $i; $j++) {
            $formulas[$i] = str_replace("[$names[$j]]", $calc[$j], $formulas[$i]);
        }
        eval("\$calc[$i] = $formulas[$i];");
        $cml = $cmls[$i];
        $eval = 0;
        if ($cml > 0) {
            $col = $cum_str[$cml] == "-" ? "ded" : $cols[$typs[$i]];
            eval("\$eval = $calc[$i];");
            eval("\$prep['$col']['Total'] $cum_str[$cml]= $eval;");
            if ($eval != 0) {
                if ($cum_str[$cml] == "-") {
                    $head .= '<td class="blue-normal">' . $names[$i] . '</td>';
                    $conts .= '<td><input type="text" readonly="readonly" value="' .
                            number_format($eval, 2) . '" style="width:80px" /></td>';
                }
                if ($col == 'ded') {
                    eval("\$prep['$col']['vals']['$names[$i]'] = $eval;");
                }
                eval("\$prep['$col']['$names[$i]'] = '[$cum_str[$cml]] {$_SESSION['COY']['code']} ' . number_format($eval, 2) . ' {$_SESSION['COY']['unitcode']}';");
            }
        }
    }

    foreach ($iou as $ded) {
        $prep['ded']['Total'] -= $ded[1];
        if ($ded[1] != 0) {
            $head .= '<td class="titles" nowrap="nowrap">' . $ded[0] . '</td>';
            $conts .= '<td>' . $ded[1] . '</td>';
            $prep['ded']['vals'][$ded[0]] = $ded[1];
            $prep['ded'][$ded[0]] = "[-] {$_SESSION['COY']['code']} " . number_format($ded[1], 2) . " {$_SESSION['COY']['unitcode']}";
        }
    }
    $prep['DedHTML'] = strlen($head) == 0 ? "" :
            '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px"><tr align="center">' .
            $head . '</tr><tr>' . $conts . '</tr></table>';
    $prep['Deductions'] = TabHole($iou, array('#~#', '$~$', '&~&'));
    $prep['Total'] = ($bth['bonus'] == 1 ? $prep['bon']['Total'] : 0) +
            ($bth['salary'] == 1 ? $prep['sal']['Total'] - $prep['tax']['Total'] + $prep['ded']['Total'] : 0);
    foreach ($cols as $col) {
        $prep[$col]['Val'] = $prep[$col]['Total'];
        $prep[$col]['Total'] = "<b class=\"blue-normal\">" . $_SESSION['COY']['code'] . ' ' . number_format(abs($prep[$col]['Total']), 2) . ' ' . $_SESSION['COY']['unitcode'] . "</b>";
    }
    return $prep;
}

function Tab_Explode($str, $gum, $glue) {
    $Tab = array();
    $rows = array();
    if (strlen($str) > 0) {
        $rows = explode($gum, $str);
        foreach ($rows as $row)
            array_push($Tab, stripos($row, $glue) == false ? $row : explode($glue, $row));
    }
    return $Tab;
}

function avg($flds) {
    return array_sum($flds) / count($flds);
}

function sum($flds) {
    return array_sum($flds);
}

function ded($title, $val) {
    global $row, $iou;
    if (!isset($row['deduct']) && $row['amtbal'] < 0) {
        $val = min(abs($row['amtbal']), $val);
        array_push($iou, array($title, $val, 0));
    }
    return 0;
}

function IOU($bth) {
    global $row, $dbh, $iou;
    $sql = "SELECT `ded_id`, `Title`, `ded`, `bal` FROM `{$_SESSION['DBCoy']}`.`deductions`
            WHERE `VendorID`={$row['VendorID']}
                AND `paybatchid`=$bth
                AND `bal`>0";
    $Ded = getDBData($dbh, $sql);
    foreach ($Ded as $deduct) {
        $val = min($deduct['ded'], $deduct['bal']);
        array_push($iou, array($deduct['Title'], $val, $deduct['ded_id']));
    }
}

function grd($grades, $def, $val) {
    $tot = 0;
    $gradtot = 0;
    foreach ($grades as $per => $grade) {
        if ($val < $gradtot)
            break;
        $gradtot += $grade;
        $tot += $per / 100 * ($val >= $gradtot ? $grade : $val - $gradtot + $grade);
    }
    if ($val > $gradtot)
        $tot += $def / 100 * ($val - $gradtot);
    return $tot;
}

?>
