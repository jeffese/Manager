<?php require_once('init.php'); ?>
<?php

$tmpf = file_get_contents(BASEPATH . 'templates/main.html');
$tmpw = file_get_contents(BASEPATH . 'templates/win.html');

/*$tags = array("#xmyname", "#xaccount", "#xlogme", "#sugmessage");
$vals   = array($xmyname, $xacc, $xlogme, $sugmessage);
$tmpf = str_replace($tags, $vals, $tmpf);
$tmpw = str_replace($tags, $vals, $tmpw);
$tmpl = split('<!-- ###xtag### -->', $tmpf);
$tmpt = split('<!-- ###xtag### -->', $tmpw);*/

function ob_postprocess($buffer) {
	$buffer = trim(preg_replace('/\s+/', ' ', $buffer));
	$acronyms['html'] = 'hypertext markup language';
	$acronyms['css'] = 'cascading style sheets';
	foreach($acronyms as $acronym => $meaning) {
		$buffer = preg_replace('/(\b' . $acronym . '\b)(?=[^>]*<)/i', '<acronym title="' . $meaning . '">\\1</acronym>', $buffer);
	}
	$buffer = str_replace(' & ', ' &amp; ', $buffer);
	return $buffer;
}

?>