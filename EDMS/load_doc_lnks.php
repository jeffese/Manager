<?php
require_once('../scripts/init.php');
$docs = _xget('docs');
$sql = "SELECT doc_id, docname FROM `{$_SESSION['DBCoy']}`.`edms` WHERE doc_id IN (0$docs) ORDER BY `docname`";
$TDocs = getDBData($dbh, $sql);
?>
<script language="JavaScript1.2" type="text/javascript">
    var elem = $('#docs_' + <?php echo _xget('c') ?>);
<?php foreach ($TDocs as $row_TDocs) { ?>
        elem.append(docLnk(<?php echo $row_TDocs['doc_id'] ?>, 
        "<?php echo addcslashes($row_TDocs['docname'], "\"") ?>" ));
<?php } ?>
</script>