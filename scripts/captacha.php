<?php

session_start();

class CaptchaSecurityImages {

    var $font = 'fonts/monofont.ttf';
    var $char = 6;

    function wave_area($img, $x, $y, $width, $height, $amplitude = 7, $period = 15) {
        // Make a copy of the image twice the size
        $height2 = $height * 2;
        $width2 = $width * 2;
        $img2 = imagecreatetruecolor($width2, $height2);
        imagecopyresampled($img2, $img, 0, 0, $x, $y, $width2, $height2, $width, $height);
        if ($period == 0)
            $period = 1;

        // Wave it
        for ($i = 0; $i < ($width2); $i += 2) {
            imagecopy($img2, $img2, $x + $i - 2, $y + sin($i / $period) * $amplitude, $x + $i, $y, 2, $height2);
        }
        for ($i = 0; $i < ($height2); $i += 2) {
            imagecopy($img2, $img2, $x + sin($i / $period) * $amplitude, $y + $i - 2, $x, $y + $i, $width2, 2);
        }

        // Resample it down again
        imagecopyresampled($img, $img2, $x, $y, 0, 0, $width, $height, $width2, $height2);
        imagedestroy($img2);
        return $img;
    }

    function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1) {
        /* this way it works well only for orthogonal lines
         * imagesetthickness($image, $thick);
         * return imageline($image, $x1, $y1, $x2, $y2, $color);
         */
        if ($thick == 1) {
            return imageline($image, $x1, $y1, $x2, $y2, $color);
        }
        $t = $thick / 2 - 0.5;
        if ($x1 == $x2 || $y1 == $y2) {
            return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
        }
        $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
        $a = $t / sqrt(1 + pow($k, 2));
        $points = array(round($x1 - (1 + $k) * $a), round($y1 + (1 - $k) * $a), round($x1 - (1 - $k) * $a), round($y1 - (1 + $k) * $a), round($x2 + (1 + $k) * $a), round($y2 - (1 - $k) * $a), round($x2 + (1 - $k) * $a), round($y2 + (1 + $k) * $a),);
        imagefilledpolygon($image, $points, 4, $color);
        return imagepolygon($image, $points, 4, $color);
    }

    function generateCode() {
        $possible = '2345789acCdEfFghHJkLmMnNpPrRsStTwWxYzZ';
        $code = '';
        $i = 0;
        while ($i < $this->char) {
            $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            $i++;
        }
        return $code;
    }

    function CaptchaSecurityImages($width = '200', $height = '40') {
        $code = $this->generateCode();
        /* seed random number gen to produce the same noise pattern time after time */
        mt_srand(crc32($code));

        /* init image */
        $font_size = $height * 0.9;
        $image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');

        /* set the colours */
        $background_color = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 100, 100, 100);
        $noise_color = imagecolorallocate($image, 100, 100, 100);

        /* create textbox and add text */
        $textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
        $x = ($width - $textbox[4]) / 2;
        $y = ($height - $textbox[5]) / 2;
        $d = -1;
        imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font, $code) or die('Error in imagettftext function');
        imagettftext($image, $font_size, 0, $x + $d, $y + $d, $noise_color, $this->font, $code) or die('Error in imagettftext function');
        imagettftext($image, $font_size, 0, $x + 2 * $d + 1, $y + 2 * $d + 1, $noise_color, $this->font, $code) or die('Error in imagettftext function');
        //			imagettftext($image, $font_size, 0, $x + 2 * $d, $y + 2 * $d, $background_color, $this->font, $code) or die('Error in imagettftext function');

        /* mix in background dots */
        for ($i = 0; $i < ($width * $height) / 10; $i++) {
            //imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $background_color);
        }

        /* mix in text and noise dots */
        for ($i = 0; $i < ($width * $height) / 25; $i++) {
            imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $noise_color);
            imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $text_color);
        }
        for ($i = 0; $i < ($width * $height) / 2000; $i++) {
            $this->imagelinethick($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $noise_color, 2);
        }

        /* rotate a bit to add fuzziness */
        //	$image = imagerotate($image, 1, $background_color);

        /* output */
        $image = $this->wave_area($image, 0, 0, 200, 40);
        $_SESSION['captchacode'] = strtolower($code);
        header('Content-Type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);
    }

}

$captcha = new CaptchaSecurityImages();
?>