var cmp_id = 1, cell_id = 1, myPop, par, idx;

$(document).ready(function () {

    $(window).load(function () {
        if (edit != -1 && edit != -4)
            bindTmplEvents();
        if (edit != 0)
            loadTmpl();
    });

    if (edit < -1 && edit != -4) {
        $('#seek_apprv').autocomplete({
            source: "load_approver.php",
            minLength: 3,
            select: function (event, ui) {
                var apvals = $('#approvals').val();
                apvals = apvals.length == 0 ? '|' : apvals;
                if (apvals.search(new RegExp(',' + ui.item.value + '(,|\\|)')) == -1) {
                    approver(ui.item.value, ui.item.label);
                    apvals = apvals.replace(/\|/, ',' + ui.item.value + '|');
                    $('#approvals').val(apvals + ',0');
                }
                $("#seek_apprv").autocomplete("close");
            }
        });
    }

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
                    }},
                "tabdel": {name: "Delete Table", icon: "delcmp",
                    callback: function (key, options) {
                        options.$trigger.closest('table').closest("li").remove();
                    }
                },
                "tabpty": {name: "Table Properties", icon: "pty",
                    callback: function (key, options) {
                        showPopup(options.$trigger.closest('table'));
                    }}
            }
        });
});

$(function () {
    if (edit > -1)
        $.contextMenu({
            selector: '.tab, .txt, .tar, .cmb, .rad, .chk, .lbl, .img, .fil, .doc, .dbf, .hor',
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
        var elem = $(this).closest('.doc');
        var id = prev.val();
        if (id > 0) {
            var ids = deftxtAttr(elem, 'files', '');
            if (ids.search(new RegExp(',' + id + '(,|$)')) == -1) {
                elem.append(docLnk(id, prev.children('option[value="' + id + '"]').text()));
                elem.attr('files', deftxtAttr(elem, 'files', '') + ',' + id);
            }
        }
    });

    $('.del_doc_lnk').live('click', function () {
        var elem = $(this).closest('.doc');
        elem.attr('files', elem.attr('files').replace(new RegExp(',' + $(this).attr('lnk') + '(,|$)'), "$1"));
        $(this).parent().parent().remove();
    });

    $('.load_apprv').live('click', function () {
        $('#tmp_loader').load('load_approvers.php');
    });

    $('.del_aprov').live('click', function () {
        var par = $(this).parent().parent();
        var idx = par.parent().children().index(par);
        var apvals = $('#approvals').val().split('|');
        var apv = apvals[0].split(',');
        var vals = apvals[1].split(',');
        apv.splice(idx + 1, 1);
        vals.splice(idx + 1, 1);
        $('#approvals').val(apv.join(',') + '|' + vals.join(','));
        par.remove();
    });

    $('.addrow').live('click', function () {
        cloneRow($(this).closest('tr'));
    });

    $('.delrow').live('click', function () {
        var tr = $(this).closest('tr');
        if ($('tr[doc_row="1"]', $(this).closest('table')).length > 1)
            tr.remove();
    });

}

function cloneRow(tr) {
    var clone = tr.clone();
    $('.cmpbox, .tab, .txt, .tar, .cmb, .rad, .chk, .lbl, .img, .fil, .doc, .dbf, .hor', clone).each(function () {
        var id = $(this).attr('name'), num = 0;
        while ($('#' + id + '-' + num).length > 0) {
            num++;
        }
        $(this).attr('id', id + '-' + num);
    });
    $('.chk, .rad', clone).removeAttr('checked');
    $('.img[editable="1"]', clone).removeAttr('src_r').click(function () {
        showPopup($(this));
    });
    tr.after(clone);
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
        $(this).attr('checked', elem.attr($(this).attr('name')) == '1');
    });
    if (cls[0] == 'img') {
        var src_r = deftxtAttr(elem, 'src_r', '');
        $('#propWin [name="src"]').val(src_r);
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
            formData: {"id": tmp_id, "cmp": elem.attr('id'), "src": src_r},
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
            elem.attr('src_r', $(this).val());
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
    return '<div class="doclnk"><a href="javascript: void(0)" onclick="dloadFil(\'' + file + '\')">' + file + '</a></div>';
}

