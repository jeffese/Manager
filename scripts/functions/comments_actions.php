<?php require_once ('../init.php');
require_once ('comment_functions.php');
$ht1 = "<html><head><link href='/css/style001.css' rel='stylesheet' type='text/css' /></head><body><div style='background-color:#303030; width:100%; height:200px; padding-top:50px; color:#FFFFFF; font-weight:bolder; font-size:14px' align='center'>";
$ht2 = "</div></body></html>";
if (_xpost('xpost') != '') {
	if (strlen(_xpost('com_body')) > 1) {
		$cid = _xpost('cid');
		$jscript = "<br><a class='red-normal' href='javascript: void(0)' onclick='parent.hideit(\"#tbox".$cid."\")'>[x]Close</a>";
		if (vetcaptcha() == true) {
			$title = "";
			$TAB_ID = GSQLStr(_xpost('tabid'), "int");
			$pid = GSQLStr(_xpost('pid'), "int");
			$ITEM_ID = GSQLStr(_xpost('itemid'), "int");
			$level = GSQLStr(_xpost('level'), "textv");
			$userid = isset($_SESSION['userid']) ? _xses('userid') : 'Anonymous';
			$user_name = isset($_SESSION['name']) ? _xses('name') : 'Anonymous';
			$bdy = GSQLStr(_c(_xpost('com_body')), "textv");
			$body = "'".$bdy."'";
			$ref = "There was an error posting your comment. Please try again later. . .";
			if (_xpost('xpost') == 'new') {
				$jscript = "<br><a class='red-normal' href='javascript: void(0)' onclick=\"parent.setfrm('new', 0, '0', 0, '')\">[x]Close</a>";
				if (post_comment($body, $TAB_ID, $ITEM_ID, $userid, $user_name) > 0) {
					update_comment_cnt($TAB_ID, $ITEM_ID);
					$ref = "Your Comment has been posted.";
				}
			} elseif (_xpost('xpost') == 'reply') {
				if (reply_post($cid, $level, $body, $TAB_ID, $ITEM_ID, $userid, $user_name) > 0) {
					update_comment_cnt($TAB_ID, $ITEM_ID);
					$ref = "Your Comment has been posted.";
				}
			} elseif (_xpost('xpost') == 'Edit') {
				if (update_comment($cid, $userid, $body) > 0) $ref = "Your edit has been posted.<script type='text/javascript'>cmt".$cid." = \"".$bdy."\"; parent.document.getElementById('cmt".$cid."').innerHTML = cmt".$cid."; </script>";
			}
			echo $ht1.$ref.$jscript.$ht2;
			exit;
		} else {
			echo $ht1;
			catch_error($errors);
			echo $jscript.$ht2;
			exit;
		}
	}
} elseif (_xget('x') == '0') {
	$cid = _xget('id');
	if (update_comment_status($cid, _xses('userid'), 0) > 0) {
		echo $ht1."Your Comment has been deleted<br><a class='red-normal' href='javascript: void(0)' onclick='parent.hideit(\"#tbox".$cid."\")'>[x]Close</a><script type='text/javascript'>parent.document.getElementById('lnk".$cid."').style.display = 'none'; parent.document.getElementById('cmt".$cid."').innerHTML='## Comment deleted by User ##'; </script>".$ht2;
	} else {
		echo $ht1."There was an error deleting your comment. Please try again later. . . .<br><a class='red-normal' href='javascript: void(0)' onclick='parent.hideit(\"#tbox".$cid."\")'>[x]Close</a>".$ht2;
	}
} else  echo "error..... wrong request"; ?>