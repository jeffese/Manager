<?php
require_once('scripts/init.php');

if ($_SESSION['accesskeys']['Accounts']['View'] == 1) {
    $all = $_SESSION['accesskeys']['Accounts']['Outlets']['Supervisor'] == 1 ? "" :
            "AND {$_SESSION['ids']['VendorID']} REGEXP CONCAT('^(0', REPLACE(`guests`,',','|'), ')$')";
    $sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` outcat ON `outlets`.Dept = outcat.catID 
        INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` vencat ON 
            (`outcat`.category_id LIKE CONCAT(`vencat`.category_id, '-%')
            OR  `outcat`.category_id = `vencat`.category_id)
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`                ON `vencat`.catID = `vendors`.DeptID 
        WHERE `account`=1 AND `VendorID`={$_SESSION['ids']['VendorID']}
        UNION
        SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
        WHERE `account`=1 $all";
    $TOutlets = getDBData($dbh, $sql);

    $all_outs = "0";
    foreach ($TOutlets as $row_TOutlets) {
        $all_outs .= "," . $row_TOutlets['OutletID'];
    }
    array_unshift($TOutlets, array('OutletID' => $all_outs, 'OutletName' => "All"));
    if (isset($_POST['OutletID'])) {
        $_SESSION['OutletID'] = GSQLStr(_xpost('OutletID'), "textv");
    } elseif (strlen(_xses('OutletID')) == 0) {
        $_SESSION['OutletID'] = $all_outs;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Top</title>
        <link href="/css/text.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            <!--
            body {
                margin-left: 0px;
                margin-top: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
                background-image: url(/images/top_bg.jpg);
            }

            #top {
                float: right;
                display: inline-block;
            }

            #logo {
                padding-left: 10px;
                padding-top: 10px;
                height:40px;
                display: inline-block;
            }

            #coy {
                padding-top: 10px;
                font-size: large;
                text-align: center;
                color: #E10C00;
                display: inline-block;
            }

            -->
        </style>
        <script type="text/javascript" src="/lib/jquery/jquery-2.1.4.min.js"></script>
        <script src="/lib/FlipClock-master/compiled/flipclock.min.js"></script>
        <script type="text/javascript">
            var clock;
            $(document).ready(function () {
                $('#logo').attr('src', parent.coy);
<?php
$shakedown = intval(file_get_contents(ROOT . '/tmp/shkdn')) == 1;
if ($shakedown) {
    ?>
                    clock = $('.clock').FlipClock(<?php echo (strtotime('2016-2-10 16:00:00') - time()) ?>, {
                        countdown: true,
                        callbacks: {
                            start: function () {
                                //                            alert('The clock has started!');
                            }
                        }
                    });
<?php } ?>
            });
        </script>
        <link rel="stylesheet" href="/lib/FlipClock-master/src/flipclock/css/flipclock.css"></link>
    </head>

    <body>
        <img id="logo" />
        <?php if ($shakedown) { ?>
            <img src="/images/demo.png" alt="" width="111" height="95" />
            <div class="clock"></div>
        <?php } ?>
        <div id="coy"><?php echo $_SESSION['COY']['CoyName'] ?></div>
        <table border="0" id="top">
            <tr>
                <td align="right" bgcolor="#000000" class="boldwhite1">User:</td>
                <td align="center" bgcolor="#000000" class="yellowtxt"><strong><?php echo $_SESSION['ids']['ContactLastName'], ' ', $_SESSION['ids']['ContactFirstName'] ?></strong></td>
            </tr>
            <?php if ($_SESSION['accesskeys']['Accounts']['View'] == 1) { ?>
                <tr>
                  <td bgcolor="#000000" class="titles">&nbsp;</td>
                  <td bgcolor="#000000">&nbsp;</td>
                </tr>
                <tr>
                    <td bgcolor="#000000" class="boldwhite1">Outlet:</td>
                    <td bgcolor="#000000"><form id="frmoutlet" name="frmoutlet" method="post" action="">
                            <select name="OutletID" onchange="frmoutlet.submit()">
                                <?php foreach ($TOutlets as $row_TOutlets) { ?>
                                    <option value="<?php echo $row_TOutlets['OutletID'] ?>"<?php
                                    if (!(strcmp(_xses('OutletID'), $row_TOutlets['OutletID']))) {
                                        echo "selected=\"selected\"";
                                    }
                                    ?>><?php echo $row_TOutlets['OutletName'] ?></option>
                                        <?php } ?>
                            </select>
                        </form></td>
                </tr>
            <?php } ?>
        </table>
    </body>
</html>
