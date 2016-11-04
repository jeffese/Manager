<?php
require_once("$vpth/scripts/init.php");
require_once("$vpth/classifications/sql.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'Add');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array($access['Add'], 0, 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmdept","","index.php","","","","");
$rec_status = 2;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_insert") == "frmdept") {
  	$catid = getCatid("{$_SESSION['DBCoy']}.`classifications`");
	$sql = sprintf("INSERT INTO `%s`.`classifications`(`category_id`, `parent_id`, `category_name`, 
                    `catname`, `catype`, `cat_tag`, `description`, `code`) 
                    VALUES (%s, %s, %s, %s, $vtype, %s, %s, %s)",
                       $_SESSION['DBCoy'],
                       GSQLStr($catid, "text"),
                       GSQLStr(_xpost('parent_id'), "text"),
                       GSQLStr(_xpost('category_name'), "text"),
                       GSQLStr(_xpost('catname'), "text"),
                       GSQLStr(_xpost('cat_tag'), "int"),
                       GSQLStr(_xpost('description'), "text"),
                       GSQLStr(_xpost('code'), "text"));
	$insert = runDBQry($dbh, $sql);
	
	if ($insert > 0) {
		$recid = mysqli_insert_id($dbh);
                docs($vmod.DS.$vkey, $recid);

		if ($vtype == 1) {
                    $sql = sprintf("INSERT INTO `%s`.`outlets`(`OutletCode`, `OutletName`, `Dept`, 
                                `account`, `description`) 
                                VALUES (%s, %s, $recid, 2, 'Department Store')",
                                   $_SESSION['DBCoy'],
                                   GSQLStr(_xpost('code'), "text"),
                                   GSQLStr(_xpost('catname'), "text"));
                    runDBQry($dbh, $sql);
                } elseif ($vtype == 8) {
                    $sql = sprintf("INSERT INTO `%s`.`edms_num` (`doc_cat`, `prefix`, `autonum`) 
                                VALUES ($recid, %s, %s)",
                                   $_SESSION['DBCoy'],
                                   GSQLStr(_xpost('prefix'), "text"),
                                   GSQLStr(_xpost('autonum'), "int"));
                    runDBQry($dbh, $sql);
                }
		header("Location: view.php?id=$recid");
		exit;
	}
}

$sql = "SELECT catID, category_id, catname FROM `{$_SESSION['DBCoy']}`.`classifications` WHERE catype=$vtype ORDER BY `catname`";
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
        catname(catpre($('#parent_id').text()));
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
                    <td align="left"><input name="code" type="text" id="code" size="20" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><input name="category_name" type="text" id="category_name" size="32" onblur="catnamed()" /></td>
                    </tr>
                  <?php if ($isSchType) { ?>
                  <tr>
                    <td class="titles">Type:</td>
                    <td><select name="cat_tag" id="cat_tag">
                      <option value="">Select</option>
                      <?php foreach ($TDeptype as $row_TDeptype) { ?>
                      <option value="<?php echo $row_TDeptype['CategoryID'] ?>"><?php echo $row_TDeptype['Category'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td class="titles">Parent <?php echo $vnm ?>:</td>
                    <td><select name="parent_id" id="parent_id" onchange="catnamesel(this)">
                      <option value="">Select</option>
                      <?php foreach ($TPar as $row_TPar) { ?>
                      <option value="<?php echo $row_TPar['category_id'] ?>"><?php echo $row_TPar['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                    </tr>
                  <tr>
                    <td width="120" class="titles"><input type="hidden" name="catname" id="catname" value="" />Full Name:</td>
                    <td id="catview">&nbsp;</td>
                    </tr>
                  <?php if ($vtype == 8) { ?>
                  <tr>
                    <td class="titles">Prefix:</td>
                    <td><input name="prefix" type="text" id="prefix" size="20" /></td>
                    </tr>
                  <tr>
                    <td class="titles">Auto Number:</td>
                    <td><input name="autonum" type="text" id="autonum" size="20" /></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td width="120" class="titles">Description:</td>
                    <td><textarea name="description" rows="3" id="description" style="width:300px"></textarea></td>
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
                        <td><?php include "$vpth/scripts/newdoc.php" ?></td>
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
            <input type="hidden" name="MM_insert" value="frmdept" />
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
