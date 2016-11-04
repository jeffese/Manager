<?php
require_once("$vpth/scripts/init.php");
require_once("$vpth/classifications/sql.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmdept","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();
$doc_shelf = $vmod.DS.$vkey;
$doc_id = $id;

if (_xpost("MM_update") == "frmdept") {
    $catid = getCatid("{$_SESSION['DBCoy']}.`classifications`");
    $oldcat = GSQLStr(_xpost('category_id'), "textv");
    $catn = GSQLStr(_xpost('catname'), "textv");
    $oldcatn = GSQLStr(_xpost('oldcatname'), "textv");
    $cascade = $oldcat != $catid || $catn != $oldcatn;
    $oldlen = strlen($oldcat);
    $catlen = strlen($oldcatn);

    if ($cascade) {

        $sql = "INSERT INTO `{$_SESSION['DBCoy']}`.`classifications` (`category_id`, `parent_id`) 
                    VALUES ('{$catid}-tmp', '1')";
        $insert = runDBQry($dbh, $sql);
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`classifications` SET 
                `tmp_par`=`parent_id`, `parent_id`='{$catid}-tmp'
                WHERE `parent_id`='$oldcat' OR `parent_id` LIKE '$oldcat-%'";
        runDBQry($dbh, $sql);
    }
    
    $sql = sprintf("UPDATE `%s`.`classifications` SET category_id=%s, parent_id=%s, category_name=%s, 
        catname=%s, cat_tag=%s, description=%s, code=%s WHERE catID=%s", //
            $_SESSION['DBCoy'], //
            "'$catid'", //
            GSQLStr(_xpost('parent_id'), "text"), //
            GSQLStr(_xpost('category_name'), "text"), //
            "'$catn'", //
            GSQLStr(_xpost('cat_tag'), "int"), //
            GSQLStr(_xpost('description'), "text"), //
            GSQLStr(_xpost('code'), "text"), //
            $id);
    $update = runDBQry($dbh, $sql);
    docs($doc_shelf, $doc_id);

    if ($vtype == 8) {
        $sql = sprintf("UPDATE `%s`.`edms_num` SET `prefix`=%s,`autonum`=%s WHERE `doc_cat`=%s", //
                $_SESSION['DBCoy'], //
                GSQLStr(_xpost('prefix'), "text"), //
                GSQLStr(_xpost('autonum'), "int"), //
                $id);
        runDBQry($dbh, $sql);
    }
        
    if ($update > 0 && $cascade) {
        $tmplen = $oldlen + 4;
        $sql = "UPDATE `{$_SESSION['DBCoy']}`.`classifications` SET 
               `parent_id`=INSERT(`tmp_par`, 1, $oldlen, '$catid'),
                `category_id`=INSERT(`category_id`, 1, $oldlen, '$catid'),
                `catname`=INSERT(`catname`, 1, $catlen, '$catn') 
                WHERE `parent_id`='{$catid}-tmp'";
        runDBQry($dbh, $sql);

        $sql = "DELETE FROM `{$_SESSION['DBCoy']}`.`classifications`
            WHERE `category_id`='{$catid}-tmp'";
        runDBQry($dbh, $sql);
    }
    header("Location: view.php?id=$id");
    exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`classifications` 
        LEFT JOIN `{$_SESSION['DBCoy']}`.`edms_num` ON `classifications`.`catID`=`edms_num`.`doc_cat`
        WHERE `catID`=$id";
$row_TDept = getDBDataRow($dbh, $sql);

$sql = "SELECT catID, category_id, catname FROM `{$_SESSION['DBCoy']}`.`classifications` WHERE catype=$vtype AND catID<>$id ORDER BY `catname`";
$TPar = getDBData($dbh, $sql);

$isSchType = isset($vcode) && $_SESSION['accesskeys']['Academics']['View'] != -1;
if ($isSchType) {
    $TDeptype = getCat('dept');
}

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
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=
        [
			["code", "", 
				["req", "Enter <?php echo $vnm ?> Code"]],
			["category_name", "", 
				["req", "Enter <?php echo $vnm ?> Name"]],
			<?php if ($isSchType) { ?>
			["cat_tag", "", 
				["req", "Select <?php echo $vnm ?> Type"]],
			<?php } ?>
			<?php if ($vtype == 8) { ?>
			["prefix", "", 
				["req", "Enter Prefix"]],
			["autonum", "", 
				["req", "Enter Auto Number"], 
				["num", "'Auto Number' should be a number!"]],
			<?php } ?>
			["parent_id", "", 
				["req", "Select Parent <?php echo $vnm ?>"]]
    ];
    
    function catnamed() {
        catnamesel(document.getElementById('parent_id'));
    }
    
    function catnamesel(par) {
        catname(catpre(par.options[par.selectedIndex].text));
    }
    
    function catpre(str) {
        if (str == 'Departments') {
            return '';
        } else {
            return str + ' &gt; ';
        }
    }
    
    function catname(str) {
        str += trimme($('#category_name').val());
        $('#catview').html(str);
        $('#catname').val(str);
    }
	
$(document).ready(function() {
    if (window.innerWidth<720) {
		$('#subpx').hide();
	}
});
</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><img src="/images/<?php echo $vcat ?>.jpg" id="subpx" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lbl<?php echo $vcat ?>.png); background-repeat:no-repeat">&nbsp;</td>
            </tr>
          <tr>
            <td class="h1" height="5px"></td>
            </tr>
          <tr>
            <td><?php include("$vpth/scripts/buttonset.php")?></td>
            </tr>
          </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmdept" id="frmdept">
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
                    <td class="titles">Code:</td>
                    <td align="left"><input name="code" type="text" id="code" value="<?php echo $row_TDept['code'] ?>" size="20" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><input name="category_name" type="text" id="category_name" value="<?php echo $row_TDept['category_name'] ?>" size="32" onblur="catnamed()" /></td>
                    </tr>
                  <?php if ($isSchType) { ?>
                  <tr>
                    <td class="titles">Type:</td>
                    <td><select name="cat_tag" id="cat_tag">
                      <option value="">Select</option>
                      <?php foreach ($TDeptype as $row_TDeptype) { ?>
                      <option value="<?php echo $row_TDeptype['CategoryID'] ?>" <?php if (!(strcmp($row_TDept['cat_tag'], $row_TDeptype['CategoryID']))) { echo "selected=\"selected\""; }?>><?php echo $row_TDeptype['Category'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td class="titles">Parent <?php echo $vnm ?>:</td>
                    <td><select name="parent_id" id="parent_id" onchange="catnamesel(this)">
                      <option value="">Select</option>
                      <?php foreach ($TPar as $row_TPar) { ?>
                      <option value="<?php echo $row_TPar['category_id'] ?>" <?php if (!(strcmp($row_TDept['parent_id'], $row_TPar['category_id']))) { echo "selected=\"selected\""; }?>><?php echo $row_TPar['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles"><input type="hidden" name="catname" id="catname" value="<?php echo $row_TDept['catname'] ?>" />Full Name:</td>
                    <td id="catview"><?php echo $row_TDept['catname'] ?></td>
                    </tr>
                  <?php if ($vtype == 8) { ?>
                  <tr>
                    <td class="titles">Prefix:</td>
                    <td><input name="prefix" type="text" id="prefix" value="<?php echo $row_TDept['prefix'] ?>" size="20" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Auto Number:</td>
                    <td><input name="autonum" type="text" id="autonum" value="<?php echo $row_TDept['autonum'] ?>" size="20" /></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td width="120" class="titles">Description:</td>
                    <td><textarea name="description" rows="3" id="description" style="width:300px"><?php echo $row_TDept['description'] ?></textarea></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
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
                        <td><?php include "$vpth/scripts/editdoc.php" ?></td>
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
              <tr>
                <td><?php include("$vpth/scripts/buttonset.php")?></td>
                </tr>

              </table>
            <input type="hidden" name="MM_update" value="frmdept" />
            <input type="hidden" name="catID" value="<?php echo $row_TDept['catID']; ?>" />
            <input type="hidden" name="old_par" value="<?php echo $row_TDept['parent_id']; ?>" />
            <input type="hidden" name="category_id" value="<?php echo $row_TDept['category_id']; ?>" />
            <input type="hidden" name="oldcatname" id="oldcatname" value="<?php echo $row_TDept['catname'] ?>" />
            </form>
          <table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>            </tr>
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