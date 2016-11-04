<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php

function getfs($dir,$xdr) {
if($objs = glob($dir."\*")){
foreach($objs as $obj) {
if (is_dir($obj)) {
	 getfs($obj,$xdr);}
else {
	$flnk = str_replace($xdr, "/documents/archive", $obj);
	$flnk = str_replace("\\", "/", $flnk);
	echo "<a href=\"".$flnk."\">".$flnk."</a><br>"."\r\n";
	}
}}}
$xdir = getcwd();
getfs($xdir,$xdir);
?>
</body>
</html>
