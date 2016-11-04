<?php

$startRow = ${'startRow_'.$TabArray};
$maxRows = ${'maxRows_'.$TabArray};
$totalRows = ${'totalRows_'.$TabArray};
$totalPages = ${'totalPages_'.$TabArray};
$pageNum = ${'pageNum_'.$TabArray};
$queryString = ${'queryString_'.$TabArray};

?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="darkgrey"><b> Items: </b><b class="red-normal"><?php echo ($startRow +
1) ?></b><b> to </b><b class="red-normal"><?php echo
min($startRow + $maxRows, $totalRows); ?></b><b> of </b><b class="red-normal"><?php echo
$totalRows ?></b></td>
  </tr>
  <tr>
    <td align="center" class="darkgrey"><b>Go To Page:
      <select name="cmbpage" class="darkgrey" id="cmbpage" onchange="if (this.value!='') location=<?php printf("'%s?pageNum_%s='+ this.value +'%s'", $currentPage, $TabArray, addcslashes($queryString, "\r\n\'\"")); ?>">
      </select>
      <script type="text/javascript">
	navpagesjump(document.getElementById('cmbpage'), <?php echo $totalPages; ?>, <?php echo $pageNum; ?>);
					            </script>
    </b></td>
  </tr>
  <tr>
    <td align="center"><table border="0" cellpadding="4" cellspacing="4">
      <tr>
        <td align="center"><?php if ($pageNum > 0) { // Show if not first page ?>
          <a class="darkgrey" href="<?php printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, 0, $queryString); ?>"><img src="/images/first.png" alt="" width="24" height="24" border="0" /><br />
            First</a>
          <?php } // Show if not first page ?></td>
        <td align="center"><?php if ($pageNum > 0) { // Show if not first page ?>
          <a class="darkgrey" href="<?php printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, max(0, $pageNum - 1), $queryString); ?>"><img src="/images/prev.png" alt="" width="24" height="24" border="0" /><br />
            Previous</a>
          <?php } // Show if not first page ?></td>
        <td align="center"><?php if ($pageNum < $totalPages) { // Show if not last page ?>
          <a class="darkgrey" href="<?php printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, max(0, $pageNum + 1), $queryString); ?>"><img src="/images/next.png" alt="" width="24" height="24" border="0" /><br />
            Next</a>
          <?php } // Show if not last page ?></td>
        <td align="center"><?php if ($pageNum < $totalPages) { // Show if not last page ?>
          <a class="darkgrey" href="<?php printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, $totalPages, $queryString); ?>"><img src="/images/last.png" alt="" width="24" height="24" border="0" /><br />
            Last</a>
          <?php } // Show if not last page ?></td>
      </tr>
    </table></td>
  </tr>
</table>
