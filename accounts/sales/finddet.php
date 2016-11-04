<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Sales'));
vetAccess('Accounts', 'Sales', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print ~ 12 List
$buttons_links = array("","","","","","","","","","","frmsales.submit()","","index.php");
$rec_status = 0;

$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_POST["name"])) {
    header("Location: details.php");
    exit;
}

$outid = _xses('OutletID');

$sql = "SELECT DISTINCT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
    INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` outcat ON `outlets`.Dept = outcat.catID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` vencat ON 
        (`outcat`.category_id LIKE CONCAT(`vencat`.category_id, '-%')
        OR  `outcat`.category_id = `vencat`.category_id)
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`                ON `vencat`.catID = `vendors`.DeptID 
    WHERE `account`=1 AND OutletID IN ($outid)";
$TOutlets = getDBData($dbh, $sql);

$sql = "SELECT VendorID, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorType=5 ORDER BY `VendorName`";
$TStaff = getDBData($dbh, $sql);

$sql = "SELECT DISTINCT `ItemID`, `ProdName`
        FROM `{$_SESSION['DBCoy']}`.items 
        INNER JOIN (
            SELECT `items_prod`.`ProductID` AS `id` 
            FROM `{$_SESSION['DBCoy']}`.`items_prod`
            INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`          ON `items_prod`.`ProductID`=`outlet`.`ProductID`
            WHERE `OutletID` IN ($outid)
            UNION
            SELECT `ServiceID` AS `id` 
            FROM `{$_SESSION['DBCoy']}`.`items_srv`
            WHERE ',$outid,' REGEXP CONCAT(',(', REPLACE(`outlets`,',','|'), '),')
            ) `grp`                                             ON `items`.`ItemID`=`grp`.`id`
        ORDER BY `ProdName`";
$TProds = getDBData($dbh, $sql);
/*
            UNION
            SELECT `PackageID` AS `id` 
            FROM `{$_SESSION['DBCoy']}`.`items_pkgs`
            WHERE ',$outid,' REGEXP CONCAT(',(', REPLACE(`items_pkgs`.`outlets`,',','|'), '),')
*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Find Staff</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
<link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
<script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
<script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
<script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["ProdName", "", 
            ["req", "Enter Name"]
        ]
    ]
    
    var  mCal, mCal2;
    window.onload = function() {
        mCal = new dhtmlxCalendarObject('StartDate', true, {isYearEditable: true, isMonthEditable: true});
	mCal.setSkin('dhx_black');
        mCal2 = new dhtmlxCalendarObject('EndDate', true, {isYearEditable: true, isMonthEditable: true});
	mCal2.setSkin('dhx_black');
    }
    
    function getOutlets() {
        var str = "";
        $("#Outlist option:selected").each(function() {
            str += "," + $(this).val();
        });
        $("#outlets").val(str.substr(1));
    }
    
    function getProds() {
        var str = "";
        $("#Prodlist option:selected").each(function() {
            str += "," + $(this).val();
        });
        $("#prods").val(str.substr(1));
    }
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="240" valign="top"><img src="/images/sales.jpg" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblSalesDet.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          </table>
          <form action="details.php" method="post" name="frmsales" id="frmsales">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">Find</td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td width="120" class="titles">&nbsp;</td>
                    <td class="darkgrey"><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                          <td><b>
                            <input name="strict" type="checkbox" id="strict" checked="checked" />
                          </b></td>
                          <td><b>
 Strict</b></td>
                          </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Client ID:</td>
                    <td align="left"><input type="text" name="VendorID" value="" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Client Name:</td>
                    <td align="left"><input type="text" name="name" style="width: 300px" /></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Invoice No.:</td>
                    <td align="left"><input type="text" name="InvoiceID" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles"> Period:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td class="black-normal">From</td>
                        <td><input name="StartDate" type="text" id="StartDate" value="" size="12" readonly="readonly" /></td>
                        <td>&nbsp;</td>
                        <td class="black-normal">To</td>
                        <td><input name="EndDate" type="text" id="EndDate" value="" size="12" readonly="readonly" /></td>
                      </tr>
                    </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Oulet(s):</td>
                    <td><select id="Outlist" size="5" onblur="getOutlets()">
                      <?php foreach ($TOutlets as $row_TOutlets) { ?>
                      <option value="<?php echo $row_TOutlets['OutletID'] ?>"><?php echo $row_TOutlets['OutletName'] ?></option>
                      <?php } ?>
                      </select>
                      <input type="hidden" name="outlets" id="outlets" value="" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Item(s):</td>
                    <td><select size="5" id="Prodlist" onblur="getProds()">
                      <?php foreach ($TProds as $row_TProds) { ?>
                      <option value="<?php echo $row_TProds['ItemID'] ?>"><?php echo $row_TProds['ProdName'] ?></option>
                      <?php } ?>
                    </select>
                      <input type="hidden" name="prods" id="prods" value="" /></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Sales Person:</td>
                    <td><select name="staff">
                      <option value="" selected="selected">Any</option>
                      <?php foreach ($TStaff as $row_TStaff) { ?>
                      <option value="<?php echo $row_TStaff['VendorID'] ?>"><?php echo $row_TStaff['VendorName'] ?></option>
                      <?php } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Posted:</td>
                    <td align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input name="Posted" type="radio" value="" size="32" checked="checked" /></td>
                        <td>All</td>
                        <td><input type="radio" name="Posted" value="1" size="32" /></td>
                        <td>Yes</td>
                        <td><input type="radio" name="Posted" value="0" size="32" /></td>
                        <td>No</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Refunded:</td>
                    <td align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input name="Refunded" type="radio" value="" size="32" checked="checked" /></td>
                        <td>All</td>
                        <td><input type="radio" name="Refunded" value="=20" size="32" /></td>
                        <td>Yes</td>
                        <td><input type="radio" name="Refunded" value="!=20" size="32" /></td>
                        <td>No</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Notes:</td>
                    <td><span class="TabbedPanelsContent">
                      <textarea name="Notes" style="width:300px"></textarea>
                      </span></td>
                  </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
</tr>

            </table>
            <input type="hidden" name="MM_insert" value="frmsales" />
            <?php include('../../scripts/buttonset.php')?>
          </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>            </tr>
          </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
