<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Stock'));
$access = _xvar_arr_sub($_access, array('Transfers'));
vetAccess('Stock', 'Transfers', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmtransfer","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmtransfer") {
    $sql = sprintf("INSERT INTO `%s`.`requisitions`( `Outletout`, `Outletin`, `transfertype`, 
        `RequestedBy`, `GivenBy`, `RequestDate`, `DateGiven`, `Transfered`, `ShipName`, 
        `ShipAddress`, `ShipDate`, `ShippingMethodID`, `FreightCharge`, `expenses`, `Notes`) 
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
               $_SESSION['DBCoy'],
               GSQLStr(_xpost('Outletout'), "int"),
               GSQLStr(_xpost('Outletin'), "int"),
               GSQLStr(_xpost('transfertype'), "int"),
               GSQLStr($_SESSION['ids']['VendorID'], "int"),
               "NULL",
               "NOW()",
               "NULL",
               0,
               GSQLStr(_xpost('ShipName'), "text"),
               GSQLStr(_xpost('ShipAddress'), "text"),
               GSQLStr(_xpost('ShipDate'), "date"),
               GSQLStr(_xpost('ShippingMethodID'), "int"),
               GSQLStr(_xpost('FreightCharge'), "double"),
               GSQLStr(_xpost('expenses'), "double"),
               GSQLStr(_xpost('Notes'), "text"));
    $insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
        $id = mysqli_insert_id($dbh);
        docs('Stock'.DS.'Transfers', $id);
        $TransDetID = intval(_xpost('TransDetID'));
        for ($q = 0; $q < $TransDetID; $q++) {
            if (!isset($_POST["ProductID$q"]) || floatval(_xpost("units$q")) <= 0)
                continue;
                $sql = sprintf("INSERT INTO `%s`.`req_items`(`RequisitID`, `ProductID`, 
                    `ProductName`, `units`, `serials`) 
                    VALUES (%s,%s,%s,%s,%s)",
                           $_SESSION['DBCoy'],
                           $id,
                           GSQLStr(_xpost("ProductID$q"), "int"),
                           GSQLStr(_xpost("ProductName$q"), "text"),
                           GSQLStr(_xpost("units$q"), "double"),
                           GSQLStr(_xpost("serials$q"), "text"));
                runDBQry($dbh, $sql);
        }
        header("Location: view.php?id=$id");
        exit;
    }
}

$TType = getCat('TransferType');

$sql = "SELECT OutletID, OutletName, account FROM `{$_SESSION['DBCoy']}`.outlets ORDER BY OutletName";
$TOulets = getDBData($dbh, $sql);

