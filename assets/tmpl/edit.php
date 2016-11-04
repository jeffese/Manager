<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Assets'));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess('Assets', $vkey, 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmAsset","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Assets'.DS.$vkey;
$doc_id = $id;

if (_xpost("MM_update") == "frmAsset") {
  
	$pix = newpix(ROOT . ASSETPIX_DIR, '', $id, 20, array(600, 200));
	
	$sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`assets` SET `AssetCode`=%s,`AssetName`=%s,`parent`=%s,
            `Category`=%s,`AssetType`=%s,`department`=%s,`staff`=%s,`desgtype`=%s,`occupant`=%s,`designation`=%s,
            `colour`=%s,`picturefile`=%s,`description`=%s,`Notes`=%s,`Status`=%s,`InUse`=%s,`NextSchedMaint`=%s,
            `Capacity`=%s,`Brand`=%s,`Model`=%s,`serialno`=%s,`modelno`=%s,
            `partno`=%s,`BarcodeNumber`=%s,`licenceno`=%s,`purchfrom`=%s,`PurchCost`=%s,`CurPurchCost`=%s,
            `AuctionValue`=%s,`dateofpurch`=%s,`DateSold`=%s,`insurers`=%s,`servcomp`=%s,`insuranceno`=%s,
            `DepreciationMethod`=%s,`DepreciableLife`=%s,`DepreciationValue`=%s,`DepreciationRate`=%s,
            `SalvageValue`=%s WHERE AssetID=%s",
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
                       $pix['pixcode'],
                       GSQLStr(_xpost('description'), "text"),
                       GSQLStr(_xpost('Notes'), "text"),
                       GSQLStr(_xpost('Status'), "intn"),
                       _xpostchk('InUse'),
                       GSQLStr(_xpost('NextSchedMaint'), "date"),
                       GSQLStr(_xpost('Capacity'), "int"),
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
                       GSQLStr(_xpost('SalvageValue'), "double"),
                       $id);
	$update = runDBQry($dbh, $sql);
	docs($doc_shelf, $doc_id);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`assets` WHERE `AssetID`=".$id;
$row_TAssets = getDBDataRow($dbh, $sql);

$sql = "SELECT AssetID, AssetName FROM `{$_SESSION['DBCoy']}`.`assets` WHERE AssetID<>{$row_TAssets['AssetID']} ORDER BY `AssetName`";
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

$Allocated = $row_TAssets['Children'] == 1 || $access['Allocate'] == 0 ? ' disabled="disabled"' : "";

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
    designate(<?php echo $row_TAssets['desgtype']; ?>);
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
        <td width="240" valign="top"><img src="/images/<?php echo $vpth?>.jpg" alt="" width="240" height="300" /></td>
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmAsset" id="frmAsset">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><?php $pixcnt = 20; $max = 20000000; ?>
                  <?php 
