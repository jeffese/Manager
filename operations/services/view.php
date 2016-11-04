<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Operations'));
$access = _xvar_arr_sub($_access, array('Services'));
vetAccess('Operations', 'Services', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], $access['Edit'], $access['Del'], $access['Print'], 0, 1);

$id = intval(_xget('id'));
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","add.php","edit.php?id=$id","","[Staff]del.php?id=$id","","","find.php","print.php?id=$id","index.php");
$rec_status = 1;

$sql = "SELECT `items`.*, `items_srv`.*, dept.catname AS dept, cat.catname AS cat, 
    pdtyp.Category AS pertype, tmtyp.Category AS tmtype
FROM `{$_SESSION['DBCoy']}`.items 
INNER JOIN `{$_SESSION['DBCoy']}`.items_srv             ON `items`.ItemID=items_srv.ServiceID
LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` dept ON `items_srv`.department = dept.catID  
LEFT JOIN `{$_SESSION['DBCoy']}`.`classifications` cat  ON `items`.Classification = cat.catID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` pdtyp         ON `items_srv`.event_length = pdtyp.CategoryID 
LEFT JOIN `{$_SESSION['DBCoy']}`.`status` tmtyp         ON `items_srv`.timetype = tmtyp.CategoryID 
WHERE `ServiceID`=$id";
$row_TServices = getDBDataRow($dbh, $sql);

$T_AssCat = getClassify(4, "AND `catID` IN (0{$row_TServices['assetcat']})");

$sql = "SELECT OutletID, OutletName FROM `{$_SESSION['DBCoy']}`.`outlets`
    WHERE `OutletID` IN (0{$row_TServices['outlets']})";
