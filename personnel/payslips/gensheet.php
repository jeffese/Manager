<?php

require_once '../../lib/Excel/PHPExcel/IOFactory.php';

$objPHPExcel = PHPExcel_IOFactory::load($file);

$i = 2;
$j = 0;
$tab = array();
$import = true;

if ($row_TPay['bonus'] == 1) {
    $rescol = array();
    $cols = array("E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
    $k = 0;
    while ($k < count($cols)) {
        $col = trim($objPHPExcel->getActiveSheet()->getCell("{$cols[$k]}1")->getValue());
        if (strlen($col) == 0)
            break;
        array_push($rescol, $col);
        $k++;
    }
}

while (strlen(trim($objPHPExcel->getActiveSheet()->getCell("A$i")->getValue())) > 0) {
    $po1 = trim($objPHPExcel->getActiveSheet()->getCell("C$i")->getValue());
    $po2 = trim($objPHPExcel->getActiveSheet()->getCell("D$i")->getValue());
    if (strlen($po1) == 0 || strlen($po2) == 0) {
        array_push($errors, array("Error", "No PO No. at Row: $i!"));
        $import = false;
        break;
    }

    $tab[$j]['id'] = trim($objPHPExcel->getActiveSheet()->getCell("A$i")->getValue());
    $tab[$j]['po'] = "$po1/$po2";

    if ($row_TPay['bonus'] == 1) {
        $tab[$j]['bonus'] = array();
        for ($k = 0; $k < count($rescol); $k++) {
            $res = trim($objPHPExcel->getActiveSheet()->getCell("$cols[$k]$i")->getCalculatedValue());
            if (is_numeric($res) || strlen($res) == 0)
                $tab[$j]['bonus'][$rescol[$k]] = floatval($res);
            else {
                array_push($errors, array("Error", "Numeric value or Blank expected for $rescol[$k] at $cols[$k] $i!"));
                $import = false;
//                break 2;
            }
        }
    }

    $sql = sprintf("SELECT `VendorID`, `tax`, `parts`
                            FROM `{$_SESSION['DBCoy']}`.`vendors` 
                            INNER JOIN `{$_SESSION['DBCoy']}`.`salaryscale` ON `vendors`.`salary` = `salaryscale`.`salary_id`
                            WHERE `VendorType`=5 AND vendorcode=%s", GSQLStr($tab[$j]['id'], "text"));
    $row_TEmployees = getDBDataRow($dbh, $sql);
    if ($row_TEmployees == null) {
        array_push($errors, array("Error", "No Employee with Code {$tab[$j]['id']} at Row: $i!"));
        $import = false;
    } elseif ($row_TPay['bonus'] == 1) {
        $names = explode('|', $row_TEmployees['parts']);
        $vals = array_fill(0, count($names), 0);

        foreach ($tab[$j]['bonus'] as $key => $bon) {
            $idx = array_search($key, $names);
            if ($idx !== false)
                $vals[$idx] = $bon;
            elseif ($bon > 0) {
                $Name = trim($objPHPExcel->getActiveSheet()->getCell("B$i")->getValue());
                array_push($errors, array("Error", "'$key' not found in Package for '$Name' at Row $i!"));
                $import = false;
            }
        }
    }

    $i++;
    $j++;
}

if ($import) {
    $i = 1;
    foreach ($tab as $row) {
        $i++;
        $sql = sprintf("SELECT `VendorID`, `tax`, `parts`
                            FROM `{$_SESSION['DBCoy']}`.`vendors` 
                            INNER JOIN `{$_SESSION['DBCoy']}`.`salaryscale` ON `vendors`.`salary` = `salaryscale`.`salary_id`
                            WHERE `VendorType`=5 AND vendorcode=%s", GSQLStr($row['id'], "text"));
        $row_TEmployees = getDBDataRow($dbh, $sql);
        $tax = "";
        if ($row_TPay['bonus'] == 1) {
            $names = explode('|', $row_TEmployees['parts']);
            $vals = array_fill(0, count($names), 0);

            foreach ($row['bonus'] as $key => $bon) {
                $idx = array_search($key, $names);
                if ($idx !== false)
                    $vals[$idx] = $bon;
            }
            $tval = implode('|', $vals);
            $tax = ", tax='$tval'";
        }
        $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET InUse=1, `contract`=%s $tax WHERE vendorcode=%s", GSQLStr($row['po'], "text"), GSQLStr($row['id'], "text"));
        runDBQry($dbh, $sql);
    }
}

//$objPHPExcel->getActiveSheet()->getCell('B5')->getValue();
//
//$data->read($email_list);
//
//if ($data->sheets[0]['numCols'] != 2) {
//    echo "Sheet should have only 2 columns";
//    exit;
//}
//if ($data->sheets[0]['numRows'] < 2) {
//    echo "Sheet is empty";
//    exit;
//}
//
//for ($j = 2; $j <= $data->sheets[0]['numRows']; $j++) {
//    $value = $data->sheets[0]['cells'][$j][1];
//    if (filter_var($value, FILTER_VALIDATE_EMAIL) === FALSE) {
//        array_push($bad_rcp, $value);
//    } else {
//        array_push($Recipients, array($value, $data->sheets[0]['cells'][$j][2]));
//    }
//}
?>
