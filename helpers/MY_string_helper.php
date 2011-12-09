<?php
function strimwidth($string, $width){ //{{{                                                                                                                        
	$res = mb_strimwidth($string, 0, $width, '' ,'UTF-8');                                                                                                         
	return $res;                                                                                                                                                   
} //}}} 
