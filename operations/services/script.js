
function setTime() {
    var flds = new Array("start", "end"), val;
    for (var k = 0; k < 2; k++) {
        val = $("#" + flds[k] + "_date").val() + " " +
                $("#" + flds[k] + "_hour").val() + ":" +
                $("#" + flds[k] + "_min").val();
        $("input[name='" + flds[k] + "time']").val(val);
    }
}

function getTime() {
    var flds = new Array("start", "end"), hrmin, prd;
    for (var k = 0; k < 2; k++) {
        prd = $("input[name='" + flds[k] + "time']").val().split(' ');
        $("#" + flds[k] + "_date").val(prd[0]);
        if (prd.length == 2) {
            hrmin = prd[1].split(':');
            $("#" + flds[k] + "_hour").val(hrmin[0]);
            $("#" + flds[k] + "_min").val(hrmin[1]);
        }
    }
}

function expTyp() {
    $("#expire").show();
    var perd = '';
    switch (parseInt($("#timetype").val())) {
        case 0:
        case 6:
        case 7:
            $("#expire").hide();
            break;
        case 1:
        case 2:
        case 3:
            perd = 'Days';
            break;
        case 4:
        case 8:
            perd = 'Hours';
            break;
    }
    $("#exptyp").html(perd);
}

function setAssUse(use) {
    if (use)
        $("#assframe").show();
    else
        $("#assframe").hide();
}

function pushRule(from, to, src, val) {
    var i = from.selectedIndex;
    if (i > -1) {
        to.options[to.length] = from.options[i];
    }
    stackList(src, val);
}

function pushRules(from, to, src, val) {
    for (var i = from.length - 1; i >= 0; i--) {
        to.options[to.length] = from.options[i];
    }
    stackList(src, val);
}

function stackList(lst, val) {
    var itms = "";
    for (var i = 0; i < lst.length; i++) {
        itms += "," + lst.options[i].value;
    }
    val.value = itms.substr(1);
}

function hidewin(winid) {
    if (winid == 1) {
        $("#frm").show();
        $("#catwin").hide();
    } else {
        $("#frm").hide();
        $("#catwin").show();
    }
}

function tmtyp(view) {
    $("#fram").hide();
    $("#periods").show();
    $("#avail").html("");
    if (!view) {
        $("input[name='repeat']").attr('disabled', '');
        $("#radday").attr('checked', 'checked');
    }

    chkdt("day");
    dorepeat();
    switch ($("#timetype").val()) {
        case "1":
            $("#radday").attr('disabled', 'disabled');
            $("#radweek").attr('disabled', 'disabled');
            $("#radmonth").attr('disabled', 'disabled');
            $("#radyear").attr('checked', 'checked');
            chkdt("year");
            break;
        case "2":
            $("#radday").attr('disabled', 'disabled');
            $("#radweek").attr('disabled', 'disabled');
            $("#radmonth").attr('checked', 'checked');
            chkdt("month");
            break;
        case "3":
            $("#radday").attr('disabled', 'disabled');
            $("#radweek").attr('checked', 'checked');
            chkdt("week");
            break;
        case "4":
        case "5":
        case "6":
        case "7":
            $("#startdate").hide();
            $("#enddate1").hide();
            $("#dtxt1").html('');
            $("#dtxt2").html('');
            break;
        case "8":
            $("#fram").show();
            $("#avail").html("Time Frame:");
        case "0":
            $("#periods").hide();
            break;
    }
    expTyp();
}

function dorepeat() {
    var b1 = false, b2 = false, dt = false, txt = '';
    if ($("#repeated").is(":checked")) {
        b1 = true;
    } else {
        dt = true;
        if (parseInt($("#timetype").val()) < 5) {
            b2 = true;
            txt = 'Date';
        }
    }
    vethideshow('repeatbox', b1);
    vethideshow('dtbox', dt);
    vethideshow('startdate', b2);
    vethideshow('enddate1', b2);
    $("#dtxt1").html(txt);
    $("#dtxt2").html(txt);
}

function vethideshow(elem, show) {
    if (show)
        $("#" + elem).show();
    else
        $("#" + elem).hide();
}

function chkdt(chk) {
    $("input[name='repeat'][value='" + chk + "']").attr('checked', 'checked');
    $("#repeat_day").hide();
    $("#repeat_week").hide();
    $("#repeat_month").hide();
    $("#repeat_year").hide();
    $("#repeat_dates").hide();
    $("#endbox").show();
    switch (chk) {
        case "day":
            $("#repeat_day").show();
            break;
        case "week":
            $("#repeat_week").show();
            break;
        case "month":
            $("#repeat_month").show();
            break;
        case "year":
            $("#repeat_year").show();
            break;
        case "dates":
            $("#endbox").hide();
            $("#repeat_dates").show();
            $("#end_0").attr('checked', 'checked');
            $("#enddate").val('');
            $("#enddate1").val('');
            $("#enddate2").val('');
            break;
    }
}

