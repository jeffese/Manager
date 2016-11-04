<?php

$request = new HttpRequest();
$request->setUrl('https://api.infobip.com/sms/1/text/single');
$request->setMethod(HTTP_METH_POST);

$auth = base64_encode("evrs:zmOe9N9q");
$reg = $sms_vars['licenceno'];
$exp = $sms_vars['enddate'];
$num = $sms_vars['PhoneNumber'];
$snd = intval($sms_vars['MachineTime']);

if (substr($num, 0, 1) == '0') {
    $num = '234' . substr($num, 1);
} elseif (substr($num, 0, 1) == '+') {
    $num = substr($num, 1);
}

$request->setHeaders(array(
    'accept' => 'application/json',
    'content-type' => 'application/json',
    'authorization' => "Basic $auth"
));

$msgs = array("Kano State welcomes you to it's Electronic Vehicle Registration System (eVRS). We will send reminders before your license for vehicle $reg expires",
    "Your license for vehicle $reg expires in 1 month. [ $exp ]",
    "Your license for vehicle $reg expires in 3 days. [ $exp ]");
$msg = $msgs[$snd];

$request->setBody('{  
   "from":"EVRS",
   "to":"' . $num . '",
   "text":"' . $msg . '"
}');

try {
    $response = $request->send();
    if ($response->getResponseCode() == 200) {
        $resp = json_decode($response->getBody(), true);
        $status = $resp['messages'][0]['status'];
        if ($status['groupId'] == 0) {
            $snd++;
            $sql = "UPDATE `{$_SESSION['DBCoy']}`.`items_srv_sched` SET `MachineTime`='$snd' 
                        WHERE `SrvSchedID`={$sms_vars['SrvSchedID']}";
            runDBQry($dbh, $sql);
            array_push($xMessages, array("SMS Delivery [{$status['groupName']}]", $status['description']));
        } else {
            array_push($errors, array("SMS Error [{$status['groupName']}]", $status['description']));
        }
    }
} catch (HttpException $ex) {
    array_push($errors, array("SMS Error", $ex));
}
