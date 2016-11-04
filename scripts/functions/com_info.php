<link href="/css/style001.css" rel="stylesheet" type="text/css" />
<table border="0" cellpadding="4" cellspacing="0" class="Yellow-normal" style="border: 1px #000 ridge; background-color:#320A10">
  <tr>
    <td nowrap="nowrap" class="shopitemdet"><iframe style="display:none" id="booth<?php echo $vote_id ?>"></iframe></td>
    <td nowrap="nowrap">Views: <span class="boldwhite1"><?php echo $vote_v; ?></span> | Comments: <span class="boldwhite1"><?php echo $vote_c; ?></span> | Rating:</td>
    <td nowrap="nowrap" class="boldwhite1" id="votes<?php echo $vote_id ?>"><?php echo $vote_up-$vote_dn; ?> - - ></td>
    <td style="padding:3px; border:#FC0 1px dotted"><table border="0" cellpadding="2" cellspacing="2">
      <tr>
        <td id="vtupx<?php echo $vote_id ?>" style="padding:3px"><img alt="Like" width="16" height="16" <?php echo $voteup ?> /></td>
        <td id="vtup<?php echo $vote_id ?>" class="brightgreen" align="center"><b><?php echo $vote_up ?></b></td>
        <td style="padding:3px">&nbsp;</td>
        <td id="vtdpx<?php echo $vote_id ?>" style="padding:3px"><img alt="Hate" width="16" height="16" <?php echo $votedn ?> /></td>
        <td id="vtdn<?php echo $vote_id ?>" class="red-normal" align="center"><b><?php echo $vote_dn ?></b></td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
    <td nowrap="nowrap" class="red-normal">Click thumb to Rate</td>
  </tr>
</table>