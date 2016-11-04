<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Clients'));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess('Clients', $vkey, 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print ~ 12 List
$buttons_links = array("","","","","","","","","","","frmclient.submit()","","index.php");
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
        <td width="240" valign="top"><img src="/images/<?php echo $vpth?>.jpg" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lbl<?php echo $vpth?>.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          </table>
          <form action="index.php" method="post" name="frmclient" id="frmclient">
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
                    <td class="titles">Type:</td>
                    <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input type="radio" name="ClientType" id="ClientType_1" value="1" size="32" /></td>
                        <td><?php echo $vtype == 4 ? 'Company Account' : 'Individual' ?></td>
                        <td><input name="ClientType" type="radio" id="ClientType_2" value="2" size="32" /></td>
                        <td><?php echo $vtype == 4 ? 'Bank' : 'Company' ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Gender:</td>
                    <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input type="radio" name="sex" value="1" size="32" /></td>
                        <td>Male</td>
                        <td><input type="radio" name="sex" value="2" size="32" /></td>
                        <td>Female</td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Group Account:</td>
                    <td><select name="parentcompany">
                      <option value="" selected="selected"></option>
                      <?php foreach ($TClients as $row_TClients) { ?>
                      <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                      <?php } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
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
                    <td class="titles">State Of Origin,<br />Home Town,<br />Permanent Home Address :</td>
                    <td><textarea name="origin" style="width:300px" rows="3"></textarea></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
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
                    <td nowrap="nowrap" class="titles">Currency:</td>
                    <td align="left"><select name="currency">
                      <option value=""></option>
                      <?php for ($i=0; $i<count($Tcurrency); $i++) { ?>
                      <option value="<?php echo $Tcurrency[$i]['cur_id']?>"><?php echo $Tcurrency[$i]['currencyname']?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Cheque:</td>
                    <td align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                          <td><input name="cheque" type="radio" value="-1" size="32" checked="checked" /></td>
                          <td>Any</td>
                          <td><input type="radio" name="cheque" value="0" size="32" /></td>
                          <td>No</td>
                          <td><input type="radio" name="cheque" value="1" size="32" /></td>
                          <td>Yes</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Credit:</td>
                    <td align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                          <td><input name="credit" type="radio" value="-1" size="32" checked="checked" /></td>
                          <td>Any</td>
                          <td><input type="radio" name="credit" value="0" size="32" /></td>
                          <td>No</td>
                          <td><input type="radio" name="credit" value="1" size="32" /></td>
                          <td>Yes</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Credit Limit:</td>
                    <td align="left"><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td class="black-normal">From</td>
                        <td><input name="creditlimit1" type="text" value="" size="12" readonly="readonly" /></td>
                        <td>&nbsp;</td>
                        <td class="black-normal">To</td>
                        <td><input name="creditlimit2" type="text" value="" size="12" readonly="readonly" /></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Discount:</td>
                    <td align="left"><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td class="black-normal">From</td>
                        <td><input name="Discount1" type="text" value="" size="12" readonly="readonly" /></td>
                        <td>&nbsp;</td>
                        <td class="black-normal">To</td>
                        <td><input name="Discount2" type="text" value="" size="12" readonly="readonly" /></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Active:</td>
                    <td><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                          <td><input name="InUse" type="radio" value="-1" size="32" checked="checked" /></td>
                          <td>Any</td>
                          <td><input type="radio" name="InUse" value="0" size="32" /></td>
                          <td>No</td>
                          <td><input type="radio" name="InUse" value="1" size="32" /></td>
                          <td>Yes</td>
                        </tr>
                    </table></td>
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
            <input type="hidden" name="MM_insert" value="frmclient" />
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