<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Categories'));
vetAccess('Accounts', 'Categories', 'Print');

require_once('ledger_fncs.php');
require_once ROOT . '/lib/Excel/PHPExcel.php';

$from = _xget("s");
$to = _xget("e");
$title = "Ledger from $from --> $to";

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator($_SESSION['COY']['CoyName'])
        ->setLastModifiedBy($_SESSION['userid'])
        ->setTitle("Ledger Export");

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objDrawing->setName("Logo");
$objDrawing->setDescription("{$_SESSION['COY']['CoyName']} Logo");
$objDrawing->setPath('../..' . COYPIX_DIR . $_SESSION['coyid'] . "/xxpix.jpg");
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(1);
$objDrawing->setOffsetY(6);

$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells("A1:C6")
        ->mergeCells("D1:J1")
        ->mergeCells("D2:J2")
        ->mergeCells("B3:C3")
        ->mergeCells("B4:C4")
        ->setCellValue("D1", $_SESSION['COY']['CoyName'])
        ->setCellValue("D2", $title);
$h = 1;
$header = array("Type", "Client Type", "Client", "Date", "Trans. #", "Debit", "Credit");
$cols = array("", "A", "B", "C", "D", "E", "F", "G");
foreach ($header as $key => $col) {
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cols[$h]8", $col);
    $h++;
}

$i = 9;

function printRow($row, $gap, $grp) {
    global $i, $objPHPExcel;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i", gapTitle($grp, $gap, $row['Title'], " "));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i", $row['VType']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i", $row['VendorName']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i", $row['TransDate']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i", $row['LedgerID']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i", number_format($row['Debit'], 2, '.', ','));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i", number_format($row['Credit'], 2, '.', ','));
    $i++;
}

genCat("7", "7", 0);
$sums = getAggr("7");

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i", "Total:");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i", number_format($sums['Debits'], 2, '.', ','));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i", number_format($sums['Credits'], 2, '.', ','));

//$objPHPExcel->getActiveSheet()->setTitle($title);
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $title . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;

