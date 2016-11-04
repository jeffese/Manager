<?php

$dir = $argv[1];
$fileTypes = $argv[2];
$expire_time = $argv[3];

foreach (glob($dir . $fileTypes) as $Filename) {
    $FileAge = time() - filectime($Filename);
    if ($FileAge > ($expire_time * 60)) {
        unlink($Filename);
    }
}
