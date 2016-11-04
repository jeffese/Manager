<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Orders'));
vetAccess('Stock', 'Orders', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print ~ 12 List
$buttons_links = array("","","","","","","","","","","frmorder.submit()","","index.php");
$rec_status = 0;

$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_POST["name"])) {
	header("Location: index.php");
	exit;
}

$sql = "SELECT country_id, country FROM `".DB_NAME."`.`country` ORDER BY country";
$Tcountry = getDBData($dbh, $sql);

$sql = "SELECT `state` FROM `".DB_NAME."`.`state` ORDER BY `state`";
$Tstate = getDBData($dbh, $sql);

$sql = "SELECT OrderID, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorType=5 ORDER BY `VendorName`";
$TSup = getDBData($dbh, $sql);

$TDept = getClassify(1);

$TCat = getClassify(5);

$sql = "SELECT CategoryID, Category FROM `{$_SESSION['DBCoy']}`.`status` WHERE cattype='proj' ORDER BY Category";
$TProj = getDBData($dbh, $sql);

$sql = "SELECT CategoryID, Category FROM `{$_SESSION['DBCoy']}`.`status` WHERE cattype='bank' ORDER BY Category";
$TBank = getDBData($dbh, $sql);

$sql = "SELECT CategoryID, Category FROM `{$_SESSION['DBCoy']}`.`status` WHERE cattype='plan' ORDER BY Category";
$TPlan = getDBData($dbh, $sql);

$sql = "SELECT CategoryID, Category FROM `{$_SESSION['DBCoy']}`.`status` WHERE cattype='hosp' ORDER BY Category";
$THosp = getDBData($dbh, $sql);

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
        <td width="240" valign="top"><img src="/images/orders.jpg" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblorders.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          </table>
          <form action="index.php" method="post" name="frmorder" id="frmorder">
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
                    <td class="titles">&nbsp;</td>
                    <td class="darkgrey"><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                          <td><b>
                            <input type="checkbox" name="strict" id="strict" />
                          </b></td>
                          <td><b>
 Strict</b></td>
                          </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles"> Name:</td>
                    <td><input type="text" name="name" size="32" id="name" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Gender:</td>
                    <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input name="sex" type="radio" value="0" size="32" checked="checked" /></td>
                        <td>Any</td>
                        <td><input type="radio" name="sex" value="1" size="32" /></td>
                        <td>Male</td>
                        <td><input type="radio" name="sex" value="2" size="32" /></td>
                        <td>Female</td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Marital Status </td>
                    <td><select name="marital_status" width="175" style="width: 168px;">
                      <option value="0" selected="selected">Any</option>
                      <option value="1">Single - never married</option>
                      <option value="2">Married</option>
                      <option value="3">Divorced </option>
                      <option value="4">Widowed </option>
                      <option value="5">Separated </option>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Religion:</td>
                    <td><select name="religion">
                      <option value="0" selected="selected">Any</option>
                      <option value="1">Christian</option>
                      <option value="2">Muslim</option>
                      <option value="3">Jewish</option>
                      <option value="4">Budhist</option>
                      <option value="5">Atheist</option>
                      <option value="6">Others</option>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Age:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                          <td class="black-normal">From</td>
                          <td><input name="dob1" type="text" id="dob1" value="" size="12" readonly="readonly" /></td>
                          <td>&nbsp;</td>
                          <td class="black-normal">To</td>
                          <td><input name="dob2" type="text" id="dob2" value="" size="12" readonly="readonly" /></td>
                          </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Nationality: </td>
                    <td><select name="nationality">
                      <option value="" selected="selected">Any</option>
                      <?php foreach ($Tcountry as $row_Tcountry) { ?>
                      <option value="<?php echo $row_Tcountry['country_id'] ?>"><?php echo $row_Tcountry['country'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">State Of Origin,<br />
                      Home Town,<br />
                      Native Tongue,<br />
                      Languages, <br />
                      Permanent Home Address :</td>
                    <td><textarea name="origin" style="width:300px" rows="3"></textarea></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    </tr>
                  <tr>
                    <td valign="top" class="titles">Department:</td>
                    <td align="left"><select name="DeptID">
                      <option value="" selected="selected">Any</option>
                      <?php foreach ($TDept as $row_TDept) { ?>
                      <option value="<?php echo $row_TDept['catID'] ?>"><?php echo $row_TDept['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td align="left"><select name="categoryid">
                      <option value="" selected="selected">Any</option>
                      <?php foreach ($TCat as $row_TCat) { ?>
                      <option value="<?php echo $row_TCat['catID'] ?>"><?php echo $row_TCat['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Job Title:</td>
                    <td align="left"><input type="text" name="ReferredBy" value="" size="32" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Project:</td>
                    <td align="left" nowrap="nowrap"><select name="Discount" id="Discount">
                      <option value="" selected="selected">Any</option>
                      <?php foreach ($TProj as $row_TProj) { ?>
                      <option value="<?php echo $row_TProj['CategoryID'] ?>"><?php echo $row_TProj['Category'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Location:</td>
                    <td align="left"><input name="FaxNumber" type="text" id="FaxNumber" value="" size="32" /></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    </tr>
                  <tr>
                    <td class="titles">Bank:</td>
                    <td align="left"><select name="currency" id="currency">
                      <option value="" selected="selected">Any</option>
                      <?php foreach ($TBank as $row_TBank) { ?>
                      <option value="<?php echo $row_TBank['CategoryID'] ?>"><?php echo $row_TBank['Category'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Account No.:</td>
                    <td align="left"><input name="logofile" type="text" id="logofile" value="" size="32" /></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    </tr>
                  <tr>
                    <td class="titles">Health Plan:</td>
                    <td align="left"><select name="salary" id="salary">
                      <option value="" selected="selected">Any</option>
                      <?php foreach ($TPlan as $row_TPlan) { ?>
                      <option value="<?php echo $row_TPlan['CategoryID'] ?>"><?php echo $row_TPlan['Category'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Hospital:</td>
                    <td align="left"><select name="parentcompany" id="parentcompany">
                      <option value="" selected="selected">Any</option>
                      <?php foreach ($THosp as $row_THosp) { ?>
                      <option value="<?php echo $row_THosp['CategoryID'] ?>"><?php echo $row_THosp['Category'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Insured:</td>
                    <td align="left"><input name="credit" type="checkbox" id="credit" /></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                    </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Supervisor:</td>
                    <td><select name="supervisor">
                      <option value="" selected="selected">Any</option>
                      <?php foreach ($TSup as $row_TSup) { ?>
                      <option value="<?php echo $row_TSup['OrderID'] ?>"><?php echo $row_TSup['VendorName'] ?></option>
                      <?php } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Terminated:</td>
                    <td><input type="checkbox" name="InUse" value="" /></td>
                  </tr>
                  <tr>
                    <td class="titles">On Leave:</td>
                    <td align="left"><input name="leavstatus" type="checkbox" id="leavstatus" value=""  /></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                  <tr>
                    <td class="titles">Address:</td>
                    <td align="left"><textarea name="City" style="width:300px"></textarea></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
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
            <input type="hidden" name="MM_insert" value="frmorder" />
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