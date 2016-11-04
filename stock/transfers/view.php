<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Transfers'));
vetAccess('Stock', 'Transfers', 'View');

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Item Transfer]del.php?id=$id","","","find.php","print.php?id=$id","index.php");
$rec_status = 1;

if (_xpost("MM_Post") == "frmpost") {
    $outid = GSQLStr(_xpost("Outletout"), "int");
    $inid = GSQLStr(_xpost("Outletin"), "int");
    $sql = "FROM `{$_SESSION['DBCoy']}`.`req_items` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlet` ON `req_items`.ProductID=outlet.ProductID 
        AND outlet.OutletID=$outid
    WHERE `RequisitID`={$id} AND `ShopStock`<`units`";
    $errCnt = getDBDatacnt($dbh, $sql);

    if ($errCnt == 0){
        try {
            $dbh->autocommit(FALSE);
            $sql = "INSERT INTO `{$_SESSION['DBCoy']}`.`outlet`(`ProductID`, `OutletID`, `serials`, 
                `Shopshelf`, `ShopStock`, `Shopactlstock`, `Shoplevel`, `ShopNotes`) 
                SELECT `ProductID`, $inid, '', NULL, 0, 0, 0, ''
                FROM `{$_SESSION['DBCoy']}`.req_items 
                WHERE req_items.RequisitID={$id} AND `ProductID` NOT IN
                    (SELECT `ProductID` FROM `{$_SESSION['DBCoy']}`.`outlet` 
                    WHERE `OutletID`=$inid)";
            runDBQry($dbh, $sql);

            $join = "`{$_SESSION['DBCoy']}`.`req_items` 
                        INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`         ON `req_items`.ProductID=outlet.ProductID 
                                                                                AND outlet.OutletID=$outid 
                        INNER JOIN `{$_SESSION['DBCoy']}`.`outlet` `inlet` ON `req_items`.ProductID=inlet.ProductID 
                                                                                AND inlet.OutletID=$inid";
            runDBQry($dbh, "SELECT * FROM $join
                    INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`   ON `req_items`.ProductID=`items_prod`.`ProductID`
                    INNER JOIN `{$_SESSION['DBCoy']}`.`requisitions` ON `req_items`.RequisitID=`requisitions`.`RequisitID` 
                    WHERE `requisitions`.`RequisitID`={$id} LOCK IN SHARE MODE");

            $sql = "SELECT transfer_id, `units`, `req_items`.ProductName,
                `req_items`.serials, `outlet`.serials AS outserials, `inlet`.`serials` AS inserials
                FROM $join
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `req_items`.ProductID=`items_prod`.`ProductID` 
                WHERE `RequisitID`={$id} AND serialized=1";
            $TSerials = getDBData($dbh, $sql);

            $x = 0;
            for (; $x < count($TSerials); $x++) {
                $transfer = _xplode(",", $TSerials[$x]['serials']);
                $outlet = _xplode(",", $TSerials[$x]['outserials']);
                $inlet = _xplode(",", $TSerials[$x]['inserials']);
                if (count($transfer) != $TSerials[$x]['units']) {
                    array_push($errors, array("Serials Verification", "The Serials for '{$TSerials[$x]['ProductName']}' are incomplete!"));
                    break;
                }
                foreach ($transfer as $itm) {
                    if (!in_array($itm, $outlet)) {
                        array_push($errors, array("Serials Verification", "The Serial '$itm' is not in this Storage!"));
                        break 2;
                    }
                    $TSerials[$x]['_outserials'] = implode(',', array_diff($outlet, $transfer));
                    $TSerials[$x]['_inserials'] = implode(',', array_merge($inlet, $transfer));
                }
            }
            if ($x == count($TSerials)) {
                for ($x = 0; $x < count($TSerials); $x++) {
                    $sql = "UPDATE $join
                        SET `outlet`.serials = '{$TSerials[$x]['_outserials']}', 
                            `inlet`.`serials` = '{$TSerials[$x]['_inserials']}'
                        WHERE `transfer_id`={$TSerials[$x]['transfer_id']}";
                    runDBQry($dbh, $sql);
                }

                $sql = "UPDATE $join
                    SET `outlet`.`ShopStock`=`outlet`.`ShopStock`-`units`, 
                        `inlet`.`ShopStock`=`inlet`.`ShopStock`+`units`
                    WHERE `RequisitID`={$id}";
                runDBQry($dbh, $sql);
                
                $sql = "UPDATE `{$_SESSION['DBCoy']}`.`requisitions` 
                    SET `Transfered`=1, `GivenBy`={$_SESSION['ids']['VendorID']}
                    WHERE `RequisitID`={$id} AND `Transfered`=0";
                runDBQry($dbh, $sql);
            }
            $dbh->commit();
        } catch (Exception $ex) {
            $dbh->rollback();
            array_push($errors, array("Error", $ex->getMessage()));
        }
        $dbh->autocommit(TRUE);
    }
}

