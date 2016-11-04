<?php require_once('../bset.php'); 
$adcode='0'; ?>
<?php

$pager_options = array(
    'mode'       => (intval(_xget('m'))==1)? 'Sliding': 'Jumping',
    'perPage'    => intval(_xget('p')),
    'delta'      => intval(_xget('d')),
);
$tab_id = intval(_xget('t'));
$item_id = intval(_xget('i'));

$comments = list_comment($tab_id, $item_id, $dbh);  
	
?>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href="/css/style001.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="/scripts/js/behavior.js"></script>
<script type="text/javascript" language="javascript" src="/scripts/js/rating.js"></script>
<script type="text/javascript" language="javascript" src="/scripts/js/basicroutines.js"></script>
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td bgcolor="#C2DBE0"><div class="header">Comments</div></td>
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
 $vote = rating_bar('x'.$tab_id.$comment['id'], $tab_id, 10, $can_vote );
 
 # The Edit link (check is user_id is eq to comment user_id, enable edititng)
 $replyLink = " | <a href=?action=edit&id={$comment['id']}&pid={$comment['pid']}&_level={$comment['level_text']}&itemid={$comment['itemid']}&modid={$comment['table_id']}#do{$comment['id']} style='color:blue'>Edit</a>"; 
$replyLink = (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['uid']) ? $replyLink : '';

$posted_on =date('Y-m-d H:i:s');
echo <<<_HTML
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td rowspan='2' width='2'><img src='/images/spacer.gif' width='{$w}' height='1' /></td>
    <td colspan='2' class='comment_head'><div>By <strong>{$comment['uid']}</strong> 
	<script type="text/javascript">document.write(timepast("{$posted_on}","{$comment['created_on']}",false));</script> 
    <a href="?action=reply&id={$comment['id']}&pid={$comment['pid']}&_level={$comment['level_text']}&itemid={$comment['itemid']}&modid={$comment['table_id']}#do{$comment['id']}" style="color:blue; float:right; margin-left:15px"><img src="/images/reply.png" width="16" height="16" />Reply</a>  {$vote}</td>
  </tr>
  <tr class='comment_text'>
    <td width='40' valign="top"><img width="40" height="40" src="/images/no_pix1.png" /></td>
    <td width="560" valign="top">
	{$comment['body']}	
	
	<div style="text-align:right; color:gray; font-size:9px;>"> $replyLink</div>
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
                <td><img src="/scripts/captacha.php" /><br />
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
                <td class="darkgreen"><b>Write your comment here
                </b></td>
              </tr>
              <tr>
                <td><textarea name="com_body" id="com_body" style="width:400px; height:40px; border:#CCCCCC solid 1px"></textarea></td>
              </tr>
              <tr>
                <td class="darkgreen"><b><img src="/scripts/captacha.php" /><br />
                  Type the above text into the below box<br />
                  <input type="text" name="captVar" id="captVar" />                
                  </b></td>
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