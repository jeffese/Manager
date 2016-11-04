<?php require_once('scripts/init.php'); ?>
//----------DHTML Menu Created using AllWebMenus PRO ver 5.1-#766---------------
var awmMenuName='main';
var awmLibraryBuild=766;
var awmLibraryPath='/awmdata';
var awmImagesPath='/awmdata/main';
var awmSupported=(navigator.appName + navigator.appVersion.substring(0,1)=="Netscape5" || document.all || document.layers || navigator.userAgent.indexOf('Opera')>-1 || navigator.userAgent.indexOf('Konqueror')>-1)?1:0;
if (awmAltUrl!='' && !awmSupported) window.location.replace(awmAltUrl);
if (awmSupported){
var nua=navigator.userAgent,scriptNo=(nua.indexOf('Chrome')>-1)?2:((nua.indexOf('Safari')>-1)?7:(nua.indexOf('Gecko')>-1)?2:((document.layers)?3:((nua.indexOf('Opera')>-1)?4:((nua.indexOf('Mac')>-1)?5:1))));
var mpi=document.location,xt="";
var mpa=mpi.protocol+"//"+mpi.host;
var mpi=mpi.protocol+"//"+mpi.host+mpi.pathname;
if(scriptNo==1){oBC=document.all.tags("BASE");if(oBC && oBC.length) if(oBC[0].href) mpi=oBC[0].href;}
while (mpi.search(/\\/)>-1) mpi=mpi.replace("\\","/");
mpi=mpi.substring(0,mpi.lastIndexOf("/")+1);
var e=document.getElementsByTagName("SCRIPT");
for (var i=0;i<e.length;i++){if (e[i].src){if (e[i].src.indexOf(awmMenuName+".js")!=-1){xt=e[i].src.split("/");if (xt[xt.length-1]==awmMenuName+".js"){xt=e[i].src.substring(0,e[i].src.length-awmMenuName.length-3);if (e[i].src.indexOf("://")!=-1){mpi=xt;}else{if(xt.substring(0,1)=="/")mpi=mpa+xt; else mpi+=xt;}}}}}
    while (mpi.search(/\/\.\//)>-1) {mpi=mpi.replace("/./","/");}
    var awmMenuPath=mpi.substring(0,mpi.length-1);
    while (awmMenuPath.search("'")>-1) {awmMenuPath=awmMenuPath.replace("'","%27");}
document.write("<SCRIPT SRC='"+awmMenuPath+awmLibraryPath+"/awmlib"+scriptNo+".js'><\/SCRIPT>");
    var n=null;
    awmzindex=1000;
}

var awmImageName='';
var awmPosID='divmnu';
var awmSubmenusFrame='';
var awmSubmenusFrameOffset;
var awmOptimize=0;
var awmHash='';
var awmUseTrs=0;
var awmSepr=["0","","",""];
function awmBuildMenu(){
    if (awmSupported){
        awmImagesColl=["SlidingFemale-tile.jpg",7,32,"SlidingFemale-itemLeft.jpg",8,32,"SlidingFemale-itemRight.jpg",8,32,"SlidingFemale-indicator.png",9,32,"SlidingFemale-indicatorUp.png",9,32,"SlidingFemale-itemTileOver.jpg",5,32,"SlidingFemale-itemTilePressed.jpg",5,32,"SlidingFemale-itemLeftOver.jpg",8,32,"SlidingFemale-itemLeftPressed.jpg",8,32,"SlidingFemale-itemRightOver.jpg",8,32,"SlidingFemale-itemRightPressed.jpg",8,32,"hassubmenu.gif",4,7,"SlidingFemale-subitemTile.jpg",20,26,"SlidingFemale-subitemTileOver.jpg",20,26,"SlidingFemale-subitemLeft.jpg",34,26,"SlidingFemale-subitemLeftOver.jpg",34,26,"SlidingFemale-subitemRight.jpg",34,26,"SlidingFemale-subitemRightOver.jpg",34,26,"logout.png",20,20];
        awmCreateCSS(1,2,1,'#FFFFFF',n,0,'bold 12px sans-serif','underline','none','0','#000000','0px 0px 0px 0',0);
        awmCreateCSS(0,2,1,'#FFFFFF',n,0,'bold 12px sans-serif','underline','none','0','#000000','0px 0px 0px 0',0);
        awmCreateCSS(1,2,1,'#FFFFFF',n,0,'bold 14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
        awmCreateCSS(0,2,1,'#FFFFFF',n,0,'bold 14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
        awmCreateCSS(0,1,0,n,n,n,n,n,'none','0','#000000',0,0);
        awmCreateCSS(1,2,0,'#FFFFFF',n,0,'bold 12px Tahoma',n,'none','0','#000000','0px 15px 0px 15',0);
        awmCreateCSS(0,2,0,'#FFFFFF',n,5,'bold 12px Tahoma',n,'none','0','#000000','0px 15px 0px 15',0);
        awmCreateCSS(0,2,0,'#FFFFFF',n,6,'bold 12px Tahoma',n,'none','0','#000000','0px 15px 0px 15',0);
        awmCreateCSS(0,1,0,n,n,n,n,n,'solid','1','#808080',0,0);
        awmCreateCSS(1,2,0,'#000000',n,12,'11px Tahoma',n,'none','0','#000000','0px 15px 0px 35',1);
        awmCreateCSS(0,2,0,'#000000',n,13,'11px Tahoma',n,'none','0','#000000','0px 15px 0px 35',1);
        awmCreateCSS(1,2,0,'#000000',n,12,'11px Tahoma',n,'none','0','#000000','0px 15px 0px 35',0);
        awmCreateCSS(0,2,0,'#000000',n,13,'11px Tahoma',n,'none','0','#000000','0px 15px 0px 35',0);
        var s0=awmCreateMenu(0,0,0,0,1,0,0,0,0,10,10,0,0,4,0,1,1,n,n,100,1,0,0,0,170,-1,1,200,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,1,0,1,0);
        it=s0.addItemWithImages(5,6,7," &nbsp;My Account &nbsp;",n,n,"",n,n,n,3,3,3,3,3,4,"",n,n,n,n,n,0,0,2,1,7,8,2,9,10,1,1,1,0,0,n,n,n);
        var s1=it.addSubmenu(0,0,-1,1,3,0,0,8,0,1,0,n,n,100,0,2,0,-1,1,200,200,1,3,"0,0,0",0,"1,0,0,0,0,0,0,0,1");
        it=s1.addItemWithImages(9,10,10,"Change Password",n,n,"",n,n,n,3,3,3,n,n,n,"/change_pass.php",n,n,n,"/change_pass.php","top",0,0,2,14,15,15,16,17,17,1,1,1,0,0,n,n,n);
        it=s1.addItemWithImages(9,10,10,"Log out",n,n,"",18,18,18,3,3,3,n,n,n,"/logout.php",n,n,n,"/logout.php","top",0,0,2,14,15,15,16,17,17,1,1,1,0,0,n,n,n);
        <?php

        $mnu_lnks = Array_Lnks();
        foreach ($_SESSION['accesskeys'] as $mod => $usr_permit) {
            if ($usr_permit['View'] == 1) {
                ?>
        it=s0.addItemWithImages(5,6,7," &nbsp;<?php echo $mod ?>&nbsp;",n,n,"",n,n,n,3,3,3,3,3,4,"",n,n,n,n,n,0,0,2,1,7,8,2,9,10,1,1,1,0,0,n,n,n);
        var s1=it.addSubmenu(0,0,-1,1,3,0,0,8,0,1,0,n,n,100,0,3,0,-1,1,200,200,1,3,"0,0,0",0,"1,0,0,0,0,0,0,0,1");
                <?php
                foreach ($usr_permit as $sub => $permits) {
                    if (is_array($permits) && $permits['View'] == 1 && isset($mnu_lnks[$mod][$sub])) {
                        $nam = $mnu_lnks[$mod][$sub][0];
                        $lnk = $mnu_lnks[$mod][$sub][1];
                        ?>
        it=s1.addItemWithImages(11,12,12,"<?php echo $nam ?>",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,"showMod('<?php echo $nam ?>', '<?php echo $lnk ?>')",n,n,0,0,2,14,15,15,16,17,17,1,1,1,0,0,n,n,n);
                        <?php
                    }
                }
            }
        }
        ?>
    it=s0.addItemWithImages(2,3,3,"",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,1,1,1,2,2,2,0,0,0,0,0,n,n,n);
    s0.pm.buildMenu();
}
}