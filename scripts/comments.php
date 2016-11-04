<?php if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Frm")) {
  $insertSQL = sprintf("INSERT INTO comments (com_pid, com_rootid, com_itemid, com_created, com_uid, com_ip, com_text) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GSQLStr(0, "int"),
                       GSQLStr($_POST['com_id'], "int"),
                       GSQLStr($_POST['com_itemid'], "int"),
                       GSQLStr(date('Y-m-d H:i:s'), "text"),
                       GSQLStr($_COOKIE['userid'], "text"),
                       GSQLStr($_SERVER['REMOTE_ADDR'], "text"),
                       GSQLStr($_POST['comment'], "text"));  mysql_select_db($database_itestify, $itestify);
  $Result1 = mysql_query($insertSQL, $itestify) or die(mysql_error());  $recid = mysql_insert_id($itestify);
  mysql_select_db($database_itestify, $itestify);
	$updateSQL = sprintf("UPDATE comments SET com_rootid=com_id WHERE com_rootid=0 AND com_id=%s", GSQLStr($recid, "int"));
	$Result1 = mysql_query($updateSQL, $itestify) or die(mysql_error());
	
  $GoTo = $editFormAction;
  header(sprintf("Location: %s", $GoTo));
  exit;
}$maxRows_comt = 20;
$pageNum_comt = 0;
if (isset($_GET['pageNum_comt'])) {
  $pageNum_comt = $_GET['pageNum_comt'];
}
$startRow_comt = $pageNum_comt * $maxRows_comt;
mysql_select_db($database_itestify, $itestify);
$query_comt = "SELECT comments.*, users.pixfile FROM comments INNER JOIN users ON comments.com_uid=users.userid WHERE com_itemid = ".$colname_media." ORDER BY com_rootid, com_created";
$query_limit_comt = sprintf("%s LIMIT %d, %d", $query_comt, $startRow_comt, $maxRows_comt);
$comt = mysql_query($query_limit_comt, $itestify) or die(mysql_error());if (isset($_GET['totalRows_comt'])) {
  $totalRows_comt = $_GET['totalRows_comt'];
} else {
  $all_comt = mysql_query($query_comt);
  $totalRows_comt = mysql_num_rows($all_comt);
}
$totalPages_comt = ceil($totalRows_comt/$maxRows_comt)-1;
$queryString_comt = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_comt") == false && 
        stristr($param, "totalRows_comt") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_comt = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_comt = sprintf("&totalRows_comt=%d%s", $totalRows_comt, $queryString_comt);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/style001.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript">
