<?php

require_once('../../scripts/init.php');
require_once '../../lib/Excel/PHPExcel.php';
require_once("payees/paygen.php");

if (!vetAccess('Personnel', 'Pay Slips', 'Dispatch', false)) {
    ?>
    <script>alert('Access Denied!')</script>
    <?php

    exit;
}

$id = intval(_xget('id'));
$sql = "SELECT *, DATEDIFF(`dtto`, `dtfrom`)+1 AS `dys` FROM `{$_SESSION['DBCoy']}`.`paybatch` WHERE paybatchid=$id";
$row_TPay = getDBDataRow($dbh, $sql);

$sql = "SELECT `VendorID`, `vendorcode`, `amtbal`, `logofile`,
    `ContactFirstName`, `ContactLastName`, `payslip`.`worked`,
    `InUse`, `payslip_id`, `details` AS `tax`, `payslip`.`code`, `bank`.`code` AS `bank`
    FROM `{$_SESSION['DBCoy']}`.`vendors` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`payslip` ON `vendors`.`VendorID`=`payslip`.`staffid`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status` `bank` ON `vendors`.`bank` = `bank`.`CategoryID` 
    WHERE `paybatchid`=$id";
$TEmployees = getDBData($dbh, $sql);
$bank = _xget('bank');
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator($_SESSION['COY']['CoyName'])
        ->setLastModifiedBy($_SESSION['userid'])
        ->setTitle("$bank Excel Sheet - [{$row_TPay['dtfrom']}] ==> [{$row_TPay['dtto']}]")
        ->setSubject($row_TPay['payday'])
        ->setDescription("Salary sheet for {$_SESSION['COY']['CoyName']} employees.")
        ->setKeywords("$bank Salary")
        ->setCategory("Salaries");

$i = 1;
foreach ($TEmployees as $row_TEmployees) {
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
    $prep = prepView($row_TEmployees, $row_TPay);
    if ($bank == "REMITA") {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i", "'{$row_TPay['paybatchid']}")
                ->setCellValue("B$i", $row_TEmployees['ContactFirstName'])
                ->setCellValue("C$i", $row_TEmployees['ContactLastName'])
                ->setCellValue("D$i", $prep['Total'])
                ->setCellValue("E$i", "'{$row_TEmployees['bank']}")
                ->setCellValue("F$i", "'{$row_TEmployees['logofile']}")
                ->setCellValue("G$i", $_SESSION['COY']['admin_mail'])
                ->setCellValue("H$i", "'{$_SESSION['COY']['gateway']}")
                ->setCellValue("I$i", 10);
        $objPHPExcel->getActiveSheet()->getStyle("D$i")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
    } elseif ($bank == "FCMB") {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i", "INST")
                ->setCellValue("B$i", "P")
                ->setCellValue("C$i", "LIGHT")
                ->setCellValue("D$i", "FCMB_INT_FUNDS_TRF")
                ->setCellValue("E$i", "'0674373016")
                ->setCellValue("F$i", "{$row_TEmployees['ContactFirstName']} {$row_TEmployees['ContactLastName']}")
                ->setCellValue("G$i", "'{$row_TEmployees['logofile']}")
                ->setCellValue("H$i", $prep['Total'])
                ->setCellValue("I$i", date('d/m/Y'))
                ->setCellValue("J$i", "N")
                ->setCellValue("K$i", "NGN")
                ->setCellValue("L$i", $row_TPay['payday'])
                ->setCellValue("M$i", "'{$row_TPay['paybatchid']}")
                ->setCellValue("N$i", "OUR")
                ->setCellValue("O$i", $row_TPay['payday']);
        $objPHPExcel->getActiveSheet()->getStyle("I$i")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
        $objPHPExcel->getActiveSheet()->getStyle("H$i")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
    }
    $i++;
}

$objPHPExcel->getActiveSheet()->setTitle('Salaries');

//$objPHPExcel->getSecurity()->setLockWindows(true);
//$objPHPExcel->getSecurity()->setLockStructure(true);
//$objPHPExcel->getSecurity()->setWorkbookPassword(Rand_chr(12));
//
//$objPHPExcel->getActiveSheet()->getProtection()->setPassword(Rand_chr(12));
//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
//$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $bank . '_' . $row_TPay['paybatchid'] . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;



