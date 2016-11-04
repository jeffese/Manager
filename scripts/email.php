<?php

require_once('init.php');

$email = getDBDataRow($dbh, "SELECT `EmailAddress`, $vendor_sql 
    FROM `{$_SESSION['DBCoy']}`.`vendors` 
    WHERE `VendorID`=" . intval(_xget('vid')));

mailPDF(_xget('url'), false, _xget('body'), $email['EmailAddress'], $email['VendorName'], _xget('title'));
?>
