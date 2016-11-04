var cmp_id = 1, cell_id = 1, myPop, par, idx;

$(document).ready(function () {

    $(window).load(function () {
        if (edit != -1 && edit != -4)
            bindTmplEvents();
        if (edit != 0)
            loadTmpl();
    });
});

$(function () {
    if (edit > -1)
        $.contextMenu({
            selector: '.cell',
            items: {
                "rows": "---------",
                "addrow": {name: "Add Row", icon: "addrow",
                    callback: function (key, options) {
                        var tab = options.$trigger.closest("table");
                        var tr = options.$trigger.closest('tr');
                        var rows = tr.parent().children();
                        var idx = rows.index(tr);
                        var span = getSpan(tab);
                        var l = span[idx].length;
                        var cells = '';
                        for (var i = 0; i < l; i++) {
                            if (span[idx][i][2] == -1) {
                                var row = idx;
                                var pvRow = -1;
                                while (pvRow == -1) {
                                    pvRow = span[--row][i][2];
                                }
                                var agr = rows.eq(row).children().eq(pvRow);
                                agr.attr('rowspan', defAttr(agr, 'rowspan', 1) + 1);
                                i += span[idx][i][1] - 1;
                            } else if (span[idx][i][2] > -1) {
                                var colspan = span[idx][i][1] > 1 ? ' colspan="' + span[idx][i][1] + '"' : '';
                                cells += new_cell(colspan);
                            }
                        }
                        tr.before('<tr>' + cells + '</tr>');
                    }},
                "delrow": {name: "Delete Row", icon: "delrow",
                    callback: function (key, options) {
                        var tab = options.$trigger.closest("table");
                        if (tab.find('tr:first').parent().children().length > 1) {
                            var tr = options.$trigger.closest('tr');
                            var span = getSpan(tab);
                            tr.remove();
                        }
                    }},
                "moveup": {name: "Move Up", icon: "moveup",
                    callback: function (key, options) {
                        var tr = options.$trigger.closest('tr');
                        var idx = tr.parent().children().index(tr);
                        if (idx > 0)
                            tr.prev().before(tr);
                    }},
                "movedn": {name: "Move Down", icon: "movedn",
                    callback: function (key, options) {
                        var tr = options.$trigger.closest('tr');
                        var idx = tr.parent().children().index(tr);
                        if (idx < tr.parent().children().length - 1)
                            tr.next().after(tr);
                    }},
                "mergelft": {name: "Merge Left", icon: "mergelft",
                    callback: function (key, options) {
                        var td = $(this).closest('td');
                        var tr = td.closest("tr");
                        var col = tr.children().index(td);
                        if (col > 0) {
                            var rows = tr.parent().children();
                            var idx = rows.index(tr);
                            var span = getSpan(td.closest('table'));
                            var k = getsSpanIdx(span, idx, col);
                            var pcl = -2;
                            while (pcl == -2) {
                                pcl = span[idx][--k][2];
                            }
                            if (pcl != -1) {
                                var pv = td.prev();
                                if (defAttr(td.prev(), 'rowspan', 1) == defAttr(td, 'rowspan', 1)) {
                                    $(":first-child", td).remove();
                                    $(":first-child", pv).after(td.html());
                                    td.prev().attr('colspan', defAttr(pv, 'colspan', 1) + defAttr(td, 'colspan', 1));
                                    td.remove();
                                }
                            }
                        }
                    }},
                "cols": "---------",
                "addcol": {name: "Add Column", icon: "addcol",
                    callback: function (key, options) {
                        var td = options.$trigger;
                        var idx = td.parent().children().index(td);
                        td.parent().parent().children().each(function () {
                            var cell = $(this).children().eq(idx);
                            cell.before(new_cell(''));
                        });
                    }},
                "delcol": {name: "Delete Column", icon: "delcol",
                    callback: function (key, options) {
                        var td = options.$trigger;
                        var idx = td.parent().children().index(td);
                        if (td.parent().children().length > 1) {
                            td.parent().parent().children().each(function () {
                                $(this).children().eq(idx).remove();
                            });
                        }
                    }},
                "movelft": {name: "Move Left", icon: "movelft",
                    callback: function (key, options) {
                        var td = options.$trigger;
                        var idx = td.parent().children().index(td);
                        if (idx > 0) {
                            td.parent().parent().children().each(function () {
                                var cell = $(this).children().eq(idx);
                                cell.prev().before(cell);
                            });
                        }
                    }},
                "movergt": {name: "Move Right", icon: "movergt",
                    callback: function (key, options) {
                        var td = options.$trigger;
                        var idx = td.parent().children().index(td);
                        if (idx < td.parent().children().length - 1) {
                            td.parent().parent().children().each(function () {
                                var cell = $(this).children().eq(idx);
                                cell.next().after(cell);
                            });
                        }
                    }},
                "mergeup": {name: "Merge Up", icon: "mergeup",
                    callback: function (key, options) {
                        var td = options.$trigger;
                        var tr = td.closest("tr");
                        var tab = td.closest("table");
                        if (tab.find('tr:first').parent().children().index(tr) > 0) {
                            var col = tr.children().index(td);
                            var idx = tab.find('tr:first').parent().children().index(tr);
                            var span = getSpan(tab);
                            var k = getsSpanIdx(span, idx, col);
                            var pvRow = -1;
                            while (pvRow == -1) {
                                pvRow = span[--idx][k][2];
                            }
                            if (pvRow != -2) {
                                var pv = tab.find('tr:first').parent().children().eq(idx).children().eq(pvRow);
                                if (defAttr(pv, 'colspan', 1) == defAttr(td, 'colspan', 1)) {
                                    $(":first-child", td).remove();
                                    $(":first-child", pv).after(td.html());
                                    pv.attr('rowspan', defAttr(pv, 'rowspan', 1) + defAttr(td, 'rowspan', 1));
                                    td.remove();
                                }
                            }
                        }
                    }},
                "prop": "---------",
                "pty": {name: "Cell Properties", icon: "pty",
                    callback: function (key, options) {
                        showPopup(options.$trigger);
                    }}
            }
        });
});

