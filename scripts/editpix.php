<table border="0" cellspacing="4" cellpadding="4">
  <tr valign="baseline">
    <td width="70" align="right" valign="top" bgcolor="#333333" class="Yellow-normal">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max ?>" />
      <input name="picture" type="hidden" id="picture" value="<?php echo $pictfld ?>" />
      <input name="fpath" type="hidden" id="fpath" value="<?php echo $_SESSION['coyid'], DS, $fpath, DS ?>" />
      <strong class="red-normal">Pictures:</strong><br />
      Add up to<br />
      <?php echo $pixcnt ?> Pictures<br />
      with a Total<br />
      Max. size<br />
      of <?php echo $max/1000000 ?>MB
      <script type="text/javascript">var px=2;</script>
      </td>
    <td><?php for ($i=1; $i<=$pixcnt; $i++) { ?>
      <table border="0" cellpadding="0" cellspacing="0" id="px<?php echo $i ?>" style="padding:4px; margin:4px; display:<?php echo $i>1 ? 'none':'block'; ?>">
        <tr>
          <td align="center"><img src="/images/b_drop.png" width="16" height="16" border="0" onclick="$('#pix<?php echo $i ?>').attr('src', '/images/noimage2.jpg'); $('#p<?php echo $i ?>').attr('value', '0');" /></td>
          <td><img src="/images/noimage.jpg" alt="" border="0" id="pix<?php echo $i ?>" /></td>
        </tr>
        <tr>
          <td width="30"><strong><?php echo $i ?>.</strong></td>
          <td><input type="file" name="picture<?php echo $i ?>" id="picture<?php echo $i ?>" onchange="vetext(this, 1)" value="" size="20" />
            <input type="hidden" name="p<?php echo $i ?>" id="p<?php echo $i ?>" /></td>
        </tr>
      </table>
      <?php } ?>
      <div id="mopix"><a style="float:left" href="Javascript: void(0)" onclick="document.getElementById('px'+px).style.display='block'; px++; if (px><?php echo $pixcnt ?>) {document.getElementById('mopix').style.display='none';}" class="red-normal">Add More Pictures</a></div>
      <script type="text/javascript">
	var pixrnd = "<?php if (isset($_SESSION['pixrnd'])) {echo $_SESSION['pixrnd']; $_SESSION['pixrnd'] = '';} ?>";
	pixnav('<?php echo $pixdir ?>', '<?php echo $pixi ?>'); </script></td>
  </tr>
</table>
