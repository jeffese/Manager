<?php

require_once("../../scripts/init.php");
require_once("../../classifications/sql.php");

$cred = getLDAP($_SESSION['coyid']);
$grps = array();

if (ldap_bind($cred['ldap'], $cred['ad_user'] . $cred['dom'], $cred['ad_pass'])) {
    $filter = "(objectClass=organizationalunit)";
    $attributes = array("memberof", "ObjectGUID");
    $result = ldap_search($cred['ldap'], $cred['dn'], $filter, $attributes);
    if ($result) {
        $entries = ldap_get_entries($cred['ldap'], $result);
        for ($m = 0; $m < $entries['count']; $m++) {
            $entry = explode(',', $entries[$m]['dn']);
            for ($e = count($entry) - 1; $e >= 0; $e--) {
                if (substr($entry[$e], 0, 3) == 'OU=' && ($val = substr($entry[$e], 3)) != 'Domain Controllers') {
                    $entry[$e] = $val;
                } else {
                    array_pop($entry);
                }
            }
            if ($entry) {
                array_unshift($entry, $entries[$m]['objectguid'][0]);
                array_push($grps, $entry);
            }
        }
    }
    ldap_unbind($cred['ldap']);
}

$lst = array();
$sort = array();

foreach ($grps as $OU) {
    $o = count($OU) - 1;
    $cat = $OU[$o];
    $par = '1';
    while (--$o >= 1) {
        if ($o == 1)
            $par = $cat;
        $cat .= ' &gt; ' . $OU[$o];
    }
    array_push($lst, array($OU[0], $OU[1], $par, $cat));
    array_push($sort, $cat);
}

array_multisort($sort, SORT_ASC, SORT_STRING, $lst);

foreach ($lst as $OU) {
    $sql = sprintf("SELECT `catID`, `category_id`, `catname` 
                    FROM `{$_SESSION['DBCoy']}`.`classifications` 
                    WHERE binary `guid`=%s AND `catype`=1", //
            GSQLStr($OU[0], 'text'));
    $dept = getDBDataRow($dbh, $sql);

    if ($dept == NULL) {
        if ($OU[2] != '1') {
            $OU[2] = getDBDataFldkey($dbh, "{$_SESSION['DBCoy']}.`classifications`", 'catname', 'category_id', "'$OU[2]'");
        }
        if ($OU[2]) {
            $catid = getCatid("{$_SESSION['DBCoy']}.`classifications`", $OU[2]);
            $sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`classifications` (`category_id`, `parent_id`, `category_name`, 
                    `catname`, `catype`, `cat_tag`, `description`, `code`, `guid`) 
                    VALUES ('$catid', '$OU[2]', '$OU[1]', %s, 1, 31, '', '', %s)", //
                    GSQLStr($OU[3], 'text'), //
                    GSQLStr($OU[0], 'text'));
            $insert = runDBQry($dbh, $sql);
        }
    } elseif ($OU[3] != $dept['catname']) {
        $OU[2] = getDBDataFldkey($dbh, "{$_SESSION['DBCoy']}.`classifications`", 'catname', 'category_id', "'$OU[2]'");
        if ($OU[2]) {
            $catid = getCatid("{$_SESSION['DBCoy']}.`classifications`", $OU[2]);
            $catn = $OU[3];
            $oldcat = $dept['category_id'];
            $oldcatn = $dept['catname'];
            $oldlen = strlen($oldcat);
            $catlen = strlen($oldcatn);

            $sql = "INSERT INTO `{$_SESSION['DBCoy']}`.`classifications` (`category_id`, `parent_id`) 
                    VALUES ('{$catid}-tmp', '1')";
            $insert = runDBQry($dbh, $sql);
            $sql = "UPDATE `{$_SESSION['DBCoy']}`.`classifications` SET 
                        `tmp_par`=`parent_id`, `parent_id`='{$catid}-tmp'
                        WHERE `parent_id`='$oldcat' OR `parent_id` LIKE '$oldcat-%'";
            runDBQry($dbh, $sql);

            $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`classifications` SET category_id='$catid', parent_id='$OU[2]', 
                        category_name='$OU[1]', catname=%s WHERE catID={$dept['catID']}", //
                    GSQLStr($OU[3], 'text'));
            $update = runDBQry($dbh, $sql);
            if ($update > 0) {
                $sql = "UPDATE `{$_SESSION['DBCoy']}`.`classifications` SET 
                            `parent_id`=INSERT(`tmp_par`, 1, $oldlen, '$catid'),
                            `category_id`=INSERT(`category_id`, 1, $oldlen, '$catid'),
                            `catname`=INSERT(`catname`, 1, $catlen, '$catn') 
                            WHERE `parent_id`='{$catid}-tmp'";
                runDBQry($dbh, $sql);

                $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`classifications`
                        WHERE `category_id`='{$catid}-tmp'";
                runDBQry($dbh, $sql);
            }
        }
    }
}

header("Location: index.php");
