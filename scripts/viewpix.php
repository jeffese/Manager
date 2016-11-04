<table border="0" cellspacing="4" cellpadding="4">
  <tr valign="baseline">
    <td width="50" align="right" valign="top" bgcolor="#333333" class="black-normal"><strong class="red-normal">
      <input name="picture" type="hidden" id="picture" value="<?php echo $pictfld ?>" />
      <input name="fpath" type="hidden" id="fpath" value="<?php $fpath = $_SESSION['coyid'].DS.$fpath; echo $fpath, $xid, DS ?>" />
      Pictures:</strong>
        <script type="text/javascript">
var image_set = [<?php echo gen_img_set($xid, $label, $pixdir.$fpath, $pictfld); ?>];
var px=2;</script></td>
    <td>
      <?php for ($i=1; $i<=$pixcnt; $i++) { ?>
      <table border="0" cellpadding="0" cellspacing="0" id="px<?php echo $i ?>" style="float:left; padding:4px; display:<?php echo $i>1 ? 'none':'block'; ?>">
      <tr>
        <td class="red-normal"><strong><?php echo $i ?></strong></td>
        <td><img src="/images/noimage.jpg" alt="" border="0" id="pix<?php echo $i ?>" /></td>
      </tr>
      <tr>
        <td width="10">&nbsp;</td>
        <td><?php if ($pictfld!='') { ?>
                <a href="#" class="darkgrey" onclick="return GB_showImageSet(image_set, <?php echo $i ?>)"><b>Large View</b></a>
                  <?php } ?></td>
      </tr>
    </table>
    <?php } ?>
    <script type="text/javascript">
	var pixrnd = "<?php if (isset($_SESSION['pixrnd'])) {echo $_SESSION['pixrnd']; $_SESSION['pixrnd'] = '';} ?>";
	pixnav('<?php echo $pixdir ?>', '<?php echo $pixi ?>'); </script></td>
  </tr>
</table>
