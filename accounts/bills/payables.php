<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = $_access['Journal'];
vetAccess('Accounts', 'Journal', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, $access['Print'], 1, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("", "", "", "", "add.php", "", "", "", "", "", "", "printall.php", "");
$rec_status = 4;

$outid = intval(_xses('OutletID'));
$reset = "";
if (isset($_POST['AccountID']) && $outid > 0) {
    $lst = _xpost('list');
    $list = explode(',', $lst);
    $pay = floatval(_xpost('pay'));
    $acc = intval(_xpost('AccountID'));
    $payed = 0;
    $run = 0;
    
    if ($acc > 0 && $pay > 0) {
        try {
            $dbh->autocommit(FALSE);
            
            $sql = "SELECT BillID, Amount, Payed 
                    FROM `{$_SESSION['DBCoy']}`.`bills` 
                    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `bills`.VendorID=`vendors`.VendorID
                    WHERE `BillID` IN ($lst) LOCK IN SHARE MODE";
            $bills = getDBData($dbh, $sql);
            
            $sql = sprintf("INSERT INTO `%s`.`expenses`(
                    `AccountID`, `Dept`, `OutetID`, `EmployeeID`, `InvoiceID`, `ExpenseTitle`, 
                    `VendorType`, `VendorID`, `Recipient`, `ExpenseType`, `Amount`, LedgerDate,
                    `ExpenseDate`, `DateSubmitted`, `Posted`, `Status`, `PaymentMethodID`, `PaymentMethod`, 
                    `AccountName`, `AccountNumber`, `CheckNumber`, `CreditCardType`, `CheckDate`, `Notes`) 
                SELECT %s,`Dept`,%s,%s,`InvoiceID`,%s, 
                    `VendorType`, `VendorID`, `CustomerName`, `BillType`,'%s',NOW(),
                     NOW(),NOW(),1,NULL,%s,%s,%s,%s,%s,%s,%s,%s
                     FROM `{$_SESSION['DBCoy']}`.`bills`
                     WHERE `BillID`=$list[0]",
                    $_SESSION['DBCoy'],
                    $acc,
                    $outid,
                    $_SESSION['ids']['VendorID'],
                    GSQLStr(_xpost('ExpenseTitle'), "text"),
                    $pay,
                    GSQLStr(_xpost('PaymentMethodID'), "int"),
                    GSQLStr(_xpost('PaymentMethod'), "text"),
                    GSQLStr(_xpost('AccountName'), "text"),
                    GSQLStr(_xpost('AccountNumber'), "text"),
                    GSQLStr(_xpost('CheckNumber'), "text"),
                    GSQLStr(_xpost('CreditCardType'), "intn"),
                    GSQLStr(_xpost('CheckDate'), "date"),
                    GSQLStr(_xpost('Notes'), "text"));
            $insert = runDBQry($dbh, $sql);
            
            if ($insert == 1) {
                $exp_id = mysqli_insert_id($dbh);
            
                runDBQry($dbh, "SELECT `ExpenseID` FROM `{$_SESSION['DBCoy']}`.`expenses` 
                        WHERE `ExpenseID`=$exp_id LOCK IN SHARE MODE");

                foreach ($list as $id) {
                    foreach ($bills as $bill) {
                        if ($bill['BillID'] == $id) {
                            $bal = $bill['Amount'] - $bill['Payed'];
                            $amt = $bal > $pay ? $pay : $bal;
                            $pay -= $amt;

                            $payed += runDBQry($dbh, "UPDATE `{$_SESSION['DBCoy']}`.`bills` 
                                SET Payed=Payed+'$amt' WHERE `BillID`=$id");
                            $run++;
                            break;
                        }
                    }
                    if ($pay == 0) {
                        break;
                    }
                }
            }
            
            if ($insert < 1 || $run < $payed) {
                throw new Exception("Error making Payments!!");
            }
            $dbh->commit();
            $reset = "reset()";
        } catch (Exception $ex) {
            $dbh->rollback();
            array_push($errors, array("Error", $ex->getMessage()));
        }
        $dbh->autocommit(TRUE);
    }
}

preOrd('bill', array('', 'BillID', 'BillTitle', 'CustomerName', 'Amount', 'BillDate', 'Category', 'Payed', 'Posted', ));

$From = "FROM `{$_SESSION['DBCoy']}`.`bills`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` creditor ON `bills`.VendorID=`creditor`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`currencies`       ON `creditor`.currency=currencies.cur_id 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`            ON `bills`.Status=status.CategoryID
    WHERE `payable`=1 AND `Amount`!=`Payed`";
$sql = "SELECT BillID, BillTitle, CustomerName, Amount, BillDate, Category, Posted, Payed,`bills`.VendorID,
        currency, currencies.code {$From}{$orderval}";

$currentPage = 'payables.php';
$maxRows_TBills = 30;

$TabArray = 'TBills';
require_once (ROOT . '/scripts/fetchdata.php');

$sql = "SELECT VendorID, VendorType, currency, $vendor_sql FROM `{$_SESSION['DBCoy']}`.`vendors` 
    WHERE VendorType=4 ORDER BY `VendorName`";
$TAccounts = getDBData($dbh, $sql);

$TCards = getCat('card');
$TPayType = getCat('PaymentType');
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
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/accounts/expenses/script.js" type="text/javascript"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
<script type="text/javascript">
    var arrFormValidation=[
        ["list", "", 
            ["req", "Select item(s) to pay!"]
        ],
        ["pay", "", 
            ["req", "Enter Amount to pay"],
            ["eval=parseFloat($('#pay').val())<=parseFloat($('#topay').val())", 
                "Amout to pay is more than sum of Payables selected!"]
        ],
        ["AccountID", "", 
            ["req", "Select Account to pay from"],
            ["eval=top.paycur==$('#AccountID option:selected').attr('currency')", 
                "Currency of Posting Account and Currency of Payment do not match"]
        ],
        ["ExpenseTitle", "", 
            ["req", "Enter Expense Description"]
        ],
        ["PaymentMethodID", "", 
            ["req", "Select Payment Method"]
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
        ]
    ];
    
    function reset() {
        top.paylist = [];
        top.paycur=0
        top.paytot=0
        top.payvnd=0;
    }
    
    function Pay(bill) {
        if (bill.is(":checked")) {
            var push = true;
            if (top.paycur == 0) {
                top.paycur = bill.attr('cur');
                top.payvnd = bill.attr('vnd');
            } else if (top.paycur != bill.attr('cur')) {
                bill.attr("checked", false);
                alert("Currency of transaction differs from previously selected ones!!");
                push = false;
            } else if (top.payvnd != bill.attr('vnd')) {
                alert("You can only pay to one Client at a time!!");
                bill.attr("checked", false);
                push = false;
            }
            if (push) {
                top.paylist.push(bill.val());
                top.paytot += parseInt(bill.attr('amt'));
            }
        } else {
            var index = top.paylist.indexOf(bill.val());
            if (index >= 0) {
              top.paylist.splice(index, 1);
              top.paytot -= bill.attr('amt');
              if (top.paytot == 0) {
                  reset();
              }
            }
        }
        $("#payable").html(setthous(top.paytot));
        $("#topay").val(top.paytot);
        $("#list").val(top.paylist.join(','));
    }
    
    window.onload = function() {
        <?php echo $reset ?>;
        $('[name=paylist]').each(function(){
            if (top.paylist.indexOf($(this).val()) >= 0) {
                $(this).attr("checked", true);
            }
        });
        $("#payable").html(setthous(top.paytot));
        $("#topay").val(top.paytot);
        $("#list").val(top.paylist.join(','));
    }
</script>
</head>
<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="mnulft",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="menu.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/Bills.jpg" width="240" height="300" />
          <div id='mnulft' style="width:70%; height:200px; margin:30px"></div></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblPayables.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td><table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="border:solid 2px #666666" bgcolor="#F9F7E6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" class="boldwhite1">
                          <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td align="center" valign="top" bgcolor="#FFFBF0">
                                <table width="100%" cellpadding="4" cellspacing="1" style="border: 2px #CCCCCC">
                                  <tr align="center" bgcolor="#666666" class="boldwhite1">
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Journal Entry #', $currentPage, 1, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Description', $currentPage, 2, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Client', $currentPage, 3, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Value', $currentPage, 4, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Date', $currentPage, 5, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Status', $currentPage, 6, $ord, $asc); ?></td>
                                  <td nowrap="nowrap"><?php echo setOrderTitle('Payed', $currentPage, 7, $ord, $asc); ?></td>
                                    <td nowrap="nowrap"><?php echo setOrderTitle('Posted', $currentPage, 8, $ord, $asc); ?></td>
                                    <td nowrap="nowrap">Pay</td>
                                  </tr>
                                  <?php $j=1;
	   foreach ($TBills as $row_TBills) {
	  $k=$j%2;
	  $rowdefcolor=($k==1) ? "#E5E5E5" : "#D5D5D5"; 
	  ?>
                                  <tr bgcolor="<?php echo $rowdefcolor ?>" class="black-normal" 
                                      onmouseover="setPointer(this, <?php echo $j ?>, 'over', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" onmouseout="setPointer(this, <?php echo $j ?>, 'out', '<?php echo $rowdefcolor ?>', '#CCFFCC', '#FFCC99');" ondblclick="location.href='view.php?id=<?php echo $row_TBills['BillID']; ?>&p=1'">
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TBills['BillID'] ?></b></td>
                                    <td align="center" nowrap="nowrap" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TBills['BillTitle'] ?></b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TBills['CustomerName'] ?></b></td>
                                    <td align="right" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TBills['code'], number_format($row_TBills['Amount'], 2) ?></b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TBills['BillDate'] ?></b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><b><?php echo $row_TBills['Category'] ?></b></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><?php echo $row_TBills['code'], number_format($row_TBills['Payed'], 2) ?></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><input type="checkbox" name="Posted" value=""  <?php echo $row_TBills['Posted']==1 ? "checked=\"checked\"": ""; ?> disabled="disabled" /></td>
                                    <td align="center" bgcolor="<?php echo $rowdefcolor ?>" class="black-normal"><?php if ($row_TBills['Posted']==1 && $row_TBills['Payed']!=$row_TBills['Amount']) { ?><input type="checkbox" name="paylist" value="<?php echo $row_TBills['BillID'] ?>" cur="<?php echo $row_TBills['currency'] ?>" amt="<?php echo $row_TBills['Amount']-$row_TBills['Payed'] ?>" vnd="<?php echo $row_TBills['VendorID'] ?>" onclick="Pay($(this))"  /><?php } ?></td>
                                    </tr>
                                  <?php $j++;} ?>
                                  </table></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                  <td align="center"><form id="frmpay" name="frmpay" method="post" action="" onsubmit="return validateFormPop(arrFormValidation)"><table border="2" cellspacing="2" cellpadding="2" bordercolor="#003300">
                  <tr class="boldwhite1">
                    <td bgcolor="#003300">Total Value</td>
                    <td align="center" bgcolor="#003300">Pay</td>
                    <td bgcolor="#003300">From</td>
                    <td bgcolor="#003300"><input type="hidden" name="list" id="list" />
                      <input type="hidden" name="topay" id="topay" name="topay" /></td>
                    </tr>
                  <tr>
                    <td align="center" bgcolor="#990000" class="boldwhite1" id="payable">&nbsp;</td>
                    <td bgcolor="#33CC00"><input type="text" name="pay" id="pay" /></td>
                    <td bgcolor="#33CC00"><select name="AccountID" id="AccountID">
                      <option value="" currency="0"></option>
                      <?php foreach ($TAccounts as $row_TAccounts) { ?>
                      <option value="<?php echo $row_TAccounts['VendorID'] ?>" currency="<?php echo $row_TAccounts['currency'] ?>"><?php echo $row_TAccounts['VendorName'] ?></option>
                      <?php } ?>
                      </select></td>
                    <td bgcolor="#33CC00"><input type="submit" name="button" id="button" value="Pay" /></td>
                  </tr>
                  <tr>
                    <td align="right" bgcolor="#333333" class="boldwhite1"">Method:</td>
                    <td colspan="3" align="center" bgcolor="#333333" class="boldwhite1""><table width="300" border="0" cellpadding="1" cellspacing="1">
                      <tr>
                        <td><select name="PaymentMethodID" id="PaymentMethodID" onchange="paytype(this.value)">
                          <option value="">Select Method</option>
                          <?php foreach ($TPayType as $row_TPayType) { ?>
                          <option value="<?php echo $row_TPayType['CategoryID'] ?>"><?php echo $row_TPayType['Category'] ?></option>
                          <?php } ?>
                        </select></td>
                        <td width="100%" align="left"><input type="hidden" name="PaymentMethod" />
                          <input type="hidden" name="AccountName" />
                          <input type="hidden" name="AccountNumber" />
                          <input type="hidden" name="CheckNumber" />
                          <input type="hidden" name="CheckDate" /></td>
                      </tr>
                      <tr>
                        <td colspan="2"><table border="0" cellspacing="1" cellpadding="1" id="pay21" style="display:none">
                          <tr>
                            <td align="right" nowrap="nowrap" class="boldwhite1">Card Type:</td>
                            <td><select name="CreditCardType" id="CreditCardType">
                              <option value=""></option>
                              <?php foreach ($TCards as $row_TCards) { ?>
                              <option value="<?php echo $row_TCards['CategoryID'] ?>" acc="<?php echo $row_TCards['par'] ?>"><?php echo $row_TCards['Category'] ?></option>
                              <?php } ?>
                            </select>
                              <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/accounts/cards/index.php', 480,520)" /></td>
                          </tr>
                          <tr>
                            <td align="right" nowrap="nowrap" class="boldwhite1">Card Holder:</td>
                            <td><input name="AccountName21" id="AccountName21" style="width:200px" onchange="setpaydet(21)" /></td>
                          </tr>
                          <tr>
                            <td align="right" nowrap="nowrap" class="boldwhite1">Card #:</td>
                            <td><input name="AccountNumber21" id="AccountNumber21" style="width:200px" onchange="setpaydet(21)" /></td>
                          </tr>
                        </table>
                          <table border="0" cellspacing="1" cellpadding="1" id="pay23" style="display:none">
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Bank:</td>
                              <td><input name="PaymentMethod23" id="PaymentMethod23" style="width:200px" onchange="setpaydet(23)" /></td>
                            </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Account Name:</td>
                              <td><input name="AccountName23" id="AccountName23" style="width:200px" onchange="setpaydet(23)" /></td>
                            </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Account #:</td>
                              <td><input name="AccountNumber23" id="AccountNumber23" style="width:200px" onchange="setpaydet(23)" /></td>
                            </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Cheque #:</td>
                              <td><input name="CheckNumber23" type="text" id="CheckNumber23" size="16" onchange="setpaydet(23)" /></td>
                            </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Cheque Date:</td>
                              <td><input name="CheckDate23" type="text" id="CheckDate23" size="16" onchange="setpaydet(23)" /></td>
                            </tr>
                          </table>
                          <table border="0" cellspacing="1" cellpadding="1" id="pay25" style="display:none">
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Institution/Method:</td>
                              <td><input name="PaymentMethod25" id="PaymentMethod25" style="width:200px" onchange="setpaydet(25)" /></td>
                            </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Payer Name:</td>
                              <td><input name="AccountName25" id="AccountName25" style="width:200px" onchange="setpaydet(25)" /></td>
                            </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Tracking #:</td>
                              <td><input name="CheckNumber25" type="text" id="CheckNumber25" size="16" onchange="setpaydet(25)" /></td>
                            </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Other Info:</td>
                              <td><input name="AccountNumber25" id="AccountNumber25" style="width:200px" onchange="setpaydet(25)" /></td>
                            </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Date:</td>
                              <td><input name="CheckDate25" type="text" id="CheckDate25" size="16" onchange="setpaydet(25)" /></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                    </tr>
                  <tr>
                    <td align="center" bgcolor="#333333" class="boldwhite1">Description:</td>
                    <td colspan="3" bgcolor="#333333"><input name="ExpenseTitle" style="width:300px" /></td>
                  </tr>
                  <tr>
                    <td align="right" bgcolor="#333333" class="boldwhite1">Notes:</td>
                    <td colspan="3" align="left" bgcolor="#333333" class="boldwhite1" id="payable4"><textarea name="Notes" style="width:300px" rows="3"></textarea></td>
                    </tr>
                  </table>
                </form>
                  </td>
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
