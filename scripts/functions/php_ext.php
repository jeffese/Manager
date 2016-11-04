<?php
/**
 * @author Jeffrey Ese Akwahsam
 * @copyright 2010
 */

function _rshift($a, $b) {
	$a &= 0xffffffff;
	$b &= 0x1f; // (bounds check)
	if ($a & 0x80000000 && $b > 0) { // if left-most bit set
		$a = ($a >> 1) & 0x7fffffff; //   right-shift one bit & clear left-most bit
		$a = $a >> ($b - 1); //   remaining right-shifts
	} else { // otherwise
		$a = ($a >> $b); //   use normal right-shift
	}
	return $a;
}

function _add($i1, $i2) {
	$result = 0.0;
	foreach (func_get_args() as $value) {
		// remove sign if necessary
		if (0.0 > $value) {
			$value -= 1.0 + 0xffffffff;
		}
		$result += $value;
	}
	// convert to 32 bits
	if (0xffffffff < $result || -0xffffffff > $result) {
		$result = fmod($result, 0xffffffff + 1);
	}
	// convert to signed integer
	if (0x7fffffff < $result) {
		$result -= 0xffffffff + 1.0;
	} elseif (-0x80000000 > $result) {
		$result += 0xffffffff + 1.0;
	}
	return $result;
}

function str2byteArray($str) {
	$byteArr = str_split($str);
	foreach ($byteArr as $key => $val) {
		$byteArr[$key] = ord($val);
	}
	return $byteArr;
}

function byteArray2str($byteArr) {
	foreach ($byteArr as $key => $val) {
		$byteArr[$key] = chr($val);
	}
	return implode($byteArr);
}

//Covert a string into longinteger
function _str2long($data) {
	//$n = strlen($data);
	$tmp = unpack('N*', $data);
	$data_long = array();
	$j = 0;
	foreach ($tmp as $value) $data_long[$j++] = $value;
	return $data_long;
}

//Convert a longinteger into a string
function _long2str($l) {
	return pack('N', $l);
}

function _int4($int) {
	if ($int > 127) {
		$int -= 256;
	} elseif ($int < -128) {
		$int += 256;
	}
	return $int;
}

?>