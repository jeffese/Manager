//----------DHTML Menu Created using AllWebMenus PRO ver 5.1-#766---------------
//D:\wamp\www\Manager\leftmenu.awm
var awmMenuName='mnulft';
var awmLibraryBuild=766;
var awmLibraryPath='/awmdata';
var awmImagesPath='/awmdata/mnulft';
var awmSupported=(navigator.appName + navigator.appVersion.substring(0,1)=="Netscape5" || document.all || document.layers || navigator.userAgent.indexOf('Opera')>-1 || navigator.userAgent.indexOf('Konqueror')>-1)?1:0;
if (awmAltUrl!='' && !awmSupported) window.location.replace(awmAltUrl);
if (awmSupported){
var nua=navigator.userAgent,scriptNo=(nua.indexOf('Chrome')>-1)?2:((nua.indexOf('Safari')>-1)?7:(nua.indexOf('Gecko')>-1)?2:((document.layers)?3:((nua.indexOf('Opera')>-1)?4:((nua.indexOf('Mac')>-1)?5:1))));
var mpi=document.location,xt="";
var mpa=mpi.protocol+"//"+mpi.host;
 mpi=mpi.protocol+"//"+mpi.host+mpi.pathname;
if(scriptNo==1){oBC=document.all.tags("BASE");if(oBC && oBC.length) if(oBC[0].href) mpi=oBC[0].href;}
while (mpi.search(/\\/)>-1) mpi=mpi.replace("\\","/");
mpi=mpi.substring(0,mpi.lastIndexOf("/")+1);
var e=document.getElementsByTagName("SCRIPT");
for (var i=0;i<e.length;i++){if (e[i].src){if (e[i].src.indexOf(awmMenuName+".js")!=-1){xt=e[i].src.split("/");if (xt[xt.length-1]==awmMenuName+".js"){xt=e[i].src.substring(0,e[i].src.length-awmMenuName.length-3);if (e[i].src.indexOf("://")!=-1){mpi=xt;}else{if(xt.substring(0,1)=="/")mpi=mpa+xt; else mpi+=xt;}}}}}
while (mpi.search(/\/\.\//)>-1) {mpi=mpi.replace("/./","/");}
var awmMenuPath=mpa;
while (awmMenuPath.search("'")>-1) {awmMenuPath=awmMenuPath.replace("'","%27");}
document.write("<SCRIPT SRC='"+awmMenuPath+awmLibraryPath+"/awmlib"+scriptNo+".js'><\/SCRIPT>");
var n=null;
awmzindex=1000;
}

var awmImageName='';
var awmPosID='mnulft';
var awmSubmenusFrame='';
var awmSubmenusFrameOffset;
var awmOptimize=0;
var awmHash='';
var awmUseTrs=0;
var awmSepr=["0","","",""];
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["has-submenu.gif",9,9,"has-submenuOver.gif",9,9,"button-tile.jpg",25,23,"buttonOver-tile.jpg",25,23,"button-left.jpg",25,23,"buttonOver-left.jpg",25,23,"button-right.jpg",25,23,"buttonOver-right.jpg",25,23];
awmCreateCSS(0,1,0,n,n,n,n,n,'none','0','#000000',0,0);
awmCreateCSS(1,2,0,'#A40000',n,2,'bold 11px Verdana',n,'none','0','#000000','0px 10px 0px 10',1);
awmCreateCSS(0,2,0,'#FFFFFF',n,3,'bold 11px Verdana',n,'none','0','#000000','0px 10px 0px 10',1);
var s0=awmCreateMenu(0,0,0,0,1,0,0,0,0,10,10,0,0,0,1,1,0,n,n,100,1,0,0,0,0,-1,1,200,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0);
it=s0.addItemWithImages(1,2,2," &nbsp;Programs &nbsp;",n,n,"",n,n,n,3,3,3,n,n,n,"/acad/programs/index.php",n,n,n,"/acad/programs/index.php",n,0,0,2,4,5,5,6,7,7,1,1,1,0,0,n,n,n);
it=s0.addItemWithImages(1,2,2," &nbsp;Certificates &nbsp;",n,n,"",n,n,n,3,3,3,n,n,n,"/acad/programs/certificates/index.php",n,n,n,"/acad/programs/certificates/index.php",n,0,0,2,4,5,5,6,7,7,1,1,1,0,0,n,n,n);
it=s0.addItemWithImages(1,2,2," &nbsp;Grade Systems &nbsp;",n,n,"",n,n,n,3,3,3,n,n,n,"/acad/programs/grades/index.php",n,n,n,"/acad/programs/grades/index.php",n,0,0,2,4,5,5,6,7,7,1,1,1,0,0,n,n,n);
s0.pm.buildMenu();
}}
