<?php
require_once('../../scripts/init.php');
vetAccess('Personnel', 'Pay Slips', 'Unlock');

$id = intval(_xget('id'));
$sql = "UPDATE `{$_SESSION['DBCoy']}`.`paybatch` SET `posted`=0, `staffid`='{$_SESSION['ids']['VendorID']}' 
    WHERE paybatchid={$id} AND `posted`=1";
$post = runDBQry($dbh, $sql);
if ($post > 0) {
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`payslip` WHERE `paybatchid`={$id}";
    runDBQry($dbh, $sql);
    
    $sql = "SELECT `VendorID`, SUM(`deduct`) AS `deds` FROM `{$_SESSION['DBCoy']}`.`deductions`
             WHERE `paybatchid`={$id}
             GROUP BY `VendorID`";
    $Ded = getDBData($dbh, $sql);
    foreach ($Ded as $deduct) {
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET `amtbal`=`amtbal`-{$deduct['deds']}
                WHERE `VendorID`={$deduct['VendorID']}";
        runDBqry($dbh, $sql);
    }
    
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`deductions` WHERE `paybatchid`={$id}";
    runDBQry($dbh, $sql);
}
header("Location: view.php?id=$id");
?>
