<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Sales'));
vetAccess('Accounts', 'Sales', 'Add');

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

$outid = intval(_xses('OutletID'));
if (_xpost("MM_insert") == "frmsales" && $outid > 0) {
  
    $sql = sprintf("INSERT INTO `%s`.`invoices`(`OutletID`, `AccountID`, `EmployeeID`, `VendorType`, 
        `VendorID`, `CustomerName`, `InvoiceDate`, `InvoiceType`, `Status`, `TaxRate`, `Dscnt`, 
        `Discount`, `TotTax`, `TotDisc`, `TotalValue`, `Grandvalue`, `ExchangeFrom`, `ExchangeTo`, 
        `Notes`, `ShipTo`, `LedgerDate`) 
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,NOW())",
                $_SESSION['DBCoy'],
                $outid,
                $_SESSION['COY']['CashAccount'],
                GSQLStr($_SESSION['EmployeeID'], "int"),
                1,
                $_SESSION['custid'],
                GSQLStr(_xpost('CustomerName'), "text"),
                'NOW()',
                GSQLStr(_xpost('InvoiceType'), "int"),
                GSQLStr(_xpost('Status'), "int"),
                GSQLStr(_xpost('TaxRate'), "double"),
                GSQLStr(_xpost('Dscnt'), "double"),
                GSQLStr(_xpost('Discount'), "double"),
                GSQLStr(_xpost('TotTax'), "double"),
                GSQLStr(_xpost('TotDisc'), "double"),
                GSQLStr(_xpost('TotalValue'), "double"),
                GSQLStr(_xpost('Grandvalue'), "double"),
                1,
                1,
                GSQLStr(_xpost('Notes'), "text"),
                GSQLStr(_xpost('ShipTo'), "text"));
    $insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
        $recid = mysqli_insert_id($dbh);
        docs('Accounts'.DS.'Sales', $recid);
        $ItmID = intval(_xpost('ItmID'));
        for ($q = 0; $q < $ItmID; $q++) {
            if (!isset($_POST["ProductID_$q"]))
                continue;
            $sql = sprintf("INSERT INTO `%s`.`invoicedetails`( `InvoiceID`, `ProductID`, `ProductName`, 
                `serials`, `units`, `UnitPrice`, `Discount`, `Discnt`, `TaxRate`, `LineTotal`) 
                VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        $_SESSION['DBCoy'],
                        $recid,
                        GSQLStr(_xpost("ProductID_$q"), "int"),
                        GSQLStr(_xpost("ProductName_$q"), "text"),
                        GSQLStr(_xpost("serials_$q"), "text"),
                        GSQLStr(_xpost("units_$q"), "double"),
                        GSQLStr(_xpost("UnitPrice_$q"), "double"),
                        GSQLStr(_xpost("Discount_$q"), "double"),
                        GSQLStr(_xpost("Discnt_$q"), "double"),
                        GSQLStr(_xpost("TaxRate_$q"), "double"),
                        GSQLStr(_xpost("LineTotal_$q"), "double"));
            $invdet = runDBQry($dbh, $sql);
        }
        
        header("Location: view.php?id=$recid");
        exit;
    }
}

