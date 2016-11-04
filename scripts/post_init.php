<?php
$TConfig = array();
if (isset($_SESSION['DBCoy'])) {
    $sql = "SELECT `term_name`, `class_name`, `arm_nm`, `crs_nm`, `lect_nm` 
    FROM `{$_SESSION['DBCoy']}`.`sch_sessions` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`sch_schemes` ON `sch_sessions`.`scheme`=`sch_schemes`.`schm_id` 
    WHERE `active`=1";
    $TConfig = getDBData($dbh, $sql);
}
if (count($TConfig) > 0) {
    define('TERM', $TConfig[0]['term_name']);
    define('LEVEL', $TConfig[0]['class_name']);
    define('ARM', $TConfig[0]['arm_nm']);
    define('COURSE', $TConfig[0]['crs_nm']);
    define('LECTURER', $TConfig[0]['lect_nm']);
} else {
    define('TERM', "Term");
    define('LEVEL', "Class");
    define('ARM', "Arm");
    define('COURSE', "Subject");
    define('LECTURER', "Teacher");
}
?>
