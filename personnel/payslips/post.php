<?php

require_once('../../scripts/init.php');
vetAccess('Personnel', 'Pay Slips', 'Post');

$id = intval(_xget('id'));
$sql = "UPDATE `{$_SESSION['DBCoy']}`.`paybatch` SET `posted`=1, `dategen`=NOW(), `staffid`='{$_SESSION['ids']['VendorID']}' 
    WHERE paybatchid={$id} AND `posted`=0";
$post = runDBQry($dbh, $sql);
if ($post > 0) {
    $sql = "INSERT INTO `{$_SESSION['DBCoy']}`.`payslip`(`paybatchid`, `staffid`, `salaryid`, `details`, `worked`, `code`) 
        SELECT {$id}, `VendorID`, `salary`, `tax`,`worked`,
            CONCAT_WS('@@@',`salary_name`,`parts`,`typs`,`cmls`,`ftyp`,`oprs`,`fncs`,`flds`,`wins`,`state`,`deduct`)
        FROM `{$_SESSION['DBCoy']}`.`vendors` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`salaryscale` ON `vendors`.`salary`=`salaryscale`.`salary_id` 
        WHERE `VendorType`=5 AND `InUse`=1";
    runDBQry($dbh, $sql);

    $sql = "SELECT `VendorID`, `deduct` FROM `{$_SESSION['DBCoy']}`.`vendors`
            WHERE `VendorType`=5 AND `InUse`=1";
    $Ded = getDBData($dbh, $sql);
    foreach ($Ded as $deduct) {
        $iou = Taboom($deduct['deduct'], array('#~#', '$~$', '&~&'), false);
        $tot = 0;
        foreach ($iou as $ded) {
            $val = GSQLStr(_xpost('deduct'), "doublev");
            $bal = floatval(getDBDataFldkey($dbh, 'vendors', 'VendorID', 'amtbal', $deduct['VendorID'])) - $val;
            $amt = "$val";
            $sql = "INSERT INTO `{$_SESSION['DBCoy']}`.`deductions` (`paybatchid`, `Title`, `par_id`, `VendorID`, `deduct`) 
                    VALUES ({$id}, '$ded[0]', $ded[2], {$deduct['VendorID']}, '$ded[1]')";
            runDBqry($dbh, $sql);
            $tot += $ded[1];
        }

        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET `amtbal`=`amtbal`+$tot
                WHERE `VendorID`={$deduct['VendorID']}";
        runDBqry($dbh, $sql);
    }
}
header("Location: view.php?id=$id");
?>
