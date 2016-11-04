<?php require_once("../../scripts/init.php");
vetAccess('Stock', 'Returns', 'Del');
$id = intval(_xget('id'));

try {
    $dbh->autocommit(FALSE);
    
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`orderreturndet` WHERE OrderRetID=$id";
    runDBQry($dbh, $sql);
    
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`orderreturns` 
        WHERE OrderRetID={$id} AND Posted=0";
    $del = runDBQry($dbh, $sql);

    if ($del != 1) {
        throw new Exception("Not updated");
    }
    $dbh->commit();
    delDocs('Stock', 'Returns', $id);
    header("Location: index.php");
} catch (Exception $ex) {
    $dbh->rollback();
    header("Location: view.php?id=$id");
}
$dbh->autocommit(TRUE);

?>
