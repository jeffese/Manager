<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Returns'));
vetAccess('Stock', 'Returns', 'View');

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Order Return]del.php?id=$id","","","find.php","print.php?id=$id","index.php");
$rec_status = 1;

if (_xpost("MM_Post") == "frmpost") {

    $outid = GSQLStr(_xpost("outlet"), "int");

    $sql = "FROM `{$_SESSION['DBCoy']}`.`orderreturndet` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `orderreturndet`.OrderDetailID=orderdetails.OrderDetailID
        INNER JOIN `{$_SESSION['DBCoy']}`.`outlet` ON `orderdetails`.ProductID=outlet.ProductID 
            AND outlet.OutletID=$outid
        WHERE `OrderRetID`={$id} AND (`QtyinStock`>=`units` AND `ShopStock`>=`units`)";
    $errCnt = getDBDatacnt($dbh, $sql);

    if ($errCnt == 1) {
        try {
            $dbh->autocommit(FALSE);
            runDBQry($dbh, "SELECT * FROM `{$_SESSION['DBCoy']}`.`orderreturndet` 
                INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `orderreturndet`.OrderDetailID=orderdetails.OrderDetailID
                INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`       ON `orderdetails`.ProductID=outlet.ProductID 
                    AND outlet.OutletID=$outid
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`   ON `orderdetails`.ProductID=`items_prod`.`ProductID` 
                INNER JOIN `{$_SESSION['DBCoy']}`.`orderreturns` ON `orderreturndet`.OrderRetID=`orderreturns`.OrderRetID 
                INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`      ON `invoices`.VendorID=`vendors`.VendorID 
                WHERE `orderreturndet`.`OrderRetID`=$id LOCK IN SHARE MODE");

            $sql = "SELECT OrderRetDetID, `units`, `orderdetails`.ProductName,
                `orderreturndet`.serials, `outlet`.serials AS outserials, `orderdetails`.`serials` AS ordserials
                FROM `{$_SESSION['DBCoy']}`.`orderreturndet` 
                INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `orderreturndet`.OrderDetailID=orderdetails.OrderDetailID
                INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`       ON `orderdetails`.ProductID=outlet.ProductID 
                    AND outlet.OutletID=$outid
                INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`   ON `orderdetails`.ProductID=`items_prod`.`ProductID` 
                WHERE `OrderRetID`={$id} AND serialized=1";
            $TSerials = getDBData($dbh, $sql);

            $x = 0;
            for (; $x < count($TSerials); $x++) {
                $return = _xplode(",", $TSerials['serials']);
                $outlet = _xplode(",", $TSerials['outserials']);
                $orders = _xplode(",", $TSerials['ordserials']);
                if (count($return) != $TSerials['units']) {
                    array_push($errors, array("Serials Verification", "The Serials for '{$TSerials['ProductName']}' are incomplete!"));
                    break;
                }
                foreach ($return as $itm) {
                    if (!in_array($itm, $outlet)) {
                        array_push($errors, array("Serials Verification", "The Serial '$itm' is not in this Storage!"));
                        break 2;
                    }
                    if (!in_array($itm, $orders)) {
                        array_push($errors, array("Serials Verification", "The Serial $itm is not part of this order!"));
                        break 2;
                    }
                    $TSerials['_outserials'] = implode(',', array_diff($outlet, $return));
                    $TSerials['_ordserials'] = implode(',', array_diff($orders, $return));
                }
            }
            $update = 0;
            if ($x == count($TSerials)) {
                for ($x = 0; $x < count($TSerials); $x++) {
                    $sql = "UPDATE `{$_SESSION['DBCoy']}`.`orderreturndet` 
                            INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `orderreturndet`.OrderDetailID=orderdetails.OrderDetailID
                            INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`       ON `orderdetails`.ProductID=outlet.ProductID 
                                AND outlet.OutletID=$outid
                            SET `outlet`.serials = '{$TSerials[$x]['_outserials']}', 
                                `orderdetails`.`serials` = '{$TSerials[$x]['_ordserials']}'
                            WHERE `OrderRetDetID`={$TSerials[$x]['OrderRetDetID']}";
                    runDBQry($dbh, $sql);
                }

                $sql = "UPDATE `{$_SESSION['DBCoy']}`.`orderreturndet` 
                        INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `orderreturndet`.OrderDetailID=orderdetails.OrderDetailID
                        INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`       ON `orderdetails`.ProductID=outlet.ProductID 
                            AND outlet.OutletID=$outid
                        SET `QtyinStock`=`QtyinStock`-`units`, `ShopStock`=`ShopStock`-`units`
                        WHERE `OrderRetID`={$id}";
                runDBQry($dbh, $sql);

                $sql = "UPDATE `{$_SESSION['DBCoy']}`.`orderreturns` 
                        SET `Posted`=1
                        WHERE `OrderRetID`={$id}";
                $update = runDBQry($dbh, $sql);
            }
            if ($update != 1 || mysqli_error($dbh)) {
                throw new Exception("Error in SQL");
            }
            $dbh->commit();
        } catch (Exception $ex) {
            $dbh->rollback();
            array_push($errors, array("Error", $ex->getMessage()));
        }
        $dbh->autocommit(TRUE);
    } else
        array_push($errors, array("Stock Verification", "The stock in Storage is less than that to be returned!"));
}
$vendor_stf = vendorFlds("emp", "staff");
$vendor_sup = vendorFlds("vend", "sup");

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

$sql = "SELECT `orderreturndet`.*, `orderdetails`.ProductName, 
    `orderdetails`.UnitPrice/`Quantity` AS `SalePrice`, `units`/`orderdetails`.`unitsinpack` AS `qty` 
    FROM `{$_SESSION['DBCoy']}`.`orderreturndet` 
    INNER JOIN `{$_SESSION['DBCoy']}`.`orderdetails` ON `orderreturndet`.OrderDetailID=orderdetails.OrderDetailID
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `orderdetails`.ProductID=`items_prod`.`ProductID` 
    WHERE `OrderRetID`={$id}";
$TOrderRetDets = getDBData($dbh, $sql);

$sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets` ORDER BY OutletName";
$TOutlets = getDBData($dbh, $sql);

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, AccStat($access['Edit'], $row_TReturns['Posted']), AccStat($access['Del'], $row_TReturns['Posted']), $access['Print'], 0, 1);
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
<script language="JavaScript1.2" type="text/javascript">
    window.onload = function() {
        $("#ordwords").html(NumToWords('<?php echo $row_TReturns['TotalValue'] ?>', 
        "<?php echo $row_TReturns['currencyname'] ?>", "<?php echo $row_TReturns['unitname'] ?>"));
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
                    <td align="left"><?php echo $row_TReturns['ReturnDate'] ?></td>
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
                        <td><?php echo $row_TReturns['TotalValue'] ?></td>
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
                                <td align="left">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="titles">Shipping Method:</td>
                                <td align="left"><script language="javascript" type="text/javascript">
                                switch (<?php echo $row_TReturns['ShippingMethodID']; ?>) {
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
                                <td align="left"><?php echo $row_TReturns['ShipDate'] ?></td>
                              </tr>
                              <tr>
                                <td valign="top" class="titles">Shipper Address:</td>
                                <td align="left"><textarea name="ShipAddress" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TReturns['ShipAddress'] ?></textarea></td>
                              </tr>
                              <tr>
                                <td class="titles">&nbsp;</td>
                                <td align="left">&nbsp;</td>
                              </tr>
                              <tr>
                                <td class="titles">Freight Charge:</td>
                                <td><?php echo $row_TReturns['FreightCharge'] ?></td>
                              </tr>
                              <tr>
                                <td class="titles">Expenses:</td>
                                <td><?php echo $row_TReturns['Expenses'] ?></td>
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
                    <td><textarea name="Notes" rows="5" readonly="readonly" style="width:500px"><?php echo $row_TReturns['Notes'] ?></textarea>                    </td>
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
                            <td><?php $doc_shelf = 'Stock'.DS.'Returns';
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
                    <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="TabOrderdet">
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Units</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Sales Price</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Line Total</td>
                        </tr>
                      <?php $j = 0;foreach ($TOrderRetDets as $row_TOrderRetDets) { ?>
                      <tr id="RetDet<?php echo $j ?>">
                        <td><?php echo $row_TOrderRetDets['OrderDetailID'] ?></td>
                        <td id="ProductName<?php echo $j ?>"><strong><?php echo $row_TOrderRetDets['ProductName'] ?></strong></td>
                        <td id="Qty"><?php echo $row_TOrderRetDets['qty'] ?></td>
                        <td><?php echo $row_TOrderRetDets['units'] ?></td>
                        <td id="SalePrice<?php echo $j ?>"><?php echo $row_TOrderRetDets['SalePrice'] ?></td>
                        <td id="linetotal<?php echo $j ?>"><?php echo $row_TOrderRetDets['SalePrice']*$row_TOrderRetDets['units'] ?></td>
                        </tr>
                      <?php $j++; } ?>
                      </table>
                      <script>var OrdDetID=<?php echo $j ?> </script></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center"><?php if ($access['Return'] == 1 && $row_TReturns['Posted'] == 0) { ?>
                      <table border="0" cellspacing="1" cellpadding="1">
                        <tr>
                          <td><a id="post" href="javascript: void(0)" onclick="ordPost()"><img src="/images/but_return.png" width="60" height="20" /></a></td>
                          <td class="titles">From</td>
                          <td>
                    <form id="frmpost" name="frmpost" method="post" action="">
                      <input type="hidden" name="MM_Post" value="frmpost" />
                      <input name="OrdDetID" type="hidden" id="OrdDetID" value="<?php echo $j ?>" />
                      <select name="outlet">
                            <?php foreach ($TOutlets as $row_TOutlets) { ?>
                            <option value="<?php echo $row_TOutlets['OutletID'] ?>" <?php if (!(strcmp(1, $row_TOutlets['OutletID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TOutlets['OutletName'] ?></option>
                            <?php } ?>
                          </select>
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