$vendor_req = vendorFlds("ReqBy", "req_by");
$vendor_giv = vendorFlds("GivBy", "giv_by");
$sql = "SELECT `requisitions`.*, Category, $vendor_req, $vendor_giv, 
        IF(`transfertype` IN (11,14,17),`deptin`.`catname`,`outin`.`OutletName`) AS Outlet_In,
        IF(`transfertype`>16,`deptout`.`catname`,`outout`.`OutletName`) AS Outlet_Out
    FROM `{$_SESSION['DBCoy']}`.`requisitions` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`status`          ON `requisitions`.`transfertype`=`status`.`CategoryID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`outlets` `outin`  ON `requisitions`.`Outletin`=`outin`.`OutletID` 
            AND `requisitions`.`transfertype` IN (12,13,15,16,18,19)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`outlets` `outout` ON `requisitions`.`Outletout`=`outout`.`OutletID` 
            AND `requisitions`.`transfertype` IN (11,12,13,14,15,16)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `deptin` ON `requisitions`.`Outletin`=`deptin`.`catID` 
            AND `requisitions`.`transfertype` IN (11,14,17)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `deptout` ON `requisitions`.`Outletout`=`deptout`.`catID` 
            AND `requisitions`.`transfertype` IN (17,18,19)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `ReqBy` ON `requisitions`.`RequestedBy`=`ReqBy`.VendorID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `GivBy` ON `requisitions`.`GivenBy`=`GivBy`.VendorID
    WHERE `RequisitID`={$id}";
$row_TTranfers = getDBDataRow($dbh, $sql);

$sql = "SELECT `req_items`.*, `ShopStock`, serialized, `outlet`.`serials` AS allserials
    FROM `{$_SESSION['DBCoy']}`.`req_items` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `req_items`.ProductID=`items_prod`.`ProductID` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlet` ON `req_items`.ProductID=outlet.ProductID 
        AND outlet.OutletID={$row_TTranfers['Outletout']}
    WHERE `RequisitID`={$id}";
$TItems = getDBData($dbh, $sql);

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], AccStat($access['Edit'], $row_TTranfers['Transfered']), AccStat($access['Del'], $row_TTranfers['Transfered']), $access['Print'], 0, 1);
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
  <link rel="stylesheet" href="/lib/jquery-ui/css/smoothness/jquery-ui.css">
  <script src="/lib/jquery-ui/js/jquery.js"></script>
  <script src="/lib/jquery-ui/js/jquery-ui.js"></script>
