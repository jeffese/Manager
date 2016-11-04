<?php
require_once('../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'EDMS'));
$access = _xvar_arr_sub($_access, array('Documents'));
vetAccess('EDMS', 'Documents', 'Print');

$id = intval(_xget('id'));
$sql = "SELECT `edms`.* FROM `{$_SESSION['DBCoy']}`.`edms` WHERE `doc_id`=$id";
$row_TDocs = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var GB_ROOT_DIR = "http://<?php echo WEBSITE ?>/lib/greybox/";</script>
<script type="text/javascript" src="/lib/greybox/AJS.js"></script>
<script type="text/javascript" src="/lib/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/lib/greybox/gb_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/greybox/gb_styles.css" />
<link rel="stylesheet" type="text/css" href="../css/canvas.css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="/lib/jquery/jquery-migrate-1.2.1.min.js"></script>
<link href="/lib/jQuery-Upload-File/uploadfile.css" rel="stylesheet">
<script src="/lib/jQuery-Upload-File/jquery.uploadfile.min.js"></script>
<script language="JavaScript1.2" src="templates/script.js" type="text/javascript"></script>
<script language="JavaScript1.2" type="text/javascript">
    <?php include 'load_dbf.php'; ?>
    var edit = -4, tmp_id = <?php echo $id ?>;

    function loadTmpl() {
        load_Tmpl(<?php echo $row_TDocs['tmpl_id'] ?>);
    }

    function show_Doc() {
        showDoc("<?php echo addcslashes($row_TDocs['content'], '\"'); ?>", true);
    }
    
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" id="canvas"><ul id="cmp_list">
    </ul></td>
  </tr>
  <tr>
    <td id="tmp_loader">&nbsp;</td>
  </tr>
</table>
<script type="text/javascript">
</script>
</body>
</html>