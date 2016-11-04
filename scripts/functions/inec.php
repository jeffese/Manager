<?php require_once ('../init.php');

if (_xget('t') != '' && _xget('v') != '' && _xget('id') != '') {
	$t = GSQLStr(_xget('t'), "int");
	$tabs = getTabNamefromID($t);
	$tab = $tabs['tabname'];
	$key = $tabs['keyfield'];
	$id = GSQLStr(_xget('id'), "int");
	$v = GSQLStr(_xget('v'), "int");
    switch ($v) {
        case -1: $vote = "votedn"; break;
        case 1: $vote = "voteup"; break;
    }
	$sql = "UPDATE {$tab} SET {$vote} = $vote + 1 WHERE {$key} = $id";
	runDBqry($dbh, $sql);
    
	$sql = "SELECT voteup, votedn FROM {$tab} WHERE {$key} = $id";
	$votes = getDBDataRow($dbh, $sql);
	$_SESSION['votes_'.$t] = _xses('votes_'.$t).'#'.$id.'#'.$v.'_';
    
    echo "<script type='text/javascript'>parent.showvote(".$v.", ".$id.", ".$t.", ".$votes['voteup'].", ".$votes['votedn'].");</script>";
} ?>