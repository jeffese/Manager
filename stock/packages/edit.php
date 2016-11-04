<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Packages'));
vetAccess('Stock', 'Packages', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmpackage","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Stock'.DS.'Packages';
$doc_id = $id;

if (_xpost("MM_update") == "frmpackage") {
    try {
        $dbh->autocommit(FALSE);
        
        $ItmID = intval(_xpost('ItmID'));
        for ($q = 0; $q < $ItmID; $q++) {
            if (!isset($_POST["itmid_$q"]))
                continue;
            $subid = intval(_xpost("subid_$q"));
            if ($subid == 0) {
                $sql = sprintf("INSERT INTO `%s`.`items_pkgs_itms`(`PackageID`, `ProductID`, 
                    `Quantity`, `Discount`, `Discnt`) VALUES (%s,%s,%s,%s,%s)",
                        $_SESSION['DBCoy'],
                        $id,
                        GSQLStr(_xpost("itmid_$q"), "int"),
                        GSQLStr(_xpost("Quantity_$q"), "double"),
                        GSQLStr(_xpost("Discount_$q"), "double"),
                        GSQLStr(_xpost("Discnt_$q"), "double"));
            } else {
                $sql = sprintf("UPDATE `%s`.`items_pkgs_itms` SET `Quantity`=%s,`Discount`=%s, `Discnt`=%s
                     WHERE `PackItemID`=%s",
                           $_SESSION['DBCoy'],
                        GSQLStr(_xpost("Quantity_$q"), "double"),
                        GSQLStr(_xpost("Discount_$q"), "double"),
                        GSQLStr(_xpost("Discnt_$q"), "double"),
                        $subid);
            }
            runDBQry($dbh, $sql);
        }

        $delsub = _xpost('del');
        $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`items_pkgs_itms` WHERE PackItemID IN ($delsub)";
        runDBQry($dbh, $sql);

        $sql = sprintf("UPDATE `%s`.`items` SET `ExoodID`=%s,`ProdCode`=%s,`ProdName`=%s,`Description`=%s,
            `Classification`=%s,`category`=%s,`status`=%s,`UnitPrice`=%s,`WebPrice`=%s,
            `InUse`=%s,`Notes`=%s,`exood`=%s,`exoodsales`=%s,`InfoLoad`=%s,`pixLoad`=%s,`StockLoad`=%s 
            WHERE ItemID=%s",
                       $_SESSION['DBCoy'],
                       GSQLStr(_xpost('ExoodID'), "int"),
                       GSQLStr(_xpost('ProdCode'), "text"),
                       GSQLStr(_xpost('ProdName'), "text"),
                       GSQLStr(_xpost('Description'), "text"),
                       GSQLStr(_xpost('Classification'), "intn"),
                       GSQLStr(_xpost('category'), "intn"),
                       GSQLStr(_xpost('status'), "intn"),
                       GSQLStr(_xpost('Grandvalue'), "double"),
                       GSQLStr(_xpost('WebPrice'), "double"),
                       _xpostchk('InUse'),
                       GSQLStr(_xpost('Notes'), "text"),
                       _xpostchk('exood'),
                       _xpostchk('exoodsales'),
                       _xpostchk('InfoLoad'),
                       _xpostchk('pixLoad'),
                       _xpostchk('StockLoad'),
                       $id);
        runDBQry($dbh, $sql);
        $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`items_pkgs`
            SET `StartDate`=%s,`EndDate`=%s,`Dscnt`=%s,`Discount`=%s,`TotalValue`=%s,
            `TotDisc`=%s,`Grandvalue`=%s,`wkday`=%s,`LimitedTime`=%s,`outlets`=%s 
            WHERE `PackageID`=%s",
                        GSQLStr(_xpost('StartDate'), "date"),
                        GSQLStr(_xpost('EndDate'), "date"),
                        GSQLStr(_xpost('Dscnt'), "double"),
                        GSQLStr(_xpost('Discount'), "double"),
                        GSQLStr(_xpost('TotalValue'), "double"),
                        GSQLStr(_xpost('TotDisc'), "double"),
                        GSQLStr(_xpost('Grandvalue'), "double"),
                        GSQLStr(_xpost('wkday'), "text"),
                        _xpostchk('LimitedTime'),
                        GSQLStr(_xpost('outlets'), "text"),
                       $id);
        $update = runDBQry($dbh, $sql);
        
        if ($update != 1) {
            throw new Exception("Not updated");
        }
        $dbh->commit();
        $dbh->autocommit(TRUE);
        docs($doc_shelf, $doc_id);
        header("Location: view.php?id=$id");
        exit;
    } catch (Exception $ex) {
        $dbh->rollback();
    }
    $dbh->autocommit(TRUE);
}

