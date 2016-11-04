<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Journal'));
vetAccess('Accounts', 'Journal', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmBills","","view.php?id=$id".(_xget('p') == "1" ? "&p=1" : ""),"","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$id = intval(_xget('id'));
$doc_shelf = 'Accounts'.DS.'Journal';
$doc_id = $id;

if (_xpost("MM_update") == "frmBills") {
	$sql = sprintf("UPDATE `%s`.`bills` SET `EmployeeID`=%s,`InvoiceID`=%s,`BillTitle`=%s,
            `VendorType`=%s,`VendorID`=%s,`CustomerName`=%s,`BillType`=%s,`Status`=%s,
            `BillDate`=%s,`ReceivedDate`=%s,`Amount`=%s,`payable`=%s,`entrytype`=%s,`Notes`=%s 
            WHERE BillID=%s AND Posted=0",
                       $_SESSION['DBCoy'],
                       GSQLStr($_SESSION['ids']['VendorID'], "int"),
                       GSQLStr(_xpost('InvoiceID'), "text"),
                       GSQLStr(_xpost('BillTitle'), "text"),
                       GSQLStr(_xpost('VendorType'), "int"),
                       GSQLStr(_xpost('VendorID'), "int"),
                       GSQLStr(_xpost('CustomerName'), "text"),
                       GSQLStr(_xpost('BillType'), "intn"),
                       GSQLStr(_xpost('Status'), "intn"),
                       GSQLStr(_xpost('BillDate'), "date"),
                       GSQLStr(_xpost('ReceivedDate'), "date"),
                       GSQLStr(_xpost('Amount'), "double"),
                       GSQLStr(_xpostchk('payable'), "int"),
                       GSQLStr(_xpost('entrytype'), "int"),
                       GSQLStr(_xpost('Notes'), "text"),
                       $id);
	$update = runDBQry($dbh, $sql);
	docs($doc_shelf, $doc_id);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT `bills`.*, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`bills`
    LEFT JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `bills`.EmployeeID=`vendors`.VendorID 
    WHERE `BillID`=$id";
$row_TBills = getDBDataRow($dbh, $sql);

$sch = $_SESSION['accesskeys']['Academics']['View'] == -1 ? ",6,7" : "";
$sql = "SELECT VendorID, VendorType, currency, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` 
    WHERE VendorType NOT IN (0$sch) ORDER BY `VendorName`";
$TClients = getDBData($dbh, $sql);

$sql = "SELECT `VendorID`, `VendorType` FROM `" . DB_NAME . "`.`vendortypes`
WHERE `VendorID` NOT IN (0,4$sch)
ORDER BY `VendorType`";
$TVendorTypes = getDBData($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`currencies` ORDER BY cur_id";
$TCurrency = getDBData($dbh, $sql);

$TStatus  = getCat('AccStatus');
$TCat = getClassify(7);
$TDept = getClassify(1, "AND (`category_id`='{$_SESSION['ids']['dept']}' OR `category_id` LIKE '{$_SESSION['ids']['dept']}-%')");

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
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
<link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
<script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
<script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
<script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["BillTitle", "", 
            ["req", "Enter Journal Entry Description"]
        ],
        ["VendorType", "", 
            ["req", "Select Creditor Type"]
        ],
        ["VendorID", "", 
            ["req", "Select Creditor", "desg1,desg2,desg3,desg4,desg5,desg6,desg7"]
        ],
        ["CustomerName", "", 
            ["req", "Enter Creditor Name"]
        ],
        ["currency", "", 
            ["req", "Select Currency by selecting a client with a required currency account"]
        ],
        ["Amount", "", 
            ["req", "Enter Value"]
        ],
        ["BillDate", "", 
            ["req", "Enter Journal Entry Date"]
        ],
        ["ReceivedDate", "", 
            ["req", "Enter Date Received"]
        ],
        ["Dept", "", 
            ["req", "Select Department"]
        ],
        ["BillType", "", 
            ["req", "Select Category"]
        ]
    ];

    window.onload = function() {
        var mCal = new dhtmlxCalendarObject('BillDate', true, {isYearEditable: true, isMonthEditable: true});
        mCal.setSkin('dhx_black');
        var mCal2 = new dhtmlxCalendarObject('ReceivedDate', true, {isYearEditable: true, isMonthEditable: true});
        mCal2.setSkin('dhx_black');
        clientype(<?php echo $row_TBills['VendorType']; ?>);
        isPayable();
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
        <td width="240" valign="top"><img src="/images/Bills.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblBills.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmBills" id="frmBills">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles">Journal Entry ID:</td>
                    <td class="red-normal"><b><?php echo $row_TBills['BillID']; ?></b></td>
                    </tr>
                  <tr>
                    <td class="titles">Payable:</td>
                    <td align="left"><input type="checkbox" name="payable" id="payable"<?php if ($row_TBills['payable'] == 1) echo " checked=\"checked\"" ?> onclick="isPayable()" /></td>
                    </tr>
                  <tr>
                    <td class="titles" id="titleEntry">Entry Type:</td>
                    <td align="left"><table id="tabentry">
                      <tr>
                        <td><input type="radio" name="entrytype" value="1" id="entrytype_0"<?php if ($row_TBills['entrytype'] == 1) echo " checked=\"checked\"" ?> /></td>
                        <td class="blue-normal"><strong>Debit</strong></td>
                        <td>&nbsp;</td>
                        <td><input type="radio" name="entrytype" value="2" id="entrytype_1"<?php if ($row_TBills['entrytype'] == 2) echo " checked=\"checked\"" ?> /></td>
                        <td class="blue-normal"><strong>Credit</strong></td>
                        </tr>
                      </table>
                      <input name="InvoiceID" type="text" value="<?php echo $row_TBills['InvoiceID'] ?>" size="32" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Description:</td>
                    <td><input name="BillTitle" value="<?php echo $row_TBills['BillTitle'] ?>" style="width:300px" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Client:</td>
                    <td align="left"><table width="300" border="0" cellpadding="1" cellspacing="1">
                      <tr>
                        <td><select name="VendorType" onchange="clientype(this.value)">
                          <option value=""></option>
                          <?php foreach ($TVendorTypes as $row_TVendorTypes) { ?>
                          <option value="<?php echo $row_TVendorTypes['VendorID'] ?>" <?php if (!(strcmp($row_TBills['VendorType'],$row_TVendorTypes['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TVendorTypes['VendorType'] ?></option>
                          <?php } ?>
                          </select></td>
                        <td width="100%" align="left"><select id="desg1" style="display:none" onchange="setclient(1)">
                          <option value=""></option>
                          <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==1) { ?>
                          <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TBills['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                          <?php }} ?>
                          </select>
                          <select id="desg2" style="display:none" onchange="setclient(2)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==2) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TBills['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select id="desg3" style="display:none" onchange="setclient(3)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==3) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TBills['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select id="desg4" style="display:none" onchange="setclient(4)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==4) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TBills['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select id="desg5" style="display:none" onchange="setclient(5)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==5) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TBills['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select id="desg6" style="display:none" onchange="setclient(6)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==6) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TBills['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select id="desg7" style="display:none" onchange="setclient(7)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==7) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TBills['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <input name="VendorID" type="hidden" id="VendorID" value="<?php echo $row_TBills['VendorID'] ?>" /></td>
                        </tr>
                      <tr>
                        <td colspan="2"><input name="CustomerName" id="CustomerName" value="<?php echo $row_TBills['CustomerName'] ?>" style="width:300px" /></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Value:</td>
                    <td><table border="0" cellspacing="1" cellpadding="1">
                      <tr>
                        <td><select name="currency" id="currency" disabled="disabled">
                          <option value=""></option>
                          <?php foreach ($TCurrency as $row_TCurrency) { ?>
                          <option value="<?php echo $row_TCurrency['cur_id'] ?>"><?php echo $row_TCurrency['currencyname'] ?></option>
                          <?php } ?>
                          </select></td>
                        <td><input name="Amount" type="text" style="width:120px" value="<?php echo $row_TBills['Amount'] ?>" /></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Journal Entry Date:</td>
                    <td align="left"><input name="BillDate" type="text" id="BillDate" value="<?php echo $row_TBills['BillDate'] ?>" size="16" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Date Received:</td>
                    <td align="left"><input name="ReceivedDate" type="text" id="ReceivedDate" value="<?php echo $row_TBills['ReceivedDate'] ?>" size="16" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td align="left"><select name="Dept">
                      <?php foreach ($TDept as $row_TDept) { ?>
                      <option value="<?php echo $row_TDept['catID'] ?>" <?php if (!(strcmp($row_TBills['Dept'], $row_TDept['catID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TDept['catname'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td><select name="BillType">
                      <option value=""></option>
                      <?php foreach ($TCat as $row_TCat) { ?>
                      <option value="<?php echo $row_TCat['catID'] ?>" <?php if (!(strcmp($row_TBills['BillType'], $row_TCat['catID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TCat['catname'] ?></option>
                      <?php } ?>
                      </select>
                      <input type="button"bank value="edit" onclick="return GB_showCenter('Categories', '/accounts/cat/index.php', 480,520)" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Status: </td>
                    <td><select name="Status">
                      <option value=""></option>
                      <?php foreach ($TStatus as $row_TStatus) { ?>
                      <option value="<?php echo $row_TStatus['CategoryID'] ?>" <?php if (!(strcmp($row_TBills['Status'], $row_TStatus['CategoryID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TStatus['Category'] ?></option>
                      <?php } ?>
                      </select>
                      <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/accounts/status/index.php', 480,520)" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Posted:</td>
                    <td><input type="checkbox" name="Posted"<?php if ($row_TBills['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Staff:</td>
                    <td><?php echo $row_TBills['VendorName'] ?></td>
                    </tr>
                  <tr>
                    <td valign="top" class="titles">Notes:</td>
                    <td align="left"><textarea name="Notes" style="width:300px" rows="3"><?php echo $row_TBills['Notes'] ?></textarea></td>
                    </tr>
                  <tr>
                    <td valign="top" class="titles">&nbsp;</td>
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
                  </table></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmBills" />
            <input type="hidden" name="BillID" value="<?php echo $row_TBills['BillID']; ?>" />
          </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
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
