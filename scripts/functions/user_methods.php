<?php

function vetlogin($bool = true) {
    global $errors;
    if (isset($_SESSION['userid'])) {
        return true;
    } else {
        if (!$bool) {
            array_push($errors, array('Sign In', "You are not signed in."));
            return false;
        } else {
            $PgUrl = $_SERVER['PHP_SELF'];
            if (isset($_SERVER['QUERY_STRING'])) {
                $PgUrl .= "&" . htmlentities($_SERVER['QUERY_STRING']);
            }
            header("Location:login.php?PrevUrl=" . $PgUrl);
            exit;
        }
    }
}

function vetlogout() {
    if (isset($_SESSION['userid'])) {
        header("Location:/index.php");
        exit;
    }
}

function logout() {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

function ldap_auth($cred, $user, $password) {
    $grps = array();
    if (ldap_bind($cred['ldap'], $user . $cred['dom'], $password)) {
        $filter = "(sAMAccountName=$user)";
        $attributes = array("memberof");
        $result = ldap_search($cred['ldap'], $cred['dn'], $filter, $attributes);
        if ($result) {
            $entries = ldap_get_entries($cred['ldap'], $result);
            if (isset($entries[0]['memberof'])) {
                for ($m = 0; $m < $entries[0]['memberof']['count']; $m++) {
                    array_push($grps, substr($entries[0]['memberof'][$m], 3, stripos($entries[0]['memberof'][$m], ',') - 3));
                }
            }
        }
        ldap_unbind($cred['ldap']);
    }
    return $grps;
}

function getLDAP($coy) {
    global $dbh;
    return false;
    $sql = "SELECT `ad_auth`, `ad_host`, `ad_user`, `ad_pass`
            FROM `" . DB_NAME . "`.`coyinfo`
            WHERE `coyinfo`.`CoyID`=$coy";
    $ldap_cred = getDBDataRow($dbh, $sql);

    if ($ldap_cred) {
        $dn_parts = explode('.', $ldap_cred['ad_host']);
        array_shift($dn_parts);
        $ldap = ldap_connect($ldap_cred['ad_host']);
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        $ldap_cred['ldap'] = $ldap;
        $ldap_cred['units'] = $dn_parts;
        $ldap_cred['dom'] = '@' . implode('.', $dn_parts);
        $ldap_cred['dn'] = 'DC=' . implode(',DC=', $dn_parts);
    }
    return $ldap_cred;
}

function login($c, $u, $p) {
    global $vendor_sql, $dbh, $errors, $custom_pg;
    $coy = GSQLStr(_xvarloop($c), "int");
    $usr = GSQLStr(_xvarloop($u), "textv");
    $pw = GSQLStr(_xvarloop($p), "textv");
    $pass = hashPwd($pw, $usr);

    $sql = sprintf("SELECT * FROM `%s`.`users` WHERE `username`='$usr' AND `userpass`='$pass'", DB_COY . $coy);
    $login = getDBDataRow($dbh, $sql);

    if (!$login) {
        if (($ldap_cred = getLDAP($coy)) && ($grps = ldap_auth($ldap_cred, $usr, $pw))) {
            $sql = sprintf("SELECT * FROM `%s`.`users` WHERE `username`='$usr'", DB_COY . $coy);
            $login = getDBDataRow($dbh, $sql);
        } else {
            $_SESSION['X_BAD_CREDENTIALS'] = intval(_xses('X_BAD_CREDENTIALS')) + 1;
            array_push($errors, array("Error", "Username or password are wrong!"));
        }
    }

    if ($login) {
        $_SESSION['usergroup'] = array($login['usergroup']);
        $sql = "SELECT `coyinfo`.*, `currencyname`, `unitname`, `code`, `unitcode` 
            FROM `" . DB_NAME . "`.`coyinfo` 
            LEFT JOIN `" . DB_COY . $coy . "`.`currencies` ON `coyinfo`.`currency`=`currencies`.`cur_id` 
            WHERE `coyinfo`.`CoyID`=$coy";
        $_SESSION['COY'] = getDBDataRow($dbh, $sql);
        genLic();

        if ($login['active'] == 0) {
            $_SESSION['X_RESET'] = $login;
            header("Location: {$custom_pg}change_pass.php");
            exit;
        } else {
            $sql = "SELECT vendors.*, category_id AS `dept`, $vendor_sql FROM `" . DB_COY . $coy . "`.`vendors`
                        LEFT JOIN `" . DB_COY . $coy . "`.`classifications` ON `vendors`.DeptID = classifications.catID 
                        WHERE VendorID={$login['EmployeeID']}";
            $ids = getDBDataRow($dbh, $sql);
            $regex = implode('|', $_SESSION['usergroup']);
            $permissions = getDBData($dbh, "SELECT `permissions` FROM `" . DB_COY . $coy . "`.`usergroups` WHERE `usergroup` REGEXP '^($regex)$'");
            if (count($permissions) > 1) {
                $_permission = array('0');
                foreach ($permissions as $ky => $permit) {
                    if (strlen($permit) == 1 && $permit == '1') {
                        foreach ($_permission as $key => $char) {
                            $_permission[$key] = '1';
                        }
                    } elseif (strlen($permit) > 1) {
                        $permits = str_split($permit);
                        foreach ($permits as $col => $ch) {
                            if (!isset($_permission[$col]) || $_permission[$col] == 0 && $permits[$col] == 1)
                                $_permission[$col] = $permits[$col];
                        }
                    }
                }
                $permission = implode('', $_permission);
            } else {
                $permission = $permissions[0]['permissions'];
            }

            $_SESSION['ids'] = $ids;
            $_SESSION['coyid'] = $coy;
            $_SESSION['DBCoy'] = DB_COY . $coy;
            $_SESSION['userid'] = $usr;
            $_SESSION['passwd'] = $pw;
            $_SESSION['EmployeeID'] = $login['EmployeeID'];
            $_SESSION['accesskeys'] = permission_array($permission);
            $_SESSION['COY']['CoyName'] = _xpost('coyname');
            return true;
        }
    }
    return false;
}

/**
 * Create hash password
 */
function hashPwd($pwd, $salt) {
    return md5(sprintf("%s%s", $pwd, $salt));
}

function Array_rights() {
    return array(
        "Administration" => array("View",
            "Company Info" => array("View", "Edit", "Account View"),
            "Departments" => array("View", "Add", "Edit", "Del", "Print"),
            "Currency" => array("View", "Add", "Edit", "Del", "Print"),
            "Users" => array("View", "Add", "Edit", "Del", "Print"),
            "Usergroups" => array("View", "Add", "Edit", "Del", "Print"),
            "Traffic Control" => array("View", "Add", "Edit", "Del", "Print")
        ),
        "EDMS" => array("View",
            "Documents" => array("View", "Add", "Edit", "Del", "Print"),
            "Templates" => array("View", "Add", "Edit", "Del", "Print"),
            "Archive" => array("View", "Del", "Print"),
            "Archive Admin." => array("View", "Edit"),
            "Categories" => array("View", "Add", "Edit", "Del", "Print")
        ),
        "Personnel" => array("View",
            "Employees" => array("View", "Add", "Edit", "Del", "Print"),
            "Salaries" => array("View", "Add", "Edit", "Del", "Print"),
            "Pay Slips" => array("View", "Add", "Edit", "Del", "Print", "Post", "Unlock", "Dispatch"),
            "Deductions" => array("View", "Add", "Edit", "Print"),
            "Resource Lists" => array("View", "Add", "Edit", "Del", "Print"),
            "Categories" => array("View", "Add", "Edit", "Del", "Print")
        ),
        "Clients" => array("View",
            "Customers" => array("View", "Add", "Edit", "Del", "Print"),
            "Suppliers" => array("View", "Add", "Edit", "Del", "Print"),
            "Vendors" => array("View", "Add", "Edit", "Del", "Print"),
            "Cash Accounts" => array("View", "Add", "Edit", "Del", "Print", "Convert"),
            "Categories" => array("View", "Add", "Edit", "Del", "Print")
        ),
        "Operations" => array("View",
            "Services" => array("View", "Add", "Edit", "Del", "Print"),
            "Service Schedule" => array("View", "Add", "Edit", "Del", "Print"),
            "Maintenance" => array("View", "Add", "Edit", "Del", "Print"),
            "Categories" => array("View", "Add", "Edit", "Del", "Print")
        ),
        "Academics" => array("View",
            "Schemes" => array("View", "Add", "Edit", "Del", "Print"),
            "Sessions" => array("View", "Add", "Edit", "Del", "Print"),
            "Terms" => array("View", "Add", "Edit", "Del", "Print"),
            "Programs" => array("View", "Add", "Edit", "Del", "Print"),
            "Classes" => array("View", "Add", "Edit", "Del", "Print"),
            "Certificates" => array("View", "Add", "Edit", "Del", "Print"),
            "Grades" => array("View", "Add", "Edit", "Del", "Print"),
            "Courses" => array("View", "Add", "Edit", "Del", "Print"),
            "Electives" => array("View", "Add", "Edit", "Del", "Print"),
            "Course Selection" => array("View", "Add", "Edit", "Del", "Print"),
            "Assessment Structure" => array("View", "Add", "Edit", "Del", "Print"),
            "Assessments" => array("View", "Add", "Edit", "Del", "Print"),
            "Students" => array("View", "Add", "Edit", "Del", "Print"),
            "Parents" => array("View", "Add", "Edit", "Del", "Print")
        ),
        "Assets" => array("View",
            "Facilities" => array("View", "Add", "Edit", "Del", "Print", "Allocate"),
            "Equipment" => array("View", "Add", "Edit", "Del", "Print", "Allocate"),
            "Vehicles" => array("View", "Add", "Edit", "Del", "Print", "Allocate"),
            "Categories" => array("View", "Add", "Edit", "Del", "Print", "Allocate")
        ),
        "Stock" => array("View",
            "Products" => array("View", "Add", "Edit", "Del", "Print", "Edit Prices"),
            "Orders" => array("View", "Add", "Edit", "Del", "Print", "Post", "Receive"),
            "Packages" => array("View", "Add", "Edit", "Del", "Print"),
            "Transfers" => array("View", "Add", "Edit", "Del", "Print", "Transfer"),
            "Returns" => array("View", "Add", "Edit", "Del", "Print", "Return"),
            "Warehouses" => array("View", "Add", "Edit", "Del", "Print"),
            "Categories" => array("View", "Add", "Edit", "Del", "Print")
        ),
        "Library" => array("View",
            "Books" => array("View", "Add", "Edit", "Del", "Print"),
            "Categories" => array("View", "Add", "Edit", "Del", "Print"),
            "Shelves" => array("View", "Add", "Edit", "Del", "Print"),
            "Circulation" => array("View", "Add", "Edit", "Del", "Print"),
            "Materials" => array("View", "Add", "Edit", "Del", "Print"),
            "Settings" => array("View", "Add", "Edit", "Del", "Print")
        ),
        "Accounts" => array("View",
            "Sales" => array("View", "Add", "Edit", "Del", "Print", "Post", "Refund"
                , "Exchange Rate", "Add Income", "Edit Income"),
            "Income" => array("View", "Add", "Edit", "Del", "Print", "Post"),
            "Journal" => array("View", "Add", "Edit", "Del", "Print", "Post"),
            "Expenses" => array("View", "Add", "Edit", "Del", "Print", "Post"),
            "Outlets" => array("View", "Add", "Edit", "Del", "Print", "Kiosk-Mode","Supervisor"),
            "Categories" => array("View", "Add", "Edit", "Del", "Print")
        )
    );
}

function Array_Lnks() {
    return array(
        "Administration" => array(
            "Company Info" => array(COY . " Information", "/admin/companies/view.php"),
            "Departments" => array("Departments", "/admin/departments/"),
            "Currency" => array("Currencies", "/admin/currency/"),
            "Users" => array("Users", "/admin/users/"),
            "Usergroups" => array("Usergroups", "/admin/usergroups/"),
            "Traffic Control" => array("Traffic Control", "/admin/gateway/")
        ),
        "EDMS" => array(
            "Documents" => array("Documents", "/EDMS/"),
            "Templates" => array("Templates", "/EDMS/templates/"),
            "Archive" => array("Archive", "/EDMS/archive/"),
            "Archive Admin." => array("Archive Admin.", "EDMS/archive/admin.php"),
            "Categories" => array("Categories", "/EDMS/cat/")
        ),
        "Assets" => array(
            "Facilities" => array("Facilities", "/assets/facilities/"),
            "Equipment" => array("Equipment", "/assets/equipment/"),
            "Vehicles" => array("Vehicles", "/assets/vehicles/"),
            "Categories" => array("Categories", "/assets/cat/")
        ),
        "Stock" => array(
            "Products" => array("Products", "/stock/products/"),
            "Orders" => array("Purchases", "/stock/orders/"),
            "Packages" => array("Packages", "/stock/packages/"),
            "Transfers" => array("Item Transfers", "/stock/transfers/"),
            "Returns" => array("Purchase Returns", "/stock/returns/"),
            "Warehouses" => array("Warehouses", "/stock/warehouses/"),
            "Categories" => array("Categories", "/stock/cat/")
        ),
        "Clients" => array(
            "Customers" => array("Customers", "/clients/customers/"),
            "Suppliers" => array("Suppliers", "/clients/suppliers/"),
            "Vendors" => array("Vendors", "/clients/vendors/"),
            "Cash Accounts" => array("Cash Accounts", "/clients/cash/"),
            "Categories" => array("Categories", "/clients/cat/")
        ),
        "Personnel" => array(
            "Employees" => array("Employees", "/personnel/employees/"),
            "Salaries" => array("Salaries", "/personnel/salaries/"),
            "Pay Slips" => array("Pay Slips", "/personnel/payslips/"),
            "Categories" => array("Categories", "/personnel/tools/cat/")
        ),
        "Operations" => array(
            "Services" => array("Services", "/operations/services/"),
            "Service Schedule" => array("Service Schedule", "/operations/servsched/"),
            "Maintenance" => array("Maintenance", "/operations/maintenance/"),
            "Categories" => array("Categories", "/operations/cat/")
        ),
        "Academics" => array(
            "Sessions" => array("Sessions", "/acad/sessions/"),
            "Programs" => array("Programs", "/acad/programs/"),
            "Classes" => array(LEVEL . (substr(LEVEL, -1) == 's' ? 'es' : 's'), "/acad/programs/classes/"),
            "Courses" => array(COURSE . "s", "/acad/courses/"),
            "Students" => array("Students", "/acad/students/"),
        ),
        "Library" => array(
            "Books" => array("View", ""),
            "Categories" => array("View", ""),
            "Shelves" => array("View", ""),
            "Circulation" => array("View", ""),
            "Materials" => array("View", ""),
            "Settings" => array("View", "")
        ),
        "Accounts" => array(
            "Sales" => array("Sales", "/accounts/sales/"),
            "Income" => array("Income", "/accounts/payments/"),
            "Journal" => array("Journal", "/accounts/bills/"),
            "Expenses" => array("Expenses", "/accounts/expenses/"),
            "Outlets" => array("Outlets", "/accounts/outlets/"),
            "Categories" => array("Categories", "/accounts/cat/")
        )
    );
}

function permission_array($rights) {
    $permissions = array();
    $arr_rights = Array_rights();
    $my_rights = Taboom($rights, array('#', '_', '&', '~'), false);
    $def = strlen($rights) == 1;

    $i = 0;
    foreach ($arr_rights as $mod => $permits) {
        $j = 0;
        foreach ($permits as $sub => $permit) {
            if (!is_array($permit)) {
                if ($_SESSION['license'][$mod][$permit] == 0) {
                    $permissions[$mod][$permit] = -1;
                } else if ($def) {
                    $permissions[$mod][$permit] = $rights;
                } else {
                    $permissions[$mod][$permit] = isset($my_rights[$i][$j]) ? $my_rights[$i][$j] : 0;
                }
            } else {
                foreach ($permit as $k => $val) {
                    if ($_SESSION['license'][$mod][$sub] == 0) {
                        $permissions[$mod][$sub][$val] = -1;
                    } else if ($def) {
                        $permissions[$mod][$sub][$val] = $rights;
                    } else {
                        $permissions[$mod][$sub][$val] = isset($my_rights[$i][$j][$k]) ? $my_rights[$i][$j][$k] : 0;
                    }
                }
            }
            $j++;
        }
        $i++;
    }
    return $permissions;
}

function genLic() {
    $lic = Explode_3($_SESSION['COY']['license']);
    $arr_rights = Array_rights();
    $i = 0;
    foreach ($arr_rights as $mod => $permits) {
        $j = 0;
        foreach ($permits as $sub => $permit) {
            if ($j == 0) {
                $_SESSION['license'][$mod][$permit] = isset($lic[$i][0]) ? $lic[$i][0] : 0;
            } else {
                $_SESSION['license'][$mod][$sub] = isset($lic[$i][$j]) ? $lic[$i][$j] : 0;
            }
            $j++;
        }
        $i++;
    }
}

function demo() {
    $shakedown = intval(file_get_contents(ROOT . '/tmp/shkdn')) == 1;
    if ($shakedown)
    $tm = strtotime('2016-2-10 23:00:00') - time();
    $_tm = intval(file_get_contents(ROOT . '/tmp/tm'));
    if ($tm < $_tm && $tm + 1000 < $_tm)
        file_put_contents (ROOT . '/tmp/tm', $tm);
    return $tm > $_tm || $tm > 172000 || $tm < 0;
}

function vetAccess($mod, $sub, $key, $die = true) {
    if (!isset($_SESSION['userid']) && !(isset($_GET['u']) &&
            isset($_GET['p']) && login('coy', 'u', 'p'))) {
        header("Location: /relogin.htm");
        exit;
    }
    if (/*demo() || */(isset($_SESSION['accesskeys'][$mod]) && !canAccess($mod, $sub, $key))) {
        if ($die) {
            header("Location: /denied.php");
            exit;
        } else
            return false;
    }
    return true;
}

function canAccess($mod, $sub, $key) {
    return $_SESSION['accesskeys'][$mod]['View'] == 1 && $_SESSION['accesskeys'][$mod][$sub][$key] == 1;
}

function isTeacher($arm, $course, $term, $kill = false) {
    if (!vetCourse($arm, $course, $term)) {
        if ($kill) {
            header("Location: /denied.php");
            exit;
        } else
            viewButs(false);
    }
}

function vetCourse($arm, $course, $term) {
    global $errors, $dbh;
    if (isAdminPowerGrp())
        return true;
    $sql = "SELECT `courses`, `lecturers`
        FROM `{$_SESSION['DBCoy']}`.`sch_electives`
        WHERE `elect_arm`=$arm AND `elect_term`=$term";
    $crs = getDBDataRow($dbh, $sql);
    if (count($crs) > 0) {
        $ids = explode("|", $crs['lecturers']);
        $crsids = explode("|", $crs['courses']);
        $x = 0;
        while ($x < count($ids) && $crsids[$x] != $course && $ids[$x] != $_SESSION['EmployeeID']) {
            $x++;
        }
        return $x < count($ids);
    } else
        return false;
}

function isAdviser($arm, $kill = false) {
    if (!vetClass($arm)) {
        if ($kill) {
            header("Location: /denied.php");
            exit;
        } else
            viewButs(false);
    }
}

function vetClass($arm) {
    global $errors, $dbh;
    if (isAdminPowerGrp())
        return true;
    $sql = "SELECT `arm_teacher`, `cls_teacher`
        FROM `{$_SESSION['DBCoy']}`.`sch_arms`
        INNER JOIN `{$_SESSION['DBCoy']}`.`sch_class` ON `sch_arms`.`class`=`sch_class`.`class_id`
        WHERE `arm_id`=$arm AND 
        (`arm_teacher`={$_SESSION['EmployeeID']} OR `cls_teacher`={$_SESSION['EmployeeID']})";
    $crs = getDBDatarow($dbh, $sql);
    return count($crs) > 0;
}

function vetClassView($arm, $kill = false) {
    if (!vetClass($arm)) {
        if ($kill) {
            header("Location: /denied.php");
            exit;
        } else
            viewButs(false);
    }
}

function vetFullClass($cls) {
    global $errors, $dbh;
    if (isAdminPowerGrp())
        return true;
    $sql = "SELECT `cls_teacher`
        FROM `{$_SESSION['DBCoy']}`.`sch_class`
        WHERE `class_id`=$cls AND `cls_teacher`={$_SESSION['EmployeeID']}";
    $crs = getDBDatarow($dbh, $sql);
    return count($crs) > 0;
}

function viewCourse($arm, $course, $term, $kill = false) {
    if (!vetClass($arm) && !vetCourse($arm, $course, $term)) {
        if ($kill) {
            header("Location: /denied.php");
            exit;
        } else {
            viewButs(false);
        }
    }
}

function isDept($dept) {
    if (!viewDept($dept)) {
        header("Location: /denied.php");
        exit;
    }
}

function viewDept($dept) {
    return isAdminPowerGrp() || $_SESSION['ids']['DeptID'] == $dept;
}

function deptQry($only = true) {
    global $errors, $dbh;
    $deptid = intval($_SESSION['ids']['DeptID']);
    $sql = "SELECT catID, catname FROM `{$_SESSION['DBCoy']}`.`classifications` WHERE catype=1 AND catID<>1 ";

    if (isAdminPowerGrp()) {
        $fld = getDBDataFldkey($dbh, $_SESSION['DBCoy'] . '.classifications', 'catID', 'category_id', $deptid);
        $qry = "$sql AND `catID`=$deptid";
        $qry .= $only ? '' : " OR category_id LIKE '^$fld-%'";
        return "$qry ORDER BY `catname`";
    } else
        return "$sql ORDER BY `catname`";
}

function DeptButs($dept) {
    viewButs(viewDept($dept));
}

function isOutlet($outlet) {
    if (!editOutlet($outlet)) {
        header("Location: /denied.php");
        exit;
    }
}

function viewOutlet($outlet) {
    return isAdminPowerGrp() || $_SESSION['ids']['DeptID'] == $outlet;
}

function editOutlet($outlet) {
    return $_SESSION['ids']['DeptID'] == $outlet;
}

/**
 * Button permission
 * @param $_bottons array(new, edit, delete, print, Nav, find)
 * @param $rec_status int 1=view, 2=insert, 3=edit
 * Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
 * 
 * $button_links array() | links of javascript commands
 */
//button_permission(array(''))
function button_permission($_bottons, $rec_status) {
    global $buttons;
    if (!is_array($_bottons)) {
        trigger_error("$_buttons must be an array", E_USER_WARNING);
        return false;
    }
    //nav
    $buttons[0] = $_bottons[4];
    $buttons[1] = $_bottons[4];
    $buttons[2] = $_bottons[4];
    $buttons[3] = $_bottons[4];

    $buttons[4] = $_bottons[0];
    $buttons[5] = $_bottons[1];
    $buttons[7] = $_bottons[2];
    $buttons[9] = 1;
    $buttons[10] = $_bottons[5];
    $buttons[11] = $_bottons[3];

    // Button states
    if ($rec_status == 2 || $rec_status == 3) {
        for ($i = 0; $i < count($buttons); $i++) {
            $buttons[$i] = 0;
        }
        $buttons[6] = 1;
        $buttons[8] = 1;
    } elseif ($rec_status == 1) {
        $buttons[6] = 0;
        $buttons[8] = 0;
    }
}

function viewTerm($term, $kill = false) {
    global $dbh;
    if (isAdminPowerGrp())
        return;
    $active = getDBDataFldkey($dbh, $_SESSION['DBCoy'] . '.sch_terms', 'term_id', 'active', $term);

    if ($active == 0 && $kill) {
        header("Location: /denied.php");
        exit;
    }
    viewButs($active == 1);
}

function isAdminPowerGrp() {
    return in_array('PowerGroup', $_SESSION['usergroup']) || in_array('Admin', $_SESSION['usergroup']);
}

function viewButs($active) {
    global $_bottons;
    if (!$active) {
        $_bottons[0] = 0;
        $_bottons[1] = 0;
        $_bottons[2] = 0;
    }
}

function vetcaptcha() {
    global $errors;
    $txt = strtolower(_xpost('captcha'));
    if (USE_CAPTCHA == 1) {
        if (!isset($_SESSION['captchacode'])) {
            array_push($errors, array('Suspicious Activity', 'No Security code was detected!'));
            return false;
        } elseif ($txt == '') {
            array_push($errors, array('Incomplete Entry', 'No Security code was entered!'));
            return false;
        } elseif ($txt != _xses('captchacode')) {
            array_push($errors, array('Wrong Entry', 'The Security code you entered was wrong!'));
            return false;
        }
    }
    return true;
}

function delCmd() {
    global $buttons_links;
    if (strlen($buttons_links[7]) == 0)
        return "";
    $pos1 = stripos($buttons_links[7], "[");
    $pos2 = stripos($buttons_links[7], "]");

    if ($pos1 !== false && $pos1 == 0 && $pos2 > 0) {
        $buttons_links[7] = "if (confirm('Are you sure you want to delete this " .
                substr($buttons_links[7], $pos1 + 1, $pos2 - 1) . " record?')) document.location='" .
                substr($buttons_links[7], $pos2 + 1) . "'";
    }
    return $buttons_links[7];
}

function savCmd() {
    global $buttons_links;
    if (strlen($buttons_links[6]) == 0 || stripos($buttons_links[6], " ") > 0 || stripos($buttons_links[6], ",") > 0)
        return $buttons_links[6];

    $frm = $buttons_links[6];
    return "if (!document.$frm.onsubmit || document.$frm.onsubmit()) document.$frm.submit()";
}

function urlNav($k) {
    global $buttons_links;
    if ($k == 11) {
        if (strlen($buttons_links[11]) == 0)
            return "";
        elseif (substr($buttons_links[11], 0, 1) == '|') {
            return "printSys('{$buttons_links[11]}')";
        } else {
            return "window.open('$buttons_links[11]')";
        }
    }
    if (stripos($buttons_links[$k], ".") === false)
        return "document.$buttons_links[$k].submit()";
    if (strlen($buttons_links[$k]) == 0 || stripos($buttons_links[$k], " ") > 0 ||
            stripos($buttons_links[$k], ",") > 0 || stripos($buttons_links[$k], ")") > 0)
        return $buttons_links[$k];
    $pos1 = stripos($buttons_links[$k], "[");
    $pos2 = stripos($buttons_links[$k], "]");
    $pfx = "";

    if ($pos1 !== false && $pos1 == 0 && $pos2 > 0)
        $pfx = substr($buttons_links[$k], $pos1 + 1, $pos2 - 1) . ".";

    return "{$pfx}document.location.href='" .
            substr($buttons_links[$k], $pos2 === false ? 0 : $pos2 + 1) . "'";
}

function url_print($k) {
    global $buttons_links;
    $url = "";
    $xtra = "";
    switch ($k) {
        case 1:
            $url = "excel";
            break;
        case 2:
            $url = "pdf";
            break;
        case 3:
            $url = "email";
            $xtra = "vid=$buttons_links[14]&title=" . urlencode($buttons_links[15]) . "&body=" . urlencode($buttons_links[16]) . "&";
            break;
    }
    $pths = urlencode(dirname($_SERVER['PHP_SELF']) . DS . $buttons_links[11]);
    return "window.open('/scripts/$url.php?{$xtra}url=$pths')";
}

function genPDF($url, $outfile = 'doc', $dl = FALSE, $landscape = FALSE, $config = "", $host = WEBSITE) {
    $fname = filenameUsed(ROOT . "/tmp/", "gen_pdf.pdf");
    $pth = explode('?', $url);
    $u = urlencode($_SESSION['userid']);
    $p = urlencode($_SESSION['passwd']);
    $url = "http://" . $host . $pth[0] . set_QS(count($pth) == 2 ? $pth[1] : '', "coy={$_SESSION['coyid']}&u=$u&p=$p");
    exec(HTML2PDF . ($landscape ? ' -O Landscape' : '') . " $config \"$url\" $fname");

    if ($dl) {
        header("Content-Type: application/pdf");
        header("Cache-Control: no-cache");
        header("Accept-Ranges: none");
        header("Content-Disposition: attachment; filename=\"$outfile.pdf\"");
        readfile($fname);
        unlink($fname);
    }
    return $fname;
}

function mailPDF($url, $landscape, $body, $email, $rcpt, $subj) {
    $fname = genPDF($url, 'doc', FALSE, $landscape);

    require_once(ROOT . "/lib/phpmailer/class.phpmailer.php");

    class phpmailerAppException extends Exception {

        public function errorMessage() {
            global $errors;
            array_push($errors, array('Error', $this->getMessage()));
        }

    }

    $mail = new phpmailer();

//SMTP
    $MTAcode = 3;
    switch ($MTAcode) {
        case 1:
            $mail->IsMail();      // telling the class to use PHP's Mail()
            break;
        case 2:
            $mail->IsQmail();     // telling the class to use Qmail
            break;
        case 3:
            $mail->IsSMTP();  // telling the class to use SMTP
            $mail->SMTPDebug = 1;
            $mail->SMTPAuth = true;     // enable SMTP authentication
            $mail->SMTPKeepAlive = true;
            $mail->Port = $_SESSION['COY']['smtp_port'];      // set the SMTP port
            $mail->Host = $_SESSION['COY']['smtp'];    // SMTP server
            $mail->Username = $_SESSION['COY']['admin_mail']; // SMTP account username
            $mail->Password = $_SESSION['COY']['email_pass']; // SMTP account password
            break;
    }

    $mail->SetFrom($_SESSION['COY']['admin_mail'], $_SESSION['COY']['CoyName']);
    $mail->AddBCC($_SESSION['COY']['ReceiptComment'], $_SESSION['COY']['CoyName'] . " Auditor");
    $mail->Subject = $subj;
    $mail->WordWrap = 80; // set word wrap
    $mail->IsHTML(false); // send as plain Text
    $mail->AltBody = '';
    $mail->Body = <<<EOF
Hello {$rcpt},

$body

Thank you

{$_SESSION['COY']['CoyName']}
    
EOF;
    $addrs = explode(',', $email);
    foreach ($addrs as $addr)
        $mail->AddAddress($addr, $rcpt);
    if ($mail->AddAttachment($fname) === false) {
        echo "Could not attach $fname";
    } else {
        set_time_limit(30);
        $fail = "<br /><b class='red-normal'>Failed: {$rcpt}&lt;{$email}&gt;</b>";
        try {
            if ($mail->Send())
                echo "<b class='blue-normal'>SENT!</b>";
            else
                echo $fail;
        } catch (phpmailerAppException $e) {
            echo $fail;
        }
    }
    unlink($fname);
}

function doExcel($TabArray) {
    if (isset($_GET['xcel'])) {
        excel($TabArray, "Data");
    }
}

function excel($tab, $title) {
    require_once ROOT . '/lib/Excel/PHPExcel.php';

    if (count($tab) == 0)
        exit;

    $cols = array("", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M",
        "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator($_SESSION['COY']['CoyName'])
            ->setLastModifiedBy($_SESSION['userid'])
            ->setTitle("Data Export");

    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
    $objDrawing->setName("Logo");
    $objDrawing->setDescription("{$_SESSION['COY']['CoyName']} Logo");
    $objDrawing->setPath('../..' . COYPIX_DIR . $_SESSION['coyid'] . "/xxpix.jpg");
    $objDrawing->setCoordinates('A1');
    $objDrawing->setOffsetX(1);
    $objDrawing->setOffsetY(6);

    $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells("A1:C6")
            ->mergeCells("D1:J1")
            ->mergeCells("D2:J2")
            ->mergeCells("B3:C3")
            ->mergeCells("B4:C4")
            ->setCellValue("D1", $_SESSION['COY']['CoyName'])
            ->setCellValue("D2", $title);
    $h = 1;
    foreach ($tab as $row) {
        foreach ($row as $key => $col) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cols[$h]8", $key);
            $h++;
        }
        break;
    }
    $i = 9;
    foreach ($tab as $row) {
        $h = 1;
        foreach ($row as $col) {
            $a = substr($col, 0, 1);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cols[$h]$i", ($a == '=' ? "'" : "") . $col);
            $h++;
        }
        $i++;
    }

    $objPHPExcel->getActiveSheet()->setTitle($title);
    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $title . '.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function getCat($typ) {
    global $dbh;
    $sql = "SELECT CategoryID, Category, par FROM `{$_SESSION['DBCoy']}`.`status` 
        WHERE cattype='$typ' AND InUse=1 ORDER BY Category";
    return getDBData($dbh, $sql);
}

function getClassify($typ, $where = "") {
    global $dbh;
    $sql = "SELECT catID, category_id, catname FROM `{$_SESSION['DBCoy']}`.`classifications` 
        WHERE catype=$typ AND category_id<>'$typ' $where ORDER BY `catname`";
    return getDBData($dbh, $sql);
}

function getVendor($typ, $client = 0, $qry = "") {
    global $dbh, $vendor_sql;
    $clt = $client > 0 ? "AND ClientType=$client" : "";
    $sql = "SELECT VendorID, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` 
        WHERE VendorType=$typ $clt $qry ORDER BY `VendorName`";
    return getDBData($dbh, $sql);
}

function AccStat($AccKy, $rowKy) {
    if ($AccKy == 1 && $rowKy == 0)
        return 1;
    else
        return 0;
}

function inheritedRight($tab, $key, $par, $fld, $id, $val) {
    global $dbh;
    do {
        $sql = "SELECT $par, $fld FROM $tab WHERE $key=$id";
        $res = getDBDataRow($dbh, $sql);
    } while ($res != NULL && intval($res[$par]) != 0 && ($id = $res[$par]) && $res[$fld] != $val);
    return getDBDataFldkey($dbh, "`{$_SESSION['DBCoy']}`.`$tab`", $key, $fld, $id);
}

function setAssOwn($ass, $typ, $id, $kid) {
    global $dbh;
    $sql = "UPDATE `{$_SESSION['DBCoy']}`.`assets` 
        SET `desgtype`=$typ, `occupant`=$id, `Children`=$kid 
        WHERE AssetID=$ass OR `parent`=$ass";
    runDBqry($dbh, $sql);

    $sql = "SELECT AssetID FROM `{$_SESSION['DBCoy']}`.`assets` 
        WHERE `parent`=$ass";
    $TAssets = getDBData($dbh, $sql);
    foreach ($TAssets as $row) {
        setAssOwn($row['AssetID'], $typ, $id, $kid);
    }
    setAssPar($ass, $kid);
}

function setAssPar($ass, $kid) {
    global $dbh;
    $sql = "SELECT `parent` FROM `{$_SESSION['DBCoy']}`.`assets` 
            WHERE AssetID=$ass";
    $row = getDBDataRow($dbh, $sql);
    $par = intval($row['parent']);
    if ($par > 0) {
        $From = "FROM `{$_SESSION['DBCoy']}`.`assets` 
        WHERE `parent`=$par AND `Children`=1";
        $cnt = getDBDatacnt($dbh, $From);

        if ($cnt == 0) {
            $sql = "UPDATE `{$_SESSION['DBCoy']}`.`assets` 
            SET `Children`=$kid 
            WHERE AssetID=$par";
            runDBqry($dbh, $sql);

            setAssPar($par, $kid);
        }
    }
}

function docs($shelf, $recid, $elem = 'doc', $d = 'd', $f = 'f', $fn = 'fn', $doc = 'doc') {
    global $dbh;
    $dirname = DOC_ARCHV;
    $dirn = explode(DS, $_SESSION['coyid'] . DS . $shelf);
    for ($i = 0; $i < count($dirn); $i++) {
        $dirname = pixdir($dirname, $dirn[$i] . DS);
    }
    $dirname = pixdir($dirname, $recid . DS);

    $cnt = intval(_xpost('docnt'));
    for ($i = 1; $i <= $cnt; $i++) {
        if (isset($_FILES[$elem . $i]) && $_FILES[$elem . $i]['name'] != "") {
            $fname = filenameUsed($dirname, $doc);
            $filename = "$dirname$fname";
            $post_file = $_FILES[$elem . $i]['name'];
            $tmp_file = $_FILES[$elem . $i]['tmp_name'];
            if (move_uploaded_file($tmp_file, $filename)) {
                $sql = sprintf("INSERT INTO {$_SESSION['DBCoy']}.`documentfiles`(`shelf`, 
                                `OwnerID`, `EmployeeID`, `Category`, `fname`, `FileName`, 
                                `SecCode`, `Description`) 
                                VALUES (%s,%s,%s,%s,%s,%s,%s,%s)", 
                        GSQLStr($shelf, "text"), 
                        $recid, 
                        GSQLStr($_SESSION['ids']['VendorID'], "int"), 
                        "NULL", 
                        GSQLStr($fname, "text"), 
                        GSQLStr($post_file, "text"), 
                        "NULL", 
                        GSQLStr(_xpost("doc_info$i"), "text"));
                runDBQry($dbh, $sql);
                chmod($filename, 0755);
            }
        } elseif (_xpost($d . $i) == '0') {
            $filename = "$dirname" . _xpost($fn . $i);
            $id = intval(_xpost($f . $i));
            $del = runDBQry($dbh, "DELETE FROM {$_SESSION['DBCoy']}.`documentfiles` WHERE `DocID`=$id");
            if ($del == 1 && file_exists($filename)) {
                unlink($filename);
            }
        }
    }
}

function delDocs($vmod, $vkey, $id) {
    global $dbh;
    $shelf = $vmod . DS . $vkey;
    $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`documentfiles`
        WHERE `shelf`='$shelf' AND `OwnerID`=$id";
    runDBQry($dbh, $sql);
    
    $dirname = DOC_ARCHV . $_SESSION['coyid'] . DS . $shelf . DS . $id . DS;
    rmdirr($dirname);
}

function delPixs($PTH, $id) {
    $dirname = ROOT . $PTH . $_SESSION['coyid'] . DS . $id;
    if (file_exists($dirname)) {
        rmdirr($dirname);
    }
}
