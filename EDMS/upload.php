<?php

include_once '../scripts/init.php';
$id = _xpost('id');
$output_dir = EDMS_DIR . $_SESSION['coyid'] . DS  . "$id/";
$custom_error = array();

if (isset($_FILES["myfile"])) {
    $ret = array();

    if (!is_dir($output_dir)) {
        mkdir($output_dir);
    }
    //This is for custom errors;
    $error = $_FILES["myfile"]["error"];
    //You need to handle  both cases
    //If Any browser does not support serializing of multiple files using FormData() 
    if (!is_array($_FILES["myfile"]["name"])) { //single file
        moveFile($_FILES["myfile"]["name"], $_FILES["myfile"]["tmp_name"]);
    } else {  //Multiple files, file[]
        $fileCount = count($_FILES["myfile"]["name"]);
        for ($i = 0; $i < $fileCount; $i++) {
            moveFile($_FILES["myfile"]["name"][$i], $_FILES["myfile"]["tmp_name"][$i]);
        }
    }
    echo json_encode($ret);
    if ($custom_error) {
        echo json_encode($custom_error);
    }
} else {
    $custom_error['jquery-upload-file-error'] = "No File uploaded!";
    echo json_encode($custom_error);
}

function moveFile($fileName, $tmp) {
    global $output_dir, $custom_error, $ret;
    if (strlen($cmp = _xpost('cmp')) > 0) {
        $src_r = _xpost('src');
        if (strlen($src_r) > 0) {
            $_src = explode('/', $src_r);
            $src = _xvar_arr($_src, count($_src) - 1);
        }
        $fileName = strlen($src_r) > 0 ? $src : filenameUsed($output_dir, $cmp . '.' . getFileExtension($fileName));
    }
    if (move_uploaded_file($tmp, $output_dir . $fileName)) {
//        if (strlen($cmp) > 0 && strlen($src_r) > 0 && $src != $fileName) {
//            unlink($output_dir . $src);
//        }
        $ret[] = $fileName;
    } else {
        $custom_error['jquery-upload-file-error'] = "Could Not save file in Archive!!";
    }
}
