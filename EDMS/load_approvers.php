<?php
require_once('../scripts/init.php');
$approvers = array();
$sup_ids = '';
$approve = '';
$supo = $_SESSION['EmployeeID'];

do {
    $sup_ids .= ",$supo";
    $approve .= ",0";
    $sql = "SELECT `VendorID`, `supervisor`, $vendor_sql "
            . "FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE `VendorID`=$supo";
    $supv = getDBDataRow($dbh, $sql);
    if ($supv) {
        array_push($approvers, $supv);
        $supo = $supv['supervisor'];
    }
} while ($supv && intval($supo) > 0);
?>
<script language="JavaScript1.2" type="text/javascript">
    $('.approvals').empty();
    $('#approvals').val("<?php echo "$sup_ids|$approve" ?>");
<?php foreach ($approvers as $approver) { ?>
        approver(<?php echo $approver['VendorID'] ?>, "<?php echo $approver['VendorName'] ?>");
<?php } ?>
</script>
