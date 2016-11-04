<?php
/*
  Filename: limit_update.php
  Created by: J�Gius�, alivin70 - it - http://www.vinsoft.it
  Modified by: Charter - http://www.phpdig.net/
 */

$relative_script_path = '..';
$no_connect = 0;
require_once('../../scripts/init.php');
include "$relative_script_path/includes/config.php";
include "$relative_script_path/libs/auth.php";
include "$relative_script_path/admin/robot_functions.php";

// extract http vars
extract(phpdigHttpVars(array('type' => 'string')), EXTR_SKIP);

set_time_limit(300);
?>
<?php include $relative_script_path . '/libs/htmlheader.php' ?>
<head>
    <title>PhpDig : <?php phpdigPrnMsg('limit') ?> </title>
    <?php include $relative_script_path . '/libs/htmlmetas.php' ?>
</head>
<body bgcolor="white">
    <table border="0">
        <tr>
            <td valign="top">
                <h1><?php phpdigPrnMsg('limit') ?></h1>
                <p class='grey'>
                    <?= phpdigPrnMsg('upd_sites') ?>
                </p>
                <a href="index.php" target="arch_win">
                    [<?php phpdigPrnMsg('back') ?>]</a> 
                <?php phpdigPrnMsg('to_admin') ?>.
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td valign="top"><?php echo phpdigPrnMsg('manage'); ?><br> 
                <?php
                if (CRON_ENABLE) {
                    ?><?php echo phpdigPrnMsg('dayscron'); ?><br><?php }
                ?>
                <?php echo phpdigPrnMsg('links_mean'); ?><br>
                <?php echo phpdigPrnMsg('depth_mean'); ?><br><br>
                <?php echo phpdigPrnMsg('max_found'); ?><br><br>

                <?php
                if ((isset($_REQUEST['upd'])) && ($_REQUEST['upd'] == 1)) {
                    ?><p class='grey'><?= phpdigPrnMsg('upd2') ?></p><br><?
                }

                if ((isset($_REQUEST['links'])) && (!$_REQUEST['links'])) { /* SHOW the form to enter the days */
                    form_cron_limits($id_connect);
                } else {
                    if (CRON_ENABLE) {
                        /*
                         * template record for crontab
                         */
                        $tpl_cron = '0 0 1-31/DAY * * ' . PHPEXEC . ' -f ' . ABSOLUTE_SCRIPT_PATH . '/admin/spider.php URL' . "\n";
                        /*
                         * read itself
                         */
                        $self_cron = '0 0 1-31/1 * * ' . CRON_EXEC_FILE . ' ' . CRON_CONFIG_FILE . "\n";
                        /*
                         * the file that holds all this stuff
                         */
                        $site_fp = fopen(CRON_CONFIG_FILE, "w"); // file opening
                        fputs($site_fp, $self_cron, 4096); // write the first row 
                        /*
                         * Delete records all at once.
                         */

                        if (isset($_REQUEST['days'])) {
                            $days = $_REQUEST['days'];
                        } else {
                            $days = null;
                        }
                        /*
                         * write the cron file and update the table.
                         */
                        if ((isset($_REQUEST['days'])) && ($days != null)) {
                            foreach ($_REQUEST['days'] as $id => $days) {
                                settype($id, 'integer');
                                settype($days, 'integer');
                                if (((int) $days) >= 0) {
                                    $site_id_sql = "select site_url from " . PHPDIG_DB_PREFIX . "sites where site_id ='$id'";
                                    $res_id = mysql_query($site_id_sql, $id_connect);
                                    list($url) = mysql_fetch_row($res_id);
                                    // Insert only if a value has been passed
                                    $site_cron = preg_match("DAY", "$days", $tpl_cron);
                                    $site_cron = preg_match("URL", "$url", $site_cron);
                                    if ($days != 0) {
                                        fputs($site_fp, $site_cron, 4096);
                                    }
                                    $query_days = "SELECT days FROM " . PHPDIG_DB_PREFIX . "site_page WHERE site_id = '$id'";
                                    $result_days = mysql_query($query_days, $id_connect);
                                    if (mysql_num_rows($result_days) > 0) {
                                        $sql_ins = "UPDATE " . PHPDIG_DB_PREFIX . "site_page SET days='$days' "
                                                . " WHERE site_id='$id'";
                                    } else {
                                        $sql_ins = "INSERT INTO " . PHPDIG_DB_PREFIX . "site_page (site_id,days) VALUES  "
                                                . "('$id', '$days') ";
                                    }
                                    $insert_d = mysql_query($sql_ins, $id_connect);
                                }
                            }
                        }
                        fclose($site_fp); // closing time
                    }

                    if (isset($_REQUEST['links'])) {
                        foreach ($_REQUEST['links'] as $id => $links) {
                            settype($id, 'integer');
                            settype($links, 'integer');
                            $query_links = "SELECT links FROM " . PHPDIG_DB_PREFIX . "site_page WHERE site_id = '$id'";
                            $result_links = mysql_query($query_links, $id_connect);
                            if (mysql_num_rows($result_links) > 0) {
                                $sql = "UPDATE " . PHPDIG_DB_PREFIX . "site_page SET links='$links' "
                                        . " WHERE site_id='$id'";
                            } else {
                                $sql = "INSERT INTO " . PHPDIG_DB_PREFIX . "site_page (site_id,links) VALUES  "
                                        . "('$id', '$links') ";
                            }
                            $res = mysql_query($sql, $id_connect);
                        }
                    }

                    if (isset($_REQUEST['depth'])) {
                        foreach ($_REQUEST['depth'] as $id => $depth) {
                            settype($id, 'integer');
                            settype($depth, 'integer');
                            $query_depth_page = "SELECT depth FROM " . PHPDIG_DB_PREFIX . "site_page WHERE site_id = '$id'";
                            $result_depth_page = mysql_query($query_depth_page, $id_connect);
                            if (mysql_num_rows($result_depth_page) > 0) {
                                $sql = "UPDATE " . PHPDIG_DB_PREFIX . "site_page SET depth='$depth' "
                                        . " WHERE site_id='$id'";
                            } else {
                                $sql = "INSERT INTO " . PHPDIG_DB_PREFIX . "site_page (site_id,depth) VALUES  "
                                        . "('$id', '$depth') ";
                            }
                            $res = mysql_query($sql, $id_connect);
                        }
                    }

                    if (isset($_REQUEST['sent'])) {
                        $upd_done = 1;
                    } else {
                        $upd_done = 0;
                    }
                    form_cron_limits($id_connect, $upd_done);
                }
                ?>
            </td></tr></table>
