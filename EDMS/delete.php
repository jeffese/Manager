<?php

include_once '../scripts/init.php';

if (_xpost('op') == "delete" && isset($_POST['name']) && isset($_POST['id'])) {
    $fileName = $_POST['name'];
    $id = _xpost('id');
    $fileName = str_replace("..", ".", $fileName); //required. if somebody is trying parent folder files	
    $filePath = EDMS_DIR . $_SESSION['coyid'] . DS . $id . "/" . $fileName;
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    echo "Deleted File " . $fileName . "<br>";
}
