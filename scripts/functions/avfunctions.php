<?php

function getframe($srcFile, $destpix) {
    $ffmpegPath = "ffmpeg";
    $ffmpegObj = new ffmpeg_movie($srcFile);
    $GLOBALS['srcdur'] = ceil(($ffmpegObj->getDuration()) / 3);
    exec($ffmpegPath . " -i " . $srcFile . " -an -ss " . $GLOBALS['srcdur'] . " -t 1 -r 1 -y " . $destpix);
}

function vid2flv($srcFile, $destFile, $srcWidth, $srcHeight) {
    $ffmpegObj = new ffmpeg_movie($srcFile); // Save our needed variables
//$srcWidth = makeMultipleTwo($ffmpegObj->getFrameWidth());
//$srcHeight = makeMultipleTwo($ffmpegObj->getFrameHeight());
    $srcFPS = $ffmpegObj->getFrameRate();
    $srcAB = $ffmpegObj->getAudioBitRate();
    $srcAR = $ffmpegObj->getAudioSampleRate();
    $GLOBALS['srcdur'] = $ffmpegObj->getDuration();
    /* ." | flvtool2 -U stdin ".$destFile */// Call our convert using exec()".(($srcdur>1200)? "-t 1200 ":"")."
    exec("ffmpeg -i " . $srcFile . " -ar 22050 -ab 32768 -f flv -y -s " . $srcWidth . "x" . $srcHeight . " " . $destFile . ".flv");
    exec("ffmpeg -i " . $srcFile . " -ar 44100 -ab 32768 -r 25 -y -s 720x576 " . $destFile . ".avi");
    exec("ffmpeg -i " . $destFile . ".flv -vcodec png -an -ss " . gmdate("H:i:s", intval($GLOBALS['srcdur'] / 3)) . " -vframes 1 -y -f rawvideo -s " . $srcWidth . "x" . $srcHeight . " " . $destFile . ".jpg");
    for ($i = 0; $i < 10; $i++) {
	exec("ffmpeg -i " . $destFile . ".flv -vcodec png -an -ss " . gmdate("H:i:s", intval($GLOBALS['srcdur'] * $i / 10)) . " -vframes 1 -y -f rawvideo -s 120x90 " . $destFile . $i . ".jpg");
    }
}

// Make multiples function

function makeMultipleTwo($value) {
    $sType = gettype($value / 2);
    if ($sType == "integer") {
	return $value;
    } else {
	return ($value - 1);
    }
}

?>