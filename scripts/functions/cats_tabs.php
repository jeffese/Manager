<?php

/**
 * @author ohyeah
 * @copyright 2010
 */
function getTabNamefromID($tabid) {
    global $dbh;
    return getDBDataRow($dbh, "SELECT CONCAT(`dbname`, '.', `tablecode`) AS `tabname`, `keyfield` FROM `exood_tablecodes` WHERE `table_id`={$tabid}");
}

function getTabNamefromCode($tabid) {
    global $dbh;
    return getDBDataRow($dbh, "SELECT * FROM `exood_tablecodes` WHERE `catcode`='{$tabid}' AND `mod_id`=1");
}

function getFullTablefromCat($catq) {
    $cat = str_replace("-", DS, $catq);
    $catl = explode("-", $catq);
    $catd = getTabNamefromCode(intval($catl[0]) . '-' . intval($catl[1]));
    if (count($catd) == 0) {
	$catd = getTabNamefromCode(intval($catl[0]));
    }
    return $catd;
}

function getTablefromCat($catq) {
    $catd = getFullTablefromCat($catq);
    return "`" . $catd['dbname'] . "`.`" . $catd['tablecode'] . "`";
}

function cat_cnt($tab) {
    global $dbh;
    $sql = "UPDATE `{$tab}` SET `subs` = 0";
    $update = runDBQry($dbh, $sql);

    $sql = "UPDATE `{$tab}` INNER JOIN (SELECT `parent_id`, COUNT( * ) AS `cnt` FROM `{$tab}` GROUP BY `parent_id`) AS `cats` ON `{$tab}`.`category_id` = `cats`.`parent_id` SET `{$tab}`.`subs` = `cats`.`cnt`";

    set_time_limit(30);
    echo runDBQry($dbh, $sql);
}

function biz_cnt() {
    global $dbh;
    $sql = "UPDATE `categories` SET `cnt` = 0";
    $update = runDBQry($dbh, $sql);

    $sql = "UPDATE `categories` INNER JOIN
            (SELECT `cat`, COUNT(*) AS cnt FROM 
                (SELECT `company_id`, SUBSTRING_INDEX( `cat1` , '-', %1\$d ) AS `cat` 
                    FROM `companies`
                    UNION DISTINCT
                    SELECT `company_id`, SUBSTRING_INDEX( `cat2` , '-', %1\$d ) AS `cat` 
                    FROM `companies`
                    UNION DISTINCT
                    SELECT `company_id`, SUBSTRING_INDEX( `cat3` , '-', %1\$d ) AS `cat` 
                    FROM `companies`
                    UNION DISTINCT
                    SELECT `company_id`, SUBSTRING_INDEX( `cat4` , '-', %1\$d ) AS `cat` 
                    FROM `companies`
                    UNION DISTINCT
                    SELECT `company_id`, SUBSTRING_INDEX( `cat5` , '-', %1\$d ) AS `cat` 
                    FROM `companies`
                    UNION DISTINCT
                    SELECT `old_companies`.`company_id`, SUBSTRING_INDEX( `old_cat`.`category_id` , '-', %1\$d ) AS `cat` 
                    FROM `old_companies` INNER JOIN `old_cat` ON `old_companies`.`category`=`old_cat`.`CatID`
                )
            AS crp GROUP BY `cat`) 
        AS `coys` ON `categories`.`category_id` = `coys`.`cat` SET `categories`.`cnt` = `coys`.`cnt`";

    for ($i = 0; $i < 10; $i++) {
	set_time_limit(30);
	$update = runDBQry($dbh, sprintf($sql, $i));
	if ($update > 0)
	    echo $update;
    }
}

function bizCntUpd($cats, $typ) {
    global $dbh;
    $cats = explode("-", $cat);
    $cur = "";
    foreach ($cats as $cnt) {
	$cur .= ( $cur != "" ? "-" : "") . $cnt;
	$sql = "UPDATE `categories` SET `cnt` = `cnt`{$typ}1 WHERE `category_id`='{$cur}'";
	runDBQry($dbh, $sql);
    }
}

