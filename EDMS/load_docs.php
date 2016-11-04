<?php
require_once('../scripts/init.php');

$sql = "SELECT `doc_id`, `docname` FROM `{$_SESSION['DBCoy']}`.`edms` ORDER BY `docname`";
$TDoc = getDBData($dbh, $sql);
?>
<script language="JavaScript1.2" type="text/javascript">
    var elem = $('#doc_cmb_' + <?php echo _xget('c') ?>);
<?php foreach ($TDoc as $row_TDoc) { ?>
        elem.append($('<option>', {
            value: <?php echo $row_TDoc['doc_id'] ?>,
            text: "<?php echo $row_TDoc['docname'] ?>"
        }));
<?php } ?>
</script>