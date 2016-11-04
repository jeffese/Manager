<?php
require_once('../scripts/init.php');
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Sales'));
vetAccess('Accounts', 'Sales', 'View');

    
if (!isset($_SESSION['OutletID']) && $_SESSION['accesskeys']['Accounts']['View'] == 1) {
    $sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` outcat ON `outlets`.Dept = outcat.catID 
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` vencat ON 
            (`outcat`.category_id LIKE CONCAT(`vencat`.category_id, '-%')
            OR  `outcat`.category_id = `vencat`.category_id)
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`                ON `vencat`.catID = `vendors`.DeptID 
        WHERE `account`=1 AND `VendorID`={$_SESSION['ids']['VendorID']}
        UNION
        SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
        WHERE `account`=1 AND {$_SESSION['ids']['VendorID']} REGEXP CONCAT('^(0', REPLACE(`guests`,',','|'), ')$')
        ";
    $TOutlets = getDBData($dbh, $sql);
    if (count($TOutlets) == 0) {
	header("Location: /denied.php");
        exit;
    } else {
        $_SESSION['OutletID'] = $TOutlets[0]['OutletID'];
        $_SESSION['new_veh'] = array();
    }
}
unset($_SESSION['custid']);
$shakedown = intval(file_get_contents(ROOT . '/tmp/shkdn')) == 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>eVRS Screen</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="/scripts/js/set.js"></script>
<script language="JavaScript1.2" src="vehicles/auto_cats.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="vehicles/autos.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="vehicles/models.jgz" type="text/javascript"></script>
<style>
.screen {
    border-radius: 10px;
    border:3px inset #333;
    background-color: #333;
}
.pixlnk {
    cursor: pointer;
}

</style>
<script src="/lib/FlipClock-master/compiled/flipclock.min.js"></script>
<link rel="stylesheet" href="/lib/FlipClock-master/src/flipclock/css/flipclock.css"></link>
<script type="text/javascript">

    window.onload = function() {
        setContent();
    }
    window.onresize = function() {
        setContent();
    }
<?php if ($shakedown) { ?>
    var clock;
    $(document).ready(function () {
        clock = $('.clock').FlipClock(<?php echo (strtotime('2016-2-10 16:00:00') - time()) ?>, {
            countdown: true,
            callbacks: {
                start: function () {
//                            alert('The clock has started!');
                }
            }
        });
    });
<?php } ?>
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<div id="content">
  <table border="0" align="center" cellpadding="2" cellspacing="2">
    <?php if ($shakedown) { ?>
    <tr>
      <td colspan="5" align="center"><table border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td height="64"><img src="/images/demo.png" alt="" width="111" height="95" /></td>
          <td class="clock">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <?php } ?>
    <tr>
      <td><img src="/custom/images/old_reg.png" width="120" height="120" onclick="location.href='/custom/owners/add.php?flow=1'" class="pixlnk" /></td>
      <td><img src="/custom/images/new_reg.png" width="120" height="120" onclick="location.href='/custom/owners/add.php?flow=2'" class="pixlnk" /></td>
      <td><img src="/custom/images/renew_lic.png" width="120" height="120"  onclick="location.href='/custom/vehicles/find.php?flow=3'" class="pixlnk" /></td>
      <td><a href="/custom/sales/find.php"><img src="/custom/images/find_invoice.png" width="120" height="120"  onclick="location.href='/custom/reprint.php'" class="pixlnk" /></a></td>
      <td><img src="/custom/images/logout.png" width="120" height="120" onclick="location.href='/logout.php'" class="pixlnk" /></td>
    </tr>
  </table>
</div>
</body>
</html>