var pixrot = '<embed src="imagerotator.swf" width="640" height="360" allowscriptaccess="always" allowfullscreen="false" showicons ="true" shownavigation  ="true" flashvars="file=pix.php?d=<?php echo $row_media['media_id']; ?>" /></embed>';function setcom(com,id) {
	document.getElementById('com'+com).innerHTML='<form name="form'+com+'" method="POST" action="<?php echo $FormAction ?>"><table width="100%" border="0" cellspacing="0" cellpadding="4"><tr><td><textarea name="comment" cols="32" rows="3" id="comment" style="width:550px"></textarea></td></tr><tr><td class="red-normal"><input type="<?php echo ((isset($_COOKIE['userid']))? 'submit':'button'); ?>" value="Post Comment" name="but" id="but" /><?php echo ((isset($_COOKIE['userid']))? '':'Sign in to post a comment'); ?></td></tr></table><input type="hidden" name="MM_insert" value="Frm" /><input type="hidden" name="com_id" id="com_id" value="'+id+'" /><input name="com_itemid" type="hidden" id="com_itemid" value="<?php echo $row_media['media_id']; ?>" /></form>';
}</script>
</head><body>
<table border="0" cellpadding="4" cellspacing="4">
  <tr>
    <td colspan="2" class="header headbot">Comments</td>
  </tr>
  <tr style="">
    <td colspan="2" align="right"><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <?php $j=0; while ($row_comt = mysql_fetch_assoc($comt)) { 
			  $j++; 
			  if ($row_comt['com_rootid']==$row_comt['com_id']) {
			  ?>
      <tr>
        <td width="40" rowspan="2" valign="top" class="cellbot"><img src="<?php echo (($row_comt['pixfile']=='') ? '/images/no_picture.jpg' : '/profilepix/x'.$row_comt['pixfile']); ?>" name="userpix" width="40" height="40" id="userpix" /></td>
        <td colspan="2" nowrap="nowrap" class="darkgreen"><strong><?php echo $row_comt['com_uid']; ?></strong> || 
          <script language="JavaScript" type="text/javascript">document.write(timepast('<?php echo date('Y-m-d H:i:s'); ?>', '<?php echo $row_comt['com_created']; ?>', false));</script></td>
        <td width="45" nowrap="nowrap"><a href="javascript: void(0);" onclick="setcom('<?php echo $j, "', '", $row_comt['com_id']; ?>');" class="red-normal"><img src="/images/prev01.gif" width="16" height="16" /><strong>reply</strong></a></td>
      </tr>
      <tr>
        <td colspan="3" class="blue-normal cellbot"><?php echo $row_comt['com_text']; ?></td>
      </tr>
      <tr>
        <td colspan="4" valign="top"><div id="com<?php echo $j ?>"></div></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td rowspan="2">&nbsp;</td>
        <td width="40" rowspan="2" class="blue-normal cellbot"><img src="<?php echo (($row_comt['pixfile']=='') ? '/images/no_picture.jpg' : '/profilepix/x'.$row_comt['pixfile']); ?>" name="userpix" width="40" height="40" id="userpix2" /></td>
        <td width="451" class="blue-normal"><strong><?php echo $row_comt['com_uid']; ?></strong> ||
          <script language="JavaScript" type="text/javascript">document.write(timepast('<?php echo date('Y-m-d H:i:s'); ?>', '<?php echo $row_comt['com_created']; ?>', false));</script></td>
        <td nowrap="nowrap" class="blue-normal"><a href="javascript: void(0);" onclick="setcom('<?php echo $j, "', '", $row_comt['com_rootid']; ?>');" class="red-normal"><img src="/images/prev01.gif" width="16" height="16" /><strong>reply</strong></a></td>
      </tr>
      <tr>
        <td colspan="2" class="blue-normal cellbot"><?php echo $row_comt['com_text']; ?></td>
      </tr>
      <tr>
        <td colspan="4" valign="top"><div id="com<?php echo $j ?>"></div></td>
      </tr>
      <?php }} ?>
    </table></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;
        <table border="0">
          <tr>
            <td class="darkpurple"><strong>
              <?php if ($pageNum_comt > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_comt=%d%s", $currentPage, 0, $queryString_comt); ?>">First</a>
              <?php } // Show if not first page ?>
            </strong></td>
            <td class="darkpurple"><strong>
              <?php if ($pageNum_comt > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_comt=%d%s", $currentPage, max(0, $pageNum_comt - 1), $queryString_comt); ?>">Previous</a>
              <?php } // Show if not first page ?>
            </strong></td>
            <td class="darkpurple"><strong>
              <?php if ($pageNum_comt < $totalPages_comt) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_comt=%d%s", $currentPage, min($totalPages_comt, $pageNum_comt + 1), $queryString_comt); ?>">Next</a>
              <?php } // Show if not last page ?>
            </strong></td>
            <td class="darkpurple"><strong>
              <?php if ($pageNum_comt < $totalPages_comt) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_comt=%d%s", $currentPage, $totalPages_comt, $queryString_comt); ?>">Last</a>
              <?php } // Show if not last page ?>
            </strong></td>
            <td class="darkpurple">&nbsp;</td>
            <td align="left" class="darkpurple"><strong>
              <?php if ($pageNum_comt > 0) { echo ($startRow_comt + 1) ?>
            </strong> to <strong><?php echo min($startRow_comt + $maxRows_comt, $totalRows_comt) ?></strong> of <strong><?php echo $totalRows_comt;} ?></strong></td>
          </tr>
      </table></td>
  </tr>
  <tr>
    <td colspan="2"><form id="Frm" name="Frm" method="post" action="<?php echo $FormAction ?>">
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td><textarea name="comment" cols="32" rows="3" id="comment" style="width:620px"></textarea></td>
        </tr>
        <tr>
          <td class="red-normal"><input type="<?php echo ((isset($_COOKIE['userid']))? 'submit':'button'); ?>" value="<?php echo ((isset($_COOKIE['userid']))? 'Post Comment':'Sign in to post a comment'); ?>" name="btcomment" id="btcomment" />
                </td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="Frm" />
      <input type="hidden" name="com_id" id="com_id" value="0" />
      <input name="com_itemid" type="hidden" id="com_itemid" value="<?php echo $row_media['media_id']; ?>" />
    </form></td>
  </tr>
  <tr>
    <td colspan="2" align="right">&nbsp;</td>
  </tr>
</table>
</body>
</html>
