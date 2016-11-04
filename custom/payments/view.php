<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Accounts'));
$access = _xvar_arr_sub($_access, array('Payments'));
vetAccess('Accounts', 'Payments', 'View');

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","0","","","","","","print.php?id=$id","index.php");
$rec_status = 1;

if (_xpost("MM_Post") == "frmpost") {
    $sql = "UPDATE `{$_SESSION['DBCoy']}`.`payments` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`     ON `payments`.VendorID=`vendors`.VendorID 
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` acc ON `payments`.AccountID=`acc`.VendorID 
        SET `posted`=1, LedgerDate=NOW(), 
        `vendors`.amtbal=`vendors`.amtbal+Amount, RecAccountBalance=`vendors`.amtbal+Amount,
        `acc`.amtbal=`acc`.amtbal+Amount, AccountBalance=`acc`.amtbal+Amount 
        WHERE `PaymentID`=$id";
    runDBQry($dbh, $sql);
}

$outid = _xses('OutletID');
$Payer_sql = vendorFlds("Payer", "person");
$acc_sql = vendorFlds("acc", "acc_person");
$sql = "SELECT `payments`.*, OutletName, $vendor_sql, $Payer_sql, $acc_sql, vendortypes.VendorType AS vtype, 
    currencies.code, catname, status.Category, paytp.Category AS paytype, cards.Category AS card
    FROM `{$_SESSION['DBCoy']}`.`payments`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`        ON `payments`.EmployeeID=`vendors`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`outlets`        ON `payments`.OutletID=`outlets`.OutletID 
    INNER JOIN `" . DB_NAME . "`.`vendortypes`         ON `payments`.VendorType=`vendortypes`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` Payer  ON `payments`.VendorID=`Payer`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` acc    ON `payments`.AccountID=`acc`.VendorID 
    INNER JOIN `{$_SESSION['DBCoy']}`.`currencies`     ON `Payer`.currency=currencies.cur_id 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` ON `payments`.PaymentType=classifications.catID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`          ON `payments`.Status=status.CategoryID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`  paytp   ON `payments`.PaymentMethodID=paytp.CategoryID 
    LEFT JOIN `{$_SESSION['DBCoy']}`.`status`  cards   ON `payments`.CreditCardType=cards.CategoryID 
    WHERE `PaymentID`=$id AND `payments`.OutletID IN ($outid)";
$row_TPayments = getDBDataRow($dbh, $sql);

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, $access['Print'], 0, 0,0);
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
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<script type="text/javascript" src="/scripts/js/set.js"></script>
<script type="text/javascript"> 
<!--
window.onload = function() {
	setContent();
        paytype(<?php echo $row_TPayments['PaymentMethodID']; ?>);
}
window.onresize = function() {
	setContent();
}

//--> 
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
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
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
  <table width="100%" border="0" cellspacing="4" cellpadding="4">
    <tr>
      <td><table border="0" cellpadding="4" cellspacing="4">
        <tr>
          <td width="120" class="titles">Payment ID:</td>
          <td align="left" class="red-normal"><b><?php echo $row_TPayments['PaymentID']; ?></b></td>
          </tr>
        <tr>
          <td class="titles">Outlet:</td>
          <td align="left"><b><?php echo $row_TPayments['OutletName']; ?></b></td>
          </tr>
        <tr>
          <td class="titles">Payer:</td>
          <td align="left"><?php echo $row_TPayments['person'] ?></td>
          </tr>
        <tr>
          <td class="titles">Value:</td>
          <td align="left"><table border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td><?php echo $row_TPayments['code'] ?></td>
              <td><?php echo $row_TPayments['Amount'] ?></td>
              </tr>
            </table></td>
          </tr>
        <tr>
          <td class="titles">Payment:</td>
          <td align="left" bgcolor="#999999"><table width="300" border="0" cellpadding="1" cellspacing="1">
            <tr>
              <td bgcolor="#666666" class="boldwhite1"><?php echo $row_TPayments['paytype'] ?></td>
              </tr>
            <tr>
              <td><table border="0" cellspacing="1" cellpadding="1" id="pay21" style="display:none">
                <tr>
                  <td align="right" nowrap="nowrap" class="boldwhite1">Card Type:</td>
                  <td><?php echo $row_TPayments['card'] ?></td>
                  </tr>
                <tr>
                  <td align="right" nowrap="nowrap" class="boldwhite1">Card Holder:</td>
                  <td><?php echo $row_TPayments['AccountName'] ?></td>
                  </tr>
                <tr>
                  <td align="right" nowrap="nowrap" class="boldwhite1">Card #:</td>
                  <td><?php echo $row_TPayments['AccountNumber'] ?></td>
                  </tr>
                </table>
                <table border="0" cellspacing="1" cellpadding="1" id="pay23" style="display:none">
                  <tr>
                    <td align="right" nowrap="nowrap" class="boldwhite1">Bank:</td>
                    <td><?php echo $row_TPayments['PaymentMethod'] ?></td>
                    </tr>
                  <tr>
                    <td align="right" nowrap="nowrap" class="boldwhite1">Account Name:</td>
                    <td><?php echo $row_TPayments['AccountName'] ?></td>
                    </tr>
                  <tr>
                    <td align="right" nowrap="nowrap" class="boldwhite1">Account #:</td>
                    <td><?php echo $row_TPayments['AccountNumber'] ?></td>
                    </tr>
                  <tr>
                    <td align="right" nowrap="nowrap" class="boldwhite1">Cheque #:</td>
                    <td><?php echo $row_TPayments['CheckNumber'] ?></td>
                    </tr>
                  <tr>
                    <td align="right" nowrap="nowrap" class="boldwhite1">Cheque Date:</td>
                    <td><?php echo $row_TPayments['CheckDate'] ?></td>
                    </tr>
                  </table>
                <table border="0" cellspacing="1" cellpadding="1" id="pay25" style="display:none">
                  <tr>
                    <td align="right" nowrap="nowrap" class="boldwhite1">Institution/Method:</td>
                    <td><?php echo $row_TPayments['PaymentMethod'] ?></td>
                    </tr>
                  <tr>
                    <td align="right" nowrap="nowrap" class="boldwhite1">Payer Name:</td>
                    <td><?php echo $row_TPayments['AccountName'] ?></td>
                    </tr>
                  <tr>
                    <td align="right" nowrap="nowrap" class="boldwhite1">Tracking #:</td>
                    <td><?php echo $row_TPayments['CheckNumber'] ?></td>
                    </tr>
                  <tr>
                    <td align="right" nowrap="nowrap" class="boldwhite1">Other Info:</td>
                    <td><?php echo $row_TPayments['AccountNumber'] ?></td>
                    </tr>
                  <tr>
                    <td align="right" nowrap="nowrap" class="boldwhite1">Date:</td>
                    <td><?php echo $row_TPayments['CheckDate'] ?></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
          </tr>
        <tr>
          <td class="titles">Payment Date:</td>
          <td align="left"><?php echo $row_TPayments['PaymentDate'] ?></td>
          </tr>
        <tr>
          <td class="titles">Posted:</td>
          <td align="left"><input type="checkbox" name="Posted"<?php if ($row_TPayments['Posted'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
          </tr>
        <tr>
          <td valign="top" class="titles">Notes:</td>
          <td align="left"><textarea name="Notes" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TPayments['Notes'] ?></textarea></td>
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
                  <td><?php $doc_shelf = 'Accounts'.DS.'Payments';
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
      <td></td>
      </tr>
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