function docLnk(id, doc) {
    var lnk = '<div><a href="javascript: void(0)" \n\
onclick="top.leftFrame.showMod(\'Documents\', \'/EDMS/view.php?id=' + id + '\')">' + doc + '</a></div>';
    var del = edit == -4 ? '' : '<div style="float: right"><img class="del_doc_lnk" lnk="' + id + '" src="/images/cancel_.png" /></div>';
    return '<div class="doclnk">' + lnk + del + '</div>';
}

function dloadFil(filename) {
    var tm = new Date().getTime();
    $('#tmp_loader').append('<iframe class="loader" id="dloader_' + tm + '"></iframe>');
    $('#dloader_' + tm).attr('src', "download.php?filename=" + filename + '&id=' + tmp_id);
}

function approver(id, name, val) {
    var confirm = "if (confirm('This document is about to be approved by you!!?')) \n\
document.location='view.php?id=" + tmp_id + "&aprv=1'";
    var disabled = edit == -4 && net_approver == id ? ' onclick="' + confirm + '"' : ' disabled';
    var drag = edit == -4 ? '' :
            ' draggable="true" ondragstart="drag(event)" ondrop="putbefore(event)" ondragover="allowDrop(event)"';
    var del = edit == -4 ? '<input type="checkbox"' + (val == 0 ? '' : 'checked') + disabled +
            ' />' : '<img class="del_aprov" src="/images/cancel_.png" draggable="false" />';
    $('.approvals').append('<div class="ajax-file-upload-green" id="' + id + '"' + drag +
            '><div id="apprv_' + id + '">' + name + '</div><div style="float: right">' + del + '</div></div>');
    if (name.length == 0)
        $('#apprv_' + id).load('load_approver_id.php?id=' + id);
}

