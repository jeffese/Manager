<?php

function gen_img_set($id, $nm, $fp, $px) {
    $ret = '';
    for ($i = 1; $i <= $px; $i++) {
        $ret .= ", {'caption': '" . addslashes($nm) . "', 'url': 'http://" . WEBSITE . $fp . DS . $id . DS . $i . ".jpg'}";
    }
    return substr($ret, 2);
}

function newpix($dirname, $shelf, $recid, $cnt, $res, $elem = 'picture', $p = 'p', $pix = 'pix', $h = 0) {
    $dirn = explode(DS, $shelf . DS . $_SESSION['coyid']);
    for ($i = 0; $i < count($dirn); $i++) {
        $dirname = pixdir($dirname, $dirn[$i] . DS);
    }
    $dirname = pixdir($dirname, $recid . DS);
    $max1 = intval(_xpost('MAX_FILE_SIZE'));
    $max2 = intval(preg_replace(array('/K/', '/M/', '/G/'), array('000', '000000', '000000000'), ini_get('upload_max_filesize')));
    $max1 = $max1 == 0 ? $max2 : $max1;
    $max = min($max1, $max2);
    $pxcnt = 0;
    $delcnt = 0;

    for ($i = 1; $i <= $cnt; $i++) {
        $sfx = ($cnt == 1) ? '' : $i;
        $pix = ($cnt == 1) ? $pix : $i;
        if (isset($_FILES[$elem . $sfx]) && $_FILES[$elem . $sfx]['name'] != "" && intval($_FILES[$elem . $sfx]['size']) <= $max) {
            $post_file = $_FILES[$elem . $sfx]['name'];
            $tmp_file = $_FILES[$elem . $sfx]['tmp_name'];
            $pext = getFileExtension($post_file);
            $x = '';
            $pixtrue = false;
            for ($j = 0; $j < count($res); $j++) {
                $filename = $dirname . $x . $pix . '.jpg';
                $height = $h == 0 ? $res[$j] : $h * $res[$j] / $res[0];
                if (resampimagepix($res[$j], $height, $tmp_file, $filename, 10, $pext)) {
                    chmod($filename, 0755);
                    $pixtrue = true;
                }
                $x .= 'x';
            }

            if ($pixtrue) {
                $pxcnt++;
                $_SESSION['pixrnd'] = '?e=' . time();
            } elseif (_xpost($p . $sfx) != '') {
                $pxcnt++;
            }
        } elseif (isset($_POST[$p . $sfx])) {
            if (_xpost($p . $sfx) == '0') {
                $delcnt++;
                $x = '';
                for ($j = 0; $j < count($res); $j++) {
                    $filename = $dirname . $x . $pix . '.jpg';
                    if (file_exists($filename)) {
                        unlink($filename);
                    }
                    $x .= 'x';
                }
            } elseif (_xpost($p . $sfx) == '1') {
                $pxcnt++;
            }
        }
    }
    if ($delcnt > 0) {
        $next = 1;
        for ($i = 1; $i <= $cnt; $i++) {
            if (file_exists($dirname . $i . '.jpg')) {
                if ($i > $next) {
                    $x = '';
                    for ($j = 0; $j < count($res); $j++) {
                        rename($dirname . $x . $i . '.jpg', $dirname . $x . $next . '.jpg');
                        chmod($dirname . $x . $next . '.jpg', 0755);
                        $x .= 'x';
                    }
                }
                $next++;
            }
        }
    }
    $pixfiles = array('pixcode' => $pxcnt, 'shelfid' => $shelf . DS);
    return $pixfiles;
}

function newfile($dirname, $shelf, $recid, $arr = array(), $elem = 'preview', $pv = 'pv', $prev = 'pvw', $fname = '') {
    $dirn = explode(DS, $shelf);
    for ($i = 0; $i < count($dirn); $i++) {
        $dirname = pixdir($dirname, $dirn[$i] . DS);
    }
    $dirname = pixdir($dirname, $recid . DS);
    $shelfid = implode(DS, $dirn) . DS . $recid . DS;
    $max1 = intval(_xpost('MAX_FILE_SIZE'));
    $max2 = intval(preg_replace(array('/K/', '/M/', '/G/'), array('000', '000000', '000000000'), ini_get('upload_max_filesize')));
    $max1 = $max1 == 0 ? $max2 : $max1;
    $max = min($max1, $max2);
    $previews = "";

    for ($i = 0; $i < count($arr); $i++) {
        $sfx = $arr[$i];
        $prv = _xpost($pv . $sfx);
        if (isset($_FILES[$elem . $sfx]) && $_FILES[$elem . $sfx]['name'] != "" && intval($_FILES[$elem . $sfx]['size']) <= $max) {
            $file = strlen($fname) == 0 ? str_replace(' ', '_', $_FILES[$elem . $sfx]['name']) : $fname;
            $tmp_file = $_FILES[$elem . $sfx]['tmp_name'];

            $file = filenameUsed($dirname, $file);
            $filename = $dirname . $file;
            if (move_uploaded_file($tmp_file, $filename)) {
                $previews .= '~#~' . $file;
            } elseif ($prv != "") {
                $previews .= '~#~' . $prv;
            }
        } elseif ($prv != "") {
            $previews .= '~#~' . $prv;
        }
    }
    $previews = strlen($previews) > 0 ? substr($previews, 3) : "";
    $old = _xpost($prev);
    if ($old != '') {
        $oldfiles = explode('~#~', $old);
        $newfiles = explode('~#~', $previews);
        $delfiles = array_diff($oldfiles, $newfiles);
        foreach ($delfiles as $delfile) {
            if (file_exists($dirname . $delfile)) {
                unlink($dirname . $delfile);
            }
        }
    }
    $pixfiles = array('prvcode' => $previews, 'shelfid' => $shelf . DS);
    return $pixfiles;
}