$TDept = getClassify(1);

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
        ["transfertype", "", 
            ["req", "Select Transfer Type"]
        ],
        ["Outletout", "", 
            ["req", "Select Storage to Transfer from"]
        ],
        ["Outletin", "", 
            ["req", "Select Storage to Transfer to"]
        ],
        ["Outletout", "", 
            ["nequiv=Outletin", "Transfer from and Transfer to cannot be the same location"]
        ],
    ]
    var TransDetID=0;
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="$('#transfertype').attr('disabled', false); return vetProds() && validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmtransfer" id="frmtransfer">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td class="titles">Transfer Type:</td>
                    <td><select name="transfertype" id="transfertype" onchange="setOutlets()">
                      <option value=""></option>
                      <?php foreach ($TType as $row_TType) { ?>
                      <option value="<?php echo $row_TType['CategoryID'] ?>"><?php echo $row_TType['Category'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="titles"><input name="Outletout" type="hidden" id="Outletout" value="0" />
                      From:</td>
                    <td><select id="from1" onchange="setFrom(this.value)" style="display:none">
                        <?php foreach ($TOulets as $row_TOulets) {
                            if ($row_TOulets['account'] == 0) { ?>
                        <option value="<?php echo $row_TOulets['OutletID'] ?>"><?php echo $row_TOulets['OutletName'] ?></option>
                        <?php }} ?>
                      </select>
                      <select id="from2" onchange="setFrom(this.value)" style="display:none">
                        <?php foreach ($TOulets as $row_TOulets) {
                            if ($row_TOulets['account'] == 1) { ?>
                        <option value="<?php echo $row_TOulets['OutletID'] ?>"><?php echo $row_TOulets['OutletName'] ?></option>
                        <?php }} ?>
                      </select>
                      <select id="from3" onchange="setFrom(this.value)" style="display:none">
                      <?php foreach ($TDept as $row_TDept) { ?>
                      <option value="<?php echo $row_TDept['catID'] ?>"><?php echo $row_TDept['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td class="titles"><input name="Outletin" type="hidden" id="Outletin" value="0" />
                      To:</td>
                    <td><select id="to1" onchange="setTo(this.value)" style="display:none">
                        <?php foreach ($TOulets as $row_TOulets) {
                            if ($row_TOulets['account'] == 0) { ?>
                        <option value="<?php echo $row_TOulets['OutletID'] ?>"><?php echo $row_TOulets['OutletName'] ?></option>
                        <?php }} ?>
                      </select>
                      <select id="to2" onchange="setTo(this.value)" style="display:none">
                        <?php foreach ($TOulets as $row_TOulets) {
                            if ($row_TOulets['account'] == 1) { ?>
                        <option value="<?php echo $row_TOulets['OutletID'] ?>"><?php echo $row_TOulets['OutletName'] ?></option>
                        <?php }} ?>
                      </select>
                      <select id="to3" onchange="setTo(this.value)" style="display:none">
                      <?php foreach ($TDept as $row_TDept) { ?>
                      <option value="<?php echo $row_TDept['catID'] ?>"><?php echo $row_TDept['catname'] ?></option>
                      <?php } ?>
                      </select></td>
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
                                <td align="left"><input name="ShipName" type="text" size="32" /></td>
                                </tr>
                              <tr>
                                <td class="titles">Shipping Method:</td>
                                <td align="left"><select name="ShippingMethodID">
                                  <option value="0">Select One </option>
                                  <option value="1">Air Freight</option>
                                  <option value="2">Sea Freight</option>
                                  <option value="3">Parcel Service</option>
                                  <option value="4">Door Delivery</option>
                                  <option value="5">Others</option>
                                  </select></td>
                                </tr>
                              <tr>
                                <td class="titles">Date of Shipping:</td>
                                <td align="left"><input name="ShipDate" type="text" id="ShipDate" size="16" /></td>
                                </tr>
                              <tr>
                                <td valign="top" class="titles">Shipper Address:</td>
                                <td align="left"><textarea name="ShipAddress" style="width:300px" rows="3"></textarea></td>
                                </tr>
                              <tr>
                                <td class="titles">&nbsp;</td>
                                <td align="left">&nbsp;</td>
                                </tr>
                              <tr>
                                <td class="titles">Freight Charge:</td>
                                <td><input type="text" name="FreightCharge" size="12" /></td>
                                </tr>
                              <tr>
                                <td class="titles">Expenses:</td>
                                <td><input type="text" name="expenses" size="12" /></td>
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
                      <textarea name="Notes" style="width:500px" rows="5"></textarea>
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
                            <td><?php include "../../scripts/newdoc.php" ?></td>
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
                        <td align="center" nowrap="nowrap" bgcolor="#000000"></td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">#</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Item</td>
                        <td align="center" nowrap="nowrap" bgcolor="#000000">Units</td>
                      </tr>
                    </table>
                      <input name="TransDetID" type="hidden" id="TransDetID" value="0" /></td>
                  </tr>
                  <tr>
                    <td height="28" colspan="2" align="center"><a href="javascript: void(0)" onclick="GB_showCenter('Order Items', '/stock/transfers/pick.php?outid='+$('input[name=\'Outletout\']').val(), 600,600)"><img src="/images/but_add.png" width="50" height="20" /></a>
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
                <td></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_insert" value="frmtransfer" />
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
