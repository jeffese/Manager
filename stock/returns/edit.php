<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Returns'));
vetAccess('Stock', 'Returns', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmreturn","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Stock'.DS.'Returns';
$doc_id = $id;

if (_xpost("MM_update") == "frmreturn") {
    try {
        $dbh->autocommit(FALSE);

        $OrdDetID = intval(_xpost('OrdDetID'));
        $tot = 0;
        for ($q = 0; $q < $OrdDetID; $q++) {
            if (!isset($_POST["OrderDetailID$q"]))
                continue;
            $OrderRetDetID = intval(_xpost("OrderRetDetID$q"));
            if ($OrderRetDetID == 0) {
                $sql = sprintf("INSERT INTO `%s`.`orderreturndet`(`OrderDetailID`, `OrderRetID`, `units`, `serials`) 
                    VALUES (%s,%s,%s,%s)",
                           $_SESSION['DBCoy'],
                           GSQLStr(_xpost("OrderDetailID$q"), "int"),
                           $id,
                           GSQLStr(_xpost("units$q"), "double"),
                           GSQLStr(_xpost("serials$q"), "text"));
            } else {
                $sql = sprintf("UPDATE `%s`.`orderreturndet` SET `units`=%s,`serials`=%s
                     WHERE `OrderRetDetID`=%s",
                           $_SESSION['DBCoy'],
                           GSQLStr(_xpost("units$q"), "double"),
                           GSQLStr(_xpost("serials$q"), "text"),
                           $OrderRetDetID);
            }
            runDBQry($dbh, $sql);
        }
        $delsub = _xpost('del_ids');
        $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`orderreturndet` WHERE OrderRetDetID IN ($delsub)";
        runDBQry($dbh, $sql);

        $sql = sprintf("UPDATE `%s`.`orderreturns` SET `EmployeeID`=%s,`ReturnDate`=%s,
            `ShipName`=%s,`ShipAddress`=%s,`ShipDate`=%s,`ShippingMethodID`=%s,`FreightCharge`=%s,
            `expenses`=%s,`TotalValue`=%s,`Notes`=%s WHERE `OrderRetID`=%s AND Posted=0",
                       $_SESSION['DBCoy'],
                       GSQLStr($_SESSION['ids']['VendorID'], "int"),
                       GSQLStr(_xpost('ReturnDate'), "date"),
                       GSQLStr(_xpost('ShipName'), "text"),
                       GSQLStr(_xpost('ShipAddress'), "text"),
                       GSQLStr(_xpost('ShipDate'), "date"),
                       GSQLStr(_xpost('ShippingMethodID'), "int"),
                       GSQLStr(_xpost('FreightCharge'), "double"),
                       GSQLStr(_xpost('Expenses'), "double"),
                       GSQLStr(_xpost('TotalValue'), "double"),
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

$vendor_supo = vendorFlds("VendorSup", "sup");
$sql = "SELECT `orderreturns`.*, ExchangeFrom, ExchangeTo, `currencies`.code AS cur, 
    `currencies`.currencyname, `currencies`.unitname, coycur.code AS coycod, $vendor_sql, $vendor_supo 
    FROM `{$_SESSION['DBCoy']}`.`orderreturns` 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `orderreturns`.EmployeeID=`vendors`.VendorID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`orders` ON `orderreturns`.OrderID = orders.OrderID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` `VendorSup` ON `orders`.SupplierID = VendorSup.VendorID
    LEFT JOIN `{$_SESSION['DBCoy']}`.`currencies`   ON `orders`.Currency=currencies.cur_id
    LEFT JOIN `{$_SESSION['DBCoy']}`.`currencies` coycur  ON `orders`.ShopCurrency=coycur.cur_id
    WHERE `OrderRetID`={$id}";
$row_TReturns = getDBDataRow($dbh, $sql);
$_SESSION['rets_ordid'] = $row_TReturns['OrderID'];

$sql = "SELECT `orderreturndet`.*, `orderdetails`.ProductName, `QtyinStock`, serialized, `orderdetails`.`unitsinpack`, 
    `orderdetails`.`serials` AS allserials, `orderdetails`.UnitPrice/`Quantity` AS `SalePrice`, `units`/`orderdetails`.`unitsinpack` AS `qty` 
    FROM `{$_SESSION['DBCoy']}`.`orderreturndet` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `orderreturndet`.OrderDetailID=orderdetails.OrderDetailID
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `orderdetails`.ProductID=`items_prod`.`ProductID` 
    WHERE `OrderRetID`={$id}";
$TOrderRetDets = getDBData($dbh, $sql);

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
    var arrFormValidation=[
        ["ReturnDate", "", 
            ["req", "Select Return Date"]
        ]
    ]
    
    var mCal, curr="<?php echo $row_TReturns['currencyname'] ?>", curunit="<?php echo $row_TReturns['unitname'] ?>";
    window.onload = function() {
        mCal = new dhtmlxCalendarObject('ReturnDate', true, {
            isYearEditable: true, 
            isMonthEditable: true
        });
        mCal.setSkin('dhx_black');
        toWords('<?php echo $row_TReturns['TotalValue'] ?>');
    }
    
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
                    calProd(itm)
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
        <td width="240" valign="top"><img src="/images/returns.jpg" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblreturns.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return vetProds() && validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmreturn" id="frmreturn">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td class="red-normal"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Return ID:</td>
                    <td class="red-normal"><b><?php echo $row_TReturns['OrderRetID']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Order ID:</td>
                    <td class="red-normal"><b><?php echo $row_TReturns['OrderID']; ?></b></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Supplier:</td>
                    <td align="left"><?php echo $row_TReturns['sup'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Staff:</td>
                    <td><?php echo $row_TReturns['VendorName'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Return Date:</td>
                    <td align="left"><input name="ReturnDate" type="text" id="ReturnDate" value="<?php echo $row_TReturns['ReturnDate'] ?>" size="16" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Exchange Rate:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td id="xfrom"><?php echo $row_TReturns['cur'] ?></td>
                        <td><?php echo $row_TReturns['ExchangeFrom'] ?></td>
                        <td><strong>=&gt;</strong></td>
                        <td><?php echo $row_TReturns['coycod'] ?></td>
                        <td><?php echo $row_TReturns['ExchangeTo'] ?></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Total Value:</td>
                    <td align="left"><table border="0" cellspacing="1" cellpadding="1">
                      <tr>
                        <td><?php echo $row_TReturns['cur'] ?></td>
                        <td><input name="TotalValue" type="text" value="<?php echo $row_TReturns['TotalValue'] ?>" size="32" readonly="readonly" /></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Posted:</td>
                    <td><input type="checkbox" name="Posted"<?php if ($row_TReturns['Posted'] == 1) {
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
                                <td align="left"><input name="ShipName" type="text" value="<?php echo $row_TReturns['ShipName'] ?>" size="32" /></td>
                                </tr>
                              <tr>
                                <td class="titles">Shipping Method:</td>
                                <td align="left"><select name="ShippingMethodID">
                                  <option value="0" <?php if (!(strcmp($row_TReturns['ShippingMethodID'],"0"))) {echo "selected=\"selected\"";} ?>>Select One </option>
                                  <option value="1" <?php if (!(strcmp($row_TReturns['ShippingMethodID'],"1"))) {echo "selected=\"selected\"";} ?>>Air Freight</option>
                                  <option value="2" <?php if (!(strcmp($row_TReturns['ShippingMethodID'],"2"))) {echo "selected=\"selected\"";} ?>>Sea Freight</option>
                                  <option value="3" <?php if (!(strcmp($row_TReturns['ShippingMethodID'],"3"))) {echo "selected=\"selected\"";} ?>>Parcel Service</option>
                                  <option value="4" <?php if (!(strcmp($row_TReturns['ShippingMethodID'],"4"))) {echo "selected=\"selected\"";} ?>>Door Delivery</option>
                                  <option value="5" <?php if (!(strcmp($row_TReturns['ShippingMethodID'],"5"))) {echo "selected=\"selected\"";} ?>>Others</option>
                                </select></td>
                                </tr>
                              <tr>
                                <td class="titles">Date of Shipping:</td>
                                <td align="left"><input name="ShipDate" type="text" id="ShipDate" value="<?php echo $row_TReturns['ShipDate'] ?>" size="16" /></td>
                                </tr>
                              <tr>
                                <td valign="top" class="titles">Shipper Address:</td>
                                <td align="left"><textarea name="ShipAddress" style="width:300px" rows="3"><?php echo $row_TReturns['ShipAddress'] ?></textarea></td>
                                </tr>
                              <tr>
                                <td class="titles">&nbsp;</td>
                                <td align="left">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="titles">Freight Charge:</td>
                                <td><input type="text" name="FreightCharge" value="<?php echo $row_TReturns['FreightCharge'] ?>" size="12" /></td>
                              </tr>
                              <tr>
                                <td class="titles">Expenses:</td>
                                <td><input type="text" name="Expenses" value="<?php echo $row_TReturns['Expenses'] ?>" size="12" /></td>
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
                      <textarea name="Notes" style="width:500px" rows="5"><?php echo $row_TReturns['Notes'] ?></textarea>
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
                    <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="TabOrderdet">
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000"><input type="hidden" name="del_ids" value="0" /></td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Units</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Sales Price</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Line Total</td>
                        </tr>
                        <?php $j = 0;foreach ($TOrderRetDets as $row_TOrderRetDets) { ?>
                      <tr id="RetDet<?php echo $j ?>">
                        <td><a href="javascript: void(0)" onclick="removeProd(<?php echo $j ?>)"><img src="/images/delete.png" width="16" height="16" /></a>
                          <input type="hidden" name="OrderRetDetID<?php echo $j ?>" id="OrderRetDetID<?php echo $j ?>" value="<?php echo $row_TOrderRetDets['OrderRetDetID']; ?>" />
                          <input type="hidden" name="OrderDetailID<?php echo $j ?>" id="OrderDetailID<?php echo $j ?>" value="<?php echo $row_TOrderRetDets['OrderDetailID']; ?>" />
                          <input type="hidden" name="QtyinStock<?php echo $j ?>" value="<?php echo $row_TOrderRetDets['QtyinStock']; ?>" />
                          <input type="hidden" name="unitsinpack<?php echo $j ?>" value="<?php echo $row_TOrderRetDets['unitsinpack']; ?>" />
                          <input type="hidden" name="SalePrice<?php echo $j ?>" value="<?php echo $row_TOrderRetDets['SalePrice']; ?>" />
                          <input type="hidden" name="allserials<?php echo $j ?>" value="<?php echo $row_TOrderRetDets['allserials']; ?>" />
                          <input type="hidden" name="serialized<?php echo $j ?>" value="<?php echo $row_TOrderRetDets['serialized']; ?>" />
                          <input type="hidden" name="serials<?php echo $j ?>" id="serials<?php echo $j ?>" value="<?php echo $row_TOrderRetDets['serials']; ?>" /></td>
                        <td><?php echo $row_TOrderRetDets['OrderDetailID'] ?></td>
                        <td id="ProductName<?php echo $j ?>"><strong><?php echo $row_TOrderRetDets['ProductName'] ?></strong></td>
                        <td id="Qty"><?php echo $row_TOrderRetDets['qty'] ?></td>
                        <td><input type="text" name="units<?php echo $j ?>" id="units<?php echo $j ?>" value="<?php echo $row_TOrderRetDets['units'] ?>" <?php if ($row_TOrderRetDets['serialized'] == 1) { ?>readonly="readonly" onkeydown="serials(<?php echo $j ?>)" onclick="serials(<?php echo $j ?>)" <?php } else { ?> onChange="setthous(this, 1); if (vetProds()) calProd(<?php echo $j ?>)"<?php } ?> style="width:40px" /></td>
                        <td id="SalePrice<?php echo $j ?>"><?php echo $row_TOrderRetDets['SalePrice'] ?></td>
                        <td id="linetotal<?php echo $j ?>"><?php echo $row_TOrderRetDets['SalePrice']*$row_TOrderRetDets['units'] ?></td>
                        </tr>
                        <?php $j++; } ?>
                      </table>
                      <input name="OrdDetID" type="hidden" id="OrdDetID" value="<?php echo $j ?>" />
                      <script>var OrdDetID=<?php echo $j ?> </script></td>
                  </tr>
                  <tr>
                    <td height="28" colspan="2" align="center"><a href="javascript: void(0)" onclick="GB_showCenter('Order Items', '/stock/orders/items.php', 600,600)"><img src="/images/but_add.png" width="50" height="20" /></a>
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
            <input type="hidden" name="MM_update" value="frmreturn" />
            <input type="hidden" name="OrderRetID" value="<?php echo $row_TReturns['OrderRetID']; ?>" />
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
