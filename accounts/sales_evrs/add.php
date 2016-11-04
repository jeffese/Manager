<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Sales'));
vetAccess('Accounts', 'Sales', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmsales","","index.php","","","","");
$rec_status = 2;

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

$sql = "SELECT VendorID, VendorType, currency, $vendor_sql, 
    CONCAT(credit, ';', cheque, ';', amtbal, ';', creditlimit, ';', Discount) AS creds 
    FROM `{$_SESSION['DBCoy']}`.`vendors` 
    WHERE VendorType =1 ORDER BY `VendorName`";
$TClients = getDBData($dbh, $sql);

$sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
    INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` outcat ON `outlets`.Dept = outcat.catID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` vencat ON 
        (`outcat`.category_id LIKE CONCAT(`vencat`.category_id, '-%')
        OR  `outcat`.category_id = `vencat`.category_id)
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`                ON `vencat`.catID = `vendors`.DeptID 
    WHERE `account`=1 AND `VendorID`={$_SESSION['ids']['VendorID']}";
$TOutlets = getDBData($dbh, $sql);

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
<link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
<link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
<script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
<script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
<script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
<link rel="stylesheet" href="/lib/jquery-ui/css/smoothness/jquery-ui.css">
<script src="/lib/jquery-ui/js/jquery.js"></script>
<script src="/lib/jquery-ui/js/jquery-ui.js"></script>
<script type="text/javascript" src="script.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["VendorType", "", 
            ["req", "Select Payer Type"]
        ],
        ["VendorID", "", 
            ["req", "Select Payer", "desg1,desg2,desg3,desg4,desg5,desg6,desg7"]
        ],
        ["CustomerName", "", 
            ["req", "Enter Customer Name"]
        ],
        ["currency", "", 
            ["req", "Select Currency by selecting a client with a required currency account"]
        ],
        ["AccountID", "", 
            ["req", "Select Account to post to"],
            ["eval=$('#currency').val()==$('#AccountID option:selected').attr('currency')", 
                "Currency of Posting Account and Currency of Client do not match"]
        ],
        ["InvoiceDate", "", 
            ["req", "Select Invoice Date"]
        ]
    ]
    
    var  mCal, coycur = <?php echo $_SESSION['COY']['currency']; ?>;
    window.onload = function() {
        mCal = new dhtmlxCalendarObject('InvoiceDate', true, {isYearEditable: true, isMonthEditable: true});
	mCal.setSkin('dhx_black');
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
        <td width="240" valign="top"><img src="/images/sales.jpg" width="240" height="300" /></td>
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
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Client:</td>
                    <td align="left"><table width="300" border="0" cellpadding="1" cellspacing="1">
                      <tr>
                        <td><select name="VendorType" onchange="clientype(this.value)">
                          <option value=""></option>
                          <?php foreach ($TVendorTypes as $row_TVendorTypes) { ?>
                          <option value="<?php echo $row_TVendorTypes['VendorID'] ?>"><?php echo $row_TVendorTypes['VendorType'] ?></option>
                          <?php } ?>
                          </select></td>
                        <td width="100%" align="left"><select name="desg" id="desg1" style="display:none" onchange="setclient(1)">
                          <option value=""></option>
                          <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==1) { ?>
                          <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" creds="<?php echo $row_TClients['creds'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                          <?php }} ?>
                          </select>
                          <select name="desg" id="desg2" style="display:none" onchange="setclient(2)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==2) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" creds="<?php echo $row_TClients['creds'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select name="desg" id="desg3" style="display:none" onchange="setclient(3)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==3) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" creds="<?php echo $row_TClients['creds'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select name="desg" id="desg4" style="display:none" onchange="setclient(4)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==4) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" creds="<?php echo $row_TClients['creds'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select name="desg" id="desg5" style="display:none" onchange="setclient(5)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==5) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" creds="<?php echo $row_TClients['creds'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select name="desg" id="desg6" style="display:none" onchange="setclient(6)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==6) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" creds="<?php echo $row_TClients['creds'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select name="desg" id="desg7" style="display:none" onchange="setclient(7)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==7) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" creds="<?php echo $row_TClients['creds'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <input name="VendorID" type="hidden" id="VendorID" /></td>
                        <td width="100%" rowspan="2" align="left" bgcolor="#700112"><table border="0" cellpadding="1" cellspacing="1" class="boldwhite1">
                          <tr>
                            <td><input type="checkbox" id="credit" disabled="disabled" /></td>
                            <td>Credit</td>
                            <td>&nbsp;</td>
                            <td align="right">Limit:</td>
                            <td class="Yellow-normal" id="limit">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right">Discount:</td>
                            <td class="Yellow-normal" id="disc">&nbsp;</td>
                            </tr>
                          <tr>
                            <td><input type="checkbox" id="cheque" disabled="disabled" /></td>
                            <td>Cheque</td>
                            <td>&nbsp;</td>
                            <td align="right" nowrap="nowrap"> Bal:</td>
                            <td class="Yellow-normal" id="bal">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                          </table></td>
                        </tr>
                      <tr>
                        <td colspan="2"><input name="CustomerName" id="CustomerName" style="width:300px" /></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Account:</td>
                    <td><select name="AccountID" id="AccountID">
                      <option value="" currency="0"></option>
                      <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==4) { ?>
                      <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>"><?php echo $row_TClients['VendorName'] ?></option>
                      <?php }} ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Items:</td>
                    <td><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td>Value:</td>
                        <td><input name="TotalValue" type="text" style="width:80px" readonly="readonly" /></td>
                        <td>&nbsp;</td>
                        <td>Discount:</td>
                        <td><input name="TotDisc" type="text" size="12" readonly="readonly" /></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Discount:</td>
                    <td><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td><input type="text" name="Dscnt" onchange="dsc()" style="width:30px" /></td>
                        <td>%</td>
                        <td><strong>=&gt;</strong></td>
                        <td><input type="text" name="Discount" onchange="disc()" size="12" /></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Tax:</td>
                    <td><table border="0" cellspacing="1" cellpadding="1">
                      <tr>
                        <td><input type="text" name="TaxRate" style="width:30px" /></td>
                        <td>%</td>
                        <td>&nbsp;</td>
                        <td>Total:</td>
                        <td><input name="TotTax" type="text" size="12" readonly="readonly" /></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Total Value:</td>
                    <td><input name="Grandvalue" type="text" style="width:120px" readonly="readonly" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Currency:</td>
                    <td><table border="0" cellspacing="1" cellpadding="1">
                      <tr>
                        <td><select name="currency" id="currency" disabled="disabled">
                          <option value=""></option>
                          <?php $coyCur = "";
                                foreach ($TCurrency as $row_TCurrency) { 
                                    if ($_SESSION['COY']['currency']==$row_TCurrency['cur_id']) 
                                        $coyCur = $row_TCurrency['code']; ?>
                          <option value="<?php echo $row_TCurrency['cur_id'] ?>" from="<?php echo $row_TCurrency['fromrate'] ?>" to="<?php echo $row_TCurrency['torate'] ?>" cod="<?php echo $row_TCurrency['code'] ?>"><?php echo $row_TCurrency['currencyname'] ?></option>
                          <?php } ?>
                        </select></td>
                        <td><table border="0" cellspacing="2" cellpadding="2" id="xbox">
                          <tr>
                            <td id="xfrom">&nbsp;</td>
                            <td><input name="ExchangeFrom" type="text" id="ExchangeFrom" style="width:30px" /></td>
                            <td><strong>=&gt;</strong></td>
                            <td><?php echo $coyCur ?></td>
                            <td><input name="ExchangeTo" type="text" id="ExchangeTo" style="width:30px" /></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Invoice Date:</td>
                    <td align="left"><input name="InvoiceDate" type="text" id="InvoiceDate" size="16" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td><select name="InvoiceType">
                      <option value=""></option>
                      <?php foreach ($TCat as $row_TCat) { ?>
                      <option value="<?php echo $row_TCat['catID'] ?>"><?php echo $row_TCat['catname'] ?></option>
                      <?php } ?>
                    </select>
                      <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/accounts/cat/index.php', 480,520)" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Status: </td>
                    <td><select name="Status">
                      <option value=""></option>
                      <?php foreach ($TStatus as $row_TStatus) { ?>
                      <option value="<?php echo $row_TStatus['CategoryID'] ?>"><?php echo $row_TStatus['Category'] ?></option>
                      <?php } ?>
                    </select>
                      <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/accounts/status/index.php', 480,520)" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Posted:</td>
                    <td><input type="checkbox" name="Posted" disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Notes:</td>
                    <td><textarea name="Notes" style="width:450px" rows="3"></textarea>                    </td>
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
                    <td colspan="2">&nbsp;</td>
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
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Tenure/Qty</td>
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
                    <td height="28" colspan="2" align="center"><a href="javascript: void(0)" onclick="GB_showCenter('Product List', '/accounts/sales/pick.php?outid=<?php echo intval(_xses('OutletID')) ?>', 600,600)"><img src="/images/but_add.png" width="50" height="20" /></a>
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
            <input type="hidden" name="MM_insert" value="frmsales" />
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