$(function () {
    if (edit > -1)
        $.contextMenu({
            selector: '.tab, .txt, .tar, .cmb, .rad, .chk, .lbl, .img, .fil, .doc, .hor',
            items: {
                "del": {name: "Delete", icon: "delcmp",
                    callback: function (key, options) {
                        options.$trigger.closest("li").remove();
                    }
                },
                "pty": {name: "Properties", icon: "pty",
                    callback: function (key, options) {
                        showPopup(options.$trigger);
                    }}
            }
        });
});

function bindTmplEvents() {

    $('.toolbox').click(function () {
        dropinCanvas($(this).attr('id'));
    });

    $('.add_but').live('click', function () {
        var prev = $(this).prev();
        var elem = $(this).parent().parent();
        var id = prev.val();
        if (id > 0) {
            elem.append(docLnk(id, prev.children('option[value="' + id + '"]').text()));
            elem.attr('files', deftxtAttr(elem, 'files', '') + ',' + id);
        }
    });
}

function spectrumConfig(input) {
    var config = {
        allowEmpty: true,
        showInitial: true,
        showInput: true,
        showPalette: true,
        showPaletteOnly: true,
        togglePaletteOnly: true,
        hideAfterPaletteSelect: true,
        togglePaletteMoreText: 'more',
        togglePaletteLessText: 'less',
        palette: [
            ["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"],
            ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"],
            ["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc"],
            ["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd"],
            ["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0"],
            ["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"],
            ["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"],
            ["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"]
        ],
        change: function (color) {
            $('#' + input).val(color.toHexString());
        }
    };
    $("#" + input).spectrum(config);
}

