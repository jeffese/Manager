<?php require_once("../../scripts/init.php");
vetAccess('Stock', 'Orders', 'Del');
$id = intval(_xget('id'));

try {
    $dbh->autocommit(FALSE);
    
    $sql = "UPDATE `{$_SESSION['DBCoy']}`.`items_prod` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `items_prod`.`ProductID`=orderdetails.ProductID
        SET `UnitsOnOrder`=`UnitsOnOrder`-`QtyinStock`
        WHERE `OrderID`={$id}";
    runDBQry($dbh, $sql);

    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`orderdetails` WHERE OrderID=$id";
    runDBQry($dbh, $sql);

    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`orders` WHERE OrderID=$id AND Posted=0";
    $del = runDBQry($dbh, $sql);

    if ($del != 1) {
        throw new Exception("Not updated");
    }
    $dbh->commit();
    delDocs('Stock', 'Orders', $id);
    header("Location: index.php");
} catch (Exception $ex) {
    $dbh->rollback();
    header("Location: view.php?id=$id");
}
$dbh->autocommit(TRUE);

?>
