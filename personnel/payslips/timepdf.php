<?php
require_once('../../scripts/init.php');

if (!vetAccess('Personnel', 'Pay Slips', 'Dispatch', false)) {
    ?>
    <script>alert('Access Denied!')</script>
    <?php
    exit;
}

$id = intval(_xget('id'));
$bth = intval(_xget('bth'));
genPDF("/personnel/payslips/timeprint.php?id=$id&bth=$bth", "TimeSheet_$id", true, true);

?>
