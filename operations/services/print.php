<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Operations'));
$access = _xvar_arr_sub($_access, array('Services'));
vetAccess('Operations', 'Services', 'Print');

$id = _xget('id');

$sql = "SELECT `items`.*, `items_srv`.*, dept.catname AS dept, cat.catname AS cat, 
    pdtyp.Category AS pertype, tmtyp.Category AS tmtype
FROM `{$_SESSION['DBCoy']}`.items 
INNER JOIN `{$_SESSION['DBCoy']}`.items_srv             ON `items`.ItemID=items_srv.ServiceID
LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` dept ON `items_srv`.department = dept.catID  
LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` cat  ON `items`.Classification = cat.catID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` pdtyp         ON `items_srv`.event_length = pdtyp.CategoryID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` tmtyp         ON `items_srv`.timetype = tmtyp.CategoryID 
WHERE `ServiceID`=$id";
$row_TServices = getDBDataRow($dbh, $sql);

$T_AssCat = getClassify(4, "AND `catID` IN (0{$row_TServices['assetcat']})");

$sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
    WHERE `OutletID` IN (0{$row_TServices['outlets']})";
$T_Outlet = getDBData($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/xxpix.jpg" ?>" /></td>
        <td><span class="coytxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblservices.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
            <table border="0" cellpadding="4" cellspacing="4">
              <tr>
                <td class="titles">Service ID:</td>
                <td class="red-normal"><b><?php echo $row_TServices['ServiceID']; ?></b></td>
              </tr>
              <tr>
                <td class="titles">Service Code:</td>
                <td align="left"><?php echo $row_TServices['ProdCode'] ?></td>
              </tr>
              <tr>
                <td class="titles">Name:</td>
                <td><?php echo $row_TServices['ProdName'] ?></td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">Category:</td>
                <td align="left"><?php echo $row_TServices['cat']; ?></td>
              </tr>
              <tr>
                <td class="titles">Department:</td>
                <td align="left"><?php echo $row_TServices['dept']; ?></td>
              </tr>
              <tr>
                <td class="titles">In Use:</td>
                <td><input type="checkbox" name="InUse"<?php if ($row_TServices['InUse'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
              </tr>
              <tr>
                <td class="titles">Charge:</td>
                <td><?php echo $row_TServices['UnitPrice'] ?></td>
              </tr>
              <tr>
                <td class="titles">Default Tax:</td>
                <td><?php echo $row_TServices['itmtax'] ?></td>
              </tr>
              <tr>
                <td class="titles">Duration Type:</td>
                <td align="left"><?php echo $row_TServices['pertype']; ?>
                  </option></td>
              </tr>
              <tr>
                <td class="titles"></td>
                <td><table border="0" cellpadding="2" cellspacing="2" id="timeframe"<?php if ($row_TServices['event_length'] != 9) echo ' style="display:none"'; ?>>
                  <tr>
                    <td><?php echo $row_TServices['starttime']; ?></td>
                    <td>&nbsp;</td>
                    <td class="black-normal">to</td>
                    <td>&nbsp;</td>
                    <td><?php echo $row_TServices['endtime']; ?></td>
                  </tr>
                </table>
                  <table border="0" cellspacing="2" cellpadding="2" id="prdframe"<?php if ($row_TServices['event_length'] != 10) echo ' style="display:none"'; ?>>
                    <tr>
                      <td><?php echo $row_TServices['periods'] ?></td>
                      <td>&nbsp;</td>
                      <td><?php echo $row_TServices['tmtype']; ?></td>
                    </tr>
                  </table>
                  <table border="0" cellspacing="1" cellpadding="1" id="expire">
                    <tr>
                      <td class="titles">Expiry Alert:</td>
                      <td><?php echo $row_TServices['alertperiod'] ?></td>
                      <td id="exptyp">Days</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td width="120" class="titles">Expiry Alert:</td>
                <td><?php echo $row_TServices['alertperiod'] ?> Days</td>
              </tr>
              <tr>
                <td class="titles">Asset Categories:</td>
                <td><table border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td><input type="checkbox" name="useasset"<?php if ($row_TServices['useasset'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                    <td nowrap="nowrap">Attach Asset(s)</td>
                  </tr>
                  <tr>
                    <td colspan="2"><table border="0" cellspacing="2" cellpadding="2" id="assframe"<?php if ($row_TServices['useasset'] == 0) echo ' style="display:none"'; ?>>
                      <tr>
                        <td nowrap="nowrap" class="h1">Selected Categories</td>
                      </tr>
                      <tr>
                        <td valign="top"><select name="selasscats" size="10" id="selasscats">
                          <?php foreach ($T_AssCat as $row_T_AssCat) { ?>
                          <option value="<?php echo $row_T_AssCat['catID'] ?>"><?php echo $row_T_AssCat['catname'] ?></option>
                          <?php } ?>
                        </select>
                          <input type="hidden" name="assetcat" value="<?php echo $row_TServices['assetcat']; ?>" /></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">Outlets:</td>
                <td><table border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td nowrap="nowrap" class="h1">Selected Outlets</td>
                  </tr>
                  <tr>
                    <td valign="top"><select name="seloutlets" size="10" id="seloutlets">
                      <?php foreach ($T_Outlet as $row_T_Outlet) { ?>
                      <option value="<?php echo $row_T_Outlet['OutletID'] ?>"><?php echo $row_T_Outlet['OutletName'] ?></option>
                      <?php } ?>
                    </select>
                      <input type="hidden" name="outlets" id="outlets" value="<?php echo $row_TServices['outlets']; ?>" /></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">Description:</td>
                <td><textarea name="Description" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TServices['Description'] ?></textarea></td>
              </tr>
              <tr>
                <td class="titles">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="titles">Notes:</td>
                <td><textarea name="Notes" rows="10" readonly="readonly" style="width:450px"><?php echo $row_TServices['Notes'] ?></textarea></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="4" cellpadding="4">

          </table></td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
	print();
});
</script>
</body>
</html>
