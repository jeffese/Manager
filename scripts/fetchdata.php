<?php
//$TabArray = '';
${'pageNum_'.$TabArray} = GSQLStr(_xget('pageNum_'.$TabArray), "int");
${'startRow_'.$TabArray} = ${'pageNum_'.$TabArray} * ${'maxRows_'.$TabArray};

${$TabArray} = getDBDatalimit($dbh, $sql, ${'startRow_'.$TabArray}, ${'maxRows_'.$TabArray});
$dirvars = DBPager($dbh, $TabArray, $From, ${'maxRows_'.$TabArray}, isset($flds)? $flds: '*');
${'totalRows_'.$TabArray} = $dirvars['totalRows'];
${'totalPages_'.$TabArray} = $dirvars['totalPages'];
${'queryString_'.$TabArray} = $dirvars['queryString'];

?>