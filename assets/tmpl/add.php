<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Assets'));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess('Assets', $vkey, 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmclient","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmclient") {
  
	$sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`assets` (`AssetCode`, `AssetName`, `parent`, 
            `Category`, `AssetType`, `department`, `staff`, `desgtype`, `occupant`, `designation`, `colour`, 
            `picturefile`, `description`, `Notes`, `Status`, `InUse`, `NextSchedMaint`, `Capacity`, 
            `Children`, `Brand`, `Model`, `serialno`, `modelno`, `partno`, `BarcodeNumber`, `licenceno`, 
            `purchfrom`, `PurchCost`, `CurPurchCost`, `AuctionValue`, `dateofpurch`, `DateSold`, 
            `insurers`, `servcomp`, `insuranceno`, `DepreciationMethod`, `DepreciableLife`, 
            `DepreciationValue`, `DepreciationRate`, `SalvageValue`) 
            VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,
            %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                       GSQLStr(_xpost('AssetCode'), "text"),
                       GSQLStr(_xpost('AssetName'), "text"),
                       GSQLStr(_xpost('parent'), "intn"),
                       GSQLStr(_xpost('Category'), "intn"),
                       $vtype,
                       GSQLStr(_xpost('department'), "intn"),
                       GSQLStr(_xpost('staff'), "intn"),
                       GSQLStr(_xpost('desgtype'), "int"),
                       GSQLStr(_xpost('occupant'), "int"),
                       GSQLStr(_xpost('designation'), "text"),
                       GSQLStr(_xpost('colour'), "int"),
                       0,
                       GSQLStr(_xpost('description'), "text"),
                       GSQLStr(_xpost('Notes'), "text"),
                       GSQLStr(_xpost('Status'), "intn"),
                       _xpostchk('InUse'),
                       GSQLStr(_xpost('NextSchedMaint'), "date"),
                       GSQLStr(_xpost('Capacity'), "int"),
                       GSQLStr(_xpost('Children'), "int"),
                       GSQLStr(_xpost('Brand'), "text"),
                       GSQLStr(_xpost('Model'), "text"),
                       GSQLStr(_xpost('serialno'), "text"),
                       GSQLStr(_xpost('modelno'), "text"),
                       GSQLStr(_xpost('partno'), "text"),
                       GSQLStr(_xpost('BarcodeNumber'), "text"),
                       GSQLStr(_xpost('licenceno'), "text"),
                       GSQLStr(_xpost('purchfrom'), "intn"),
                       GSQLStr(_xpost('PurchCost'), "double"),
                       GSQLStr(_xpost('CurPurchCost'), "double"),
                       GSQLStr(_xpost('AuctionValue'), "double"),
                       GSQLStr(_xpost('dateofpurch'), "date"),
                       GSQLStr(_xpost('DateSold'), "date"),
                       GSQLStr(_xpost('insurers'), "intn"),
                       GSQLStr(_xpost('servcomp'), "intn"),
                       GSQLStr(_xpost('insuranceno'), "text"),
                       GSQLStr(_xpost('DepreciationMethod'), "int"),
                       GSQLStr(_xpost('DepreciableLife'), "double"),
                       GSQLStr(_xpost('DepreciationValue'), "double"),
                       GSQLStr(_xpost('DepreciationRate'), "double"),
                       GSQLStr(_xpost('SalvageValue'), "double"));
	$insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
        $recid = mysqli_insert_id($dbh);
		docs('Assets'.DS.$vkey, $recid);
        $pix = newpix(ROOT . ASSETPIX_DIR, '', $recid, 20, array(600, 200));

        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`assets` SET picturefile='{$pix['pixcode']}' 
                WHERE `AssetID`=$recid";
        runDBQry($dbh, $sql);

        $id = $recid;
        header("Location: view.php?id=$recid");
        exit;
    }
}

$sql = "SELECT AssetID, AssetName FROM `{$_SESSION['DBCoy']}`.`assets` ORDER BY `AssetName`";
$TAssets = getDBData($dbh, $sql);

$TCat = getClassify(4);

$TStatus = getCat('assetStatus');

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.colors ORDER BY colorname";
$colors = getDBData($dbh, $sql);

$sch = $_SESSION['accesskeys']['Academics']['View'] == -1 ? ",6,7" : "";
$sql = "SELECT VendorID, VendorType, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` WHERE VendorType NOT IN (0,4$sch) ORDER BY `VendorName`";
$TClients = getDBData($dbh, $sql);

$sql = "SELECT `VendorID`, `VendorType` FROM `" . DB_NAME . "`.`vendortypes`
WHERE `VendorID` NOT IN (0,4$sch)
ORDER BY `VendorType`";
$TVendorTypes = getDBData($dbh, $sql);