$pictfld = $row_TAssets['picturefile'];
$fpath = $id;
$pixdir = ASSETPIX_DIR;
$pixi = 'x';
?><?php include('../../scripts/editpix.php'); ?></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td class="titles">ID:</td>
                    <td class="red-normal"><b><?php echo $row_TAssets['AssetID']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Code:</td>
                    <td align="left"><input name="AssetCode" type="text" value="<?php echo $row_TAssets['AssetCode'] ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Name:</td>
                    <td align="left"><input name="AssetName" type="text" value="<?php echo $row_TAssets['AssetName'] ?>" size="32" /></td>
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
                        <td align="left"><input type="checkbox" name="InUse" value="1" <?php if (!(strcmp($row_TAssets['InUse'], 1))) { echo "checked=\"checked\""; } ?> /></td>
                      </tr>
                      <tr>
                        <td class="titles">Parent Asset:</td>
                        <td><select name="parent">
                          <option value=""></option>
                          <?php foreach ($TAssets as $row_TAssets) { ?>
                          <option value="<?php echo $row_TAssets['AssetID'] ?>" <?php if (!(strcmp($row_TAssets['parent'],$row_TAssets['AssetID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TAssets['AssetName'] ?></option>
                          <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td class="titles">Category:</td>
                        <td align="left"><select name="Category">
                          <option value=""></option>
                          <?php foreach ($TCat as $row_TCat) { ?>
                          <option value="<?php echo $row_TCat['catID'] ?>" <?php if (!(strcmp($row_TAssets['Category'],$row_TCat['catID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TCat['catname'] ?></option>
                          <?php } ?>
                          </select>
                          <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/assets/cat/index.php', 480,520)" /></td>
                      </tr>
                      <tr>
                        <td class="titles">Color:</td>
                        <td align="left"><select name="colour">
                          <option value="" selected="selected">Select Color</option>
                          <?php foreach ($colors as $row_colors) { ?>
                          <option value="<?php echo $row_colors['colorid'] ?>" style="background-color: <?php echo $row_colors['colorcode'] ?>;color: Black;" <?php if (!(strcmp($row_colors['colorid'], $row_TAssets['colour']))) {echo "selected=\"selected\"";} ?>><?php echo $row_colors['colorname'] ?></option>
                          <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td class="titles">Status:</td>
                        <td align="left"><select name="Status">
                          <option value=""></option>
                          <?php foreach ($TStatus as $row_TStatus) { ?>
                          <option value="<?php echo $row_TStatus['CategoryID'] ?>" <?php if (!(strcmp($row_TAssets['Status'],$row_TStatus['CategoryID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TStatus['Category'] ?></option>
                          <?php } ?>
                        </select>
                          <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/assets/status/index.php', 480,520)" /></td>
                      </tr>
                      </table>
                      <table border="0" cellpadding="4" cellspacing="4" id="eqp">
                        <tr>
                          <td width="120" nowrap="nowrap" class="titles">Brand:</td>
                          <td><input type="text" name="Brand" value="<?php echo $row_TAssets['Brand'] ?>" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Model:</td>
                          <td><input type="text" name="Model" value="<?php echo $row_TAssets['Model'] ?>" size="32" /></td>
                        </tr>
                      </table>
                      <table border="0" cellpadding="4" cellspacing="4">
                        <tr>
                          <td width="120" nowrap="nowrap" class="titles">Capacity:</td>
                          <td><input type="text" name="Capacity" value="<?php echo $row_TAssets['Capacity'] ?>" size="10" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Description:</td>
                          <td><textarea name="description" rows="3" style="width:300px"><?php echo $row_TAssets['description'] ?></textarea></td>
                        </tr>
                    </table>
                      </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellpadding="4" cellspacing="4">
                        <tr>
                          <td width="120" nowrap="nowrap" class="titles">Serial No.:</td>
                          <td><input type="text" name="serialno" value="<?php echo $row_TAssets['serialno'] ?>" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Model No.:</td>
                          <td><input type="text" name="modelno" value="<?php echo $row_TAssets['modelno'] ?>" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Part No.:</td>
                          <td><input type="text" name="partno" value="<?php echo $row_TAssets['partno'] ?>" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Barcode:</td>
                          <td><input type="text" name="BarcodeNumber" value="<?php echo $row_TAssets['BarcodeNumber'] ?>" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Insurance No.:</td>
                          <td><input type="text" name="insuranceno" value="<?php echo $row_TAssets['insuranceno'] ?>" size="32" /></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">License No.:</td>
                          <td><input type="text" name="licenceno" value="<?php echo $row_TAssets['licenceno'] ?>" size="32" /></td>
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
                            <option value="<?php echo $row_TClients['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['purchfrom'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td class="titles">Insurer:</td>
                          <td><select name="insurers">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if (in_array($row_TClients['VendorType'], array(2,3))) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['insurers'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td nowrap="nowrap" class="titles">Maintenance Company:</td>
                          <td nowrap="nowrap"><select name="servcomp">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if (in_array($row_TClients['VendorType'], array(2,3))) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['servcomp'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td class="titles">&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="titles">Next Maintenance:</td>
                          <td><input name="NextSchedMaint" type="text" id="NextSchedMaint" value="<?php echo $row_TAssets['NextSchedMaint'] ?>" size="12" readonly="readonly" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Date of Purchase:</td>
                          <td><input name="dateofpurch" type="text" id="dateofpurch" value="<?php echo $row_TAssets['dateofpurch'] ?>" size="12" readonly="readonly" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Date Sold:</td>
                          <td><input name="DateSold" type="text" id="DateSold" value="<?php echo $row_TAssets['DateSold'] ?>" size="12" readonly="readonly" /></td>
                        </tr>
                        </table>
                    </div>
                    <div class="TabbedPanelsContent">
                      <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="120" class="titles">Designee Type:</td>
                          <td width="322" align="left"><select name="desgtype" onchange="designate(this.value)"<?php echo $Allocated ?>>
                            <option value="0" <?php if (!(strcmp($row_TAssets['desgtype'], "0"))) { echo "selected=\"selected\""; }?>>Asset</option>
                            <?php foreach ($TVendorTypes as $row_TVendorTypes) { ?>
                            <option value="<?php echo $row_TVendorTypes['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['desgtype'], $row_TVendorTypes['VendorID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TVendorTypes['VendorType'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td width="120" class="titles">Designee:</td>
                          <td align="left"><select id="desg0" onchange="setdesigee(this.value)" style="display:none"<?php echo $Allocated ?>>
                            <option value=""></option>
                            <?php foreach ($TAssets as $row_TAssets) { ?>
                            <option value="<?php echo $row_TAssets['AssetID'] ?>" <?php if (!(strcmp($row_TAssets['occupant'],$row_TAssets['AssetID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TAssets['AssetName'] ?></option>
                            <?php } ?>
                          </select>
                            <select id="desg1" onchange="setdesigee(this.value)" style="display:none"<?php echo $Allocated ?>>
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==1) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['occupant'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg2" onchange="setdesigee(this.value)" style="display:none"<?php echo $Allocated ?>>
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==2) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['occupant'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg3" onchange="setdesigee(this.value)" style="display:none"<?php echo $Allocated ?>>
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==3) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['occupant'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg4" onchange="setdesigee(this.value)" style="display:none"<?php echo $Allocated ?>>
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==4) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['occupant'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg5" onchange="setdesigee(this.value)" style="display:none"<?php echo $Allocated ?>>
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==5) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['occupant'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg6" onchange="setdesigee(this.value)" style="display:none"<?php echo $Allocated ?>>
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==6) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['occupant'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <select id="desg7" onchange="setdesigee(this.value)" style="display:none"<?php echo $Allocated ?>>
                              <option value=""></option>
                              <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==7) { ?>
                              <option value="<?php echo $row_TClients['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['occupant'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                              <?php }} ?>
                            </select>
                            <input name="occupant" type="hidden" id="occupant" value="<?php echo $row_TAssets['occupant'] ?>" /></td>
                        </tr>
                        <tr>
                          <td class="titles">Designation:</td>
                          <td align="left"><textarea name="designation" rows="3" style="width:300px"<?php echo $Allocated ?>><?php echo $row_TAssets['designation'] ?></textarea></td>
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
                            <option value="<?php echo $row_TDept['catID'] ?>" <?php if (!(strcmp($row_TAssets['department'], $row_TDept['catID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TDept['catname'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                        <tr>
                          <td class="titles">Management Staff:</td>
                          <td align="left"><select name="staff">
                            <option value=""></option>
                            <?php foreach ($TSup as $row_TSup) { ?>
                            <option value="<?php echo $row_TSup['VendorID'] ?>" <?php if (!(strcmp($row_TAssets['staff'], $row_TSup['VendorID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TSup['VendorName'] ?></option>
                            <?php } ?>
                          </select></td>
                        </tr>
                      </table>
                    </div>
<div class="TabbedPanelsContent">
  <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                          <td width="140" class="titles">Cost of Purchase:</td>
                          <td align="left"><input name="PurchCost" type="text" value="<?php echo $row_TAssets['PurchCost'] ?>" size="16" /></td>
                        </tr>
                        <tr>
                          <td width="140" class="titles">Current Market Cost:</td>
                          <td align="left"><input name="CurPurchCost" type="text" value="<?php echo $row_TAssets['CurPurchCost'] ?>" size="16" /></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">Auction Value:</td>
                          <td align="left"><input name="AuctionValue" type="text" value="<?php echo $row_TAssets['AuctionValue'] ?>" size="16" /></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="140" class="titles">Depreciation Method:</td>
                          <td width="322" align="left"><select name="DepreciationMethod">
                            <option value="0" <?php if (!(strcmp($row_TAssets['DepreciationMethod'],"0"))) {echo "selected=\"selected\"";} ?>>Select One </option>
                            <option value="1" <?php if (!(strcmp($row_TAssets['DepreciationMethod'],"1"))) {echo "selected=\"selected\"";} ?>>...</option>
                            <option value="2" <?php if (!(strcmp($row_TAssets['DepreciationMethod'],"2"))) {echo "selected=\"selected\"";} ?>>,,,</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">Depreciation Life:</td>
                          <td align="left"><input name="DepreciableLife" type="text" value="<?php echo $row_TAssets['DepreciableLife'] ?>" size="10" /></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">Depreciation Rate:</td>
                          <td align="left"><input name="DepreciationRate" type="text" value="<?php echo $row_TAssets['DepreciationRate'] ?>" size="16" /></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">Depreciation Value:</td>
                          <td align="left"><input name="DepreciationValue" type="text" value="<?php echo $row_TAssets['DepreciationValue'] ?>" size="16" /></td>
                        </tr>
                        <tr>
                          <td width="140" nowrap="nowrap" class="titles">Salvage Value:</td>
                          <td align="left"><input name="SalvageValue" type="text" value="<?php echo $row_TAssets['SalvageValue'] ?>" size="16" /></td>
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
                </td>
              </tr>
            </table></td>
              </tr>
              <tr>
                <td><input type="hidden" name="MM_update" value="frmAsset" />
                  <input type="hidden" name="AssetID" value="<?php echo $row_TAssets['AssetID']; ?>" />
                  <?php include('../../scripts/buttonset.php')?></td>
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