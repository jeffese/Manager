<?php
require_once('scripts/init.php');

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['COY']['kiosk'] == 1 && !isAdminPowerGrp() &&
        $_SESSION['accesskeys']['Accounts']['Outlets']['Kiosk-Mode'] == 1) {
    header("Location: custom/kiosk.php");
    exit;
}

$shakedown = intval(file_get_contents(ROOT . '/tmp/shkdn')) == 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <script type="text/javascript">
            var CRS_NAME = '<?php echo COURSE ?>';
            var TERM_NAME = '<?php echo TERM ?>';
            var ARM_NAME = '<?php echo ARM ?>';
            var coy = '<?php echo COYPIX_DIR, $_SESSION['coyid'] . "/logo/xxxpix.jpg" ?>';
            var paylist = [], paycur=0, paytot=0, payvnd=0;
            
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Manager 1.0</title>
    </head>

    <frameset rows="<?php echo $shakedown ? '100' : '60' ?>,*" cols="*" framespacing="2" frameborder="yes" border="2" bordercolor="#999999">
        <frame src="/top.php" name="topFrame" scrolling="No" noresize="noresize" id="topFrame" title="topFrame" />
        <frameset rows="*" cols="160,*" framespacing="4" frameborder="yes" border="4" bordercolor="#666666" name="bodyFrame" id="bodyFrame">
            <frame src="/menu.php" name="leftFrame" scrolling="No" noresize="noresize" id="leftFrame" title="leftFrame" />
            <frame src="/main.htm" name="mainFrame" id="mainFrame" title="mainFrame" />
        </frameset>
    </frameset>
    <noframes><body>
        </body>
    </noframes></html>
