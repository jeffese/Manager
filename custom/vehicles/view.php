<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Assets'));
$access = _xvar_arr_sub($_access, array('Vehicles'));
vetAccess('Assets', 'Vehicles', 'View');

$id = intval(_xget('id'));
if (!in_array($id, $_SESSION['new_veh'])) {
    if (!isset($_SESSION['new_veh']))
        $_SESSION['new_veh'] = $id;
    else
        array_push($_SESSION['new_veh'], $id);
}
$flow = _xses('flow');

//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","edit.php?id=$id","","del.php?id=$id",$flow == 3 ? "index.php" : "","","","","index.php");
$rec_status = 1;

$sql = "SELECT `assets`.*, TIMESTAMPDIFF(DAY,CURDATE(),`assets`.`dateofpurch`) AS canedit, colorname, `AssStatus`.Category AS assStat, `license`, `category_name` AS `vtype`
            FROM `{$_SESSION['DBCoy']}`.`assets` 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`status` `AssStatus` ON `assets`.Status=`AssStatus`.CategoryID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`colors`             ON `assets`.colour=`colors`.colorid
            LEFT JOIN `{$_SESSION['DBCoy']}`.`licenses`           ON `assets`.`desgtype`=`licenses`.`lic_typ`
            LEFT JOIN `{$_SESSION['DBCoy']}`.`auto_categories`    ON `assets`.`SalvageValue`=`auto_categories`.`CatID`
            WHERE `assets`.`AssetID`=$id";
$row_TAssets = getDBDataRow($dbh, $sql);

$canEdit = $flow != '3' && 
        $_SESSION['EmployeeID'] == $row_TAssets['staff'] && 
        $row_TAssets['canedit'] < 2 ? 1 : 0;
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'] * $canEdit, $access['Del'] * $canEdit, 0, 0, 0, 0, 0, 0);