function showPopup(elem) {
    var frm = 'No Properties to set!!';
    if (myPop) {
        myPop.unload();
        myPop = null;
    }
    myPop = new dhtmlXPopup();

    var cls = elem.attr('class').split(' ');
    frm = $('#propTmpl').contents().find('#' + cls[0]).html();
    myPop.attachHTML('<div id="propWin">' + frm + '</div>');
    $('#propWin :input').not('[type="checkbox"]').each(function () {
        $(this).val(elem.attr($(this).attr('name')));
    });
    $('#propWin :input[type="checkbox"]').each(function () {
        if (elem.attr($(this).attr('name')))
            $(this).attr('checked', 'checked');
    });
    if (cls[0] == 'img') {
        var src = elem.attr('src').split('/');
        var src_l = src[src.length - 1].split('?');
        var src_f = src_l[0];
        $('#propWin [name="src"]').val(src_f);
        $("#fileuploader").uploadFile({
            url: "upload.php",
            fileName: "myfile",
            allowedTypes: "jpg,png,gif",
            maxFileCount: 1,
            maxFileSize: 2 * 1024 * 1024,
            acceptFiles: "image/*",
            showPreview: true,
            showProgress: true,
            multiple: false,
            dragDrop: true,
            previewWidth: "100px",
            formData: {"id": tmp_id, "cmp": elem.attr('id'), "src": src_f},
            onSuccess: function (files, data, xhr, pd) {
                var file = JSON.parse(data);
                $('#propWin [name="src"]').val(tmp_id + '/' + file[0]);
            },
            onError: function (files, status, errMsg, pd) {
//                $("#eventsmessage").html($("#eventsmessage").html() + "<br/>Error for: " + JSON.stringify(files));
            }
        });
    }
    $('.color_pick').each(function () {
        spectrumConfig($(this).attr('name'));
    });
    $('.saveProp').click(function () {
        $('#propWin :input').not('[type="checkbox"]').not('[name="src"]').each(function () {
            elem.attr($(this).attr('name'), $(this).val());
        });
        $('#propWin :input[type="checkbox"]').each(function () {
            elem.attr($(this).attr('name'), $(this).is(':checked') ? '1' : '0');
        });
        $('#propWin :input[name="src"]').each(function () {
            elem.attr('src', $(this).val() + '?' + new Date().getTime());
        });
        myPop.hide();
    });
    $('.cancelProp').click(function () {
        myPop.hide();
    });

    var position = elem.position();
    var x = position.left;
    var y = position.top;
    var w = elem.width() / 2;
    var h = elem.height() / 2;
    myPop.show(x, y, w, h);
}

function filLnk(file) {
    return '<div class="doclnk"><a href="download.php?id=' + tmp_id + '&filename=' + file + '" target="_blank">' + file + '</a></div>';
}

function docLnk(id, doc) {
    return '<div class="doclnk"><div><a href="javascript: void(0)" \n\
onclick="top.leftFrame.showMod(\'Documents\', \'/EDMS/view.php?id=' + id + '\')">' + 
            doc + '</a></div><div style="float: right"><img src="/images/cancel_.png" /></div>';
}

function defAttr(elm, attr, def) {
    var val = elm.attr(attr);
    return val ? parseInt(val) : def;
}

function deftxtAttr(elm, attr, def) {
    var val = elm.attr(attr);
    return !val || val == '' ? def : val;
}

function getAttr(elm, attr, def) {
    var val = elm.attr(attr);
    if (val && val != def)
        return '#~#' + attr + '^~^' + val;
    else
        return '';
}

function getDimen(tab) {
    var cell, attr, subs, parid = '', tds = 0, trs = 0, td, idx, span = '';
    tab.find('tr:first').parent().children().each(function () {
        td = 0;
        idx = 0;
        $(this).children().each(function () {
            cell = $(this);
            td += defAttr(cell, 'colspan', 1);
            subs = cell.children();
            span += '|~|' + trs + '^~^' + idx;
            if (subs.length > 0) {
                parid = par++;
                subs.attr('par', parid);
                span += '#~#par' + '^~^' + parid;
            }
            span += getAttr(cell, 'colspan', '1');
            span += getAttr(cell, 'rowspan', '1');
            $('input, select', $('#propTmpl').contents().find('#cell')).each(function () {
                attr = $(this).attr('name');
                span += getAttr(cell, attr, '');
            });
            cell.children().each(function () {

            });
            idx++;
        });
        tds = Math.max(tds, td);
        trs++;
    });
    return [trs + '%' + tds, span.substr(3)];
}

