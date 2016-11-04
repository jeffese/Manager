<?php require_once("../../scripts/init.php");
vetAccess('Stock', 'Packages', 'Del');
$id = intval(_xget('id'));

try {
    $dbh->autocommit(FALSE);
    
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`items_pkgs_itms` WHERE PackageID=$id";
    runDBQry($dbh, $sql);
    
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`items_pkgs` WHERE PackageID={$id}";
    $del = runDBQry($dbh, $sql);
    
    if ($del != 1) {
        throw new Exception("Not updated");
    }
    $dbh->commit();
    delDocs('Stock', 'Packages', $id);
    header("Location: index.php");
} catch (Exception $ex) {
    $dbh->rollback();
    header("Location: view.php?id=$id");
}
$dbh->autocommit(TRUE);

?>
