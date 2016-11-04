<?php
header("Cache-Control: no-cache");
header("Pragma: nocache");
require('../init.php'); // get the db connection info

//getting the values
$vote_sent = preg_replace("/[^0-9]/","",$_REQUEST['j']);
$id_sent = preg_replace("/[^0-9a-zA-Z]/","",$_REQUEST['q']);
$userid = $_REQUEST['uid'];
$ip_num = preg_replace("/[^0-9\.]/","",$_REQUEST['t']);
$item_id = preg_replace("/[^0-9]/","",@$_REQUEST['itemId']);
$modid = preg_replace("/[^0-9]/","",$_REQUEST['modid']);
$units = preg_replace("/[^0-9]/","",$_REQUEST['c']);
$ip = $userid;
$referer  = @$_SERVER['HTTP_REFERER'];


if ($vote_sent > $units) die("Sorry, vote appears to be invalid."); // kill the script because normal users will never see this.

//connecting to the database to get some information
$query = $dbh->query("SELECT * FROM $rating_tableName WHERE id='$id_sent' ");
$numbers = $query->fetchRow();
$checkIP = unserialize($numbers['used_ips']);
$count = $numbers['total_votes']; //how many votes total
$current_rating = $numbers['total_value']; //total number of rating added together and stored
$sum = $vote_sent+$current_rating; // add together the current vote value and the total vote value
$tense = ($count==1) ? "vote" : "votes"; //plural form votes/vote

// checking to see if the first vote has been tallied
// or increment the current number of votes
($sum==0 ? $added=0 : $added=$count+1);

// if it is an array i.e. already has entries the push in another value
((is_array($checkIP)) ? array_push($checkIP,$ip) : $checkIP=array($ip));
$insertip=serialize($checkIP);

// Check if the user has vote on this item
//$voted=mysql_num_rows(mysql_query("SELECT id FROM $rating_dbname.$rating_tableName WHERE user_id ='$userid' AND id='$id_sent' "));

//IP check when voting
$voted= $dbh->query("SELECT used_ips FROM $rating_tableName WHERE used_ips LIKE '%".$ip."%' AND id='".$id_sent."' ")->numRows(); 

if(!$voted) {     //if the user hasn't yet voted, then vote normally...

// && ($ip == $ip_num)
if (($vote_sent >= 1 && $vote_sent <= $units)) { // keep votes within range
	$update = "UPDATE $rating_tableName SET user_id ='".$userid."',  total_votes='".$added."', total_value='".$sum."', used_ips='".$insertip."' WHERE id='$id_sent'";
	$result = $dbh->query($update);		
} 
header("Location: $referer"); // go back to the page we came from 
exit;
} //end for the "if(!$voted)"

?>