<?php
require_once("$vpth/scripts/init.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print ~ 12 List
$buttons_links = array("","","","","","","","","","","frmdept","","index.php");
$rec_status = 0;

$editFormAction = $_SERVER['PHP_SELF'];

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
<title>Find dept</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
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
        <td width="240" valign="top"><img src="/images/<?php echo $vcat ?>.jpg" id="subpx" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lbl<?php echo $vcat ?>.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          </table>
          <form action="index.php" method="post" name="frmdept" id="frmdept">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">Find</td>
              </tr>
              <tr>
                <td><?php include("$vpth/scripts/buttonset.php")?></td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td class="titles">Code:</td>
                    <td align="left"><input name="code" type="text" id="code" size="20" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><input name="category_name" type="text" id="category_name" size="32" /></td>
                  </tr>
                  <tr>
                    <td width="120" valign="top" class="titles">Full Name:</td>
                    <td align="left"><input name="catname" type="text" id="catname" value="" size="32" /></td>
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
                    <td class="titles">Parent Dept:</td>
                    <td><select name="parent_id" id="parent_id">
                      <option value="" selected="selected"></option>
                      <?php foreach ($TPar as $row_TPar) { ?>
                      <option value="<?php echo $row_TPar['category_id'] ?>"><?php echo $row_TPar['catname'] ?></option>
                      <?php } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td class="titles">Description:</td>
                    <td><span class="TabbedPanelsContent">
                      <textarea name="description" id="description" style="width:300px"></textarea>
                      </span></td>
                  </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
</tr>

            </table>
            <input type="hidden" name="MM_insert" value="frmdept" />
            <?php include("$vpth/scripts/buttonset.php")?>
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