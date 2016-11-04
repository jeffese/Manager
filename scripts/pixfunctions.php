<?php /*A simple image compare function.
It scans 2 images pixel by pixel, from left to right and from top to bottom, if all pixels have equal colors it will return 'True', otherwise it will return 'False'.
$img1 and $img2 must be of type image. (see usage)
----------------------------------------------*/
function imagecompare($img1, $img2) {
 if (imagesx($img1)!=imagesx($img2) || imagesy($img1)!=imagesy($img2)){
 return false;
 }
 for( $x=0; $x<imagesx($img1 ); $x++ ){
for ($y=0; $y<imagesy($img1); $y++) {
 if(imagecolorat($img1,$x,$y)!=imagecolorat($img2,$x,$y)){
 return false;
 }
}
 }
 return true;
}/*  
----------------------------------------------
Sample usage:
$im1=imagecreatefromgif("test1.gif");
$im2=imagecreatefromgif("test2.gif");
echo imagecompare(&$im1,&$im2);
*/function resampimagejpg($forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp)
{
$g_imgcomp=100-$imgcomp;
$g_srcfile=$sourcefile;
$g_dstfile=$destfile;
$g_fw=$forcedwidth;
$g_fh=$forcedheight;
if(file_exists($g_srcfile))
{
$g_is=getimagesize($g_srcfile);
if(($g_is[0]-$g_fw)>=($g_is[1]-$g_fh))
{
$g_iw=$g_fw;
$g_ih=($g_fw/$g_is[0])*$g_is[1];
}
else
{
$g_ih=$g_fh;
$g_iw=($g_ih/$g_is[1])*$g_is[0]; 
}
$img_src=imagecreatefromjpeg($g_srcfile);
$img_dst=imagecreatetruecolor($g_iw,$g_ih);
imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $g_iw, $g_ih, $g_is[0], $g_is[1]);
imagejpeg($img_dst, $g_dstfile, $g_imgcomp);
imagedestroy($img_dst);
return true;
}
else
return false;
}

function sharpen($pixin,$pixout){
$filename=$pixin;
list($width, $height) = getimagesize($filename);
$img = imagecreatefromjpeg($filename); 
$pix=array();
//get all color values off the image
for($hc=0; $hc<$height; ++$hc){
for($wc=0; $wc<$width; ++$wc){
$rgb = ImageColorAt($img, $wc, $hc);
$pix[$hc][$wc][0]= $rgb >> 16;
$pix[$hc][$wc][1]= $rgb >> 8 & 255;
$pix[$hc][$wc][2]= $rgb & 255;
}
}
//sharpen with upper and left pixels
$height--; $width--;
for($hc=1; $hc<$height; ++$hc){ 
$r5=$pix[$hc][0][0];
$g5=$pix[$hc][0][1];
$b5=$pix[$hc][0][2]; 
$hcc=$hc-1;
for($wc=1; $wc<$width; ++$wc){
$r=-($pix[$hcc][$wc][0]);
$g=-($pix[$hcc][$wc][1]);
$b=-($pix[$hcc][$wc][2]); 
$r-=$r5+$r5; $g-=$g5+$g5; $b-=$b5+$b5; $r5=$pix[$hc][$wc][0];
$g5=$pix[$hc][$wc][1];
$b5=$pix[$hc][$wc][2];$r+=$r5*5; $g+=$g5*5; $b+=$b5*5; 
$r*=.5; $g*=.5; $b*=.5;//here the value of 0.75 is like 75% of sharpening effect
//Change if you need it to 0.01 to 1.00 or so
//Zero would be NO effect
//1.00 would be somewhat grainy
$r=(($r-$r5)*.75)+$r5;
$g=(($g-$g5)*.75)+$g5;
$b=(($b-$b5)*.75)+$b5; 
if ($r<0) $r=0; elseif ($r>255) $r=255;
if ($g<0) $g=0; elseif ($g>255) $g=255;
if ($b<0) $b=0; elseif ($b>255) $b=255;
imagesetpixel($img,$wc,$hc,($r << 16)|($g << 8)|$b);
} 
}
//save pic
imageinterlace($img,1);
imagejpeg($img,$pixout,99);
imagedestroy($img);
}//These two are functions to flip an image (in true color)
//- vertically:
function image_flip_vertical($im)
{
 $x_i = imagesx($im);
 $y_i = imagesy($im);
 $im_ = imagecreatetruecolor($x_i, $y_i);
 for ($x = 0; $x < $x_i; $x++)
 {
 for ($y = 0; $y < $y_i; $y++)
 {
 imagecopy($im_, $im, $x_i - $x - 1, $y, $x, $y, 1, 1);
 }
 }
 return $im_;
}
//- horizontally:
function image_flip_horizontal($im)
{
 $x_i = imagesx($im);
 $y_i = imagesy($im);
 $im_ = imagecreatetruecolor($x_i, $y_i);
 for ($x = 0; $x < $x_i; $x++)
 {
 for ($y = 0; $y < $y_i; $y++)
 {
 imagecopy($im_, $im, $x, $y_i - $y - 1, $x, $y, 1, 1);
 }
 }
 return $im_;
}
//I think there is no gd function to do the same.?>