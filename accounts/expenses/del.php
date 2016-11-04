<?php require_once("../../scripts/init.php");

vetAccess('Accounts', 'Expenses', 'Del');
$id = intval(_xget('id'));

$sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`expenses` 
    WHERE ExpenseID=$id AND Posted=0";
$del = runDBQry($dbh, $sql);
if ($del == 1) {
    delDocs('Accounts', 'Expenses', $id);
}
header("Location: index.php");

?>