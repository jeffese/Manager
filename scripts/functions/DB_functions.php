<?php

function cleanvals(&$row, $key, $dirt) {
    $row = addcslashes($row, $dirt); //"\r\n\'\""
}

function swipeclean(&$row, $key, $dirt = "\r\n\'\"") {
    $row = addcslashes($row, $dirt);
}

function loadcsvdata($dbh, $table, $file) {
    $handle = fopen($file, 'r');
    while (($data = fgetcsv($handle, 1000, ',', '"')) !== false) {
	array_walk($data, 'swipeclean');
	runDBQry($dbh, "INSERT INTO $table VALUES ('" . implode("','", $data) . "')");
    }
}

function getDBData($dbh, $sql, $dirt = "") {
    $result = $dbh->query($sql);
    $tab = array();
    if ($result) {
	while ($row = $result->fetch_assoc()) {
	    if ($dirt != "")
		array_walk($row, 'cleanvals', $dirt);
	    array_push($tab, $row);
	}
	$result->close();
    }
    doError($dbh, $sql);
    return $tab;
}

function getDBDataRow($dbh, $sql, $dirt = "") {
    $result = $dbh->query($sql);
    $row = array();
    if ($result) {
	$row = $result->fetch_assoc();
	if (is_array($row) && $dirt != "")
	    array_walk($row, 'cleanvals', $dirt);
	$result->close();
    }
    doError($dbh, $sql);
    return $row;
}

function runDBqry($dbh, $sql) {
    $result = $dbh->query($sql);
    doError($dbh, $sql);
    $cnt = -1;
    if ($result)
	$cnt = $dbh->affected_rows;
    return $cnt;
}

function doError($dbh, $sql) {
    if (mysqli_error($dbh)) {
	error_log("ERROR: Line " . __line__ . " in file " . __file__ . " :::: Called From :::" . $_SERVER['PHP_SELF'] . "\n" . mysqli_error($dbh) . "\n\n" . $sql, 0);
    }
}

function getDBDatalimit($dbh, $sql, $from, $count, $dirt = "") {
    $sql = $sql . " LIMIT " . $from . ", " . $count;
    $tab = getDBData($dbh, $sql, $dirt);
    return $tab;
}

function getDBDataRowkey($dbh, $table, $key, $value, $dirt = "") {
    $sql = "SELECT * FROM " . $table . " WHERE " . $key . " = " . $value;
    $result = getDBDataRow($dbh, $sql, $dirt);
    return $result;
}

function getDBDataFldkey($dbh, $table, $key, $fld, $value, $dirt = "") {
    $sql = "SELECT $fld FROM " . $table . " WHERE " . $key . " = " . $value;
    $result = getDBDataRow($dbh, $sql, $dirt);
    return $result ? $result[$fld] : NULL;
}

function getDBDatacnt($dbh, $From, $dirt = "", $flds = "*") {
    $sql = "SELECT COUNT({$flds}) AS cnt " . $From;
    $row = getDBDataRow($dbh, $sql, $dirt);
    if ($row)
	$cnt = $row['cnt'];
    else
	$cnt = 0;
    return $cnt;
}

function getDBDataOne($dbh, $sql, $key, $dirt = "") {
    $row = getDBDataRow($dbh, $sql, $dirt);
    if (count($row) == 1)
	$ret = $row[$key];
    else
	$ret = false;
    return $ret;
}

function getDBDatacnts($dbh, $From, $dirt = "", $flds = "*") {
    $str = explode("###", $From);
    $cnt = 0;
    for ($i = 1; $i < count($str); $i++) {
	$cnt += getDBDatacnt($dbh, $str[$i], $dirt, $flds);
    }
    return $cnt;
}

function runDBdel($dbh, $table, $key, $val) {
    $sql = "DELETE FROM $table WHERE $key = $val";
    return runDBqry($dbh, $sql);
}

function isExist($dbh, $key, $value, $table) {
    $From = "FROM " . $table . " WHERE " . $key . " = " . $value;
    $cnt = getDBDatacnt($dbh, $From);
    if ($cnt > 0)
	return true;
    else
	return false;
}

function delete_last_inserted($dbh, $table, $key) {
    $dbh->query("DELETE FROM " . $table . " WHERE " . $key . " = " . $dbh->insert_id);
}

function remparam($var_str, $vals) {
    $queryString = "";
    if (!empty($var_str)) {
	$params = explode("&", $var_str);
	$newParams = array();
	$ParamList = array();
	foreach ($params as $param) {
	    $p = explode("=", $param);
	    $kill = 0;
	    foreach ($vals as $val) {
		if ($p[0] == $val || $param == "" || in_array($p[0], $ParamList)) {
		    $kill++;
		}
	    }
	    if ($kill == 0) {
		array_push($newParams, $param);
		array_push($ParamList, $p[0]);
	    }
	}
	if (count($newParams) != 0) {
	    $queryString = stripslashes(implode('&', $newParams));
	}
    }
    return $queryString;
}

function DBPager($dbh, $tabsfx, $From, $maxRows, $flds) {
    $totalRows = GSQLStr(_xget('totalRows_' . $tabsfx), "int");
    if ($totalRows == 0) {
	if (substr($From, 0, 1) == "#") {
	    $totalRows = getDBDatacnts($dbh, $From, '', $flds);
	} else {
	    $totalRows = getDBDatacnt($dbh, $From, '', $flds);
	}
    }
    $totalPages = ceil($totalRows / $maxRows) - 1;
    $queryString = remparam($_SERVER['QUERY_STRING'], array("pageNum_" . $tabsfx, "totalRows_" . $tabsfx));
    $queryString = $queryString == "" ? "" : '&' . $queryString;
    $qrystr = $queryString;
    $queryString = sprintf("&totalRows_%s=%d%s", $tabsfx, $totalRows, $queryString);
    $vars = array('totalRows' => $totalRows, 'totalPages' => $totalPages, 'queryString' => $queryString, 'qrystr' => $qrystr);
    return $vars;
}

?>