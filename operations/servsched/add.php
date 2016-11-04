<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Operations'));
$access = _xvar_arr_sub($_access, array('Service Schedule'));
vetAccess('Operations', 'Service Schedule', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmsrvschd","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmsrvschd") {
    $sql = sprintf("INSERT INTO `%s`.`items_srv_sched` (`InvoiceDetailID`, `AssetID`, 
        `MachineTime`, `EmployeeID`, `startdate`, `enddate`, `renew`, `Notes`, `Status`) 
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                $_SESSION['DBCoy'],
                GSQLStr(_xpost('InvoiceDetailID'), "int"),
                GSQLStr(_xpost('AssetID'), "intn"),
                GSQLStr(_xpost('MachineTime'), "double"),
                GSQLStr($_SESSION['ids']['VendorID'], "int"),
                GSQLStr(_xpost('startdate'), "date"),
                GSQLStr(_xpost('enddate'), "date"),
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
        
        $assetid = _xpost('AssetID');
        if ($assetid > 0) {
            setAssOwn($assetid, _xpost('VendorType'), _xpost('VendorID'), 1);
        }
        
        header("Location: view.php?id=$recid");
        exit;
    }
}

$inv = intval(_xget('inv'));
$sql = "SELECT `InvoiceDetailID`, `invoices`.`InvoiceID`, `invoices`.`VendorID`, `vendors`.`VendorType`, 
    $vendor_sql, `invoicedetails`.`ProductName`, `units`, `items_srv`.*
    FROM `{$_SESSION['DBCoy']}`.`invoicedetails`
    INNER JOIN `{$_SESSION['DBCoy']}`.`items_srv` ON `invoicedetails`.ProductID=`items_srv`.`ServiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`invoices`  ON `invoicedetails`.InvoiceID=`invoices`.`InvoiceID`
    INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`   ON `invoices`.VendorID=`vendors`.VendorID
    WHERE `InvoiceDetailID`=$inv";
$row_TSched = getDBDataRow($dbh, $sql);

$sql = "SELECT AssetID, AssetName FROM `{$_SESSION['DBCoy']}`.`assets` 
    WHERE Category IN ({$row_TSched['assetcat']}) AND `occupant`=0 
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
<script language="JavaScript1.2" src="script.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
<link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
<script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
<script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
<script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        <?php if ($row_TSched['useasset']==1) { ?>
        ["AssetID", "", 
            ["req", "Select Asset"]
        ],
        <?php } ?>
        ["start_date", "", 
            ["req", "Select Starting Date & Time"]
        ],
        ["Status", "", 
            ["req", "Select Status"]
        ]
    ];
    var ptyp=<?php echo $row_TSched['event_length'] ?>, num=<?php echo $row_TSched['units'] ?>, 
        tmtyp=<?php echo $row_TSched['timetype'] ?>, prds=<?php echo $row_TSched['periods'] ?>, 
        st='<?php echo $row_TSched['starttime'] ?>', en='<?php echo $row_TSched['endtime'] ?>';
    window.onload = function() {
        var mCal = new dhtmlxCalendarObject('start_date', true, {isYearEditable: true, isMonthEditable: true});
        mCal.setSkin('dhx_black');
        mCal.attachEvent("onClick",function(date){
            setSched();
        });
        setSched();
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
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmsrvschd" id="frmsrvschd">
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
                    <td class="titles">Service:</td>
                    <td align="left"><?php echo $row_TSched['ProductName'] ?>
                      <input type="hidden" name="InvoiceDetailID" value="<?php echo _xget('inv'); ?>" />
                      <input type="hidden" name="VendorID" value="<?php echo $row_TSched['VendorID'] ?>" />
                      <input type="hidden" name="VendorType" value="<?php echo $row_TSched['VendorType'] ?>" /></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Client:</td>
                    <td><?php echo $row_TSched['VendorName'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Invoice #:</td>
                    <td><?php echo $row_TSched['InvoiceID'] ?> : <?php echo _xget('inv'); ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Asset:</td>
                    <td><select name="AssetID" id="AssetID"<?php echo $row_TSched['useasset']==0 ? 'style="display:none"':'' ?>>
                      <option value=""></option>
                      <?php foreach ($TAssets as $row_TAssets) { ?>
                      <option value="<?php echo $row_TAssets['AssetID'] ?>"><?php echo $row_TAssets['AssetName'] ?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Schedule:</td>
                    <td><table border="0" cellpadding="2" cellspacing="2" id="timeframe">
                      <tr>
                        <td><input name="start_date" type="text" id="start_date" size="12" value="<?php echo date('Y-m-d'); ?>" readonly="readonly" onchange="setSched()" /></td>
                        <td><input type="hidden" name="startdate" id="startdate" /></td>
                        <td><select name="start_hour" id="start_hour" onchange="setSched()">
                            <?php $tm = 0; while ($tm < 24) { ?>
                                <option value="<?php echo $tm ?>"><?php echo str_pad($tm==0?'12':($tm>12?$tm-12:$tm), 2, "0", STR_PAD_LEFT), " ", ($tm > 11 ? 'PM' : 'AM') ?></option>
                            <?php $tm++; } ?>
                        </select></td>
                        <td>:</td>
                        <td><select name="start_min" id="start_min" onchange="setSched()">
                            <?php $tm = 0; while ($tm < 60) { 
                                $tim = str_pad($tm, 2, "0", STR_PAD_LEFT); ?>
                                <option value="<?php echo $tim ?>"><?php echo $tim ?></option>
                            <?php $tm++; } ?>
                        </select></td>
                        <td class="black-normal">to</td>
                        <td><input name="enddate" type="text" id="enddate" style="width:120px" readonly="readonly" /></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Auto Renew:</td>
                    <td><input type="checkbox" name="renew" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Status:</td>
                    <td><select name="Status">
                      <option value=""></option>
                      <?php foreach ($TStatus as $row_TStatus) { ?>
                      <option value="<?php echo $row_TStatus['CategoryID'] ?>"><?php echo $row_TStatus['Category'] ?></option>
                      <?php } ?>
                    </select>
                      <input type="button" value="edit" onclick="return GB_showCenter('Categories', '/operations/status/index.php', 480,520)" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Notes:</td>
                    <td><textarea name="Notes" style="width:450px" rows="5"></textarea></td>
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
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
</tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_insert" value="frmsrvschd" />
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