<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><script type="text/javascript">
function helpbros() {
	document.getElementById('secpix').src='/scripts/captacha.php?t='+Math.random();
}
</script></td>
  </tr>
  <tr>
    <td align="center"><img id="secpix" src="/scripts/captacha.php" /></td>
  </tr>
  <tr>
    <td align="center"><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><a class="red-normal" href="javascript: void(0)" onclick="helpbros()"><img src="/images/icons/arrow_refresh.png" width="16" height="16" /></a></td>
          <td>&nbsp;</td>
          <td><b><a class="red-normal" href="javascript: void(0)" onclick="helpbros()">Change Image</a></b></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td align="center" class="blue-normal"><strong>Please enter Security Phrase above</strong></td>
  </tr>
  <tr>
    <td align="center"><input name="captcha" type="text" id="captcha" onchange="parent.trimval(this)" maxlength="<?php echo CAPTCHA_LEN ?>" style="width:80px; text-align:center" /></td>
  </tr>
</table>