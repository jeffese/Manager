<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Assets'));
$access = _xvar_arr_sub($_access, array('Vehicles'));
vetAccess('Assets', 'Vehicles', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmAsset","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Assets'.DS.'Vehicles';
$doc_id = $id;

if (_xpost("MM_update") == "frmAsset") {
  
	$pix = newpix(ROOT . ASSETPIX_DIR, '', $id, 20, array(600, 200));
	
	$sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`assets` SET `AssetCode`=%s,`AssetName`=%s,`parent`=%s,
            `Category`=%s,`AssetType`=%s,`department`=%s,`staff`=%s,`desgtype`=%s,`designation`=%s,
            `colour`=%s,`picturefile`=%s,`description`=%s,`Notes`=%s,`Status`=%s,`InUse`=%s,`NextSchedMaint`=%s,
            `Capacity`=%s,`Brand`=%s,`Model`=%s,`serialno`=%s,`modelno`=%s,
            `partno`=%s,`BarcodeNumber`=%s,`licenceno`=%s,`purchfrom`=%s,`PurchCost`=%s,`CurPurchCost`=%s,
            `AuctionValue`=%s,`DateSold`=%s,`insurers`=%s,`servcomp`=%s,`insuranceno`=%s,
            `DepreciationMethod`=%s,`DepreciableLife`=%s,`DepreciationValue`=%s,`DepreciationRate`=%s,
            `SalvageValue`=%s WHERE AssetID=%s",
                       GSQLStr(_xpost('AssetCode'), "text"),
                       GSQLStr(_xpost('AssetName'), "text"),
                       GSQLStr(_xpost('parent'), "intn"),
                       GSQLStr(_xpost('Category'), "intn"),
                       2,
                       GSQLStr(_xpost('department'), "intn"),
                   GSQLStr($_SESSION['EmployeeID'], "intn"),//staff
                   GSQLStr(_xpost('lictype'), "int"),//desgtype
                       GSQLStr(_xpost('designation'), "text"),
                       GSQLStr(_xpost('colour'), "int"),
                       $pix['pixcode'],
                       GSQLStr(_xpost('description'), "text"),
                       GSQLStr(_xpost('Notes'), "text"),
                       GSQLStr(_xpost('Status'), "intn"),
                       _xpostchk('InUse'),
                       GSQLStr(_xpost('NextSchedMaint'), "date"),
                       GSQLStr(_xpost('Capacity'), "double"),
                       GSQLStr(_xpost('brandid'), "text"),//Brand
                       GSQLStr(_xpost('Model'), "text"),//
                       GSQLStr(_xpost('serieid'), "text"),//serialno
                       GSQLStr(_xpost('modelno'), "text"),
                       GSQLStr(_xpost('partno'), "text"),
                   GSQLStr(_xpost('year_prod'), "text"),//BarcodeNumber
                       GSQLStr(_xpost('licenceno'), "text"),
                       GSQLStr(_xpost('purchfrom'), "intn"),
                       GSQLStr(_xpost('PurchCost'), "double"),
                       GSQLStr(_xpost('CurPurchCost'), "double"),
                       GSQLStr(_xpost('AuctionValue'), "double"),
                       GSQLStr(_xpost('DateSold'), "date"),
                       GSQLStr(_xpost('insurers'), "intn"),
                       GSQLStr(_xpost('servcomp'), "intn"),
                       GSQLStr(_xpost('insuranceno'), "text"),
                       GSQLStr(_xpost('DepreciationMethod'), "int"),
                   GSQLStr($_SESSION['OutletID'], "double"),//_xpost('DepreciableLife')
                   GSQLStr(_xpost('bstyle'), "double"),//DepreciationValue
                       GSQLStr(_xpost('DepreciationRate'), "double"),
                       GSQLStr(_xpost('vtype'), "double"),//SalvageValue
                       $id);
	$update = runDBQry($dbh, $sql);
	docs($doc_shelf, $doc_id);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`assets` WHERE `AssetID`=".$id;
$row_TAssets = getDBDataRow($dbh, $sql);

$TStatus = getCat('assetStatus');

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.colors ORDER BY colorname";
$colors = getDBData($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`licenses`";
$TLicense = getDBData($dbh, $sql);

$sql = "SELECT `CatID`, `category_name` FROM `{$_SESSION['DBCoy']}`.`auto_categories` WHERE `parent_id`='0' ORDER BY `category_name`";
$TAutoType = getDBData($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asset - Edit <?php echo $row_TAssets['AssetName'] ?></title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="../../custom/vehicles/auto_cats.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="../../custom/vehicles/autos.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="../../custom/vehicles/models.jgz" type="text/javascript"></script>
<script type="text/javascript"> 
var arrFormValidation=[
    ["lictype", "",
        ["req", "Select License Type"]
    ],
    ["vtype", "",
        ["req", "Select Vehicle Type"]
    ],
    ["licenceno", "",
        ["req", "Enter Registration No."]
    ]
];

window.onload = function() {
    assetType(2);
    licch();
    $('#vtype').val(<?php echo $row_TAssets['SalvageValue'] ?>);
    typech();
    $('#bstyle').val(<?php echo $row_TAssets['DepreciationValue'] ?>);
    $('#brandid').val(<?php echo $row_TAssets['Brand'] ?>);
    brandch();
    $('#serieid').val(<?php echo $row_TAssets['serialno'] ?>);
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmAsset" id="frmAsset">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><div id="Details" class="TabbedPanels">
                  <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0">Info</li>
                    <li class="TabbedPanelsTab" tabindex="0" id="particulars">Particulars</li>
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
                                  <td><input name="Category" type="radio" id="Category_1" value="29" size="32" <?php if (!(strcmp($row_TAssets['Category'], 29))) { echo "checked=\"checked\""; } ?> /></td>
                                  <td>Private</td>
                                  <td>&nbsp;</td>
                                  <td><input name="Category" type="radio" id="Category_2" value="30" size="32" <?php if (!(strcmp($row_TAssets['Category'], 30))) { echo "checked=\"checked\""; } ?> /></td>
                                  <td>Commercial</td>
                                  <td>&nbsp;</td>
                                  <td><input name="Category" type="radio" id="Category_3" value="31" size="32" <?php if (!(strcmp($row_TAssets['Category'], 31))) { echo "checked=\"checked\""; } ?> /></td>
                                  <td>Government</td>
                        </tr>
                              </table></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">License Type:</td>
                              <td align="left"><select name="lictype" id="lictype" onchange="licch()">
                                <option value="">..</option>
                                <?php foreach ($TLicense as $row_TLicense) { ?>
                                <option value="<?php echo $row_TLicense['lic_typ'] ?>" cats="<?php echo $row_TLicense['cats'] ?>" <?php if (!(strcmp($row_TLicense['lic_typ'], $row_TAssets['desgtype']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TLicense['license'] ?></option>
                                <?php } ?>
                                </select></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">Vehicle Type:</td>
                              <td align="left"><select name="vtype" id="vtype" onchange="typech()">
                                <option value="">..</option>
                          </select></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">Body Style:</td>
                              <td align="left"><select name="bstyle" id="bstyle">
                                <option value="">..</option>
                          </select></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">Brand:</td>
                              <td align="left"><select name="brandid" id="brandid" onchange="brandch(this.form.serieid.value)">
                                <option value="">..</option>
                          </select></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">Model:</td>
                              <td align="left"><select name="serieid" id="serieid">
                                <option value="">..</option>
                              </select>
                                <input type="text" name="Model" size="30" value="<?php echo $row_TAssets['Model'] ?>" /></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">Year:</td>
                              <td align="left"><select name="year_prod" id="year_prod">
                                <option value="">..</option>
                              </select>
                                <script type="text/javascript">
					var yr;
					Today = new Date();
					yr = Today.getFullYear() + 2;
					i=1;
					while (yr>=1900) {
						document.getElementById("year_prod").options[i] = new Option(yr, yr, yr == <?php echo intval($row_TAssets['BarcodeNumber']) ?>, yr == <?php echo intval($row_TAssets['BarcodeNumber']) ?>);
						yr--;
						i++;
					}
                                </script></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">&nbsp;</td>
                              <td align="left"></td>
                        </tr>
                        <tr>
                              <td width="120" nowrap="nowrap" class="titles">Passengers:</td>
                              <td align="left"><input name="Children" type="text" value="<?php echo $row_TAssets['Children'] ?>" size="10" /></td>
                        </tr>
                        <tr>
                              <td class="titles">Weight (kg):</td>
                              <td align="left"><input name="CurPurchCost" type="text" value="<?php echo number_format($row_TAssets['CurPurchCost']) ?>" size="10" /></td>
                        </tr>
                        <tr>
                              <td class="titles">Load (kg):</td>
                              <td align="left"><input name="AuctionValue" type="text" value="<?php echo number_format($row_TAssets['AuctionValue']) ?>" size="10" /></td>
                        </tr>
                        <tr>
                              <td class="titles">Engine Capacity:</td>
                              <td align="left"><input type="text" name="Capacity" value="<?php echo $row_TAssets['Capacity'] ?>" size="10" /></td>
                        </tr>
                        <tr>
                              <td class="titles">Color:</td>
                              <td align="left"><select name="colour">
                                <option value="">Select Color</option>
                                <?php foreach ($colors as $row_colors) { ?>
                                <option value="<?php echo $row_colors['colorid'] ?>" style="background-color: <?php echo $row_colors['colorcode'] ?>;color: Black;" <?php if (!(strcmp($row_colors['colorid'], $row_TAssets['colour']))) {echo "selected=\"selected\"";} ?>><?php echo $row_colors['colorname'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">Description:</td>
                              <td align="left"><textarea name="description" rows="3" style="width:300px"><?php echo $row_TAssets['description'] ?></textarea></td>
                        </tr>
                      </table>
                    </div>
<div class="TabbedPanelsContent">
                          <table border="0" cellpadding="4" cellspacing="4">
                        <tr>
                              <td nowrap="nowrap" class="titles">Registration No.:</td>
                              <td><input type="text" name="licenceno" value="<?php echo $row_TAssets['licenceno'] ?>" size="32" /></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">Chasis No.:</td>
                              <td><input type="text" name="modelno" value="<?php echo $row_TAssets['modelno'] ?>" size="32" /></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">Engine No.:</td>
                              <td><input type="text" name="partno" value="<?php echo $row_TAssets['partno'] ?>" size="32" /></td>
                        </tr>
                        <tr>
                              <td nowrap="nowrap" class="titles">Insurance No.:</td>
                              <td><input type="text" name="insuranceno" value="<?php echo $row_TAssets['insuranceno'] ?>" size="32" /></td>
                        </tr>
                        </table>
                  </div>
<div class="TabbedPanelsContent">
  <textarea name="Notes" style="width:450px" rows="10"><?php echo $row_TAssets['Notes'] ?></textarea>
</div>
<div class="TabbedPanelsContent"><?php include '../../scripts/editdoc.php' ?></div>
                  </div>
                  </div></td>
</tr>
              <tr>
                <td><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
                  <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
                  <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
                  <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
                  <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
                      <input type="hidden" name="MM_update" value="frmAsset" />
                      <input type="hidden" name="AssetID" value="<?php echo $row_TAssets['AssetID']; ?>" /></td>
              </tr>
            </table></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
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
<script type="text/javascript">
var Tabs = new Spry.Widget.TabbedPanels("Details");
</script>
</body>
</html>