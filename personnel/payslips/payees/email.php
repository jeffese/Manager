<?php
require_once('../../../scripts/init.php');

if (!vetAccess('Personnel', 'Pay Slips', 'Dispatch', false)) {
    ?>
    <script>alert('Access Denied!')</script>
    <?php
    exit;
}

$id = intval(_xget('id'));
$bth = intval(_xget('bth'));
$sql = "SELECT `VendorID`, `EmailAddress`, `ContactTitle`, `ContactFirstName`, `ContactMidName`, 
            $vendor_sql, `ContactLastName`, `payslip`.*,  `payday`
            FROM `{$_SESSION['DBCoy']}`.`vendors` 
            INNER JOIN `{$_SESSION['DBCoy']}`.`payslip` ON `vendors`.`VendorID`=`payslip`.`staffid`
            INNER JOIN `{$_SESSION['DBCoy']}`.`paybatch` ON `payslip`.`paybatchid`=`paybatch`.`paybatchid`
            WHERE `payslip_id`=$id";
$row_TEmployees = getDBDataRow($dbh, $sql);
$body = "Find attached your payslip detailing the transfers made to your account for the above subject matter.";

mailPDF("/personnel/payslips/payees/slip.php?id=$id&bth=$bth", false, $body, 
        $row_TEmployees['EmailAddress'], $row_TEmployees['VendorName'], $row_TEmployees['payday']);
?>
