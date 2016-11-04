//----------DHTML Menu Created using AllWebMenus PRO ver 5.1-#766---------------
//D:\wamp\www\Exood.com\httpdocs\fieldmsg.awm
var awmMenuName='fieldmsg';
var awmLibraryBuild=766;
var awmLibraryPath='/awmdata';
var awmImagesPath='/awmdata/fieldmsg';
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
if (typeof(exoodmsg)=='undefined') var exoodmsg="Text Message";
var n=null;
awmzindex=2;
}

var awmImageName='';
var awmPosID='';
var awmSubmenusFrame='';
var awmSubmenusFrameOffset;
var awmOptimize=1;
var awmHash='';
var awmComboFix=2;
var awmUseTrs=0;
var awmSepr=["0","","",""];
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["fieldmsgbox_03.png",6,70,"fieldmsgbox_01.png",48,70,"fieldmsgbox_05.png",13,70];
awmCreateCSS(0,1,0,n,n,n,n,n,'none','0','#000000',0,0);
awmCreateCSS(1,2,0,'#FF0000',n,0,'11px Verdana, Arial, Helvetica, sans-serif',n,'none','0','#000000','3px 14px 3px 50',0);
awmCreateCSS(0,2,0,'#FF0000',n,0,'11px Verdana, Arial, Helvetica, sans-serif',n,'none','0','#000000','3px 14px 3px 50',0);
var s0=awmCreateMenu(1,0,0,1,2,0,0,0,0,10,10,0,0,0,1,1,1,n,n,100,0,0,10,10,0,-1,1,200,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,1,0,0,0);
it=s0.addItemWithImages(1,2,n,"<div id='frmmsg'></div>",n,"","",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,300,70,2,1,1,1,2,2,2,1,1,1,0,0,n,n,n);
s0.pm.buildMenu();
}}
