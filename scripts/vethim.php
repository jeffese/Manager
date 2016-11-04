<?php
$PgUrl = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $PgUrl .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}
if (!(isset($_SESSION['userid']))) {
	header("Location:http:/useraccounts/userlogin.php?PrevUrl=". $PgUrl);
	exit;
}
?>