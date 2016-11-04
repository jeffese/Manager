<tr>
  <td><link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxGrid/codebase/dhtmlxgrid.css" />
    <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css" />
    <table border="0" cellpadding="0" cellspacing="0" style="margin:2px">
      <tr>
        <td class="bl_tl"></td>
        <td class="bl_tp"></td>
        <td class="bl_tr"></td>
      </tr>
      <tr>
        <td class="bl_lf"></td>
        <td align="left" class="bl_title"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Trial Balance</td>
              <td><div style="float:right"><img src="/images/bt_show.png" alt="" width="60" height="16" id="show_range" onclick="hideshow('range', 1, '')" style="cursor: pointer" /><img src="/images/bt_hide.png" alt="" width="60" height="16" id="hide_range" onclick="hideshow('range', 0, '');
                      unloadGrid()" style="display:none; cursor: pointer" /></div></td>
            </tr>
          </table></td>
        <td class="bl_rt"></td>
      </tr>
      <tr>
        <td class="bl_lf"></td>
        <td align="left" class="bl_center"><table border="0" cellspacing="2" cellpadding="2" id="bx_range" style="display:none">
            <tr>
              <td class="black-normal">From</td>
              <td><input name="StartDate" type="text" id="StartDate" value="" size="12" readonly="readonly" /></td>
              <td>&nbsp;</td>
              <td class="black-normal">To</td>
              <td><input name="EndDate" type="text" id="EndDate" value="" size="12" readonly="readonly" /></td>
              <td>&nbsp;</td>
              <td><img src="/images/but_lst.png" alt="" width="60" height="20" onclick="loadGrid()" style="cursor: pointer" /></td>
              <td><img src="/images/but_print.png" width="60" height="20" onclick="printLedger()" style="cursor: pointer" /></td>
              <td><img src="/images/but_export.png" width="60" height="20" onclick="exportLedger()" style="cursor: pointer" /></td>
            </tr>
          </table></td>
        <td class="bl_rt"></td>
      </tr>
      <tr>
        <td class="bl_bl"></td>
        <td class="bl_bt"></td>
        <td class="bl_br"></td>
      </tr>
    </table>
    <script type="text/javascript">var MenuLinkedBy = "AllWebMenus [4]", awmMenuName = "fieldmsg", awmBN = "766";
        awmAltUrl = "";</script>
    <script charset="UTF-8" src="/fieldmsg.js" type="text/javascript"></script>
    <script type="text/javascript">awmBuildMenu();</script>
    <script language="JavaScript1.2" src="/scripts/js/gen_validation.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.css" />
    <link rel="stylesheet" type="text/css" href="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_black.css" />
    <script>window.dhx_globalImgPath = "/lib/dhtmlxSuite/dhtmlxCalendar/codebase/imgs/";</script>
    <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
    <script type="text/javascript" src="/lib/dhtmlxSuite/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
    <script  src="/lib/dhtmlxSuite/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
    <script  src="/lib/dhtmlxSuite/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
    <script  src="/lib/dhtmlxSuite/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
    <script  src="/lib/dhtmlxSuite/dhtmlxTreeGrid/grid/dhtmlxtreegrid.js"></script>
    <div id="gridbox" width="100%" height="430px" style="background-color:white"></div>
    <div id="box" style="display:none">
      <input id="debit" type="text" style="width: 100%; border:1px solid gray" class="titles" readonly>
      <input id="credit" type="text" style="width: 100%; border:1px solid gray" class="titles" readonly></div>
    <div id="srvaction" style="display:none"></div>
    <script>
        var arrFormValidation = [
            ["StartDate", "",
                ["req", "Enter Starting date"]
            ],
            ["EndDate", "",
                ["req", "Enter End date"]
            ]
        ];

        var mCal, mCal2, mygrid;
        window.onload = function () {
            mCal = new dhtmlxCalendarObject('StartDate', true, {isYearEditable: true, isMonthEditable: true});
            mCal.setSkin('dhx_black');
            mCal2 = new dhtmlxCalendarObject('EndDate', true, {isYearEditable: true, isMonthEditable: true});
            mCal2.setSkin('dhx_black');
        }

        function loadGrid() {
            if (validateFormPop(arrFormValidation)) {
                $("#gridbox").show(800);
                mygrid = new dhtmlXGridObject('gridbox');
                mygrid.selMultiRows = true;
                mygrid.imgURL = "/lib/dhtmlxSuite/dhtmlxGrid/codebase/imgs/icons_greenfolders/";
                mygrid.setEditable(false);
                mygrid.setSkin("dhx_skyblue");
                mygrid.setHeader("Type,Client Type,Client,Date,Trans. #,Debit,Credit");
                mygrid.setInitWidths("200,70,150,70,60,100,100");
                mygrid.setColTypes("tree,ro,ro,ro,ro,ro,ro");
                mygrid.setColSorting("str,str,str,str,str,str,str");
                mygrid.setColAlign("left,center,center,center,center,right,right");
                mygrid.attachEvent("onRowDblClicked", function (rId, cInd) {
                    var pts = rId.split('-');
                    if (pts.length == 2) {
                        showTrans(pts[0], pts[1]);
                    }
                });
                mygrid.init();
                var url_vars = "?s=" + $('#StartDate').val() + "&e=" + $('#EndDate').val();
                mygrid.kidsXmlFile = "xmlledger.php" + url_vars;
                mygrid.loadXML("xmlledger.php" + url_vars, function () {
                    mygrid.attachHeader("#rspan,#rspan,#rspan,#rspan,#rspan,<div id='debit_box' style='padding-right:3px'></div>,<div id='credit_box' style='padding-right:3px'></div>");
                    $('#debit_box').append($('#debit'));
                    $('#credit_box').append($('#credit'));
                    mygrid.setSizes();
                    $('#srvaction').load('xmlBal.php' + url_vars)
                });
            }
        }

        function unloadGrid() {
            $('#box').append($('#debit'));
            $('#box').append($('#credit'));
            $("#gridbox").empty();
            $("#gridbox").hide(800);
            mygrid = null;
        }
        
        function printLedger() {
            var url_vars = "?s=" + $('#StartDate').val() + "&e=" + $('#EndDate').val();
            window.open('printledger.php' + url_vars);
        }

        function exportLedger() {
            var url_vars = "?s=" + $('#StartDate').val() + "&e=" + $('#EndDate').val();
            window.open('excelledger.php' + url_vars);
        }

        function showTrans(typ, id) {
            var lnks = [[],
            <?php
            $mnu_lnks = Array_Lnks();
            echo "['", $mnu_lnks['Accounts']['Expenses'][0], "', '", $mnu_lnks['Accounts']['Expenses'][1], "'],\n";
            echo "['", $mnu_lnks['Accounts']['Income'][0], "', '", $mnu_lnks['Accounts']['Income'][1], "'],\n";
            echo "['", $mnu_lnks['Accounts']['Journal'][0], "', '", $mnu_lnks['Accounts']['Journal'][1], "'],\n";
            echo "['", $mnu_lnks['Accounts']['Sales'][0], "', '", $mnu_lnks['Accounts']['Sales'][1], "'],\n";
            echo "['", $mnu_lnks['Stock']['Orders'][0], "', '", $mnu_lnks['Stock']['Orders'][1], "'],\n";
            echo "['", $mnu_lnks['Stock']['Returns'][0], "', '", $mnu_lnks['Stock']['Returns'][1], "']";
            ?>];
            top.leftFrame.showMod(lnks[typ][0], lnks[typ][1] + 'view.php?id=' + id);
        }

    </script></td>
</tr>
