<?php require_once('bset.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$thankumessage = '';
if ((isset($_POST['txtcomment'])==true) && (trim($_POST['txtcomment'])<>'') && (trim($_POST['txtcomment'])<>'Place your comments here.')) {

	$sender = ''; 
	if (isset($_SESSION['userid'])) {
		$sender = $_SESSION['userid'];  
	}
	
	$IPDetail=countryCityFromIP($_SERVER['REMOTE_ADDR']);
	
	$comment  = stripslashes($_POST['txtcomment']);
	$email = stripslashes($_POST['txtemail']);
	if ($email == '{optional} Feedback Email Address here') {
		$email = '';
	}
	$insertSQL = sprintf("INSERT INTO feedback (`fb_time`, `ip_src`, `email_add`, `feedback`, `referfrom`, `city`, `country`) VALUES (%s, %s,%s, %s, %s, %s, %s)",
			GSQLStr(date('Y-m-d H:i:s'), "date"),
			GSQLStr($_SERVER['REMOTE_ADDR'], "text"),
			GSQLStr($email, "text"),
			GSQLStr($comment, "text"),
			GSQLStr($currentPage, "text"),
			GSQLStr($IPDetail['city'], "text"),
			GSQLStr($IPDetail['country'], "text")
			);
	$Result1 = mysql_query($insertSQL, $exood) or die(mysql_error());
	$thankumessage = 'Your Comment has been sent to our Editors. Based on the comment, we will respond via the Email address you provided.';
}
?>
<html>
<head>
<title>Exood- Send Comment!</title><link href="/css/style001.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-color: #000000;
}
-->
</style></head>
<body>
<div align="center">
<table width="500" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#000000">
  <tr>
<td><table border="0" cellspacing="0" cellpadding="0">
<tr><td width="110" valign="top" bgcolor="#333333"><table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td width="110" height="44" align="left" valign="top"><img src="/images/exood-blk.jpg" width="110" height="100"></td>
</tr></table>
    </td>
<td width="390" valign="top" bgcolor="#000000"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
    <td height="180">
<script language="javascript" type="text/javascript">
var comment = '<?php echo $thankumessage ?>';
if (comment !== ''){
alert(comment);
}
</script><form name="Frm" method="post" action="<?php echo $currentPage; ?>">
  <table width="100%" cellspacing="1" cellpadding="2">
    <tr>
      <td align="center">&nbsp;</td>
              <td colspan="2" align="center" class="boldwhite1">Thank you for your Feedback</td>
            </tr>
    <tr>
      <td></td>
              <td colspan="2"></td>
            </tr>
    <tr>
      <td valign="top" class="boldwhite1">Comment</td>
              <td colspan="2" align="left"><textarea name="txtcomment" rows="3" onFocus="if (this.value=='Place your comments here.') {this.value=''}" style="width:300px">Place your comments here.</textarea>                </td>
            </tr>
    <tr>
      <td class="boldwhite1" height="5"></td>
              <td colspan="2" class="boldwhite1"></td>
            </tr>
    <tr>
      <td><span class="boldwhite1">Email address</span></td>
              <td colspan="2" align="left"><input name="txtemail" type="text" id="txtemail" value="{optional} Feedback Email Address here" size="40" onFocus="if (this.value=='{optional} Feedback Email Address here') {this.value=''}">
                <input name="referfrom" type="hidden" id="referfrom"><script language="javascript" type="text/javascript">document.Frm.referfrom.value=opener.location</script></td>              
      </tr>
    <tr>
      <td align="center">&nbsp;</td>
              <td align="center"><input type="submit" name="Submit" value="Send"></td>
              <td align="center"><input type="button" name="Submit2" value="Cancel" onClick="window.close();"></td>
            </tr>
    </table>
      </form></td>
  </tr>
</table></td>
</tr></table></td></tr><tr>
<td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td width="776" bgcolor="#105833"></td></tr><tr>
<td height="20" background="/images/bg_rpt.gif"><div align="center"><span class="lightgrey">&copy;2004-2010 <a href="http://www.electricavenuetech.co" target="_blank" class="boldwhite1">Electric Avenue Technologies</a>. </span></div></td></tr></table></td></tr></table>
</div></body></html>