function getSpan(tab) {
    var span = [];
    tab.find('tr:first').parent().children().each(function () {
        var row = [], idx = 0;
        $(this).children().each(function () {
            row.push([defAttr($(this), 'rowspan', 1), defAttr($(this), 'colspan', 1), idx++]);
        });
        span.push(row);
    });
    for (var i = 0; i < span.length; i++) {
        for (var j = 0; j < span[i].length; j++) {
            var r = span[i][j][0], k = 1;
            while (r > 1) {
                span[i + k].splice(j, 0, [1, span[i][j][1], -1]);
                r--;
                k++;
            }
        }
    }
    for (var i = 0; i < span.length; i++) {
        for (var j = 0; j < span[i].length; j++) {
            var c = span[i][j][1];
            while (c > 1) {
                span[i].splice(j + 1, 0, [1, 1, -2]);
                c--;
            }
        }
    }
    return span;
}

function getsSpanIdx(span, idx, col) {
    var k = -1;
    while (++k < span[idx].length)
        if (span[idx][k][2] == col)
            break;
    return k;
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    dropinCanvas(data);
}

function dropbefore(ev) {
    ev.preventDefault();
    var src_id = ev.dataTransfer.getData("text");
    var tag_id = ev.target.id;
    var html = get_tmpl_cmp(src_id);
    if (html.length > 0) {
        $('#' + tag_id).before(new_cmp(html));
    } else if (canMove(src_id, tag_id)) {
        $('#' + tag_id).before($('#' + src_id));
    }
    ev.stopPropagation();
}

function canMove(src, target) {
    return src.substr(0, 9) == 'tmpl_cmp_' && target != src && $('#' + src).find('#' + target).length == 0;
}

function dropin(ev) {
    ev.preventDefault();
    var src_id = ev.dataTransfer.getData("text");
    var tag_id = ev.target.id;
    var par = $('#' + tag_id);
    if (src_id.length == 3) {
        par.append(new_cmp(get_tmpl_cmp(src_id)));
        par.html(par.children());
    } else if (canMove(src_id, tag_id)) {
        var prvPar = $('#' + src_id).parent('.cell');
        par.append($('#' + src_id));
        par.html(par.children());
        if (prvPar && prvPar.children().length == 0) {
            prvPar.html('&nbsp;&nbsp;' + prvPar.html());
        }
    }
    ev.stopPropagation();
}

