<table border="0" cellspacing="4" cellpadding="4">
  <tr valign="baseline">
    <td width="70" align="right" valign="top" bgcolor="#333333" class="Yellow-normal">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max ?>" />
      <strong class="red-normal">Pictures:</strong><br />
      Add up to<br />
      <?php echo $pixcnt ?> Pictures<br />
      with a Total<br />
      Max. size<br />
      of <?php echo $max/1000000 ?>MB
      <script type="text/javascript">var px=2;</script>
      </td>
    <td><?php for ($i=1; $i<=$pixcnt; $i++) { ?>
      <table border="0" cellpadding="0" cellspacing="0" id="px<?php echo $i ?>" style="display:<?php echo $i>1 ? 'none':'block'; ?>">
        <tr>
          <td width="30" class="red-normal"><strong><?php echo $i ?>.</strong></td>
          <td><input type="file" name="picture<?php echo $i ?>" id="picture<?php echo $i ?>" onchange="vetext(this, 1)" value="" size="20" /></td>
        </tr>
      </table>
      <?php } ?>
      <div id="mopix"><a href="Javascript: void(0)" onclick="document.getElementById('px'+px).style.display='block'; px++; if (px><?php echo $pixcnt ?>) {document.getElementById('mopix').style.display='none';}" class="red-normal">Add More Pictures</a></div></td>
  </tr>
</table>
