<?php

require_once('../scripts/init.php');
//echo hashPwd(_xget('p'), _xget('u'));
echo _xget('p'), _xget('u');
//$sql = "SELECT `VendorID`, `vendorcode`, `picturefile`, `signfile` FROM `exood_coy1`.`staff`";
//$TEmployees = getDBData($dbh, $sql);
//$dir = "D:/My Work Files/Globe Motors/Companies/1/Employee Pictures/";
//
//foreach ($TEmployees as $staff) {
//    $pdir = "{$dir}pictures/{$staff['VendorID']}/";
//    mkdir($pdir);
//    resampimagejpg(600, 600, $dir.$staff['picturefile'], "{$pdir}1.jpg", 10);
//    resampimagejpg(200, 200, $dir.$staff['picturefile'], "{$pdir}x1.jpg", 10);
//    copy("{$dir}Signature/{$staff['signfile']}", "$pdir/sign.jpg");
//}
//genLic();
//unlink(ROOT."\\tmp\\sql.*");
//$forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp, $pext = "")
//echo Implode_3($_SESSION['license']);
//runDBqry($dbh, "USE `exood_coy1`");
//$tabs = getDBData($dbh, "SHOW TABLES");
//log_die($tabs);
//foreach ($tabs as $tab) {
//    set_time_limit(30);
//    runDBqry($dbh, "ALTER TABLE `{$tab['Tables_in_exood_coy1']}` ENGINE = INNODB");
//}
/*
  echo "chown -R apache:apache ".ROOT.DS."\n
  echo '' > ".ERRORS."\n
  rm -rf ".STUDPIX_DIR."*\n
  rm -rf ".PARENT_PIX_DIR."*\n
  rm -rf ".COYPIX_DIR."*\n
  mkdir ".COYPIX_DIR."signature\n
  rm -rf ".ASSETPIX_DIR."*\n
  rm -rf ".CLIENTPIX_DIR."*\n
  rm -rf ".STAFFPIX_DIR."*\n
  rm -rf ".PRODPIX_DIR."*\n
  rm -rf ".DOC_ARCHV."*\n
  chmod    777 ".ERRORS."\n
  chmod -R 777 ".STUDPIX_DIR."\n
  chmod -R 777 ".PARENT_PIX_DIR."\n
  chmod -R 777 ".COYPIX_DIR."\n
  chmod -R 777 ".ASSETPIX_DIR."\n
  chmod -R 777 ".CLIENTPIX_DIR."\n
  chmod -R 777 ".STAFFPIX_DIR."\n
  chmod -R 777 ".PRODPIX_DIR;
 */
//echo phpinfo();
//$printer = "\\XP-58";
//if ($ph = printer_open($printer)) {
//    // Get file contents 
////    $fh = fopen("filename.ext", "rb");
////    $content = fread($fh, filesize("filename.ext"));
////    fclose($fh);
//
//    // Set print mode to RAW and send PDF to printer 
//    printer_set_option($ph, PRINTER_MODE, "RAW");
//    printer_write($ph, "Test");
//    printer_close($ph);
//} else
//    "Couldn't connect..."; 
//$f = 'print-3267.html';
//echo substr($f, 6, stripos($f, '.') - 6);
//$tm = strtotime('2016-2-10 16:00:00') - time();
//    echo $tm;
//require_once 'lab_1.php';

/*$sms_vars = getDBDataRow($dbh, 
        "SELECT `SrvSchedID`, `enddate`, `items_srv_sched`.`MachineTime`, `licenceno`, '08034434343' AS `PhoneNumber` 
        FROM `{$_SESSION['DBCoy']}`.`items_srv_sched` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails` ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv` ON `invoicedetails`.ProductID=`items_srv`.`ServiceID` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`invoices` ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `invoices`.VendorID=`vendors`.VendorID 
        INNER JOIN `{$_SESSION['DBCoy']}`.`assets` ON `items_srv_sched`.AssetID=`assets`.`AssetID` 
        WHERE `invoicedetails`.`InvoiceID`=1000010 AND `ProductID`=61");
include_once '../scripts/sms.php';*/
