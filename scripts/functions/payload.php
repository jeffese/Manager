<?php
require_once ('../init.php');
if (isset($_POST['pin'])) {
    vetpin();
}
?>
<link href="/css/style001.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/scripts/js/basicroutines.js"></script>
<script type="text/javascript">
function getpin() {
	with (document.forms.frmpin) {
	pin.value = (pin1.value + pin2.value + pin3.value).toLowerCase();
	}
}
</script>
  <table width="240px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo catch_error($errors); echo showMsg($xMessages); ?></td>
  </tr>
  <tr>
    <td><form action="" method="post" name="frmpin" id="frmpin">
      <table id="frmwin" width="100%" border="0" cellpadding="2" cellspacing="2" bgcolor="#999999">
        <tr>
          <td align="center" class="Yellow-normal"><strong>Fill in your &quot;PIN Number&quot; below</strong></td>
        </tr>
        <tr>
          <td align="center" class="red"><b>
            <input name="pin1" type="text" id="pin1" style="width:50px; text-align:center" class="tab" onchange="this.value=setintnum(this.value); getpin()" maxlength="4" />
            -
            <input name="pin2" type="text" id="pin2" style="width:50px; text-align:center" class="tab" onchange="this.value=setintnum(this.value); getpin()" maxlength="4" />
            -
            <input name="pin3" type="text" class="tab" id="pin3" style="width:50px; text-align:center" onchange="this.value=setintnum(this.value); getpin()" maxlength="4" />
            <input type="hidden" name="pin" id="pin" />
          </b></td>
        </tr>
        <tr>
          <td height="10" align="center" class="red"></td>
        </tr>
        <tr>
          <td align="center"><?php include ('bot_chk.php'); ?></td>
        </tr>
        <tr>
          <td align="center"><input type="button" name="button" id="button" value="Submit" onclick="if (this.form.pin.value.length<12) {alert('Pin is incomplete!')} else if (this.form.captcha.value.length<<?php echo CAPTCHA_LEN ?>) {alert('Security phrase is incomplete!')} else { document.getElementById('frmwin').style.display='none'; document.getElementById('load').style.display='block'; this.form.submit()}" />
            <input name="xpost" type="hidden" id="xpost" value="getpin" /></td>
        </tr>
        <tr>
          <td align="center"></td>
        </tr>
      </table>
    </form><div style="display:none; background-color:#303030; width:100%; height:170px; padding-top: 10px;" id="load" align="center"><img src="/images/load_comment.gif" width="48" height="48" /></div></td>
  </tr>
</table>