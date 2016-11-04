<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Journal'));
vetAccess('Accounts', 'Journal', 'View');

$id = intval(_xget('id'));
$p = _xget('p');
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id".($p == "1" ? "&p=1" : ""),"","[Journal Entry]del.php?id=$id","","","","print.php?id=$id", $p == "1" ? "payables.php" : "index.php");
$rec_status = 1;

if (_xpost("MM_Post") == "frmpost") {
    try {
        $dbh->autocommit(FALSE);
        $sql = "`{$_SESSION['DBCoy']}`.`bills` 
            INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` ON `bills`.VendorID=`vendors`.VendorID";
        runDBQry($dbh, "SELECT * FROM $sql 
            WHERE `BillID`=$id AND `Posted`=0 LOCK IN SHARE MODE");
        
        runDBQry($dbh, "UPDATE $sql
            SET `Posted`=1, LedgerDate=NOW(), RecAccountBalance=IF(entrytype=1,amtbal-Amount,amtbal+Amount), 
            amtbal=IF(entrytype=1,amtbal-Amount,amtbal+Amount)
            WHERE `BillID`=$id AND `Posted`=0");
        $dbh->commit();
    } catch (Exception $ex) {
        $dbh->rollback();
        array_push($errors, array("Error", $ex->getMessage()));
    }
    $dbh->autocommit(TRUE);
}

$creditor_sql = vendorFlds("creditor", "person");
$sql = "SELECT `bills`.*, $vendor_sql, $creditor_sql, vendortypes.VendorType AS vtype, 
    currencies.code, `typ`.catname, `dept`.catname AS catn, Category 
    FROM `{$_SESSION['DBCoy']}`.`bills`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`          ON `bills`.EmployeeID=`vendors`.VendorID 
    INNER JOIN `" . DB_NAME . "`.`vendortypes`           ON `bills`.VendorType=`vendortypes`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` creditor ON `bills`.VendorID=`creditor`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`currencies`       ON `creditor`.currency=currencies.cur_id 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `typ` ON `bills`.BillType=typ.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` `dept` ON `bills`.Dept=dept.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`            ON `bills`.Status=status.CategoryID 
    WHERE `BillID`=$id";
$row_TBills = getDBDataRow($dbh, $sql);

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], AccStat($access['Edit'], $row_TBills['Posted']), AccStat($access['Del'], $row_TBills['Posted']), $access['Print'], 0, 0);
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
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
<script language="JavaScript1.2" type="text/javascript">
    window.onload = function() {
        isPayable();
    }
</script>
</head>
<body>
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
                    <td align="left"><input type="checkbox" name="payable" id="payable"<?php if ($row_TBills['payable'] == 1) echo " checked=\"checked\"" ?> disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td class="titles" id="titleEntry">Invoice #:</td>
                    <td align="left"><table id="tabentry">
                      <tr>
                        <td><input type="radio" name="entrytype" value="1" id="entrytype_0"<?php if ($row_TBills['entrytype'] == 1) echo " checked=\"checked\"" ?> disabled="disabled" /></td>
                        <td class="blue-normal"><strong>Debit</strong></td>
                        <td>&nbsp;</td>
                        <td><input type="radio" name="entrytype" value="2" id="entrytype_1"<?php if ($row_TBills['entrytype'] == 2) echo " checked=\"checked\"" ?> disabled="disabled" /></td>
                        <td class="blue-normal"><strong>Credit</strong></td>
                      </tr>
                    </table>
                      <span id="InvoiceID"><?php echo $row_TBills['InvoiceID'] ?></span></td>
                  </tr>
                  <tr>
                    <td class="titles">Description:</td>
                    <td><?php echo $row_TBills['BillTitle'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Client:</td>
                    <td align="left"><table width="300" border="0" cellpadding="1" cellspacing="1">
                      <tr>
                        <td nowrap="nowrap" bgcolor="#000000" class="boldwhite1"><strong><?php echo $row_TBills['vtype'] ?>:</strong></td>
                        <td align="left" class="blue-normal">&nbsp;</td>
                        <td width="100%" align="left" bgcolor="#003366" class="boldwhite1"><?php echo $row_TBills['person'] ?></td>
                      </tr>
                      <tr>
                        <td colspan="3" bgcolor="#999999"><?php echo $row_TBills['CustomerName'] ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Value:</td>
                    <td><table border="0" cellspacing="1" cellpadding="1">
                      <tr>
                        <td><?php echo $row_TBills['code'] ?></td>
                        <td><?php echo $row_TBills['Amount'] ?></td>
                        </tr>
                      </table></td>
                  </tr>
                  <?php if ($row_TBills['payable']==1) { ?>
                  <tr>
                    <td class="titles">Paid:</td>
                    <td align="left"><?php echo $row_TBills['Payed'] ?></td>
                  </tr>
                  <?php } ?>
                  <tr>
                    <td class="titles">Journal Entry Date:</td>
                    <td align="left"><?php echo $row_TBills['BillDate'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Date Received:</td>
                    <td align="left"><?php echo $row_TBills['ReceivedDate'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td align="left"><?php echo $row_TBills['catn']; ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td><?php echo $row_TBills['catname'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Status: </td>
                    <td><?php echo $row_TBills['Category'] ?></td>
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
                    <td align="left"><textarea name="Notes" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TBills['Notes'] ?></textarea></td>
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
                            <td><?php $doc_shelf = 'Accounts'.DS.'Bills';
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
                </table></td>
              </tr>
              <tr>
                <td align="center"><?php if ($access['Post'] == 1 && $row_TBills['Posted'] == 0) { ?>
                  <table border="0" cellspacing="1" cellpadding="1">
                    <tr>
                      <td><a id="post" href="javascript: void(0)" onclick="Post()"><img src="/images/post.png" width="50" height="20" /></a></td>
                      <td><form id="frmpost" name="frmpost" method="post" action="">
                        <input type="hidden" name="MM_Post" value="frmpost" />
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