function get_tmpl_cmp(id, data) {
    var html = '', flds, cells, vals, val = '';
    var attrs = '';
    if (data) {
        flds = data.split('#~#');
        cells = flds[0].split('%');
        val = cells[1];
        for (var i = 1; i < flds.length; i++) {
            vals = flds[i].split('^~^');
            if (vals[0] == 'id')
                idx = vals[1].substr(4);
            attrs += ' ' + vals[0] + '="' + vals[1] + '"';
        }
    } else {
        idx = cmp_;
        attrs = ' id="cmp_' + idx + '"';
    }

    switch (id) {
        case 'tab':
            if (!data) {
                cells = ['', 2, 2];
                attrs += ' border="0" cellspacing="2" cellpadding="2"';
            }
            var cls = edit < 0 ? '' : ' class="tab"';
            html = '<table' + cls + attrs + '>' + newRows(cells[1], cells[2]) + '</table>';
            break;
        case 'txt':
            if (data) {
                attrs += ' value="' + val + '"';
            }
            html = edit < -2 ? '<div class="txt"' + attrs + '></div>' :
                    '<input class="txt" type="text"' + attrs + ' />';
            break;
        case 'tar':
            if (!data) {
                attrs += ' rows="5" style="width:250px"';
            }
            html = edit < -2 ? '<div class="tar"' + attrs + '></div>' :
                    '<textarea class="tar"' + attrs + '>' + val + '</textarea>';
            break;
        case 'cmb':
            if (edit < -2) {
                html = '<div class="cmb"' + attrs + ' typ="' + val + '"></div>'
            } else {
                var opts = '';
                if (edit > -2) {
                    opts = '<option value="0"' + (val == 0 ? 'selected' : '') + '>List</option>\n\
                        <option value="1"' + (val == 1 ? 'selected' : '') + '>Departments</option>';
                }
                html = '<select class="cmb"' + attrs + '>' + opts +
                        '</select><div class="loader" id="cmb_loader_' + idx + '"></div>';
            }
            break;
        case 'rad':
            html = '<input class="rad" type="radio"' + attrs + (edit < -2 ? ' disabled' : '') + ' />';
            break;
        case 'chk':
            html = '<input class="chk" type="checkbox"' + attrs + (edit < -2 ? ' disabled' : '') + ' />';
            break;
        case 'lbl':
            if (edit < 0) {
                html = '<span ' + attrs + '>' + val + '</span>';
            } else {
                attrs += ' value="' + val + '"';
                html = '<input class="lbl" type="text"' + attrs + ' />';
            }
            break;
        case 'img':
            if (!data) {
                attrs += ' src="/images/noimage2.jpg"';
            }
            html = '<img class="img"' + attrs + ' />';
            break;
        case 'fil':
            if (edit > -2) {
                html = '<img class="fil" src="/images/adddocs.png"' + attrs + ' />';
            } else {
                html = '<div class="fil"' + attrs + '></div>';
            }
            break;
        case 'doc':
            if (edit > -2) {
                html = '<img class="doc" src="/images/linkdocs.png"' + attrs + ' />';
            } else {
                var ctrl = edit == -4 ? '' : '<div class="lineup"><select class="doc_cmb" id="doc_cmb_' + idx +
                        '"><option value="0"></option></select><div class="ajax-file-upload-green add_but">Add</div></div>\n\
                    <div class="loader" id="docs_loader_' + idx + '"></div>';
                html = '<div class="doc"' + attrs + '>' + ctrl + '</div>';
            }
            break;
        case 'hor':
            if (!data) {
                attrs += ' style="background-color:#999; height:10px"';
            }
            html = '<div class="hor"' + attrs + '></div>';
            break;
    }
    if (!data && html.length > 0)
        cmp_++;
    return html;
}

function new_cell(span) {
    var drag = edit < 0 ? '' : ' ondrop="dropin(event)" ondragover="allowDrop(event)"';
    var cell = '<td class="cell" id="cell_' + cell_id + '"' + drag + span + '>&nbsp;&nbsp;</td>';
    cell_id++;
    return cell;
}

function new_cmp(html) {
    var drag = edit < 0 ? '' : ' draggable="true" ondragstart="drag(event)" ondrop="dropbefore(event)" ondragover="allowDrop(event)"';
    var innHtml = '<li id="tmpl_cmp_' + cmp_id + '" class="cmpbox"' + drag + '>' + html + '</li>';
    cmp_id++;
    return innHtml;
}

function dropinCanvas(id, data) {
    var html = get_tmpl_cmp(id, data);
    if (html.length > 0) {
        $('#cmp_list').append(new_cmp(html));
    } else if (id.substr(0, 9) == 'tmpl_cmp_') {
        $('#cmp_list').append($('#' + id));
    }
}

function newRow(cols) {
    var html = '<tr>';
    for (var i = 0; i < cols; i++) {
        html += new_cell('');
    }
    html += '</tr>';
    return html;
}

function newRows(rows, cols) {
    var html = '';
    for (var i = 0; i < rows; i++) {
        html += newRow(cols);
    }
    return html;
}

