<?php require_once("../../scripts/init.php");
vetAccess('Stock', 'Transfers', 'Del');
$id = intval(_xget('id'));

try {
    $dbh->autocommit(FALSE);
    
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`req_items` WHERE RequisitID=$id";
    runDBQry($dbh, $sql);
    
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`requisitions` 
        WHERE RequisitID={$id} AND Transfered=0";
    $del = runDBQry($dbh, $sql);
    if ($del != 1) {
        throw new Exception("Not updated");
    }
    $dbh->commit();
    delDocs('Stock', 'Transfers', $id);
    header("Location: index.php");
} catch (Exception $ex) {
    $dbh->rollback();
    header("Location: view.php?id=$id");
}
$dbh->autocommit(TRUE);

?>