function newpixr($dirname, $shelf, $recid, $cnt, $res, $tpix) {
    $fpath = $dirname;
    for ($i = 0; $i < count($shelf); $i++) {
        $dirname = pixdir($dirname, $shelf[$i] . DS);
    }
    $dirname = pixdir($dirname, $recid . DS);
    $shelfid = implode(DS, $shelf) . DS . $recid . DS;
    $pixcode = "";
    for ($i = 1; $i <= $cnt; $i++) {
        $sfx = ($cnt == 1) ? '' : $i;
        $pix = $i;
        $post_file = $tpix;
        $tmp_file = $fpath . $tpix;
        $pext = getFileExtension($post_file);
        $pext = strtolower($pext);
        $x = '';
        $pixtrue = false;
        for ($j = 0; $j < count($res); $j++) {
            $filename = $dirname . $x . $pix . ".jpg";
            if (resampimagepix($res[$j], $res[$j], $tmp_file, $filename, 10, $pext)) {
                $pixtrue = true;
            }
            $x .= 'x';
        }
        if ($pixtrue) {
            $pixcode .= ":" . $pix;
        }
    }
    if ($pixcode != "") {
        $pixcode = substr($pixcode, 1);
        unlink($tmp_file);
    }
    $pixfiles = array('pixcode' => $pixcode, 'shelfid' => $shelfid);
    return $pixfiles;
}

/* A simple image compare function.
 * It scans 2 images pixel by pixel, from left to right and from top to bottom, if all pixels have equal colors it will return 'True', otherwise it will return 'False'.
 * $img1 and $img2 must be of type image. (see usage)
 * ---------------------------------------------- */

function imagecompare($img1, $img2) {
    if (imagesx($img1) != imagesx($img2) || imagesy($img1) != imagesy($img2)) {
        return false;
    }
    for ($x = 0; $x < imagesx($img1); $x++) {
        for ($y = 0; $y < imagesy($img1); $y++) {
            if (imagecolorat($img1, $x, $y) != imagecolorat($img2, $x, $y)) {
                return false;
            }
        }
    }
    return true;
}

/**
 * clrpixs(ROOT.AUTOPIX_DIR);
 * resamplepixs(ROOT.AUTOPIX_DIR, array(640, 200, 64));
 */
function resamplepixs($dir, $res) {
    $objs = glob($dir . "/*");
    if ($objs) {
        foreach ($objs as $obj) {
            if (is_dir($obj)) {
                resamplepixs($obj, $res);
            } else {
                $t = strrpos($obj, "/") + 1;
                $fn = substr($obj, $t);
                $dir = substr($obj, 0, $t);
                $tmp = $dir . "tmp.jpg";
                rename($obj, $tmp);
                $c = 0;
                foreach ($res as $r) {
                    $dest = $dir . str_repeat("x", $c) . $fn;
                    resampimagepix($r, $r, $tmp, $dest, 10, "jpg");
                    $c++;
                }
                unlink($tmp);
            }
        }
    }
}

function clrpixs($dir) {
    $objs = glob($dir . "/*");
    if ($objs) {
        foreach ($objs as $obj) {
            if (is_dir($obj)) {
                clrpixs($obj);
            } else {
                $t = strrpos($obj, "/") + 1;
                $fn = substr($obj, $t);
                if (substr($fn, 0, 1) == "x" || substr($fn, 0, 1) == "t") {
                    unlink($obj);
                }
            }
        }
    }
}

/*
 * ----------------------------------------------
 * Sample usage:
 * $im1=imagecreatefromgif("test1.gif");
 * $im2=imagecreatefromgif("test2.gif");
 * echo imagecompare(&$im1,&$im2);
 */

