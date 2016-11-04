<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Expenses'));
vetAccess('Accounts', 'Expenses', 'View');

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Expense]del.php?id=$id","","","","print.php?id=$id","index.php");
$rec_status = 1;

if (_xpost("MM_Post") == "frmpost") {
    try {
        $dbh->autocommit(FALSE);
        
        $bill = 0;
        $bal = "`vendors`.amtbal=`vendors`.amtbal-Amount, RecAccountBalance=`vendors`.amtbal-Amount,";
        if (isset($_POST['journal'])) {
            $sql = sprintf("INSERT INTO `%1\$s`.`bills`(`Dept`, `OutetID`, `EmployeeID`, `InvoiceID`, `BillTitle`, 
                `VendorType`, `VendorID`, `CustomerName`, `BillType`, `Status`, `BillDate`, 
                `ReceivedDate`, `Amount`, `Notes`, `Posted`, `RecAccountBalance`, `Payed`, 
                `LedgerDate`, `payable`, `entrytype`) 
                SELECT `Dept`, `OutetID`, `EmployeeID`,`InvoiceID`,`ExpenseTitle`,
                `expenses`.`VendorType`,`expenses`.`VendorID`,`Recipient`,`ExpenseType`,`Status`,`ExpenseDate`,
                `DateSubmitted`,`Amount`,`expenses`.`Notes`,1,`amtbal`+`Amount`,`Amount`,NOW(),1,2
                    FROM `%1\$s`.`vendors` 
                INNER JOIN `%1\$s`.`expenses`     ON `vendors`.VendorID=`expenses`.VendorID ",
                                       $_SESSION['DBCoy']);
            $bill = runDBQry($dbh, $sql);
            $billid = mysqli_insert_id($dbh);
            $bal = "`RecAccountBalance`=`vendors`.`amtbal`, `bills`=$billid, `payments`=`Amount`,";
        }
        
        $sql = "`{$_SESSION['DBCoy']}`.`expenses` 
            INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`     ON `expenses`.VendorID=`vendors`.VendorID 
            INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` acc ON `expenses`.AccountID=`acc`.VendorID";
        runDBQry($dbh, "SELECT * FROM $sql 
            WHERE `ExpenseID`=$id AND `posted`=0 LOCK IN SHARE MODE");
        
        $update = runDBQry($dbh, "UPDATE $sql
            SET `posted`=1, LedgerDate=NOW(), $bal
            `acc`.`amtbal`=`acc`.`amtbal`-`Amount`, AccountBalance=`acc`.amtbal-Amount 
            WHERE `ExpenseID`=$id AND `posted`=0");
        
        if (isset($_POST['journal']) && $billid < 1 || $update < 1) {
            throw new Exception("Not updated");
        }
        $dbh->commit();
    } catch (Exception $ex) {
        $dbh->rollback();
        array_push($errors, array("Error", $ex->getMessage()));
    }
    $dbh->autocommit(TRUE);
}

$payee_sql = vendorFlds("payee", "person");
$acc_sql = vendorFlds("acc", "acc_person");
$sql = "SELECT `expenses`.*, $vendor_sql, $payee_sql, $acc_sql, vendortypes.VendorType AS vtype, 
    currencies.code, `typ`.catname, `dept`.catname AS catn, status.Category, paytp.Category AS paytype, cards.Category AS card
    FROM `{$_SESSION['DBCoy']}`.`expenses`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`        ON `expenses`.EmployeeID=`vendors`.VendorID 
    INNER JOIN `" . DB_NAME . "`.`vendortypes`         ON `expenses`.VendorType=`vendortypes`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` payee  ON `expenses`.VendorID=`payee`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` acc    ON `expenses`.AccountID=`acc`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`currencies`     ON `payee`.currency=currencies.cur_id 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `typ` ON `expenses`.ExpenseType=typ.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `dept` ON `expenses`.Dept=dept.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`          ON `expenses`.Status=status.CategoryID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`  paytp   ON `expenses`.PaymentMethodID=paytp.CategoryID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`  cards   ON `expenses`.CreditCardType=cards.CategoryID 
    WHERE `ExpenseID`=$id";
$row_TExpenses = getDBDataRow($dbh, $sql);

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], AccStat($access['Edit'], $row_TExpenses['Posted']), AccStat($access['Del'], $row_TExpenses['Posted']), $access['Print'], 0, 0);
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
<script language="JavaScript1.2" type="text/javascript">
    window.onload = function() {
        paytype(<?php echo $row_TExpenses['PaymentMethodID']; ?>);
    }
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="240" valign="top"><img src="/images/expenses.jpg" alt="" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblexpenses.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Expense ID:</td>
                    <td class="red-normal"><b><?php echo $row_TExpenses['ExpenseID']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Invoice #:</td>
                    <td align="left"><?php echo $row_TExpenses['InvoiceID'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Description:</td>
                    <td><?php echo $row_TExpenses['ExpenseTitle'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Payee:</td>
                    <td align="left"><table width="300" border="0" cellpadding="1" cellspacing="1">
                      <tr class="boldwhite1">
                        <td nowrap="nowrap" bgcolor="#000000"><span class="boldwhite1"><strong><?php echo $row_TExpenses['vtype'] ?>:</strong></span></td>
                        <td align="left">&nbsp;</td>
                        <td width="100%" align="left" bgcolor="#003366"><?php echo $row_TExpenses['person'] ?></td>
                      </tr>
                      <tr>
                        <td colspan="3" bgcolor="#999999"><?php echo $row_TExpenses['Recipient'] ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Value:</td>
                    <td><table border="0" cellspacing="1" cellpadding="1">
                      <tr>
                        <td><?php echo $row_TExpenses['code'] ?></td>
                        <td><?php echo $row_TExpenses['Amount'] ?></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Account:</td>
                    <td><?php echo $row_TExpenses['acc_person'] ?></td>
                    </tr>
                  <tr>
                    <td class="titles">Payment:</td>
                    <td bgcolor="#999999"><table width="300" border="0" cellpadding="1" cellspacing="1">
                      <tr>
                        <td bgcolor="#000000" class="boldwhite1"><?php echo $row_TExpenses['paytype'] ?></td>
                        </tr>
                      <tr>
                        <td><table border="0" cellspacing="1" cellpadding="1" id="pay21" style="display:none">
                          <tr>
                            <td align="right" nowrap="nowrap" class="boldwhite1">Card Type:</td>
                            <td><?php echo $row_TExpenses['card'] ?></td>
                            </tr>
                          <tr>
                            <td align="right" nowrap="nowrap" class="boldwhite1">Card Holder:</td>
                            <td><?php echo $row_TExpenses['AccountName'] ?></td>
                            </tr>
                          <tr>
                            <td align="right" nowrap="nowrap" class="boldwhite1">Card #:</td>
                            <td><?php echo $row_TExpenses['AccountNumber'] ?></td>
                            </tr>
                          </table>
                          <table border="0" cellspacing="1" cellpadding="1" id="pay23" style="display:none">
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Bank:</td>
                              <td><?php echo $row_TExpenses['PaymentMethod'] ?></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Account Name:</td>
                              <td><?php echo $row_TExpenses['AccountName'] ?></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Account #:</td>
                              <td><?php echo $row_TExpenses['AccountNumber'] ?></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Cheque #:</td>
                              <td><?php echo $row_TExpenses['CheckNumber'] ?></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Cheque Date:</td>
                              <td><?php echo $row_TExpenses['CheckDate'] ?></td>
                              </tr>
                            </table>
                          <table border="0" cellspacing="1" cellpadding="1" id="pay25" style="display:none">
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Institution/Method:</td>
                              <td><?php echo $row_TExpenses['PaymentMethod'] ?></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Payer Name:</td>
                              <td><?php echo $row_TExpenses['AccountName'] ?></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Tracking #:</td>
                              <td><?php echo $row_TExpenses['CheckNumber'] ?></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Other Info:</td>
                              <td><?php echo $row_TExpenses['AccountNumber'] ?></td>
                              </tr>
                            <tr>
                              <td align="right" nowrap="nowrap" class="boldwhite1">Date:</td>
                              <td><?php echo $row_TExpenses['CheckDate'] ?></td>
                              </tr>
                            </table></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Expense Date:</td>
                    <td align="left"><?php echo $row_TExpenses['ExpenseDate'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Date Received:</td>
                    <td align="left"><?php echo $row_TExpenses['DateSubmitted'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td><?php echo $row_TExpenses['catn'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td><?php echo $row_TExpenses['catname'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Status: </td>
                    <td><?php echo $row_TExpenses['Category'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Posted:</td>
                    <td><input type="checkbox" name="Posted"<?php if ($row_TExpenses['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Staff:</td>
                    <td><?php echo $row_TExpenses['VendorName'] ?></td>
                  </tr>
                  <tr>
                    <td valign="top" class="titles">Notes:</td>
                    <td align="left"><textarea name="Notes" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TExpenses['Notes'] ?></textarea></td>
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
                            <td><?php $doc_shelf = 'Accounts'.DS.'Expenses';
							$doc_id = $id; ?>
                              <?php include '../../scripts/viewdoc.php' ?></td>
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
                <td align="center"><?php if ($access['Post'] == 1 && $row_TExpenses['Posted'] == 0) { ?>
                  <table border="0" cellspacing="1" cellpadding="1">
                    <tr>
                      <td><a id="post" href="javascript: void(0)" onclick="Post()"><img src="/images/post.png" width="50" height="20" /></a></td>
                      <td><form id="frmpost" name="frmpost" method="post" action="">
                        <table border="0" cellspacing="2" cellpadding="2">
                          <tr>
                            <td><input type="hidden" name="MM_Post" value="frmpost" />                              <input type="checkbox" name="journal" id="journal" /></td>
                            <td class="red-normal"><strong>Create Journal Entry</strong></td>
                            </tr>
                      </table>
                      </form></td>
                    </tr>
                  </table>
                <?php } ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php'); ?></td>
              </tr>

            </table>
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
