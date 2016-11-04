<html>
    <head></head>
    <script language="JavaScript" type="text/javascript">
<?php
require_once('../../scripts/init.php');
if (isExist($dbh, 'vendorcode', GSQLStr(_xget('code'), "text"), "`{$_SESSION['DBCoy']}`.`vendors`")) {
    echo "parent.replycheck(parent.document.frmclient.vendorcode, false, true, 'Code is already used for another Client');";
} else {
    echo "parent.replycheck(parent.document.frmclient.vendorcode, true, false, '');";
}
?>
    </script>
</html>