</body>
</html>

<?
exit();

function form_cron_limits($id_connect, $upd = 0) {
    if ((isset($_GET['dir'])) && ($_GET['dir'] == 'DESC'))
        $dir = 'ASC';
    else
        $dir = 'DESC';

    if ($upd == 1) {
        ?><p class='grey'><?= phpdigPrnMsg('upd2') ?></p><br><?
    }
    ?>	
    <table class="borderCollapse" border="0">
        <tr>
            <td class="blueForm"><a href="limit_update.php?OB=site_id&dir=<?= $dir ?>"><?= phpdigPrnMsg('id') ?></a></td>
            <td class="blueForm"><a href="limit_update.php?OB=site_url&dir=<?= $dir ?>"><?= phpdigPrnMsg('url') ?></a></td>
            <?php
            if (CRON_ENABLE) {
                ?><td class="blueForm"><a href="limit_update.php?OB=days&dir=<?= $dir ?>"><?= phpdigPrnMsg('days') ?></a></td><?php }
            ?>
            <td class="blueForm"><a href="limit_update.php?OB=links&dir=<?= $dir ?>"><?= phpdigPrnMsg('links') ?></a></td>
            <td class="blueForm"><a href="limit_update.php?OB=depth&dir=<?= $dir ?>"><?= phpdigPrnMsg('depth') ?></a></td>
        </tr>
        <form class="grey" action="limit_update.php" method="post">
            <?php
            //list of sites in the database
            $query = "SELECT S.site_id,S.site_url,P.days,P.links,P.depth 
				FROM " . PHPDIG_DB_PREFIX . "sites AS S  
				LEFT JOIN " . PHPDIG_DB_PREFIX . "site_page AS P ON S.site_id=P.site_id ";
            /* stabiliamo il campo su cui ordinare i dati */
            if (isset($_GET['OB'])) {
                switch ($_GET['OB']) {

                    case("site_id"):
                        $query .= ' ORDER BY S.site_id';
                        break;

                    case('site_url'):
                        $query .= ' ORDER BY S.site_url';
                        break;

                    case('days'):
                        $query .= ' ORDER BY P.days';
                        break;

                    case('links'):
                        $query .= ' ORDER BY P.links';
                        break;

                    case('depth'):
                        $query .= ' ORDER BY P.depth';
                        break;

                    default:
                        $query .= ' ORDER BY S.site_url';
                        break;
                }
            } else {
                $query .= ' ORDER BY S.site_url';
            }

            //echo $query;
            // ordinamento discendente
            if ((isset($_GET['dir'])) && ($_GET['dir'] == 'DESC'))
                $query .= ' DESC';
            else
                $query .= ' ASC';
            /*
             * Build the query
             */
            $col = 1;
            $result_id = mysql_query($query, $id_connect);
            while (list($id, $url, $days_db, $links, $depth) = mysql_fetch_row($result_id)) {
                switch ($col) {
                    case 1:
                        $class = 'greyFormDark';
                        $col++;
                        break;

                    case 2:
                        $class = 'greyForm';
                        $col++;
                        break;

                    case 3:
                        $class = 'greyFormLight';
                        $col++;
                        break;

                    case 4:
                        $class = 'greyForm';
                        $col++;
                        break;
                }
                if ($col == 5)
                    $col = 1;
                ?>
                <tr class="<?= $class ?>">		
                    <td class="<?= $class ?>"><?= $id ?></td>	
                    <td class="<?= $class ?>"><?= $url ?></td>
                    <?php
                    if (CRON_ENABLE) {
                        ?><td class="<?= $class ?>">
                            <input class="phpdigSelect" type="text" name="days[<?= $id ?>]" value="<?= $days_db ?>" size="10"/>
                        </td><?php } ?>
                    <td class="<?= $class ?>">
                        <input class="phpdigSelect" type="text" name="links[<?= $id ?>]" value="<?= $links ?>" size="10"/>
                    </td>
                    <td class="<?= $class ?>">
                        <input class="phpdigSelect" type="text" name="depth[<?= $id ?>]" value="<?= $depth ?>" size="10"/>
                    </td>
                </tr>
            <? } ?>
            <tr><td><input type="submit" name="sent" value="<?php echo phpdigPrnMsg('go'); ?>"></td></tr>
        </form>
    </table>
<? } ?>