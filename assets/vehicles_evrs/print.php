<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Assets'));
$access = _xvar_arr_sub($_access, array('Vehicles'));
vetAccess('Assets', 'Vehicles', 'Print');

$id = intval(_xget('id'));
$vendor_deal = vendorFlds("dealers", "dealer");
$vendor_ins = vendorFlds("insurers", "insurer");
$vendor_srv = vendorFlds("servers", "service");
$vendor_stf = vendorFlds("staff", "officer");
$vendor_ocp = vendorFlds("occupys", "");
$sql = "SELECT `assets`.*, `parAsset`.AssetName AS `par`, `cats`.catname AS cat,
          colorname, `AssStatus`.Category AS assStat, `classifications`.catname, `vendortypes`.VendorType, 
          IF(`assets`.desgtype=0,`occuAsset`.AssetName,$vendor_ocp) AS occupy,
          $vendor_deal, $vendor_ins, $vendor_srv, $vendor_stf
            FROM `{$_SESSION['DBCoy']}`.`assets` 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`assets` `parAsset`  ON `assets`.`parent`=`parAsset`.`AssetID`
            LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `cats` ON `assets`.Category=`cats`.catID 
            LEFT JOIN `{$_SESSION['DBCoy']}`.`status` `AssStatus` ON `assets`.Status=`AssStatus`.CategoryID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`colors`             ON `assets`.colour=`colors`.colorid
            LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications`    ON `assets`.department=classifications.catID
            LEFT JOIN `" . DB_NAME . "`.`vendortypes`             ON `assets`.desgtype=`vendortypes`.VendorID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `dealers`  ON `assets`.purchfrom=dealers.VendorID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `insurers` ON `assets`.insurers=insurers.VendorID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `servers`  ON `assets`.servcomp=servers.VendorID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `staff`    ON `assets`.staff=staff.VendorID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `occupys`  ON `assets`.occupant=occupys.VendorID
            LEFT JOIN `{$_SESSION['DBCoy']}`.`assets` `occuAsset` ON `assets`.`occupant`=`occuAsset`.`AssetID`
            WHERE `assets`.`AssetID`=$id";
$row_TAssets = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="2" cellpadding="2">
      <tr>
          <td><img src="<?php echo COYPIX_DIR, $_SESSION['../tmpl/coyid']."/xxpix.jpg" ?>" /></td>
          <td><span class="coytxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:460px; background-image:url(/images/lblvehicles.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">Info</td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td><?php $pixcnt = 20; $max = 20000000; ?>
                      <?php 
$pictfld = $row_TAssets['picturefile'];
$fpath = $id;
$pixdir = ASSETPIX_DIR;
$pixi = 'x';
$xid = '';
$label = '';
?>
                      <?php include('../../scripts/viewpix.php'); ?></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td width="120" class="titles">ID:</td>
                        <td class="red-normal"><b><?php echo $row_TAssets['AssetID']; ?></b></td>
                      </tr>
                      <tr>
                        <td width="120" class="titles">Code:</td>
                        <td align="left"><?php echo $row_TAssets['AssetCode'] ?></td>
                      </tr>
                      <tr>
                        <td width="120" nowrap="nowrap" class="titles">Name:</td>
                        <td align="left"><?php echo $row_TAssets['AssetName'] ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td valign="top" class="h1">Info</td>
                  </tr>
                  <tr>
                    <td><table border="0" cellpadding="4" cellspacing="4">
                      <tr>
                        <td width="120" class="titles">Active:</td>
                        <td align="left"><input type="checkbox" name="InUse" value="1" <?php if (!(strcmp($row_TAssets['InUse'], 1))) { echo "checked=\"checked\""; } ?> disabled="disabled" /></td>
                      </tr>
                      <tr>
                        <td class="titles">Parent Asset:</td>
                        <td><?php echo $row_TAssets['par'] ?></td>
                      </tr>
                      <tr>
                        <td class="titles">Category:</td>
                        <td align="left"><?php echo $row_TAssets['cat'] ?></td>
                      </tr>
                      <tr>
                        <td class="titles">Color:</td>
                        <td align="left"><?php echo $row_TAssets['colorname'] ?></td>
                      </tr>
                      <tr>
                        <td class="titles">Status:</td>
                        <td align="left"><?php echo $row_TAssets['assStat'] ?></td>
                      </tr>
                    </table>
                      <table border="0" cellpadding="4" cellspacing="4" id="eqp">
                        <tr>
                          <td width="120" nowrap="nowrap" class="titles">Brand:</td>
                          <td><?php echo $row_TAssets['Brand'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Model:</td>
                          <td><?php echo $row_TAssets['Model'] ?></td>
                        </tr>
                      </table>
                      <table border="0" cellpadding="4" cellspacing="4">
                        <tr>
                          <td width="120" nowrap="nowrap" class="titles">Capacity:</td>
                          <td><?php echo $row_TAssets['Capacity'] ?></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Description:</td>
                          <td><?php echo $row_TAssets['description'] ?></td>
                        </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4" id="particulars">
                    <tr>
                      <td colspan="2" nowrap="nowrap" class="h1">Particulars</td>
                    </tr>
                    <tr>
                      <td width="120" nowrap="nowrap" class="titles">Serial No.:</td>
                      <td><?php echo $row_TAssets['serialno'] ?></td>
                    </tr>
                    <tr>
                      <td nowrap="nowrap" class="titles">Model No.:</td>
                      <td><?php echo $row_TAssets['modelno'] ?></td>
                    </tr>
                    <tr>
                      <td nowrap="nowrap" class="titles">Part No.:</td>
                      <td><?php echo $row_TAssets['partno'] ?></td>
                    </tr>
                    <tr>
                      <td nowrap="nowrap" class="titles">Barcode:</td>
                      <td><?php echo $row_TAssets['BarcodeNumber'] ?></td>
                    </tr>
                    <tr>
                      <td nowrap="nowrap" class="titles">Insurance No.:</td>
                      <td><?php echo $row_TAssets['insuranceno'] ?></td>
                    </tr>
                    <tr>
                      <td nowrap="nowrap" class="titles">License No.:</td>
                      <td><?php echo $row_TAssets['licenceno'] ?></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Firms</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Dealer:</td>
                    <td><?php echo $row_TAssets['dealer'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Insurer:</td>
                    <td><?php echo $row_TAssets['insurer'] ?></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Maintenance Company:</td>
                    <td nowrap="nowrap"><?php echo $row_TAssets['service'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Next Maintenance:</td>
                    <td><?php echo $row_TAssets['NextSchedMaint'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Date of Purchase:</td>
                    <td><?php echo $row_TAssets['dateofpurch'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Date Sold:</td>
                    <td><?php echo $row_TAssets['DateSold'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Designation</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Designee Type:</td>
                    <td align="left"><?php echo $row_TAssets['VendorType'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Designee:</td>
                    <td align="left"><?php echo $row_TAssets['occupy'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Designation:</td>
                    <td align="left"><?php echo $row_TAssets['designation'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Department:</td>
                    <td align="left"><?php echo $row_TAssets['catname'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Management Staff:</td>
                    <td align="left"><?php echo $row_TAssets['officer'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td colspan="2" valign="top" class="h1">Value</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Cost of Purchase:</td>
                    <td align="left"><?php echo $row_TAssets['PurchCost'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Current Market Cost:</td>
                    <td align="left"><?php echo $row_TAssets['CurPurchCost'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">Auction Value:</td>
                    <td align="left"><?php echo $row_TAssets['AuctionValue'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Depreciation Method:</td>
                    <td align="left"><script language="javascript" type="text/javascript">
switch (<?php echo $row_TAssets['DepreciationMethod']; ?>) {
case 1: document.write("..."); break;
case 2: document.write(",,,"); break;
default: document.write("");
}</script></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">Depreciation Life:</td>
                    <td align="left"><?php echo $row_TAssets['DepreciableLife'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">Depreciation Rate:</td>
                    <td align="left"><?php echo $row_TAssets['DepreciationRate'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">Depreciation Value:</td>
                    <td align="left"><?php echo $row_TAssets['DepreciationValue'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" nowrap="nowrap" class="titles">Salvage Value:</td>
                    <td align="left"><?php echo $row_TAssets['SalvageValue'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="2" cellspacing="2">
                  <tr>
                    <td class="h1">Notes</td>
                  </tr>
                  <tr>
                    <td><?php echo $row_TAssets['Notes']; ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td></td>
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
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    assetType(2);
	print();
});
</script>
</body>
</html>