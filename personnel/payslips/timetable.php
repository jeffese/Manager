<?php

require_once('../../scripts/init.php');
require_once '../../lib/Excel/PHPExcel.php';

if (!vetAccess('Personnel', 'Pay Slips', 'Dispatch', false)) {
    ?>
    <script>alert('Access Denied!')</script>
    <?php

    exit;
}

$po = _xget('id');
$bth = intval(_xget('bth'));

$sql = "SELECT `dtfrom`, `dtto`, DATEDIFF(`dtto`, `dtfrom`)+1 AS `dys`, posted,
    DATE_FORMAT(`dtfrom`,'%b %Y') AS `mth`, DATE_FORMAT(`dtfrom`,'%b-%Y') AS `_mth` 
    FROM `{$_SESSION['DBCoy']}`.`paybatch` WHERE paybatchid=$bth";
$row_TPay = getDBDataRow($dbh, $sql);

$vendor_supo = vendorFlds("VendorSup", "supo");
$fld = $row_TPay['posted'] == 0 ? '`vendors`.`contract`' : '`ref_no`';
$join = $row_TPay['posted'] == 0 ? '' : 'INNER JOIN `payslip` ON `vendors`.`VendorID`=`payslip`.`staffid`';
$sql = "SELECT $vendor_sql, `vendors`.`MobilePhone`, `salary_name`, 
            `loc`.`Category` AS `loc`, `vendors`.Discount, $vendor_supo
            FROM `{$_SESSION['DBCoy']}`.`vendors` 
            $join
            LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `VendorSup` ON `vendors`.supervisor = VendorSup.VendorID 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`salaryscale` ON `vendors`.salary = salaryscale.salary_id 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`status` loc ON `vendors`.FaxNumber = loc.CategoryID 
            WHERE $fld='$po'";
$TEmployees = getDBData($dbh, $sql);

$sql = "SELECT proj_name, $vendor_sql
        FROM `{$_SESSION['DBCoy']}`.`projects`
	LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `projects`.kad = vendors.VendorID
        WHERE `proj_id`={$TEmployees[0]['Discount']}";
$TProj = getDBDatarow($dbh, $sql);
$proj = $TProj['proj_name'];

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator($_SESSION['COY']['CoyName'])
        ->setLastModifiedBy($_SESSION['userid'])
        ->setTitle("Time Sheet - [{$row_TPay['dtfrom']}] ==> [{$row_TPay['dtto']}]")
        ->setSubject($TProj['proj_name'])
        ->setDescription("Time Sheet for {$TProj['proj_name']} - [PO: $po]")
        ->setKeywords("$po " . $TProj['proj_name'])
        ->setCategory("Time Sheet");

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objDrawing->setName("Logo");
$objDrawing->setDescription("{$_SESSION['COY']['CoyName']} Logo");
$objDrawing->setPath('../..' . COYPIX_DIR . $_SESSION['coyid'] . "/xxpix.jpg");
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(1);
$objDrawing->setOffsetY(6);

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("A1:C2")
        ->mergeCells("D1:J1")
        ->mergeCells("D2:J2")
        ->mergeCells("B3:C3")
        ->mergeCells("B4:C4")
        ->setCellValue("D1", $_SESSION['COY']['CoyName'])
        ->setCellValue("D2", 'MONTHLY PROJECT WORKING HOUR TIMESHEET')
        ->setCellValue("B3", 'Project Name')
        ->setCellValue("D3", 'Code')
        ->setCellValue("E3", 'Key Account Director')
        ->setCellValue("F3", 'Signature')
        ->setCellValue("H3", 'Period')
        ->setCellValue("B4", $TProj['proj_name'])
        ->setCellValue("D4", "$po/")
        ->setCellValue("E4", $TProj['VendorName'])
        ->setCellValue("H4", $row_TPay['mth'])
        ->setCellValue("A6", '#')
        ->setCellValue("B6", 'Name')
        ->setCellValue("C6", 'Phone')
        ->setCellValue("D6", 'Salary Package')
        ->setCellValue("E6", 'Location')
        ->setCellValue("F6", 'Start Date')
        ->setCellValue("G6", 'End Date')
        ->setCellValue("H6", 'Work Days')
        ->setCellValue("I6", 'Project Manager')
        ->setCellValue("J6", 'Signature');
$i = 7;
foreach ($TEmployees as $row_TEmployees) {
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$i", $i - 6)
            ->setCellValue("B$i", $row_TEmployees['VendorName'])
            ->setCellValue("C$i", $row_TEmployees['MobilePhone'])
            ->setCellValue("D$i", $row_TEmployees['salary_name'])
            ->setCellValue("E$i", $row_TEmployees['loc'])
            ->setCellValue("F$i", $row_TPay['dtfrom'])
            ->setCellValue("G$i", $row_TPay['dtto'])
            ->setCellValue("H$i", $row_TPay['dys'])
            ->setCellValue("I$i", $row_TEmployees['supo']);
    $objPHPExcel->getActiveSheet()->getStyle("F$i")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX16);
    $i++;
}

$objPHPExcel->getActiveSheet()->setTitle("{$row_TPay['mth']} Time Sheet");
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="TimeSheet_' . $row_TPay['_mth'] . '_' . $po . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;



