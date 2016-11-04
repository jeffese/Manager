<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Transfers'));
vetAccess('Stock', 'Transfers', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmtransfer","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Stock'.DS.'Transfers';
$doc_id = $id;

if (_xpost("MM_update") == "frmtransfer") {
    try {
        $dbh->autocommit(FALSE);

        $TransDetID = intval(_xpost('TransDetID'));
        for ($q = 0; $q < $TransDetID; $q++) {
            if (!isset($_POST["ProductID$q"]) || floatval(_xpost("units$q")) <= 0)
                continue;
            $transfer_id = intval(_xpost("transfer_id$q"));
            $sql = "";
            if ($transfer_id == 0) {
                $sql = sprintf("INSERT INTO `%s`.`req_items`(`RequisitID`, `ProductID`, 
                    `ProductName`, `units`, `serials`) 
                    VALUES (%s,%s,%s,%s,%s)",
                           $_SESSION['DBCoy'],
                           $id,
                           GSQLStr(_xpost("ProductID$q"), "int"),
                           GSQLStr(_xpost("ProductName$q"), "text"),
                           GSQLStr(_xpost("units$q"), "double"),
                           GSQLStr(_xpost("serials$q"), "text"));
            } else {
                $sql = sprintf("UPDATE `%s`.`req_items` SET `units`=%s,`serials`=%s
                     WHERE `transfer_id`=%s",
                           $_SESSION['DBCoy'],
                           GSQLStr(_xpost("units$q"), "double"),
                           GSQLStr(_xpost("serials$q"), "text"),
                           $transfer_id);
            }
            runDBQry($dbh, $sql);
        }
        $delsub = _xpost('del_ids');
        $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`req_items` WHERE transfer_id IN ($delsub)";
        runDBQry($dbh, $sql);

        $sql = sprintf("UPDATE `%s`.`requisitions` SET `RequestedBy`=%s,
            `ShipName`=%s,`ShipAddress`=%s,`ShipDate`=%s,`ShippingMethodID`=%s,`FreightCharge`=%s,
            `expenses`=%s,`Notes`=%s WHERE `RequisitID`=%s AND Transfered=0",
                       $_SESSION['DBCoy'],
                       GSQLStr($_SESSION['ids']['VendorID'], "int"),
                       GSQLStr(_xpost('ShipName'), "text"),
                       GSQLStr(_xpost('ShipAddress'), "text"),
                       GSQLStr(_xpost('ShipDate'), "date"),
                       GSQLStr(_xpost('ShippingMethodID'), "int"),
                       GSQLStr(_xpost('FreightCharge'), "double"),
                       GSQLStr(_xpost('expenses'), "double"),
                       GSQLStr(_xpost('Notes'), "text"),
                       $id);
        runDBQry($dbh, $sql);
        
        if (mysqli_error($dbh)) {
            throw new Exception("Error in SQL");
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

$vendor_req = vendorFlds("ReqBy", "req_by");
$sql = "SELECT `requisitions`.*, Category, $vendor_req, 
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
    WHERE `RequisitID`={$id}";
$row_TTranfers = getDBDataRow($dbh, $sql);

$sql = "SELECT `req_items`.*, `ShopStock`, serialized, `outlet`.`serials` AS allserials
    FROM `{$_SESSION['DBCoy']}`.`req_items` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `req_items`.ProductID=`items_prod`.`ProductID` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlet` ON `req_items`.ProductID=outlet.ProductID 
        AND outlet.OutletID={$row_TTranfers['Outletout']}
    WHERE `RequisitID`={$id}";
$TItems = getDBData($dbh, $sql);

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
<script type="text/javascript">
    
    $(function() {
        $("#dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 350,
            modal: true,
            buttons: {
                "Ok": function() {
                    var itms = "";
                    $("#serials > option").each(function() {
                        itms += "," + this.text;
                    });
                    $("[name=serials"+itm+"]").val(itms.substr(2));
                    $("[name=units"+itm+"]").val($("#serials > option").length);
                    $(this).dialog("close");
                },
                Cancel: function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
            }
        });
    });
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="return vetProds()" method="post" enctype="multipart/form-data" name="frmtransfer" id="frmtransfer">
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
                                <td align="left"><input name="ShipName" type="text" value="<?php echo $row_TTranfers['ShipName'] ?>" size="32" /></td>
                                </tr>
                              <tr>
                                <td class="titles">Shipping Method:</td>
                                <td align="left"><select name="ShippingMethodID">
                                  <option value="0" <?php if (!(strcmp($row_TTranfers['ShippingMethodID'],"0"))) {echo "selected=\"selected\"";} ?>>Select One </option>
                                  <option value="1" <?php if (!(strcmp($row_TTranfers['ShippingMethodID'],"1"))) {echo "selected=\"selected\"";} ?>>Air Freight</option>
                                  <option value="2" <?php if (!(strcmp($row_TTranfers['ShippingMethodID'],"2"))) {echo "selected=\"selected\"";} ?>>Sea Freight</option>
                                  <option value="3" <?php if (!(strcmp($row_TTranfers['ShippingMethodID'],"3"))) {echo "selected=\"selected\"";} ?>>Parcel Service</option>
                                  <option value="4" <?php if (!(strcmp($row_TTranfers['ShippingMethodID'],"4"))) {echo "selected=\"selected\"";} ?>>Door Delivery</option>
                                  <option value="5" <?php if (!(strcmp($row_TTranfers['ShippingMethodID'],"5"))) {echo "selected=\"selected\"";} ?>>Others</option>
                                </select></td>
                                </tr>
                              <tr>
                                <td class="titles">Date of Shipping:</td>
                                <td align="left"><input name="ShipDate" type="text" id="ShipDate" value="<?php echo $row_TTranfers['ShipDate'] ?>" size="16" /></td>
                                </tr>
                              <tr>
                                <td valign="top" class="titles">Shipper Address:</td>
                                <td align="left"><textarea name="ShipAddress" style="width:300px" rows="3"><?php echo $row_TTranfers['ShipAddress'] ?></textarea></td>
                                </tr>
                              <tr>
                                <td class="titles">&nbsp;</td>
                                <td align="left">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="titles">Freight Charge:</td>
                                <td><input type="text" name="FreightCharge" value="<?php echo $row_TTranfers['FreightCharge'] ?>" size="12" /></td>
                              </tr>
                              <tr>
                                <td class="titles">Expenses:</td>
                                <td><input type="text" name="expenses" value="<?php echo $row_TTranfers['expenses'] ?>" size="12" /></td>
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
                      <textarea name="Notes" style="width:500px" rows="5"><?php echo $row_TTranfers['Notes'] ?></textarea>
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
                    <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="TabTransDet">
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000"><input type="hidden" name="del_ids" value="0" /></td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Units</td>
                        </tr>
                        <?php $j = 0;foreach ($TItems as $row_TItems) { ?>
                      <tr id="RetDet<?php echo $j ?>">
                        <td align="center"><a href="javascript: void(0)" onclick="removeProd(<?php echo $j ?>)"><img src="/images/delete.png" width="16" height="16" /></a>
                          <input type="hidden" name="transfer_id<?php echo $j ?>" id="transfer_id<?php echo $j ?>" value="<?php echo $row_TItems['transfer_id']; ?>" />
                          <input type="hidden" name="ProductID<?php echo $j ?>" id="ProductID<?php echo $j ?>" value="<?php echo $row_TItems['ProductID']; ?>" />
                          <input type="hidden" name="ShopStock<?php echo $j ?>" value="<?php echo $row_TItems['ShopStock']; ?>" />
                          <input type="hidden" name="allserials<?php echo $j ?>" value="<?php echo $row_TItems['allserials']; ?>" />
                          <input type="hidden" name="serialized<?php echo $j ?>" value="<?php echo $row_TItems['serialized']; ?>" />
                          <input type="hidden" name="serials<?php echo $j ?>" id="serials<?php echo $j ?>" value="<?php echo $row_TItems['serials']; ?>" /></td>
                        <td align="center"><?php echo $row_TItems['ProductID'] ?></td>
                        <td align="center" id="ProductName<?php echo $j ?>"><strong><?php echo $row_TItems['ProductName'] ?></strong></td>
                        <td align="center"><input type="text" name="units<?php echo $j ?>" id="units<?php echo $j ?>" value="<?php echo $row_TItems['units'] ?>" <?php if ($row_TItems['serialized'] == 1) { ?>readonly="readonly" onkeydown="serials(<?php echo $j ?>)" onclick="serials(<?php echo $j ?>)" <?php } else { ?> onChange="setthous(this, 1); vetProds()"<?php } ?> style="width:40px" /></td>
                        </tr>
                        <?php $j++; } ?>
                      </table>
                      <input name="TransDetID" type="hidden" id="TransDetID" value="<?php echo $j ?>" />
                      <script>var TransDetID=<?php echo $j ?> </script></td>
                  </tr>
                  <tr>
                    <td height="28" colspan="2" align="center"><a href="javascript: void(0)" onclick="GB_showCenter('Order Items', '/stock/transfers/pick.php?outid=<?php echo $row_TTranfers['Outletout'] ?>', 600,600)"><img src="/images/but_add.png" width="50" height="20" /></a>
                      <div id="dialog-form" title="Edit Item Serial No.s">
                        <table border="0" cellspacing="2" cellpadding="2">
                          <tr>
                            <td><select name="serials" size="12" id="serials">
                            </select></td>
                            <td valign="top"><table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td align="center"><input id="serialno" name="serialno" type="text" style="width:100px" /></td>
                              </tr>
                              <tr>
                                <td align="center"><a id="addserial" href="javascript: void(0)" onclick="addSerial()"><img src="/images/but_add.png" width="50" height="20" /></a></td>
                              </tr>
                              <tr>
                                <td align="center"><a id="delserial" href="javascript: void(0)" onclick="delSerial()"><img src="/images/but_mini_del.png" width="34" height="20" /></a></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table>
                      </div></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="frmtransfer" />
            <input type="hidden" name="RequisitID" value="<?php echo $row_TTranfers['RequisitID']; ?>" />
          </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>            </tr>
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
