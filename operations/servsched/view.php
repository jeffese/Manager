<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Operations'));
$access = _xvar_arr_sub($_access, array('Service Schedule'));
vetAccess('Operations', 'Service Schedule', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, $access['Print'], 0, 0);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","pick.php","edit.php?id=$id","","[Currency]del.php?id=$id","","","","print.php?id=$id","index.php");
$rec_status = 1;

if (_xpost("MM_Post") == "frmpost") {
    $sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`
        WHERE `SrvSchedID`=$id";
    $row_TSched = getDBDataRow($dbh, $sql);

    if (_xpost('del') == '1') {
        $sql = "UPDATE {$_SESSION['DBCoy']}.`items_srv_sched` SET `enddate`=NOW(),`Status`=1 
             WHERE `SrvSchedID`=$id";
        runDBQry($dbh, $sql);
    } elseif (_xpost('AssetID') != $row_TSched['AssetID']) {
        $sql = sprintf("INSERT INTO `%s`.`items_srv_sched` (`InvoiceDetailID`, `AssetID`, 
            `MachineTime`, `EmployeeID`, `startdate`, `enddate`, `renew`, `Notes`, `Status`) 
            VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                    $_SESSION['DBCoy'],
                    $row_TSched['InvoiceDetailID'],
                    GSQLStr(_xpost('AssetID'), "intn"),
                    GSQLStr(_xpost('MachineTime'), "double"),
                    GSQLStr($_SESSION['ids']['VendorID'], "int"),
                    "NOW()",
                    "'{$row_TSched['enddate']}'",
                    _xpostchk('renew'),
                    GSQLStr(_xpost('Notes'), "text"),
                    GSQLStr(_xpost('Status'), "int"));
        $insert = runDBQry($dbh, $sql);
        
        if ($insert > 0) {
            $recid = mysqli_insert_id($dbh);
            docs('Operations'.DS.'Service_Schedule', $recid);
            $sql = sprintf("UPDATE `%s`.`invoicedetails` SET `serials`=CONCAT(`serials`,',','%s')
                 WHERE `InvoiceDetailID`=%s",
                        $_SESSION['DBCoy'],
                        $recid,
                        GSQLStr(_xpost('InvoiceDetailID'), "int"));
            runDBQry($dbh, $sql);
            $sql = "UPDATE {$_SESSION['DBCoy']}.`items_srv_sched` SET `enddate`=NOW(),`Status`=1 
                 WHERE `SrvSchedID`=$id";
            runDBQry($dbh, $sql);

            $assetid = _xpost('AssetID');
            if ($assetid > 0) {
                setAssOwn($assetid, _xpost('desgtype'), _xpost('occupant'), 1);
                setAssOwn($row_TSched['AssetID'], 0, 0, 0);
            }

            $id = $recid;
        }

    } else {
        $sql = sprintf("UPDATE `%s`.`items_srv_sched` SET `renew`=%s, `Notes`=%s, `Status`=%s 
             WHERE `SrvSchedID`=$id",
                    $_SESSION['DBCoy'],
                    _xpostchk('renew'),
                    GSQLStr(_xpost('Notes'), "text"),
                    GSQLStr(_xpost('Status'), "int"));
        runDBQry($dbh, $sql);
        docs('Operations'.DS.'Service_Schedule', $id);
    }
}

$vendor_stf = vendorFlds("emp", "staff");
$sql = "SELECT `items_srv_sched`.*, ProductName, $vendor_sql, $vendor_stf, AssetName, status.Category, 
    `useasset`, `assetcat`, `items_srv_sched`.InvoiceDetailID, `invoicedetails`.InvoiceID, 
    IF(`enddate`>NOW(),0,1) AS `expired`, `desgtype`, `occupant`
    FROM `{$_SESSION['DBCoy']}`.`items_srv_sched`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoicedetails` ON `items_srv_sched`.InvoiceDetailID=`invoicedetails`.`InvoiceDetailID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv`      ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`       ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`        ON `invoices`.VendorID=`vendors`.VendorID
    INNER JOIN `{$_SESSION['DBCoy']}`.`status`         ON `items_srv_sched`.Status=status.CategoryID
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors` emp    ON `items_srv_sched`.EmployeeID=`emp`.VendorID
    LEFT  JOIN `{$_SESSION['DBCoy']}`.`assets`         ON `items_srv_sched`.AssetID=`assets`.AssetID
    WHERE `SrvSchedID`=$id";
$row_TSched = getDBDataRow($dbh, $sql);

$sql = "SELECT AssetID, AssetName FROM `{$_SESSION['DBCoy']}`.`assets` 
    WHERE Category IN ({$row_TSched['assetcat']}) AND `occupant`=0 OR AssetID={$row_TSched['AssetID']}
    ORDER BY `AssetName`";
$TAssets = getDBData($dbh, $sql);

$TStatus  = getCat('srv_schd_status');
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
<script type="text/javascript">
    function showCmps() {
        <?php if ($row_TSched['useasset']==1) { ?>
        $('#AssetID').show();
        <?php } ?>
        $('#Status').show();
    }
    
    function hideCmps() {
        <?php if ($row_TSched['useasset']==1) { ?>
        $('#AssetID').hide();
        <?php } ?>
        $('#Status').hide();
    }
    
    function Post() {
        if (confirm("Are you sure you want to Post these Changes?"))
            $("#frmpost").submit();
    }
    
    function Del() {
        if (confirm("Are you sure you want to Terminate this Service?")) {
            $('#del').val('1');
            $("#frmpost").submit();
        }
    }
    
    function edit() {
        $('#edit').hide();
        $('#assname').show();
        $('#Status').show();
        $('#accpt').show();
        $('#ndocs').show();
        $('#AssetID').show();
        $('#renew').attr("disabled", "");
        $('#Notes').attr("readonly", "");
    }
    
    function cancel() {
        $('#edit').show();
        $('#accpt').hide();
        $('#assname').hide();
        $('#Status').hide();
        $('#ndocs').hide();
        $('#AssetID').hide();
        $('#renew').attr("disabled", "disabled");
        $('#Notes').attr("readonly", "readonly");
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
          <td width="240" valign="top"><img src="/images/srvsched.jpg" alt="" width="240" height="300" /></td>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lblsrvsched.png); background-repeat:no-repeat">&nbsp;</td>
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
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><form id="frmpost" name="frmpost" method="post" action=""><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td class="titles">Service:</td>
                    <td align="left"><?php echo $row_TSched['ProductName'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Client:</td>
                    <td><?php echo $row_TSched['VendorName'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Invoice #:</td>
                    <td><?php echo $row_TSched['InvoiceID'], ' : ', $row_TSched['InvoiceDetailID'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Asset:</td>
                    <td><span id="assname"><?php if ($row_TSched['useasset']==1) { ?>
                        <?php echo $row_TSched['AssetName'] ?>
                      <select name="AssetID" id="AssetID" style="display:none">
                        <?php foreach ($TAssets as $row_TAssets) { ?>
                        <option value="<?php echo $row_TAssets['AssetID'] ?>" <?php if (!(strcmp($row_TAssets['AssetID'], $row_TSched['AssetID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TAssets['AssetName'] ?></option>
                        <?php } ?>
                      </select>
                        <?php } ?>
                    </span></td>
                  </tr>
                  <tr>
                    <td class="titles">Schedule:</td>
                    <td><table border="0" cellpadding="2" cellspacing="2" id="timeframe">
                      <tr>
                        <td><?php echo $row_TSched['startdate'] ?></td>
                        <td class="black-normal">to</td>
                        <td><?php echo $row_TSched['enddate'] ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Auto Renew:</td>
                    <td><input type="checkbox" name="renew" id="renew" disabled="disabled"<?php if ($row_TSched['renew'] == 1) {
                echo " checked=\"checked\"";
            } ?> /></td>
                  </tr>
                  <tr>
                    <td class="titles">Status: </td>
                    <td><?php echo $row_TSched['Category'] ?>
                      <select name="Status" id="Status" style="display:none">
                        <?php foreach ($TStatus as $row_TStatus) { ?>
                        <option value="<?php echo $row_TStatus['CategoryID'] ?>" <?php if (!(strcmp($row_TStatus['CategoryID'], $row_TSched['Status']))) { echo "selected=\"selected\""; }?>><?php echo $row_TStatus['Category'] ?></option>
                        <?php } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Staff:</td>
                    <td><?php echo $row_TSched['staff'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Notes:</td>
                    <td><textarea name="Notes" id="Notes" rows="5" readonly="readonly" style="width:450px"><?php echo $row_TSched['Notes'] ?></textarea></td>
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
                            <td><?php $doc_shelf = 'Operations'.DS.'Service_Schedule';
							$doc_id = $id; ?>
                              <?php include "../../scripts/viewdoc.php" ?>
<span id="ndocs" style="display:none"><?php include "../../scripts/newdoc.php" ?></span></td>
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
                    <td class="titles">&nbsp;
                        <input name="occupant" type="hidden" value="<?php echo $row_TSched['occupant'] ?>" />
                        <input name="desgtype" type="hidden" value="<?php echo $row_TSched['desgtype'] ?>" /></td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                          <?php if ($access['Edit'] == 1 && $row_TSched['expired'] == 0 && $row_TSched['Status'] != 0) { ?>
                          <td><a id="edit" href="javascript: void(0)" onclick="edit()"><img src="/images/but_edit.png" width="60" height="20" /></a></td>
                          <td><input type="hidden" name="MM_Post" value="frmpost" />
                            <input name="schdid" type="hidden" id="schdid" value="<?php echo $id ?>" /></td>
                          <td><table border="0" cellspacing="1" cellpadding="1" id="accpt" style="display:none">
                            <tr>
                              <td><a id="cncl" href="javascript: void(0)" onclick="cancel()"><img src="/images/cancel.png" width="60" height="20" /></a></td>
                              <td>&nbsp;</td>
                              <td><a id="post" href="javascript: void(0)" onclick="Post()"><img src="/images/post.png" width="50" height="20" /></a></td>
                              <?php if ($access['Del'] == 1) { ?>
                              <td>&nbsp;<input name="del" type="hidden" id="del" value="0" /></td>
                              <td><a id="del" href="javascript: void(0)" onclick="Del()"><img src="/images/but_del.png" width="60" height="20" /></a></td>
                              <?php } ?>
                            </tr>
                          </table></td>
                          <?php } ?>
                        </tr>
                  </table></td>
                  </tr>
                </table>
                </form></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php'); ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>