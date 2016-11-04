<?php require_once('bset.php'); ?>
<?php

mysql_select_db($database_exood, $exood);
$query_TOffice = "SELECT * FROM address WHERE lat=0 AND lng=0 ORDER BY address_id DESC";
$TOffice = mysql_query($query_TOffice, $exood) or die(mysql_error());
$totalRows_TOffice = mysql_num_rows($TOffice);

$er = 0;
while ($row_TOffice = mysql_fetch_assoc($TOffice)) {
	set_time_limit(0);
	$latlng = '';		
	$add = $row_TOffice['address'].' '.$row_TOffice['city'].' '.$row_TOffice['state'].' Nigeria';
/*	$add = preg_replace('/(\W+|\d+)/',' ',);
	$add = preg_replace('/(\s+)/',' ',$add);*/
	$addr = urlencode($add);
	
	$Gmap = 'http://maps.google.com/maps/geo?q='.$addr.'&output=json&oe=utf8&sensor=false&key='.$GMapKey;
	$locs = file($Gmap);
	if ($locs)
		while (list(, $val) = each($locs)) {
		   $pos = strpos($val, '"coordinates":');
		   if (!($pos===false)) {
				$a = strpos($val, '[')+1;
				$b = strpos($val, ', 0 ]') - $a;
				$latlng = trim(substr($val, $a, $b));
			   break;
		   }
		}	if ($latlng!='') {
		$cd = explode(', ', $latlng);		
		
		mysql_select_db($database_exood, $exood);
		$updateSQL = sprintf("UPDATE address SET lng=%s, lat=%s WHERE address_id=%s ",
			GSQLStr($cd[0], "double"),
			GSQLStr($cd[1], "double"),
			GSQLStr($row_TOffice['address_id'], "int"));
		$Result1 = mysql_query($updateSQL, $exood) or die(mysql_error());		
		
		echo $latlng;
	} /*else {
		$er++;
		echo $er.'...';
		mysql_select_db($database_exood, $exood);
		$updateSQL = sprintf("UPDATE address SET lng=%s, lat=%s WHERE address_id=%s ",
			GSQLStr(0.0123456, "double"),
			GSQLStr(0.0123456, "double"),
			GSQLStr($row_TOffice['address_id'], "int"));
		$Result1 = mysql_query($updateSQL, $exood) or die(mysql_error());
	}*/
	//sleep(rand(0,8));
}?>