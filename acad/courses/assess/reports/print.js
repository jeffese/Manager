
function prepView(id) {
    var data = parent.parent.stud_data;
    //0 present 1 absent 2 late 3 name 4 comments 5 sum 6 average 7 pos
    //    $("#sess").html();
    var att = parseInt(data[id].det[0]) + parseInt(data[id].det[1]);
    $("#stud_name").html(data[id].det[3]);
    $("#present").html(data[id].det[0]);
    $("#late").html(data[id].det[2]);
    $("#absent").html(data[id].det[1]);
    $("#per_pres").html(att == 0 ? '0%' : Math.round(data[id].det[0] * 100 / att) + '%');
    $("#per_late").html(att == 0 ? '0%' : Math.round(data[id].det[2] * 100 / att) + '%');
    $("#per_abs").html(att == 0 ? '0%' : Math.round(data[id].det[1] * 100 / att) + '%');
    $("#position").html(data[id].det[7] + numposition(data[id].det[7]));
    $("#average").html(data[id].det[6]);
    $("#classnum").html(data.length);
    $("#classhigh").html(parent.parent.high_av);
    $("#classav").html(parent.parent.cls_av);
    $("#obtain").html(data[id].det[5]);
    $("#pix").attr('src', px_dir + data[id].id + "/x1.jpg");
    $("#comment").html(data[id].det[4]);
    //    $("#").html();
    
    var pers = new Array();
    var marks = new Array();

    var courses = parent.parent.crs_data;
    for (var b=1; b<courses[0].grps.length; b++) {
        if (courses[0].grps[b].per != "0") {
            $("#head_tot").before('<td>'+courses[0].grps[b].code+'</td>');
            $("#head_per").before('<td bgcolor="#999999">'+courses[0].grps[b].per+'</td>');
            pers.push(courses[0].grps[b].per);
            marks.push(1);
        } else 
            marks.push(0);
    }
    for (var c=0; c<courses[0].ca_lst.length; c++) {
        if (courses[0].grp_lst[c] == 0) {
            if (courses[0].per_lst[c] != "0") {
                $("#head_tot").before('<td>'+courses[0].ca_lst[c]+'</td>');
                $("#head_per").before('<td bgcolor="#999999">'+courses[0].per_lst[c]+'</td>');
                pers.push(courses[0].per_lst[c]);
                marks.push(1);
            } else 
                marks.push(0);
        }
    }
    /**
     * 0 `ass_names`, 1 `ass_codes`, 2 `ass_ca`, 3 `ass_state`, 4 `ass_grp`, 5 `percentages`, 6 `max_scores`, 
     * 7 `attachments`, 8 `attend_date`, 9 `cls_typ`, 10 `cls_names`, 11 `cls_codes`, 12 `cls_ca`, 13 `cls_state`, 
     * 14 `cls_grp_inf`, 15 `cls_percentages`, 16 `cls_max_scores`, 17 course_name, 18 department
     */
    var depts = new Array("#FF9900", "#CC3300", "#009900", "#003399", "#333333", "#CC00CC", "#FFFF00", "#663300", "#333366");
    var dept = -1, dpt = -1, stud, clr, clc, crs_cnt = 0, bg;
    for (var d=0; d<courses.length; d++) {
        stud = courses[d].studs;
        if (dpt != courses[d].config[18]) {
            dpt = courses[d].config[18];
            dept++;
        }
        for (var s=0; s<stud.length; s++) {
            if (stud[s].id == data[id].id) {
                crs_cnt++;
                bg = d % 2 == 1 ? "#E5E5E5" : "#D5D5D5";
                var scores = "";
                for (var x=0; x<stud[s].marks.length; x++) {
                    if (marks[x] == 1) {
                        clr = parseFloat(stud[s].marks[x]) >= parseFloat(pers[x]/2) ? 'black-normal' : 'red-normal';
                        scores += '<td align="center" bgcolor="'+bg+'">'+stud[s].marks[x]+'</td>';
                    }
                }
                y = 1;
                while (stud[s].tot > parent.parent.range[y]) {
                    y++
                }
                
                clr = parseFloat(stud[s].tot) >= 50 ? 'black-normal' : 'red-normal';
                clc = parseFloat(courses[d].high) >= 50 ? 'black-normal' : 'red-normal';
                $("#course_lst").append('<tr class="black-normal">\n\
                    <td bgcolor="'+depts[dept]+'">&nbsp;</td>\n\
                    <td bgcolor="'+bg+'"><b>'+courses[d].config[17]+'</b></td>'+scores+
                    '<td align="center" class="'+clr+'" bgcolor="'+bg+'">'+stud[s].tot+'</td>\n\
                    <td align="center" class="'+clc+'" bgcolor="'+bg+'">'+courses[d].high+'</td>\n\
                    <td align="center" bgcolor="'+bg+'">'+parent.parent.grades[y]+'</td>'+
                    '<td align="center" bgcolor="'+bg+'">' + stud[s].pos + numposition(stud[s].pos) + '</td>' +
                    '<td align="left" bgcolor="'+bg+'">' + stud[s].com + '</td>'+
                    '</tr>');
                break;
            }
        }
    }
    $("#totobtain").html(crs_cnt * 100);
}


