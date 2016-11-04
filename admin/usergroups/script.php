<?php
require_once('../../scripts/init.php');

$usr_permits = permission_array("0");
$mods = "";
foreach ($usr_permits as $mod => $usr_permit) {
    array_shift($usr_permit);
    $subs = "";
    foreach ($usr_permit as $sub => $permits) {
        $cans = "";
        foreach ($permits as $can => $val) {
            if ($val != -1)
                $cans .= ", '$can'";
        }
        if (strlen($cans) == 0) {
            $cans = ", 'View'";
        }
        $space = str_repeat(" ", 24);
        $subs .= ",\n$space'$sub': [" . substr($cans, 2) . "]";
    }
    $space = str_repeat(" ", 16);
    $mods .= ",\n$space'$mod': {" . substr($subs, 1) . "\n$space}";
}
?>

function collate() {
    var mods = <?php echo "{", substr($mods, 1), "\n", str_repeat(" ", 8), "}" ?>;
    var permits = "", permit, can, name;
    for (var mod in mods) {
        permits += "#" + ($("input[name="+mod+"]").is(":checked") ? "1" : "0");
        permit = "";
        for (var sub in mods[mod]) {
            can = "";
            for (var i in mods[mod][sub]) {
                name = (mod+'_'+sub+'_'+mods[mod][sub][i]).replace(/[^\w]/g, '_');
                can += "&" + ($("input[name="+name+"]").is(":checked") ? "1" : "0");
            }
            permit += "_" + can.substr(1);
        }
        permits += "_" + permit.substr(1);
    }
    $("#permissions").val(permits.substr(1));
}
