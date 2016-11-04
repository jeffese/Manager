<?php require_once('../init.php');

	$tab_id = GSQLStr(_xget('t'), "int");
	$item_id = GSQLStr(_xget('i'), "int");
	
	$From = "FROM exood_comments LEFT JOIN users ON exood_comments.uid=users.userid WHERE exood_comments.table_id = {$tab_id} AND exood_comments.itemid = {$item_id}";
	$sql = "SELECT exood_comments.*, users.pix {$From} ORDER BY exood_comments.level_text";	

$currentPage = "/scripts/functions/comments";
$maxRows_comments = 5;

$TabArray = 'comments';
require_once (ROOT.'/scripts/fetchdata.php');

$votes = _xses('votes_200');
$jstr = ""; 
?>
<link href="/css/style001.css" rel="stylesheet" type="text/css">
<link href="/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/scripts/functions/comment_functions.js"></script>
<table width="100%" border="0" cellpadding="4" cellspacing="0">
  <tr>
    <td style="border:#0C0 ridge 2px"><b><span class="header">Comments</span></b>
    </td>
  </tr>
  <tr>
    <td align="right" style="border:ridge 1px #090"><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="center" class="darkgrey"><b><span class="darkgreen"> Comments: </span><span class="red-normal"><?php echo ($startRow_comments +
1) ?></span><span class="darkgreen"> to <b><span class="red-normal"><?php echo min($startRow_comments + $maxRows_comments, $totalRows_comments); ?></span></b></span><span class="darkgreen"> of </span><span class="red-normal"><b><?php echo $totalRows_comments ?></b></span></b></td>
        <td align="center" class="darkgrey"><table border="0" cellpadding="2" cellspacing="2">
          <tr>
            <td align="center"><?php if ($pageNum_comments > 0) { // Show if not first page ?>
              <a class="darkgreen" onclick="loadhtml('<?php echo $currentPage."/".$tab_id."_".$item_id."/".$totalRows_comments; ?>_0.htm', 'commentbox'); tmchk = setInterval('chkloaded()',2000);" href="javascript: void(0)"><img src="/images/first.png" alt="" width="24" height="24" /><br />
                First</a>
              <?php } // Show if not first page ?></td>
            <td align="center"><?php if ($pageNum_comments > 0) { // Show if not first page ?>
              <a class="darkgreen" onclick="loadhtml('<?php echo $currentPage."/".$tab_id."_".$item_id."/".$totalRows_comments."_".
max(0, $pageNum_comments - 1); ?>.htm', 'commentbox'); tmchk = setInterval('chkloaded()',2000);" href="javascript: void(0)"><img src="/images/prev.png" alt="" width="24" height="24" /><br />
                Previous</a>
              <?php } // Show if not first page ?></td>
            <td align="center"><?php if ($pageNum_comments < $totalPages_comments) { // Show if not last page ?>
              <a class="darkgreen" onclick="loadhtml('<?php echo $currentPage."/".$tab_id."_".$item_id."/".$totalRows_comments."_".
max(0, $pageNum_comments + 1); ?>.htm', 'commentbox'); tmchk = setInterval('chkloaded()',2000);" href="javascript: void(0)"><img src="/images/btnext.png" alt="" width="24" height="24" /><br />
                Next</a>
              <?php } // Show if not last page ?></td>
            <td align="center"><?php if ($pageNum_comments < $totalPages_comments) { // Show if not last page ?>
              <a class="darkgreen" onclick="loadhtml('<?php echo $currentPage."/".$tab_id."_".$item_id."/".$totalRows_comments."_".$totalPages_comments; ?>.htm', 'commentbox'); tmchk = setInterval('chkloaded()',2000);" href="javascript: void(0)"><img src="/images/last.png" alt="" width="24" height="24" /><br />
                Last</a>
              <?php } // Show if not last page ?></td>
          </tr>
        </table></td>
        <td align="center" class="darkgrey"><b>Go To Page:
          <select name="cmbpage0" class="darkgrey" id="cmbpage0" onchange="if (this.value!='') loadhtml('<?php echo