<script type="text/javascript" src="script.js"></script>
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
          <td width="240" valign="top"><img src="/images/transfers.jpg" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lbltransfers.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td width="120" class="titles">Transfer ID:</td>
                    <td class="red-normal"><b><?php echo $row_TTranfers['RequisitID']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Requested By:</td>
                    <td><?php echo $row_TTranfers['req_by'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Given By:</td>
                    <td><?php echo $row_TTranfers['giv_by'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Request Date:</td>
                    <td align="left"><?php echo $row_TTranfers['RequestDate'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Transfer Type:</td>
                    <td><?php echo $row_TTranfers['Category'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">From:</td>
                    <td><?php echo $row_TTranfers['Outlet_Out'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">To:</td>
                    <td><?php echo $row_TTranfers['Outlet_In'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Transfered:</td>
                    <td><input type="checkbox" name="Transfered"<?php if ($row_TTranfers['Transfered'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">&nbsp;</td>
                    <td><table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
                      <tr>
                        <td class="bo_tl"></td>
                        <td class="bo_tp"></td>
                        <td class="bo_tr"></td>
                      </tr>
                      <tr>
                        <td rowspan="2" class="bo_lf"></td>
                        <td align="left" class="bo_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td nowrap="nowrap">Shipping Info</td>
                            <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_tax" onclick="hideshow('tax', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_tax" onclick="hideshow('tax', 0, '')" style="display:none; cursor: pointer" /></div></td>
                          </tr>
                        </table></td>
                        <td rowspan="2" class="bo_rt"></td>
                      </tr>
                      <tr>
                        <td class="bo_center"><table width="100%" border="0" cellspacing="2" cellpadding="2" id="bx_tax" style="display:none">
                          <tr>
                            <td><table border="0" cellspacing="4" cellpadding="4">
                              <tr>
                                <td width="140" class="titles">Shipper:</td>
                                <td align="left"><?php echo $row_TTranfers['ShipName'] ?></td>
                              </tr>
                              <tr>
                                <td class="titles">Shipping Method:</td>
                                <td align="left"><script language="javascript" type="text/javascript">
                                switch (<?php echo $row_TTranfers['ShippingMethodID']; ?>) {
                                    case 1: document.write("Air Freight"); break;
                                    case 2: document.write("Sea Freight"); break;
                                    case 3: document.write("Parcel Service"); break;
                                    case 4: document.write("Door Delivery"); break;
                                    case 5: document.write("Others"); break;
                                    default: document.write("");
                                }</script></td>
                              </tr>
                              <tr>
                                <td class="titles">Date of Shipping:</td>
                                <td align="left"><?php echo $row_TTranfers['ShipDate'] ?></td>
                              </tr>
                              <tr>
                                <td valign="top" class="titles">Shipper Address:</td>
                                <td align="left"><textarea name="ShipAddress" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TTranfers['ShipAddress'] ?></textarea></td>
                              </tr>
                              <tr>
                                <td class="titles">&nbsp;</td>
                                <td align="left">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="titles">Freight Charge:</td>
                                <td><?php echo $row_TTranfers['FreightCharge'] ?></td>
                              </tr>
                              <tr>
                                <td class="titles">Expenses:</td>
                                <td><?php echo $row_TTranfers['expenses'] ?></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td class="bo_bl"></td>
                        <td class="bo_bt"></td>
                        <td class="bo_br"></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Notes:</td>
                    <td><div class="TabbedPanelsContent">
                      <textarea name="Notes" rows="5" readonly="readonly" style="width:500px"><?php echo $row_TTranfers['Notes'] ?></textarea>
                    </div></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
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
                            <td><?php $doc_shelf = 'Stock'.DS.'Transfers';
							$doc_id = $id; ?>
                              <?php include "../../scripts/viewdoc.php" ?></td>
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
                    <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="TabTransDet">
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Units</td>
                      </tr>
                      <?php $j = 0;foreach ($TItems as $row_TItems) { ?>
                      <tr id="RetDet<?php echo $j ?>">
                        <td align="center"><?php echo $row_TItems['ProductID'] ?></td>
                        <td align="center" id="ProductName<?php echo $j ?>"><strong><?php echo $row_TItems['ProductName'] ?></strong></td>
                        <td align="center"><?php echo $row_TItems['units'] ?></td>
                      </tr>
                      <?php $j++; } ?>
                    </table>
                      <script>var TransDetID=<?php echo $j ?> </script></td>
                  </tr>
                  <tr>
                    <td height="28" colspan="2" align="center"><?php if ($access['Transfer'] == 1 && $row_TTranfers['Transfered'] == 0) { ?>
                    <table border="0" cellspacing="1" cellpadding="1">
                        <tr>
                          <td><a id="post" href="javascript: void(0)" onclick="Transfer()"><img src="/images/but_transfer.png" width="80" height="20" /></a></td>
                          <td>
                            <form id="frmpost" name="frmpost" method="post" action="">
                              <input type="hidden" name="MM_Post" value="frmpost" />
                              <input name="TransDetID" type="hidden" id="TransDetID" value="<?php echo $j ?>" />
                              <input name="Outletout" type="hidden" id="Outletout" value="<?php echo $row_TTranfers['Outletout'] ?>" />
                              <input name="Outletin" type="hidden" id="Outletin" value="<?php echo $row_TTranfers['Outletin'] ?>" />
                            </form></td>
                        </tr>
                      </table>
                      <?php } ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php'); ?></td>
              </tr>

            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
          </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
