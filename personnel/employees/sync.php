<?php

require_once("../../scripts/init.php");

$cred = getLDAP($_SESSION['coyid']);
$pips = array();

if (ldap_bind($cred['ldap'], $cred['ad_user'] . $cred['dom'], $cred['ad_pass'])) {
    $filter = "(objectcategory=person)";
    $attributes = array("ObjectGUID", "samaccountname", "dn", "sn", "givenname", "initials", "manager");
    $result = ldap_search($cred['ldap'], $cred['dn'], $filter, $attributes);
    if ($result) {
        $entries = ldap_get_entries($cred['ldap'], $result);
        for ($m = 4; $m < $entries['count']; $m++) {
            $name = array(isset($entries[$m]['sn']) ? $entries[$m]['sn'][0] : '',
                isset($entries[$m]['givenname']) ? $entries[$m]['givenname'][0] : '',
                isset($entries[$m]['initials']) ? $entries[$m]['initials'][0] : '');
            $entry = explode(',', $entries[$m]['dn']);
            $OU = array();
            for ($e = count($entry) - 1; $e >= 0; $e--) {
                if (substr($entry[$e], 0, 3) == 'OU=') {
                    array_push($OU, substr($entry[$e], 3));
                }
            }
            $ou = implode(' &gt; ', $OU);
            $dept = getDBDataFldkey($dbh, "{$_SESSION['DBCoy']}.`classifications`", 'catname', 'catID', "'$ou'");
            $oga = isset($entries[$m]['manager']) ? $entries[$m]['manager'][0] : '';
            array_push($pips, array($entries[$m]['objectguid'][0], $name, $dept ? $dept : 1, $entries[$m]['samaccountname'][0], $oga));
        }
        
        for ($x = 0; $x < count($pips); $x++) {
            if (strlen($pips[$x][4]) != 0) {
                $dn = explode(',', $pips[$x][4]);
                $cn = array_shift($dn);
                $attributes = array("ObjectGUID", "samaccountname");
                $result = ldap_search($cred['ldap'], implode(',', $dn), $cn, $attributes);
                if ($result) {
                    $entries = ldap_get_entries($cred['ldap'], $result);
                    if ($entries['count'] == 1) {
                        $pips[$x][4] = $entries[0]['objectguid'][0];
                    }
                }
            } else {
                $pips[$x][4] = '0';
            }
        }
    }
    ldap_unbind($cred['ldap']);
}

foreach ($pips as $pip) {
    if (strlen($pip[4]) != 1) {
        $sql = sprintf("SELECT `VendorID` FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE binary `guid`=%s", //
                GSQLStr($pip[4], 'text'));
        $supv = getDBDataRow($dbh, $sql);
        
        $pip[4] = $supv ? $supv['VendorID'] : 0;
    }
    $sql = sprintf("SELECT `VendorID`, `ContactLastName`, `ContactFirstName`, `ContactMidName`, `DeptID`, `guid` 
                    FROM `{$_SESSION['DBCoy']}`.`vendors` 
                    WHERE binary `guid`=%s", //
            GSQLStr($pip[0], 'text'));
    $staff = getDBDataRow($dbh, $sql);

    if ($staff == NULL) {
        $sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`vendors` 
            (`VendorType`, `ClientType`, `InUse`, 
            `ContactLastName`, `ContactFirstName`, `ContactMidName`, `ContactTitle`, 
            `DeptID`, `currency`, `credit`, `guid`, `supervisor`) VALUES
            (5, 1, 1, %s, %s, %s, '', $pip[2], {$_SESSION['COY']['currency']}, 0, %s, %s)", //
                GSQLStr($pip[1][0], 'text'), //
                GSQLStr($pip[1][1], 'text'), //
                GSQLStr($pip[1][2], 'text'), //
                GSQLStr($pip[0]   , 'text'), //
                GSQLStr($pip[4]   , 'int'));
        runDBQry($dbh, $sql);

        $id = mysql_insert_id();
        if ($id > 0) {
            $sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`users` 
                        (`username`, `userpass`, `usergroup`, `EmployeeID`, `active`) 
                        VALUES (%s, '', 'Basic', %s, 1)", GSQLStr($pip[3], "text"), $id);
            $insert = runDBQry($dbh, $sql);
        }
    } else {
        if ($pip[1][0] != $staff['ContactLastName'] ||
                $pip[1][1] != $staff['ContactFirstName'] ||
                $pip[1][2] != $staff['ContactMidName'] ||
                $pip[2] != $staff['DeptID'] ||
                $pip[4] != $staff['supervisor']) {
            $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`vendors` SET 
                        `ContactLastName`=%s, `ContactFirstName`=%s, `ContactMidName`=%s,
                        DeptID=$pip[2], `supervisor`=%s WHERE `VendorID`={$staff['VendorID']}", //
                    GSQLStr($pip[1][0], 'text'), //
                    GSQLStr($pip[1][1], 'text'), //
                    GSQLStr($pip[1][2], 'text'), //
                    GSQLStr($pip[4]   , 'int'));
            runDBQry($dbh, $sql);
        }

        $sql = "SELECT username FROM `{$_SESSION['DBCoy']}`.`users` 
                    WHERE `EmployeeID`={$staff['VendorID']}";
        $row_TUsers = getDBDataRow($dbh, $sql);
        if ($row_TUsers == NULL) {
            $sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`users` 
                        (`username`, `userpass`, `usergroup`, `EmployeeID`, `active`) 
                        VALUES (%s, '', 'Basic', %s, 1)", GSQLStr($pip[3], "text"), $staff['VendorID']);
            $insert = runDBQry($dbh, $sql);
        } elseif ($row_TUsers['username'] != $pip[3]) {
            $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`users` SET `username`=%s
                        WHERE `EmployeeID`=%s", //
                    GSQLStr($pip[3], 'text'), //
                    $staff['VendorID']);
            runDBQry($dbh, $sql);
        }
    }
}

header("Location: index.php");
