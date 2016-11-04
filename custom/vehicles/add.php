<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Assets'));
$access = _xvar_arr_sub($_access, array('Vehicles'));
vetAccess('Assets', 'Vehicles', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmAsset","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmAsset") {
  
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
                   2,
                   GSQLStr(_xpost('department'), "intn"),
                   GSQLStr($_SESSION['EmployeeID'], "intn"),//staff
                   GSQLStr(_xpost('lictype'), "int"),//desgtype
                   GSQLStr($_SESSION['custid'], "int"),//occupant
                   GSQLStr(_xpost('designation'), "text"),
                   GSQLStr(_xpost('colour'), "int"),
                   0,
                   GSQLStr(_xpost('description'), "text"),
                   GSQLStr(_xpost('Notes'), "text"),
                   GSQLStr(_xpost('Status'), "intn"),
                   _xpostchk('InUse'),
                   GSQLStr(_xpost('NextSchedMaint'), "date"),
                   GSQLStr(_xpost('Capacity'), "double"),
                   GSQLStr(_xpost('Children'), "int"),
                   GSQLStr(_xpost('brandid'), "text"),//Brand
                   GSQLStr(_xpost('Model'), "text"),
                   GSQLStr(_xpost('serieid'), "text"),//serialno
                   GSQLStr(_xpost('modelno'), "text"),
                   GSQLStr(_xpost('partno'), "text"),
                   GSQLStr(_xpost('year_prod'), "text"),//BarcodeNumber
                   GSQLStr(_xpost('licenceno'), "text"),
                   GSQLStr(_xpost('purchfrom'), "intn"),
                   GSQLStr(_xpost('PurchCost'), "double"),
                   GSQLStr(_xpost('CurPurchCost'), "double"),
                   GSQLStr(_xpost('AuctionValue'), "double"),
                   'CURDATE()',
                   GSQLStr(_xpost('DateSold'), "date"),
                   GSQLStr(_xpost('insurers'), "intn"),
                   GSQLStr(_xpost('servcomp'), "intn"),
                   GSQLStr(_xpost('insuranceno'), "text"),
                   GSQLStr(_xpost('DepreciationMethod'), "int"),
                   GSQLStr($_SESSION['OutletID'], "double"),//_xpost('DepreciableLife')
                   GSQLStr(_xpost('bstyle'), "double"),//DepreciationValue
                   GSQLStr(_xpost('DepreciationRate'), "double"),
                   GSQLStr(_xpost('vtype'), "double"));//SalvageValue
    $insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
        $recid = mysqli_insert_id($dbh);
		docs('Assets'.DS.'Vehicles', $recid);
        $pix = newpix(ROOT . ASSETPIX_DIR, '', $recid, 20, array(600, 200));

        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`assets` SET picturefile='{$pix['pixcode']}' 
                WHERE `AssetID`=$recid";
        runDBQry($dbh, $sql);

        array_push($_SESSION['new_veh'], $recid);
        header("Location: view.php?id=$recid");
        exit;
    }
}

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
<title>New Asset</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
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
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="auto_cats.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="autos.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="models.jgz" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/set.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
var arrFormValidation=[
    ["lictype", "if=$('#inf').click()",
        ["req", "Select License Type"]
    ],
    ["vtype", "if=$('#inf').click()",
        ["req", "Select Vehicle Type"]
    ],
    ["licenceno", "if=$('#part').click()",
        ["req", "Enter Registration No."]
    ],
    ["modelno", "if=$('#part').click()",
        ["req", "Enter Chassis No."]
    ],
    ["vetlicenceno", "if=$('#part').click()",
        ["chk=licenceno", "Please wait while we check the availability of this value. . . ."]
    ],
    ["vetmodelno", "if=$('#part').click()",
        ["chk=modelno", "Please wait while we check the availability of this value. . . ."]
    ],
    ["vetpartno", "if=$('#part').click()",
        ["chk=partno", "Please wait while we check the availability of this value. . . ."]
    ],
    ["vetinsuranceno", "if=$('#part').click()",
        ["chk=insuranceno", "Please wait while we check the availability of this value. . . ."]
    ]
];

function vet_chk(cmp, idx, pass) {
    if (pass || validateDataPop(arrFormValidation[idx][0][0], cmp, arrFormValidation[idx][0][1])) {
        checkup(cmp, 'checkcode.php?code=' + cmp.value + '&fld=' + cmp.getAttribute("name"));
    }
}