function resampimagejpg($forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp, $pext = "") {
    
}

function resampimagepix($forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp, $pext = "") {
    $img_dst = resampimage($forcedwidth, $forcedheight, $sourcefile, $pext);
    if ($pext == "png") {
        $ret = imagepng($img_dst, $destfile);
    } else {
        $ret = imagejpeg($img_dst, $destfile, 100 - $imgcomp);
    }
    imagedestroy($img_dst);
    return $ret;
}

function resampimage($forcedwidth, $forcedheight, $g_srcfile, $pext = "") {
    $g_fw = $forcedwidth;
    $g_fh = $forcedheight;
    if (file_exists($g_srcfile)) {
        $g_is = getimagesize($g_srcfile);
        if (($g_is[0] - $g_fw) >= ($g_is[1] - $g_fh)) {
            $g_iw = $g_fw;
            $g_ih = ($g_fw / $g_is[0]) * $g_is[1];
        } else {
            $g_ih = $g_fh;
            $g_iw = ($g_ih / $g_is[1]) * $g_is[0];
        }
        $pext = $pext == "" ? getFileExtension($g_srcfile) : $pext;
        $img_dst = imagecreatetruecolor($g_iw, $g_ih);
        if ($pext == "jpg" || $pext == "jpeg") {
            $img_src = imagecreatefromjpeg($g_srcfile);
        } elseif ($pext == "png") {
            $img_src = imagecreatefrompng($g_srcfile);
            imagealphablending($img_dst, false);
            imagesavealpha($img_dst, true);
            $transparent = imagecolorallocatealpha($img_dst, 255, 255, 255, 127);
            imagefilledrectangle($img_dst, 0, 0, $g_iw, $g_ih, $transparent);
        } elseif ($pext == "bmp" || $pext == "wbmp" || $pext == "wbm" || $pext == "rle" || $pext == "dib") {
            $img_src = imagecreatefromwbmp($g_srcfile);
        }
        imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $g_iw, $g_ih, $g_is[0], $g_is[1]);
        return $img_dst;
    } else {
        return false;
    }
}

function cmyk2rgb($file) {
    $mgck_wnd = NewMagickWand();
    MagickReadImage($mgck_wnd, $file);

    $img_colspc = MagickGetImageColorspace($mgck_wnd);
    if ($img_colspc == MW_CMYKColorspace) {
        echo "$file was in CMYK format<br />";
        MagickSetImageColorspace($mgck_wnd, MW_RGBColorspace);
    }
    MagickWriteImage($mgck_wnd, str_replace('.', '-rgb.', $file));
}

function tiff2jpg($file) {
    $mgck_wnd = NewMagickWand();
    MagickReadImage($mgck_wnd, $file);

    $img_colspc = MagickGetImageColorspace($mgck_wnd);
    if ($img_colspc == MW_CMYKColorspace) {
        echo "$file was in CMYK format<br />";
        MagickSetImageColorspace($mgck_wnd, MW_RGBColorspace);
    }
    MagickSetImageFormat($mgck_wnd, 'JPG');
    MagickWriteImage($mgck_wnd, str_replace('.tif', '.jpg', $file));
}

function to300dpi($file) {
    $mgck_wnd = NewMagickWand();
    MagickReadImage($mgck_wnd, $file);
    $img_units = MagickGetImageUnits($mgck_wnd);
    switch ($img_units) {
        case MW_UndefinedResolution:
            $units = 'undefined';
            break;
        case MW_PixelsPerInchResolution:
            $units = 'PPI';
            break;
        case MW_PixelsPerCentimeterResolution:
            $units = 'PPcm';
            break;
    }
    list($x_res, $y_res) = MagickGetImageResolution($mgck_wnd);
    echo "$file<br /> x_res=$x_res $units - y_res=$y_res $units<br />";
    if ($x_res == 300 && $y_res == 300 && $img_units == MW_PixelsPerInchResolution) {
        return;
    }
    MagickSetImageResolution($mgck_wnd, 300, 300);
    MagickSetImageUnits($mgck_wnd, MW_PixelsPerInchResolution);
    MagickWriteImage($mgck_wnd, str_replace('.', '-300.', $file));
}

