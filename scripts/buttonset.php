<?php
$buts_show = false;
if ($_bottons[4] == 1 && isset($TabArray)) {
    $buts_show = true;
    $startRow = ${'startRow_' . $TabArray};
    $maxRows = ${'maxRows_' . $TabArray};
    $totalRows = ${'totalRows_' . $TabArray};
    $totalPages = ${'totalPages_' . $TabArray};
    $pageNum = ${'pageNum_' . $TabArray};
    $queryString = ${'queryString_' . $TabArray};
}

/**
 * Button permission
 * @param $_bottons array(new, edit, delete, print, Nav, find)
 * @param $rec_status int 1=view, 2=insert, 3=edit
 * Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
 * 
 * $button_links array() | links of javascript commands
 */
//button_permission(array(''))
//nav
$buttons[0] = $_bottons[4];
$buttons[1] = $_bottons[4];
$buttons[2] = $_bottons[4];
$buttons[3] = $_bottons[4];

$buttons[4] = $_bottons[0];
$buttons[5] = $_bottons[1];
$buttons[6] = 0;
$buttons[7] = $_bottons[2];
$buttons[8] = 0;
$buttons[9] = 1;
$buttons[10] = $_bottons[5];
$buttons[11] = $_bottons[3];
$buttons[12] = $rec_status == 4 && $_bottons[3] == 1 ? 1 : 0;
$buttons[13] = $_bottons[3];
$buttons[14] = isset($_bottons[7]) ? $_bottons[7] : 0;
$buttons[15] = isset($_bottons[6]) ? $_bottons[6] : 1;
$buttons[16] = isset($_bottons[8]) ? $_bottons[8] : 0;

// Button states
if ($rec_status == 2 || $rec_status == 3) {
    for ($i = 0; $i < count($buttons); $i++) {
        $buttons[$i] = 0;
    }
    $buttons[6] = 1;
    $buttons[8] = 1;
    $buttons[9] = 1;
} elseif ($rec_status == 1) {
    $buttons[6] = 0;
    $buttons[8] = 0;
} elseif ($rec_status == 0) {
    $buttons[8] = 1;
}
$buttons[10] = $_bottons[5];
?>

