<?php

/**
 * @author ohyeah
 * @copyright 2010
 */

/**
 * This function do some security stuff on the session
 */
function regenerate_Session($reload = false) {
    // This token is used by forms to prevent cross site forgery attempts
    // So guys dem bad, fit use another person session
    if (!isset($_SESSION['nonce']) || $reload)
	$_SESSION['nonce'] = md5(microtime(true));

    if (!isset($_SESSION['IPaddress']) || $reload)
	$_SESSION['IPaddress'] = long2ip(getIp());

    if (!isset($_SESSION['userAgent']) || $reload)
	$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];

    //$_SESSION['userid'] = $this->user->getId();
    // Set current session to expire in 1 HOUR
    $_SESSION['OBSOLETE'] = true;
    $_SESSION['EXPIRES'] = (time() + 60) * 60;

    // Create new session without destroying the old one
    session_regenerate_id(false);

    // Grab current session ID and close both sessions to allow other scripts to use them
    $newSession = session_id();
    session_write_close();

    // Set session ID to the new one, and start it back up again
    session_id($newSession);
    session_start();

    // Don't want this one to expire
    //unset($_SESSION['OBSOLETE']);    //unset($_SESSION['EXPIRES']);
}

/* * nSession check */

function checkSession() {
    try {
	if (isset($_SESSION['OBSOLETE']) && ($_SESSION['EXPIRES'] < time()))
	    throw new Exception(29);

	if (!isset($_SESSION['userid']) || !isset($_SESSION['userid']))
	    throw new Exception(30);

	if ($_SESSION['IPaddress'] != long2ip(getIp()))
	    throw new Exception(31);

	if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
	    throw new Exception(32);

	if (!ExoodUser::checkUser($_SESSION['Exoodid'], $_SESSION['userid']))
	    throw new Exception(33);

	if (!$_SESSION['OBSOLETE'] && mt_rand(1, 100) == 1) {
	    regenerate_Session(RELOAD_SESSION);
	}

	return true;
    } catch (exception $e) {
	session_destroy();
	header(sprintf("location:%s?do=sess&id=%s", ERROR_PAGE, base64_encode($e->getMessage())));
	exit;
    }
}

function set_QS($qs = '', $qrystr = '') {
    $qrystr = strlen($qrystr) == 0 ? $_SERVER['QUERY_STRING'] : ($qrystr == '0' ? '' : $qrystr);
    $lnks = TabKey_Explode($qs, 0, array('&'), array('='));
    $urls = TabKey_Explode($qrystr, 0, array('&'), array('='));
    $res = array_merge($urls, $lnks);
    $qry = TabKey_Implode($res, 0, array('&'), array('='));
    return (strlen($qry) > 0 ? '?' : '') . $qry;
}

function getformaction() {
    $FormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
	$FormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }
}

function get_prevURL() {
    if (isset($_SERVER["HTTP_REFERER"])) {
	return $_SERVER["HTTP_REFERER"];
    }
    return '';
}

function _xpost($name) {
    if (isset($_POST[$name]))
	return trim($_POST[$name]);
    else
	return '';
}

function _xpostchk($name) {
    if (isset($_POST[$name]))
	return "1";
    else
	return "0";
}

function _xget($name) {
    if (isset($_GET[$name]))
	return trim($_GET[$name]);
    else
	return '';
}

function _xses($name) {
    if (isset($_SESSION[$name]))
	return trim($_SESSION[$name]);
    else
	return '';
}

function _xvarloop($name, $ses = '') {
    $var = "";
    if (isset($_GET[$name])) {
	$var = _xget($name);
    } else if (isset($_POST[$name])) {
	$var = _xpost($name);
    } else if (strlen($ses > 0)){
	$var = _xses($ses);
    }
    return $var;
}

function _xvarloopstore($name, $ses) {
    $var = "";
    if (isset($_GET[$name])) {
	$var = _xget($name);
    } else if (isset($_POST[$name])) {
	$var = _xpost($name);
    } else {
	$var = _xses($ses);
    }
    $_SESSION[$ses] = $var;
    return $var;
}

function _xcookie($name) {
    if (isset($_COOKIE[$name]))
	return trim($_COOKIE[$name]);
    else
	return '';
}

function _xvar($name) {
    global $$name;
    if (isset($$name))
	return $$name;
    else
	return '';
}

function _xvar_arr($name, $key) {
    if (isset($name[$key]))
	return trim($name[$key]);
    else
	return '';
}

function _xvar_arr_sub($name, $subs) {
    $vet = true;
    foreach ($subs as $sub) {
        if (isset($name[$sub])) {
            $name = $name[$sub];
        } else {
            $vet = false;
            break;
        }
    }
    return $vet ? $name : array();
}

function getIp() {
    // No IP found (will be overwritten by for
    // if any IP is found behind a firewall)
    $ip = false;

    // User is behind a proxy and check that we discard RFC1918 IP addresses
    // if they are behind a proxy then only figure out which IP belongs to the
    // user.  Might not need any more hackin if there is a squid reverse proxy
    // infront of apache.
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

	// Put the IP's into an array which we shall work with shortly.
	$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);

	for ($i = 0; $i < count($ips); $i++) {
	    // Skip RFC 1918 IP's 10.0.0.0/8, 172.16.0.0/12 and
	    // 192.168.0.0/16
	    // below.
	    if (!eregi("^(10|172\.16|192\.168)\.", $ips[$i])) {
		$ip = $ips[$i];
		break;
	    }
	}
    }
    // Return with the found IP or the remote address
    return (ip2long($ip) ? ip2long($ip) : ip2long($_SERVER['REMOTE_ADDR']));
}

function GetServerStatus($site, $port = 80) {
    $status = array("OFFLINE", "ONLINE");
    $fp = @fsockopen($site, $port, $errno, $errstr, 2);
    if (!$fp) {
        return $status[0];
    } else {
        return $status[1];
    }
}
?>