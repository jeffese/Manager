// JavaScript Document
var act = 0;

function licch() {
    var cats = $("#lictype option:selected").attr("cats").split(',');
    var val = $('#vtype').val();
    $('#vtype').empty().append($('<option>', {value: '', text: '..'}));
    $.each(cats, function (i, item) {
        $('#vtype').append($('<option>', {
            value: item,
            text: auto_cats[item]
        }));
    });
    $('#vtype').val(val);
    typech();
}

function typech() {
    chtype(parseInt(0 + $('#vtype').val()) - 1, $('#bstyle').val(), $('#brandid').val());
}

function chtype(x, bst, brd) {
    $('#bstyle').empty();
    $('#brandid').empty();
    $('#serieid').empty();

    $('#bstyle')[0].options[0] = new Option("..", "", true, true);

    if (x > -1 && c[x].length > 0) {
        for (var i = 0; i < c[x].length; i++) {
            if (bst == ci[x][i]) {
                $('#bstyle')[0].options[i + 1] = new Option(c[x][i], ci[x][i], true, true);
            } else {
                $('#bstyle')[0].options[i + 1] = new Option(c[x][i], ci[x][i], false, false);
            }
        }
    } else {
        $('#bstyle')[0].options[0] = new Option("Others", "1321", true, true);
    }
    $('#brandid')[0].options[0] = new Option("..", "", true, true);

    if (x > -1 && b[x].length > 0) {
        for (var i = 0; i < b[x].length; i++) {
            if (brd == bi[x][i]) {
                $('#brandid')[0].options[i + 1] = new Option(b[x][i], bi[x][i], true, true);
            } else {
                $('#brandid')[0].options[i + 1] = new Option(b[x][i], bi[x][i], false, false);
            }
        }
        $('#brandid')[0].options[i + 1] = new Option("Others", "820", false, false);
    } else {
        $('#brandid')[0].options[0] = new Option("Others", "820", true, true);
        $('#serieid')[0].options[0] = new Option("Others", "1321", true, true);
        return;
    }
    $('#serieid')[0].options[0] = new Option("..", "", true, true);
}

function brandch() {
    while ($('#serieid')[0].length > 0) {
        $('#serieid')[0].remove(0);
    }
    var x = $('#vtype').val() - 1;
    var y = $('#brandid')[0].selectedIndex - 1;
    var z = $('#serieid').val();

    $('#serieid')[0].options[0] = new Option("..", "", true, true);
    if ($('#brandid')[0].value != '') {
        if (s[x][y].length > 0) {
            for (var i = 0; i < s[x][y].length; i++) {
                if (z == si[x][y][i]) {
                    $('#serieid')[0].options[i + 1] = new Option(s[x][y][i], si[x][y][i], true, true);
                } else {
                    $('#serieid')[0].options[i + 1] = new Option(s[x][y][i], si[x][y][i], false, false);
                }
            }
        } else {
            $('#serieid')[0].options[0] = new Option("Others", "1321", true, true);
        }
    } else {
        $('#serieid')[0].options[0] = new Option("..", "", true, true);
    }
}

function get_bstyle(typ, id) {
    for (var i = 0; i < c[typ].length; i++) {
        if (id == ci[typ][i]) {
            return c[typ][i];
        }
    }
    return 'Others';
}

function get_brand(typ, id) {
    for (var i = 0; i < b[typ].length; i++) {
        if (id == bi[typ][i]) {
            serie = i;
            return b[typ][i];
        }
    }
    return 'Others';
}
var serie;
function get_model(typ, id) {
    for (var i = 0; i < s[typ][serie].length; i++) {
        if (id == si[typ][serie][i]) {
            return s[typ][serie][i];
        }
    }
    return 'Others';
}
