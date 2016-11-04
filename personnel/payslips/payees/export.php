<?php

require_once('../../../scripts/init.php');
require_once '../../../lib/Excel/PHPExcel.php';
require_once("paygen.php");
vetAccess('Personnel', 'Pay Slips', 'Print');

preOrd("paylst", array('InUse', 'vendorcode', 'VendorName', 'ClientType', 'InUse', 'worked', 'ref_no', 'salary_name'));

$id = intval(_xget('id'));
$sql = "SELECT *, DATEDIFF(`dtto`, `dtfrom`)+1 AS `dys` FROM `{$_SESSION['DBCoy']}`.`paybatch` WHERE paybatchid=$id";
$row_TPay = getDBDataRow($dbh, $sql);
$tax = $row_TPay['posted'] == 0 ? "`contract` AS `ref_no`, `salaryscale`.*, `vendors`.`worked`, `VendorID` AS payslip_id," :
        "`ref_no`, `code`, `payslip_id`, `payslip`.`worked`, salary_name, `details` AS";
$ijn = $row_TPay['posted'] == 0 ? "" : "INNER JOIN `{$_SESSION['DBCoy']}`.`payslip` ON `vendors`.`VendorID`=`payslip`.`staffid`";
$where = $row_TPay['posted'] == 0 ? "`VendorType`=5 AND `InUse`=1" : "`paybatchid`=$id";

$sql = "SELECT `VendorID`, `vendorcode`, $vendor_sql, ClientType, 
    `amtbal`, `InUse`, $tax `tax`
    FROM `{$_SESSION['DBCoy']}`.`vendors` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`salaryscale` ON `vendors`.`salary`=`salaryscale`.`salary_id`
    $ijn
    WHERE $where $orderval";
$TEmployees = getDBData($dbh, $sql);

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator($_SESSION['COY']['CoyName'])
        ->setLastModifiedBy($_SESSION['userid'])
        ->setTitle("Payslip Sheet - [{$row_TPay['dtfrom']}] ==> [{$row_TPay['dtto']}]")
        ->setSubject($row_TPay['payday'])
        ->setDescription("Salary sheet for {$_SESSION['COY']['CoyName']} employees.")
        ->setKeywords("Payslip Salary")
        ->setCategory("Salaries");

$dtnow = date('d/m/Y');
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A1", 'Staff No.')
        ->setCellValue("B1", 'Name')
        ->setCellValue("C1", 'Type')
        ->setCellValue("D1", 'Worked')
        ->setCellValue("E1", 'Ref. No.')
        ->setCellValue("F1", 'Salary Package')
        ->setCellValue("G1", 'Salary')
        ->setCellValue("H1", 'Bonus')
        ->setCellValue("I1", 'Deductions')
        ->setCellValue("J1", 'Tax')
        ->setCellValue("K1", 'Total');
$cols = array("L", "M", "N", "O", "P", "Q");

$i = 2;
foreach ($TEmployees as $row_TEmployees) {
    if ($row_TPay['posted'] == 1) {
        $codes = explode('@@@', $row_TEmployees['code']);
        $row_TEmployees['parts'] = $codes[1];
        $row_TEmployees['typs'] = $codes[2];
        $row_TEmployees['cmls'] = $codes[3];
        $row_TEmployees['ftyp'] = $codes[4];
        $row_TEmployees['oprs'] = $codes[5];
        $row_TEmployees['fncs'] = $codes[6];
        $row_TEmployees['flds'] = $codes[7];
        $row_TEmployees['wins'] = $codes[8];
        $row_TEmployees['state'] = $codes[9];
        $row_TEmployees['deduct'] = $codes[10];
        $row_TEmployees['InUse'] = 1;
    }
    $prep = prepView($row_TEmployees, $row_TPay);

    switch ($row_TEmployees['ClientType']) {
        case 1:$typ = "Admin";
            break;
        case 2:$typ = "Engineer";
            break;
        default :$typ = "";
    }

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$i", $row_TEmployees['vendorcode'])
            ->setCellValue("B$i", $row_TEmployees['VendorName'])
            ->setCellValue("C$i", $typ)
            ->setCellValue("D$i", $row_TEmployees['worked'])
            ->setCellValue("E$i", $row_TEmployees['ref_no'])
            ->setCellValue("F$i", strbrief($row_TEmployees['salary_name'], 10))
            ->setCellValue("G$i", $prep['sal']['Val'])
            ->setCellValue("H$i", $prep['bon']['Val'])
            ->setCellValue("I$i", abs($prep['ded']['Val']))
            ->setCellValue("J$i", $prep['tax']['Val'])
            ->setCellValue("K$i", $prep['Total']);
    foreach ($prep['ded']['vals'] as $key => $val) {
        $t = 0;
        $found = false;
        do {
            if ($objPHPExcel->getActiveSheet()->getCell("$cols[$t]1")->getValue() == $key)
                $found = true;
            elseif (strlen($objPHPExcel->getActiveSheet()->getCell("$cols[$t]1")->getValue()) == 0) {
                $objPHPExcel->getActiveSheet()->setCellValue("$cols[$t]1", $key);
                $found = true;
            }
        } while (!$found && $t++ < 6);
        $objPHPExcel->getActiveSheet()->setCellValue("$cols[$t]$i", $val);
        $objPHPExcel->getActiveSheet()->getStyle("$cols[$t]$i")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    }
    $objPHPExcel->getActiveSheet()->getStyle("G$i")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle("H$i")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle("I$i")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle("J$i")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle("K$i")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $i++;
}

$objPHPExcel->getActiveSheet()->setTitle('Salaries Payslips');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="payslip_' . $row_TPay['paybatchid'] . '_' . $row_TPay['payday'] . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;