$flow = _xses('flow');
$TProducts = array();
if (isset($_GET['schd'])) {
    $sql = "SELECT `VendorID`, `AssetID`, `Classification`, `invoicedetails`.`InvoiceID`
        FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`
        INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails`      ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
        INNER JOIN `{$_SESSION['DBCoy']}`.`items`               ON `invoicedetails`.ProductID=`items`.ItemID
        INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`            ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
        WHERE `SrvSchedID`=" . _xget('schd');
    $row_TSched = getDBDataRow($dbh, $sql);
    
    $_SESSION['custid'] = $row_TSched['VendorID'];
    $_SESSION['flow'] = 4;
    $itm = $row_TSched['Classification'] == 20 ? 73 : 72;
    $inv = $row_TSched['InvoiceID'];
    
    $sql = "SELECT `ItemID`, `ProdName`, `UnitPrice` AS Price, ShopStock, `outlet`.`serials`, `serialized`, `itmtax`, {$row_TSched['AssetID']} AS `AssetID`
        FROM `{$_SESSION['DBCoy']}`.`items`
        INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`      ON `items`.`ItemID`=`items_prod`.`ProductID`
        INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`          ON `items_prod`.ProductID=outlet.ProductID
        WHERE `items`.`ItemID`=$itm AND OutletID=$outid";
    $TProducts = getDBData($dbh, $sql);
} else {
    $lst = count($_SESSION['new_veh']) > 0 ? implode(',', $_SESSION['new_veh']) : intval(_xget('id'));
    if (intval(_xses('custid')) > 0 && strlen($lst) > 0) {
        $from = "`assets`.`AssetID`, `assets`.`Category` FROM `{$_SESSION['DBCoy']}`.`assets` 
            INNER JOIN `{$_SESSION['DBCoy']}`.`items`           ON `assets`.`Category`=`items`.`Classification`
            INNER JOIN `{$_SESSION['DBCoy']}`.`licenses`        ON (`assets`.`desgtype`=`licenses`.`lic_typ` AND `items`.`ItemID` REGEXP CONCAT('^(', `licenses`.`reg`, ')$'))
            LEFT JOIN `{$_SESSION['DBCoy']}`.items_srv          ON `items`.`ItemID`=`items_srv`.`ServiceID`
            LEFT JOIN `{$_SESSION['DBCoy']}`.items_pkgs         ON `items`.`ItemID`=`items_pkgs`.`PackageID`
            LEFT JOIN `{$_SESSION['DBCoy']}`.items_prod         ON `items`.`ItemID`=`items_prod`.`ProductID`
            LEFT JOIN `{$_SESSION['DBCoy']}`.`outlet`           ON `items_prod`.`ProductID`=`outlet`.`ProductID`
            WHERE occupant=0{$_SESSION['custid']} AND `assets`.`AssetID` IN ($lst) AND `items`.`InUse`=1 AND 
                (`LimitedTime`=0 OR `LimitedTime` IS NULL OR (`LimitedTime`=1 AND StartDate<=NOW() AND EndDate>=NOW() 
                        AND DAYOFWEEK(NOW()) REGEXP CONCAT('^(', `wkday`, ')$'))) AND 
                (OutletID=$outid OR $outid REGEXP CONCAT('^(', REPLACE(items_srv.`outlets`,',','|'), ')$')
                                 OR $outid REGEXP CONCAT('^(', REPLACE(items_pkgs.`outlets`,',','|'), ')$'))";

        $inv = '';
        $evrc = '';
        $prod = '';
        if ($flow != '2') {
            $evrc = 'WHERE repeated=1';
            $prod = '';
            if ($flow == '1') {
                $evrc .= ' OR `items`.`Classification`=34';
                $prod = 'AND (`items`.`Classification`=23 OR `items`.`Classification`=19)';
            }
        }

        $sql = "SELECT `ItemID`, `ProdName`, `UnitPrice`-Discount AS Price, '' AS ShopStock, '' AS `serials`, 0 AS `serialized`, `itmtax`, `AssetID`
            FROM (
                SELECT `PackageID`, $from
            ) AS item_asset
            INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms` ON `item_asset`.PackageID=`items_pkgs_itms`.`PackageID`
            INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`       ON `items_pkgs_itms`.ProductID=`items_srv`.`ServiceID`
            INNER JOIN `{$_SESSION['DBCoy']}`.`items`           ON `items_pkgs_itms`.ProductID=`items`.`ItemID`
            $evrc
            UNION
            SELECT `ItemID`, `ProdName`, `UnitPrice`-Discount AS Price, ShopStock, `outlet`.`serials`, `serialized`, `itmtax`, `AssetID`
            FROM (
                SELECT `PackageID`, $from
            ) AS item_asset
            INNER JOIN `{$_SESSION['DBCoy']}`.`items_pkgs_itms` ON `item_asset`.PackageID=`items_pkgs_itms`.`PackageID`
            INNER JOIN `{$_SESSION['DBCoy']}`.`items_prod`      ON `items_pkgs_itms`.ProductID=`items_prod`.`ProductID`
            INNER JOIN `{$_SESSION['DBCoy']}`.`items`           ON `items_pkgs_itms`.ProductID=`items`.`ItemID`
            INNER JOIN `{$_SESSION['DBCoy']}`.`outlet`          ON `items_prod`.ProductID=outlet.ProductID
            WHERE OutletID=$outid $prod";
        $TProducts = getDBData($dbh, $sql);
    }
}

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmsales","",$flow==4 ? "../kiosk.php" : "../vehicles/index.php","","","","");
$rec_status = 2;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<link rel="stylesheet" href="/lib/jquery-ui/css/smoothness/jquery-ui.css">
<script src="/lib/jquery-ui/js/jquery.js"></script>
<script src="/lib/jquery-ui/js/jquery-ui.js"></script>
<script type="text/javascript" src="script.js"></script>
<script type="text/javascript" src="/scripts/js/set.js"></script>
<script type="text/javascript"> 
<!--
window.onload = function() {
    setContent();<?php foreach ($TProducts as $row_TProducts) { ?>
    appendItm(<?php echo "'", $row_TProducts['ItemID'], "','", $row_TProducts['ProdName'], "','", $row_TProducts['Price'], "','", $row_TProducts['ShopStock'], "','"
            . "", $row_TProducts['AssetID'], "','", $row_TProducts['serialized'], "','", $row_TProducts['itmtax'], "'" ?>);
    <?php } ?>
}
window.onresize = function() {
    setContent();
}

//--> 
</script>
<script language="JavaScript1.2" type="text/javascript">
    var  coycur = <?php echo $_SESSION['COY']['currency']; ?>;
    var arrFormValidation=[
    ];
	
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
    <td height="10"></td>
  </tr>
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
                    <td class="titles">Total Value:</td>
                    <td width="482" align="left"><input name="Grandvalue" type="text" style="width:120px" readonly="readonly" /></td>
                  </tr>
                  <tr>
                    <td width="88" class="titles">Notes:</td>
                    <td align="left"><textarea name="Notes" style="width:450px" rows="3"></textarea>                    </td>
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
                            <td><?php include '../../scripts/newdoc.php' ?></td>
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
                    <td colspan="2"><input type="hidden" name="TaxRate" />
                      <input name="TotTax" type="hidden" size="12" readonly="readonly" />
                      <input name="TotalValue" type="hidden" readonly="readonly" />
                      <input name="TotDisc" type="hidden" size="12" readonly="readonly" />
                      <input type="hidden" name="ShipTo" value="<?php echo $inv ?>" /></td>
                    </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="2" id="Tabdet">
                      <tr class="boldwhite1">
                        <td colspan="10" align="center" nowrap="nowrap" bgcolor="#000000" class="h1">Items</td>
                        </tr>
                      <tr class="boldwhite1">
                        <td align="center" nowrap="nowrap" bgcolor="#000000"><input type="hidden" name="del" value="0" /></td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Qty</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Unit Price</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">% Dsc</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Discount</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Tax%</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Sales Price</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Total Value</td>
                        </tr>
                      </table>
                      <input name="ItmID" type="hidden" id="ItmID" value="0" />
                      <script>var ItmID=0 </script></td>
                    </tr>
                  <tr>
                    <td height="28" colspan="2" align="center">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              </table>
            <input type="hidden" name="MM_insert" value="frmsales" />
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
      <td valign="bottom" class="framebot"><span class="greytxt">Copyright © 2010 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>
</html>