function tiffff() {

    $file = 'photos/test-cmyk.tif';
    //this is a TIFF file in CMYK format with a 96 DPI resolution
    cmyk2rgb($file);
    $file = str_replace('.', '-rgb.', $file);
    to300dpi($file);
    $file = str_replace('.', '-300.', $file);
    tiff2jpg($file);
    $file = str_replace('.tif', '.jpg', $file);
    to300dpi($file);
    /* no file name changes as ImageMagick reports 300 DPIs
     * $file = str_replace('.', '-300.', $file);
     */
    list($width, $height, $type, $attr) = getimagesize($file);
    $width = $width / 3;
    $height = $height / 3;
    echo "<img src=\"http://localhost/$file\" width=\"$width\" height=\"$height\" alt=\"getimagesize() example\" />";
    echo "<br />$file => width=$width - height=$height - type=$type - attr=$attr<br /><br />";
    $file = 'photos/test-rgb.tif';
    //this is a TIFF file in RGB format with a 96 DPI resolution
    cmyk2rgb($file);
    $file = str_replace('.', '-rgb.', $file);
    to300dpi($file);
    $file = str_replace('.', '-300.', $file);
    tiff2jpg($file);
    $file = str_replace('.tif', '.jpg', $file);
    to300dpi($file);
    /* no file name changes as ImageMagick reports 300 DPIs
     * $file = str_replace('.', '-300.', $file);
     */
    list($width, $height, $type, $attr) = getimagesize($file);
    $width = $width / 3;
    $height = $height / 3;
    echo "<img src=\"http://localhost/$file\" width=\"$width\" height=\"$height\" alt=\"getimagesize() example\" />";
    echo "<br />$file => width=$width - height=$height - type=$type - attr=$attr<br /><br />";
}

function sharpen($pixin, $pixout) {
    $filename = $pixin;
    list($width, $height) = getimagesize($filename);
    $img = imagecreatefromjpeg($filename);
    $pix = array();
    //get all color values off the image
    for ($hc = 0; $hc < $height; ++$hc) {
        for ($wc = 0; $wc < $width; ++$wc) {
            $rgb = ImageColorAt($img, $wc, $hc);
            $pix[$hc][$wc][0] = $rgb >> 16;
            $pix[$hc][$wc][1] = $rgb >> 8 & 255;
            $pix[$hc][$wc][2] = $rgb & 255;
        }
    }
    //sharpen with upper and left pixels
    $height--;
    $width--;
    for ($hc = 1; $hc < $height; ++$hc) {
        $r5 = $pix[$hc][0][0];
        $g5 = $pix[$hc][0][1];
        $b5 = $pix[$hc][0][2];
        $hcc = $hc - 1;
        for ($wc = 1; $wc < $width; ++$wc) {
            $r = -($pix[$hcc][$wc][0]);
            $g = -($pix[$hcc][$wc][1]);
            $b = -($pix[$hcc][$wc][2]);
            $r -= $r5 + $r5;
            $g -= $g5 + $g5;
            $b -= $b5 + $b5;
            $r5 = $pix[$hc][$wc][0];
            $g5 = $pix[$hc][$wc][1];
            $b5 = $pix[$hc][$wc][2];
            $r += $r5 * 5;
            $g += $g5 * 5;
            $b += $b5 * 5;
            $r *= .5;
            $g *= .5;
            $b *= .5; //here the value of 0.75 is like 75% of sharpening effect
            //Change if you need it to 0.01 to 1.00 or so
            //Zero would be NO effect
            //1.00 would be somewhat grainy
            $r = (($r - $r5) * .75) + $r5;
            $g = (($g - $g5) * .75) + $g5;
            $b = (($b - $b5) * .75) + $b5;
            if ($r < 0)
                $r = 0;
            elseif ($r > 255)
                $r = 255;
            if ($g < 0)
                $g = 0;
            elseif ($g > 255)
                $g = 255;
            if ($b < 0)
                $b = 0;
            elseif ($b > 255)
                $b = 255;
            imagesetpixel($img, $wc, $hc, ($r << 16) | ($g << 8) | $b);
        }
    }
    //save pic
    imageinterlace($img, 1);
    imagejpeg($img, $pixout, 99);
    imagedestroy($img);
}

//These two are functions to flip an image (in true color)
//- vertically:
function image_flip_vertical($im) {
    $x_i = imagesx($im);
    $y_i = imagesy($im);
    $im_ = imagecreatetruecolor($x_i, $y_i);
    for ($x = 0; $x < $x_i; $x++) {
        for ($y = 0; $y < $y_i; $y++) {
            imagecopy($im_, $im, $x_i - $x - 1, $y, $x, $y, 1, 1);
        }
    }
    return $im_;
}

//- horizontally:
function image_flip_horizontal($im) {
    $x_i = imagesx($im);
    $y_i = imagesy($im);
    $im_ = imagecreatetruecolor($x_i, $y_i);
    for ($x = 0; $x < $x_i; $x++) {
        for ($y = 0; $y < $y_i; $y++) {
            imagecopy($im_, $im, $x, $y_i - $y - 1, $x, $y, 1, 1);
        }
    }
    return $im_;
}

//I think there is no gd function to do the same.
?>
