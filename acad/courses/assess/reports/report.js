// JavaScript Document
var stud_data = new Array(), order;
var crs_data = new Array();

function stud_dt(id, det) {
    //0 present 1 absent 2 late 3 cnt 4 name 5 comments 6 sum 7 average 8 pos
    this.id = id;
    det.shift();
    this.det = det;
}

function crs_dt(data) {
    var high = data[0];
    var config = data[1].split('~-~');
    var ids = data[2].split('<<>>');
    var glue = config[1].length > 0 && config[11].length > 0 ? '|' : '';
    this.high = high;
    this.config = config;
    this.ca_lst = (config[1] + glue + config[11]).split('|');
    this.grp_lst = config[4].split('|');
    var grpinf = config[14].split('|');
    this.grps = new Array(1);
    for (var i=1; i<grpinf.length; i++) {
        this.grps.push(new grp_inf(grpinf[i]));
    }
    this.per_lst = (config[5] + glue + config[15]).split('|');
    this.studs = new Array();
    
    for (var j=0; j<ids.length; j++) {
        this.studs.push(new stud_crs(ids[j], data[j+3]));
    }
}

function stud_crs(id, data) {
    this.id = id;
    var arr = data.split('~~##~~');
    var marks = arr[2].split('~#~');
    marks.shift();
    this.tot = arr[1];//tot
    this.marks = marks;//marks
    this.com = arr[3];//comments
    this.pos = arr[4];//pos
}

function grp_inf(data) {
    var arr = data.split(':');
    this.name = arr[0];
    this.code = arr[1];
    this.state = arr[2];
    this.per = arr[3];
    this.arith = arr[4];
}

function Explode_Stud_Tab() {
    if (stud_str.length > 0) {
        var arr_0 = stud_str.split('~~||~~');
        var arr_1 = arr_0[0].split('<<>>');
        for (var i=1; i<arr_0.length; i++) {
            stud_data.push(new stud_dt(arr_1[i-1], arr_0[1].split('~~##~~')));
        }
    }
}

function Explode_Crs_Tab() {
    var arr;
    for (var i=1; i<course_arr.length; i++) {
        arr = course_arr[i].split('~~||~~');
        crs_data.push(new crs_dt(arr));
    }
}

$(document).ready(function(){
    Explode_Stud_Tab();
    Explode_Crs_Tab();
});
    
