<?php require_once("../../scripts/init.php");
vetAccess('Accounts', 'Sales', 'Del');
$id = intval(_xget('id'));

$outid = _xses('OutletID');
try {
    $dbh->autocommit(FALSE);
    
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`invoicedetails` WHERE InvoiceID=$id";
    runDBQry($dbh, $sql);

    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`invoices` 
        WHERE InvoiceID=$id AND Posted=0 AND OutletID IN ($outid)";
    $del = runDBQry($dbh, $sql);
    
    if ($del != 1) {
        throw new Exception("Not updated");
    }
    $dbh->commit();
    delDocs('Accounts', 'Sales', $id);
    header("Location: index.php");
} catch (Exception $ex) {
    $dbh->rollback();
    header("Location: view.php?id=$id");
}
$dbh->autocommit(TRUE);

?>