function collateDT(DT, dt, hr, mn) {
    var d = dt.val() == '' ? '0000-01-01' : dt.val();
    var hour = hr.find('option:selected').text();
    var h = hour == '' ? '00' : hour;
    var min = mn.find('option:selected').text();
    var m = min == '' ? '00' : min;
    DT.val(d + ' ' + h + ':' + m + ':00');
}

function coldt1() {
    collateDT($('[name="starttime"]'), $('[name="startdate"]'), $('[name="StartHour"]'), $('[name="StartMin"]'));
}

function coldt2() {
    collateDT($('[name="endtime"]'), $('[name="enddate"]'), $('[name="EndHour"]'), $('[name="EndMin"]'));
}

function packdates() {
    var dts = '';
    $('[name="leventdate"] > option').each(function () {
        dts += ',' + $(this).value;
    });
    $('[name="eventdate"]').val(dts.substr(1));
}

function eventLen() {
    var amt = $('[name="periods"]').val();
    var dt1 = DateFromStr($('[name="starttime"]').val(), true);
    var dt2 = DateFromStr($('[name="endtime"]').val(), true);
    var len = (dt2 - dt1) / 1000;
    var dt3 = dt1;
    var dt4 = dt2;
    dt3.setFullYear(2000, 0, 1);
    dt4.setFullYear(2000, 0, 1);
    var len2 = (dt4 - dt3) / 1000;
    switch ($('[name="timetype"]').val()) {
        case "0":
            break;
        case "1":
            len = amt * 60 * 60 * 24 * 365;
            break;
        case "2":
            len = amt * 60 * 60 * 24 * 30;
            break;
        case "3":
            len = amt * 60 * 60 * 24 * 7;
            break;
        case "4":
            len = len2 <= 0 ? amt * 60 * 60 * 24 : len2;
            break;
        case "5":
            len = len2 <= 0 ? amt * 60 * 60 : len2;
            break;
        case "6":
            len = len2 <= 0 ? amt * 60 : len2;
            break;
        case "7":
            len = len2 <= 0 ? amt : len2;
            break;
    }
    $('[name="event_length"]').val(len);
}

function collateSched() {
    var val = "", day, mth, date;
    if ($('[name="repeated"]').is(':checked')) {
        var dt = DateFromStr($('[name="startdate"]').val(), true);
        switch (RadioChk($('[name="repeat"]'))) {
            case "day":
                if ($('[name="day_type"]')[0].checked) {
                    val += "day_" + $('[name="day_count"]').val() + "___";
                } else {
                    val += "week_1___1,2,3,4,5";
                }
                break;
            case "week":
                var days = "";
                for (i = 0; i < 7; i++) {
                    days += $('[name="week_day"]')[i].checked ?
                            (days == "" ? "" : ',') + $('[name="week_day"]')[i].value : "";
                }
                val += "week_" + $('[name="week_count"]').val() + "___" + days;
                break;
            case "month":
                val += "month_" + $('[name="month_count"]').val() + "_";
                if ($('[name="month_type"]')[0].checked) {
                    val += "__";
                } else if ($('[name="month_type"]')[1].checked) {
                    val += "1__";
                    dt.setDate($('[name="month_day"]').val());
                    setStartDate(dtStrFromDate(dt, true, false));
                } else {
                    val += $('[name="month_weekday"]').val() + "_" + $('[name="month_week"]').val() + "_";
                }
                break;
            case "year":
                val += "year_" + $('[name="year_count"]').val() + "_";
                if ($('[name="year_type"]')[0].checked) {
                    val += "__";
                } else if ($('[name="year_type"]')[1].checked) {
                    val += "1__";
                    day = $('[name="year_day"]').val();
                    mth = $('[name="year_day_month"]').val();
                } else {
                    val += $('[name="year_weekday"]').val() + "_" + $('[name="year_week"]').val() + "_";
                    day = 1;
                    mth = $('[name="year_week_month"]').val();
                }
                dt.setMonth(mth, day);
                date = dtStrFromDate(dt, true, false);
                setStartDate(date);
                break;
            case "dates":
                val += "dates____"
                break;
        }

        val += "#";
        if ($('[name="end"]')[0].checked) {
            val += "no";
        } else if ($('[name="end"]')[1].checked) {
            val += $('[name="occurences_count"]').val();
        }
    }
    $('#rec_type').val(val);
}

function setStartDate(date) {
    $("#startdate").val(date);
    $("#startdate2").val(date);
    $("#startdate3").val(date);
}

