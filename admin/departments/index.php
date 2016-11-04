<?php
require_once("../../scripts/init.php");

if (getLDAP($_SESSION['coyid'])) {
    $xtra_buts_0 = <<<EOF
<td align="center"><a href="sync.php"><img src="/images/nav_but/sync.png" title="Synchronize Departments" alt="Synchronize" width="33" height="34" border="0" /></a></td>
EOF;
    $xtra_buts_1 = <<<EOF
<td align="center"><a href="sync.php" class="titles">Sync</a></td>
EOF;
}
require_once "tmpl.php";
