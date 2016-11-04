
function setSched() {
    $('#startdate').val($('#start_date').val() + ' ' + 
        $('#start_hour').val() + ':' + $('#start_min').val() + ':00');
    var DT = DateFromStr($('#startdate').val()),
    cnt = prds * num;
    
    switch (ptyp) {
        case 9:
            break;
        case 10:
            switch (tmtyp) {
                case 2:
                    DT.setSeconds(DT.getSeconds() + cnt);
                    break;
                case 3:
                    DT.setMinutes(DT.getMinutes() + cnt);
                    break;
                case 4:
                    DT.setHours(DT.getHours() + cnt);
                    break;
                case 5:
                    DT.setDate(DT.getDate() + cnt);
                    break;
                case 6:
                    DT.setMonth(DT.getMonth() + cnt);
                    break;
                case 7:
                    DT.setFullYear(DT.getFullYear() + cnt);
                    break;
            }
            break;
    }
     $('#enddate').val(dtStrFromDate(DT, true, true));
}
