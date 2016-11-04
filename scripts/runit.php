<?php
session_start(); // we need session to access our captcha variable
require_once 'init.php';


# $dbh is the pear database handle we use to fetch the array from db
# list_comment()  return false if database error occure
# list_comment return array default

# We need to below parameters to do paging stuff
# mode = Slide 
# perPage = the amount of item to show per page
# delta = how many digits to strip from the beginning
$pager_options = array(
    'mode'       => 'Sliding',
    'perPage'    => 15,
    'delta'      => 2,
);

# MODE ID
$TAB_ID = 1;
$ITEM_ID = 1;

$comments = list_comment($TAB_ID, $ITEM_ID, $dbh); 

/**
 * Is time to process reply or post
 */
 if(isset($_GET['action']) and $_GET['action'] == 'reply') {
 
 	// Post is a reply
	if(isset($_POST['com_body']) && strlen($_POST['com_body']) > 5 ) {
 	$comments_errors = array();
 
	if(!isset($_POST['captVar']) || !isset($_SESSION['captchacode'])) $comments_errors[] = "ERROR: security image is not set";
 
	 if(trim($_POST['captVar']) != $_SESSION['captchacode']) $comments_errors[] = "Spamming is not allowed, please enter the random string value";
	 
	 if(sizeof($comments_errors) == 0) {
 	// No error in our script
	// Just strip html and go
	$title = isset($_POST['com_body']) ? $_POST['com_body'] : '';

	$pid = isset($_GET['pid']) ?  $_GET['pid'] : 0;
	$level = isset($_GET['_level']) ? strip_tags($_GET['_level']) : '0';

	$userid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'siteuser';
	$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'site user'; 
    
	$ref = reply_post(strip_tags($_GET['id']), _c($_POST['com_body']), $TAB_ID, $ITEM_ID, $userid, $user_name);
	//post_comment(_c($_POST['com_body']), $TAB_ID, $ITEM_ID, $userid, $user_name);
	//create_comment(_c($title), _c($_POST['com_body']), $TAB_ID, get_id_from_pid($pid, $dbh), $level, $ITEM_ID, $userid, $user_name,  $dbh);
  	
	// Lets do some few check here
	if(false == $ref) {
		// Error
		$comments_errors[] = "SYSTEM ERROR: Please try again later";
	}else{
	echo "<center><h1>Thank you, your comment has been posted.</h1><h2> <a href='?'>click here</a> or wait for the system to redirect in 5 seconds</h2></center>";
	echo '<meta http-equiv="refresh" content="4;URL=?" />';
	exit;
	}
	}
	}
 
	
} else {

 if(isset($_POST['com_body']) && strlen($_POST['com_body']) > 5 ) {
 $comments_errors = array();
 
if(!isset($_POST['captVar']) || !isset($_SESSION['captchacode'])) $comments_errors[] = "ERROR: security image is not set";
 
 if(trim($_POST['captVar']) != $_SESSION['captchacode']) $comments_errors[] = "Spamming is not allowed, please enter the random string value";

 
 if(sizeof($comments_errors) == 0) {
 	// No error in our script
	// Just strip html and go
	$title = isset($_POST['com_body']) ? $_POST['com_body'] : '';

	$pid = isset($_GET['pid']) ?  $_GET['pid'] : 0;
	$level = isset($_GET['_level']) ? strip_tags($_GET['_level']) : '0';

	$userid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'siteuser';
	$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'site user'; 
    
	$ref = post_comment(_c($_POST['com_body']), $TAB_ID, $ITEM_ID, $userid, $user_name);
	//create_comment(_c($title), _c($_POST['com_body']), $TAB_ID, get_id_from_pid($pid, $dbh), $level, $ITEM_ID, $userid, $user_name,  $dbh);
  	
	// Lets do some few check here
	if(false == $ref) {
		// Error
		$comments_errors[] = "SYSTEM ERROR: Please try again later";
	}else{
	echo "<center><h1>Thank you, your comment has been posted.</h1><h2> <a href='?'>click here</a> or wait for the system to redirect in 5 seconds</h2></center>";
	echo '<meta http-equiv="refresh" content="4;URL=?" />';
	exit;
	}
}
 }
} 	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Exood Run comment</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="js/behavior.js"></script>
<script type="text/javascript" language="javascript" src="js/rating.js"></script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td><div style="font-size:24px; color:#666666">Exood Comment System</div> <a href="?">RESET</a></td>
  </tr>
  <tr>
    <td>
    <?php
	if(is_object($comments)) {
echo <<<EOD
    <div class="comment_box">
    <div class="comment_body">
	  <div class="comment_head"><span>COMMENT SYSTEM IS DOWN</span></div>
    <div class="content">You can not make comment at this time due to system break down, we are fixing it. Please check again in a few minutes</div>
    </div>
    </div>
    </td>
EOD;
	}elseif(sizeof($comments['data']) == 0) {
	// No body has commented
echo <<<EOD
    <div class="comment_box">
    <div class="comment_body">
	  <div class="comment_head"><span>Let`s hear from you</span></div>
    <div class="content">Be the first person to comment on this item</div>
    </div>
    </div>
    </td>
EOD;
	}else{
	?>
  <div class="comment_box">
    <div class="comment_body">
    <div class="content">
 <?php
 foreach($comments['data'] as $comment) {
 
 # Width of the box
 $w = 20 * substr_count($comment['level_text'], '-');
 
 # Check if user can vote, set to static if session is not set
 $can_vote = isset($_SESSION['user_id']) ? '' : 'static';
 
  # The voting box
 $vote = rating_bar('x'.$TAB_ID.$comment['id'], $TAB_ID, 10, $can_vote );
 
 # The Edit link (check is user_id is eq to comment user_id, enable edititng)
 $replyLink = " | <a href=?action=edit&id={$comment['id']}&pid={$comment['pid']}&_level={$comment['level_text']}&itemid={$comment['itemid']}&modid={$comment['table_id']}#do{$comment['id']} style='color:blue'>Edit</a>"; 
$replyLink = (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['uid']) ? $replyLink : '';

 	 //echo "<div class='comment_level'></div><div><div><span></span></div></div>";
$posted_on = sprintf( '<font color="#666666" size="1">%s</font>', date('F j\t\h, Y, g:i a', strtotime($comment['created_on'])));
echo <<<_HTML
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td rowspan='2' width='2'><img src='images/spacer.gif' width='{$w}' height='1' /></td>
    <td colspan='2' class='comment_head'> <font color="gray"><strong>{$comment['title']}</strong></font> by {$comment['uid']} on $posted_on</td>
  </tr>
  <tr class='comment_text'>
    <td width='40' valign="top"><img width="40" height="40" src="images/no_pix1.png" /></td>
    <td width="560" valign="top">
	{$comment['body']}
	
	{$vote}
	<div style="text-align:right; color:gray; font-size:9px;>"><a href="?action=reply&id={$comment['id']}&pid={$comment['pid']}&_level={$comment['level_text']}&itemid={$comment['itemid']}&modid={$comment['table_id']}#do{$comment['id']}" style="color:blue">Reply</a> $replyLink</div>
	</td>
  </tr>
</table>
_HTML;
if(isset($_GET['id']) && $_GET['id'] == $comment['id']) {
echo <<<_HTML
<div class="comment_box">
      <div class="comment_body">
        <div class="content">
		<a name="do{$comment['id']}"></a>
_HTML;

		 if(isset($comments_errors)) {
		  echo "<div class='error'>";
		  foreach($comments_errors as $error) {
		  echo "$error<br />";
		  }
		  echo "</div>";
		  }
echo '		  
         <a name="commentfrom" id="commentfrom"></a> 
          <form id="comFrm" name="comFrm" method="post" action="">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
              
              <tr>
                <td><strong>Write your comment here</strong><br /></td>
              </tr>
              <tr>
                <td><textarea name="com_body" id="com_body" style="width:400px; height:40px; border:#CCCCCC solid 1px"></textarea></td>
              </tr>
              <tr>
                <td><img src="captacha.php" /><br />
                      <strong>Type the above text into the below box</strong><br />
                  <input type="text" name="captVar" id="captVar" /></td>
              </tr>
              <tr>
                <td><a href="#" onclick="document.comFrm.submit();"><img src="images/img_submit.jpg" width="82" height="25"  border="0"/></a></td>
              </tr>
            </table>
            </form>
          </div>
        </div>
    </div>';
 }
 }
 ?>
 </div>
   <div class="comment_head"><span><?php echo $comments['links']; ?></span></div>
 </div>
 </div>

 <?php
 }
 ?> </td>
  </tr>
  <?php if(!isset($_GET['action']) || $_GET['action'] != 'reply') { ?>
  <tr>
    <td>
    <div class="comment_box">
      <div class="comment_body">
        <div class="content">
         <?php
		 if(isset($comments_errors) ) {
		  echo "<div class='error'>";
		  foreach($comments_errors as $error) {
		  echo "$error<br />";
		  }
		  echo "</div>";
		  }
		  ?>
         <a name="commentfrom" id="commentfrom"></a> 
          <form id="comFrm" name="comFrm" method="post" action="">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td><strong>Write your comment here</strong><br /></td>
              </tr>
              <tr>
                <td><textarea name="com_body" id="com_body" style="width:400px; height:40px; border:#CCCCCC solid 1px"></textarea></td>
              </tr>
              <tr>
                <td><img src="captacha.php" /><br />
                      <strong>Type the above text into the below box</strong><br />
                  <input type="text" name="captVar" id="captVar" /></td>
              </tr>
              <tr>
                <td><a href="#" onclick="document.comFrm.submit();"><img src="/images/img_submit.jpg" width="82" height="25"  border="0"/></a></td>
              </tr>
            </table>
            </form>
            
          </div>
        </div>
    </div>
    </td>
  </tr>
  <?php } ?>
</table>
</body>
</html>
