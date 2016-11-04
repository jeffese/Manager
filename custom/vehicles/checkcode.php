<html>
    <head></head>
    <script language="JavaScript" type="text/javascript">
<?php
require_once('../../scripts/init.php');
$fld = _xget('fld');
$val = _xget('code');
if (strlen($val) > 0 && isExist($dbh, $fld, "'$val'", "`{$_SESSION['DBCoy']}`.`assets`")) {
    echo "parent.replycheck(parent.document.frmAsset.$fld, false, true, 'Value is already used for another Vehicle');";
} else {
    echo "parent.replycheck(parent.document.frmAsset.$fld, true, false, '');";
}
?>
    </script>
</html>