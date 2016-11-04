<?php
require_once('../../../scripts/init.php');

if (!vetAccess('Personnel', 'Pay Slips', 'Dispatch', false)) {
    ?>
    <script>alert('Access Denied!')</script>
    <?php
    exit;
}

$id = intval(_xget('id'));
$sql = "SELECT `payslip_id` FROM `{$_SESSION['DBCoy']}`.`payslip` WHERE `paybatchid`=$id";
$TSlips = getDBData($dbh, $sql);
$slips = '';

foreach ($TSlips as $slip)
    $slips .= ",{$slip['payslip_id']}";
$slips = substr($slips, 1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
    var slips = [<?php echo $slips ?>];
    var idx = 0;
    function reset() {
        if (idx < slips.length)
            sendmail();
        else
            $('#spin').hide();
        idx++;
    }
    
    function sendmail() {
        $('#loadwin').load('email.php?bth=$id&id='+slips[idx], function(){
            $('#msgwin').append('<br />'+$('#loadwin').html());
            setTimeout("reset()", 5);
        })
    }
    $(document).ready(function() {
        reset();
    });
    
</script>
</head>

<body>
<table border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><img src="/images/load32.gif" width="32" height="32" id="spin" /></td>
  </tr>
  <tr>
    <td id="msgwin">&nbsp;</td>
  </tr>
  <tr>
    <td id="loadwin" style="display:none">&nbsp;</td>
  </tr>
</table>
</body>
</html>
