<?php require_once('../scripts/init.php');

$id = intval(_xget('id'));
$sql = "SELECT `tmpl_det` FROM `{$_SESSION['DBCoy']}`.`edms_tmpl` WHERE `tmpl_id`=$id";
$row_TEdms_tmpl = getDBDataRow($dbh, $sql);

?>
<script language="JavaScript1.2" type="text/javascript">
    $('#cmp_list').empty();
<?php
$lis = explode(PHP_EOL, $row_TEdms_tmpl['tmpl_det']);
foreach ($lis as $li) { ?>
    showCmp("<?= addcslashes($li, '"') ?>");
<?php } ?>
</script>