<?php
require_once('../../scripts/init.php');

$_access = _xvar_arr_sub($_SESSION, array('accesskeys', 'Administration'));
$access = _xvar_arr_sub($_access, array('Currency'));
vetAccess('Administration', 'Currency', 'Edit');

$id = intval(_xget('id'));
//$_bottons array(new, edit, delete, print, Nav, find)
$_bottons = array(0, $access['Edit'], 0, 0, 0, 0);
//Button order: 0 first | 1 previous | 2 next | 3 last | 4 new | 5 edit | 6 save | 7 delete | 8 back | 9 refresh | 10 find | 11 print
$buttons_links = array("","","","","","","frmcurr","","view.php?id=$id","","","","");
$rec_status = 3;

$editFormAction = $_SERVER['PHP_SELF'] . set_QS();

if (_xpost("MM_update") == "frmcurr") {
	$sql = sprintf("UPDATE `%s`.`currencies` SET `currencyname`=%s, `symbol`=%s, `code`=%s, `unitname`=%s, `unitsymbol`=%s, `unitcode`=%s, `fromrate`=%s, `torate`=%s, `fullname`=%s WHERE cur_id=%s",
					   $_SESSION['DBCoy'],
                       GSQLStr(_xpost('currencyname'), "text"),
                       GSQLStr(_xpost('code'), "text"),
                       GSQLStr(_xpost('code'), "text"),
                       GSQLStr(_xpost('unitname'), "text"),
                       GSQLStr(_xpost('unitcode'), "text"),
                       GSQLStr(_xpost('unitcode'), "text"),
                       GSQLStr(_xpost('fromrate'), "int"),
                       GSQLStr(_xpost('torate'), "int"),
                       GSQLStr(_xpost('fullname'), "text"),
                       $id);
	$update = runDBQry($dbh, $sql);
	header("Location: view.php?id=$id");
	exit;
}

$sql = "SELECT * FROM `{$_SESSION['DBCoy']}`.`currencies` WHERE `cur_id`=$id";
$row_TCurr = getDBDataRow($dbh, $sql);

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
var arrFormValidation=[
["currencyname", "", 
        ["req", "Enter Currency Name"]
    ],
["code", "", 
        ["req", "Enter Currency Symbol"]
    ],
["unitname", "", 
        ["req", "Enter Unit Name"]
    ],
["unitcode", "", 
        ["req", "Enter Unit Symbol"]
    ],
["fromrate", "", 
        ["req", "Enter Dollar value"]
    ],
["torate", "", 
        ["req", "Enter Currency value"]
    ],
["fullname", "", 
        ["req", "Enter Full Name"]
]
]
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
        <td width="240" valign="top"><img src="/images/currency.jpg" alt="" width="240" height="300" /></td>
        <td valign="top"><table width="100%" border="0" cellspacing="4" cellpadding="4">
          <tr>
            <td style="height:30px; min-width:500px; background-image:url(/images/lblcurrency.png); background-repeat:no-repeat">&nbsp;</td>
          </tr>
          <tr>
            <td class="h1" height="5px"></td>
          </tr>
          <tr>
            <td><?php include('../../scripts/buttonset.php')?></td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" onsubmit="return validateFormPop(arrFormValidation)" method="post" enctype="multipart/form-data" name="frmcurr" id="frmcurr">
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
                    <td></td>
                    <td align="center"><?php echo catch_error($errors) ?></td>
                  </tr>
                  <tr>
                    <td class="titles">Name:</td>
                    <td align="left"><input name="currencyname" type="text" id="currencyname" value="<?php echo $row_TCurr['currencyname'] ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Symbol:</td>
                    <td><input name="code" type="text" id="code" value="<?php echo $row_TCurr['code'] ?>" size="5" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Unit Name:</td>
                    <td><input name="unitname" type="text" id="unitname" value="<?php echo $row_TCurr['unitname'] ?>" size="32" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Unit Symbol:</td>
                    <td><input name="unitcode" type="text" id="unitcode" value="<?php echo $row_TCurr['unitcode'] ?>" size="5" /></td>
                  </tr>
                  <tr>
                    <td class="titles">Exchange Rate:</td>
                    <td><strong>$</strong>
                      <input name="fromrate" type="text" id="fromrate" value="<?php echo $row_TCurr['fromrate'] ?>" size="10" />
                      <strong>= </strong>
                      <input name="torate" type="text" id="torate" value="<?php echo $row_TCurr['torate'] ?>" size="10" />
                      of this</td>
                  </tr>
                  <tr>
                    <td width="120" class="titles">Full Name:</td>
                    <td><input name="fullname" type="text" id="fullname" style="width:300px" value="<?php echo $row_TCurr['fullname'] ?>" /></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><?php include('../../scripts/buttonset.php')?></td>
              </tr>

            </table>
            <input type="hidden" name="MM_update" value="frmcurr" />
            <input type="hidden" name="cur_id" value="<?php echo $row_TCurr['cur_id']; ?>" />
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