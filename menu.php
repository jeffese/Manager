<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="/lib/jquery/jquery-1.4.2.min.js"></script>
<style type="text/css">
<!--
body {
        background-color: #333333;
        margin-left: 0px;
        margin-top: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
}
-->
</style>
<link href="/css/text.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
    
    //var Pans = new Array('', '', '', '', '', '', '', '', '', '');
    var pan = 0;
    
    function showMod(mod, url) {
	pan++;
	parent.mainFrame.tabbar.addTab("tab"+pan,mod,"100%");
	parent.mainFrame.tabbar.setContentHref("tab"+pan, url);
	parent.mainFrame.tabbar.setTabActive("tab"+pan);
    }
    
    function killMod(caller) {
        if (caller) {
            if (typeof caller.CRS_NAME === 'undefined') {
                caller.GB_hide();
                return;
            }
        }
	parent.mainFrame.tabbar.removeTab(parent.mainFrame.tabbar.getActiveTab(), true);
	pan--;
	parent.mainFrame.tabbar.setTabActive("tab"+pan);
    }
</script>
</head>
<body>
<!-- DO NOT MOVE! The following AllWebMenus code must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR main ******** -->
<script type='text/javascript'>var MenuLinkedBy='AllWebMenus [4]',awmMenuName='main',awmBN='DW';awmAltUrl='';</script>
<script charset='UTF-8' src='mainmenu.php' type='text/javascript'></script>
<script type='text/javascript'>awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR main ******** -->
<div id='divmnu' style="width:100%">&nbsp;</div>
</body>
</html>
