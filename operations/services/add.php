<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Operations'));
$access = _xvar_arr_sub($_access, array('Services'));
vetAccess('Operations', 'Services', 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmservice","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmservice") {

    $sql = sprintf("INSERT INTO `%s`.`items`(`typ`, `ExoodID`, `ProdCode`, `ProdName`, 
        `Description`, `picturefile`, `Classification`, `category`, `status`, 
        `UnitPrice`, `WebPrice`, `InUse`, `Notes`, `exood`, `exoodsales`, 
        `InfoLoad`, `pixLoad`, `StockLoad`) 
        VALUES (2,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                   $_SESSION['DBCoy'],
                   GSQLStr(_xpost('ExoodID'), "int"),
                   GSQLStr(_xpost('ProdCode'), "text"),
                   GSQLStr(_xpost('ProdName'), "text"),
                   GSQLStr(_xpost('Description'), "text"),
                   0,
                   GSQLStr(_xpost('Classification'), "intn"),
                   GSQLStr(_xpost('category'), "int"),
                   GSQLStr(_xpost('status'), "intn"),
                   GSQLStr(_xpost('UnitPrice'), "double"),
                   GSQLStr(_xpost('WebPrice'), "double"),
                   _xpostchk('InUse'),
                   GSQLStr(_xpost('Notes'), "text"),
                   _xpostchk('exood'),
                   _xpostchk('exoodsales'),
                   _xpostchk('InfoLoad'),
                   _xpostchk('pixLoad'),
                   _xpostchk('StockLoad'));
    $insert = runDBQry($dbh, $sql);

    if ($insert > 0) {
        $recid = mysqli_insert_id($dbh);
        docs('Operations'.DS.'Services', $recid);
        $sql = sprintf("INSERT INTO `%s`.`items_srv`(`ServiceID`, `department`, `outlets`, 
            `useasset`, `assetcat`, `quantity`, `MachineTime`, `timetype`, `periods`, `repeated`, 
            `starttime`, `endtime`, `eventdate`, `rec_type`, `event_length`, `alertperiod`) 
            VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                       $_SESSION['DBCoy'],
                       $recid,
                       GSQLStr(_xpost('department'), "int"),
                       GSQLStr(_xpost('outlets'), "text"),
                       _xpostchk('useasset'),
                       GSQLStr(_xpost('assetcat'), "text"),
                       GSQLStr(_xpost('quantity'), "int"),
                       GSQLStr(_xpost('MachineTime'), "double"),
                       GSQLStr(_xpost('timetype'), "int"),
                       GSQLStr(_xpost('periods'), "int"),
                       _xpostchk('repeated'),
                       GSQLStr(_xpost('starttime'), "date"),
                       GSQLStr(_xpost('endtime'), "date"),
                       GSQLStr(_xpost('eventdate'), "text"),
                       GSQLStr(_xpost('rec_type'), "text"),
                       GSQLStr(_xpost('event_length'), "int"),
                       GSQLStr(_xpost('alertperiod'), "int"));
        runDBQry($dbh, $sql);
        header("Location: view.php?id=$recid");
        exit;
    }
}

$TDept = getClassify(1);
$TCat = getClassify(3);

$sql = "SELECT CategoryID, Category FROM `{$_SESSION['DBCoy']}`.`status` WHERE cattype='ServiceTimeType'";
$TTmTyp = getDBData($dbh, $sql);

$sql = "SELECT CategoryID, Category FROM `{$_SESSION['DBCoy']}`.`status` WHERE cattype='Srvevent_length'";
$TPrdTyp = getDBData($dbh, $sql);

$TAssCat = getClassify(4);

$sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`";
$TOutlet = getDBData($dbh, $sql);

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
<script type="text/javascript" src="script.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["ProdCode", "", 
            ["req", "Enter Service Code"]
        ],
        ["ProdName", "", 
            ["req", "Enter Service Name"]
        ],
        ["Classification", "", 
            ["req", "Select Category"]
        ],
        ["UnitPrice", "", 
            ["req", "Enter Service Charge"]
        ]
    ]
    
    var mCal1, mCal2, mCal3, mCal4, mCal5, CalBut;
    window.onload = function() {
        mCal1 = new dhtmlxCalendarObject('startdate', true, {isYearEditable: true, isMonthEditable: true});
        mCal1.setSkin('dhx_black');
        mCal2 = new dhtmlxCalendarObject('enddate1', true, {isYearEditable: true, isMonthEditable: true});
        mCal2.setSkin('dhx_black');
        mCal3 = new dhtmlxCalendarObject('startdate2', true, {isYearEditable: true, isMonthEditable: true});
        mCal3.setSkin('dhx_black');
        mCal4 = new dhtmlxCalendarObject('enddate2', true, {isYearEditable: true, isMonthEditable: true});
        mCal4.setSkin('dhx_black');
        mCal5 = new dhtmlxCalendarObject('startdate3', true, {isYearEditable: true, isMonthEditable: true});
        mCal5.setSkin('dhx_black');

        CalBut = new dhtmlxCalendarObject('adddate', true, {isYearEditable: true, isMonthEditable: true});
        CalBut.setSkin('dhx_black');
        CalBut.attachEvent("onClick",function(date){
            CalBut.hide();
            dt = CalBut.getFormatedDate("%Y-%m-%d", date);
            lst=document.getElementById('leventdate');
            bt=document.getElementById('adddate');
            lst.options[lst.length] = new Option(dt, dt, false, false);
            bt.value = "Add Date";
        });
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
        <td width="240" valign="top"><img src="/images/services.jpg" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblservices.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="collateSched(); return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmservice" id="frmservice">
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
                    <td width="120" class="titles">Service Code:</td>
                    <td align="left"><input type="text" name="ProdCode" size="32" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td><input type="text" name="ProdName" size="32" /></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td align="left"><select name="Classification">
                      <option value=""></option>
                      <?php foreach ($TCat as $row_TCat) { ?>
                      <option value="<?php echo $row_TCat['catID'] ?>"><?php echo $row_TCat['catname'] ?></option>
                      <?php } ?>
                      </select>
                      <input type="button" name="btcat" id="btcat" value="edit" onclick="return GB_showCenter('Categories', '/operations/cat/index.php', 480,520)" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td align="left"><select name="department">
                      <?php foreach ($TDept as $row_TDept) { ?>
                      <option value="<?php echo $row_TDept['catID'] ?>"><?php echo $row_TDept['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td class="titles">In Use:</td>
                    <td><input type="checkbox" name="InUse" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Capacity:</td>
                    <td><input type="text" name="quantity" value="" style="width:40px" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Charge:</td>
                    <td><input type="text" name="UnitPrice" size="12" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Default Tax:</td>
                    <td><input type="text" name="itmtax" size="12" /></td>
                    </tr>
                  <tr>
                    <td bgcolor="#FF0000" class="boldwhite1"><b>Schedule</b></td>
                    <td class="darkgrey">&nbsp;</td>
                    </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Service Duration:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td><input name="periods" type="text" id="periods" style="width:30px; display:none" value="1" size="4" onchange="numme(this,1);" /></td>
                        <td><select name="timetype" id="timetype" onchange="tmtyp()">
                          <option value="0" selected="selected">Not Applicable</option>
                          <option value="1">Year(s)</option>
                          <option value="2">Month(s)</option>
                          <option value="3">Week(s)</option>
                          <option value="4">Day(s)</option>
                          <option value="5">Hour(s)</option>
                          <option value="6">Minute(s)</option>
                          <option value="7">Second(s)</option>
                          <option value="8">Time Frame</option>
                          </select></td>
                        <td><span class="darkgrey">**Time or time-frame for each service</span></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td valign="middle" class="titles" id="avail"></td>
                    <td class="darkgrey"><table id="fram" style="display:none; border: thin #333 solid; background-color:#CCC" border="0" cellspacing="2" cellpadding="2">
                      <tr class="black-normal">
                        <td><input name="starttime" type="hidden" id="starttime" /></td>
                        <td id="dtxt1">Date</td>
                        <td>Hour</td>
                        <td>Min</td>
                        <td><input type="hidden" name="event_length" id="event_length" /></td>
                        <td><input name="endtime" type="hidden" id="endtime" /></td>
                        <td>&nbsp;</td>
                        <td id="dtxt2">Date</td>
                        <td>Hour</td>
                        <td>Min</td>
                        <td>&nbsp;</td>
                        <td rowspan="2"><table border="0" cellspacing="2" cellpadding="2" id="dtbox" style="display:none">
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="black-normal">Date</td>
                            </tr>
                          <tr>
                            <td class="black-normal"><b>On</b></td>
                            <td>&nbsp;</td>
                            <td><input type="text" name="startdate2" id="startdate2" value="<?php echo date('Y-m-d') ?>" size="12" readonly="readonly" onblur="this.form.startdate.value=this.value;  this.form.startdate3.value=this.value;" /></td>
                            </tr>
                          </table></td>
                        </tr>
                      <tr>
                        <td class="black-normal"><b>From</b></td>
                        <td><input type="text" name="startdate" id="startdate" value="<?php echo date('Y-m-d') ?>" onblur="this.form.startdate2.value=this.value; this.form.startdate3.value=this.value;" size="12" readonly="readonly" /></td>
                        <td><select id="StartHour" name="StartHour" class="blue">
                          <option value="0" selected="selected">00</option>
                          <option value="1">01</option>
                          <option value="2">02</option>
                          <option value="3">03</option>
                          <option value="4">04</option>
                          <option value="5">05</option>
                          <option value="6">06</option>
                          <option value="7">07</option>
                          <option value="8">08</option>
                          <option value="9">09</option>
                          <?php for ($i = 10; $i < 24; $i++) {
									?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                          </select></td>
                        <td><select id="StartMin" name="StartMin" class="blue">
                          <option value="0" selected="selected">00</option>
                          <option value="1">01</option>
                          <option value="2">02</option>
                          <option value="3">03</option>
                          <option value="4">04</option>
                          <option value="5">05</option>
                          <option value="6">06</option>
                          <option value="7">07</option>
                          <option value="8">08</option>
                          <option value="9">09</option>
                          <?php for ($i = 10; $i < 60; $i++) {
									?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                          </select></td>
                        <td>&nbsp;</td>
                        <td class="black-normal"><b>To</b></td>
                        <td class="black-normal">&nbsp;</td>
                        <td nowrap="nowrap"><input type="text" name="enddate1" id="enddate1" value="" onblur="this.form.enddate.value=this.value; this.form.enddate2.value=this.value; this.form.end[2].checked = 'checked';" size="12" readonly="readonly" /></td>
                        <td><select id="EndHour" name="EndHour" class="blue">
                          <option value="0" selected="selected">00</option>
                          <option value="1">01</option>
                          <option value="2">02</option>
                          <option value="3">03</option>
                          <option value="4">04</option>
                          <option value="5">05</option>
                          <option value="6">06</option>
                          <option value="7">07</option>
                          <option value="8">08</option>
                          <option value="9">09</option>
                          <?php for ($i = 10; $i < 24; $i++) {
									?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                          </select></td>
                        <td><select id="EndMin" name="EndMin" class="blue">
                          <option value="0" selected="selected">00</option>
                          <option value="1">01</option>
                          <option value="2">02</option>
                          <option value="3">03</option>
                          <option value="4">04</option>
                          <option value="5">05</option>
                          <option value="6">06</option>
                          <option value="7">07</option>
                          <option value="8">08</option>
                          <option value="9">09</option>
                          <?php for ($i = 10; $i < 60; $i++) {
									?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                          </select></td>
                        <td>&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td class="darkgrey"><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td><input type="checkbox" name="repeated" id="repeated" onclick="dorepeat()"/></td>
                        <td><span class="black-normal"><b>Recurring</b></span></td>
                        <td>&nbsp;</td>
                        <td class="darkgrey">**Is this a recurring Service</td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td colspan="2"><table id="repeatbox" style="border:2px #333 ridge; display:none" border="1" cellspacing="2" cellpadding="5">
                      <tr>
                        <td width="77"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                          <tr>
                            <td><span class="black-normal">
                              <input name="repeat" id="radday" type="radio" value="day" checked="checked" onclick="chkdt('day')" />
                              </span></td>
                            <td><span class="black-normal">Daily</span></td>
                            </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" value="week" name="repeat" id="radweek" onclick="chkdt('week')" />
                              </span></td>
                            <td><span class="black-normal">Weekly</span></td>
                            </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" value="month" name="repeat" id="radmonth" onclick="chkdt('month')" />
                              </span></td>
                            <td><span class="black-normal">Monthly</span></td>
                            </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" value="year" name="repeat" id="radyear" onclick="chkdt('year')" />
                              </span></td>
                            <td><span class="black-normal">Yearly</span></td>
                            </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" value="dates" name="repeat" id="raddates" onclick="chkdt('dates')" />
                              </span></td>
                            <td class="black-normal">Dates</td>
                            </tr>
                          </table><input type="hidden" name="rec_type" id="rec_type" />                            </td>
                        <td width="320" style="background-color:#F2FEAF; border: 2px ridge #F60"><div id="repeat_day" style="display: block;">
                          <label> </label>
                          <table border="0" cellspacing="2" cellpadding="2">
                            <tr>
                              <td width="7%"><input name="day_type" type="radio" value="d" checked="checked" /></td>
                              <td width="10%" class="black-normal">Every</td>
                              <td width="7%" class="black-normal"><input type="text" value="1" name="day_count" style="width:20px" onchange="numme(this, 1);" /></td>
                              <td width="76%" class="black-normal">day</td>
                              </tr>
                            <tr>
                              <td><input type="radio" value="w" name="day_type" /></td>
                              <td colspan="3" class="black-normal">Every workday</td>
                              </tr>
                            <tr>
                              <td><input type="radio" value="wd" name="day_type" /></td>
                              <td colspan="3" class="black-normal"><table class="repeat_days">
                                <tbody>
                                  <tr class="black-normal">
                                    <td><input type="checkbox" value="0" name="week_day" /></td>
                                    <td>Sunday</td>
                                    <td><input type="checkbox" value="1" name="week_day" /></td>
                                    <td>Monday</td>
                                    <td><input type="checkbox" value="2" name="week_day" /></td>
                                    <td>Tuesday</td>
                                    <td><input type="checkbox" value="3" name="week_day" /></td>
                                    <td>Wednesday</td>
                                    </tr>
                                  <tr class="black-normal">
                                    <td><input type="checkbox" value="4" name="week_day" /></td>
                                    <td>Thursday</td>
                                    <td><input type="checkbox" value="5" name="week_day" /></td>
                                    <td>Friday</td>
                                    <td><input type="checkbox" value="6" name="week_day" /></td>
                                    <td>Saturday</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                  </tbody>
                                </table></td>
                              </tr>
                            </table>
                          <table border="0" cellspacing="2" cellpadding="2">
                            <tr>
                              <td class="black-normal">By</td>
                              <td><select id="dh" name="dh" class="blue">
                                <option value="0" selected="selected">00</option>
                                <option value="1">01</option>
                                <option value="2">02</option>
                                <option value="3">03</option>
                                <option value="4">04</option>
                                <option value="5">05</option>
                                <option value="6">06</option>
                                <option value="7">07</option>
                                <option value="8">08</option>
                                <option value="9">09</option>
                                <?php for ($i = 10; $i < 24; $i++) {
									?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                                </select></td>
                              <td><select id="dm" name="dm" class="blue">
                                <option value="0" selected="selected">00</option>
                                <option value="1">01</option>
                                <option value="2">02</option>
                                <option value="3">03</option>
                                <option value="4">04</option>
                                <option value="5">05</option>
                                <option value="6">06</option>
                                <option value="7">07</option>
                                <option value="8">08</option>
                                <option value="9">09</option>
                                <?php for ($i = 10; $i < 60; $i++) {
									?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                                </select></td>
                              </tr>
                            </table>
                          </div>
                          <div class="black-normal" id="repeat_week" style="display: none;">
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td class="black-normal">Every</td>
                                <td class="black-normal"><select name="wkday" id="wkday">
                                  <option selected="selected" value="1">Monday</option>
                                  <option value="2">Tuesday</option>
                                  <option value="3">Wednesday</option>
                                  <option value="4">Thursday</option>
                                  <option value="5">Friday</option>
                                  <option value="6">Saturday</option>
                                  <option value="0">Sunday</option>
                                  </select></td>
                                <td nowrap="nowrap" class="black-normal">of every</td>
                                <td class="black-normal"><input type="text" value="1" name="week_count" style="width:20px" onchange="numme(this, 1);" />                                </td>
                                <td nowrap="nowrap" class="black-normal">week</td>
                                </tr>
                              </table>
                            </div>
                          <div id="repeat_month" style="display: none;">
                            <label> </label>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input name="month_type" type="radio" value="p" checked="checked" /></td>
                                <td class="black-normal">From first occurence</td>
                                </tr>
                              </table>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input name="month_type" type="radio" value="d" /></td>
                                <td class="black-normal">Repeat</td>
                                <td><input type="text" value="1" name="month_day" style="width:20px" onchange="numme(this,1); numposit(this, 'posmth1', ' day of every')" /></td>
                                <td nowrap="nowrap" class="black-normal" id="posmth1">st day of</td>
                                </tr>
                              </table>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input type="radio" value="w" name="month_type" /></td>
                                <td class="black-normal">On</td>
                                <td nowrap="nowrap" class="black-normal"><input type="text" value="1" name="month_week" style="width:20px" onchange="numme(this,1); numposit(this, 'posmth3', '')" /></td>
                                <td nowrap="nowrap" class="black-normal" id="posmth3">st</td>
                                <td><select name="month_weekday">
                                  <option selected="selected" value="1">Monday</option>
                                  <option value="2">Tuesday</option>
                                  <option value="3">Wednesday</option>
                                  <option value="4">Thursday</option>
                                  <option value="5">Friday</option>
                                  <option value="6">Saturday</option>
                                  <option value="0">Sunday</option>
                                  </select></td>
                                <td>of</td>
                                </tr>
                              </table>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td nowrap="nowrap" class="black-normal"> Every</td>
                                <td><input type="text" value="1" name="month_count" style="width:20px" onchange="numme(this,1);" /></td>
                                <td class="black-normal">month(s)</td>
                                </tr>
                              </table>
                            </div>
                          <div id="repeat_year" style="display: none;">
                            <label> </label>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input name="year_type" type="radio" value="p" checked="checked" /></td>
                                <td class="black-normal">From first occurence</td>
                                </tr>
                              </table>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input name="year_type" type="radio" value="d" /></td>
                                <td class="black-normal">Every</td>
                                <td><input type="text" value="1" name="year_day" style="width:20px" onchange="numme(this,1); numposit(this, 'posyr1', ' day of ')" /></td>
                                <td nowrap="nowrap" class="black-normal" id="posyr1">st day of </td>
                                <td><select name="year_day_month">
                                  <option selected="selected" value="0">January</option>
                                  <option value="1">February</option>
                                  <option value="2">March</option>
                                  <option value="3">April</option>
                                  <option value="4">May</option>
                                  <option value="5">June</option>
                                  <option value="6">July</option>
                                  <option value="7">August</option>
                                  <option value="8">September</option>
                                  <option value="9">October</option>
                                  <option value="10">November</option>
                                  <option value="11">December</option>
                                  </select></td>
                                </tr>
                              </table>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input type="radio" value="w" name="year_type" /></td>
                                <td class="black-normal">On</td>
                                <td class="black-normal"><input type="text" value="1" name="year_week" style="width:20px" onchange="numme(this,1); numposit(this, 'posyr2', '')" /></td>
                                <td class="black-normal" id="posyr2">st</td>
                                <td><select name="year_weekday">
                                  <option selected="selected" value="1">Monday</option>
                                  <option value="2">Tuesday</option>
                                  <option value="3">Wednesday</option>
                                  <option value="4">Thursday</option>
                                  <option value="5">Friday</option>
                                  <option value="6">Saturday</option>
                                  <option value="7">Sunday</option>
                                  </select></td>
                                <td class="black-normal">of</td>
                                <td><select name="year_week_month">
                                  <option selected="selected" value="0">January</option>
                                  <option value="1">February</option>
                                  <option value="2">March</option>
                                  <option value="3">April</option>
                                  <option value="4">May</option>
                                  <option value="5">June</option>
                                  <option value="6">July</option>
                                  <option value="7">August</option>
                                  <option value="8">September</option>
                                  <option value="9">October</option>
                                  <option value="10">November</option>
                                  <option value="11">December</option>
                                  </select></td>
                                </tr>
                              </table>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td width="11%" class="black-normal">Every</td>
                                <td width="13%" class="black-normal"><input type="text" value="1" name="year_count" style="width:20px" onchange="numme(this, 1);" /></td>
                                <td width="68%" class="black-normal">year(s)</td>
                                </tr>
                              </table>
                            </div>
                          <div id="repeat_dates" style="display: none;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="27%">&nbsp;</td>
                                <td nowrap="nowrap"><table border="0" cellspacing="2" cellpadding="2">
                                  <tr>
                                    <td rowspan="2"><select name="leventdate" size="5" id="leventdate" style="width:100px">
                                      </select></td>
                                    <td><input type="button" name="adddate" id="adddate" value="Add Date" onblur="this.value ='Add Date'" onclick="mCal.show()" /></td>
                                    </tr>
                                  <tr>
                                    <td><input type="button" name="button4" id="button4" value="Remove" onclick="lst.remove(lst.selectedIndex)" /></td>
                                    </tr>
                                  </table>
                                  <input type="hidden" name="eventdate" id="eventdate" /></td>
                                </tr>
                              </table>
                            </div></td>
                        <td width="174"><table id="endbox" width="100%" border="0" cellspacing="2" cellpadding="2">
                          <tr>
                            <td>&nbsp;</td>
                            <td><span style="display:none" class="black-normal">From
                              <input type="text" name="startdate3" id="startdate3" value="<?php echo date('Y-m-d') ?>" size="12" readonly="readonly" onblur="this.form.startdate.value=this.value; this.form.startdate2.value=this.value;" />
                              </span></td>
                            </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" checked="checked" name="end" id="end_0" onclick="clearDoomsday()" />
                              </span></td>
                            <td><span class="black-normal">No end date</span></td>
                            </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" name="end" onclick="clearDoomsday()" />
                              </span></td>
                            <td nowrap="nowrap"><span class="black-normal">After
                              <input type="text" value="1" style="width:30px" name="occurences_count" onchange="numme(this,1);" />
                              occurrence(s)</span></td>
                            </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" name="end" style="display:none" />
                              </span></td>
                            <td><span class="black-normal" style="display:none">End by
                              <input type="text" name="enddate2" id="enddate2" value="" size="12" readonly="readonly" onblur="this.form.enddate.value=this.value; this.form.enddate1.value=this.value; this.form.end[2].checked = 'checked';" />
                              </span></td>
                            </tr>
                          </table>                          <input type="hidden" name="enddate" id="enddate" />                            </td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles"></td>
                    <td><table border="0" cellspacing="1" cellpadding="1" id="expire" style="display:none">
                      <tr>
                        <td class="titles">Expiry Alert:</td>
                        <td><input type="text" name="alertperiod" style="width:40px" /></td>
                        <td id="exptyp">Days</td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Asset Categories:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td><input type="checkbox" name="useasset" onclick="setAssUse(this.checked)" /></td>
                        <td nowrap="nowrap">Attach Asset(s)</td>
                        </tr>
                      <tr>
                        <td colspan="2"><table border="0" cellspacing="2" cellpadding="2" id="assframe" style="display:none">
                          <tr>
                            <td class="h1">&nbsp;</td>
                            <td><input type="hidden" name="assetcat" /></td>
                            <td nowrap="nowrap" class="h1">Selected Categories</td>
                            </tr>
                          <tr>
                            <td valign="top"><select name="asscats" size="10" id="asscats">
                              <?php foreach ($TAssCat as $row_TAssCat) { ?>
                              <option value="<?php echo $row_TAssCat['catID'] ?>"><?php echo $row_TAssCat['catname'] ?></option>
                              <?php } ?>
                              </select></td>
                            <td><p><a href="javascript: void(0)" onclick="pushRules(frmservice.asscats, frmservice.selasscats, frmservice.selasscats, frmservice.assetcat)"><img src="/images/last.png" width="24" height="24" /></a></p>
                              <p><a href="javascript: void(0)" onclick="pushRule(frmservice.asscats, frmservice.selasscats, frmservice.selasscats, frmservice.assetcat)"><img src="/images/next.png" width="24" height="24" /></a></p>
                              <p><a href="javascript: void(0)" onclick="pushRule(frmservice.selasscats, frmservice.asscats, frmservice.selasscats, frmservice.assetcat)"><img src="/images/prev.png" width="24" height="24" /></a></p>
                              <p><a href="javascript: void(0)" onclick="pushRules(frmservice.selasscats, frmservice.asscats, frmservice.selasscats, frmservice.assetcat)"><img src="/images/first.png" width="24" height="24" /></a></p></td>
                            <td valign="top"><select name="selasscats" size="10" id="selasscats">
                              </select></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">Outlets:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td class="h1">&nbsp;</td>
                        <td><input type="hidden" name="outlets" id="outlets" /></td>
                        <td nowrap="nowrap" class="h1">Selected Outlets</td>
                        </tr>
                      <tr>
                        <td valign="top"><select name="alloutlets" size="10" id="alloutlets">
                          <?php foreach ($TOutlet as $row_TOutlet) { ?>
                          <option value="<?php echo $row_TOutlet['OutletID'] ?>"><?php echo $row_TOutlet['OutletName'] ?></option>
                          <?php } ?>
                          </select></td>
                        <td><p><a href="javascript: void(0)" onclick="pushRules(frmservice.alloutlets, frmservice.seloutlets, frmservice.seloutlets, frmservice.outlets)"><img src="/images/last.png" width="24" height="24" /></a></p>
                          <p><a href="javascript: void(0)" onclick="pushRule(frmservice.alloutlets, frmservice.seloutlets, frmservice.seloutlets, frmservice.outlets)"><img src="/images/next.png" width="24" height="24" /></a></p>
                          <p><a href="javascript: void(0)" onclick="pushRule(frmservice.seloutlets, frmservice.alloutlets, frmservice.seloutlets, frmservice.outlets)"><img src="/images/prev.png" width="24" height="24" /></a></p>
                          <p><a href="javascript: void(0)" onclick="pushRules(frmservice.seloutlets, frmservice.alloutlets, frmservice.seloutlets, frmservice.outlets)"><img src="/images/first.png" width="24" height="24" /></a></p></td>
                        <td valign="top"><select name="seloutlets" size="10" id="seloutlets">
                          </select></td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                  <tr>
                    <td class="titles">Description:</td>
                    <td><textarea name="Description" style="width:300px" rows="3"></textarea></td>
                    </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                  <tr>
                    <td class="titles">Notes:</td>
                    <td><textarea name="Notes" style="width:450px" rows="10"></textarea>                      </td>
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
                <td><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
            <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
            <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
            <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
            <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_insert" value="frmservice" />
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
