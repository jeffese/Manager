<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Sales'));
vetAccess('Accounts', 'Sales', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmsales","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Accounts'.DS.'Sales';
$doc_id = $id;

if (_xpost("MM_update") == "frmsales") {
    try {
        $dbh->autocommit(FALSE);

        $ItmID = intval(_xpost('ItmID'));
        for ($q = 0; $q < $ItmID; $q++) {
            if (!isset($_POST["ProductID_$q"]))
                continue;
            $invdetID = intval(_xpost("InvoiceDetailID_$q"));
            if ($invdetID == 0) {
                $sql = sprintf("INSERT INTO `%s`.`invoicedetails`( `InvoiceID`, `ProductID`, `ProductName`, 
                    `serials`, `units`, `UnitPrice`, `Discount`, `Discnt`, `TaxRate`, `LineTotal`) 
                    VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                            $_SESSION['DBCoy'],
                            $id,
                            GSQLStr(_xpost("ProductID_$q"), "int"),
                            GSQLStr(_xpost("ProductName_$q"), "text"),
                            GSQLStr(_xpost("serials_$q"), "text"),
                            GSQLStr(_xpost("units_$q"), "double"),
                            GSQLStr(_xpost("UnitPrice_$q"), "double"),
                            GSQLStr(_xpost("Discount_$q"), "double"),
                            GSQLStr(_xpost("Discnt_$q"), "double"),
                            GSQLStr(_xpost("TaxRate_$q"), "double"),
                            GSQLStr(_xpost("LineTotal_$q"), "double"));
            } else {
                $sql = sprintf("UPDATE `%s`.`invoicedetails` SET `serials`=%s,`units`=%s,
                    `Discount`=%s,`Discnt`=%s,`TaxRate`=%s,`LineTotal`=%s
                     WHERE `InvoiceDetailID`=%s",
                            $_SESSION['DBCoy'],
                            GSQLStr(_xpost("serials_$q"), "text"),
                            GSQLStr(_xpost("units_$q"), "double"),
                            GSQLStr(_xpost("Discount_$q"), "double"),
                            GSQLStr(_xpost("Discnt_$q"), "double"),
                            GSQLStr(_xpost("TaxRate_$q"), "double"),
                            GSQLStr(_xpost("LineTotal_$q"), "double"),
                            $invdetID);
            }
            runDBQry($dbh, $sql);
        }
        $delsub = _xpost('del');
        $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`invoicedetails` WHERE InvoiceDetailID IN ($delsub)";
        runDBQry($dbh, $sql);

        $sql = sprintf("UPDATE `%s`.`invoices` SET `EmployeeID`=%s,
            `TotalValue`=%s,`Grandvalue`=%s,`Notes`=%s,`LedgerDate`=NOW() 
            WHERE `InvoiceID`=%s",
                    $_SESSION['DBCoy'],
                    GSQLStr($_SESSION['EmployeeID'], "int"),
                    GSQLStr(_xpost('TotalValue'), "double"),
                    GSQLStr(_xpost('Grandvalue'), "double"),
                    GSQLStr(_xpost('Notes'), "text"),
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

$outid = _xses('OutletID');
$sql = "SELECT `invoices`.*, OutletName, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`invoices` 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `invoices`.EmployeeID=`vendors`.VendorID
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlets` ON `invoices`.OutletID=`outlets`.OutletID 
    WHERE `InvoiceID`=$id AND `invoices`.OutletID IN ($outid)";
$row_TSales = getDBDataRow($dbh, $sql);

$sql = "SELECT `invoicedetails`.*, serialized, `outlet`.`serials` AS allserials, ShopStock
    FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices` ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`items_prod` ON `invoicedetails`.ProductID=`items_prod`.`ProductID`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`outlet` ON 
        (`invoicedetails`.ProductID=`outlet`.ProductID AND `invoices`.OutletID=`outlet`.OutletID)
    LEFT JOIN `{$_SESSION['DBCoy']}`.`items_srv` ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    WHERE `invoices`.`InvoiceID`=$id";
$TItems = getDBData($dbh, $sql);

$TStatus  = getCat('AccStatus');
$TCat = getClassify(7);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
<link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
<script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
<script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
<script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
<link rel="stylesheet" href="/lib/jquery-ui/css/smoothness/jquery-ui.css">
<script src="/lib/jquery-ui/js/jquery.js"></script>
<script src="/lib/jquery-ui/js/jquery-ui.js"></script>
<script type="text/javascript" src="script.js"></script>
<script type="text/javascript" src="/scripts/js/set.js"></script>
<script type="text/javascript"> 
<!--
window.onload = function() {
	setContent();
        calItms();
}
window.onresize = function() {
	setContent();
}

//--> 
</script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["Grandvalue", "", 
            ["req", "Invoice value must be greater than 0"]
        ]
    ];
    
    var  coycur = <?php echo $_SESSION['COY']['currency']; ?>;

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
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><img src="/images/sales.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblsales.png); background-repeat:no-repeat">&nbsp;</td>
            </tr>
          <tr>
            <td class="h1" height="5px"></td>
            </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
            </tr>
          </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return vetProds() && validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmsales" id="frmsales">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Invoice ID:</td>
                    <td align="left" class="red-normal"><b><?php echo $row_TSales['InvoiceID']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Total Value:</td>
                    <td align="left"><input name="Grandvalue" type="text" style="width:120px" value="<?php echo $row_TSales['Grandvalue'] ?>" readonly="readonly" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Staff:</td>
                    <td align="left"><?php echo $row_TSales['VendorName'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td align="left"><textarea name="Notes" style="width:450px" rows="3"><?php echo $row_TSales['Notes'] ?></textarea></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td align="left"><table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
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
                            <td><?php include '../../scripts/editdoc.php' ?></td>
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
                    <td colspan="2"><input type="hidden" name="TaxRate" value="<?php echo $row_TSales['TaxRate'] ?>" style="width:30px" />
                      <input name="TotTax" type="hidden" value="<?php echo $row_TSales['TotTax'] ?>" size="12" readonly="readonly" />
                      <input type="hidden" name="Dscnt" value="<?php echo $row_TSales['Dscnt'] ?>" onchange="dsc()" style="width:30px" />
                      <input type="hidden" name="Discount" value="<?php echo $row_TSales['Discount'] ?>" onchange="disc()" size="12" />
                      <input name="TotalValue" type="hidden" style="width:80px" value="<?php echo $row_TSales['TotalValue'] ?>" readonly="readonly" />
                      <input name="TotDisc" type="hidden" value="<?php echo $row_TSales['TotDisc'] ?>" size="12" readonly="readonly" /></td>
                    </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="Tabdet">
                      <tr class="boldwhite1">
                        <td colspan="10" align="center" nowrap="nowrap" bgcolor="#000000" class="h1">Items</td>
                        </tr>
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000"><input type="hidden" name="del" id="del" value="0" /></td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Unit Price</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">% Dsc</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Discount</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Tax</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Sales Price</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Total Value</td>
                        </tr>
                      <?php $j = 0;foreach ($TItems as $row_TItems) { ?>
                      <tr id="itm_<?php echo $j ?>">
                        <td><input type="hidden" name="InvoiceDetailID_<?php echo $j ?>" id="InvoiceDetailID_<?php echo $j ?>" value="<?php echo $row_TItems['InvoiceDetailID']; ?>" />
                          <input type="hidden" name="ProductID_<?php echo $j ?>" id="ProductID_<?php echo $j ?>" value="<?php echo $row_TItems['ProductID']; ?>" />
                          <input type="hidden" name="ProductName_<?php echo $j ?>" id="ProductName_<?php echo $j ?>" value="<?php echo $row_TItems['ProductName']; ?>" />
                          <input type="hidden" name="ShopStock_<?php echo $j ?>" id="ShopStock_<?php echo $j ?>" value="<?php echo $row_TItems['ShopStock']; ?>" />
                          <input type="hidden" name="UnitPrice_<?php echo $j ?>" value="<?php echo $row_TItems['UnitPrice']; ?>" />
                          <input type="hidden" name="LineTotal_<?php echo $j ?>" value="<?php echo $row_TItems['LineTotal']; ?>" />
                          <input type="hidden" name="allserials_<?php echo $j ?>" id="allserials_<?php echo $j ?>" value="<?php echo $row_TItems['allserials']; ?>" />
                          <input type="hidden" name="serials_<?php echo $j ?>" id="serials_<?php echo $j ?>" value="<?php echo $row_TItems['serials']; ?>" />
                          <input type="hidden" name="serialized_<?php echo $j ?>" id="serialized_<?php echo $j ?>" value="<?php echo $row_TItems['serialized']; ?>" /></td>
                        <td><?php echo $row_TItems['InvoiceDetailID'] ?></td>
                        <td id="Name_<?php echo $j ?>"><?php echo $row_TItems['ProductName'] ?></td>
                        <td><input type="text" name="units_<?php echo $j ?>" id="units_<?php echo $j ?>" value="<?php echo $row_TItems['units'] ?>" <?php if ($row_TItems['serialized'] == 1) { ?>readonly="readonly" onkeydown="serials(<?php echo $j ?>)" onclick="serials(<?php echo $j ?>)" <?php } else { ?> onChange="setthous(this, 1); if (vetProds()) calItm(<?php echo $j ?>)"<?php } ?> style="width:20px" readonly="readonly" /></td>
                        <td id="UnitPrice_<?php echo $j ?>"><?php echo $row_TItems['UnitPrice'] ?></td>
                        <td><input type="text" name="Discnt_<?php echo $j ?>" id="Discnt_<?php echo $j ?>" value="<?php echo $row_TItems['Discnt'] ?>" onchange="setthous(this, 0); dscItm(<?php echo $j ?>)" style="width:30px" readonly="readonly" /></td>
                        <td><input type="text" name="Discount_<?php echo $j ?>" id="Discount_<?php echo $j ?>" value="<?php echo $row_TItems['Discount'] ?>" onchange="setthous(this, 0); discItm(<?php echo $j ?>)" style="width:40px" readonly="readonly" /></td>
                        <td><input type="text" name="TaxRate_<?php echo $j ?>" id="TaxRate_<?php echo $j ?>" value="<?php echo $row_TItems['TaxRate'] ?>" onchange="setthous(this, 0); calItm(<?php echo $j ?>)" style="width:20px" readonly="readonly" /></td>
                        <td id="SalePrice_<?php echo $j ?>"></td>
                        <td id="LineTotal_<?php echo $j ?>"></td>
                        </tr>
                      <?php $j++; } ?>
                      </table>
                      <input name="ItmID" type="hidden" id="ItmID" value="<?php echo $j ?>" />
                      <script>var ItmID=<?php echo $j ?> </script></td>
                    </tr>
                  <tr>
                    <td height="28" colspan="2" align="center"><a href="javascript: void(0)" onclick="GB_showCenter('Product List', '/accounts/sales/pick.php?outid=<?php echo $row_TSales['OutletID'] ?>', 600,600)"><img src="/images/but_add.png" width="50" height="20" /></a></td>
                    </tr>
                  </table></td>
                </tr>
              </table>
            <input type="hidden" name="MM_update" value="frmsales" />
            <input type="hidden" name="InvoiceID" value="<?php echo $row_TSales['InvoiceID']; ?>" />
            </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>            </tr>
          </table></td>
      </tr>
    </table></td>
  </tr>
</table></td>
      <td class="frameright">&nbsp;</td>
    </tr>
    <tr>
      <td class="framebotleft">&nbsp;</td>
      <td align="center" valign="bottom" class="framebot"><span class="greytxt">Copyright © 2010 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>
</html>
