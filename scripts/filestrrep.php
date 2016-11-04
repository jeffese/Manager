<?php

function getFileExtension($str) {
    $i = strrpos($str, ".");
    if (!$i) {
        return "";
    }
    $l = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    return $ext;
}

function dir_replace_str($dir, $ext, $pin, $nail) {
    if ($objs = glob($dir . "/*")) {
        foreach ($objs as $obj) {
            is_dir($obj) ? dir_replace_str($obj, $ext, $pin, $nail) : file_replace_str($obj, $ext, $pin, $nail);
        }
    }
}

function file_replace_str($file, $ext, $pin, $nail) {
    set_time_limit(100);
//	if (getFileExtension($file)==$ext) {
    $arr = explode('/', $file);
    if ($arr[count($arr) - 1] == 'data') {
        $fstr = preg_replace('/[^\w\\\\\.]/', '', file_get_contents($file));
        if (stripos($fstr, 'user_methods') !== FALSE) {
            echo $file;
        }
        //unlink($file);
//		$newstr = str_replace($pin, $nail, $fstr);
//		file_put_contents($file,$newstr);
    }
}

dir_replace_str('C:\Users\Jeffrey Ese\AppData\Roaming\NetBeans\7.2\var\filehistory', 'php', '<!-- ###xtag-end### -->', '<!-- ###xtag-end### -->');
?>