<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Clients'));
$access = _xvar_arr_sub($_access, array('Customers'));
vetAccess('Clients', 'Customers', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print ~ 12 List
$buttons_links = array("","","","","","","","","index.php","","frmclient.submit()","","index.php");
$rec_status = 0;

$editFormAction = $_SERVER['PHP_SELF'];

$sql = "SELECT country_id, country FROM `".DB_NAME."`.`country` ORDER BY country";
$Tcountry = getDBData($dbh, $sql);

$sql = "SELECT `state` FROM `".DB_NAME."`.`state` ORDER BY `state`";
$Tstate = getDBData($dbh, $sql);

$TDept = getClassify(1);

$TCat = getClassify(6);

$sql = "SELECT VendorID, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorType>0 AND VendorType<5 ORDER BY `VendorName`";
$TClients = getDBData($dbh, $sql);

$sql = "SELECT cur_id, currencyname FROM `{$_SESSION['DBCoy']}`.`currencies` ORDER BY cur_id";
$Tcurrency = getDBData($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Find Staff</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
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
        <td valign="top"><span class="titles"><img src="/images/customers.jpg" alt="" width="240" height="300" /></span></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/custom/images/lblownerfind.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          </table>
          <form action="index.php" method="post" name="frmclient" id="frmclient">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
            <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td width="120" class="titles">Type:</td>
                    <td align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input name="ClientType" type="radio" id="ClientType_1" onclick="clientType(1, 1)" value="1" size="32" /></td>
                        <td>Individual</td>
                        <td><input name="ClientType" type="radio" id="ClientType_2" value="2" size="32" onclick="clientType(2, 1)" /></td>
                        <td>Corporate</td>
                        <td><input name="ClientType" type="radio" id="ClientType_3" value="3" size="32" onclick="clientType(2, 1)" /></td>
                        <td>Government</td>
                          </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Customer ID:</td>
                    <td align="left"><input type="text" name="VendorID" value="" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><input type="text" name="name" style="width: 300px"" id="name" /></td>
                      </tr>
                  <tr>
                    <td class="titles">Gender:</td>
                    <td align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input type="radio" name="sex" value="1" size="32" /></td>
                        <td>Male</td>
                        <td><input type="radio" name="sex" value="2" size="32" /></td>
                        <td>Female</td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Nationality: </td>
                    <td align="left"><select name="nationality">
                      <option value="" selected="selected">Any</option>
                      <?php foreach ($Tcountry as $row_Tcountry) { ?>
                      <option value="<?php echo $row_Tcountry['country_id'] ?>"><?php echo $row_Tcountry['country'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">ID Type: </td>
                    <td align="left"><select name="religion">
                      <option value="" selected="selected"></option>
                      <option value="1">Passport</option>
                      <option value="2">National ID.</option>
                      <option value="3">Driver's License</option>
                      <?php foreach ($Tcountry as $row_Tcountry) { ?>
                      <?php } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">ID No.:</td>
                    <td align="left"><input type="text" name="passportno" value="" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Address:</td>
                    <td align="left"><textarea name="City" style="width:300px"></textarea></td>
                  </tr>
                  <tr>
                    <td class="titles">Notes:</td>
                    <td align="left"><textarea name="Notes" style="width:300px"></textarea></td>
                  </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
</tr>

            </table>
            <input type="hidden" name="MM_insert" value="frmclient" />
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