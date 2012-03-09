<?php
function strimwidth($string, $width){ //{{{                                                                                                                        
	$res = mb_strimwidth($string, 0, $width, '' ,'UTF-8');                                                                                                         
	return $res;                                                                                                                                                   
} //}}} 

function full_trim($string) {
    // from http://cn2.php.net/manual/en/function.trim.php#107391
    $pattern = '[ \t\n\r\x0B\x00\x{A0}\x{AD}\x{2000}-\x{200F}\x{201F}\x{202F}\x{3000}\x{FEFF}]+';
    return preg_replace("/^$pattern|$pattern\$/u", '', $string);
}