function saveTmpl() {
    var content = '', cls, pty, val, attrs, attr, celstr;
    par = 1;
    $('.cmpbox').attr('par', '');
    $('.cell').attr('par', '');
    $('.cmpbox').each(function () {
        $(this).children().each(function () {
            var elem = $(this);
            cls = $(this).attr('class');
            val = elem.closest('.cmpbox').attr('par') + '%';
            attrs = getAttr(elem, 'id', '');
            celstr = '';
            $('input, textarea, select', $('#propTmpl').contents().find('#' + cls)).each(function () {
                attr = $(this).attr('name');
                attrs += getAttr(elem, attr, '');
            });

            switch (cls) {
                case 'tab':
                    pty = getDimen($(this));
                    val += pty[0];
                    celstr = "##~##" + pty[1];
                    break;
                case 'txt':
                case 'lbl':
                case 'tar':
                case 'cmb':
                    attr = elem.val();
                    if (attr.length > 0)
                        val += attr;
                    break;
                case 'img':
                    var src = elem.attr('src').split('?');
                    attrs += src[0];
                    break;
                case 'rad':
                case 'chk':
                    attrs += getAttr(elem, 'checked', '');
                    break;

            }//class ## dimension--properties ## cell info
            content += "||~~||" + cls + "##~##" + (val + "#~#" + attrs.substr(3) + celstr).replace(/\n/g, '\\n');
        });
        content += "\n";
    });
    $('[name=tmpl_det]').val(content.substr(6));
}

function saveDoc() {
    var content = '', cls, val, elem, id, idx_;
    $('.txt, .tar, .cmb, .rad, .chk, .fil, .doc').each(function () {
        elem = $(this);
        id = elem.attr('id');
        idx_ = id.substr(4);
        cls = $(this).attr('class');

        switch (cls) {
            case 'txt':
            case 'tar':
                val = elem.val();
                if (deftxtAttr(elem, 'required', '') == '1' && val.length == 0) {
                    popmsg(document.getElementById(id), 'This field is required!!');
                    return false;
                } else {
                    content += '#~#' + idx_ + '^~^' + val;
                }
                break;
            case 'cmb':
                val = elem.val();
                if (deftxtAttr(elem, 'required', '') == '1' && val == 0) {
                    popmsg(document.getElementById(id), 'Please select an option!!');
                    return false;
                } else {
                    content += '#~#' + idx_ + '^~^' + val;
                }
                break;
            case 'rad':
            case 'chk':
                if (dat[1] == 1)
                    elem.attr('checked', 'checked');
                break;
            case 'fil':
            case 'doc':
                val = deftxtAttr(elem, 'files', '');
                if (deftxtAttr(elem, 'required', '') == '1' && val.length == 0) {
                    popmsg(document.getElementById(id), cls == 'fil' ? 'Please attach file!!' : 'Please select a document!!');
                    return false;
                } else {
                    content += '#~#' + idx_ + '^~^' + val;
                }
                break;
        }
    });
    $('[name=content]').val(content.substr(3));
    return true;
}

function showDoc(content) {
    var cls, elems, elem, dat, idx_, typ;
    var elems = content.split('#~#');
    for (var i = 0; i < elems.length; i++) {
        dat = elems[i].split('^~^');
        if (dat.length < 2)
            continue;
        elem = $('#cmp_' + dat[0]);
        cls = elem.attr('class');

        switch (cls) {
            case 'txt':
            case 'tar':
                elem.html(dat[1]);
                break;
            case 'cmb':
                typ = elem.attr('typ');
                if (typ == 0) {
                    var lst = elem.attr('list').split("\n");
                    elem.html(lst[dat[1]]);
                } else if (typ == 1) {
                    elem.load('load_dept.php?id=' + dat[1]);
                }
                break;
            case 'rad':
            case 'chk':
                content += '#~#' + idx_ + '^~^' + deftxtAttr(elem, 'checked', '');
                break;
            case 'fil':
                if (edit == -4) {
                    var fils = dat[1].split('|');
                    for (var j = 1; j < 5; j++) {
                        elem.append(filLnk(fils[j]));
                    }
                }
                break;
            case 'doc':
                $('#docs_loader_' + idx).load('load_doc_lnks.php?c=' + dat[0] + '&docs=' + dat[1]);
                break;
        }
    }
}

