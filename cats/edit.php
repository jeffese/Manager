<?php
require_once("$vpth/scripts/init.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","Frm","","view.php?id=$id","","","","");
$rec_status = 3;

if (_xpost("MM_update") == "Frm") {
  $sql = sprintf("UPDATE `{$_SESSION['DBCoy']}`.`status` SET `code`=%s, Category=%s, 
                    Description=%s, `par`=%s, InUse=%s WHERE CategoryID=%s",
                       GSQLStr(_xpost('code'), "text"),
                       GSQLStr(_xpost('Category'), "text"),
                       GSQLStr(_xpost('Description'), "text"),
                       GSQLStr(_xpost('par'), "int"),
                       _xpostchk('InUse'),
                       $id);
	runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`status` WHERE `status`.CategoryID=$id";
$row_TCat = getDBDataRow($dbh, $sql);

if (isset($vPar)) {
    $sql = "SELECT $parid, $parname FROM `{$_SESSION['DBCoy']}`.$partab $parWhere ORDER BY $parname";
    $TPar = getDBData($dbh, $sql);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" type="text/javascript">
    var arrFormValidation=[
        ["Category", "", 
            ["req", "Enter Name"]]
      <?php if (isset($vCode)) { ?>,
        ["code", "", 
            ["req", "Enter Code"]]
      <?php } ?>
      <?php if (isset($vPar)) { ?>,
        ["par", "", 
            ["req", "Select <?php echo $vPar ?>"]]
      <?php } ?>
    ];
</script>
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
        <form id="Frm" name="Frm" method="post" action="" onsubmit="return validateFormPop(arrFormValidation)">
        <table border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td></td>
            <td align="center"><?php echo catch_error($errors) ?></td>
          </tr>
      <tr>
        <td class="titles">Name:</td>
        <td align="left"><input type="text" name="Category" style="width:300px" value="<?php echo $row_TCat['Category'] ?>" /></td>
      </tr>
      <?php if (isset($vCode)) { ?>
      <tr>
        <td class="titles">Code:</td>
        <td align="left"><input type="text" name="code" style="width:100px" value="<?php echo $row_TCat['code'] ?>" /></td>
      </tr>
      <?php } ?>
      <?php if (isset($vPar)) { ?>
      <tr>
        <td class="titles"><?php echo $vPar ?>:</td>
        <td><select name="par"<?php echo $parEdit == 0 ? " disabled=\"disabled\"" : ""; ?>>
          <option value="">Select</option>
          <?php foreach ($TPar as $row_TPar) { ?>
          <option value="<?php echo $row_TPar[$parid] ?>" <?php if (!(strcmp($row_TCat['par'], $row_TPar[$parid]))) { echo "selected=\"selected\""; }?>><?php echo $row_TPar[$parname] ?></option>
          <?php } ?>
          </select></td>
      </tr>
      <?php } ?>
      <tr>
        <td class="titles">In Use</td>
        <td align="left"><input type="checkbox" name="InUse"<?php if ($row_TCat['InUse'] == 1) {
                echo " checked=\"checked\"";
            } ?> /></td>
      </tr>
      <tr>
        <td class="titles">Notes:</td>
        <td align="left"><textarea name="Description" style="width:300px" rows="4"><?php echo $row_TCat['Description'] ?></textarea></td>
      </tr>
      <tr>
        <td colspan="2"><input type="hidden" name="MM_update" value="Frm" /><?php include("$vpth/scripts/buttonset.php"); ?></td>
        </tr>
    </table>
   	</form></td>
  </tr>
</table>
</body>
</html>