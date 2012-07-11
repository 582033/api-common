<?php
if ( ! function_exists('show_error_json')) {
	function show_error_json($errcode, $errmsg) { // {{{
		$data = array(
			'errcode' => $errcode, 
			'errmsg' => $errmsg,
		);
		header("Content-type: application/json");
		echo json_encode($data);
		exit;
	} //}}}
}

if ( ! function_exists('show_error_text')) {
	function show_error_text($status_code, $message="") { //{{{
		header("HTTP/1.1 $status_code");
		if ($message) echo $message;
		exit;
	} //}}}
}