<div style="float:left">
    <table border="0" cellspacing="2" cellpadding="4">
        <tr>
            <?php if ($buts_show) { ?>
                <td align="center"><span class="darkgrey"><b class="red-normal"><?php echo ($startRow + 1) ?></b><b> to </b><b class="red-normal"><?php echo min($startRow + $maxRows, $totalRows); ?></b><b> of </b><b class="red-normal"><?php echo $totalRows ?></b></span></td>
            <?php if ($buttons[0] == 1 && $pageNum > 0) { ?>
                    <td align="center"><a href="<?php if (strlen($buttons_links[0]) == 0) printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, 0, $queryString);
                else { ?>javascript: void(0)" onclick="<?php echo $buttons_links[0]; } ?>" ><img src="/images/nav_but/first.png" title="First" alt="First" width="34" height="34" border="0" /></a></td>
            <?php } ?>
            <?php if ($buttons[1] == 1 && $pageNum > 0) { ?>
                    <td align="center"><a href="<?php if (strlen($buttons_links[1]) == 0) printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, max(0, $pageNum - 1), $queryString);
                else { ?>javascript: void(0)" onclick="<?php echo $buttons_links[1];
                } ?>" ><img src="/images/nav_but/prev.png" title="Previous" alt="Previous" width="34" height="34" border="0" /></a></td>
            <?php } ?>
            <?php if ($buttons[2] == 1 && $pageNum < $totalPages) { ?>
                    <td align="center"><a href="<?php if (strlen($buttons_links[2]) == 0) printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, max(0, $pageNum + 1), $queryString);
                    else { ?>javascript: void(0)" onclick="<?php echo $buttons_links[2]; } ?>" ><img src="/images/nav_but/next.png" title="Next" alt="Next" width="34" height="34" border="0" /></a></td>
            <?php } ?>
            <?php if ($buttons[3] == 1 && $pageNum < $totalPages) { ?>
                    <td align="center"><a href="<?php if (strlen($buttons_links[3]) == 0) printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, $totalPages, $queryString);
                else { ?>javascript: void(0)" onclick="<?php echo $buttons_links[3]; } ?>" ><img src="/images/nav_but/last.png"  title="Last" alt="Last" width="34" height="34" border="0" /></a></td>
            <?php } ?>
            <?php } ?>
            <?php if ($buttons_links[12] != '') { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/list.png" title="List" alt="List"  width="33" height="34" border="0" onclick="<?php echo urlNav(12); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[4] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/new.png" title="New" alt="New"  width="33" height="34" border="0" onclick="<?php echo urlNav(4); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[5] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/edit.png"  title="Edit" alt="Edit" width="33" height="34" border="0" onclick="<?php echo urlNav(5); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[6] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/save.png"  title="Save" alt="Save" width="33" height="34" border="0" onclick="<?php echo savCmd(); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[7] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/delete.png"  title="Delete" alt="Delete" width="33" height="34" border="0" onclick="<?php echo delCmd(); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[8] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/back.png"  title="Cancel" alt="Cancel" width="34" height="34" border="0" onclick="<?php echo urlNav(8); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[9] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" onclick="<?php echo $buttons_links[9] == '' ? 'location.href=location.href' : $buttons_links[9]; ?>"><img src="/images/nav_but/refresh.png"  title="Refresh" alt="Refresh" width="33" height="34" border="0" /></a></td>
            <?php } ?>
            <?php if ($buttons[10] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/search.png"  title="Find" alt="Find" width="33" height="34" border="0" onclick="<?php echo urlNav(10); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[11] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/print.png"  title="Print" alt="Print" width="33" height="34" border="0" onclick="<?php echo urlNav(11); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[12] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/but_excel.png"  title="Export" alt="Export" width="33" height="34" border="0" onclick="<?php echo url_print(1); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[13] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/but_pdf.png"  title="PDF" alt="PDF" width="33" height="34" border="0" onclick="<?php echo url_print(2); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[14] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/but_email.png"  title="Email" alt="Email" width="33" height="34" border="0" onclick="<?php echo url_print(3); ?>" /></a></td>
            <?php } ?>
            <?php if ($buttons[15] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/close.png"  title="Close" alt="Close" width="33" height="34" border="0" onclick="top.leftFrame.killMod(parent.parent)" /></a></td>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/fullscreen.png"  title="Full Screen" tag="full_screen" alt="Full Screen" width="33" height="34" border="0" onclick="fulscreen(1)" /></a>
                    <a href="javascript: void(0)" ><img src="/images/nav_but/normal.png"  title="Normal Screen" tag="norm_screen" alt="Normal Screen" width="33" height="34" border="0" onclick="fulscreen(0)" style="display: none"/></a></td>
            <?php } ?>
            <?php if ($buttons[16] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" ><img src="/images/nav_but/close.png"  title="Back" alt="Back" width="34" height="34" border="0" onclick="<?php echo urlNav(8); ?>" /></a></td>
            <?php } ?>
            <?php echo _xvar('xtra_buts_0'); ?>
        </tr>
        <tr>
            <?php if ($buts_show) { ?>
                <td align="center"><span class="darkgrey"><b> Go to:</b></span>
                    <select name="cmbpage" class="darkgrey" id="cmbpage" onchange="if (this.value != '') location =<?php printf("'%s?pageNum_%s='+ this.value +'%s'", $currentPage, $TabArray, addcslashes($queryString, "\r\n\'\"")); ?>">
                    </select>
                    <script type="text/javascript">navpagesjump(<?php echo $totalPages; ?>, <?php echo $pageNum; ?>);</script></td>
            <?php if ($buttons[0] == 1 && $pageNum > 0) { ?>
                    <td align="center"><a href="<?php if (strlen($buttons_links[0]) == 0) printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, 0, $queryString);
                else { ?>javascript: void(0)" onclick="<?php echo $buttons_links[0]; } ?>" class="titles" >First</a></td>
            <?php } ?>
            <?php if ($buttons[1] == 1 && $pageNum > 0) { ?>
                    <td align="center"><a href="<?php if (strlen($buttons_links[1]) == 0) printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, max(0, $pageNum - 1), $queryString);
                else { ?>javascript: void(0)" onclick="<?php echo $buttons_links[1]; } ?>" class="titles" >Previous</a></td>
            <?php } ?>
            <?php if ($buttons[2] == 1 && $pageNum < $totalPages) { ?>
                    <td align="center"><a href="<?php if (strlen($buttons_links[2]) == 0) printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, max(0, $pageNum + 1), $queryString);
                else { ?>javascript: void(0)" onclick="<?php echo $buttons_links[2]; } ?>" class="titles" >Next</a></td>
            <?php } ?>
            <?php if ($buttons[3] == 1 && $pageNum < $totalPages) { ?>
                <td align="center"><a href="<?php if (strlen($buttons_links[3]) == 0) printf("%s?pageNum_%s=%d%s", $currentPage, $TabArray, $totalPages, $queryString);
                else { ?>javascript: void(0)" onclick="<?php echo $buttons_links[3]; } ?>" class="titles" >Last</a></td>
            <?php } ?>
            <?php } ?>
            <?php if ($buttons_links[12] != '') { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo urlNav(12); ?>" >List</a></td>
            <?php } ?>
            <?php if ($buttons[4] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo urlNav(4); ?>" >New</a></td>
            <?php } ?>
            <?php if ($buttons[5] == 1) { ?>
                <td align="center" but="edit"><a href="javascript: void(0)" class="titles" onclick="<?php echo urlNav(5); ?>" >Edit</a></td>
            <?php } ?>
            <?php if ($buttons[6] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo savCmd(); ?>" >Save</a></td>
            <?php } ?>
            <?php if ($buttons[7] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo delCmd(); ?>" >Delete</a></td>
            <?php } ?>
            <?php if ($buttons[8] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo urlNav(8); ?>" >Cancel</a></td>
            <?php } ?>
            <?php if ($buttons[9] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo $buttons_links[9] == '' ? 'window.location.reload()' : $buttons_links[9]; ?>" >Refresh</a></td>
            <?php } ?>
            <?php if ($buttons[10] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo urlNav(10); ?>" >Find</a></td>
            <?php } ?>
            <?php if ($buttons[11] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo urlNav(11); ?>" >Print</a></td>
            <?php } ?>
            <?php if ($buttons[12] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo url_print(1); ?>" >Export</a></td>
            <?php } ?>
            <?php if ($buttons[13] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo url_print(2); ?>" >PDF</a></td>
            <?php } ?>
            <?php if ($buttons[14] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo url_print(3); ?>" >Email</a></td>
            <?php } ?>
            <?php if ($buttons[15] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="top.leftFrame.killMod(parent.parent)" >Close</a></td>
                <td align="center"><a href="javascript: void(0)" class="titles" tag="full_screen" onclick="fulscreen(1)">Full</a>
                    <a href="javascript: void(0)" class="titles" tag="norm_screen" onclick="fulscreen(0)" style="display: none">Normal</a></td>
            <?php } ?>
            <?php if ($buttons[16] == 1) { ?>
                <td align="center"><a href="javascript: void(0)" class="titles" onclick="<?php echo urlNav(8); ?>" >Back</a></td>
            <?php } ?>
            <?php echo _xvar('xtra_buts_1'); ?>
        </tr>
    </table>
    <script src="/scripts/js/functions.js" type="text/javascript"></script>
</div>
