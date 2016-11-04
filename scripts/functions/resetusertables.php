<?php require_once('../bset.php');
$adcode='0'; ?>
<?php

global $dbh;

$sql = "SELECT users2.*,userdetails2.* FROM users2 INNER JOIN userdetails2 ON users2.userid=userdetails2.userid";
$sql1 = "INSERT INTO users (userid, userpass, usercat, detail, title, fname, mname, lname, email, creation_date, last_login, login_cnt, activation_code, status) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$sql2 = "INSERT INTO userdetails (exoodid, picture, gender, dob, occupation, stateoforigin, nationality, nativetongue, info, phone1, phone2, website, address, city, `state`, country, lat, lng, persdet, adddet, workdet) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$users = getDBData($dbh, $sql);

for ($i=0; $i<count($users); $i++) {
	$pass = hashPwd($users[$i]['userpass'], $users[$i]['userid']);
	$activation_code = md5(randomString());
	$param1 = array(
			$users[$i]['userid'], 
			$pass, 
			1, 
			$users[$i]['detail'], 
			$users[$i]['title'], 
			$users[$i]['fname'], 
			$users[$i]['mname'], 
			$users[$i]['lname'], 
			$users[$i]['email'], 
			date('Y-m-d H:i:s'), 
			date('Y-m-d H:i:s'), 
			0, 
			$activation_code, 
			0);
		$set = runDBQry($dbh, $sql1, $param1);
			
			if ($users[$i]['pixfile']!='') {
				$pixcode = newpixr(ROOT.PROFILEPIX_DIR, "", $users[$i]['userid'], 1, array(600, 200, 120, 40), $users[$i]['pixfile']);
				$pixfile = $pixcode['pixcode'];
			} else $pixfile = '';
			
			$xid = getLastId($dbh);
			$param2 = array(
			$xid,
			$pixfile, 
			$users[$i]['gender'], 
			$users[$i]['dob'], 
			$users[$i]['occupation'], 
			$users[$i]['stateoforigin'], 
			$users[$i]['nationality'], 
			$users[$i]['nativetongue'], 
			'',
			$users[$i]['phone1'], 
			$users[$i]['phone2'], 
			$users[$i]['website'], 
			$users[$i]['address'], 
			$users[$i]['city'], 
			$users[$i]['state'], 
			$users[$i]['country'], 
			0, 0, 
			$users[$i]['persdet'], 
			$users[$i]['addressdet'], 
			$users[$i]['occupdir']);
			
	$set = runDBQry($dbh, $sql2, $param2);
}

?>