function prod_cnt() {
    global $dbh;
    $sql = "UPDATE `shopcats` SET `cnt` = 0";
    $update = runDBQry($dbh, $sql);

    $sql = "SELECT DISTINCT `tablecode` FROM `exood_tablecodes` WHERE `mod_id`=1";
    $Ttabs = getDBData($dbh, $sql);

    $sql = "UPDATE `shopcats` INNER JOIN (SELECT SUBSTRING_INDEX( `category` , '-', %s ) AS `cat`, COUNT( * ) AS `cnt` FROM `exood_shop`.`%s` GROUP BY `cat` ) AS `prods` ON `shopcats`.`category_id` = `prods`.`cat` SET `shopcats`.`cnt` = `prods`.`cnt`";

    foreach ($Ttabs as $Ttab) {
	for ($i = 0; $i < 10; $i++) {
	    set_time_limit(30);
	    $update = runDBQry($dbh, sprintf($sql, $i, $Ttab['tablecode']));
	    if ($update > 0)
		echo $update;
	}
    }
}

function prodCntUpd($cat, $typ) {
    global $dbh;
    $cats = explode("-", $cat);
    $cur = "";
    foreach ($cats as $cnt) {
	$cur .= ( $cur != "" ? "-" : "") . $cnt;
	$sql = "UPDATE `shopcats` SET `cnt` = `cnt`{$typ}1 WHERE `category_id`='{$cur}'";
	runDBQry($dbh, $sql);
    }
}

function auto_cnt() {
    global $dbh;
    $sql = "UPDATE `auto_categories` SET `cnt` = 0";
    $update = runDBQry($dbh, $sql);

    $sql = "UPDATE `auto_categories` INNER JOIN ( SELECT `vtype`, COUNT( * ) AS `cnt` FROM `autos` GROUP BY `vtype` ) AS `vehicles` ON `auto_categories`.`category_id` = `vehicles`.`vtype` SET `auto_categories`.`cnt` = `vehicles`.`cnt`";
    $update .= '-' . runDBQry($dbh, $sql);

    $sql = "UPDATE `auto_categories` INNER JOIN ( SELECT CONCAT( `vtype`, '-', `subcat` ) AS cat, COUNT( * ) AS `cnt` FROM `autos` GROUP BY `cat` ) AS `vehicles` ON `auto_categories`.`category_id` = `vehicles`.`cat` SET `auto_categories`.`cnt` = `vehicles`.`cnt`";
    $update .= '-' . runDBQry($dbh, $sql);
//

    $sql = "UPDATE `auto_brands` SET `cnt` = 0";
    $update .= '-' . runDBQry($dbh, $sql);

    $sql = "UPDATE `auto_brands` INNER JOIN ( SELECT CONCAT( `vtype`, '-', `brandid` ) AS cat, COUNT( * ) AS `cnt` FROM `autos` GROUP BY `brandid` ) AS `vehicles` ON `auto_brands`.`category_id` = `vehicles`.`cat` SET `auto_brands`.`cnt` = `vehicles`.`cnt`";
    $update .= '-' . runDBQry($dbh, $sql);

    $sql = "UPDATE `auto_brands` INNER JOIN ( SELECT CONCAT( `vtype`, '-', `brandid`, '-', `serieid` ) AS cat, COUNT( * ) AS `cnt` FROM `autos` GROUP BY `cat` ) AS `vehicles` ON `auto_brands`.`category_id` = `vehicles`.`cat` SET `auto_brands`.`cnt` = `vehicles`.`cnt`";
    $update .= '-' . runDBQry($dbh, $sql);
    echo $update;
}

function autoCntUpd($typ, $vtyp, $sub, $brand, $serie) {
    global $dbh;
    $sql = "UPDATE `auto_categories` SET `cnt` = `cnt`{$typ}1 WHERE `category_id`='{$vtyp}'";
    runDBQry($dbh, $sql);

    $sql = "UPDATE `auto_categories` SET `cnt` = `cnt`{$typ}1 WHERE `category_id`='{$vtyp}-{$sub}'";
    runDBQry($dbh, $sql);

    $sql = "UPDATE `auto_brands` SET `cnt` = `cnt`{$typ}1 WHERE `category_id`='{$vtyp}-{$brand}'";
    runDBQry($dbh, $sql);

    $sql = "UPDATE `auto_brands` SET `cnt` = `cnt`{$typ}1 WHERE `category_id`='{$vtyp}-{$brand}-{$serie}'";
    runDBQry($dbh, $sql);
}

?>