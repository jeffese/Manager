<?php

require_once('../../scripts/init.php');
$id = intval(_xget('id'));
$doc = getDBDataRow($dbh, "SELECT `OwnerID`, `shelf`, `fname`, `FileName` 
        FROM {$_SESSION['DBCoy']}.`documentfiles`
        WHERE `DocID`=$id");
$fname = DOC_ARCHV . $_SESSION['coyid'] . $doc['shelf'] . DS . $doc['OwnerID'] . DS . $doc['fname'];
$ext = getFileExtension($fname);
header("Content-Type: unknown/$ext");
header("Cache-Control: no-cache");
header("Accept-Ranges: none");
header("Content-Disposition: attachment; filename=\"{$doc['FileName']}\"");
readfile($fname);
?>
