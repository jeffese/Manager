<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Progress</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/scripts/js/set.js"></script>
<script type="text/javascript"> 
<!--
window.onload = function() {
	setContent();
}
window.onresize = function() {
	setContent();
}

//--> 
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script><script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<div id="content">
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="frametopleft">&nbsp;</td>
      <td class="frametop">&nbsp;</td>
      <td class="frametopright">&nbsp;</td>
    </tr>
    <tr>
      <td class="frameleft">&nbsp;</td>
      <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="headerlogo"></td>
        </tr>
        <tr>
          <td height="10"></td>
        </tr>
        <tr>
          <td>            <table width="100%" border="0" cellpadding="2" cellspacing="2" class="bluetxt" style="border: solid 1px #666666">
              
              <tr>
                <td width="100%" align="center">&nbsp;</td>
                </tr>
              <tr>
                <td align="center" class="greytxt"><img src="/images/16.gif" id="img" /></td>
                </tr>
              <tr>
                <td align="center" class="red-normal" id="msg"><?php echo $_SESSION['msg']; ?><script type="text/javascript">
function takeaction() {
	document.getElementById('winaction').src = '<?php echo $_SESSION['scriptfile']; ?>';
}
setTimeout('takeaction()',5000)
</script>
<iframe id="winaction" style="border:none; width:1px; height:1px"></iframe></td>
              </tr>
              <tr>
                <td align="center" class="red-normal" id="status"><b>Processing ...<br />
                  Do not navigate from this page</b></td>
                </tr>
            </table></td></tr>
      </table></td>
      <td class="frameright">&nbsp;</td>
    </tr>
    <tr>
      <td class="framebotleft">&nbsp;</td>
      <td valign="bottom" class="framebot"><span class="greytxt">Copyright © 2010 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>
</html>