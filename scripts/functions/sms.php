<?php

/**
 * @author Jeffrey
 * @copyright 2010
 * @Usage: sendSMS("Sender Name or Number", "Message", "Comma Delimited phone Numbers", "Default Country Code", $flash = 1|0{Yes|No}, $sentime = ""|DateTime, $mthd = 1|2{Post|Get})
 * @Usage: Get Delivery Report: getDelvReport()
 * 
 */
$username = "jeffreyese@gmail.com";
$password = "tcrotsadc";

function multiPhones($recipient, $msgid, $cntry_code) {
    $arr_recipient = explode(',', $recipient);
    $recipients = "";
    $i = 0;

    for ($i = 0; $i < count($arr_recipient); $i++) {
        $mobilenumber = $arr_recipient[$i];
        if (substr($mobilenumber, 0, 1) == '0') {
            $mobilenumber = $cntry_code . substr($mobilenumber, 1);
        } elseif (substr($mobilenumber, 0, 1) == '+') {
            $mobilenumber = substr($mobilenumber, 1);
        }
        $arr_recipient[$i] = $mobilenumber;
    }
    array_unique($arr_recipient);

    foreach ($arr_recipient as $mobilenumber) {
        $recipients .= "<gsm messageId='{$msgid}_{$i}'>{$mobilenumber}</gsm>\n";
        $i++;
    }

    return $recipients;
}

function sendSMS($sender, $message, $recipients, $cntry_code = "234", $flash = 1, $sentime = "", $mthd = 1) {
    global $dbh, $username, $password;
    $recipients = preg_replace('/\s/e', ' ', $recipients);
    $response = "";
    $uid = isset($_SESSION['exoodid']) ? $_SESSION['exoodid'] : 0;
    $sql = "INSERT INTO `exood_talk`.`sms_sent_logs` (`userid`, `req_time`, `message`, `recipients`, `status`) VALUES ({$uid}, NOW(), '{$message}', '{$recipients}', '')";
    if (runDBqry($dbh, $sql) == 1) {
        $msgid = mysqli_insert_id($dbh);

        switch ($mthd) {
            case 1:
                $url = "http://www.50kobo.com/tools/xml/Sms.php";
                require_once ("HTTP/Request.php");
                $xmlrecipients = multiPhones($recipients, $msgid, $cntry_code);
                $xmlstr = "<SMS>
	<authentification>
		<username>{$username}</username>
		<password>{$password}</password>
	</authentification>
	<message>
		<sender>{$sender}</sender>
		<msgtext>{$message}</msgtext>
		<flash>{$flash}</flash>
		<sendtime>{$sentime}</sendtime>
	</message>
	<recipients>
		{$xmlrecipients}
	</recipients>
</SMS>";

                $req = new HTTP_Request($url);
                $req->addHeader("Content-Type", "text/xml; charset=utf-8");
                $req->setMethod(HTTP_REQUEST_METHOD_POST);
                $req->addRawPostData($xmlstr, true);
                $req->sendRequest();
                $response = $req->getResponseBody();
                break;
            case 2:
                $url = "http://www.50kobo.com/tools/geturl/Sms.php";
                $response = file_get_contents("{$url}?username={$username}&password={$password}&sender={$sender}&message={$message}&flash={$flash}&sendtime={$sentime}&recipients={$recipients}");
                break;
        }
        $response = intval(preg_replace('/(<\/?[\w\s]+>)|(\s*)/e', '', $response));
        $sql = "UPDATE `exood_talk`.`sms_sent_logs` SET `status`='{$response}' WHERE `msg_id`={$msgid}";
        runDBqry($dbh, $sql);
    } else
        $response = "-100";

    switch ($response) {
        case - 1:
            return "Incorrect / badly formed XML data";
            break;
        case - 2:
            return "Incorrect username and/or password";
            break;
        case - 3:
            return "Not enough credit units in user account";
            break;
        case - 4:
            return "Invalid sender name";
            break;
        case - 5:
            return "No valid recipient";
            break;
        case - 6:
            return "Invalid message length/No message content";
            break;
        case - 10:
            return "Unknown/Unspecified error";
            break;
        case - 100:
            return "System error";
            break;
        case 100:
            return "Send successful";
            break;
    }
}

function infoBip($user, $pass, $sender, $to, $msg) {
    if (substr($to, 0, 1) == '0') {
        $to = '234' . substr($to, 1);
    } elseif (substr($to, 0, 1) == '+') {
        $to = substr($to, 1);
    }
    
    $request = new HttpRequest();
    $request->setUrl('https://api.infobip.com/sms/1/text/single');
    $request->setMethod(HTTP_METH_POST);

    $request->setHeaders(array(
        'accept' => 'application/json',
        'content-type' => 'application/json',
        'authorization' => 'Basic ' . base64_encode("$user:$pass")
    ));

    $request->setBody('{  
   "from":"' . addcslashes($sender, '"') . '",
   "to":"' . $to . '",
   "text":"' . addcslashes($msg, '"') . '"
}');

    try {
        $response = $request->send();

        echo $response->getBody();
    } catch (HttpException $ex) {
        echo $ex;
    }
}

function getSubStr($str, $key1, $key2, $start, $limit = 99999999) {
    $dir = "";
    $morestring = true;
    $j = strlen($key1);
    $x = stripos($str, $key1, $start);
    if ($x !== false)
        $y = stripos($str, $key2, $x);
    if ($x !== false && $y !== false && $y < $limit) {
        $dir .= " " . substr($str, $x + $j, $y - $x - $j);
    } else
        $morestring = false;
    return $dir;
}

function getDelvReport() {
    global $dbh, $username, $password;
    $str = file_get_contents("http://www.50kobo.com/tools/getdr.php?username={$username}&password={$password}");
    getSubStr($str, "<gsm messageId='", "'>", 0, $limit = 99999999);
}

?>
