<?php
require_once('../scripts/init.php');

$TCat = getClassify(1);
?>
<script language="JavaScript1.2" type="text/javascript">
    var elem = $('#cmp_' + <?php echo _xget('c') ?>);
        elem.append($('<option>', {value: 0, text: ""}));
<?php foreach ($TCat as $row_TCat) { ?>
        elem.append($('<option>', {
            value: <?php echo $row_TCat['catID'] ?>,
            text: "<?php echo addcslashes(str_replace('&gt;', '>', $row_TCat['catname']), "\"") ?>"
        }));
<?php } ?>
    elem.val(elem.attr('val'));
</script>
