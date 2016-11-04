var appvdt = eng_date(<?php setFld('approve_tm') ?>);
var dbf = {docname:<?php setFld('docname') ?>,
catname:<?php setFld('catname') ?>,
doc_typ:<?php setFld('doc_typ') ?>,
docnum:<?php setFld('docnum') ?>,
VendorName:<?php setFld('VendorName') ?>,
approve_tm: appvdt.substr(-4) == '1899' ? ' --- ' : appvdt,
retention:<?php notFld('retention', '0', ' days') ?>,
revision:<?php notFld('revision', '0', ' days') ?>,
version:<?php setFld('version') ?>,
editwhy:<?php setFld('editwhy') ?>,
notes:<?php setFld('notes') ?>
};
<?php

function getFld($fld) {
    global $row_TDocs;
    return addcslashes(str_replace("\n", '\\n\\', $row_TDocs[$fld]), '"');
}

function setFld($fld) {
    echo '"' . getFld($fld) . '"';
}

function notFld($fld, $not, $sfx = '') {
    $val = getFld($fld);
    echo '"' . ($val == $not ? ' --- ' : $val . $sfx) . '"';
}