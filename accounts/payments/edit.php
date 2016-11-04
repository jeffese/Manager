<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Income'));
vetAccess('Accounts', 'Income', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmPayments","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = 'Accounts'.DS.'Income';
$doc_id = $id;

if (_xpost("MM_update") == "frmPayments") {
	$sql = sprintf("UPDATE `%s`.`payments` SET `AccountID`=%s,`EmployeeID`=%s,
            `InvoiceID`=%s,`PaymentTitle`=%s,`VendorType`=%s,`VendorID`=%s,`Payer`=%s,`PaymentType`=%s,
            `Amount`=%s,`PaymentDate`=%s,`PaymentMethodID`=%s,`PaymentMethod`=%s,`AccountName`=%s,
            `AccountNumber`=%s,`CheckNumber`=%s,`CreditCardType`=%s,`CheckDate`=%s,`Status`=%s,
            `Notes`=%s WHERE PaymentID=%s AND Posted=0",
                       $_SESSION['DBCoy'],
                       GSQLStr(_xpost('AccountID'), "int"),
                       GSQLStr($_SESSION['ids']['VendorID'], "int"),
                       GSQLStr(_xpost('InvoiceID'), "text"),
                       GSQLStr(_xpost('PaymentTitle'), "text"),
                       GSQLStr(_xpost('VendorType'), "int"),
                       GSQLStr(_xpost('VendorID'), "int"),
                       GSQLStr(_xpost('Payer'), "text"),
                       GSQLStr(_xpost('PaymentType'), "intn"),
                       GSQLStr(_xpost('Amount'), "double"),
                       GSQLStr(_xpost('PaymentDate'), "date"),
                       GSQLStr(_xpost('PaymentMethodID'), "int"),
                       GSQLStr(_xpost('PaymentMethod'), "text"),
                       GSQLStr(_xpost('AccountName'), "text"),
                       GSQLStr(_xpost('AccountNumber'), "text"),
                       GSQLStr(_xpost('CheckNumber'), "text"),
                       GSQLStr(_xpost('CreditCardType'), "intn"),
                       GSQLStr(_xpost('CheckDate'), "date"),
                       GSQLStr(_xpost('Status'), "int"),
                       GSQLStr(_xpost('Notes'), "text"),
                       $id);
	$update = runDBQry($dbh, $sql);
	docs($doc_shelf, $doc_id);
	header("Location: view.php?id=$id");
	exit;
}

$outid = _xses('OutletID');
$sql = "SELECT `payments`.*, OutletName, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`payments`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `payments`.EmployeeID=`vendors`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlets` ON `payments`.OutletID=`outlets`.OutletID 
    WHERE `PaymentID`=$id AND `payments`.OutletID IN ($outid)";
$row_TPayments = getDBDataRow($dbh, $sql);

$sch = $_SESSION['accesskeys']['Academics']['View'] == -1 ? ",6,7" : "";
$sql = "SELECT VendorID, VendorType, currency, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` 
    WHERE VendorType NOT IN (0$sch) ORDER BY `VendorName`";
$TClients = getDBData($dbh, $sql);

$sql = "SELECT `VendorID`, `VendorType` FROM `" . DB_NAME . "`.`vendortypes`
WHERE `VendorID` NOT IN (0$sch)
ORDER BY `VendorType`";
$TVendorTypes = getDBData($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`currencies` ORDER BY cur_id";
$TCurrency = getDBData($dbh, $sql);

$TStatus  = getCat('AccStatus');
$TCards  = getCat('card');
$TPayType  = getCat('PaymentType');
$TCat = getClassify(7);

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
        ["PaymentTitle", "", 
            ["req", "Enter Income Description"]
        ],
        ["VendorType", "", 
            ["req", "Select Payer Type"]
        ],
        ["VendorID", "", 
            ["req", "Select Payer", "desg1,desg2,desg3,desg4,desg5,desg6,desg7"]
        ],
        ["Payer", "", 
            ["req", "Enter Customer Name"]
        ],
        ["currency", "", 
            ["req", "Select Currency by selecting a client with a required currency account"]
        ],
        ["Amount", "", 
            ["req", "Enter Value"],
            ["gt=0", "Enter Value"]
        ],
        ["AccountID", "", 
            ["req", "Select Account to post to"],
            ["eval=$('#currency').val()==$('#AccountID option:selected').attr('currency')", 
                "Currency of Posting Account and Currency of Income do not match"]
        ],
        ["PaymentMethodID", "", 
            ["req", "Select Income Method"]
        ],
        ["CreditCardType", "if=$('#PaymentMethodID').val()==21", 
            ["req", "Select POS Card"],
            ["eval=$('#AccountID').val()==$('#CreditCardType option:selected').attr('acc')", 
                "Please select a POS Card associated with the Account"]
        ],
        ["AccountName21", "if=$('#PaymentMethodID').val()==21", 
            ["req", "Enter Card Holder's Name"]
        ],
        ["AccountNumber21", "if=$('#PaymentMethodID').val()==21", 
            ["req", "Enter Card Number"]
        ],
        ["PaymentMethod23", "if=$('#PaymentMethodID').val()==23", 
            ["req", "Enter Name of Bank"]
        ],
        ["AccountName23", "if=$('#PaymentMethodID').val()==23", 
            ["req", "Enter Account Name"]
        ],
        ["AccountNumber23", "if=$('#PaymentMethodID').val()==23", 
            ["req", "Enter Account Number"]
        ],
        ["CheckNumber23", "if=$('#PaymentMethodID').val()==23", 
            ["req", "Enter Cheque Number"]
        ],
        ["CheckDate23", "if=$('#PaymentMethodID').val()==23", 
            ["req", "Enter Cheque Date"]
        ],
        ["PaymentMethod25", "if=$('#PaymentMethodID').val()==25", 
            ["req", "Enter Institution or Method"]
        ],
        ["AccountName25", "if=$('#PaymentMethodID').val()==25", 
            ["req", "Enter Payer Name"]
        ],
        ["CheckNumber25", "if=$('#PaymentMethodID').val()==25", 
            ["req", "Enter Tracking Number"]
        ],
        ["CheckDate25", "if=$('#PaymentMethodID').val()==25", 
            ["req", "Enter Date"]
        ],
        ["PaymentDate", "", 
            ["req", "Enter Income Date"]
        ]
    ];

    window.onload = function() {
        var mCal = new dhtmlxCalendarObject('PaymentDate', true, {isYearEditable: true, isMonthEditable: true});
        mCal.setSkin('dhx_black');
        var mCal3 = new dhtmlxCalendarObject('CheckDate23', true, {isYearEditable: true, isMonthEditable: true});
        mCal3.setSkin('dhx_black');
        var mCal4 = new dhtmlxCalendarObject('CheckDate25', true, {isYearEditable: true, isMonthEditable: true});
        mCal4.setSkin('dhx_black');
        clientype(<?php echo $row_TPayments['VendorType']; ?>);
        paytype(<?php echo $row_TPayments['PaymentMethodID']; ?>);
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
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="240" valign="top"><img src="/images/payments.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblpayments.png); background-repeat:no-repeat">&nbsp;</td>
            </tr>
          <tr>
            <td class="h1" height="5px"></td>
            </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
            </tr>
          </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="setpaydet(this.PaymentMethodID.value); return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmPayments" id="frmPayments">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
                </tr>
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
                    <td width="120" class="titles">Income ID:</td>
                    <td class="red-normal"><b><?php echo $row_TPayments['PaymentID']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Outlet:</td>
                    <td><b><?php echo $row_TPayments['OutletName']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Invoice #:</td>
                    <td align="left"><input name="InvoiceID" type="text" value="<?php echo $row_TPayments['InvoiceID'] ?>" onchange="numme(this)" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Description:</td>
                    <td><input name="PaymentTitle" value="<?php echo $row_TPayments['PaymentTitle'] ?>" style="width:300px" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Payer:</td>
                    <td align="left"><table width="300" border="0" cellpadding="1" cellspacing="1">
                      <tr>
                        <td><select name="VendorType" onchange="clientype(this.value)">
                          <option value=""></option>
                          <?php foreach ($TVendorTypes as $row_TVendorTypes) { ?>
                          <option value="<?php echo $row_TVendorTypes['VendorID'] ?>" <?php if (!(strcmp($row_TPayments['VendorType'],$row_TVendorTypes['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TVendorTypes['VendorType'] ?></option>
                          <?php } ?>
                          </select></td>
                        <td width="100%" align="left"><select id="desg1" style="display:none" onchange="setclient(1)">
                          <option value=""></option>
                          <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==1) { ?>
                          <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TPayments['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                          <?php }} ?>
                          </select>
                          <select id="desg2" style="display:none" onchange="setclient(2)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==2) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TPayments['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select id="desg3" style="display:none" onchange="setclient(3)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==3) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TPayments['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select id="desg4" style="display:none" onchange="setclient(4)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==4) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TPayments['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select id="desg5" style="display:none" onchange="setclient(5)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==5) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TPayments['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select id="desg6" style="display:none" onchange="setclient(6)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==6) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TPayments['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <select id="desg7" style="display:none" onchange="setclient(7)">
                            <option value=""></option>
                            <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==7) { ?>
                            <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TPayments['VendorID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                            <?php }} ?>
                            </select>
                          <input name="VendorID" type="hidden" id="VendorID" value="<?php echo $row_TPayments['VendorID'] ?>" /></td>
                        </tr>
                      <tr>
                        <td colspan="2"><input name="Payer" id="Payer" value="<?php echo $row_TPayments['Payer'] ?>" style="width:300px" /></td>
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
                        <td><input name="Amount" type="text" style="width:120px" value="<?php echo $row_TPayments['Amount'] ?>" onchange="numme(this)" /></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Account:</td>
                    <td><select name="AccountID" id="AccountID">
                      <option value="" currency="0"></option>
                      <?php foreach ($TClients as $row_TClients) {
                                  if ($row_TClients['VendorType']==4) { ?>
                      <option value="<?php echo $row_TClients['VendorID'] ?>" currency="<?php echo $row_TClients['currency'] ?>" <?php if (!(strcmp($row_TPayments['AccountID'],$row_TClients['VendorID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TClients['VendorName'] ?></option>
                      <?php }} ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">Income:</td>
                    <td bgcolor="#999999"><table width="300" border="0" cellpadding="1" cellspacing="1">
                      <tr>
                        <td><select name="PaymentMethodID" id="PaymentMethodID" onchange="paytype(this.value)">
                          <option value="">Select Method</option>
                          <?php foreach ($TPayType as $row_TPayType) { ?>
                          <option value="<?php echo $row_TPayType['CategoryID'] ?>" <?php if (!(strcmp($row_TPayments['PaymentMethodID'],$row_TPayType['CategoryID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_TPayType['Category'] ?></option>
                          <?php } ?>
                          </select></td>
                        <td width="100%" align="left"><input type="hidden" name="PaymentMethod" value="<?php echo $row_TPayments['PaymentMethod']; ?>" />
                          <input type="hidden" name="AccountName" value="<?php echo $row_TPayments['AccountName']; ?>" />
                          <input type="hidden" name="AccountNumber" value="<?php echo $row_TPayments['AccountNumber']; ?>" />
                          <input type="hidden" name="CheckNumber" value="<?php echo $row_TPayments['CheckNumber']; ?>" />
                          <input type="hidden" name="CheckDate" value="<?php echo $row_TPayments['CheckDate']; ?>" /></td>
                        </tr>
                      <tr>
                        <td colspan="2"><table border="0" cellspacing="1" cellpadding="1" id="pay21" style="display:none">
                          <tr>
                            <td align="right" nowrap="nowrap" class="boldwhite1">Card Type:</td>
                            <td><select name="CreditCardType" id="CreditCardType">
                              <option value=""></option>
                              <?php foreach ($TCards as $row_TCards) { ?>
                              <option value="<?php echo $row_TCards['CategoryID'] ?>" acc="<?php echo $row_TCards['par'] ?>" <?php if (!(strcmp($row_TPayments['CreditCardType'], $row_TCards['CategoryID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TCards['Category'] ?></option>
                              <?php } ?>
                              </select>
                              <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/accounts/cards/index.php', 480,520)" /></td>
                            </tr>
                          <tr>
                            <td align="right" nowrap="nowrap" class="boldwhite1">Card Holder:</td>
                            <td><input name="AccountName21" id="AccountName21" style="width:200px" value="<?php echo $row_TPayments['AccountName'] ?>" onchange="setpaydet(21)" /></td>
                            </tr>
                          <tr>
                            <td align="right" nowrap="nowrap" class="boldwhite1">Card #:</td>
                            <td><input name="AccountNumber21" id="AccountNumber21" style="width:200px" value="<?php echo $row_TPayments['AccountNumber'] ?>" onchange="setpaydet(21)" /></td>
                            </tr>
                          </table>
                          <table border="0" cellspacing="1" cellpadding="1" id="pay23" style="display:none">
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Bank:</td>
                              <td><input name="PaymentMethod23" id="PaymentMethod23" value="<?php echo $row_TPayments['PaymentMethod'] ?>" style="width:200px" onchange="setpaydet(23)" /></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Account Name:</td>
                              <td><input name="AccountName23" id="AccountName23" value="<?php echo $row_TPayments['AccountName'] ?>" style="width:200px" onchange="setpaydet(23)" /></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Account #:</td>
                              <td><input name="AccountNumber23" id="AccountNumber23" style="width:200px" value="<?php echo $row_TPayments['AccountNumber'] ?>" onchange="setpaydet(23)" /></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Cheque #:</td>
                              <td><input name="CheckNumber23" type="text" id="CheckNumber23" value="<?php echo $row_TPayments['CheckNumber'] ?>" size="16" onchange="setpaydet(23)" /></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Cheque Date:</td>
                              <td><input name="CheckDate23" type="text" id="CheckDate23" value="<?php echo $row_TPayments['CheckDate'] ?>" size="16" onchange="setpaydet(23)" /></td>
                              </tr>
                            </table>
                          <table border="0" cellspacing="1" cellpadding="1" id="pay25" style="display:none">
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Institution/Method:</td>
                              <td><input name="PaymentMethod25" id="PaymentMethod25" style="width:200px" value="<?php echo $row_TPayments['PaymentMethod'] ?>" onchange="setpaydet(25)" /></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Payer Name:</td>
                              <td><input name="AccountName25" id="AccountName25" style="width:200px" value="<?php echo $row_TPayments['AccountName'] ?>" onchange="setpaydet(25)" /></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Tracking #:</td>
                              <td><input name="CheckNumber25" type="text" id="CheckNumber25" value="<?php echo $row_TPayments['CheckNumber'] ?>" size="16" onchange="setpaydet(25)" /></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Other Info:</td>
                              <td><input name="AccountNumber25" id="AccountNumber25" style="width:200px" value="<?php echo $row_TPayments['AccountNumber'] ?>" onchange="setpaydet(25)" /></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Date:</td>
                              <td><input name="CheckDate25" type="text" id="CheckDate25" value="<?php echo $row_TPayments['CheckDate'] ?>" size="16" onchange="setpaydet(25)" /></td>
                              </tr>
                            </table></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Income Date:</td>
                    <td align="left"><input name="PaymentDate" type="text" id="PaymentDate" value="<?php echo $row_TPayments['PaymentDate'] ?>" size="16" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td><select name="PaymentType">
                      <option value=""></option>
                      <?php foreach ($TCat as $row_TCat) { ?>
                      <option value="<?php echo $row_TCat['catID'] ?>" <?php if (!(strcmp($row_TPayments['PaymentType'], $row_TCat['catID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TCat['catname'] ?></option>
                      <?php } ?>
                      </select>
                      <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/accounts/cat/index.php', 480,520)" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Status: </td>
                    <td><select name="Status">
                      <option value=""></option>
                      <?php foreach ($TStatus as $row_TStatus) { ?>
                      <option value="<?php echo $row_TStatus['CategoryID'] ?>" <?php if (!(strcmp($row_TPayments['Status'], $row_TStatus['CategoryID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TStatus['Category'] ?></option>
                      <?php } ?>
                      </select>
                      <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/accounts/status/index.php', 480,520)" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Posted:</td>
                    <td><input type="checkbox" name="Posted"<?php if ($row_TPayments['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Staff:</td>
                    <td><?php echo $row_TPayments['VendorName'] ?></td>
                    </tr>
                  <tr>
                    <td valign="top" class="titles">Notes:</td>
                    <td align="left"><textarea name="Notes" style="width:300px" rows="3"><?php echo $row_TPayments['Notes'] ?></textarea></td>
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
                  </table></td>
                </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
                </tr>
              
              </table>
            <input type="hidden" name="MM_update" value="frmPayments" />
            <input type="hidden" name="PaymentID" value="<?php echo $row_TPayments['PaymentID']; ?>" />
            </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>