$TDept = getClassify(1);
$TSup = getVendor(5);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New Asset</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<link href="/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script src="/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/assets/tmpl/script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
var arrFormValidation=[
    ["AssetCode", "",
        ["req", "Enter Asset Code"]
    ],
    ["AssetName", "",
        ["req", "Enter Asset Name"]
    ]
];

window.onload = function() {
    var mCal = new dhtmlxCalendarObject('dateofpurch', true, {isYearEditable: true, isMonthEditable: true});
    mCal.setSkin('dhx_black');
    var mCal2 = new dhtmlxCalendarObject('DateSold', true, {isYearEditable: true, isMonthEditable: true});
    mCal2.setSkin('dhx_black');
    var mCal3 = new dhtmlxCalendarObject('NextSchedMaint', true, {isYearEditable: true, isMonthEditable: true});
    mCal3.setSkin('dhx_black');
    assetType(<?php echo $vtype ?>);
    designate(0);
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
        <td width="240" valign="top"><span class="TabbedPanelsTabGroup"><span class="titles"><img src="/images/<?php echo $vpth?>.jpg" width="240" height="300" /></span></span></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lbl<?php echo $vpth?>.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmclient" id="frmclient">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><?php $pixcnt = 20; $max = 20000000; ?>
                  <?php include('../../scripts/newpix.php'); ?></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Code:</td>
                    <td align="left"><input name="AssetCode" type="text" size="32" /></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Name:</td>
                    <td align="left"><input name="AssetName" type="text" size="32" /></td>
                  </tr>
                  </table></td>
              </tr>
              <tr>
                <td><div id="Details" class="TabbedPanels">
                  <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0">Info</li>
                    <li class="TabbedPanelsTab" tabindex="0" id="particulars">Particulars</li>
                    <li class="TabbedPanelsTab" tabindex="0">Firms</li>
                    <li class="TabbedPanelsTab" tabindex="0">Designation</li>
                    <li class="TabbedPanelsTab" tabindex="0">Value</li>
                    <li class="TabbedPanelsTab" tabindex="0">Notes</li>
                    <li class="TabbedPanelsTab" tabindex="0">Documents</li>
                  </ul>
                  <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent"><table border="0" cellpadding="4" cellspacing="4">
                      <tr>
                        <td width="120" class="titles">Active:</td>
                        <td align="left"><input type="checkbox" name="InUse" value="1" /></td>
                      </tr>
                      <tr>
                        <td class="titles">Parent Asset:</td>
                        <td><select name="parent">
                          <option value=""></option>
                          <?php foreach ($TAssets as $row_TAssets) { ?>
                          <option value="<?php echo $row_TAssets['AssetID'] ?>"><?php echo $row_TAssets['AssetName'] ?></option>
                          <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td class="titles">Category:</td>
                        <td align="left"><select name="Category">
                          <option value=""></option>
                          <?php foreach ($TCat as $row_TCat) { ?>
                          <option value="<?php echo $row_TCat['catID'] ?>"><?php echo $row_TCat['catname'] ?></option>
                          <?php } ?>
                          </select>
                          <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/assets/cat/index.php', 480,520)" /></td>
                      </tr>
                      <tr>
                        <td class="titles">Color:</td>
                        <td align="left"><select name="colour">
                          <option value="">Select Color</option>
                          <?php foreach ($colors as $row_colors) { ?>
                          <option value="<?php echo $row_colors['colorid'] ?>" style="background-color: <?php echo $row_colors['colorcode'] ?>;color: Black;"><?php echo $row_colors['colorname'] ?></option>
                          <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td class="titles">Status:</td>
                        <td align="left"><select name="Status">
                          <option value=""></option>
                          <?php foreach ($TStatus as $row_TStatus) { ?>
                          <option value="<?php echo $row_TStatus['CategoryID'] ?>"><?php echo $row_TStatus['Category'] ?></option>
                          <?php } ?>
                        </select>
                          <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/assets/status/index.php', 480,520)" /></td>
                      </tr>
                      </table>
                      <table border="0" cellpadding="4" cellspacing="4" id="eqp">
                        <tr>
                          <td width="120" nowrap="nowrap" class="titles">Brand:</td>
                          <td><input type="text" name="Brand" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Model:</td>
                          <td><input type="text" name="Model" size="32" /></td>
                        </tr>
                      </table>
                      <table border="0" cellpadding="4" cellspacing="4">
                        <tr>
                          <td width="120" nowrap="nowrap" class="titles">Capacity:</td>
                          <td><input type="text" name="Capacity" size="10" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Description:</td>
                          <td><textarea name="description" rows="3" style="width:300px"></textarea></td>
                        </tr>
                    </table>
                      </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" nowrap="nowrap" class="titles">Serial No.:</td>
                          <td><input type="text" name="serialno" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Model No.:</td>
                          <td><input type="text" name="modelno" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Part No.:</td>
                          <td><input type="text" name="partno" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Barcode:</td>
                          <td><input type="text" name="BarcodeNumber" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Insurance No.:</td>
                          <td><input type="text" name="insuranceno" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">License No.:</td>
                          <td><input type="text" name="licenceno" size="32" /></td>
                        </tr>
                      </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" class="titles">Dealer:</td>
                          <td width="322"><select name="purchfrom">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if (in_array($row_TClients['VendorType'], array(2,3))) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td class="titles">Insurer:</td>
                          <td><select name="insurers">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if (in_array($row_TClients['VendorType'], array(2,3))) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Maintenance Company:</td>
                          <td nowrap="nowrap"><select name="servcomp">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if (in_array($row_TClients['VendorType'], array(2,3))) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="titles">Next Maintenance:</td>
                          <td><input name="NextSchedMaint" type="text" id="NextSchedMaint" size="12" readonly="readonly" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Date of Purchase:</td>
                          <td><input name="dateofpurch" type="text" id="dateofpurch" size="12" readonly="readonly" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Date Sold:</td>
                          <td><input name="DateSold" type="text" id="DateSold" size="12" readonly="readonly" /></td>
                        </tr>
                        </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" class="titles">Designee Type:</td>
                          <td width="322" align="left"><select name="desgtype" id="desgtype" onchange="designate(this.value)">
                            <option value="0">Asset</option>
                            <?php foreach ($TVendorTypes as $row_TVendorTypes) { ?>
                            <option value="<?php echo $row_TVendorTypes['VendorID'] ?>"><?php echo $row_TVendorTypes['VendorType'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Designee:</td>
                          <td align="left"><select id="desg0" onchange="setdesigee(this.value)" style="display:none">
                            <option value=""></option>
                            <?php foreach ($TAssets as $row_TAssets) { ?>
                            <option value="<?php echo $row_TAssets['AssetID'] ?>"><?php echo $row_TAssets['AssetName'] ?></option>
                            <?php } ?>
                          </select>
                            <select id="desg1" onchange="setdesigee(this.value)" style="display:none">
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==1) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg2" onchange="setdesigee(this.value)" style="display:none">
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==2) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg3" onchange="setdesigee(this.value)" style="display:none">
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==3) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg4" onchange="setdesigee(this.value)" style="display:none">
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==4) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg5" onchange="setdesigee(this.value)" style="display:none">
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==5) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg6" onchange="setdesigee(this.value)" style="display:none">
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==6) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg7" onchange="setdesigee(this.value)" style="display:none">
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==7) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <input name="occupant" type="hidden" id="occupant" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Designation:</td>
                          <td align="left"><textarea name="designation" rows="3" style="width:300px"></textarea></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Department:</td>
                          <td align="left"><select name="department">
                            <option value=""></option>
                            <?php foreach ($TDept as $row_TDept) { ?>
                            <option value="<?php echo $row_TDept['catID'] ?>"><?php echo $row_TDept['catname'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td class="titles">Management Staff:</td>
                          <td align="left"><select name="staff">
                            <option value=""></option>
                            <?php foreach ($TSup as $row_TSup) { ?>
                            <option value="<?php echo $row_TSup['VendorID'] ?>"><?php echo $row_TSup['VendorName'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                      </table>
                    </div>
<div class="TabbedPanelsContent">
  <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="140" class="titles">Cost of Purchase:</td>
                          <td align="left"><input name="PurchCost" type="text" size="16" /></td>
                        </tr>
                        <tr>
                          <td width="140" class="titles">Current Market Cost:</td>
                          <td align="left"><input name="CurPurchCost" type="text" size="16" /></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">Auction Value:</td>
                          <td align="left"><input name="AuctionValue" type="text" size="16" /></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="140" class="titles">Depreciation Method:</td>
                          <td width="322" align="left"><select name="DepreciationMethod">
                            <option value="0">Select One </option>
                            <option value="1">...</option>
                            <option value="2">,,,</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">Depreciation Life:</td>
                          <td align="left"><input name="DepreciableLife" type="text" size="10" /></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">Depreciation Rate:</td>
                          <td align="left"><input name="DepreciationRate" type="text" size="16" /></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">Depreciation Value:</td>
                          <td align="left"><input name="DepreciationValue" type="text" size="16" /></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">Salvage Value:</td>
                          <td align="left"><input name="SalvageValue" type="text" size="16" /></td>
                        </tr>
                        </table>
                  </div>
<div class="TabbedPanelsContent">
  <textarea name="Notes" style="width:450px" rows="10"></textarea>
</div>
<div class="TabbedPanelsContent"><?php include '../../scripts/newdoc.php' ?></div>
                  </div>
                  </div></td>
</tr>
              <tr>
                <td><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
                  <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
                  <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
                  <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
                  <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
                </td>
              </tr>
            </table></td>
              </tr>
              <tr>
                <td><input type="hidden" name="MM_insert" value="frmclient" /></td>
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
</html></html>