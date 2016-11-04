<?php
if (substr_count($_SERVER['HTTP_REFERER'], '/') == 3) {
    $referer = "javascript: void(0)";
    $onclick = ' onclick="top.mainFrame.KillMod()"';
} else {
    $referer = $_SERVER['HTTP_REFERER'];
    $onclick = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
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
<div id="content" align="center">
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
          <td>
              <table width="100%" border="0" cellpadding="2" cellspacing="2" class="bluetxt" style="border: solid 1px #666666">
                <tr>
                  <td width="200%" colspan="2" align="center" class="errorbox"><b><?php echo isset($_GET['msg']) ? $_GET['msg'] : "ACCESS DENIED"; ?></b></td>
                </tr>
                <tr>
                  <td colspan="2" align="center"><a href="<?php echo $referer ?>"<?php echo $onclick ?>><img src="/images/back.png" width="80" height="30" /></a></td>
                </tr>
                </table>              </form></td>
        </tr>
      </table></td>
      <td class="frameright">&nbsp;</td>
    </tr>
    <tr>
      <td class="framebotleft">&nbsp;</td>
      <td valign="bottom" class="framebot">&nbsp;</td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>
</html>
