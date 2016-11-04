<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script language="JavaScript1.2" src="/scripts/js/basicroutines.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#loadwin').load('email.php?id=<?php echo $_GET['id'] ?>&bth=<?php echo $_GET['bth'] ?>', function(){
            $('#spin').hide();
        });
    });
    
</script>
</head>

<body>
<table border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><img id="spin" src="/images/load32.gif" width="32" height="32" /></td>
  </tr>
  <tr>
    <td id="loadwin">&nbsp;</td>
  </tr>
</table>
</body>
</html>
