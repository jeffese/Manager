// JavaScript Document<!--  Fireworks script by kurt.grigg@virgin.net (http://website.lineone.net/~kurt.grigg/javascript/)// Courtesy of SimplytheBest.net - http://simplythebest.net/scripts/
ns=(document.layers)?1:0;
amount=14;
if (ns){
for (i=0; i < amount; i++)
document.write("<LAYER NAME='nsstars"+i+"' LEFT=0 TOP=0 BGCOLOR='#FFFFF0' CLIP='0,0,1,1'></LAYER>");
}
else{
document.write("<div id='ieCov' style='position:absolute;top:0px;left:0px'>");
document.write("<div style='position:relative'>");
for (i=0; i < amount; i++)
document.write("<div id='iestars' style='position:absolute;top:0px;left:0px;width:1;height:1;background:#ffffff;font-size:1'></div>");
document.write("</div></div>");
}
Clrs=new Array('ff0000','00ff00','ffffff','ff00ff','ffa500','ffff00','00ff00','ffffff','ff00ff')
sClrs=new Array('ffa500','00ff00','FFAAFF','fff000','fffffF')
Xpos=300;
Ypos=150;
initialStarColor='00ff00';
step=5;
currStep=0;
explosionSize=120;
function Fireworks(){
var WinHeight=(document.layers)?window.innerHeight-100:window.document.body.clientHeight-100;var WinWidth=(document.layers)?window.innerWidth-100:window.document.body.clientWidth-100;
var Yscroll=(document.layers)?window.pageYOffset:document.body.scrollTop;
for (i=0; i < amount; i++){
var layer=(document.layers)?document.layers["nsstars"+i]:iestars[i].style; 
var randCol=Math.round(Math.random()*8);
var randSz=Math.round(Math.random()*2);
layer.top = Ypos + explosionSize*Math.sin((currStep+i*5)/3)*Math.sin(currStep/100)
layer.left= Xpos + explosionSize*Math.cos((currStep+i*5)/3)*Math.sin(currStep/100)
if (currStep < 110){
 if (ns){layer.bgColor=initialStarColor;layer.clip.width=1;layer.clip.height=1}
 else{layer.background=initialStarColor;layer.width=1;layer.height=1;layer.fontSize=1}
 }
else{
 if (ns){layer.bgColor=Clrs[randCol];layer.clip.width=randSz;layer.clip.height=randSz}
 else{layer.background=Clrs[randCol];layer.width=randSz;layer.height=randSz;layer.fontSize=randSz}
 }
}
if (currStep > 220) 
{
 currStep=0;
 Ypos = 50+Math.round(Math.random()*WinHeight)+Yscroll;
 Xpos = 50+Math.round(Math.random()*WinWidth);
 for (i=0; i < sClrs.length; i++)
  {
  var newIcol=Math.round(Math.random()*i);
  }
initialStarColor=sClrs[newIcol];
explosionSize=Math.round(80*Math.random()+100);
}
currStep+=step;
setTimeout("Fireworks()",20);
}
Fireworks();
// -->