if (_xses('flow') == '3') {
    if ($row_TAssets['occupant'] > 0) {
        $_SESSION['custid'] = $row_TAssets['occupant'];
        $xtra_buts_0 = '<td align="center"><a href="../sales/add.php?id='.$id.'"><img src="/custom/images/nav_inv.png"  title="Invoice" alt="Invoice" border="0" /></a></td>';
        $xtra_buts_1 = '<td align="center"><a href="../sales/add.php?id='.$id.'" class="titles">Invoice</a></td>';
        $xtra_buts_0 .= '<td align="center"><a href="../owners/view.php?id='. $row_TAssets['occupant'] .'"><img src="../../images/nav_but/close.png"  title="Owner" alt="Invoice" border="0" /></a></td>';
        $xtra_buts_1 .= '<td align="center"><a href="../owners/view.php?id='. $row_TAssets['occupant'] .'" class="titles">Owner</a></td>';
    }
} else {
    $xtra_buts_0 = '<td align="center"><a href="../vehicles/add.php" ><img src="/custom/images/nav_veh.png"  title="Add Vehicle" alt="Add Vehicle" border="0" /></a></td>';
    $xtra_buts_1 = '<td align="center"><a href="../vehicles/add.php" class="titles">Add Vehicle</a></td>';
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asset - <?php echo $row_TAssets['AssetName'] ?> Details</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="auto_cats.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="autos.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="models.jgz" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/set.js" type="text/javascript"></script>
<script type="text/javascript"> 
<!--
window.onload = function() {
    setContent();
    assetType(2);
}
window.onresize = function() {
    setContent();
}
//--> 
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<div id="content">
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="frametopleft">&nbsp;</td>
      <td class="frametop">&nbsp;</td>
      <td class="frametopright">&nbsp;</td>
    </tr>
    <tr>
      <td class="frameleft">&nbsp;</td>
      <td bgcolor="#FFFFFF">
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><img src="/images/vehicles.jpg" alt="" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblvehicles.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><div id="Details" class="TabbedPanels">
                  <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0">Info</li>
                    <li class="TabbedPanelsTab" tabindex="0" id="particulars">Particulars</li>
  <li class="TabbedPanelsTab" tabindex="0">History</li>
  <li class="TabbedPanelsTab" tabindex="0">Notes</li>
                    <li class="TabbedPanelsTab" tabindex="0">Documents</li>
                    </ul>
                  <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent">
                      <table border="0" cellpadding="4" cellspacing="4">
                        <tr>
                          <td></td>
                          <td align="center"><?php echo catch_error($errors) ?></td>
                        </tr>
                        <tr>
                          <td class="titles">ID:</td>
                          <td align="left" class="red-normal"><b><?php echo $row_TAssets['AssetID']; ?></b></td>
                        </tr>
                        <tr>
                          <td class="titles">Category:</td>
                          <td align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                            <tr>
                              <td><input name="Category" type="radio" id="Category_1" value="29" size="32" <?php if (!(strcmp($row_TAssets['Category'], 29))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                              <td>Private</td>
                              <td>&nbsp;</td>
                              <td><input name="Category" type="radio" id="Category_2" value="30" size="32" <?php if (!(strcmp($row_TAssets['Category'], 30))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                              <td>Commercial</td>
                                  <td>&nbsp;</td>
                                  <td><input name="Category" type="radio" id="Category_3" value="31" size="32" <?php if (!(strcmp($row_TAssets['Category'], 31))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                                  <td>Government</td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">License Type:</td>
                          <td align="left"><?php echo $row_TAssets['license'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Vehicle Type:</td>
                          <td align="left"><?php echo $row_TAssets['vtype'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Body Style:</td>
                          <td align="left"><script>document.write(get_bstyle(<?php echo $row_TAssets['SalvageValue'] - 1 ?>, <?php echo intval($row_TAssets['DepreciationValue']) ?>))</script></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Brand:</td>
                          <td align="left"><script>document.write(get_brand(<?php echo $row_TAssets['SalvageValue'] - 1 ?>, <?php echo intval($row_TAssets['Brand']) ?>))</script></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Model:</td>
                          <td align="left"><script>document.write(get_model(<?php echo $row_TAssets['SalvageValue'] - 1 ?>, <?php echo intval($row_TAssets['serialno']) ?>))</script><?php echo $row_TAssets['Model'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Year:</td>
                          <td align="left"><?php echo intval($row_TAssets['BarcodeNumber']) ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="120" nowrap="nowrap" class="titles">Passengers:</td>
                          <td align="left"><?php echo $row_TAssets['Children'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Weight (kg):</td>
                          <td align="left"><?php echo number_format($row_TAssets['CurPurchCost']) ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Load (kg):</td>
                          <td align="left"><?php echo number_format($row_TAssets['AuctionValue']) ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Engine Capacity:</td>
                          <td align="left"><?php echo $row_TAssets['Capacity'] ?></td>
                        </tr>
                        <tr>
                          <td class="titles">Color:</td>
                          <td align="left"><?php echo $row_TAssets['colorname'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Description:</td>
                          <td align="left"><textarea name="description" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TAssets['description'] ?></textarea></td>
                        </tr>
                        </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellpadding="4" cellspacing="4">
                        <tr>
                          <td nowrap="nowrap" class="titles">Registration No.:</td>
                          <td align="left"><?php echo $row_TAssets['licenceno'] ?></td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Chasis No.:</td>
                          <td align="left"><?php echo $row_TAssets['modelno'] ?></td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Engine No.:</td>
                          <td align="left"><?php echo $row_TAssets['partno'] ?></td>
                          </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Insurance No.:</td>
                          <td align="left"><?php echo $row_TAssets['insuranceno'] ?></td>
                          </tr>
                        </table>
                      </div>
  <div class="TabbedPanelsContent">
    <iframe width="700" height="400" src="/operations/servsched/assets.php?id=<?php echo $row_TAssets['AssetID']; ?>"></iframe>
    </div>
  <div class="TabbedPanelsContent">
    <textarea name="Notes" rows="10" readonly="readonly" style="width:450px"><?php echo $row_TAssets['Notes'] ?></textarea>
    </div>
                    <div class="TabbedPanelsContent"><?php $doc_shelf = 'Assets'.DS.'Vehicles';
							$doc_id = $id; ?><?php include "../../scripts/viewdoc.php" ?></div>
                    </div>
                  </div></td>
</tr>
              </table></td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">

          </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript">
var Tabs = new Spry.Widget.TabbedPanels("Details");
</script></td>
      <td class="frameright">&nbsp;</td>
    </tr>
    <tr>
      <td class="framebotleft">&nbsp;</td>
      <td align="center" valign="bottom" class="framebot"><span class="greytxt">Copyright © 2010 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>
</html>