$T_Outlet = getDBData($dbh, $sql);

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
    
    window.onload = function() {
        setDates();
        tmtyp();
        setRepeat();
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
                    <td width="120" class="titles">Service ID:</td>
                    <td class="red-normal"><b><?php echo $row_TServices['ServiceID']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="titles">Service Code:</td>
                    <td align="left"><?php echo $row_TServices['ProdCode'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td><?php echo $row_TServices['ProdName'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Category:</td>
                    <td align="left"><?php echo $row_TServices['cat']; ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Department:</td>
                    <td align="left"><?php echo $row_TServices['dept']; ?></td>
                  </tr>
                  <tr>
                    <td class="titles">In Use:</td>
                    <td><input type="checkbox" name="InUse"<?php if ($row_TServices['InUse'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Capacity:</td>
                    <td><?php echo $row_TServices['quantity'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Charge:</td>
                    <td><?php echo $row_TServices['UnitPrice'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Default Tax:</td>
                    <td><?php echo $row_TServices['itmtax'] ?></td>
                  </tr>
                  <tr class="black-normal">
                    <td bgcolor="#FF0000" class="boldwhite1" id="avail3"><b>Schedule</b></td>
                    <td align="center" class="darkgrey">&nbsp;</td>
                  </tr>
                  <tr class="black-normal">
                    <td nowrap="nowrap" class="titles">Service Duration:</td>
                    <td><div id="periods" style="float:left"><?php echo $row_TServices['periods']; ?></div>
                      &nbsp;
                      <input name="timetype" type="hidden" id="timetype" value="<?php echo $row_TServices['timetype']; ?>" />
                      <script type="text/javascript">
switch (<?php echo $row_TServices['timetype']; ?>) {
	case 0: document.write('Not Applicable'); break;
	case 1: document.write('Year(s)'); break;
	case 2: document.write('Month(s)'); break;
	case 3: document.write('Week(s)'); break;
	case 4: document.write('Day(s)'); break;
	case 5: document.write('Hour(s)'); break;
	case 6: document.write('Minute(s)'); break;
	case 7: document.write('Second(s)'); break;
	case 8: document.write('Period'); break;
}
                        </script></td>
                  </tr>
                  <tr class="black-normal">
                    <td class="titles" id="avail4">&nbsp;</td>
                    <td class="darkgrey">**Time or time-frame for each service</td>
                  </tr>
                  <tr class="black-normal">
                    <td class="titles" id="avail">&nbsp;</td>
                    <td class="darkgrey"><table id="fram" style="display:none; border: thin #333 solid; background-color:#CCC" border="0" cellspacing="2" cellpadding="2">
                      <tr class="black-normal">
                        <td><input name="starttime" type="hidden" id="starttime" value="<?php echo $row_TServices['starttime']; ?>" /></td>
                        <td id="dtxt1">Date</td>
                        <td>Hour</td>
                        <td>Min</td>
                        <td><input name="event_length" type="hidden" id="event_length" value="<?php echo $row_TServices['event_length']; ?>" /></td>
                        <td><input name="endtime" type="hidden" id="endtime" value="<?php echo $row_TServices['endtime']; ?>" /></td>
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
                            <td><input type="text" name="startdate2" id="startdate2" size="12" readonly="readonly" /></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td class="black-normal"><b>From</b></td>
                        <td><input type="text" name="startdate" id="startdate" size="12" readonly="readonly" /></td>
                        <td><select id="StartHour" name="StartHour" class="blue" disabled="disabled">
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
                        <td><select id="StartMin" name="StartMin" class="blue" disabled="disabled">
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
                        <td nowrap="nowrap"><input type="text" name="enddate1" id="enddate1" value="" size="12" readonly="readonly" /></td>
                        <td><select id="EndHour" name="EndHour" class="blue" disabled="disabled">
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
                        <td><select id="EndMin" name="EndMin" class="blue" disabled="disabled">
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
                  <tr class="black-normal">
                    <td class="titles">&nbsp;</td>
                    <td class="darkgrey"><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td><input type="checkbox" name="repeated" id="repeated" disabled="disabled" <?php if (!(strcmp($row_TServices['repeated'],"1"))) {echo "checked=\"checked\"";} ?>/></td>
                        <td><span class="black-normal"><b>Recurring</b></span></td>
                        <td>&nbsp;</td>
                        <td class="darkgrey">**Is this a recurring Service</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr class="black-normal">
                    <td colspan="2"><table id="repeatbox" style="border:2px #333 ridge; display:none" border="1" cellspacing="2" cellpadding="5">
                      <tr>
                        <td width="77"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                          <tr>
                            <td><span class="black-normal">
                              <input name="repeat" id="radday" type="radio" value="day" disabled="disabled" />
                            </span></td>
                            <td><span class="black-normal">Daily</span></td>
                          </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" value="week" name="repeat" id="radweek" disabled="disabled" />
                            </span></td>
                            <td><span class="black-normal">Weekly</span></td>
                          </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" value="month" name="repeat" id="radmonth" disabled="disabled" />
                            </span></td>
                            <td><span class="black-normal">Monthly</span></td>
                          </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" value="year" name="repeat" id="radyear" disabled="disabled" />
                            </span></td>
                            <td><span class="black-normal">Yearly</span></td>
                          </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" value="dates" name="repeat" id="raddates" disabled="disabled" />
                            </span></td>
                            <td class="black-normal">Dates</td>
                          </tr>
                        </table>
                          <input name="rec_type" id="rec_type" type="hidden" value="<?php echo $row_TServices['rec_type']; ?>" /></td>
                        <td width="320" style="background-color:#F2FEAF; border: 2px ridge #F60"><div id="repeat_day" style="display: block;">
                          <label> </label>
                          <table border="0" cellspacing="2" cellpadding="2">
                            <tr>
                              <td width="8%"><input name="day_type" type="radio" value="d" disabled="disabled" /></td>
                              <td width="11%" class="black-normal">Every</td>
                              <td width="13%" class="black-normal"><input name="day_count" type="text" style="width:20px" value="1" readonly="readonly" /></td>
                              <td width="68%" class="black-normal">day</td>
                            </tr>
                            <tr>
                              <td><input type="radio" value="w" name="day_type" disabled="disabled" /></td>
                              <td colspan="3" class="black-normal">Every workday</td>
                            </tr>
                          </table>
                        </div>
                          <div class="black-normal" id="repeat_week" style="display: none;"> Repeat every
                            <input type="text" value="1" name="week_count" style="width:20px" readonly="readonly" />
                            week(s)
                            <table class="repeat_days">
                              <tbody>
                                <tr class="black-normal">
                                  <td><input type="checkbox" value="0" name="week_day" disabled="disabled" /></td>
                                  <td>Sunday</td>
                                  <td><input type="checkbox" value="1" name="week_day" disabled="disabled" /></td>
                                  <td>Monday</td>
                                  <td><input type="checkbox" value="2" name="week_day" disabled="disabled" /></td>
                                  <td>Tuesday</td>
                                  <td><input type="checkbox" value="3" name="week_day" disabled="disabled" /></td>
                                  <td>Wednesday</td>
                                </tr>
                                <tr class="black-normal">
                                  <td><input type="checkbox" value="4" name="week_day" disabled="disabled" /></td>
                                  <td>Thursday</td>
                                  <td><input type="checkbox" value="5" name="week_day" disabled="disabled" /></td>
                                  <td>Friday</td>
                                  <td><input type="checkbox" value="6" name="week_day" disabled="disabled" /></td>
                                  <td>Saturday</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <div id="repeat_month" style="display: none;">
                            <label> </label>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input name="month_type" type="radio" value="p" disabled="disabled" /></td>
                                <td class="black-normal">From first occurence</td>
                              </tr>
                            </table>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input name="month_type" type="radio" value="d" disabled="disabled" /></td>
                                <td class="black-normal">Repeat</td>
                                <td><input type="text" value="1" name="month_day" style="width:20px" readonly="readonly" /></td>
                                <td class="black-normal" id="posmth1">st day of every</td>
                                <td><input type="text" value="1" name="month_day_count" style="width:20px" readonly="readonly" /></td>
                                <td class="black-normal">month</td>
                              </tr>
                            </table>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input type="radio" value="w" name="month_type" disabled="disabled" /></td>
                                <td class="black-normal">On</td>
                                <td nowrap="nowrap" class="black-normal"><input type="text" value="1" name="month_week" style="width:20px" readonly="readonly" /></td>
                                <td nowrap="nowrap" class="black-normal" id="posmth3">st</td>
                                <td><select name="month_weekday" disabled="disabled">
                                  <option selected="selected" value="1">Monday</option>
                                  <option value="2">Tuesday</option>
                                  <option value="3">Wednesday</option>
                                  <option value="4">Thursday</option>
                                  <option value="5">Friday</option>
                                  <option value="6">Saturday</option>
                                  <option value="0">Sunday</option>
                                </select></td>
                                <td nowrap="nowrap" class="black-normal">of every</td>
                                <td><input type="text" value="1" name="month_week_count" style="width:20px" readonly="readonly" /></td>
                                <td nowrap="nowrap" class="black-normal">month</td>
                              </tr>
                            </table>
                          </div>
                          <div id="repeat_year" style="display: none;">
                            <label> </label>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input name="year_type" type="radio" value="p" disabled="disabled" /></td>
                                <td class="black-normal">From first occurence</td>
                              </tr>
                            </table>
                            <table border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td><input name="year_type" type="radio" value="d" disabled="disabled" /></td>
                                <td class="black-normal">Every</td>
                                <td><input type="text" value="1" name="year_day" style="width:20px" readonly="readonly" /></td>
                                <td nowrap="nowrap" class="black-normal" id="posyr1">st day of </td>
                                <td><select name="year_day_month" disabled="disabled">
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
                                <td><input type="radio" value="w" name="year_type" disabled="disabled" /></td>
                                <td class="black-normal">On</td>
                                <td class="black-normal"><input type="text" value="1" name="year_week" style="width:20px" readonly="readonly" /></td>
                                <td class="black-normal" id="posyr2">st</td>
                                <td><select name="year_weekday" disabled="disabled">
                                  <option selected="selected" value="1">Monday</option>
                                  <option value="2">Tuesday</option>
                                  <option value="3">Wednesday</option>
                                  <option value="4">Thursday</option>
                                  <option value="5">Friday</option>
                                  <option value="6">Saturday</option>
                                  <option value="7">Sunday</option>
                                </select></td>
                                <td class="black-normal">of</td>
                                <td><select name="year_week_month" disabled="disabled">
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
                                <td width="13%" class="black-normal"><input type="text" value="1" name="year_count" style="width:20px" readonly="readonly" /></td>
                                <td width="68%" class="black-normal">year</td>
                              </tr>
                            </table>
                          </div>
                          <div id="repeat_dates" style="display: none;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="27%">&nbsp;</td>
                                <td nowrap="nowrap"><table border="0" cellspacing="2" cellpadding="2">
                                  <tr>
                                    <td><select name="leventdate" size="5" id="leventdate" style="width:100px">
                                    </select></td>
                                  </tr>
                                </table>
                                  <input name="eventdate" type="hidden" id="eventdate" value="<?php echo $row_TServices['eventdate']; ?>" /></td>
                              </tr>
                            </table>
                          </div></td>
                        <td width="174"><table id="endbox" width="100%" border="0" cellspacing="2" cellpadding="2">
                          <tr>
                            <td>&nbsp;</td>
                            <td class="black-normal">From
                              <input type="text" name="startdate3" id="startdate3" size="12" readonly="readonly" onblur="this.form.startdate.value=this.value; this.form.startdate2.value=this.value;" /></td>
                          </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" checked="checked" name="end" disabled="disabled" />
                            </span></td>
                            <td><span class="black-normal">No end date</span></td>
                          </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" name="end" disabled="disabled" />
                            </span></td>
                            <td nowrap="nowrap"><span class="black-normal">After
                              <input type="text" value="1" style="width:30px" name="occurences_count" readonly="readonly" />
                              occurrence(s)</span></td>
                          </tr>
                          <tr>
                            <td><span class="black-normal">
                              <input type="radio" name="end" disabled="disabled" />
                            </span></td>
                            <td><span class="black-normal">End by
                              <input type="text" name="enddate2" id="enddate2" value="" size="12" readonly="readonly" />
                            </span></td>
                          </tr>
                        </table>
                          <span class="titles">
                            <input name="enddate" type="hidden" id="enddate" />
                          </span></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Duration Type:</td>
                    <td align="left"><?php echo $row_TServices['pertype']; ?></option>
                    </td>
                  </tr>
                  <tr>
                    <td class="titles"></td>
                    <td><table border="0" cellpadding="2" cellspacing="2" id="timeframe"<?php if ($row_TServices['event_length'] != 9) echo ' style="display:none"'; ?>>
                      <tr>
                        <td><?php echo $row_TServices['starttime']; ?></td>
                        <td>&nbsp;</td>
                        <td class="black-normal">to</td>
                        <td>&nbsp;</td>
                        <td><?php echo $row_TServices['endtime']; ?></td>
                        </tr>
                      </table>
                      <table border="0" cellspacing="2" cellpadding="2" id="prdframe"<?php if ($row_TServices['event_length'] != 10) echo ' style="display:none"'; ?>>
                        <tr>
                          <td><?php echo $row_TServices['periods'] ?></td>
                          <td>&nbsp;</td>
                          <td><?php echo $row_TServices['tmtype']; ?></td>
                          </tr>
                        </table>
                      <table border="0" cellspacing="1" cellpadding="1" id="expire">
                        <tr>
                          <td class="titles">Expiry Alert:</td>
                          <td><?php echo $row_TServices['alertperiod'] ?></td>
                          <td id="exptyp">Days</td>
                          </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Asset Categories:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td><input type="checkbox" name="useasset"<?php if ($row_TServices['useasset'] == 1) {
                echo " checked=\"checked\"";
            } ?> disabled="disabled" /></td>
                        <td nowrap="nowrap">Attach Asset(s)</td>
                        </tr>
                      <tr>
                        <td colspan="2"><table border="0" cellspacing="2" cellpadding="2" id="assframe"<?php if ($row_TServices['useasset'] == 0) echo ' style="display:none"'; ?>>
                          <tr>
                            <td nowrap="nowrap" class="h1">Selected Categories</td>
                            </tr>
                          <tr>
                            <td valign="top"><select name="selasscats" size="10" id="selasscats">
                              <?php foreach ($T_AssCat as $row_T_AssCat) { ?>
                              <option value="<?php echo $row_T_AssCat['catID'] ?>"><?php echo $row_T_AssCat['catname'] ?></option>
                              <?php } ?>
                              </select>
                              <input type="hidden" name="assetcat" value="<?php echo $row_TServices['assetcat']; ?>" /></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="titles">Outlets:</td>
                    <td><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td nowrap="nowrap" class="h1">Selected Outlets</td>
                      </tr>
                      <tr>
                        <td valign="top"><select name="seloutlets" size="10" id="seloutlets">
                          <?php foreach ($T_Outlet as $row_T_Outlet) { ?>
                          <option value="<?php echo $row_T_Outlet['OutletID'] ?>"><?php echo $row_T_Outlet['OutletName'] ?></option>
                          <?php } ?>
                          </select>
                          <input type="hidden" name="outlets" id="outlets" value="<?php echo $row_TServices['outlets']; ?>" /></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Description:</td>
                    <td><textarea name="Description" rows="3" readonly="readonly" style="width:300px"><?php echo $row_TServices['Description'] ?></textarea></td>
                  </tr>
                  <tr>
                    <td class="titles">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="titles">Notes:</td>
                    <td><textarea name="Notes" rows="10" readonly="readonly" style="width:450px"><?php echo $row_TServices['Notes'] ?></textarea></td>
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
                            <td><?php $doc_shelf = 'Operations'.DS.'Services';
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
                <td>&nbsp;</td>
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
