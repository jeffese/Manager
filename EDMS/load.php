<?php

include_once '../scripts/init.php';
$id = _xget('id');
$dir = EDMS_DIR . $_SESSION['coyid'] . DS . $id;
$files = scandir($dir);

$ret = array();
foreach ($files as $file) {
    if ($file == "." || $file == ".." || substr($file, 0, 4) == 'cmp_')
        continue;
    $filePath = $dir . "/" . $file;
    $fakepth = $id . "/" . $file;
    $details = array();
    $details['name'] = $file;
    $details['path'] = $fakepth;
    $details['size'] = filesize($filePath);
    $ret[] = $details;
}

echo json_encode($ret);
