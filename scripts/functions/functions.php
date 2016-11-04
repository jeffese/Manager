<?php

function log_die($ob) {
    print '<pre>';
    print_r($ob);
    print '</pre>';
    exit;
}

function log_roll($ob) {
    print '<pre>';
    print_r($ob);
    print '</pre>';
}

function varShow() {
    print '<pre>';
    print_r(get_defined_vars());
    print '</pre>';
    exit;
}

function getFileExtension($str) {
    $i = strrpos($str, ".");
    if (!$i)
        return "";
    $l = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    $ext = strtolower($ext);
    return $ext;
}

function filenameUsed($dir, $filename) {
    $x = 0;
    $pos = strripos($filename, ".");
    $pos = $pos == FALSE ? strlen($filename) : $pos;
    $newname = $filename;
    while (file_exists($dir . $newname)) {
        $x++;
        $newname = substr_replace($filename, '-' . $x, $pos, 0);
    }
    return $newname;
}

function xreadFile($fname) {
    $str = file_get_contents($fname);
    $order = array("\r\n", "\n", "\r");
    $replace = '';
    $newstr = str_replace($order, $replace, $str);
    return $newstr;
}

function padNum($len, $num) {
    $blank = pow(10, $len) + $num;
    return substr('' . $blank, 1);
}

function persons_age($BirthDate) {
    $DateParts = explode('[-.]', $BirthDate);
    if (count($DateParts) == 3) {
        list($year, $month, $day) = $DateParts;
        $tmonth = date('n');
        $tday = date('j');
        $tyear = date('Y');
        $years = $tyear - $year;
        if ($tmonth <= $month) {
            if ($month == $tmonth) {
                if ($day > $tday)
                    $years--;
            } else
                $years--;
        }
        echo ($years < 100) ? $years : "?";
    }
    echo "?";
}

function rmdirr($dir) {
    clrdir($dir);
    rmdir($dir);
}

function clrdir($dir) {
    if ($objs = glob($dir . "/*")) {
        foreach ($objs as $obj) {
            is_dir($obj) ? rmdirr($obj) : unlink($obj);
        }
    }
}

function cpdirr($dir, $newdir) {
    mkdir($newdir);
    if ($objs = glob($dir . "/*")) {
        foreach ($objs as $obj) {
            is_dir($obj) ? cpdirr($obj, $newdir . "/" . basename($obj)) : copy($obj, $newdir . "/" . basename($obj));
        }
    }
}

function pixdir($dirname, $shelfid) {
    $dirname .= $shelfid;
    if (!file_exists($dirname)) {
        mkdir($dirname, 0777);
    }
    return $dirname;
}

