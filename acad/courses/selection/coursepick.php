<?php
require_once('../../../scripts/init.php');
require_once('../sql.php');

$crs = _xget('crs');

$sql = "SELECT course_id, CONCAT(course_name, ' {', course_code, '}') as `course`
FROM `{$_SESSION['DBCoy']}`.`sch_courses`
WHERE {$_SESSION['elect_arm']} REGEXP CONCAT('^(', `classes`, ')$') AND course_id NOT REGEXP CONCAT('^($crs)$')";
$TCourses = getDBData($dbh, $sql);

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
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../course.js"></script>
<!--[if lte IE 6]><script type="text/javascript" src="/scripts/supersleight-min.js"></script><![endif]-->
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellpadding="4" cellspacing="4">
              <tr>
                <td style="height:30px; min-width:500px; background-image:url(/images/<?php echo COURSE=='Subject' ? 'lblsubjects' : 'lblcourses' ?>.png); background-repeat:no-repeat">&nbsp;</td>
              </tr>
              <tr>
                <td class="h1" height="5px"></td>
              </tr>
              </table>
<table border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td valign="top"><table border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td colspan="4"><table cellpadding="4" cellspacing="0">
                      <tr>
                        <td bgcolor="#333333"><span class="boldwhite1">Type</span></td>
                        <td bgcolor="#CCCCCC"><input name="grp" type="radio" id="grp_0" onclick="setGroup(1)" value="1" checked="checked" /></td>
                        <td bgcolor="#CCCCCC" class="titles">Single</td>
                        <td bgcolor="#CCCCCC">&nbsp;</td>
                        <td bgcolor="#CCCCCC"><input type="radio" name="grp" value="2" id="grp_1" onclick="setGroup(2)" /></td>
                        <td bgcolor="#CCCCCC" class="titles">Group</td>
                      </tr>
                    </table></td>
                    </tr>
                  <tr>
                    <td colspan="4" align="center"><input name="elective" type="hidden" id="elective" value="<?php echo _xget('typ') ?>" />
                      <a href="javascript: void(0)" onclick="addCourses()"><img src="/images/but_add.png" width="50" height="20" /></a></td>
                    </tr>
                  <tr>
                    <td colspan="4"><table width="100%" border="0" cellpadding="2" cellspacing="0" style="display:none" id="grp_box">
                      <tr>
                        <td bgcolor="#000033" class="boldwhite1">Group Settings</td>
                      </tr>
                      <tr>
                        <td bgcolor="#CFD0FE"><table cellpadding="2" cellspacing="0">
                          <tr>
                            <td bgcolor="#333333"><span class="boldwhite1">Choice</span></td>
                            <td><input name="choice" type="radio" id="choice_0" value="1" checked="checked" onclick="setSelOpt(1)" /></td>
                            <td class="black-normal"><strong><strong>Alternatives</strong></strong></td>
                            <td class="black-normal"><input type="radio" name="choice" value="2" id="choice_1" onclick="setSelOpt(2)" /></td>
                            <td class="black-normal"><strong>Selections</strong></td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td bgcolor="#CFD0FE"><table border="0" cellspacing="0" cellpadding="2" style="display:none" id="sel_box">
                          <tr>
                            <td bgcolor="#333333"><span class="boldwhite1">Selection Limits</span></td>
                            <td>Min:</td>
                            <td><input name="min" type="text" id="min" value="0" size="3" onblur="numme(this, 0)" /></td>
                            <td>Max:</td>
                            <td><input name="max" type="text" id="max" value="0" size="3" onblur="numme(this, 0)" /></td>
                            </tr>
                          <tr>
                            <td bgcolor="#333333"><span class="boldwhite1">GP Limits</span></td>
                            <td>Min:</td>
                            <td><input name="min_gp" type="text" id="min_gp" value="0" size="3" onblur="numme(this, 0)" /></td>
                            <td>Max:</td>
                            <td><input name="max_gp" type="text" id="max_gp" value="0" size="3" onblur="numme(this, 0)" /></td>
                            </tr>
                          </table></td>
                      </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td colspan="4" bgcolor="#003366" class="boldwhite1"><?php echo COURSE ?>s</td>
                  </tr>
                  <tr>
                    <td colspan="4" bgcolor="#CFE7F3" id="coursewin">&nbsp;</td>
                    </tr>
                </table></td>
                <td valign="top"><select name="lstcourse" size="20" class="titles" id="lstcourse" onchange="setCourse(this)">
                  <?php foreach ($TCourses as $row_TCourses) { ?>
                  <option value="<?php echo $row_TCourses['course_id'] ?>"><?php echo $row_TCourses['course'] ?></option>
                  <?php } ?>
                </select></td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="4" cellpadding="4">

          </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
	lst = document.getElementById('lstcourse');
});
</script>
</body>
</html>