$currentPage."/".$tab_id."_".$item_id."/".$totalRows_comments; ?>_'+this.value+'.htm', 'commentbox'); tmchk = setInterval('chkloaded()',2000);">
          </select>
        </b></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php if (count($comments) == 0) { ?>
      <div class="comment_box">
        <div class="comment_body">
          <div class="blue-normal"><b>Let`s hear from you</b></div>
          <div class="darkgrey">Be the first person to comment on this item</div>
        </div>
      </div>
      <?php echo catch_error($errors);
      } else {
	  foreach ($comments as $comment) {
		$w = 60 * substr_count($comment['level_text'], '-');
		$posted_on = date('Y-m-d H:i:s');
		$canvote1 = strpos($votes, '#'.$comment['id'].'#1_');
		$canvote0 = strpos($votes, '#'.$comment['id'].'#-1_');
         
		if ($canvote1 === false && $canvote0 === false) {
			$voteup = 'src="/images/thumbs_up.png "title="Vote Up" onclick="votes(1, '.$comment['id'].', 200)"';
			$votedn = 'src="/images/thumbs_dn.png "title="Vote Down" onclick="votes(-1, '.$comment['id'].', 200)"';
		} elseif ($canvote1>-1) {
			$voteup = 'src="/images/thumbs_up1.png "title="Voted Up"';
			$votedn = 'src="/images/thumbs_dn0.png "title="Voted Up"';
		} elseif ($canvote0>-1) {
			$voteup = 'src="/images/thumbs_up0.png "title="Voted Down"';
			$votedn = 'src="/images/thumbs_dn1.png "title="Voted Down"';
		}			
		?>
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width='2'><img src='/images/spacer.gif' width='<?php echo $w ?>' height='1' /></td>
                <td width='60' align="center" valign="top"><img src="<?php echo $comment['pix']== '' ? '/images/no_pix1.png' : PROFILEPIX_DIR.$comment['uid'].'/xxx'.$comment['pix'].'.jpg'; ?>" /><br /><span class="brown-normal"><?php echo $comment['uid'] ?></span></td>
                <td width="100%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="callout_toplft"></td>
                    <td class="callout_top"></td>
                    <td class="callout_toprgt"></td>
                  </tr>
                  <tr>
                    <td class="callout_lft"><img src="/images/callout_pt.jpg" width="21" height="33" /></td>
                    <td class="callout_cnt darkgrey" id="cmt<?php echo $comment['id'] ?>"><?php 
					$cmtbdy = ""; 
					if ($comment['status']==1) { 
						$cmtbdy = $comment['body'];
					} elseif ($comment['status']==-1) {
						$cmtbdy = "## Comment deleted by Admin ##";
					} elseif ($comment['status']==0) {
						$cmtbdy = "## Comment deleted by User ##";
					}
					$jstr .= "cmt".$comment['id'].' = "'.$cmtbdy."\"; document.getElementById('cmt".$comment['id']."').innerHTML = cmt".$comment['id']."; "; ?></td>
                    <td class="callout_rgt"></td>
                  </tr>
                  <tr>
                    <td class="callout_botlft"></td>
                    <td class="callout_bot"></td>
                    <td class="callout_botrgt"></td>
                  </tr>
                </table>
                  <div class="black-normal"></div>
                  <div align="right" id="lnk<?php echo $comment['id'] ?>" class="shopitemdet" style=" vertical-align:bottom"><span id="tm<?php echo $comment['id'] ?>"><?php $jstr .= "document.getElementById('tm".$comment['id']."').innerHTML = timepast('".$posted_on."', '".$comment['created_on']."', false); "; ?>"</span>
                    <?php if (_xses('userid')==$comment['uid'] && $comment['status']==1) { ?>
                    <a href="javascript: void(0)" onclick="setfrm('Edit', <?php echo $comment['pid'] ?>, '<?php echo $comment['level_text'] ?>', '<?php echo $comment['id'] ?>', cmt<?php echo $comment['id'] ?>, tab_id, item_id)" class="shopitemdet" style='margin-left:15px'><img src="/images/b_edit.png" width="16" height="16" /><img src="/images/but_edit.png" width="60" height="20" alt="Edit" /></a><a href="javascript: void(0)" onclick="if (confirm('Are you sure you want to delete this comment?')) delcmt(<?php echo $comment['id'] ?>)" class="shopitemdet" style='margin-left:15px'><img src="/images/b_drop.png" width="16" height="16" /><img src="/images/but_del.png" width="60" height="20" alt="Delete" /></a>
                    <?php } ?>
                    <a href="javascript: void(0)" onclick="setfrm('reply', <?php echo $comment['pid'] ?>, '<?php echo $comment['level_text'] ?>', '<?php echo $comment['id'] ?>', '', tab_id, item_id)" class="shopitemdet" style="margin-left:15px"><img src="/images/reply.png" width="16" height="16" />Reply</a></div>
                <iframe id="tbox<?php echo $comment['id'] ?>" width="100%" height="260" style="display:none; border:none" scrolling="No"></iframe></td>
                <td valign="top"><iframe style="display:none" id="booth200_<?php echo $comment['id'] ?>"></iframe><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="2" align="center" bgcolor="#84E47C" class="darkgreen" id="votes200_<?php echo $comment['id'] ?>"><?php echo $comment['voteup']-$comment['votedn'] ?></td>
                  </tr>
                  <tr>
                    <td style="padding:3px" id="vtupx200_<?php echo $comment['id'] ?>"><img width="16" height="16" <?php echo $voteup ?> /></td>
                    <td style="padding:3px" id="vtdpx200_<?php echo $comment['id'] ?>"><img width="16" height="16" <?php echo $votedn ?> /></td>
                  </tr>
                  <tr>
                    <td align="center" id="vtup200_<?php echo $comment['id'] ?>" class="blue-normal"><?php echo $comment['voteup'] ?></td>
                    <td align="center" id="vtdn200_<?php echo $comment['id'] ?>" class="red-normal"><?php echo $comment['votedn'] ?></td>
                  </tr>
                </table></td>
              </tr>
            </table>
           <?php }} ?>
          </td>
  </tr>
  <tr>
    <td><a id="newcmt" href="javascript: void(0)" onclick="setfrm('new', 0, '0', 0, '', tab_id, item_id)"><b><span class="red-normal">Post Comment</span></b></a><iframe id="tbox0" width="100%" height="260" style="display:none; border:none" scrolling="no"></iframe>
</td>
  </tr>
  <tr>
    <td align="right" style="border:ridge 1px #090"><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="center" class="darkgrey"><b><span class="darkgreen"> Comments: </span><span class="red-normal"><?php echo ($startRow_comments +
1) ?></span><span class="darkgreen"> to <b><span class="red-normal"><?php echo min($startRow_comments + $maxRows_comments, $totalRows_comments); ?></span></b></span><span class="darkgreen"> of </span><span class="red-normal"><b><?php echo $totalRows_comments ?></b></span></b></td>
        <td align="center" class="darkgrey"><table border="0" cellpadding="4" cellspacing="2">
          <tr>
            <td align="center"><?php if ($pageNum_comments > 0) { // Show if not first page ?>
              <a class="darkgreen" onclick="loadhtml('<?php echo $currentPage."/".$tab_id."_".$item_id."/".$totalRows_comments; ?>_0.htm', 'commentbox'); tmchk = setInterval('chkloaded()',2000);" href="javascript: void(0)"><img src="/images/first.png" alt="" width="24" height="24" /><br />
                First</a>
              <?php } // Show if not first page ?></td>
            <td align="center"><?php if ($pageNum_comments > 0) { // Show if not first page ?>
              <a class="darkgreen" onclick="loadhtml('<?php echo $currentPage."/".$tab_id."_".$item_id."/".$totalRows_comments."_".
max(0, $pageNum_comments - 1); ?>.htm', 'commentbox'); tmchk = setInterval('chkloaded()',2000);" href="javascript: void(0)"><img src="/images/prev.png" alt="" width="24" height="24" /><br />
                Previous</a>
              <?php } // Show if not first page ?></td>
            <td align="center"><?php if ($pageNum_comments < $totalPages_comments) { // Show if not last page ?>
              <a class="darkgreen" onclick="loadhtml('<?php echo $currentPage."/".$tab_id."_".$item_id."/".$totalRows_comments."_".
max(0, $pageNum_comments + 1); ?>.htm', 'commentbox'); tmchk = setInterval('chkloaded()',2000);" href="javascript: void(0)"><img src="/images/btnext.png" alt="" width="24" height="24" /><br />
                Next</a>
              <?php } // Show if not last page ?></td>
            <td align="center"><?php if ($pageNum_comments < $totalPages_comments) { // Show if not last page ?>
              <a class="darkgreen" onclick="loadhtml('<?php echo $currentPage."/".$tab_id."_".$item_id."/".$totalRows_comments."_".$totalPages_comments; ?>.htm', 'commentbox'); tmchk = setInterval('chkloaded()',2000);" href="javascript: void(0)"><img src="/images/last.png" alt="" width="24" height="24" /><br />
                Last</a>
              <?php } // Show if not last page ?></td>
          </tr>
        </table></td>
        <td align="center" class="darkgrey"><b>Go To Page:
          <select name="cmbpage1" class="darkgrey" id="cmbpage1" onchange="if (this.value!='') loadhtml('<?php echo
$currentPage."/".$tab_id."_".$item_id."/".$totalRows_comments; ?>_'+this.value+'.htm', 'commentbox'); tmchk = setInterval('chkloaded()',2000);">
            </select>
	<?php $jstr .= " navpagesjump(document.getElementById('cmbpage0'), ".$totalPages_comments.", ".$pageNum_comments."); ";
		  $jstr .= " navpagesjump(document.getElementById('cmbpage1'), ".$totalPages_comments.", ".$pageNum_comments."); ";
	?>
        </b></td>
        </tr>
    </table></td>
  </tr>
</table>
<div id="js" style="display:none"><?php echo $jstr ?></div>