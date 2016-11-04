<?php
require_once('../../scripts/init.php');
$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Personnel'));
$access = _xvar_arr_sub($_access, array('Pay Slips'));
vetAccess('Personnel', 'Pay Slips', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmpayslip","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmpayslip") {
    $sql = sprintf("INSERT INTO `{$_SESSION['DBCoy']}`.`paybatch`(`payday`, `dtfrom`, `dtto`, 
                    `salary`, `bonus`, `staffid`, `description`)  
                    VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GSQLStr(_xpost('payday'), "text"),
                       GSQLStr(_xpost('dtfrom'), "text"),
                       GSQLStr(_xpost('dtto'), "text"),
                       GSQLStr(_xpostchk('salary'), "int"),
                       GSQLStr(_xpostchk('bonus'), "int"),
                       GSQLStr($_SESSION['ids']['VendorID'], "int"),
                       GSQLStr(_xpost('description'), "text"));
    $insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
            $recid = mysqli_insert_id($dbh);
            header("Location: view.php?id=$recid");
            exit;
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New Payslip</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" type="text/javascript">
var arrFormValidation=[
    ["payday", '',
        ["req", "Enter Title for Pay Slip"]
    ],
    ["dtMth", "if=$('#dtyp_0').is(':checked')",
        ["req", "Select Month of Payment"]
    ],
    ["dtfrom", "if=$('#dtyp_1').is(':checked')",
        ["req", "Enter Period starting date"]
    ],
    ["dtto", "if=$('#dtyp_1').is(':checked')",
        ["req", "Enter Period end date"]
    ],
    ["salary", '',
        ["eval=$('#salary').is(':checked') || $('#bonus').is(':checked')", 
            "Select at least one Payment Type"]
    ]
];

    var called = false;
    function post() {
        called = true;
        $('#frmpayslip').submit();
    }
    
    function chdtyp(typ) {
        $('#mthbox').hide();
        $('#prdbox').hide();
        if (typ == 1) {
            $('#mthbox').show();
            $('#dtMth').val('');
        } else {
            $('#prdbox').show();
        }
    }
    
    function calmth() {
        if ($('#dtMth').val().length > 0) {
            var dt = $('#dtMth').val().split('-');
            var dat = new Date(dt[0], dt[1], 0);
            $('#dtfrom').val(dt[0]+'-'+dt[1]+'-1');
            $('#dtto').val(dt[0]+'-'+dt[1]+'-'+dat.getDate());
            caldays();
        }
    }
    
    function caldays() {
        if ($('#dtfrom').val().length > 0 && $('#dtto').val().length > 0) {
            var days = daysPast($('#dtto').val(), $('#dtfrom').val());
            window.frames['paylist'].setdays(days+1);
        }
    }
	
var mCal, mCal2, mcal3;
window.onload = function() {
    mCal = new dhtmlxCalendarObject('dtfrom', true, {isYearEditable: true, isMonthEditable: true});
    mCal.setSkin('dhx_black');
    mCal.attachEvent("onClick", function (){
                    caldays();
                });
    mCal2 = new dhtmlxCalendarObject('dtto', true, {isYearEditable: true, isMonthEditable: true});
    mCal2.setSkin('dhx_black');
    mCal2.attachEvent("onClick", function (){
                    caldays();
                });
    mCal3 = new dhtmlxCalendarObject('dtMth', true, {isYearEditable: true, isMonthEditable: true});
    mCal3.setSkin('dhx_black');
    mCal3.attachEvent("onClick", function (){
                    calmth();
                });
				$("#paylist").width(screen.availWidth-520);
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
        <td width="240" valign="top"><img src="/images/payslip.png" width="240" height="160" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblpayslip.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="" onsubmit="if (!called) {if (validateFormPop(arrFormValidation)) window.frames['paylist'].post(); return false}" method="post" name="frmpayslip" id="frmpayslip">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Title:</td>
                    <td align="left"><input type="text" name="payday" id="payday" style="width:300px" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Period:</td>
                    <td><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td bgcolor="#CCCCCC"><input name="dtyp" type="radio" id="dtyp_0" value="1" checked="checked" onclick="chdtyp(1)" /></td>
                        <td bgcolor="#CCCCCC">Month</td>
                        <td bgcolor="#CCCCCC">&nbsp;</td>
                        <td bgcolor="#CCCCCC"><input type="radio" name="dtyp" value="2" id="dtyp_1" onclick="chdtyp(2)" /></td>
                        <td bgcolor="#CCCCCC">Period</td>
                        </tr>
                      </table>
                      <table width="200" border="0" cellpadding="2" cellspacing="2" style="display:none" id="prdbox">
                        <tr>
                          <td>Start:</td>
                          <td><input name="dtfrom" type="text" id="dtfrom" size="12" readonly="readonly" /></td>
                          <td>&nbsp;</td>
                          <td>End:</td>
                          <td><input name="dtto" type="text" id="dtto" size="12" readonly="readonly" /></td>
                          </tr>
                        </table>
                      <table border="0" cellpadding="2" cellspacing="2" id="mthbox">
                        <tr>
                          <td>Month:</td>
                          <td><input name="dtMth" type="text" id="dtMth" size="12" readonly="readonly" /></td>
                          </tr>
                        </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Type:</td>
                    <td><table border="0" cellpadding="2" cellspacing="2" class="blue-normal">
                      <tr>
                        <td><input type="checkbox" name="salary" id="salary" /></td>
                        <td><strong>Salary</strong></td>
                        <td>&nbsp;</td>
                        <td><input type="checkbox" name="bonus" id="bonus" /></td>
                        <td><strong>Bonus</strong></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td colspan="2"><iframe id="paylist" name="paylist" src="payees/add.php" style="width:100%; height:500px"></iframe></td>
                    </tr>
                  <tr>
                    <td class="titles">Description:</td>
                    <td align="left"><textarea name="description" id="description" style="width:500px" rows="4"></textarea></td>
                    </tr>
                  <tr>
                    <td class="titles"><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
                      <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
                      <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
                      <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
                      <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script></td>
                    <td>&nbsp;</td>
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
            <span class="titles">
            <input type="hidden" name="MM_insert" value="frmpayslip" />
            </span>
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