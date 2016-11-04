<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Clients'));
$access = _xvar_arr_sub($_access, array('Customers'));
vetAccess('Clients', 'Customers', 'View');

//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, 0, 0, 0, 0, 1, 0, 0, 1);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print ~ 12 List
$buttons_links = array("","","","","","","","","../kiosk.php","","frmAsset.submit()","","");
$rec_status = 1;

$editFormAction = $_SERVER['PHP_SELF'];

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.colors ORDER BY colorname";
$colors = getDBData($dbh, $sql);

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`licenses`";
$TLicense = getDBData($dbh, $sql);

$sql = "SELECT `CatID`, `category_name` FROM `{$_SESSION['DBCoy']}`.`auto_categories` WHERE `parent_id`='0' ORDER BY `category_name`";
$TAutoType = getDBData($dbh, $sql);

$_SESSION['flow'] = 3;
$_SESSION['new_veh'] = array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Find Staff</title>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script language="JavaScript1.2" src="auto_cats.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="autos.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="models.jgz" type="text/javascript"></script>
<script type="text/javascript" src="/scripts/js/set.js"></script>
<script type="text/javascript">
var arrFormValidation=[
    ["year_prod2", "if=$('#year_prod1').val()>$('#year_prod2').val()",
        ["req", "'from Year' should be less than 'to Year'"]
    ]
];

window.onload = function() {
    setContent();
    
    $.each(auto_cats, function (i, item) {
        $('#vtype').append($('<option>', {
            value: i,
            text: item
        }));
    });
}
window.onresize = function() {
    setContent();
}

</script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>

<body>
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="fieldmsg",awmBN="766";awmAltUrl="";</script>
<script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
<script type="text/javascript">awmBuildMenu();</script>
<div id="content">
<div id="content">
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="frametopleft">&nbsp;</td>
      <td class="frametop">&nbsp;</td>
      <td class="frametopright">&nbsp;</td>
    </tr>
    <tr>
      <td class="frameleft">&nbsp;</td>
      <td bgcolor="#FFFFFF">
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><img src="/images/vehicles.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(../images/lblfindvehicle.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          </table>
          <form action="index.php" method="post" name="frmAsset" id="frmAsset">
            <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>
              <tr>
                <td><table border="0" cellpadding="4" cellspacing="4">
                  <tr>
                    <td class="titles">Category:</td>
                    <td align="left"><table width="0" border="0" cellpadding="1" cellspacing="1" class="blacktxt">
                      <tr>
                        <td><input name="Category" type="radio" id="Category_1" value="29" size="32" /></td>
                        <td>Private</td>
                        <td>&nbsp;</td>
                        <td><input name="Category" type="radio" id="Category_2" value="30" size="32" /></td>
                        <td>Commercial</td>
                        <td>&nbsp;</td>
                        <td><input name="Category" type="radio" id="Category_3" value="31" size="32" /></td>
                        <td>Government</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">License Type:</td>
                    <td align="left"><select name="lictype" id="lictype" onchange="licch()">
                      <option value="" selected="selected">..</option>
                      <?php foreach ($TLicense as $row_TLicense) { ?>
                      <option value="<?php echo $row_TLicense['lic_typ'] ?>" cats="<?php echo $row_TLicense['cats'] ?>"><?php echo $row_TLicense['license'] ?></option>
                      <?php } ?>
                    </select></td>
                    </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Vehicle Type:</td>
                    <td align="left"><select name="vtype" id="vtype" onchange="typech()">
                    </select></td>
                    </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Body Style:</td>
                    <td align="left"><select name="bstyle" id="bstyle">
                      <option value="" selected="selected">..</option>
                    </select></td>
                    </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Brand:</td>
                    <td align="left"><select name="brandid" id="brandid" onchange="brandch()">
                      <option value="" selected="selected">..</option>
                    </select></td>
                    </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Model:</td>
                    <td align="left"><select name="serieid" id="serieid">
                      <option value="" selected="selected">..</option>
                      </select>
                      <input type="text" name="Model" size="30" /></td>
                    </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Year:</td>
                    <td align="left"><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td>from</td>
                          <td><select name="year_prod1" id="year_prod1">
                            <option value="" selected="selected">..</option>
                          </select></td>
                          <td>to</td>
                          <td><select name="year_prod2" id="year_prod2">
                            <option value="" selected="selected">..</option>
                          </select></td>
                        </tr>
                  </table>
                      <script type="text/javascript">
					var yr;
					Today = new Date();
					yr = Today.getFullYear() + 2;
					i=1;
					while (yr>=1900) {
						document.getElementById("year_prod1").options[i] = new Option(yr, yr, false, false);
						document.getElementById("year_prod2").options[i] = new Option(yr, yr, false, false);
						yr--;
						i++;
					}
                                </script></td>
                    </tr>
                  <tr>
                    <td class="titles">Color:</td>
                    <td align="left"><select name="colour">
                      <option value="">..</option>
                      <?php foreach ($colors as $row_colors) { ?>
                      <option value="<?php echo $row_colors['colorid'] ?>" style="background-color: <?php echo $row_colors['colorcode'] ?>;color: Black;"><?php echo $row_colors['colorname'] ?></option>
                      <?php } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Vehicle ID:</td>
                    <td align="left"><input type="text" name="AssetID" size="32" /></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Registration No.:</td>
                    <td align="left"><input type="text" name="licenceno" size="32" /></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Chasis No.:</td>
                    <td align="left"><input type="text" name="modelno" size="32" /></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Engine No.:</td>
                    <td align="left"><input type="text" name="partno" size="32" /></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Insurance No.:</td>
                    <td align="left"><input type="text" name="insuranceno" size="32" /></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" class="titles">Description:</td>
                    <td align="left"><textarea name="description" id="description" style="width:300px"></textarea></td>
                  </tr>
                  </table></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
  </tr>
              
              </table>
            <input type="hidden" name="MM_insert" value="frmAsset" />
          </form></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table></td>
      <td class="frameright">&nbsp;</td>
    </tr>
    <tr>
      <td class="framebotleft">&nbsp;</td>
      <td valign="bottom" class="framebot"><span class="greytxt">Copyright © 2010 <a href="http://www.electricavenuetech.co" target="_blank" class="greytxt">Electric Avenue Technolgies</a>. All rights reserved.</span></td>
      <td class="framebotright">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>
</html>