function stripSlashesDeep($value) {
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

function removeMagicQuotes() {
    if (get_magic_quotes_gpc()) {
        $_GET = stripSlashesDeep($_GET);
        $_POST = stripSlashesDeep($_POST);
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

function setReporting($set) {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    if ($set) {
        ini_set('display_errors', 'On');
    } else {
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ERRORS);
    }
}

/**
 * Display Errors
 */
function catch_error($msg) {
    if (count($msg) > 0)
        foreach ($msg as $mg) {
            echo <<< EOD
<div style="border: solid #FF0000 1px; padding: 5px; color:#FF0000; font-weight:bolder; background-color:#000; margin:10px; font-size:20px">
<div align="center">{$mg[0]}</div>
<div align="center" style="border:#990000 solid 1px; padding:3px; font-size:11px; background-color:#FFF"><pre>{$mg[1]}</pre></div>
</div>
EOD;
        }
}

/**
 * Display Messages
 */
function showMsg($msg) {
    if (count($msg) > 0)
        foreach ($msg as $mg) {
            echo <<< EOD
<div style="border: solid #666 1px; padding: 5px; color:#FFF; font-weight:bolder; background-color:#F90; margin:10px; font-size:20px">
	<div><img src="/images/msg.png" width="60" height="60" style="float:left"></div>
    <div>
  		<div align="center" style="margin-left:70px; padding:5px">{$mg[0]}</div>
		<div align="center" style="border:#666 solid 1px; margin-left:70px; padding:3px; color:#222; font-size:11px; background-color:#FFF">{$mg[1]}</div>
	</div>
</div>
EOD;
        }
}

/**
 * Check for bad words
 */
function censor_it($s) {
    $search_for = "(4r5e|5h1t|5hit|a55|anal|ar5e|arrse|arse|ass|ass-fucker|assfucker|assfukka|asshole|asswhole|b00bs|ballbag|balls|ballsack|blowjob|boiolas|boobs|booobs|boooobs|booooobs|booooooobs|buceta|bunny fucker|buttmuch|c0ck|c0cksucker|cawk|chink|cipa|cl1t|clit|clit|clits|cnut|cock|cock-sucker|cockface|cockhead|cockmunch|cockmuncher|cocksucker|cocksuka|cocksukka|cok|cokmuncher|coksucka|cox|cum|cunt|cyalis|dickhead|dildo|dirsa|dlck|dog-fucker|dogging|doosh|duche|f u c k e r|fag|faggitt|faggot|fannyfucker|fanyy|fcuk|fcuker|fcuking|feck|fecker|fook|fooker|fuck|fuck|fucka|fucker|fuckhead|fuckin|fucking|fuckingshitmotherfucker|fuckwhit|fuckwit|fuk|fuker|fukker|fukkin|fukwhit|fukwit|fux|fux0r|gaylord|heshe|hoare|hoer|hore|jackoff|jism|kawk|knob|knobead|knobed|knobhead|knobjocky|knobjokey|m0f0|m0fo|m45terbate|ma5terb8|ma5terbate|master-bate|masterb8|masterbat*|masterbat3|masterbation|masterbations|masturbate|mo-fo|mof0|mofo|motherfucker|motherfuckka|mutha|muthafecker|muthafuckker|muther|mutherfucker|n1gga|n1gger|nazi|nigg3r|nigg4h|nigga|niggah|niggas|niggaz|nigger|nob|nob jokey|nobhead|nobjocky|nobjokey|numbnuts|nutsack|penis|penisfucker|phuck|pigfucker|pimpis|piss|pissflaps|porn|prick|pron|pusse|pussi|pussy|rimjaw|rimming|schlong|scroat|scrote|scrotum|sh!+|sh!t|sh1t|shag|shagger|shaggin|shagging|shemale|shi+|shit|shit|shitdick|shite|shited|shitey|shitfuck|shithead|shitter|slut|smut|snatch|spac|t1tt1e5|t1tties|teets|teez|testical|testicle|titfuck|tits|titt|tittie5|tittiefucker|titties|tittyfuck|tittywank|titwank|tw4t|twat|twathead|twatty|twunt|twunter|wang|wank|wanker|wanky|whoar|whore|willies|willy|suck my dick|suck ma dick)";
    return preg_replace($search_for, '****', $s);
}

function PowerClean($str) {
    // This will remove HTML tags, javascript sections
    // and white space. It will also convert some
    // common HTML entities to their text equivalent.

    $search = array("'<script[^>]*?>.*?</script>'si", // Strip out javascript
        "'<[\/\!]*?[^<>]*?>'si", // Strip out HTML tags
        "'([\r\n])[\s]+'", // Strip out white space
        "'&(quot|#34);'i", // Replace HTML entities
        "'&(amp|#38);'i", "'&(lt|#60);'i", "'&(gt|#62);'i", "'&(nbsp|#160);'i", "'&(iexcl|#161);'i", "'&(cent|#162);'i", "'&(pound|#163);'i", "'&(copy|#169);'i", "'&#(\d+);'e"); // evaluate as php

    $replace = array("", "", "\\1", "\"", "&", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), "chr(\\1)");

    return preg_replace($search, $replace, $str);
}

/**
 *  Remove html tags and censor the words
 */
function _c($s) {
    return PowerClean(censor_it($s));
}

/**
 * str_reverse($string)
 * Return a reversed version of the string
 */
function str_reverse($string) {
    return join(' ', array_reverse(explode(' ', $string)));
}

/**
 * clean($str)
 * Clean the string from html tags
 */
function clean($str) {
    $str = strip_tags($str);
    $str = preg_replace("/([%#'\"\)\(\}\{\]\[\;\|\+\-\\^\^\%\$])/i", "", $str);
    return $str;
}

/**
 * isValidEmail($email)
 * Return True if valid else return false
 * This method will validate any email
 */
function isValidEmail($email) {
    return preg_match('/^[a-z0-9_-][a-z0-9._-]+@([a-z0-9][a-z0-9-]*\.)+[a-z]{2,6}$/i', $email);
}

function randomString($len = 24) {
    $s = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $slen = strlen($s) - 1;
    $out = '';
    for ($i = 0; $i < $len; $i++) {
        $out .= $s[rand(0, $slen)];
    }
    return $out;
}

function randomnum($len = 10) {
    $s = "1234567890";
    $slen = strlen($s) - 1;
    $out = '';
    for ($i = 0; $i < $len; $i++) {
        $out .= $s[rand(0, $slen)];
    }
    return $out;
}

function escape_string($str) {
    $str = preg_replace('/\\\\/', '\\\\\\\\', $str);
    $str = mysql_escape_string($str);
    $str = preg_replace('/([%_`\'"])/', '\\\\$1', $str);
    return $str;
}

function GSQLStr($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    $theValue = mysql_escape_string($theValue);

    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "''";
            break;
        case "textv":
            $theValue = ($theValue != "") ? $theValue : "";
            break;
        case "textn":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
        case "int":
            $theValue = ($theValue != "") ? intval(preg_replace('/[^\.\d\-]/', '', $theValue)) : "0";
            break;
        case "intn":
            $theValue = ($theValue != "") ? intval(preg_replace('/[^\.\d\-]/', '', $theValue)) : "NULL";
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval(preg_replace('/[^\.\d\-]/', '', $theValue)) . "'" : "'0'";
            break;
        case "doublev":
            $theValue = ($theValue != "") ? doubleval(preg_replace('/[^\.\d\-]/', '', $theValue)) : "0";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : 'NULL';
            break;
        case "datev":
            $theValue = ($theValue != "") ? $theValue : 'NULL';
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}

function getlatlng($addr) {
    $cd = array("", "");
    if (trim($addr) != "") {
        $GMapKey = "ABQIAAAAWGoA092fePkhhiEY0rf1yBTpj45uO--m0lzzdKL3ubdaD7llgBRXzpWTuPTYC2Pd4he39-K_lz4Z9A";
        $Gmap = "http://maps.google.com/maps/geo?q=" . urlencode($addr) . "&output=csv&key={$GMapKey}";
        $locs = file_get_contents($Gmap);
        $ret = explode(',', $locs);
        $cd = array(0, 0);
        if ($ret[0] == "200" && $ret[1] > 6) {
            $cd[0] = $ret[2];
            $cd[1] = $ret[3];
        }
    }
    return $cd;
}

function GMap($lat, $lng, $zoom, $map, $marks, $w, $h) {
    $Gmap = "http://maps.google.com/maps/api/staticmap?center={$lat},{$lng}&markers=icon:http://exood.com/images/mapicon.png|shadow:true|{$marks}&format=png32&zoom={$zoom}&size={$w}x{$h}&maptype={$map}&sensor=false";
    return file_get_contents($Gmap);
}

function _xmail($to, $fromwho, $from, $replytowho, $replyto, $subject, $attach, $message, $altMsg, $mailkind = 1, $MTAcode = 1, $mmime = 'text/plain') { #'text/html'
    if ($mailkind == 2) {
        ini_set("include_path", ".:" . ROOT . "/lib/phpmailer");
        require_once (ROOT . "/lib/phpmailer/class.phpmailer.php");
        $mail = new PHPMailer();
        switch ($MTAcode) { // telling the class what to use
            case 1:
                $mail->IsMail();
                break;
            case 2:
                $mail->IsSendmail();
                break;
            case 3:
                $mail->IsQmail();
                break;
            case 4:
                $mail->IsSMTP();
                $mail->Host = SMTP_SERVER; // SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASS;
                break;
        }
        $mail->AddAddress($to);
        $mail->ContentType = $mmime;
        $mail->From = $from;
        $mail->FromName = $fromwho;
        $mail->Sender = $from;
        $mail->AddReplyTo($replyto, $replytowho);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $altMsg;
        $mail->WordWrap = 50;
        $mail->AddEmbeddedImage('logo.jpg', 'logoimg', 'logo.jpg'); // attach file logo.jpg, and later link to it using identfier logoimg
        $mailval = $mail->Send();
        $mail->ClearAddresses();
        $mail->ClearAttachments();
    } else {
        wordwrap($message, 50);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: ' . $mmime . "\r\n";
        $headers .= 'From: ' . $fromwho . '<' . $from . '>' . "\r\n";
        $mailval = mail($to, $subject, $message, $headers);
    }
    return $mailval;
}

function countryCityFromIP($ipAddr) {
    $ipDetail = array('city' => "", 'country' => "", 'country_code' => "");
    if (ip2long($ipAddr) != -1 || ip2long($ipAddr) === true) {
        $xml = file_get_contents("http://api.hostip.info/?ip=" . $ipAddr);
        if ($xml) {
            preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si", $xml, $match);
            $ipDetail['city'] = isset($match[2]) ? $match[2] : '';
            preg_match("@<countryName>(.*?)</countryName>@si", $xml, $match);
            $ipDetail['country'] = isset($match[1]) ? $match[1] : '';
            preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si", $xml, $cc_match);
            $ipDetail['country_code'] = $cc_match[1];
        }
    }
    return $ipDetail;
}

function setorderstr($orderval, $qryvals) {
    if ($qryvals != "") {
        $ordstr = "";
        if (preg_match('/^\, SQRT\(POW\(/', $qryvals) > 0) {
            $ordstr .= ", `km` ";
        }
        if (preg_match('/ AS score$/', $qryvals) > 0) {
            $ordstr .= ", `score` DESC";
        }
        $ordstr = substr($ordstr, 2);
        $orderval .= $orderval == "" ? " ORDER BY {$ordstr}" : ", {$ordstr}";
    }
    return $orderval;
}

/**
 * Insert searched keywords into database
 * @param modid int
 * @param keyword string
 * @param short_words array  | array of word not to insert into the database e.g array('the','and');
 * @return boolean
 */
function insert_searched_keywords($modid, $keyword, $short_words = '') {
    global $dbh;
    if ($short_words != '' && !is_array($short_words)) {
        // Short words list must be an array of words
        trigger_error("Short word list must be an array", E_USER_WARNING);
    }
    // Get user details from ip address
    $IPDetail = countryCityFromIP($_SERVER['REMOTE_ADDR']);

    $city = GSQLStr($IPDetail['city'], "text");
    $country = GSQLStr($IPDetail['country'], "text");
    $ip = GSQLStr($_SERVER['REMOTE_ADDR'], "text");
    // Split searched words into array of word
    $words = explode(" ", $keyword);
    $SQLData = array();
    foreach ($words as $word) {
        $word = strtolower($word);
        // I first check if the word is not in short words list
        if (!in_array($word, $short_words))
            $SQLData[] = "(NOW(),'$word','$ip','$city','$country','$modid')";
    }
    $query = 'INSERT INTO searched_keywords (time_search,txtword,ip_from,city,country,modid)VALUES' . implode(',', $SQLData);

    $result = &$dbh->query($query);

    // Check for error
    if (PEAR::isError($result)) {
        trigger_error($result->userinfo, E_USER_ERROR);
    }
    return true;
}

/**
 * create short words in db
 * @param words array | e.g array('in','as')
 */
function insert_short_words($words) {
    global $dbh;
    $sqldata = array();
    foreach ($words as $w) {
        $w = strtolower($w);
        $sqldata[] = "('$w')";
    }
    $sql = "INSERT INTO short_words(word)VALUES" . implode(',', $sqldata);
    $result = &$dbh->query($sql);
    if (PEAR::isError($result)) {
        trigger_error($result->userinfo, E_USER_WARNING);
        return false;
    }
}

/**
 * get list of short words from db
 */
function get_short_words() {
    global $dbh;
    $out = array();
    $words = $dbh->getAll("SELECT DISTINCT word FROM short_words");
    foreach ($words as $row) {
        $out[] = strtolower($row['word']);
    }
    unset($words);
    return $out;
}

function bbgetSubStr($str, $key1, $key2, $start, $limit = 99999999) {
    $dir = "";
    $morestring = true;
    $j = strlen($key1);
    $x = stripos($str, $key1, $start);
    $y = stripos($str, $key2, $x);
    if ($x != -1 && $y != -1 && $y < $limit) {
        $dir = substr($str, $x + $j, $y);
    } else
        $morestring = false;
    return dir;
}

function compress($str, $tag) {
    //require_once(CLASSES_DIR."compressor.class.php");
    //require_once(FUNCTIONS_DIR."php_ext.php");
    //$zipit = new Compressor();
    //$out =  $zipit->compress(str2byteArray($str));
    //$zipped = $tag ? ZIPTAG.byteArray2str($out).ZIPTAG : byteArray2str($out);
    return $str;
}

function decompress($str) {
    require_once(CLASSES_DIR . "compressor.class.php");
    require_once(FUNCTIONS_DIR . "php_ext.php");
    $zipit = new Compressor();
    $in = $zipit->decompress(str2byteArray($str));
    return byteArray2str($in);
}

function getWebPAYStatus($cadp, $mertid, $trxnref) {
    require_once ("HTTP/Request.php");
    $xmlstr = <<< EOD
<?xml version='1.0' encoding='utf-8'?>
<soap:Envelope xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/'>
<soap:Body>
<getStatus xmlns='http://webpay.interswitchng.com/webpay/'>
<CADPID>%s</CADPID>
<MERTID>%s</MERTID>
<TXNREF>%s</TXNREF>
</getStatus>
</soap:Body>
</soap:Envelope>
EOD;

    $data = sprintf($xmlstr, $cadp, $mertid, $trxnref);
    $url = "http://webpay.interswitchng.com/webpayservice_pilot/webpay.asmx";
    $req = new HTTP_Request($url);
    $req->addHeader("Content-Type", "text/xml; charset=utf-8");
    $req->addHeader("SOAPAction", "http://webpay.interswitchng.com/webpay/getStatus");
    $req->addHeader("Content-Length", strlen($data));
    $req->setMethod(HTTP_REQUEST_METHOD_POST);
    $req->addRawPostData($data, true);
    $req->sendRequest();
    return $req->getResponseBody();
}

function setOrderTitle($title, $url, $key, $ord, $asc = 0, $loc = '', $fn = '') {
    $asc = $ord == $key ? $asc : 0;
    $url .= set_QS("orderval=$key&asc=$asc");
    $lnk = $loc == '' ? "$url" : "javascript: void(0)\" onclick=\"$('#$loc').load('$url'$fn)";
    if ($ord == $key && $asc > 0) {
        $gif = $asc == 1 ? 'a' : 'de';
        return "<a class=\"boldwhite1\" href=\"$lnk\">$title<img src=\"/images/{$gif}scend.gif\" width=\"10\" height=\"10\" border=\"0\">";
    } else {
        return "<a class=\"boldwhite1\" href=\"$lnk\">$title</a>";
    }
}

function qryfind($ses = "", $posts = array()) {
    global $qryvals;
    $posted = false;
    foreach ($posts as $post) {
        if (isset($_POST[$post])) {
            $posted = true;
            break;
        }
    }
    if ($posted || !isset($_SESSION["qryvals_$ses"])) {
        $dr = dirname($_SERVER['PHP_SELF']) . DS;
        require_once(ROOT . "{$dr}sql.php");
        $qryvals = findRec();
        $_SESSION["qryvals_$ses"] = $qryvals;
    } else {
        $qryvals = _xses("qryvals_$ses");
    }
}

function preOrd($pg, $sort) {
    global $ord, $asc, $orderval;
    $ord = intval(_xget('orderval'));
    $asc = intval(_xget('asc'));
    
    $ord = $ord == 0 ? intval(_xses("{$pg}_ord")) : $ord;
    $_SESSION["{$pg}_ord"] = $ord;

    $ord_asc = $asc == 1 ? ' DESC' : '';

    if ($ord == 0 || $asc == 2) {
        $_SESSION["{$pg}_ord"] = "";
        $ord_asc = "";
        $ord = 0;
    }
    $orderval = strlen($sort[$ord]) > 0 ? " ORDER BY " .  str_replace(',', " $ord_asc,", $sort[$ord]) . $ord_asc : "";
    if (++$asc == 3) {
        $asc = 0;
    }
}

function strbrief($str, $len) {
    $str = mysql_escape_string($str);
    return strlen($str) < $len ? $str : substr($str, 0, $len) . '...';
}

function Rand_chr($len) {
    $possible = '2345789acCdEfFghHJkLmMnNpPrRsStTwWxYzZ#$%^&*@+=?';
    $code = '';
    $i = 0;
    while ($i < $len) {
        $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        $i++;
    }
    return $code;
}

function _xplode($gum, $str) {
    if (strlen($str) > 0) {
        return explode($gum, $str);
    } else
        return array();
}

function Explode_3($str, $gum = '#', $glue = '&', $evo = '@') {
    $Tab = array();
    if (strlen($str) > 0) {
        $rows = explode($gum, $str);
        foreach ($rows as $row) {
            if (stripos($row, $glue) === false)
                array_push($Tab, $row);
            else {
                $rowTab = array();
                $subs = explode($glue, $row);
                foreach ($subs as $sub) {
                    if (stripos($sub, $evo) === false)
                        array_push($rowTab, $sub);
                    else
                        array_push($rowTab, explode($evo, $sub));
                }
                array_push($Tab, $rowTab);
            }
        }
    }
    return $Tab;
}

function Taboom($instr, $gum, $even = true) {
    $Tab = Tabomb($instr, $gum, $even);
    return is_array($Tab) ? $Tab : (strlen($Tab) == 0 ? array() : array($Tab));
}

function Tabomb($instr, $gum, $evenTree) {
    $order = array("\r\n", "\n", "\r");
    $str = str_replace($order, '', $instr);
    $Tab = array();
    $glue = array_shift($gum);
    if ($glue == null || strlen($str) == 0 && !$evenTree) {
        return $str;
    } elseif (stripos($str, $glue) === false) {
        array_push($Tab, Tabomb($str, $gum, $evenTree));
    } else {
        $rows = explode($glue, $str);
        foreach ($rows as $row) {
            array_push($Tab, Tabomb($row, $gum, $evenTree));
        }
    }
    return !$evenTree && count($Tab) == 1 && !is_array($Tab[0]) ? $Tab[0] : $Tab;
}

function TabHole($mul_Tab, $gum) {
    $dark_mata = "";
    if (count($mul_Tab) > 0) {
        foreach ($mul_Tab as $row_Tab) {
            $dark_mata .= $gum[0] . (is_array($row_Tab) ? TabHole($row_Tab, array_slice($gum, 1)) : $row_Tab);
        }
        $dark_mata = substr($dark_mata, strlen($gum[0]));
    }
    return $dark_mata;
}

function Implode_3($TList, $gum = '#', $glue = '&', $evo = '@') {
    $dirstr = "";
    if (count($TList) > 0) {
        foreach ($TList as $row_TList) {
            $dirstr .= $gum . (is_array($row_TList) ? implode($glue, $row_TList) : $row_TList);
        }
        $dirstr = substr($dirstr, strlen($gum));
    }
    return $dirstr;
}

function TabExplode($str, $gum = '~~##~~', $glue = '~#~') {
    $Tab = array();
    if (strlen($str) > 0) {
        $rows = explode($gum, $str);
        foreach ($rows as $row) {
            array_push($Tab, explode($glue, $row));
        }
    }
    return $Tab;
}

function TabImplode($TList, $x = 0, $gums = array('~~||~~', '~~##~~', '~#~', '^~^', '~~~')) {
    $dirstr = "";
    if (count($TList) > 0) {
        foreach ($TList as $row_TList)
            $dirstr .= $gums[$x] . (!is_array($row_TList) ? $row_TList :
                            (count($row_TList) == 0 ? ":NULL:" : TabImplode($row_TList, $x + 1, $gums)));
        $dirstr = substr($dirstr, strlen($gums[$x]));
    }
    return $dirstr;
}

function TabKeyImplode($TList, $x = 0, $gums = array('~~||~~', '~~##~~', '~#~', '^~^', '~~~'), $kys = array('<<>>', '|||', '%%%', '^^^', '|^|')) {
    $dirstr = "";
    if (count($TList) > 0) {
        foreach ($TList as $row_TList)
            $dirstr .= $gums[$x] . (is_array($row_TList) ? (count($row_TList) == 0 ? ":NULL:" : TabKeyImplode($row_TList, $x + 1, $gums, $kys)) : $row_TList);

        $dirstr = RowKeyImplode($TList, $kys[$x]) . $dirstr;
    }
    return $dirstr;
}

function TabKey_Implode($TList, $x = 0, $gums = array('~~||~~', '~~##~~', '~#~', '^~^', '~~~'), $kys = array('<<>>', '|||', '%%%', '^^^', '|^|')) {
    $dirstr = "";
    if (count($TList) > 0) {
        foreach ($TList as $key => $row_TList) {
            $dirstr .= $gums[$x] . $key . $kys[$x] . (!is_array($row_TList) ? $row_TList :
                            (count($row_TList) == 0 ? "" : TabKey_Implode($row_TList, $x + 1, $gums, $kys)));
        }
        $dirstr = substr($dirstr, strlen($gums[$x]));
    }
    return $dirstr;
}

function RowImplode($row, $glue = '~-~', $gum = '^~^', $elk = '^^^', $keys = false) {
    $str = "";
    if ($row != null && count($row) > 0) {
        foreach ($row as $elem)
            $str .= $glue . (is_array($elem) ? ($keys ? RowKeyImplode($elem, $elk) . $gum : "") . implode($gum, $elem) : $elem);
        $str = substr($str, strlen($glue));
    }
    return $str;
}

function RowKeyImplode($row, $elm = '%%%') {
    $str = "";
    if ($row != null && count($row) > 0) {
        foreach ($row as $key => $elem) {
            $str .= $elm . $key;
        }
        $str = substr($str, strlen($elm));
    }
    return $str;
}

function TabKeyExplode($str, $x = 0, $gums = array('~~||~~', '~~##~~', '~#~', '^~^', '~~~'), $kys = array('<<>>', '|||', '%%%', '^^^', '|^|')) {
    $Tab = array();
    if (strlen($str) > 0) {
        $rows = explode($gums[$x], $str);
        $keys = explode($kys[$x], $rows[0]);
        for ($i = 1; $i < count($rows); $i++)
            $Tab[$keys[$i - 1]] = strpos($rows[$i], $gums[$x + 1]) !== false ? TabKeyExplode($rows[$i], $x + 1, $gums, $kys) : ($rows[$i] == ":NULL:" ? array() : $rows[$i]);
    }
    return $Tab;
}

function TabKey_Explode($str, $x = 0, $gums = array('~~||~~', '~~##~~', '~#~', '^~^', '~~~'), $kys = array('<<>>', '|||', '%%%', '^^^', '|^|')) {
    $Tab = array();
    if (strlen($str) > 0) {
        $rows = explode($gums[$x], $str);
        for ($i = 0; $i < count($rows); $i++) {
            $v = explode($kys[$x], $rows[$i]);
            $Tab[$v[0]] = count($gums) > $x + 1 && strpos($v[1], $gums[$x + 1]) !== false ?
                    TabKey_Explode($v[1], $x + 1, $gums, $kys) : ($v[1] == ":NULL:" ? array() : $v[1]);
        }
    }
    return $Tab;
}

function makepure(&$rows, $key, $glue) {
    foreach ($rows as $row) {
        $row = str_replace($glue[0], $glue, $row);
        $row = str_replace($glue[1], '~~##~~', $row);
    }
}

function sec2hms($ms) {
    $hms = "";
    if ($ms > 1000) {
        $tm = $ms / 1000;
        $hrs = round($tm / 60 * 60);
        if ($hrs > 0)
            $hms = "{$hrs}h ";

        $tm = $tm % (60 * 60);
        $mins = round($tm / 60);
        if ($mins > 0)
            $hms .= "{$mins}m ";

        $secs = $tm % 60;
        if ($secs > 0)
            $hms .= "{$secs}s";
    } elseif ($ms > 0)
        $hms = "{$ms}ms";
    return $hms;
}

function writeDevices($fname, $Devs) {
    $str = addcslashes(TabKeyImplode($Devs), '"\\');
    exec("echo \"$str\" | sudo tee " . SET_DIR . $fname);
}

function readDevices($fname) {
    $str = readSetting($fname);
    return strlen($str) <= 3 ? array() : TabKeyExplode($str);
}

function sudoRead($fname) {
    exec("if sudo bash -c \"[[ -f $fname ]]\" ; then sudo cat $fname ;  else echo '' ; fi", $file);
    return $file;
}

function readSetting($fname) {
    return xreadFile(SET_DIR . $fname);
}