window.onload = function() {
    setContent();
}
window.onresize = function() {
    setContent();
}
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
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
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><img src="/images/vehicles.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(../images/lbladdvehicle.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
                  <tr>
                    <td><div id="Details" class="TabbedPanels">
                      <ul class="TabbedPanelsTabGroup">
                          <li class="TabbedPanelsTab" tabindex="0" id="inf">Info</li>
                        <li class="TabbedPanelsTab" tabindex="0" id="part">Particulars</li>
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
                              <td class="titles">Category:</td>
                              <td align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                                <tr>
                                  <td><input name="Category" type="radio" id="Category_1" value="29" size="32" checked="checked" /></td>
                                  <td>Private</td>
                                  <td>&nbsp;</td>
                                  <td><input name="Category" type="radio" id="Category_2" value="30" size="32" /></td>
                                  <td>Commercial</td>
                                  <td>&nbsp;</td>
                                  <td><input name="Category" type="radio" id="Category_3" value="31" size="32" /></td>
                                  <td>Government</td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">License Type:</td>
                              <td align="left"><select name="lictype" id="lictype" onchange="licch()">
                                <option value="" selected="selected">..</option>
                                <?php foreach ($TLicense as $row_TLicense) { ?>
                                <option value="<?php echo $row_TLicense['lic_typ'] ?>" cats="<?php echo $row_TLicense['cats'] ?>"><?php echo $row_TLicense['license'] ?></option>
                                <?php } ?>
                                </select></td>
                            </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Vehicle Type:</td>
                              <td align="left"><select name="vtype" id="vtype" onchange="typech()">
                                <option value="" selected="selected">..</option>
                              </select></td>
                            </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Body Style:</td>
                              <td align="left"><select name="bstyle" id="bstyle">
                                <option value="" selected="selected">..</option>
                              </select></td>
                            </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Brand:</td>
                              <td align="left"><select name="brandid" id="brandid" onchange="brandch()">
                                <option value="" selected="selected">..</option>
                              </select></td>
                            </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Model:</td>
                              <td align="left"><select name="serieid" id="serieid">
                                <option value="" selected="selected">..</option>
                              </select>
                                <input type="text" name="Model" size="30" /></td>
                            </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Year:</td>
                              <td align="left"><select name="year_prod" id="year_prod">
                                <option value="" selected="selected">..</option>
                              </select>
                                <script type="text/javascript">
					var yr;
					Today = new Date();
					yr = Today.getFullYear() + 2;
					i=1;
					while (yr>=1900) {
						document.getElementById("year_prod").options[i] = new Option(yr, yr, false, false);
						yr--;
						i++;
					}
                                </script></td>
                            </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">&nbsp;</td>
                              <td align="left">&nbsp;</td>
                            </tr>
                            <tr>
                              <td width="120" nowrap="nowrap" class="titles">Passengers:</td>
                              <td align="left"><input type="text" name="Children" size="10" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Weight (kg):</td>
                              <td align="left"><input type="text" name="CurPurchCost" size="10" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Load (kg):</td>
                              <td align="left"><input type="text" name="AuctionValue" size="10" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Engine Capacity:</td>
                              <td align="left"><input type="text" name="Capacity" size="10" /></td>
                              </tr>
                            <tr>
                              <td class="titles">Color:</td>
                              <td align="left"><select name="colour">
                                <option value="">..</option>
                                <?php foreach ($colors as $row_colors) { ?>
                                <option value="<?php echo $row_colors['colorid'] ?>" style="background-color: <?php echo $row_colors['colorcode'] ?>;color: Black;"><?php echo $row_colors['colorname'] ?></option>
                                <?php } ?>
                                </select></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Description:</td>
                              <td align="left"><textarea name="description" rows="3" style="width:300px"></textarea></td>
                              </tr>
                        </table>
                          </div>
                        <div class="TabbedPanelsContent">
                          <table border="0" cellspacing="4" cellpadding="4">
                            <tr>
                              <td width="120" nowrap="nowrap" class="titles">Registration No.:
                                  <iframe style="display:none" id="licencenowin"></iframe></td>
                              <td align="left"><input type="text" name="licenceno" size="32" onchange="vet_chk(this, 2, false)" />
                                  <input name="vetlicenceno" type="hidden" id="vetlicenceno" value="0" /><span id="licencenoprogress"></span></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Chassis No.:
                                <iframe style="display:none" id="modelnowin"></iframe></td>
                              <td align="left"><input type="text" name="modelno" size="32" onchange="vet_chk(this, 3, false)" />
                                  <input name="vetmodelno" type="hidden" id="vetmodelno" value="0" /><span id="modelnoprogress"></span></td>
                              </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Engine No.:
                                <iframe style="display:none" id="partnowin"></iframe></td>
                              <td align="left"><input type="text" name="partno" size="32" onchange="vet_chk(this, 4, true)" />
                                  <input name="vetpartno" type="hidden" id="vetpartno" value="0" /><span id="partnoprogress"></span></td>
                            </tr>
                            <tr>
                              <td nowrap="nowrap" class="titles">Insurance No.:
                                <iframe style="display:none" id="insurancenowin"></iframe></td>
                              <td align="left"><input type="text" name="insuranceno" size="32" onchange="vet_chk(this, 5, true)" />
                                  <input name="vetinsuranceno" type="hidden" id="vetinsuranceno" value="0" /><span id="insurancenoprogress"></span></td>
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
                <td><input type="hidden" name="MM_insert" value="frmAsset" /></td>
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
</script></td>
      <td class="frameright">&nbsp;</td>
    </tr>
    <tr>
      <td class="framebotleft">&nbsp;</td>
      <td valign="bottom" class="framebot"><span class="greytxt">Copyright © 2010 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>
</html>