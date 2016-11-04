// JavaScript Document
function set_relate() {
with (document.Frm) {
strval =  (relate[0].checked ? '1' : '0') +'-'+ (relate[1].checked ? '1' : '0') +'-'+ (relate[2].checked ? '1' : '0') 
	+'-'+ (relate[3].checked ? '1' : '0') +'-'+ (relate[4].checked ? '1' : '0');
relateval.value = (strval.match(/(1)/)) ? strval : "";
}}

function set_live() {
with (document.Frm) {
strval =  (live[0].checked ? '1' : '0') +'-'+ (live[1].checked ? '1' : '0') +'-'+ (live[2].checked ? '1' : '0') 
	+'-'+ (live[3].checked ? '1' : '0') +'-'+ (live[4].checked ? '1' : '0') +'-'+ (live[5].checked ? '1' : '0') 
	+'-'+ (live[6].checked ? '1' : '0');
livingstatus.value = (strval.match(/(1)/)) ? strval : "";
}}

function set_social() {
with (document.Frm) {
strval =  (social[0].checked ? '1' : '0') +'-'+ (social[1].checked ? '1' : '0') +'-'+ (social[2].checked ? '1' : '0') 
	+'-'+ (social[3].checked ? '1' : '0') +'-'+ (social[4].checked ? '1' : '0') +'-'+ (social[5].checked ? '1' : '0') 
	+'-'+ (social[6].checked ? '1' : '0') +'-'+ (social[7].checked ? '1' : '0');
socialset.value = (strval.match(/(1)/)) ? strval : "";
}}

function set_tv() {
with (document.Frm) {
strval =  (tv[0].checked ? '1' : '0') +'-'+ (tv[1].checked ? '1' : '0')  +'-'+ (tv[2].checked ? '1' : '0') 
	+'-'+ (tv[3].checked ? '1' : '0') +'-'+ (tv[4].checked ? '1' : '0')  +'-'+ (tv[5].checked ? '1' : '0') 
	+'-'+ (tv[6].checked ? '1' : '0') +'-'+ (tv[7].checked ? '1' : '0')  +'-'+ (tv[8].checked ? '1' : '0') 
	+'-'+ (tv[9].checked ? '1' : '0') +'-'+ (tv[10].checked ? '1' : '0') +'-'+ (tv[11].checked ? '1' : '0') 
	+'-'+ (tv[12].checked ? '1' : '0');
tvliife.value = (strval.match(/(1)/)) ? strval : "";
}}	function set_languages() {
with (document.Frm) {
strval =  (languages[0].checked ? '1' : '0')  +'-'+ (languages[1].checked ? '1' : '0')  +'-'+ (languages[2].checked ? '1' : '0') 
	+'-'+ (languages[3].checked ? '1' : '0')  +'-'+ (languages[4].checked ? '1' : '0')  +'-'+ (languages[5].checked ? '1' : '0') 
	+'-'+ (languages[6].checked ? '1' : '0')  +'-'+ (languages[7].checked ? '1' : '0')  +'-'+ (languages[8].checked ? '1' : '0') 
	+'-'+ (languages[9].checked ? '1' : '0')  +'-'+ (languages[10].checked ? '1' : '0') +'-'+ (languages[11].checked ? '1' : '0') 
	+'-'+ (languages[12].checked ? '1' : '0') +'-'+ (languages[13].checked ? '1' : '0') +'-'+ (languages[14].checked ? '1' : '0') 
	+'-'+ (languages[15].checked ? '1' : '0') +'-'+ (languages[16].checked ? '1' : '0') +'-'+ (languages[17].checked ? '1' : '0') 
	+'-'+ (languages[18].checked ? '1' : '0') +'-'+ (languages[19].checked ? '1' : '0') +'-'+ (languages[20].checked ? '1' : '0')
	+'-'+ (languages[21].checked ? '1' : '0') +'-'+ (languages[22].checked ? '1' : '0') +'-'+ (languages[23].checked ? '1' : '0') 
	+'-'+ (languages[24].checked ? '1' : '0') +'-'+ (languages[25].checked ? '1' : '0') +'-'+ (languages[26].checked ? '1' : '0') 
	+'-'+ (languages[27].checked ? '1' : '0');
languageval.value = (strval.match(/(1)/)) ? strval : "";
}}	function set_humor() {
with (document.Frm) {
strval =  (humor[0].checked ? '1' : '0') +'-'+ (humor[1].checked ? '1' : '0') +'-'+ (humor[2].checked ? '1' : '0') 
	+'-'+ (humor[3].checked ? '1' : '0') +'-'+ (humor[4].checked ? '1' : '0') +'-'+ (humor[5].checked ? '1' : '0') 
	+'-'+ (humor[6].checked ? '1' : '0') +'-'+ (humor[7].checked ? '1' : '0') +'-'+ (humor[8].checked ? '1' : '0');
humorval.value = (strval.match(/(1)/)) ? strval : "";
}}

function set_interests() {
with (document.Frm) {
strval =  (interests[0].checked ? '1' : '0')  +'-'+ (interests[1].checked ? '1' : '0')  +'-'+ (interests[2].checked ? '1' : '0') 
	+'-'+ (interests[3].checked ? '1' : '0')  +'-'+ (interests[4].checked ? '1' : '0')  +'-'+ (interests[5].checked ? '1' : '0') 
	+'-'+ (interests[6].checked ? '1' : '0')  +'-'+ (interests[7].checked ? '1' : '0')  +'-'+ (interests[8].checked ? '1' : '0') 
	+'-'+ (interests[9].checked ? '1' : '0')  +'-'+ (interests[10].checked ? '1' : '0') +'-'+ (interests[11].checked ? '1' : '0') 
	+'-'+ (interests[12].checked ? '1' : '0') +'-'+ (interests[13].checked ? '1' : '0') +'-'+ (interests[14].checked ? '1' : '0') 
	+'-'+ (interests[15].checked ? '1' : '0') +'-'+ (interests[16].checked ? '1' : '0') +'-'+ (interests[17].checked ? '1' : '0') 
	+'-'+ (interests[18].checked ? '1' : '0') +'-'+ (interests[19].checked ? '1' : '0') +'-'+ (interests[20].checked ? '1' : '0')
	+'-'+ (interests[21].checked ? '1' : '0') +'-'+ (interests[22].checked ? '1' : '0');
interestval.value = (strval.match(/(1)/)) ? strval : "";
}}	function checkdata(frm) {
with (document.Frm) {
if (agelo.value > agehi.value) {alert('Upper limit should be higher or equal to lower limit for Age.');  agehi.focus();  return false;	}
else{
	if (bodylo.value > bodyhi.value) {alert('Upper limit should be higher or equal to lower limit for Body Type.');  bodyhi.focus();  return false; }
	else{
	if (heightlo.value > heighthi.value) {alert('Upper limit should be higher or equal to lower limit for Height.');  heighthi.focus();  return false; }
	else{
	if (complexionlo.value > complexionhi.value) {alert('Upper limit should be higher or equal to lower limit for Complexion.');  complexionhi.focus();  return false; }
	else{
	if (educationlo.value > educationhi.value) {alert('Upper limit should be higher or equal to lower limit for Education.');  educationhi.focus();  return false; }
	else{
	if (incomelo.value > incomehi.value) {alert('Upper limit should be higher or equal to lower limit for Income.');  incomehi.focus();  return false; }
	else{
	return validateForm(frm,arrFormValidation);
	}}}}}}
}}