function load_approvers() {
    var apvals = $('#approvals').val().split('|');
    if (apvals.length == 1)
        return;
    var apv = apvals[0].split(',');
    var vals = apvals[1].split(',');
    for (var i = 1; i < apv.length; i++) {
        approver(apv[i], '', vals[i]);
    }
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

function putbefore(ev) {
    ev.preventDefault();
    var src = $('#' + ev.dataTransfer.getData("text"));
    var target = $('#' + ev.target.id);
    if (target.attr('class') != 'ajax-file-upload-green') {
        target = target.closest('.ajax-file-upload-green');
    }
    if (target && target != src) {
        target.before(src);
        var appr = '';
        var vals = '';
        src.parent().children().each(function () {
            appr += ',' + $(this).attr('id');
            vals += ',0';
        });
        $('#approvals').val(appr + '|' + vals);
        ev.stopPropagation();
    }
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
    var attrs = ' class="' + id + '"';
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
        attrs += ' id="cmp_' + idx + '"';
    }

    switch (id) {
        case 'tab':
            if (!data) {
                cells = ['', 2, 2];
                attrs += ' border="0" cellspacing="2" cellpadding="2"';
            }
            var cls = edit < 0 ? '' : '';
            html = '<table' + cls + attrs + '>' + newRows(cells[1], cells[2]) + '</table>';
            break;
        case 'txt':
            if (data) {
                attrs += ' value="' + val + '"';
            }
            html = edit == -4 ? '<div' + attrs + '></div>' :
                    '<input type="text"' + attrs + ' />';
            break;
        case 'tar':
            if (!data) {
                attrs += ' rows="5" style="width:250px"';
            }
            html = edit == -4 ? '<div' + attrs + '></div>' :
                    '<textarea' + attrs + '>' + val + '</textarea>';
            break;
        case 'cmb':
            attrs += ' typ="' + val + '"';
            if (edit == -4) {
                html = '<div' + attrs + '></div>';
            } else {
                var opts = '';
                if (edit > -2) {
                    opts = '<option value="0"' + (val == 0 ? 'selected' : '') + '>List</option>\n\
                        <option value="1"' + (val == 1 ? 'selected' : '') + '>Departments</option>';
                }
                html = '<select' + attrs + '>' + opts +
                        '</select><div class="loader" id="cmb_loader_' + idx + '"></div>';
            }
            break;
        case 'rad':
            html = '<input type="radio"' + attrs + (edit == -4 || edit == -1 ? ' disabled' : '') + ' />';
            break;
        case 'chk':
            html = '<input type="checkbox"' + attrs + (edit == -4 || edit == -1 ? ' disabled' : '') + ' />';
            break;
        case 'lbl':
            if (edit < 0) {
                html = '<span' + attrs.substr(11) + '>' + val + '</span>';
            } else {
                attrs += ' value="' + val + '"';
                html = '<input type="text"' + attrs + ' />';
            }
            break;
        case 'img':
            if (!data) {
                attrs += ' src="/images/add_image.png"';
            } else if (edit < -1 && attrs.indexOf('src="/images') == -1) {
                attrs = attrs.replace(/ src="/, ' src="templates/');
            }
            html = '<img' + attrs + ' />';
            break;
        case 'fil':
            if (edit > -2) {
                html = '<img src="/images/adddocs.png"' + attrs + ' />';
            } else {
                html = '<div' + attrs + '></div>';
            }
            break;
        case 'doc':
            if (edit > -2) {
                html = '<img src="/images/linkdocs.png"' + attrs + ' />';
            } else {
                var ctrl = edit == -4 ? '' : '<div class="lineup"><select class="doc_cmb" id="doc_cmb_' + idx +
                        '"><option value="0"></option></select><div class="ajax-file-upload-green add_but">Add</div></div>';
                html = '<div' + attrs + '>' + ctrl + '<div id="docs_' + idx + '"></div><div class="loader" id="docs_loader_' + idx + '"></div></div>';
            }
            break;
        case 'dbf':
            attrs += ' typ="' + val + '"';
            if (edit > -2) {
                html = '<select' + attrs + '>\n\
                    <option value="docname"' + (val == 'docname' ? 'selected' : '') + '>Document Name</option>\n\
                    <option value="catname"' + (val == 'catname' ? 'selected' : '') + '>Department</option>\n\
                    <option value="doc_typ"' + (val == 'doc_typ' ? 'selected' : '') + '>Document Type</option>\n\
                    <option value="docnum"' + (val == 'docnum' ? 'selected' : '') + '>Document No.</option>\n\
                    <option value="VendorName"' + (val == 'VendorName' ? 'selected' : '') + '>Document Author</option>\n\
                    <option value="approve_tm"' + (val == 'approve_tm' ? 'selected' : '') + '>Implementation Date</option>\n\
                    <option value="retention"' + (val == 'retention' ? 'selected' : '') + '>Retention Period</option>\n\
                    <option value="revision"' + (val == 'revision' ? 'selected' : '') + '>Revision Period</option>\n\
                    <option value="version"' + (val == 'version' ? 'selected' : '') + '>Revision No.</option>\n\
                    <option value="editwhy"' + (val == 'editwhy' ? 'selected' : '') + '>Reason for Change</option>\n\
                    <option value="notes"' + (val == 'notes' ? 'selected' : '') + '>Notes</option>\n\
                    </select>';
            } else {
                html = '<span' + attrs.substr(11) + '>' + dbf[val] + '</span>';
            }
            break;
        case 'hor':
            if (!data) {
                attrs += ' style="background-color:#999; height:10px"';
            }
            html = '<div' + attrs + '></div>';
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
            if (cls == 'img') {
                elem.attr('src', elem.attr('src_r'));
                elem.removeAttr('src_r');
            }
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
                case 'dbf':
                    attr = elem.val();
                    if (attr.length > 0)
                        val += attr;
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
    $('[name=cmp_idx]').val(cmp_);
}

function getDoc(container, sep, sepr) {
    var content = '', cls, val, elem, id, tab = sep != '#~#', post = true;
    $('.txt, .tar, .cmb, .rad, .chk, .img[editable="1"], .fil, .doc', container).each(function () {
        id = $(this).attr(tab ? 'name' : 'id');
        cls = $(this).attr('class');
        content += sep + id.substr(4);

        $(sep == '#~#' ? '#' + id : '[name="' + id + '"]').each(function () {
            if (!post)
                return false;
            elem = $(this);

            switch (cls) {
                case 'txt':
                case 'tar':
                    val = elem.val();
                    if (deftxtAttr(elem, 'req', '') == '1' && val.length == 0) {
                        if (cls == 'tar' && defAttr(elem, 'rich', 0) == 1) {
                            elem = elem.closest('.jqte');
                            elem.scrollParent().scrollTop(elem.offset().top);
                            elem.scrollParent().scrollLeft(elem.offset().left);
                        }
                        popmsg(elem, 'This field is required!!');
                        post = false;
                        return false;
                    } else {
                        content += sepr + val;
                    }
                    break;
                case 'cmb':
                    val = elem.val();
                    if (deftxtAttr(elem, 'req', '') == '1' && val == 0) {
                        popmsg(elem, 'Please select an option!!');
                        post = false;
                        return false;
                    } else {
                        content += sepr + val;
                    }
                    break;
                case 'rad':
                case 'chk':
                    content += sepr + (elem.is(':checked') ? 1 : 0);
                    break;
                case 'img':
                    val = deftxtAttr(elem, 'src_r', '');
                    if (deftxtAttr(elem, 'req', '') == '1' && val == '/images/add_image.png') {
                        popmsg(elem, 'This field is required!!');
                        post = false;
                        return false;
                    } else {
                        content += sepr + (val == '/images/add_image.png' ? '0' : val);
                    }
                    break;
                case 'fil':
                case 'doc':
                    val = deftxtAttr(elem, 'files', '');
                    if (deftxtAttr(elem, 'req', '') == '1' && val.length == 0) {
                        popmsg(elem, cls == 'fil' ? 'Please attach file!!' : 'Please select a document!!');
                        post = false;
                        return false;
                    } else {
                        content += sepr + val;
                    }
                    break;
            }

        });
    });

    return post ? content : false;
}

function saveDoc() {
    var content = '', tab;
    $('.tab[addrow="1"]').each(function () {
        tab = getDoc($(this).find('tr[doc_row="1"]:first'), '&~&', '%%~~%%');
        if (tab === false)
            return false;
        content += '#~#' + $(this).attr('id').substr(4) + '^~^' + tab.substr(3);
    });
    $('tr[doc_row="1"]').remove();

    tab = getDoc($('#canvas'), '#~#', '^~^');

    if (tab === false)
        return false;
    content += tab;
    $('[name=content]').val(content.substr(3));
    return true;
}

function putDoc(content, container, sep, sepr, idx) {
    var cls, elem, dat, typ, cmps, rows, id;
    var elems = content.split(sep);
    for (var i = 0; i < elems.length; i++) {
        dat = elems[i].split(sepr);
        if (dat.length < 2)
            continue;
        id = 'cmp_' + dat[0];
        elem = $(sep == '#~#' ? '#' + id : '[name="' + id + '"]', container);
        cls = elem.attr('class');

        switch (cls) {
            case 'tab':
                $('.txt, .tar, .cmb, .rad, .chk, .img[editable="1"], .fil, .doc', $('tr:last', elem)).each(function () {
                    $(this).attr('name', $(this).attr('id'));
                });

                putDoc(dat[idx], $('tr:last', elem), '&~&', '%%~~%%', 1);
                cmps = dat[idx].split('&~&');
                rows = cmps[0].split('%%~~%%');

                for (var r = 2; r < rows.length; r++) {
                    cloneRow($('tr:last', elem));
                    putDoc(dat[idx], $('tr:last', elem), '&~&', '%%~~%%', r);
                }
                break;
            case 'txt':
            case 'tar':
                if (edit == -4) {
                    elem.html(dat[idx]);
                } else {
                    elem.val(dat[idx]);
                }
                break;
            case 'cmb':
                typ = elem.attr('typ');
                if (edit == -4) {
                    if (typ == 0) {
                        var lst = elem.attr('list').split("\n");
                        elem.html(lst[dat[idx]]);
                    } else {
                        elem.load('load_dept.php?id=' + dat[idx]);
                    }
                } else {
                    if (typ == 0) {
                        elem.val(dat[idx]);
                    } else {
                        elem.attr('val', dat[idx]);
                    }
                }
                break;
            case 'rad':
            case 'chk':
                elem.attr('checked', dat[idx] == 1);
                break;
            case 'img':
                if (edit == -4) {
                    if (dat[idx] == '0') {
                        elem.remove();
                    } else {
                        elem.attr('src', dat[idx]);
                    }
                } else {
                    if (dat[idx] != '0') {
                        elem.attr('src', dat[idx]);
                        elem.attr('src_r', dat[idx]);
                    }
                }
                break;
            case 'fil':
                elem.attr('files', dat[idx]);
                if (edit == -4) {
                    var fils = dat[idx].split('|');
                    for (var j = 1; j < fils.length; j++) {
                        elem.append(filLnk(fils[j]));
                    }
                }
                break;
            case 'doc':
                elem.attr('files', dat[idx]);
                $('#docs_loader_' + dat[0]).load('load_doc_lnks.php?c=' + dat[0] + '&docs=' + dat[idx]);
                break;
        }
    }
}

function showDoc(content, print) {
    putDoc(content, $('#canvas'), '#~#', '^~^', 1);
    if (!print)
        load_approvers();
}

function richup() {
    $('.tar[rich="1"]').jqte();
    $('.tar[rich="1"]').each(function () {
        $(this).closest('.jqte').attr('id', 'jqte-' + $(this).attr('id'));
        if ($(this).attr('req') == '1')
            $(this).closest('.jqte').attr('req', '1');
    });
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

                    if (doc && elem.attr('addrow') == '1') {
                        elem.find('tr:last').attr('doc_row', '1')
                                .find('td:first')
                                .prepend('<div class="tab_grow"><div class="grow_buts">' +
                                        '<img class="addrow" src="/lib/jQuery-contextMenu/images/add.png" />' +
                                        '<img class="delrow" src="/lib/jQuery-contextMenu/images/delete.png" /></div></div>');
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
                case 'img':
                    if (edit == 1) {
                        elem.attr('src_r', elem.attr('src'));
                    } else if (doc) {
                        elem.click(function () {
                            showPopup($(this));
                        });
                    }
                    break;
                case 'fil':
                    if (doc) {
                        elem.uploadFile({
                            url: "upload.php",
                            fileName: "myfile",
                            dataType: "json",
                            maxFileCount: defAttr(elem, 'max_files', 100),
                            maxFileSize: defAttr(elem, 'max_file_size', 100) * 1024 * 1024,
                            multiple: true,
                            allowedTypes: deftxtAttr(elem, 'allowedTypes', '*'),
                            showProgress: doc,
                            showDelete: doc,
                            showDownload: true,
                            dragDrop: doc,
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
                                if (!Array.isArray(data))
                                    data = JSON.parse(data);
                                for (var i = 0; i < data.length; i++) {
                                    var fl = data[i];
                                    $.post("delete.php", {op: "delete", name: fl, id: tmp_id},
                                    function (resp, textStatus, jqXHR) {
                                        if (textStatus == "success") {
                                            elem.attr('files', elem.attr('files').replace(new RegExp('\\|' + fl), ''));
                                            alert("File Deleted");
                                        } else
                                            alert("File Delete Failed!!");
                                    });
                                }
                                pd.statusbar.hide(); //You choice.
                            },
                            downloadCallback: function (filename, pd) {
                                dloadFil(filename);
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
