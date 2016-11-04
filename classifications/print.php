<?php
require_once("$vpth/scripts/init.php");

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', $vmod));
$access = _xvar_arr_sub($_access, array($vkey));
vetAccess($vmod, $vkey, 'Print');

$id = intval(_xget('id'));
$sql = "SELECT `classifications`.*, `par`.`catname` AS parcat, Category 
FROM `{$_SESSION['DBCoy']}`.`classifications` 
INNER JOIN `{$_SESSION['DBCoy']}`.`classifications` `par` ON `classifications`.`parent_id`=`par`.`category_id`
LEFT JOIN `{$_SESSION['DBCoy']}`.`status`                 ON `classifications`.`cat_tag`=`status`.`CategoryID`  
LEFT JOIN `{$_SESSION['DBCoy']}`.`edms_num`               ON `classifications`.`catID`=`edms_num`.`doc_cat`
WHERE `classifications`.`catID`=".$id;
$row_TDept = getDBDataRow($dbh, $sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td><img src="<?php echo COYPIX_DIR, $_SESSION['coyid']."/xxpix.jpg" ?>" /></td>
        <td><span class="coytxt"><?php echo $_SESSION['COY']['CoyName'] ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/lbl<?php echo $vcat ?>.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td class="titles">Code:</td>
                    <td align="left"><?php echo $row_TDept['code'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><?php echo $row_TDept['category_name'] ?></td>
                  </tr>
                  <?php if (isset($vcode) && $_SESSION['accesskeys']['Academics']['View'] != -1) { ?>
                  <tr>
                    <td class="titles">Type:</td>
                    <td align="left"><?php echo $row_TDept['Category'] ?></td>
                  </tr>
                  <?php } ?>
                  <tr>
                    <td class="titles">Parent Dept:</td>
                    <td><?php echo $row_TDept['parcat'] ?></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Full Name:</td>
                    <td><?php echo $row_TDept['catname'] ?></td>
                  </tr>
			<?php if ($vtype == 8) { ?>
                  <tr>
                    <td class="titles">Prefix:</td>
                    <td><?php echo $row_TDept['prefix'] ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Auto Number:</td>
                    <td><?php echo $row_TDept['autonum'] ?></td>
                  </tr>
                      <?php } ?>
                  <tr>
                    <td width="120" class="titles">Description:</td>
                    <td><?php echo $row_TDept['description'] ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td></td>
              </tr>

            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">

            </table></td>
        </tr>
        <tr>
          <td align="center"><span class="blacktxt"><?php echo $_SESSION['COY']['Address'], ', ', $_SESSION['COY']['City'], ' ', $_SESSION['COY']['State']   ?><br />
          <?php echo $_SESSION['COY']['Web'], ' ', $_SESSION['COY']['Email'] ?> </span></td>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
	print();
});
</script>
</body>
</html>