function showCmp(ln) {
    var lis = ln.split('||~~||'), id, elem, parid, doc = edit == -2 || edit == -3;
    for (var i = 0; i < lis.length; i++) {
        var pts = lis[i].replace(/\\n/g, "\n").split('##~##');
        if (pts[0].length > 0) {
            id = cmp_id;
            parid = pts[1].split('%');

            if (parid[0].length > 0) {
                var par = $('[par="' + parid[0] + '"]');
                if (par.html() == '&nbsp;&nbsp;')
                    par.html('');
                par.append(new_cmp(get_tmpl_cmp(pts[0], pts[1])));
            } else {
                dropinCanvas(pts[0], pts[1]);
            }
            elem = $('#cmp_' + idx);

            switch (pts[0]) {
                case 'tab':
                    var cells = pts[2].split('|~|'),
                            tr, td, dm, attrs, rspan, cspan, row, col,
                            rows = $('#cmp_' + idx).find('tr:first').parent().children();

                    for (var j = 0; j < cells.length; j++) {
                        attrs = cells[j].split('#~#');
                        dm = attrs[0].split('^~^');
                        row = parseInt(dm[0]);
                        col = parseInt(dm[1]);
                        td = rows.eq(row).children().eq(col);

                        for (var a = 1; a < attrs.length; a++) {
                            dm = attrs[a].split('^~^');
                            td.attr(dm[0], dm[1]);
                        }
                        rspan = defAttr(td, 'rowspan', 1);
                        cspan = defAttr(td, 'colspan', 1);

                        for (var r = 0; r < rspan; r++) {
                            tr = rows.eq(row + r);
                            for (var c = 0; c < cspan; c++) {
                                if (r != 0 || c != 0)
                                    tr.children().eq(col + 1).remove();
                            }
                        }
                    }
                    break;
                case 'cmb':
                    if (doc) {
                        switch (parid[1][0]) {
                            case '0':
                                var lst = elem.attr('list').split("\n");
                                $.each(lst, function (i, item) {
                                    elem.append($('<option>', {
                                        value: i,
                                        text: item
                                    }));
                                });
                                break;
                            case '1':
                                $('#cmb_loader_' + idx).load('load_depts.php?c=' + idx);
                                break;
                            case '2':
                                break;
                        }
                    }
                    break;
                case 'fil':
                    if (doc) {
                        elem.uploadFile({
                            url: "upload.php",
                            fileName: "myfile",
                            maxFileCount: defAttr(elem, 'max_files', 100),
                            maxFileSize: defAttr(elem, 'max_file_size', 100) * 1024 * 1024,
                            multiple: true,
                            allowedTypes: deftxtAttr(elem, 'allowedTypes', '*'),
                            showPreview: true,
                            showProgress: doc,
                            showDelete: doc,
                            showDownload: true,
                            dragDrop: doc,
                            previewWidth: "100px",
                            formData: {"id": tmp_id, "cmp": elem.attr('id')},
                            onSuccess: function (files, data, xhr, pd) {
                                var file = JSON.parse(data);
                                $('#propWin [name="src"]').val(tmp_id + '/' + file[0]);
                                elem.attr('files', deftxtAttr(elem, 'files', '') + '|' + file[0]);
                            },
                            onError: function (files, status, errMsg, pd) {
//                                $("#eventsmessage").html($("#eventsmessage").html() + "<br/>Error for: " + JSON.stringify(files));
                            },
                            onLoad: function (obj) {
                                $.ajax({
                                    cache: false,
                                    url: "load.php?id=" + tmp_id,
                                    dataType: "json",
                                    success: function (data) {
                                        for (var i = 0; i < data.length; i++) {
                                            obj.createProgress(data[i]["name"], data[i]["path"], data[i]["size"]);
                                        }
                                    }
                                });
                            },
                            deleteCallback: function (data, pd) {
                                for (var i = 0; i < data.length; i++) {
                                    $.post("delete.php", {op: "delete", name: data[i]},
                                    function (resp, textStatus, jqXHR) {
                                        //Show Message
                                        alert("File Deleted");
                                    });
                                }
                                pd.statusbar.hide(); //You choice.
                            },
                            downloadCallback: function (filename, pd) {
                                location.href = "download.php?filename=" + filename;
                            }
                        });
                    }
                    break;
                case 'doc':
                    if (doc) {
                        $('#docs_loader_' + idx).load('load_docs.php?c=' + idx);
                    }
                    break;
            }
        }
    }
}

function load_Tmpl(id) {
    $('#tmp_loader').load('load_tmpl.php?id=' + id, function () {
        if (edit < -2)
            show_Doc();
    });
}