function getYR() {
    var yr = 0;
    switch ($('[name="repeat"]')) {
        case "day":
        case "week":
            yr = 3;
            break;
        case "month":
            yr = 10;
            break;
        case "year":
            yr = 20;
            break;
        case "dates":
            yr = 9999;
            break;
    }
    return yr;
}

function clearDoomsday() {
    //        dt1 = DateFromStr(starttime.value, true);
    //        yr1 = dt1.getFullYear();
    //        dt2 = DateFromStr(endtime.value, true);
    //        yr2 = dt2.getFullYear();
    //        yr = getYR();
    $('[name="enddate1"]').val('');
    $('[name=enddate2""]').val('');
    //        EndHour.options[0].selected=true;
    //        EndMin.options[0].selected=true;
    $('[name="enddate"]').val('');
}

function setRepeat() {
    var rec = $('[name="rec_type"]').val();
    if (rec) {
        var hash = rec.split('#');
        var flds = hash[0].split('_');
        ChkRadio($('[name="repeat"]'), flds[0]);
        chkdt(flds[0]);
        var dt = DateFromStr($('[name="starttime"]').val(), true);
        switch (flds[0]) {
            case "day":
                $('[name="day_type"]')[0].checked = "checked";
                $('[name="day_count"]').val(flds[1]);
                break;
            case "week":
                if (hash[0] == "week_1___1,2,3,4,5") {
                    $('[name="day_type"]')[1].checked = "checked";
                    chkdt("day");
                } else {
                    $('[name="week_count"]').val(flds[1]);
                    var wk = flds[4].split(',');
                    for (var i in wk) {
                        $('[name="week_day"]')[wk[i]].checked = "checked";
                    }
                }
                break;
            case "month":
                $('[name="month_count"]').val(flds[1]);
                if (flds[2] == "") {
                    $('[name="month_type"]')[0].checked = "checked";
                } else if (flds[3] == "") {
                    $('[name="month_type"]')[1].checked = "checked";
                    $('[name="month_day"]').val(dt.getDate());
                } else {
                    $('[name="month_type"]')[2].checked = "checked";
                    $('[name="month_weekday"]').val(flds[2]);
                    $('[name="month_week"]').val(flds[3]);
                }
                break;
            case "year":
                $('[name="year_count"]').val(flds[1]);
                if (flds[2] == "") {
                    $('[name="year_type"]')[0].checked = "checked";
                } else if (flds[3] == "") {
                    $('[name="year_type"]')[1].checked = "checked";
                    $('[name="year_day"]').val(dt.getDate());
                    $('[name="year_day_month"]').val(dt.getMonth());
                } else {
                    $('[name="year_type"]')[2].checked = "checked";
                    $('[name="year_week"]').val(flds[3]);
                    $('[name="year_weekday"]').val(flds[2]);
                    $('[name="year_week_month"]').val(dt.getMonth());
                }
                break;
            case "dates":
                var dts = $('[name="eventdate"]').val().split(',');
                for (var i in dts)
                    $('[name="leventdate"]').append(new Option(dts[i], dts[i]));
                break;
        }

        switch (hash[1]) {
            case "no":
                $('[name="end"]')[0].checked = "checked";
                break;
            case "":
                $('[name="end"]')[2].checked = "checked";
                $('[name="enddate2"]').val($('[name="enddate"]').val());
                break;
            default:
                $('[name="end"]')[1].checked = "checked";
                $('[name="occurences_count"]').val(hash[1]);
                break;
        }
    }
}

function setDates() {
    var dt1 = DateFromStr($("#starttime").val());
    var dt2 = DateFromStr($("#endtime").val());
    if (dt1 == null || dt2 == null)
        return;
    setStartDate(dtStrFromDate(dt1, true, false));
    $("#enddate").val(dtStrFromDate(dt2, true, false));
    $('[name="StartHour"]').val(dt1.getHours());
    $('[name="StartMin"]').val(dt1.getMinutes());
    $('[name="EndHour"]').val(dt2.getHours());
    $('[name="EndMin"]').val(dt2.getMinutes());
}

function setEndDT() {
    var dt1 = DateFromStr($('[name="starttime"]').val(), true);
    var dt2 = DateFromStr($('[name="endtime"]').val(), true);
    if (dt2 < dt1 || !$('[name="end"]')[2].checked) {
//        yr = dt1.getFullYear()+ getYR();
        var dt = $('[name="endtime"]').val().split(' ');
        var adj = "1972-01-01 " + dt[1];
        $('[name="endtime"]').val(adj);
    }
}

function stackUp() {
    collateSched();
    packdates();
    coldt1();
    coldt2();
    setEndDT();
    eventLen();
}
