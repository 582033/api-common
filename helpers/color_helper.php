<?php
function rgb2hex($R, $G, $B) {
	$R=dechex($R);
	If (strlen($R)<2)
		$R='0'.$R;

	$G=dechex($G);
	If (strlen($G)<2)
		$G='0'.$G;

	$B=dechex($B);
	If (strlen($B)<2)
		$B='0'.$B;
	return '#' . $R . $G . $B;
}

function hex2rgb($hex){
	if (substr($hex,0,1) == "#")
		$hex = substr($hex,1);

	$R = substr($hex,0,2);
	$G = substr($hex,2,2);
	$B = substr($hex,4,2);

	$R = hexdec($R);
	$G = hexdec($G);
	$B = hexdec($B);

	return array($R, $G, $B);
}