$sql = "SELECT `items`.*, `items_pkgs`.* FROM `{$_SESSION['DBCoy']}`.`items` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs` ON items.ItemID=items_pkgs.PackageID
    WHERE `PackageID`={$id}";
$row_TPacks = getDBDataRow($dbh, $sql);

$sql = "SELECT `items_pkgs_itms`.*, `ProdName`, `UnitPrice` 
    FROM `{$_SESSION['DBCoy']}`.`items_pkgs_itms`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items` ON `items_pkgs_itms`.ProductID=items.ItemID 
    WHERE `PackageID`={$id}";
$TItems = getDBData($dbh, $sql);

$TCat = getClassify(2);

$sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
    WHERE `OutletID` NOT IN (0{$row_TPacks['outlets']})";
$TOutlet = getDBData($dbh, $sql);

$sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
    WHERE `OutletID` IN (0{$row_TPacks['outlets']})";
$T_Outlet = getDBData($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="script.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["ProdName", "", 
            ["req", "Enter Name"]
        ]
    ]
    
    var  mCal, mCal2;
    window.onload = function() {
        mCal = new dhtmlxCalendarObject('StartDate', true, {isYearEditable: true, isMonthEditable: true});
	mCal.setSkin('dhx_black');
        mCal2 = new dhtmlxCalendarObject('EndDate', true, {isYearEditable: true, isMonthEditable: true});
	mCal2.setSkin('dhx_black');
        calItms();
        setdays();
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
        <td width="240" valign="top"><img src="/images/package.jpg" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblpackage.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return vetProds() && validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmpackage" id="frmpackage">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td width="120" class="titles">Package ID:</td>
                    <td class="red-normal"><b><?php echo $row_TPacks['PackageID']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Code:</td>
                    <td align="left"><input type="text" name="ProdCode" value="<?php echo $row_TPacks['ProdCode'] ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Name:</td>
                    <td align="left"><input type="text" name="ProdName" value="<?php echo $row_TPacks['ProdName'] ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td><select name="Classification">
                      <option value=""></option>
                      <?php foreach ($TCat as $row_TCat) { ?>
                      <option value="<?php echo $row_TCat['catID'] ?>" <?php if (!(strcmp($row_TPacks['Classification'], $row_TCat['catID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TCat['catname'] ?></option>
                      <?php } ?>
                    </select>
                      <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/stock/cat/index.php', 480,520)" /></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Active:</td>
                    <td><input type="checkbox" name="InUse"<?php if ($row_TPacks['InUse'] == 1) {
                echo " checked=\"checked\"";
            } ?> /></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Items:</td>
                    <td><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td>Value:</td>
                        <td><input name="TotalValue" type="text" style="width:80px" value="<?php echo $row_TPacks['TotalValue'] ?>" readonly="readonly" /></td>
                        <td>&nbsp;</td>
                        <td>Discount:</td>
                        <td><input name="TotDisc" type="text" value="<?php echo $row_TPacks['TotDisc'] ?>" size="12" readonly="readonly" /></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Discount:</td>
                    <td><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><input type="text" name="Dscnt" value="<?php echo $row_TPacks['Dscnt'] ?>" onchange="dsc()" style="width:30px" /></td>
                        <td>%</td>
                        <td><strong>=&gt;</strong></td>
                        <td><input type="text" name="Discount" value="<?php echo $row_TPacks['Discount'] ?>" onchange="disc()" size="12" /></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Total Value:</td>
                    <td><input name="Grandvalue" type="text" style="width:120px" value="<?php echo $row_TPacks['Grandvalue'] ?>" readonly="readonly" /></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td id="ordwords">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Life Time:</td>
                    <td><input type="checkbox" name="LimitedTime"<?php if ($row_TPacks['LimitedTime'] == 1) {
                echo " checked=\"checked\"";
            } ?> onclick="if (this.checked) $('#life').show(); else $('#life').hide()" /></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td><table border="0" cellspacing="2" cellpadding="2" id="life"<?php if ($row_TPacks['LimitedTime'] == 0) { ?> style="display:none"<?php } ?>>
                      <tr>
                        <td><table border="0" cellspacing="1" cellpadding="1">
                          <tr>
                            <td>Start Date:</td>
                            <td><input name="StartDate" type="text" id="StartDate" value="<?php echo $row_TPacks['StartDate'] ?>" size="16" /></td>
                            <td><input name="wkday" type="hidden" id="wkday" value="<?php echo $row_TPacks['wkday'] ?>" /></td>
                            <td>End Date:</td>
                            <td><input name="EndDate" type="text" id="EndDate" value="<?php echo $row_TPacks['EndDate'] ?>" size="16" /></td>
                            </tr>
                          </table></td>
                        </tr>
                      <tr>
                        <td><table border="0" cellspacing="1" cellpadding="1">
                          <tr>
                            <td><input id="Sunday" type="checkbox" value="1" onclick="getdays()" /></td>
                            <td>Sunday</td>
                            <td>&nbsp;</td>
                            <td><input id="Monday" type="checkbox" value="2" onclick="getdays()" /></td>
                            <td>Monday</td>
                            <td>&nbsp;</td>
                            <td><input id="Tuesday" type="checkbox" value="3" onclick="getdays()" /></td>
                            <td>Tuesday</td>
                            <td>&nbsp;</td>
                            <td><input id="Wednesday" type="checkbox" value="4" onclick="getdays()" /></td>
                            <td>Wednesday</td>
                            <td>&nbsp;</td>
                            <td><input id="Thursday" type="checkbox" value="5" onclick="getdays()" /></td>
                            <td>Thursday</td>
                            <td>&nbsp;</td>
                            <td><input id="Friday" type="checkbox" value="6" onclick="getdays()" /></td>
                            <td>Friday</td>
                            <td>&nbsp;</td>
                            <td><input id="Saturday" type="checkbox" value="7" onclick="getdays()" /></td>
                            <td>Saturday</td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Outlets:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td class="h1">&nbsp;</td>
                        <td><input type="hidden" name="outlets" id="outlets" value="<?php echo $row_TPacks['outlets']; ?>" /></td>
                        <td nowrap="nowrap" class="h1">Selected Outlets</td>
                      </tr>
                      <tr>
                        <td valign="top"><select name="alloutlets" size="10" id="alloutlets">
                          <?php foreach ($TOutlet as $row_TOutlet) { ?>
                          <option value="<?php echo $row_TOutlet['OutletID'] ?>"><?php echo $row_TOutlet['OutletName'] ?></option>
                          <?php } ?>
                        </select></td>
                        <td><p><a href="javascript: void(0)" onclick="pushRules(frmpackage.alloutlets, frmpackage.seloutlets, frmpackage.seloutlets, frmpackage.outlets)"><img src="/images/last.png" width="24" height="24" /></a></p>
                          <p><a href="javascript: void(0)" onclick="pushRule(frmpackage.alloutlets, frmpackage.seloutlets, frmpackage.seloutlets, frmpackage.outlets)"><img src="/images/next.png" width="24" height="24" /></a></p>
                          <p><a href="javascript: void(0)" onclick="pushRule(frmpackage.seloutlets, frmpackage.alloutlets, frmpackage.seloutlets, frmpackage.outlets)"><img src="/images/prev.png" width="24" height="24" /></a></p>
                          <p><a href="javascript: void(0)" onclick="pushRules(frmpackage.seloutlets, frmpackage.alloutlets, frmpackage.seloutlets, frmpackage.outlets)"><img src="/images/first.png" width="24" height="24" /></a></p></td>
                        <td valign="top"><select name="seloutlets" size="10" id="seloutlets">
                          <?php foreach ($T_Outlet as $row_T_Outlet) { ?>
                          <option value="<?php echo $row_T_Outlet['OutletID'] ?>"><?php echo $row_T_Outlet['OutletName'] ?></option>
                          <?php } ?>
                        </select></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td valign="top" class="titles">Notes:</td>
                    <td><textarea name="Notes" style="width:450px" rows="5"><?php echo $row_TPacks['Notes'] ?></textarea></td>
                  </tr>
                  <tr>
                    <td valign="top" class="titles">&nbsp;</td>
                    <td><table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
                      <tr>
                        <td class="bl_tl"></td>
                        <td class="bl_tp"></td>
                        <td class="bl_tr"></td>
                      </tr>
                      <tr>
                        <td rowspan="2" class="bl_lf"></td>
                        <td align="left" class="bl_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td nowrap="nowrap">Documents</td>
                            <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_docs" onclick="hideshow('docs', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_docs" onclick="hideshow('docs', 0, '')" style="display:none; cursor: pointer" /></div></td>
                          </tr>
                        </table></td>
                        <td rowspan="2" class="bl_rt"></td>
                      </tr>
                      <tr>
                        <td class="bl_center"><table width="100%" border="0" cellspacing="2" cellpadding="2" id="bx_docs" style="display:none">
                          <tr>
                            <td><?php include "../../scripts/editdoc.php" ?></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td class="bl_bl"></td>
                        <td class="bl_bt"></td>
                        <td class="bl_br"></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="Tabdet">
                      <tr class="boldwhite1">
                        <td colspan="9" align="center" nowrap="nowrap" bgcolor="#000000" class="h1">Items</td>
                        </tr>
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000"><input type="hidden" name="del" value="0" /></td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Unit Price</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">% Dsc</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Discount</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Sales Price</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Total Value</td>
                        </tr>
                        <?php $j = 0;foreach ($TItems as $row_TItems) { ?>
                      <tr id="itm_<?php echo $j ?>">
                        <td><a href="javascript: void(0)" onclick="removeItm(<?php echo $j ?>)"><img src="/images/delete.png" width="16" height="16" /></a>
                          <input type="hidden" name="subid_<?php echo $j ?>" id="subid_<?php echo $j ?>" value="<?php echo $row_TItems['PackItemID']; ?>" />
                          <input type="hidden" name="itmid_<?php echo $j ?>" id="itmid_<?php echo $j ?>" value="<?php echo $row_TItems['ProductID']; ?>" /></td>
                        <td><?php echo $row_TItems['PackItemID'] ?></td>
                        <td id="Name_<?php echo $j ?>"><?php echo $row_TItems['ProdName'] ?></td>
                        <td><input type="text" name="Quantity_<?php echo $j ?>" id="Quantity_<?php echo $j ?>" value="<?php echo $row_TItems['Quantity'] ?>" onChange="setthous(this, 1); calItm(<?php echo $j ?>)" style="width:40px" /></td>
                        <td id="unitprice_<?php echo $j ?>"><?php echo $row_TItems['UnitPrice'] ?></td>
                        <td><input type="text" name="Discnt_<?php echo $j ?>" id="Discnt_<?php echo $j ?>" value="<?php echo $row_TItems['Discnt'] ?>" onchange="setthous(this, 0); dscItm(<?php echo $j ?>)" style="width:30px" /></td>
                        <td><input type="text" name="Discount_<?php echo $j ?>" id="Discount_<?php echo $j ?>" value="<?php echo $row_TItems['Discount'] ?>" onchange="setthous(this, 0); discItm(<?php echo $j ?>)" style="width:100px" /></td>
                        <td id="salesprice_<?php echo $j ?>">&nbsp;</td>
                        <td id="Total_<?php echo $j ?>">&nbsp;</td>
                        </tr>
                        <?php $j++; } ?>
                      </table>
                      <input name="ItmID" type="hidden" id="ItmID" value="<?php echo $j ?>" />
<script>var ItmID=<?php echo $j ?> </script></td>
                  </tr>
                  <tr>
                    <td height="28" colspan="2" align="center"><a href="javascript: void(0)" onclick="GB_showCenter('Product List', '/stock/packages/pick.php', 600,600)"><img src="/images/but_add.png" width="50" height="20" /></a></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
                  <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
                  <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
                  <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
                  <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmpackage" />
            <input type="hidden" name="PackageID" value="<?php echo $row_TPacks['PackageID']; ?>" />
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