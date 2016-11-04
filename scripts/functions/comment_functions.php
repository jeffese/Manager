<?php /**
 * @author Exood.com
 * @copyright 2009 by Exood.com
 */

/**
 * Create new comment
 * 
 * @param $title | the title of the comment
 * @param $body | the main body of the comment
 * @param $modid | module id (the section of the site)
 * @param $pid | parent id of the content pid = id
 * @param $level | level which comment is posted
 * @param $itemid | id of the item commented on
 * @param $uid | user id (string)
 * @param $uname | full name of the autor
 * @param $dbh | database connection handle

 * /** Post new comment */
function post_comment($body, $modid, $itemid, $uid, $uname) {
	global $dbh;
	$ip = long2ip(getIp());
	$sql = "INSERT INTO exood_comments (pid, level_text, table_id, itemid, uid, user_name, ip, status, created_on, modified_on, title, body) VALUES ('0', '0', {$modid}, {$itemid}, '{$uid}', '{$uname}', '{$ip}', 1, NOW(), NOW(), '', {$body})";
	$ref = runDBqry($dbh, $sql);
	$lastId = mysqli_insert_id($dbh);
	$parent_level = substr(''.(1000 + $lastId), 1);
	$ref = runDBqry($dbh, "UPDATE exood_comments SET level_text = '{$parent_level}' WHERE id ={$lastId}");
	return $ref;
}

function reply_post($post_id, $p_level, $body, $modid, $itemid, $uid, $uname) {
	global $dbh;
	$ip = long2ip(getIp());
	$sql = "INSERT INTO exood_comments (pid, level_text, table_id, itemid, uid, user_name, ip, status, created_on, modified_on, title, body) VALUES ({$post_id}, '0', $modid, $itemid, '$uid', '$uname', '{$ip}', 1, NOW(), NOW(), '', {$body})";
	runDBqry($dbh, $sql);
	$lastId = mysqli_insert_id($dbh);
	$my_level = $p_level.'-'.$lastId;
	$ref = runDBqry($dbh, "UPDATE exood_comments SET level_text = '{$my_level}' WHERE id ={$lastId}");
	return $ref;
}

function update_comment($id, $uid, $body) {
	global $dbh;
	$sql = "UPDATE exood_comments SET body = {$body} WHERE id = $id AND uid = '{$uid}'";
	$ref = runDBqry($dbh, $sql);
	return $ref;
}

function update_comment_status($id, $uid, $status) {
	global $dbh;
	$sql = "UPDATE exood_comments SET status = {$status} WHERE id = $id AND uid = '{$uid}'";
	$ref = runDBqry($dbh, $sql);
	return $ref;
}

function update_comment_cnt($t, $id) {
	global $dbh;
	$tabs = getTabNamefromID($t);
	$tab = $tabs['tabname'];
	$key = $tabs['keyfield'];
	$sql = "UPDATE {$tab} SET comments = comments + 1 WHERE {$key} = $id";
	runDBqry($dbh, $sql);
}

function delete_comment($id, $uid) {
	global $dbh;
	$sql = "DELETE FROM exood_comments WHERE id = $id AND uid = '{$uid}'";
	$ref = runDBqry($dbh, $sql);
	return $ref;
}

/** Remove all digits e.g 0-9 */
function remove_digit($s) {
	return preg_replace('([0-9])', '', $s);
}

/** Remove dash e.g -*/
function remove_dashes($s) {
	return preg_replace('(-)', '', $s);
}
 ?>